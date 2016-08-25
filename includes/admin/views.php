<?php
/**
 * View admin functions.
 *
 * @since 1.21.0
 * @package Strong_Testimonials
 */


/**
 * View list page.
 *
 * @since 1.21.0
 */
function wpmtst_views_admin() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	?>
	<div class="wrap wpmtst2">

		<?php
		if ( isset( $_REQUEST['changes-undone'] ) ) {
			$message = __( 'Changes undone.', 'strong-testimonials' );
		} elseif ( isset( $_REQUEST['defaults-restored'] ) ) {
			$message = __( 'Defaults restored.', 'strong-testimonials' );
		} elseif ( isset( $_REQUEST['view-saved'] ) ) {
			$message = __( 'View saved.', 'strong-testimonials' );
		} elseif( isset( $_REQUEST['view-deleted'] ) ) {
			$message = __( 'View deleted.', 'strong-testimonials' );
		} else {
			$message = '';
		}

		if ( $message ) {
			printf( '<div class="notice is-dismissible updated"><p>%s</p></div>', $message );
		}

		if ( isset( $_REQUEST['error'] ) ) {

			echo '<h2>' . __( 'Edit View', 'strong-testimonials' ) . '</h2>';
			$message = sprintf( wp_kses( __( 'An error occurred. Please <a href="%s" target="_blank">open a support ticket</a>.', 'strong-testimonials' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) ) ), esc_url( 'https://www.wpmission.com/submit-ticket/' ) );
			printf( '<div class="error strong-view-error"><p>%s</p></div>', $message );

		}
		elseif ( isset( $_REQUEST['action'] ) ) {

			if ( 'edit' == $_REQUEST['action'] && isset( $_REQUEST['id'] ) ) {
				wpmtst_view_settings( $_REQUEST['action'], $_REQUEST['id'] );
			}
			elseif ( 'duplicate' == $_REQUEST['action'] && isset( $_REQUEST['id'] ) ) {
				wpmtst_view_settings( $_REQUEST['action'], $_REQUEST['id'] );
			}
			elseif ( 'add' == $_REQUEST['action'] ) {
				wpmtst_view_settings( $_REQUEST['action'] );
			}
			else {
				echo "<p>Invalid request. Please try again.</p>";
			}

		}
		else {

			// View list
			?>
			<h2>
				<?php _e( 'Views', 'strong-testimonials' ); ?>
				<a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=add' ); ?>" class="add-new-h2">Add New</a>
			</h2>
			<div class="intro">
				<p><?php _e( 'A View can display your testimonials, create a slideshow, or show a testimonial submission form.<br>Add it to a page with a shortcode or add it to a sidebar with a widget.', 'strong-testimonials' ); ?></p>
			</div>
			<?php
			$views = wpmtst_get_views();
			$views_table = new Strong_Views_List_Table();
			$views_table->prepare_list( wpmtst_unserialize_views( $views ) );
			$views_table->display();

		}
		?>
	</div><!-- .wrap -->
	<?php
}


/**
 * An individual view settings page.
 *
 * @since 1.21.0
 *
 * @param string $action
 * @param null   $view_id
 */
