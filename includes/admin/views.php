<?php
/**
 * View admin functions.
 *
 * @since 1.21.0
 * @package Strong_Testimonials
 */

/**
 * An individual view settings page.
 *
 * @since 1.21.0
 *
 * @param string $action
 * @param null   $view_id
 */
function wpmtst_view_settings( $action = '', $view_id = null ) {

	if ( ( 'edit' == $action || 'duplicate' == $action ) && !$view_id ) return;

	global $view, $strong_templates;
	add_thickbox();

	$screen = get_current_screen();
	$url    = $screen->parent_file;

	$fields     = wpmtst_get_custom_fields();
	$all_fields = wpmtst_get_all_fields();

	$order_list = wpmtst_get_order_list();

	$posts_list = get_posts( array(
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

	$pages_list = get_pages( array(
		'sort_order'  => 'ASC',
		'sort_column' => 'menu_order',
		'post_type'   => 'page',
		'post_status' => 'publish',
	) );

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
	//$view_cats_array = explode( ',', $view['category'] );
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
			<div class="form-view-name"><span class="title">Name:</span><input type="text" id="view-name" class="view-name" name="view[name]" value="<?php echo $view_name; ?>" tabindex="1"></div>
		</div>
			<div class="view-info">
				<div class="form-view-shortcode">
					<span class="title">Shortcode:</span>
					<?php if ( 'edit' == $action ): ?>
					<span class="saved">[testimonial_view id=<?php echo $view_id; ?>]</span>
					<?php else: ?>
					<span class="unsaved"><?php _ex( 'will be available after you save this', 'The shortcode for a new View.', 'strong-testimonials' ); ?></span>
					<?php endif; ?>
				</div>
			</div>

		<?php
		include( 'views/mode.php' );

		// TODO Generify both hook and include
		do_action( 'wpmtst_view_editor_before_group_select' );
		include( 'views/group-select.php' );

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
 * View list page
 *
 * @since 1.21.0
 */
function wpmtst_views_admin() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	$screen = get_current_screen();
	$url = $screen->parent_file;
	?>
	<div class="wrap wpmtst2">

		<?php
		// @TODO move to options
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

		if ( $message )
			printf( '<div class="notice is-dismissible updated"><p>%s</p></div>', $message );

		// Editing a view
		if ( isset( $_REQUEST['action'] ) ) {

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
				<a href="<?php echo $url; ?>&page=testimonial-views&action=add" class="add-new-h2">Add New</a>
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
		'link2' => __( 'link', 'strong-testimonials' ),  // @since 1.24.0
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
		<?php printf( wp_kses( __( '<a href="%s" target="_blank">more about date formats</a>', 'strong-testimonials' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://codex.wordpress.org/Formatting_Date_and_Time' ) ); ?>
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
		$view_name  = $_POST['view']['name'];

		if ( isset( $_POST['reset'] ) ) {

			// Undo changes
			//$view = wpmtst_get_view( $view_id );
			$goback = add_query_arg( 'changes-undone', true, $goback );

		}
		elseif ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			$default_view = get_option( 'wpmtst_view_default' );

			/**
			 * Must save first to get the auto-increment ID.
			 */
			$view = array(
				'id'   => $view_id,
				'name' => sanitize_text_field( $view_name ),
				'data' => $default_view
			);
			wpmtst_save_view( $view );

			$goback = add_query_arg( 'defaults-restored', true, $goback );

		}
		else {

			// Sanitize & validate
			$view = array(
				'id'   => $view_id,
				'name' => sanitize_text_field( $view_name ),
				'data' => wpmtst_sanitize_view( $_POST['view']['data'] )
			);
			wpmtst_save_view( $view );

			$goback = add_query_arg( 'view-saved', true, $goback );

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
		$view_name = $_POST['view']['name'];

		if ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			$default_view = get_option( 'wpmtst_view_default' );

			$view = array(
				'id'   => $view_id,
				'name' => $view_name,
				'data' => $default_view,
			);
			$new_id = wpmtst_save_view( $view, 'add' );

			$query_arg = 'defaults-restored';

		}
		else {

			// Sanitize & validate
			$view = array(
				'id'   => 0,
				'name' => sanitize_text_field( $view_name ),
				'data' => wpmtst_sanitize_view( $_POST['view']['data'] )
			);
			$new_id = wpmtst_save_view( $view, 'add' );

			$query_arg = 'view-saved';

		}

		$goback = remove_query_arg( 'action', $goback );
		$goback = add_query_arg( array( 'action' => 'edit', 'id' => $new_id, $query_arg => true ), $goback );

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
 * Sanitize and validate a View.
 * TODO break down into separate validators
 *
 * @param $input
 *
 * @return array
 */
function wpmtst_sanitize_view( $input ) {
	ksort( $input );

	$view_data         = array();
	$view_data['mode'] = sanitize_text_field( $input['mode'] );

    // Compatibility
    $view_data['compat'] = ( 'compat_on' == $input['compat'] ? 1 : 0 );

	// Read more target
	if ( isset( $input['read_more'] ) && isset( $input['read_more_to'] ) ) {

		// Target: the post
		if ( 'more_post' == $input['read_more_to'] ) {
			$view_data['more_post'] = 1;
		}
		else {

			// Target: a page

			// Check the "ID or slug" field first
			if ( $input['more_page_id'] ) {
				// is post ID?
				$id = (int) sanitize_text_field( $input['more_page_id'] );
				if ( $id ) {
					if ( ! get_posts( array( 'p' => $id, 'post_type' => 'page', 'post_status' => 'publish' ) ) ) {
						$id = null;
					}
				}
				else {
					// is post slug?
					$target = get_posts( array( 'name' => $input['more_page_id'], 'post_type' => 'page', 'post_status' => 'publish' ) );
					if ( $target ) {
						$id = $target[0]->ID;
					}
				}

				$view_data['more_page']    = $id;
			}
			else {
				$view_data['more_page'] = (int) sanitize_text_field( $input['more_page'] );
			}
			$view_data['more_page_id'] = '';

		}

	}
	$view_data['more_text'] = sanitize_text_field( $input['more_text'] );

	/**
	 * Single testimonial
	 */
	// Clear single ID if "multiple" selected
	if ( 'multiple' == $input['select'] ) {
		$view_data['id'] = 0;  // must be zero not empty or false
		//$view_data['post_id'] = '';
	}
	else {
		// Check the "ID or slug" field first
		if ( !$input['post_id'] ) {
			$view_data['id'] = (int) sanitize_text_field( $input['id'] );
		} else {
			// is post ID?
			$id = (int) $input['post_id'];
			if ( $id ) {
				if ( ! get_posts( array( 'p' => $id, 'post_type' => 'wpm-testimonial', 'post_status' => 'publish' ) ) ) {
					$id = null;
				}
			} else {
				// is post slug?
				$target = get_posts( array(
					'name'        => $input['post_id'],
					'post_type'   => 'wpm-testimonial',
					'post_status' => 'publish'
				) );
				if ( $target ) {
					$id = $target[0]->ID;
				}
			}

			$view_data['id']      = $id;
			$view_data['post_id'] = '';
		}
	}

	$view_data['form_ajax'] = isset( $input['form_ajax'] ) ? 1 : 0;

	// Template
	if ( 'form' == $view_data['mode'] )
		$view_data['template'] = isset( $input['form-template'] ) ? sanitize_text_field( $input['form-template'] ) : '';
	else
		$view_data['template']   = isset( $input['template'] ) ? sanitize_text_field( $input['template'] ) : '';

	// Category
	if ( 'form' == $view_data['mode'] ) {

		if ( isset( $input['category-form'] ) ) {
			$view_data['category'] = sanitize_text_field( implode( ',', $input['category-form'] ) );
		}
		else {
			$view_data['category'] = '';
		}

	}
	else {

		if ( 'allcats' == $input['category_all'] ) {
			$view_data['category'] = 'all';
		}
		elseif ( !isset( $input['category'] ) ) {
			$view_data['category'] = 'all';
		}
		elseif ( 'somecats' == $input['category_all'] && !isset( $input['category'] ) ) {
			$view_data['category'] = 'all';
		}
		else {
			$view_data['category'] = sanitize_text_field( implode( ',', $input['category'] ) );
		}

	}

	$view_data['order'] = sanitize_text_field( $input['order'] );

	$view_data['all']   = sanitize_text_field( $input['all'] );
	$view_data['count'] = (int) sanitize_text_field( $input['count'] );

	$view_data['pagination'] = isset( $input['pagination'] ) ? 1 : 0;
	$view_data['per_page']   = (int) sanitize_text_field( $input['per_page'] );
	$view_data['nav']        = str_replace( ' ', '', sanitize_text_field( $input['nav'] ) );

	$view_data['title']          = isset( $input['title'] ) ? 1 : 0;
	$view_data['content']        = sanitize_text_field( $input['content'] );
	$view_data['length']         = (int) sanitize_text_field( $input['length'] );

	$view_data['thumbnail']        = isset( $input['thumbnail'] ) ? 1 : 0;
	$view_data['thumbnail_size']   = sanitize_text_field( $input['thumbnail_size'] );
	$view_data['thumbnail_width']  = sanitize_text_field( $input['thumbnail_width'] );
	$view_data['thumbnail_height'] = sanitize_text_field( $input['thumbnail_height'] );
	$view_data['lightbox']         = isset( $input['lightbox'] ) ? 1 : 0;
	$view_data['gravatar']         = sanitize_text_field( $input['gravatar'] );

	$view_data['class'] = sanitize_text_field( $input['class'] );

	// Background
	$view_data['background'] = WPMST()->get_background_defaults();
	if ( !isset( $input['background']['type'] ) || 'none' == $input['background']['type'] ) {
		$view_data['background']['type'] = '';
	}
	else {
		$view_data['background']['type'] = sanitize_text_field( $input['background']['type'] );
	}
	$view_data['background']['color']     = sanitize_text_field( $input['background']['color'] );
	$view_data['background']['gradient1'] = sanitize_text_field( $input['background']['gradient1'] );
	$view_data['background']['gradient2'] = sanitize_text_field( $input['background']['gradient2'] );
	$view_data['background']['preset']    = sanitize_text_field( $input['background']['preset'] );
	$view_data['background']['example-font-color'] = sanitize_text_field( $input['background']['example-font-color'] );

	// Layout input may have been disabled by selecting the widget template so no value is posted.
	if ( ! isset( $input['layout'] ) ) {
		$view_data['layout'] = '';
	}
	else {
		// pagination and Masonry are incompatible
		$view_data['layout'] = sanitize_text_field( $input['layout'] );
		if ( isset( $input['pagination'] ) && 'masonry' == $view_data['layout'] ) {
			$view_data['layout'] = '';
		}
	}

	$view_data['column_count'] = sanitize_text_field( $input['column_count'] );

	// Slideshow
	$view_data['show_for']   = floatval( sanitize_text_field( $input['show_for'] ) );
	$view_data['effect_for'] = floatval( sanitize_text_field( $input['effect_for'] ) );
	$view_data['no_pause']   = isset( $input['no_pause'] ) ? 0 : 1;

	// Custom fields
	if ( isset( $input['client_section'] ) ) {
		foreach ( $input['client_section'] as $key => $field ) {
			if ( empty( $field['field'] ) ) {
				break;
			}

			$view_data['client_section'][ $key ]['field'] = sanitize_text_field( $field['field'] );
			$view_data['client_section'][ $key ]['type']  = sanitize_text_field( $field['type'] );
			$view_data['client_section'][ $key ]['class'] = sanitize_text_field( $field['class'] );

			switch ( $field['type'] ) {
				case 'link':
				case 'link2':
					$view_data['client_section'][ $key ]['url']              = sanitize_text_field( $field['url'] );
					$view_data['client_section'][ $key ]['link_text']        = sanitize_text_field( $field['link_text'] );
					$view_data['client_section'][ $key ]['link_text_custom'] = sanitize_text_field( $field['link_text_custom'] );
					$view_data['client_section'][ $key ]['new_tab']          = isset( $field['new_tab'] ) ? 1 : 0;
					break;
				case 'date':
					$format = isset( $field['format'] ) ? sanitize_text_field( $field['format'] ) : '';
					$view_data['client_section'][ $key ]['format'] = $format;
					break;
				default:
			}

		}
	}
	else {
		$view_data['client_section'] = null;
	}

	// Multiple Forms add-on
	if ( isset( $input['form_id'] ) ) {
		$view_data['form_id'] = $input['form_id'];
	}
	else {
		$view_data['form_id'] = $input['_form_id'];
	}

	$view_data = apply_filters( 'wpmtst_sanitized_view', $view_data, $input );
	ksort( $view_data );

	return $view_data;
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
