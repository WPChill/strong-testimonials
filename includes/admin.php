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
	// Check for new options in plugin activation/update
	wpmtst_default_settings();
}
add_action( 'admin_init', 'wpmtst_admin_init' );


/*
 * Admin scripts.
 */
function wpmtst_admin_scripts( $hook ) {
	if ( in_array( $hook, array( 
				'wpm-testimonial_page_settings',
				'wpm-testimonial_page_fields',
				'wpm-testimonial_page_shortcodes',
				'wpm-testimonial_page_guide',
				'widgets.php',
				'edit.php',
				'edit-tags.php',
				'post.php',
				'post-new.php',
			) ) ) {
		wp_enqueue_style( 'wpmtst-admin-style', WPMTST_DIR . 'css/wpmtst-admin.css' );
	}
	if ( in_array( $hook, array( 
				'wpm-testimonial_page_settings',
				'wpm-testimonial_page_fields',
				'widgets.php',
			) ) ) {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'wpmtst-admin-script', WPMTST_DIR . 'js/wpmtst-admin.js', array( 'jquery' ) );
		wp_localize_script( 'wpmtst-admin-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_scripts' );


/*
 * Add meta box to the post editor screen and place above Custom Fields
 */
function wpmtst_add_meta_boxes() {
	add_meta_box( 'details', __( 'Client Details', 'strong-testimonials' ), 'wpmtst_meta_options', 'wpm-testimonial', 'normal', 'core' );
}
add_action( 'add_meta_boxes_wpm-testimonial', 'wpmtst_add_meta_boxes' );

function wpmtst_reorder_meta_boxes() {
	global $wp_meta_boxes;
	if ( ! isset( $wp_meta_boxes['wpm-testimonial'] ) ) 
		return;
	
	$core = $wp_meta_boxes['wpm-testimonial']['normal']['core'];
	$newcore = array();
	if ( $core['postexcerpt'] )
		$newcore['postexcerpt'] = $core['postexcerpt'];
	if ( $core['details'] )
		$newcore['details'] = $core['details'];
	if ( $core['postcustom'] )
		$newcore['postcustom'] = $core['postcustom'];
	if ( $core['slugdiv'] )
		$newcore['slugdiv'] = $core['slugdiv'];
		
	if ( $newcore ) 
		$wp_meta_boxes['wpm-testimonial']['normal']['core'] = $newcore;
}
add_action( 'do_meta_boxes', 'wpmtst_reorder_meta_boxes' );


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
			<td colspan="2"><?php _e( 'To add a client&apos;s photo, use the Featured Image option.', 'strong-testimonials' ); ?>&nbsp;<div class="dashicons dashicons-arrow-right-alt"></div></td>
		</tr>
		<?php foreach ( $field_groups[ $fields['current_field_group'] ]['fields'] as $key => $field ) { ?>
		<?php if ( 'custom' == $field['record_type'] ) { ?>
		<tr>
			<th><label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label></td>
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
			'title' => __( 'Title', 'strong-testimonials' ),
			'post_excerpt' => __( 'Excerpt', 'strong-testimonials' ),
	);
	
	foreach ( $fields as $key => $field ) {
		if ( $field['admin_table'] ) {
			if ( 'featured_image' == $field['name'] )
				$columns['thumbnail'] = __( 'Thumbnail', 'strong-testimonials' );
			elseif ( 'post_title' == $field['name'] )
				continue; // is set above
			else
				$columns[ $field['name'] ] = $field['label'];
		}
	}
	$columns['category']  = __( 'Category', 'strong-testimonials' );
	$columns['shortcode'] = __( 'Shortcode', 'strong-testimonials' );
	$columns['date']      = __( 'Date', 'strong-testimonials' );

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
	elseif ( 'post_excerpt' == $column ) {
		echo $post->post_excerpt;
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
	$columns['thumbnail'] = __( 'Thumbnail', 'strong-testimonials' );
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
			echo __( 'None', 'strong-testimonials' );
	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_add_thumbnail_value', 10, 2 );


/*
 * Add columns to the testimonials categories screen
 */
function wpmtst_manage_categories( $columns ) {
	$new_columns = array(
			'cb'        => '<input type="checkbox" />',
			'ID'        => __( 'ID', 'strong-testimonials' ),
			'name'      => __( 'Name', 'strong-testimonials' ),
			'slug'      => __( 'Slug' ),
			'shortcode' => __( 'Shortcode', 'strong-testimonials' ),
			'posts'     => __( 'Posts' )
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