function wpmtst_view_settings( $action = '', $view_id = null ) {

	if ( ( 'edit' == $action || 'duplicate' == $action ) && ! $view_id ) return;

	global $view, $strong_templates;
	add_thickbox();

	$screen = get_current_screen();
	$url    = $screen->parent_file;

	$fields     = wpmtst_get_custom_fields();
	$all_fields = wpmtst_get_all_fields();

	$order_list = wpmtst_get_order_list();

	$slideshow_effect_options = array(
		'none'       => 'no transition effect',
		'fade'       => 'fade',
		'fadeout'    => 'fade out',
		'scrollHorz' => 'scroll horizontally',
	);

	$slideshow_nav_options = wpmtst_get_slideshow_nav_options();

	$testimonials_list = get_posts( array(
		'orderby'          => 'post_date',
		'order'            => 'ASC',
		'post_type'        => 'wpm-testimonial',
		'post_status'      => 'publish',
		'posts_per_page'   => -1,
		'suppress_filters' => true,
	) );

	$category_list = wpmtst_get_category_list();

	/**
	 * Show category filter if necessary.
	 *
	 * @since 2.2.0
	 */
	if ( count( $category_list ) > 5 ) {
		wp_enqueue_script( 'wpmtst-view-category-filter-script' );
	}

	$pages_list   = wpmtst_get_pages();
	$posts_list   = wpmtst_get_posts();
	$view_options = get_option( 'wpmtst_view_options' );
	$default_view = get_option( 'wpmtst_view_default' );

	if ( 'edit' == $action ) {
		$view_array = wpmtst_get_view( $view_id );
		$view       = unserialize( $view_array['value'] );
		$view_name  = $view_array['name'];
	}
	elseif ( 'duplicate' == $action ) {
		$view_array = wpmtst_get_view( $view_id );
		$view       = unserialize( $view_array['value'] );
		$view_id    = 0;
		$view_name  = $view_array['name'] . ' - COPY';
	}
	else {
		$view_id   = 1;
		$view      = $default_view;
		$view_name = 'new';
	}

	// Deselect title & thumbnail if not in field group
	$has_title_field     = false;
	$has_thumbnail_field = false;
	foreach( $all_fields as $key => $field ) {
		if ( 'post_title' == $field['name'] ) {
			$has_title_field = true;
		}
		if ( 'featured_image' == $field['name'] ) {
			$has_thumbnail_field = true;
		}
	}
	if ( !$has_title_field ) {
		$view['title'] = false;
	}
	if ( !$has_thumbnail_field ) {
		$view['thumbnail'] = false;
	}

	// Select default template if necessary
	if ( !$view['template'] ) {
		if ( 'form' == $view['mode'] )
			$view['template'] = 'default:form';
		else
			$view['template'] = 'default:content';
	}

	$view['nav']     = explode( ',', str_replace( ' ', '', $view['nav'] ) );
	$view_cats_array = apply_filters( 'wpmtst_l10n_cats', explode( ',', $view['category'] ) );

	// Assemble list of templates
	$templates      = $strong_templates->get_templates( array( 'content', 'widget' ) );
	$form_templates = $strong_templates->get_templates( 'form' );

	$group = strtok( $view['template'], ':' );
	$type  = strtok( ':' );

	if ( 'form' == $type )
		$template_found = in_array( $view['template'], array_keys( $form_templates ) );
	else
		$template_found = in_array( $view['template'], array_keys( $templates ) );

	// Get list of image sizes
	$image_sizes = wpmtst_get_image_sizes();

	?>
	<h2>
		<?php 'edit' == $action ? _e( 'Edit View', 'strong-testimonials' ) : _e( 'Add View', 'strong-testimonials' ); ?>
		<a href="<?php echo $url; ?>&page=testimonial-views&action=add" class="add-new-h2">Add New</a>
	</h2>

	<p><a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views' ); ?>">Return to list</a></p>

	<form id="wpmtst-views-form" method="post" action="<?php echo get_admin_url() . 'admin-post.php'; ?>" autocomplete="off">

		<?php wp_nonce_field( 'view_form_submit', 'view_form_nonce', true, true ); ?>

		<input type="hidden" name="action" value="view_<?php echo $action; ?>_form">
		<input type="hidden" name="view[id]" value="<?php echo $view_id; ?>">
		<input type="hidden" name="view_original_mode" value="<?php echo $view['mode']; ?>">
		<input type="hidden" name="view[data][_form_id]" value="<?php echo $view['form_id']; ?>">

		<div class="view-info">
			<div class="form-view-name">
				<?php
				/**
				 * Using htmlspecialchars and stripslashes on $view_name to handle quotes, etc. in database.
				 * @since 2.11.14
				 */
				?>
				<span class="title">Name:</span><input type="text" id="view-name" class="view-name" name="view[name]"
					   value="<?php echo htmlspecialchars( stripslashes( $view_name ) ); ?>" tabindex="1">
			</div>
		</div>

		<?php
		// avoiding the tab character before the shortcode for better copy-n-paste
		if ( 'edit' == $action )
			$shortcode = '<span class="saved">[testimonial_view id=' . $view_id . ']</span>';
		else
			$shortcode = '<span class="unsaved">' . _x( 'will be available after you save this', 'The shortcode for a new View.', 'strong-testimonials' ) . '</span>';
		?>

		<div class="view-info">
			<div class="form-view-shortcode"><span class="title">Shortcode:</span><?php echo $shortcode; ?></div>
		</div>

		<?php
		include( 'views/mode.php' );

		// TODO Generify both hook and include
		do_action( 'wpmtst_view_editor_before_group_select' );
		include( 'views/group-select.php' );

		do_action( 'wpmtst_view_editor_before_group_slideshow' );
		include( 'views/group-slideshow.php' );

		do_action( 'wpmtst_view_editor_before_group_fields' );
		include( 'views/group-fields.php' );

		do_action( 'wpmtst_view_editor_before_group_form' );
		include( 'views/group-form.php' );

		do_action( 'wpmtst_view_editor_before_group_extra' );
		include( 'views/group-extra.php' );

		do_action( 'wpmtst_view_editor_before_group_style' );
		include( 'views/group-style.php' );

		do_action( 'wpmtst_view_editor_before_group_general' );
		include( 'views/group-general.php' );

		do_action( 'wpmtst_view_editor_after_groups' );
		?>

		<p class="wpmtst-submit">
			<?php submit_button( '', 'primary', 'submit', false ); ?>
			<?php submit_button( __( 'Undo Changes', 'strong-testimonials' ), 'secondary', 'reset', false ); ?>
			<?php submit_button( __( 'Restore Defaults', 'strong-testimonials' ), 'secondary', 'restore-defaults', false ); ?>
		</p>

	</form>
	<?php
}


