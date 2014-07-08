<?php
/**
 * Strong Testimonials - Admin functions
 */

 
/*
 * Init
 */
function wpmtst_admin_init() {
	// Check WordPress version
	wpmtst_version_check();
}
add_action( 'admin_init', 'wpmtst_admin_init' );


/*
 * Check WordPress version
 */
function wpmtst_version_check() {
	global $wp_version;
	$wpmtst_plugin_info = get_plugin_data( __FILE__, false );
	$require_wp = "3.5";  // least required Wordpress version
	$plugin = plugin_basename( __FILE__ );

	if ( version_compare( $wp_version, $require_wp, '<' ) ) {
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
			wp_die( '<strong>' . $wpmtst_plugin_info['Name'] . ' </strong> ' 
				. __( 'requires', WPMTST_NAME ) . ' <strong>WordPress ' . $require_wp . '</strong> ' 
				. __( 'or higher so it has been deactivated. Please upgrade WordPress and try again.', WPMTST_NAME) 
				. '<br /><br />' 
				. __( 'Back to the WordPress', WPMTST_NAME) . ' <a href="' . get_admin_url( null, 'plugins.php' ) . '">' 
				. __( 'Plugins page', WPMTST_NAME) . '</a>' );
		}
	}
}


/*
 * Admin scripts.
 */
