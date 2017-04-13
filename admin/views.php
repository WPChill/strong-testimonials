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

			echo '<h1>' . __( 'Edit View', 'strong-testimonials' ) . '</h1>';
			$message = sprintf(
				wp_kses(
					__( 'An error occurred. Please <a href="%s" target="_blank">open a support ticket</a>.', 'strong-testimonials' ),
					array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) )
				),
				esc_url( 'https://support.strongplugins.com/tickets/submit-ticket/' )
			);
			wp_die( sprintf( '<div class="error strong-view-error"><p>%s</p></div>', $message ) );

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
				echo '<p>' . __( 'Invalid request. Please try again.', 'strong-testimonials' ) . '</p>';
			}

		}
		else {

			/**
             * View list
             */
			?>
			<h1>
				<?php _e( 'Views', 'strong-testimonials' ); ?>
				<a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=add' ); ?>" class="add-new-h2">Add New</a>
			</h1>
			<?php
			// Fetch views after heading and before intro in case we need to display any database errors.
			$views = wpmtst_get_views();

			// Sort views putting single template(s) first.
            // Needed until db table is refactored to use 'type' field.
            /*
		    $views_standard = array();
		    $views_single_template = array();

			foreach ( $views as $view ) {
			    $view_data = unserialize( $view['value'] );
			    if ( 'single-template' == $view_data['mode'] ) {
			        $views_single_template[] = $view;
				} else {
			        $views_standard[] = $view;
				}
			}

			$views_ordered = array_merge( $views_single_template, $views_standard );
            */
			?>
			<div class="intro">
				<p><?php _e( 'You can create an unlimited number of views.', 'strong-testimonials' ); ?>
				<?php _e( 'A view can:', 'strong-testimonials' ); ?></p>
                <ul class="standard">
                    <li><?php _e( 'display your testimonials in a list, grid, or slideshow', 'strong-testimonials' ); ?></li>
                    <li><?php _e( 'display a testimonial submission form', 'strong-testimonials' ); ?></li>
                    <li><?php _e( 'add your custom fields to the individual testimonial using your theme\'s single post template', 'strong-testimonials' ); ?></li>
                </ul>
				<p><?php _e( 'Add a view to a page with a shortcode or add it to a sidebar with a widget.', 'strong-testimonials' ); ?></p>
			</div>
			<?php
			/**
			 * Add button to clear sort value.
			 */
			if ( isset( $_GET['orderby'] ) ) {
				?>
                <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" style="margin-bottom: 10px;">
                    <input type="hidden" name="action" value="clear-view-sort">
                    <input type="submit" value="clear sort" class="button">
                </form>
				<?php
			}

			/**
			 * Display the table
			 */
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

	global $view;
	add_thickbox();

	$screen = get_current_screen();
	$url    = $screen->parent_file;

	$fields     = wpmtst_get_custom_fields();
	$all_fields = wpmtst_get_all_fields();

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
	$view_options = apply_filters( 'wpmtst_view_options', get_option( 'wpmtst_view_options' ) );
	$default_view = apply_filters( 'wpmtst_view_default', get_option( 'wpmtst_view_default' ) );

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
	$templates      = WPMST()->templates->get_templates( array( 'content', 'widget' ) );
	$form_templates = WPMST()->templates->get_templates( 'form' );

	$group = strtok( $view['template'], ':' );
	$type  = strtok( ':' );

	if ( 'form' == $type ) {
		$template_found = in_array( $view['template'], array_keys( $form_templates ) );
	} else {
		$template_found = in_array( $view['template'], array_keys( $templates ) );
	}

	// Get list of image sizes
	$image_sizes = wpmtst_get_image_sizes();

	?>
	<h1>
		<?php 'edit' == $action ? _e( 'Edit View', 'strong-testimonials' ) : _e( 'Add View', 'strong-testimonials' ); ?>
		<a href="<?php echo $url; ?>&page=testimonial-views&action=add" class="add-new-h2">Add New</a>
	</h1>

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
				<span class="title"><?php _e( 'Name', 'strong-testimonials' ); ?></span><input type="text" id="view-name" class="view-name" name="view[name]"
					   value="<?php echo htmlspecialchars( stripslashes( $view_name ) ); ?>" tabindex="1">
			</div>
		</div>

		<?php
		// avoiding the tab character before the shortcode for better copy-n-paste
		if ( 'edit' == $action ) {
			$shortcode = '<span class="saved">';
			$shortcode .= '<input id="view-shortcode" type="text" value="[testimonial_view id=' . $view_id . ']" readonly />';
			$shortcode .= '<input id="copy-shortcode" class="button small" type="button" value="' . __( 'copy to clipboard', 'strong-testimonials' ) . '" data-copytarget="#view-shortcode" />';
			$shortcode .= '<span id="copy-message">copied</span>';
			$shortcode .= '</span>';
		}
		else {
			$shortcode = '<span class="unsaved">' . _x( 'will be available after you save this', 'The shortcode for a new View.', 'strong-testimonials' ) . '</span>';
		}
		?>

		<div class="view-info then then_display then_form then_slideshow <?php echo apply_filters( 'wpmtst_view_section', '', 'shortcode' ); ?>">
			<div class="form-view-shortcode"><span class="title"><?php _e( 'Shortcode', 'strong-testimonials' ); ?></span><?php echo $shortcode; ?></div>
		</div>

		<?php
		include( 'partials/views/mode.php' );

		// TODO Generify both hook and include
		do_action( 'wpmtst_view_editor_before_group_select' );
		include( 'partials/views/group-select.php' );

		do_action( 'wpmtst_view_editor_before_group_slideshow' );
		include( 'partials/views/group-slideshow.php' );

		do_action( 'wpmtst_view_editor_before_group_fields' );
		include( 'partials/views/group-fields.php' );

		do_action( 'wpmtst_view_editor_before_group_form' );
		include( 'partials/views/group-form.php' );

		do_action( 'wpmtst_view_editor_before_group_extra' );
		include( 'partials/views/group-extra.php' );

		do_action( 'wpmtst_view_editor_before_group_style' );
		include( 'partials/views/group-style.php' );

		do_action( 'wpmtst_view_editor_before_group_general' );
		include( 'partials/views/group-general.php' );

		do_action( 'wpmtst_view_editor_after_groups' );
		?>

		<p class="wpmtst-submit">
			<?php submit_button( '', 'primary', 'submit-form', false ); ?>
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
				'data' => wpmtst_sanitize_view( stripslashes_deep( $_POST['view']['data'] ) ),
			);
			$success = wpmtst_save_view( $view ); // num_rows

			if ( $success ) {
				$goback = remove_query_arg( 'defaults-restored', $goback );
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
				'data' => wpmtst_sanitize_view( stripslashes_deep( $_POST['view']['data'] ) ),
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

	/**
	 * Remove [category] from custom because it's included in [optional].
	 *
	 * @since 2.17.0
	 */
	foreach ( $custom_fields as $name1 => $field1 ) {
	    if ( 'category' == strtok( $field1['input_type'], '-' ) ) {
	        unset( $custom_fields[ $name1 ] );
		}
	}

	// TODO Move this to view defaults option.
	$builtin_fields = array(
		array(
			'name'        => 'post_date',
			'input_type'  => 'date',
			'type'        => 'date',
			'record_type' => 'builtin',
		),
		array(
			'name'        => 'submit_date',
			'input_type'  => 'date',
			'type'        => 'date',
			'record_type' => 'builtin',
		),
		array(
			'name'        => 'category',
			'input_type'  => 'category',
			'type'        => 'category',
			'record_type' => 'builtin',
		),
	);

	$all_fields = array(
		__( 'custom', 'strong-testimonials' ) => $custom_fields,
		__( 'built-in', 'strong-testimonials' ) => $builtin_fields
	);

	$allowed = array( 'custom', 'optional', 'builtin' );

	// TODO Move this to view defaults option.
	$types = array(
		'text'      => __( 'text', 'strong-testimonials' ),
		'link'      => __( 'link with another field', 'strong-testimonials' ),  // the original link type
		'link2'     => __( 'link (must be URL type)', 'strong-testimonials' ),  // @since 1.24.0
		'date'      => __( 'date', 'strong-testimonials' ),
		'category'  => __( 'category', 'strong-testimonials' ),
		'rating'    => __( 'rating', 'strong-testimonials' ),
		'shortcode' => __( 'shortcode', 'strong-testimonials' ),
	);

	if ( isset( $custom_fields[ $field['field'] ] ) ) {
		$field_label = $custom_fields[ $field['field'] ]['label'];
	} else {
	    $field_label = ucwords( str_replace( '_', ' ', $field['field'] ) );
	}

	/**
	 * Catch and highlight fields not found in custom fields; i.e. it has been deleted.
	 *
     * @since 2.17.0
	 */
	$all_field_names = array_merge( array_keys( $custom_fields), array( 'post_date', 'submit_date', 'category' ) );
	$label_class = '';
	if ( ! $adding && ! in_array( $field['field'], $all_field_names ) ) {
	    $field_label .= ' < ERROR - not found >';
	    $label_class = 'error';
	}
	?>
	<div id="field-<?php echo $key; ?>" class="field2">

		<div class="field3" data-key="<?php echo $key; ?>">

			<span class="link" title="<?php _e( 'click to open or close', 'strong-testimonials' ); ?>">

				<a href="#" class="field-description <?php echo $label_class; ?>"><?php echo $field_label; ?></a>

				<div class="controls2 left">
					<span class="handle ui-sortable-handle icon-wrap"
						  title="<?php _e( 'drag and drop to reorder', 'strong-testimonials' ); ?>"></span>
					<span class="delete icon-wrap"
						  title="<?php _e( 'remove this field', 'strong-testimonials' ); ?>"></span>
				</div>

				<div class="controls2 right">
					<span class="toggle icon-wrap"
						  title="<?php _e( 'click to open or close', 'strong-testimonials' ); ?>"></span>
				</div>

			</span>

			<div class="field-properties" style="display: none;">

                <!-- FIELD NAME -->
                <div class="field-property field-name">
                    <label for="client_section_<?php echo $key; ?>_field">
                        <?php _e( 'Name', 'strong-testimonials' ); ?>
                    </label>
                    <select id="client_section_<?php echo $key; ?>_field" name="view[data][client_section][<?php echo $key; ?>][field]" class="first-field">
                        <option value="">&mdash; select a field &mdash;</option>

                        <?php foreach ( $all_fields as $group_name => $group ) : ?>
                        <optgroup label="<?php echo $group_name; ?>">

                        <?php foreach ( $group as $key2 => $field2 ) : ?>
                        <?php if ( in_array( $field2['record_type'], $allowed ) && 'email' != $field2['input_type'] ) : ?>
                        <option value="<?php echo $field2['name']; ?>" data-type="<?php echo $field2['input_type']; ?>"
                            <?php selected( $field2['name'], $field['field'] ); ?>><?php echo $field2['name']; ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>

                        </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FIELD TYPE -->
                <div class="field-property field-type field-dep" <?php if ( $adding ) echo ' style="display: none;"'; ?>>
                    <label for="client_section_<?php echo $key; ?>_type">
                        <?php _e( 'Display Type', 'strong-testimonials' ); ?>
                    </label>
                    <select id="client_section_<?php echo $key; ?>_type" name="view[data][client_section][<?php echo $key; ?>][type]">
                        <?php foreach ( $types as $type => $type_label ) : ?>
                        <option value="<?php echo $type; ?>" <?php selected( $type, $field['type'] ); ?>><?php echo $type_label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FIELD META -->
                <div class="field-property-box field-meta field-dep" <?php if ( $adding ) echo ' style="display: none;"'; ?>>
                    <?php
                    if ( 'link' == $field['type'] || 'link2' == $field['type'] ) {
                        wpmtst_view_field_link( $key, $field['field'], $field['type'], $field );
                    }

                    if ( 'date' == $field['type'] ) {
                        wpmtst_view_field_date( $key, $field );
                    }
                    ?>
                </div>

                <!-- FIELD BEFORE -->
                <div class="field-property field-before field-dep" <?php if ( $adding ) echo ' style="display: none;"'; ?>>
                    <label for="client_section_<?php echo $key; ?>_before">
                        <?php _e( 'Before', 'strong-testimonials' ); ?>
                    </label>
                    <input id="client_section_<?php echo $key; ?>_before" type="text" name="view[data][client_section][<?php echo $key; ?>][before]" value="<?php echo isset( $field['before'] ) ? $field['before'] : ''; ?>">
                </div>

                <!-- FIELD CSS CLASS -->
                <div class="field-property field-css field-dep" <?php if ( $adding ) echo ' style="display: none;"'; ?>>
                    <label for="client_section_<?php echo $key; ?>_class">
                        <?php _e( 'CSS Class', 'strong-testimonials' ); ?>
                    </label>
                    <input id="client_section_<?php echo $key; ?>_class" type="text" name="view[data][client_section][<?php echo $key; ?>][class]" value="<?php echo $field['class']; ?>">
                </div>

            </div>

		</div>

	</div>
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

	<?php // the link text ?>
	<div class="flex">
		<label for="view-fieldtext<?php echo $key; ?>"><?php _e( 'Link Text', 'strong-testimonials' ); ?></label>
		<select id="view-fieldtext<?php echo $key; ?>" name="view[data][client_section][<?php echo $key; ?>][link_text]" class="if selectgroup">
			<option value="value" <?php selected( $field['link_text'], 'value' ); ?>><?php _e( "this field's value", 'strong-testimonials' ); ?></option>
			<option value="label" <?php selected( $field['link_text'], 'label' ); ?>><?php _e( "this field's label", 'strong-testimonials' ); ?></option>
			<option value="custom" <?php selected( $field['link_text'], 'custom' ); ?>><?php _e( 'custom text', 'strong-testimonials' ); ?></option>
		</select>
	</div>

	<?php // the link text options ?>
	<?php // use the field label ?>
	<div class="flex then_fieldtext<?php echo $key; ?> then_label then_not_value then_not_custom" style="display: none;">
		<div class="nolabel">&nbsp;</div>
		<input type="text" id="view-fieldtext<?php echo $key; ?>-label" value="<?php echo $field['label']; ?>" readonly>
	</div>
	<?php // use custom text ?>
	<div class="flex then_fieldtext<?php echo $key; ?> then_custom then_not_value then_not_label" style="display: none;">
		<div class="nolabel">&nbsp;</div>
		<input type="text" id="view-fieldtext<?php echo $key; ?>-custom" name="view[data][client_section][<?php echo $key; ?>][link_text_custom]" value="<?php echo $field['link_text_custom']; ?>">
	</div>

	<?php // the URL ?>
	<?php if ( 'link' == $type ) : // URL = another field ?>
	<div class="flex">
		<label for="view-fieldurl<?php echo $key; ?>"><?php _e( 'URL Field', 'strong-testimonials' ); ?></label>
		<select id="view-fieldurl<?php echo $key; ?>" name="view[data][client_section][<?php echo $key; ?>][url]" class="field-type-select">
			<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
				<?php if ( 'url' == $field2['input_type'] ) : ?>
				<option value="<?php echo $field2['name']; ?>" <?php selected( $field2['name'], $field['url'] ); ?>><?php echo $field2['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="flex">
		<?php // the URL options ?>
		<div class="nolabel"></div>
		<div class="new_tab">
			<input type="checkbox" id="view-fieldurl<?php echo $key; ?>-newtab"
				   name="view[data][client_section][<?php echo $key; ?>][new_tab]"
				   value="1" <?php checked( $field['new_tab'] ); ?>>
			<label for="view-fieldurl<?php echo $key; ?>-newtab">
				<?php _e( 'new tab', 'strong-testimonials' ); ?>
			</label>
		</div>

	</div>
	<?php else : // URL = this field ?>
		<input type="hidden" name="view[data][client_section][<?php echo $key; ?>][url]" value="<?php echo $field['name']; ?>">
	<?php endif; ?>

	<?php
}


/**
 * Show a single client date field inputs.
 *
 * @since 1.21.0
 */
function wpmtst_view_field_date( $key, $field, $adding = false ) {
	?>
	<div class="flex">
		<label for="view-<?php echo $key; ?>-client-date-format"><span><?php _e( 'Format', 'strong-testimonials' ); ?></span></label>
		<input id="view-<?php echo $key; ?>-client-date-format" type="text" name="view[data][client_section][<?php echo $key; ?>][format]" class="field-type-date" value="<?php echo isset( $field['format'] ) ? $field['format'] : ''; ?>">
	</div>
	<div class="flex">
		<div class="nolabel">&nbsp;</div>
		<div class="help minor">
			<?php printf( '<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://codex.wordpress.org/Formatting_Date_and_Time' ),
				__( 'more about date formats', 'strong-testimonials' ) ); ?>
		</div>
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
	<div class="view-category-list-panel short-panel">
		<div class="fc-search-wrap">
			<input type="search" class="fc-search-field"
				   placeholder="<?php _e( 'filter categories', 'strong-testimonials' ); ?>"/>
		</div>
		<ul class="view-category-list">
			<?php $args = array(
				'descendants_and_self' => 0,
				'selected_cats'        => $view_cats_array,
				'popular_cats'         => false,
				'walker'               => new Walker_Strong_Category_Checklist(),
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
	<div class="view-category-list-panel short-panel">
		<div class="fc-search-wrap">
			<input type="search" class="fc-search-field"
				   placeholder="<?php _e( 'filter categories', 'strong-testimonials' ); ?>"/>
		</div>
		<ul class="view-category-list">
			<?php $args = array(
				'descendants_and_self' => 0,
				'selected_cats'        => $view_cats_array,
				'popular_cats'         => false,
				'walker'               => new Walker_Strong_Form_Category_Checklist(),
				'taxonomy'             => "wpm-testimonial-category",
				'checked_ontop'        => true,
			); ?>
			<?php wp_terms_checklist( 0, $args ); ?>
		</ul>
	</div>
	<?php
}