/**
 * -----------------
 * POST-REDIRECT-GET
 * -----------------
 */

/**
 * Process form POST after editing.
 *
 * Thanks http://stackoverflow.com/a/20003981/51600
 *
 * @since 1.21.0
 */
function wpmtst_view_edit_form() {

	$goback = wp_get_referer();

	if ( ! empty( $_POST ) && check_admin_referer( 'view_form_submit', 'view_form_nonce' ) ) {

		$view_id    = $_POST['view']['id'];
		$view_name  = wpmtst_validate_view_name( $_POST['view']['name'], $view_id );

		if ( isset( $_POST['reset'] ) ) {

			// Undo changes
			$goback = add_query_arg( 'changes-undone', true, $goback );

		}
		elseif ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			$default_view = get_option( 'wpmtst_view_default' );

			$view = array(
				'id'   => $view_id,
				'name' => $view_name,
				'data' => $default_view
			);
			$success = wpmtst_save_view( $view ); // num_rows

			if ( $success ) {
				$goback = add_query_arg( 'defaults-restored', true, $goback );
			}
			else {
				$goback = add_query_arg( 'error', true, $goback );
			}


		}
		else {

			// Sanitize & validate
			$view = array(
				'id'   => $view_id,
				'name' => $view_name,
				'data' => wpmtst_sanitize_view( $_POST['view']['data'] )
			);
			$success = wpmtst_save_view( $view ); // num_rows

			if ( $success ) {
				$goback = add_query_arg( 'view-saved', true, $goback );
			}
			else {
				$goback = add_query_arg( 'error', true, $goback );
			}

		}

	}
	else {
		$goback = add_query_arg( 'error', true, $goback );
	}

	wp_redirect( $goback );
	exit;

}
add_action( 'admin_post_view_edit_form', 'wpmtst_view_edit_form' );