function wpmtst_admin_scripts() {
	wp_enqueue_style( 'wpmtst-admin-style', WPMTST_DIR . 'css/wpmtst-admin.css' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'wpmtst-admin-script', WPMTST_DIR . 'js/wpmtst-admin.js', array( 'jquery' ) );
	wp_localize_script( 'wpmtst-admin-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_scripts' );


/*
 * Add meta box to the post editor screen
 */
function wpmtst_add_meta_boxes() {
	add_meta_box( 'details', 'Client Details', 'wpmtst_meta_options', 'wpm-testimonial', 'normal', 'low' );
}
add_action( 'add_meta_boxes', 'wpmtst_add_meta_boxes' );


/*
 * Add custom fields to the testimonial editor
 */
function wpmtst_meta_options() {
	global $post;
	$post = wpmtst_get_post( $post );
	$options = get_option( 'wpmtst_options' );
	$fields = get_option( 'wpmtst_fields' );
	$field_groups = $fields['field_groups'];
	?>
	<table class="options">
		<tr>
			<td colspan="2">To add a client's photo, use the <strong>Featured Image</strong> option. <div class="dashicons dashicons-arrow-right-alt"></div></td>
		</tr>
		<?php foreach ( $field_groups[ $fields['current_field_group'] ]['fields'] as $key => $field ) { ?>
		<?php if ( 'custom' == $field['record_type'] ) { ?>
		<tr>
			<th><label for="<?php echo $field['name']; ?>"><?php _e( $field['label'], WPMTST_NAME ); ?></label></td>
			<td><?php echo sprintf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" size="" />', $field['input_type'], $field['name'], $post->$field['name'] ); ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</table>
	<?php
}


/*
 * Add custom columns to the admin screen
 */
function wpmtst_edit_columns( $columns ) {
	$options = get_option( 'wpmtst_options' );
	$fields = get_option( 'wpmtst_fields' );
	$fields = $fields['field_groups'][ $fields['current_field_group'] ]['fields'];
	
	$columns = array(
			'cb'    => '<input type="checkbox" />', 
			'title' => __( 'Title', WPMTST_NAME ),
	);
	
	foreach ( $fields as $key => $field ) {
		if ( $field['admin_table'] ) {
			if ( 'featured_image' == $field['name'] )
				$columns['thumbnail'] = __( 'Thumbnail', WPMTST_NAME );
			elseif ( 'post_title' == $field['name'] )
				continue; // is set above
			else
				$columns[ $field['name'] ] = __( $field['label'], WPMTST_NAME );
		}
	}
	$columns['category']  = __( 'Category', WPMTST_NAME );
	$columns['shortcode'] = __( 'Shortcode', WPMTST_NAME );
	$columns['date']      = __( 'Date', WPMTST_NAME );

	return $columns;
}
add_filter( 'manage_edit-wpm-testimonial_columns', 'wpmtst_edit_columns' );


/*
 * Show custom values
 */
function wpmtst_custom_columns( $column ) {
	global $post;
	$custom = get_post_custom();

	if ( 'post_id' == $column ) {
		echo $post->ID;
	}
	elseif ( 'post_content' == $column ) {
		echo substr( $post->post_content, 0, 100 ) . '...';
	}
	elseif ( 'thumbnail' == $column ) {
		echo $post->post_thumbnail;
	}
	elseif ( 'shortcode' == $column ) {
		echo '[wpmtst-single id="' . $post->ID . '"]';
	}
	elseif ( 'category' == $column ) {
		$categories = get_the_terms( 0, 'wpm-testimonial-category' );
		if ( $categories && ! is_wp_error( $categories ) ) {
			$list = array();
			foreach ( $categories as $cat ) {
				$list[] = $cat->name;
			}
			echo join( ", ", $list );
		}
	}
	else {
		// custom field?
		if ( isset( $custom[$column] ) )
			echo $custom[$column][0];
	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_custom_columns' );


/*
 * Add thumbnail column to admin list
 */
function wpmtst_add_thumbnail_column( $columns ) {
	$columns['thumbnail'] = __( 'Thumbnail', WPMTST_NAME );
	return $columns;
}
add_filter( 'manage_wpm-testimonial_posts_columns', 'wpmtst_add_thumbnail_column' );


/*
 * Show thumbnail in admin list
 */
function wpmtst_add_thumbnail_value( $column_name, $post_id ) {
	if ( 'thumbnail' == $column_name ) {
		$width  = (int) 75;
		$height = (int) 75;

		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		$attachments = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );

		if ( $thumbnail_id ) {
			$thumb = wp_get_attachment_image( $thumbnail_id, array( $width, $height ), true );
		}
		elseif ( $attachments ) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array( $width, $height ), true );
			}
		}

		if ( isset( $thumb ) && $thumb )
			echo $thumb;
		else
			echo __( 'None', WPMTST_NAME );
	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_add_thumbnail_value', 10, 2 );


/*
 * Add columns to the testimonials categories screen
 */
function wpmtst_manage_categories( $columns ) {
	$new_columns = array(
			'cb'        => '<input type="checkbox" />',
			'ID'        => __( 'ID', WPMTST_NAME ),
			'name'      => __( 'Name', WPMTST_NAME ),
			'slug'      => __( 'Slug', WPMTST_NAME ),
			'shortcode' => __( 'Shortcode', WPMTST_NAME ),
			'posts'     => __( 'Posts', WPMTST_NAME )
	);
	return $new_columns;
}
add_filter( 'manage_edit-wpm-testimonial-category_columns', 'wpmtst_manage_categories');


/*
 * Show custom column
 */
function wpmtst_manage_columns( $out, $column_name, $id ) {
	if ( 'shortcode' == $column_name )
		$output = '[wpmtst-all category="' . $id . '"]';
	elseif ( 'ID' == $column_name )
		$output = $id;
	else
		$output = '';

	return $output;
}
add_filter( 'manage_wpm-testimonial-category_custom_column', 'wpmtst_manage_columns', 10, 3 );


/*
 * Save custom fields
 */
function wpmtst_save_details() {
	// check Custom Post Type
	if ( ! isset( $_POST['post_type'] ) || 'wpm-testimonial' != $_POST['post_type'] )
		return;

	global $post;

	if ( isset( $_POST['custom'] ) ) {
		foreach ( $_POST['custom'] as $key => $value ) {
			// Allow empty values to replace existing values.
			update_post_meta( $post->ID, $key, $value );
		}
	}
}
// add_action( 'save_post_wpm-testimonial', 'wpmtst_save_details' ); // WP 3.7+
add_action( 'save_post', 'wpmtst_save_details' );
