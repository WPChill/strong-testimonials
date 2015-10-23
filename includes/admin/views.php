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
 */
function wpmtst_view_settings( $action = '', $view_id = null ) {

	if ( 'edit' == $action && ! $view_id ) return;

	$options = get_option( 'wpmtst_options' );

	// @TODO de-duplicate
	$order_list = array(
		'random'     => _x( 'Random', 'display order', 'strong-testimonials' ),
		'menu_order' => _x( 'Menu order', 'display order', 'strong-testimonials' ),
		'newest'     => _x( 'Newest first', 'display order', 'strong-testimonials' ),
		'oldest'     => _x( 'Oldest first', 'display order', 'strong-testimonials' ),
	);

	$posts_list = get_posts( array(
		'orderby'          => 'post_date',
		'order'            => 'ASC',
		'post_type'        => 'wpm-testimonial',
		'post_status'      => 'publish',
		'posts_per_page'   => -1,
		'suppress_filters' => true,
	) );

	$category_list = wpmtst_get_category_list();
	$category_ids  = wpmtst_get_category_ids();

	$pages_list = get_pages( array(
		'sort_order'  => 'ASC',
		'sort_column' => 'menu_order',
		'post_type'   => 'page',
		'post_status' => 'publish'
	) );

	$view_options = get_option( 'wpmtst_view_options' );
	$default_view = get_option( 'wpmtst_view_default' );
	
	// Get current view
	if ( 'edit' == $action ) {
		$view_array = wpmtst_get_view( $view_id );
		//$view       = array_merge( $default_view, unserialize( $view_array['value'] ) );
		$view       = unserialize( $view_array['value'] );
		$view_name  = $view_array['name'];
	} else {
		$view_id   = 1;
		$view      = $default_view;
		$view_name = 'new';
	}

	$view['nav'] = explode( ',', str_replace( ' ', '', $view['nav'] ) );
	$view_cats_array = explode( ',', $view['category'] );

	// Assemble list of templates
	$theme_templates       = wpmtst_get_theme_templates( 'testimonials' );
	$plugin_templates      = wpmtst_get_plugin_templates( 'testimonials' );
	$theme_form_templates  = wpmtst_get_theme_templates( 'testimonial-form' );
	$plugin_form_templates = wpmtst_get_plugin_templates( 'testimonial-form' );

	// Get list of image sizes
	$image_sizes = wpmtst_get_image_sizes();
	?>
	<h2><?php 'add' == $action ? _e( 'Add View', 'strong-testimonials' ) : _e( 'Edit View', 'strong-testimonials' ); ?></h2>

	<p><a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=views' ); ?>">Return to list</a></p>

	<form id="wpmtst-views-form" method="post" action="<?php echo get_admin_url() . 'admin-post.php'; ?>">

		<input type="hidden" name="action" value="view_<?php echo $action; ?>_form">
		<?php wp_nonce_field( 'view_form_submit', 'view_form_nonce', true, true ); ?>

		<input type="hidden" name="view[id]" value="<?php echo $view_id; ?>">
		<input type="hidden" name="view_original_mode" value="<?php echo $view['mode']; ?>">
		<div class="view-info">
			<div class="form-view-name"><span class="title">Name:</span><input type="text" id="view-name" class="view-name" name="view[name]" value="<?php echo $view_name; ?>" tabindex="1" autocomplete="off"></div>
		</div>
		<?php if ( 'edit' == $action ) : ?>
			<div class="view-info">
				<div class="form-view-shortcode">
					<span class="title">Shortcode:</span>[testimonial_view id=<?php echo $view_id; ?>]
				</div>
			</div>
		<?php endif; ?>

		<?php include( 'forms/view/mode.php' ); ?>
		<?php include( 'forms/view/group-select.php' ); ?>
		<?php include( 'forms/view/group-slideshow.php' ); ?>
		<?php include( 'forms/view/group-fields.php' ); ?>
		<?php include( 'forms/view/group-form-options.php' ); ?>
		<?php include( 'forms/view/group-extra.php' ); ?>
		<?php include( 'forms/view/group-style.php' ); ?>

		<p class="submit">
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
function wpmtst_views() {
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
			} elseif ( 'add' == $_REQUEST['action'] ) {
				wpmtst_view_settings( $_REQUEST['action'] );
			}
			
		} else {
			
			// View list
			?>
			<h2>
				<?php _e( 'Views', 'strong-testimonials' ); ?>
				<a href="<?php echo $url; ?>&page=views&action=add" class="add-new-h2">Add New</a>
			</h2>
			<div class="intro">
				<p>A View can display your testimonials, create a slideshow, or show a testimonial submission form.<br>Add it to a page with a shortcode or add it to a sidebar with a widget.<p>
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
	$key = (int) $_REQUEST['key'];
	$empty_field = array( 'url' => '', 'new_tab' => true );
	wpmtst_view_field_link( $key, $empty_field );
	die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_link', 'wpmtst_view_add_field_link_function' );


/**
 * [Field Type: Link] Ajax receiver
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
 */
function wpmtst_view_field_inputs( $key, $field, $adding = false ) {
	$custom_fields = wpmtst_get_custom_fields();
	// the date is a special field
	$custom_fields[] = array(
		'name' => 'date',
		'input_type' => 'date',
		'type' => 'date',
		'record_type' => 'builtin',
	); 
		
	$types = array( 'text', 'link', 'date' );
	$allowed = array( 'custom', 'builtin' );
	?>
	<tr class="field2" id="field-<?php echo $key; ?>">
		<td class="field-name">
			<select name="view[data][client_section][<?php echo $key; ?>][field]" autocomplete="off">
				<option value=""></option>
				<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
					<?php if ( in_array( $field2['record_type'], $allowed ) && 'email' != $field2['input_type'] ) : ?>
						<option value="<?php echo $field2['name']; ?>" <?php selected( $field2['name'], $field['field'] ); ?>><?php echo $field2['name']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</td>
		
		<td class="field-type">
			<select name="view[data][client_section][<?php echo $key; ?>][type]" autocomplete="off">
			<?php foreach ( $types as $type ) : ?>
				<option value="<?php echo $type; ?>" <?php selected( $type, $field['type'] ); ?>><?php echo $type; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
		<td class="field-meta">
			<?php if ( 'link' == $field['type'] ) wpmtst_view_field_link( $key, $field); ?>
			<?php if ( 'date' == $field['type'] ) wpmtst_view_field_date( $key, $field); ?>
		</td>
		<td class="field-class">
			<input type="text" name="view[data][client_section][<?php echo $key; ?>][class]" value="<?php echo $field['class']; ?>">
		</td>
		<td class="controls">
			<span class="delete-field"><span class="dashicons dashicons-no"></span></span>
			<span class="handle"><span class="dashicons dashicons-menu"></span></span>
		</td>
	</tr>
	<?php
}


/**
 * Show a single client link field's inputs.
 *
 * @since 1.21.0
 */
function wpmtst_view_field_link( $key, $field, $adding = false ) {
	$custom_fields = wpmtst_get_custom_fields();
	?>
	<span>URL</span>
	<select name="view[data][client_section][<?php echo $key; ?>][url]" class="field-type-select">
		<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
			<?php if ( 'url' == $field2['input_type'] ) : ?>
			<option value="<?php echo $field2['name']; ?>" <?php selected( $field2['name'], $field['url'] ); ?>><?php echo $field2['name']; ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
	</select>
	<span class="new_tab">
		<input type="checkbox" name="view[data][client_section][<?php echo $key; ?>][new_tab]" value="1" <?php checked( $field['new_tab'] ); ?>> new_tab
	</span>
	<?php
}


/**
 * Show a single client link field's inputs.
 *
 * @since 1.21.0
 */
function wpmtst_view_field_date( $key, $field, $adding = false ) {
	?>
	<label for="view-<?php echo $key; ?>-client-date-format"><span>Format</span></label>
	<input id="view-<?php echo $key; ?>-client-date-format" type="text" name="view[data][client_section][<?php echo $key; ?>][format]" class="field-type-date" value="<?php echo isset( $field['format'] ) ? $field['format'] : ''; ?>" autocomplete="off">
	<div class="help minor">
		<?php _e( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">more about data formats</a>', 'strong-testimonials' ); ?>
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
 * @since 1.21.0
 */
function wpmtst_view_edit_form() {
	$query_arg = 'error';

	if ( ! empty( $_POST ) && check_admin_referer( 'view_form_submit', 'view_form_nonce' ) ) {

		$view_id    = $_POST['view']['id'];
		$view_name  = $_POST['view']['name'];
		// $view_title = $_POST['view']['title'];

		// Undo changes
		if ( isset( $_POST['reset'] ) ) {

			$view = wpmtst_get_view( $view_id );

			$query_arg = 'changes-undone';

		}
		// Restore defaults
		elseif ( isset( $_POST['restore-defaults'] ) ) {

			// $default_view = wpmtst_get_view( 1 );
			$default_view = get_option( 'wpmtst_view_default' );

			$view = array(
				'id'    => $view_id,
				'name'  => $view_name,
				'data'  => $default_view
			);
			wpmtst_save_view( $view );

			$query_arg = 'defaults-restored';

		}
		// Sanitize & validate
		else {

			$view = array(
				'id'    => $view_id,
				'name'  => sanitize_text_field( $view_name ),
				'data'  => wpmtst_sanitize_view( $_POST['view']['data'] )
			);
			$num = wpmtst_save_view( $view );

			$query_arg = 'view-saved';

		}

	}

	$goback = add_query_arg( $query_arg, true, wp_get_referer() );
	wp_redirect( $goback );
	exit;

}
// Thanks http://stackoverflow.com/a/20003981/51600
add_action( 'admin_post_view_edit_form', 'wpmtst_view_edit_form' );
add_action( 'admin_post_nopriv_view_edit_form', 'wpmtst_view_edit_form' );


/**
 * Process form POST after adding.
 *
 * @since 1.21.0
 */
function wpmtst_view_add_form() {

	$query_arg = 'error';

	if ( ! empty( $_POST ) && check_admin_referer( 'view_form_submit', 'view_form_nonce' ) ) {

		// $view_id    = $_POST['view']['id'];
		$view_id    = 0;
		$view_name  = $_POST['view']['name'];
		// $view_title = $_POST['view']['title'];

		if ( isset( $_POST['restore-defaults'] ) ) {
			// Restore defaults

			$default_view = get_option( 'wpmtst_view_default' );

			$view = array(
				'id'    => $view_id,
				'name'  => $view_name,
				'data'  => unserialize( $default_view )
			);
			wpmtst_save_view( $view, 'add' );

			$query_arg = 'defaults-restored';

		} 
		else {
			// Sanitize & validate

			$view = array(
				'id'    => 0,
				'name'  => sanitize_text_field( $view_name ),
				'data'  => wpmtst_sanitize_view( $_POST['view']['data'] )
			);
			$view['id'] = wpmtst_save_view( $view, 'add' );

			$query_arg = 'view-saved';

		}

	}

	$goback = remove_query_arg( 'action', wp_get_referer() );
	$goback = add_query_arg( array( 'action' => 'edit', 'id' => $view['id'], $query_arg => true ), $goback );
	wp_redirect( $goback );
	exit;

}
add_action( 'admin_post_view_add_form', 'wpmtst_view_add_form' );
add_action( 'admin_post_nopriv_view_add_form', 'wpmtst_view_add_form' );


function wpmtst_sanitize_view( $input ) {
	$view_data = array();
	$view_data['mode'] = sanitize_text_field( $input['mode'] );
	
	/**
	 * Read more target
	 */ 
	if ( isset( $input['read_more'] ) ) {  // checkbox
		
		if ( isset( $input['read_more_to'] ) && 'more_post' == $input['read_more_to'] ) {
			$view_data['more_post'] = 1;
		}
		else {
			if ( ! $input['find_page'] ) {
				if ( $input['more_page'] ) {
					$view_data['more_page'] = (int) $input['more_page'];
				}
			}
			else {
				// is page ID or slug?
				$id = (int) $input['find_page'];
				if ( $id ) {
					if( ! get_post( $id ) ) {
						$id = null;
					}
				}
				else {
					$target = get_posts( array(
						'name'        => $input['find_page'],
						'post_type'   => 'page',
						'post_status' => 'publish'
					) );
					if ( $target ) {
						$id = $target[0]->ID;
					}
					else {
						$id = null;
					}
				}
	
				if ( $id ) {
					$view_data['more_page'] = $id;
				}
			}
			
		}
		
	}
	$view_data['more_text'] = sanitize_text_field( $input['more_text'] );
	// Clear its "ID or slug" input field.
	$view_data['find_page'] = '';

	/**
	 * Single testimonial
	 */
	// Clear single ID if "multiple" selected
	if ( 'multiple' == $input['select'] ) {
		$view_data['id'] = 0;  // must be zero not empty or false
		$view_data['post_id'] = '';
	} else {
		// Check the "ID or slug" field first
		if ( ! $input['post_id'] ) {
			$view_data['id'] = intval( sanitize_text_field( $input['id'] ) );
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

	/**
	 * Template & Category
	 */
	if ( 'form' == $view_data['mode'] ) {
		$view_data['template'] = isset( $input['form-template'] ) ? sanitize_text_field( $input['form-template'] ) : '';
		
		if ( isset( $input['category-form'] ) ) {
			$view_data['category'] = sanitize_text_field( implode( ',', $input['category-form'] ) );
		} else {
			$view_data['category'] = '';
		}
	} else {
		$view_data['template']   = isset( $input['template'] ) ? sanitize_text_field( $input['template'] ) : '';
		
		if ( ! isset( $input['category'] ) || $input['category'] == wpmtst_get_category_ids() ) {
			$view_data['category'] = 'all';
		} else {
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

	$view_data['class']      = sanitize_text_field( $input['class'] );
	$view_data['background'] = sanitize_text_field( $input['background'] );

	$view_data['show_for']   = floatval( sanitize_text_field( $input['show_for'] ) );
	$view_data['effect_for'] = floatval( sanitize_text_field( $input['effect_for'] ) );
	$view_data['no_pause']   = isset( $input['no_pause'] ) ? 0 : 1;

	if ( isset( $input['client_section'] ) ) {
		foreach ( $input['client_section'] as $key => $field ) {
			if ( empty( $field['field'] ) ) {
				break;
			}

			$view_data['client_section'][ $key ]['field'] = sanitize_text_field( $field['field'] );
			$view_data['client_section'][ $key ]['type']  = sanitize_text_field( $field['type'] );
			$view_data['client_section'][ $key ]['class'] = sanitize_text_field( $field['class'] );
			if ( 'link' == $field['type'] ) {
				$view_data['client_section'][ $key ]['url']     = sanitize_text_field( $field['url'] );
				$view_data['client_section'][ $key ]['new_tab'] = isset( $field['new_tab'] ) ? 1 : 0;
			} 
			elseif ( 'date' == $field['type'] ) {
				$format = isset( $field['format'] ) ? sanitize_text_field( $field['format'] ) : '';
				$view_data['client_section'][ $key ]['format'] = $format;
			}
		}
	}
	else {
		$view_data['client_section'] = null;
	}

	ksort( $view_data );
	return $view_data;
}