/**
 * Process form POST after adding.
 *
 * @since 1.21.0
 */
function wpmtst_view_add_form() {

	$goback = wp_get_referer();

	if ( ! empty( $_POST ) && check_admin_referer( 'view_form_submit', 'view_form_nonce' ) ) {

		$view_id   = 0;
		$view_name = wpmtst_validate_view_name( $_POST['view']['name'], $view_id );

		if ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			$default_view = get_option( 'wpmtst_view_default' );

			$view = array(
				'id'   => $view_id,
				'name' => $view_name,
				'data' => $default_view,
			);
			$success = wpmtst_save_view( $view, 'add' ); // num_rows

			$query_arg = 'defaults-restored';

		}
		else {

			// Sanitize & validate
			$view = array(
				'id'   => 0,
				'name' => $view_name,
				'data' => wpmtst_sanitize_view( $_POST['view']['data'] )
			);
			$success = wpmtst_save_view( $view, 'add' ); // new id

			$query_arg = 'view-saved';

		}

		$goback = remove_query_arg( 'action', $goback );
		if ( $success ) {
			$goback = add_query_arg( array( 'action' => 'edit', 'id' => $success, $query_arg => true ), $goback );
		}
		else {
			$goback = add_query_arg( 'error', true, $goback );
		}

	}
	else {
		$goback = add_query_arg( 'error', true, $goback );
	}

	wp_redirect( $goback );
	exit;

}
add_action( 'admin_post_view_add_form', 'wpmtst_view_add_form' );
add_action( 'admin_post_view_duplicate_form', 'wpmtst_view_add_form' );


/**
 * --------------
 * VIEW FUNCTIONS
 * --------------
 */

/**
 * Fetch pages, bypass filters.
 *
 * @since 2.10.0
 *
 * @return array|null|object
 */
function wpmtst_get_pages() {
	global $wpdb;
	$query = "SELECT * FROM $wpdb->posts WHERE post_type = 'page' AND post_status = 'publish' ORDER BY post_title ASC";

	$pages = $wpdb->get_results( $query );

	return $pages;
}


/**
 * Fetch pages, bypass filters.
 *
 * @since 2.10.0
 *
 * @return array|null|object
 */
function wpmtst_get_posts() {
	global $wpdb;
	$query = "SELECT * FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_title ASC";

	$posts = $wpdb->get_results( $query );

	return $posts;
}


/**
 * The display order options.
 *
 * @since 2.1.0
 * @todo DRY
 *
 * @return array
 */
function wpmtst_get_order_list() {
	return array(
		'random'     => _x( 'random', 'display order', 'strong-testimonials' ),
		'menu_order' => _x( 'menu order', 'display order', 'strong-testimonials' ),
		'newest'     => _x( 'newest first', 'display order', 'strong-testimonials' ),
		'oldest'     => _x( 'oldest first', 'display order', 'strong-testimonials' ),
	);
}


/**
 * Slideshow navigation options.
 *
 * @since 2.11.0
 * @todo Assemble list from component directories just like we do for templates.
 */
function wpmtst_get_slideshow_nav_options() {
	return array(
		'simple'   => _x( 'simple', 'slideshow navigation option', 'strong-testimonials' ),
		'buttons1' => _x( 'buttons 1', 'slideshow navigation option', 'strong-testimonials' ),
		'buttons2' => _x( 'buttons 2', 'slideshow navigation option', 'strong-testimonials' ),
		'indexed'  => _x( 'indexed', 'slideshow navigation option', 'strong-testimonials' ),
	);
}


/**
 * Check for forced options.
 *
 * @since 1.25.0
 */
function wpmtst_force_check() {
	global $strong_templates;
	$atts = array( 'template' => $_REQUEST['template'] );
	$force = $strong_templates->get_template_attr( $atts, 'force', false );
	echo $force;
	die();
}
add_action( 'wp_ajax_wpmtst_force_check', 'wpmtst_force_check' );


/**
 * [Add New Field] Ajax receiver
 *
 * @since 1.21.0
 */
function wpmtst_view_add_field_function() {
	$new_key = (int) $_REQUEST['key'];
	$empty_field = array( 'field' => '', 'type' => 'text', 'class' => '' );
	wpmtst_view_field_inputs( $new_key, $empty_field, true );
	die();
}
add_action( 'wp_ajax_wpmtst_view_add_field', 'wpmtst_view_add_field_function' );


/**
 * [Field Type: Link] Ajax receiver
 *
 * @since 1.21.0
 */
function wpmtst_view_add_field_link_function() {
	$key         = (int) $_REQUEST['key'];
	$field_name  = $_REQUEST['fieldName'];
	$type        = $_REQUEST['fieldType'];
	$empty_field = array( 'url' => '', 'link_text' => '', 'new_tab' => true );
	wpmtst_view_field_link( $key, $field_name, $type, $empty_field );
	die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_link', 'wpmtst_view_add_field_link_function' );


/**
 * [Field name change] Ajax receiver
 *
 * @since 1.24.0
 */
function wpmtst_view_get_label_function() {
	$field = array( 'field' => $_REQUEST['name'] );
	$label = wpmtst_get_field_label( $field );
	echo $label;
	die();
}
add_action( 'wp_ajax_wpmtst_view_get_label', 'wpmtst_view_get_label_function' );


/**
 * [Field Type: Date] Ajax receiver
 *
 * @since 1.21.0
 */
function wpmtst_view_add_field_date_function() {
	$key = (int) $_REQUEST['key'];
	$empty_field = array( 'format' => '' );
	wpmtst_view_field_date( $key, $empty_field );
	die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_date', 'wpmtst_view_add_field_date_function' );


/**
 * [Mode Change: Set Default Template] Ajax receiver
 *
 * @since 1.21.0
 */
function wpmtst_get_default_template_function() {
	$mode = $_REQUEST['mode'];
	$view_options = get_option( 'wpmtst_view_options' );
	$default_template = $view_options['default_templates'][$mode];
	echo $default_template;
	die();
}
// add_action( 'wp_ajax_wpmtst_get_default_template', 'wpmtst_get_default_template_function' );


/**
 * Show a single client field's inputs.
 *
 * @since 1.21.0
 *
 * @param $key
 * @param $field
 * @param bool $adding
 */
function wpmtst_view_field_inputs( $key, $field, $adding = false ) {
	$custom_fields = wpmtst_get_custom_fields();

	// the date is a special field
	$custom_fields[] = array(
		'name'        => 'post_date',
		'input_type'  => 'date',
		'type'        => 'date',
		'record_type' => 'builtin',
	);

	// TODO Move this to view defaults option.
	$types = array(
		'text'  => __( 'text', 'strong-testimonials' ),
		'link'  => __( 'link with field', 'strong-testimonials' ),  // the original link type
		'link2' => __( 'link (must be URL)', 'strong-testimonials' ),  // @since 1.24.0
		'date'  => __( 'date', 'strong-testimonials' )
	);

	$allowed = array( 'custom', 'builtin' );
	?>
	<tr class="field2" id="field-<?php echo $key; ?>">

		<?php // Name ?>
		<td class="field-name">
			<label>
				<select name="view[data][client_section][<?php echo $key; ?>][field]">
					<option value=""></option>
					<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
						<?php if ( in_array( $field2['record_type'], $allowed ) && 'email' != $field2['input_type'] ) : ?>
							<option value="<?php echo $field2['name']; ?>" <?php selected( $field2['name'], $field['field'] ); ?>><?php echo $field2['name']; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</label>
		</td>

		<?php // Type ?>
		<td class="field-type">
			<label>
				<select name="view[data][client_section][<?php echo $key; ?>][type]">
				<?php foreach ( $types as $type => $type_label ) : ?>
					<option value="<?php echo $type; ?>" <?php selected( $type, $field['type'] ); ?>><?php echo $type_label; ?></option>
				<?php endforeach; ?>
				</select>
			</label>
		</td>

		<?php // Meta ?>
		<td class="field-meta">
			<?php
				if ( 'link' == $field['type'] || 'link2' == $field['type'] )
					wpmtst_view_field_link( $key, $field['field'], $field['type'], $field );

				if ( 'date' == $field['type'] )
					wpmtst_view_field_date( $key, $field );
			?>
		</td>

		<?php // Class ?>
		<td class="field-class">
			<label>
				<input type="text" name="view[data][client_section][<?php echo $key; ?>][class]" value="<?php echo $field['class']; ?>">
			</label>
		</td>

		<?php // Controls ?>
		<td class="controls">
			<span class="delete-field" title="delete"><span class="dashicons dashicons-no"></span></span>
			<span class="handle" title="drag and drop to reorder"><span class="dashicons dashicons-menu"></span></span>
		</td>

	</tr>
	<?php
}


/**
 * Show a single client link field inputs.
 *
 * @since 1.21.0
 *
 * @param $key
 * @param $field_name
 * @param $type
 * @param $field
 * @param bool|false $adding
 */
function wpmtst_view_field_link( $key, $field_name, $type, $field, $adding = false ) {
	if ( $field_name ) {
		$current_field = wpmtst_get_field_by_name( $field_name );
		if ( is_array( $current_field ) ) {
			$field = array_merge( $current_field, $field );
		}
	}

	$custom_fields = wpmtst_get_custom_fields();

	// Add placeholder link_text and label to field in case we need to populate link_text
	if ( ! isset( $field['link_text'] ) ) {
		$field['link_text'] = 'field';
	}
	if ( ! isset( $field['link_text_custom'] ) ) {
		$field['link_text_custom'] = '';
	}
	$field['label'] = wpmtst_get_field_label( $field );
	?>

	<!-- the link text -->
	<div class="field-meta-row link-text">
		<label for="view-fieldtext<?php echo $key; ?>">Text</label>
		<select id="view-fieldtext<?php echo $key; ?>" name="view[data][client_section][<?php echo $key; ?>][link_text]" class="if selectgroup">
			<option value="value" <?php selected( $field['link_text'], 'value' ); ?>>this field's value</option>
			<option value="label" <?php selected( $field['link_text'], 'label' ); ?>>this field's label</option>
			<option value="custom" <?php selected( $field['link_text'], 'custom' ); ?>>custom text</option>
		</select>
	</div>

	<!-- the link text options -->
	<div class="field-meta-row link-text">
		<div class="then_fieldtext<?php echo $key; ?> then_value then_not_label then_not_custom" style="display: none;">
			<!-- placeholder -->
		</div>
		<div class="then_fieldtext<?php echo $key; ?> then_label then_not_value then_not_custom" style="display: none;">
			<input type="text" id="view-fieldtext<?php echo $key; ?>-label" value="<?php echo $field['label']; ?>" readonly>
		</div>
		<div class="then_fieldtext<?php echo $key; ?> then_custom then_not_value then_not_label" style="display: none;">
			<input type="text" id="view-fieldtext<?php echo $key; ?>-custom" name="view[data][client_section][<?php echo $key; ?>][link_text_custom]" value="<?php echo $field['link_text_custom']; ?>">
		</div>
	</div>

	<!-- the URL -->
	<?php if ( 'link' == $type ) : // URL = another field ?>
	<div class="field-meta-row">
		<label for="view-fieldurl<?php echo $key; ?>">URL</label>
		<select id="view-fieldurl<?php echo $key; ?>" name="view[data][client_section][<?php echo $key; ?>][url]" class="field-type-select">
			<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
				<?php if ( 'url' == $field2['input_type'] ) : ?>
				<option value="<?php echo $field2['name']; ?>" <?php selected( $field2['name'], $field['url'] ); ?>><?php echo $field2['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
	</div>
	<?php else : // URL = this field ?>
		<input type="hidden" name="view[data][client_section][<?php echo $key; ?>][url]" value="<?php echo $field['name']; ?>">
	<?php endif; ?>

	<!-- the URL options -->
	<div class="field-meta-row checkbox">
		<label>
			<div class="new_tab">
				<input type="checkbox" id="<?php echo $key; ?>-newtab" name="view[data][client_section][<?php echo $key; ?>][new_tab]" value="1" <?php checked( $field['new_tab'] ); ?>>
				<label for="<?php echo $key; ?>-newtab">
					<?php _e( 'new tab', 'strong-testimonials' ); ?>
				</label>
			</div>
		</label>
	</div>
	<?php
}


/**
 * Show a single client date field inputs.
 *
 * @since 1.21.0
 */
function wpmtst_view_field_date( $key, $field, $adding = false ) {
	?>
	<label for="view-<?php echo $key; ?>-client-date-format"><span>Format</span></label>
	<input id="view-<?php echo $key; ?>-client-date-format" type="text" name="view[data][client_section][<?php echo $key; ?>][format]" class="field-type-date" value="<?php echo isset( $field['format'] ) ? $field['format'] : ''; ?>">
	<div class="help minor">
		<?php printf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://codex.wordpress.org/Formatting_Date_and_Time' ),
			__( 'more about date formats', 'strong-testimonials' ) ); ?>
	</div>
	<?php
}


/**
 * Delete a view.
 *
 * @since 1.21.0
 * @param $id
 * @return false|int
 */
function wpmtst_delete_view( $id ) {
	global $wpdb;
	$num_rows_deleted = $wpdb->delete( $wpdb->prefix . 'strong_views', array( 'id' => $id ) );
	return $num_rows_deleted;
}


/**
 * Admin action hook to delete a view.
 *
 * @since 1.21.0
 */
function wpmtst_delete_view_action_hook() {
	if ( isset( $_REQUEST['action'] ) && 'delete-strong-view' == $_REQUEST['action'] && isset( $_REQUEST['id'] ) ) {
		$id = (int) $_GET['id'];
		check_admin_referer( 'delete-strong-view_' . $id );
		wpmtst_delete_view( $id );
		$goback = add_query_arg( 'view-deleted', true, wp_get_referer() );
		wp_redirect( $goback );
		exit;
	}
}


function wpmtst_category_checklist( $view_cats_array ) {
	?>
	<div class="view-category-list-panel">
		<div class="fc-search-wrap">
			<input type="search" class="fc-search-field"
				   placeholder="<?php _e( 'filter categories', 'strong-testimonials' ); ?>"/>
		</div>
		<ul class="view-category-list">
			<?php $args = array(
				'descendants_and_self' => 0,
				'selected_cats'        => $view_cats_array,
				'popular_cats'         => false,
				'walker'               => new Walker_WPMST_Category_Checklist(),
				'taxonomy'             => "wpm-testimonial-category",
				'checked_ontop'        => true,
			); ?>
			<?php wp_terms_checklist( 0, $args ); ?>
		</ul>
	</div>
	<?php
}


function wpmtst_form_category_checklist( $view_cats_array ) {
	?>
	<div class="view-category-list-panel">
		<div class="fc-search-wrap">
			<input type="search" class="fc-search-field"
				   placeholder="<?php _e( 'filter categories', 'strong-testimonials' ); ?>"/>
		</div>
		<ul class="view-category-list">
			<?php $args = array(
				'descendants_and_self' => 0,
				'selected_cats'        => $view_cats_array,
				'popular_cats'         => false,
				'walker'               => new Walker_WPMST_Form_Category_Checklist(),
				'taxonomy'             => "wpm-testimonial-category",
				'checked_ontop'        => true,
			); ?>
			<?php wp_terms_checklist( 0, $args ); ?>
		</ul>
	</div>
	<?php
}
