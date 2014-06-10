<?php
/*
	Plugin Name: Strong Testimonials
	Plugin URI: http://www.wpmission.com/plugins/strong-testimonials/
	Description: Collect and display testimonials.
	Author: Chris Dillon
	Version: 1.4.6
	Forked From: GC Testimonials version 1.3.2 by Erin Garscadden
	Author URI: http://www.wpmission.com/
	Text Domain: wpmtst
	Requires: 3.0 or higher
	License: GPLv3 or later


  Copyright 2014  Chris Dillon  chris@wpmission.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


define( 'WPMTST_NAME', 'wpmtst' );


/*
	Text domain.
*/
function wpmtst_textdomain() {
	load_plugin_textdomain( 'wpmtst', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wpmtst_textdomain' );


/*
	Plugin activation
*/
register_activation_hook( __FILE__, 'wpmtst_register_cpt' );
register_activation_hook( __FILE__, 'wpmtst_default_settings' );
register_activation_hook( __FILE__, 'wpmtst_flush_rewrite_rules' );

register_deactivation_hook( __FILE__, 'wpmtst_flush_rewrite_rules' );


/*
	Default settings.
*/
function wpmtst_default_settings() {
	$new_options = array(
			'per_page'     => '5',
			'admin_notify' => 0,
			'admin_email'  => '',
			'captcha'      => '',
	);

	// Don't overwrite saved options upon reactivation.
	if ( ! get_option( 'wpmtst_options' ) ) {
		update_option( 'wpmtst_options', $new_options );
	}
}


function wpmtst_flush_rewrite_rules() {
	flush_rewrite_rules();
}


/*
	Register scripts and styles.
*/

function wpmtst_scripts() {

	global $post;

	wp_register_style( 'wpmtst-style', plugins_url( '/css/wpmtst.css', __FILE__ ) );
	wp_register_style( 'wpmtst-form-style', plugins_url( '/css/wpmtst-form.css', __FILE__ ) );

	// shortcodes: all, single, random
	if ( has_shortcode( $post->post_content, 'wpmtst-all' )
			|| has_shortcode( $post->post_content, 'wpmtst-single' )
			|| has_shortcode( $post->post_content, 'wpmtst-random' )
			|| has_shortcode( $post->post_content, 'wpmtst-form' ) ) {
		wp_enqueue_style( 'wpmtst-style' );
	}

	// shortcode: all testimonials
	if ( has_shortcode( $post->post_content, 'wpmtst-all' ) ) {
		wp_enqueue_script( 'wpmtst-pager', plugins_url( '/js/quickpager.jquery.js', __FILE__ ), array( 'jquery' ) );
		add_action( 'wp_footer', 'wpmtst_pagination_function' );
	}

	// shortcode: submission form
	if ( has_shortcode( $post->post_content, 'wpmtst-form' ) ) {
		wp_enqueue_style( 'wpmtst-form-style' );
		wp_enqueue_script( 'wpmtst-validation', '//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js', array( 'jquery' ) );
		add_action( 'wp_footer', 'wpmtst_validation_function' );
	}

}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts' );


/*
	Pagination on "All Testimonials" shortcode.
*/
function wpmtst_pagination_function() {
	// $per_page = get_option( 'wpmtst_options' )['per_page']; // causes error in earlier PHP versions?
	$options = get_option( 'wpmtst_options' );
	$per_page = $options['per_page'];
	if ( ! $per_page ) {
		$per_page = '5';
	}
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#wpmtst-container").quickPager({ pageSize: <?php echo $per_page; ?>, currentPage: 1, pagerLocation: "after" });
		});
	</script>
	<?php
}


/*
	Submission form validation.
*/
function wpmtst_validation_function() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#wpmtst-submission-form").validate({});
		});
	</script>
	<?php
}


/*
	Admin scripts.
*/
function wpmtst_admin_scripts() {
	wp_enqueue_style( 'wpmtst-admin-style', plugins_url( '/css/wpmtst-admin.css', __FILE__ ) );
	wp_enqueue_script( 'wpmtst-admin-script', plugins_url( '/js/wpmtst-admin.js', __FILE__ ), array( 'jquery' ) );
}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_scripts' );


/*
	Helper: Format URL.
*/
function wpmtst_get_website( $url ) {
	if ( ! preg_match( "~^(?:f|ht)tps?://~i", $url ) ) {
		$url = 'http://' . $url;
	}
	return $url;
}


/*
	Shim: has_shortcode < WP 3.6
*/
if ( ! function_exists( 'has_shortcode' ) ) {
	function has_shortcode( $content, $tag ) {
		if ( false === strpos( $content, '[' ) ) {
			return false;
		}

		if ( shortcode_exists( $tag ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
			if ( empty( $matches ) )
				return false;

			foreach ( $matches as $shortcode ) {
				if ( $tag === $shortcode[2] )
					return true;
			}
		}
		return false;
	}
}


/*
	Shim: shortcode_exists < WP 3.6
*/
if( ! function_exists( 'shortcode_exists' ) ) {
	function shortcode_exists( $tag ) {
		global $shortcode_tags;
		return array_key_exists( $tag, $shortcode_tags );
	}
}


/*
	Function to check whether a script is queued by file name instead of handle.
*/
function wpmtst_is_queued( $filenames ) {
	global $wp_scripts;
	foreach ( $wp_scripts->registered as $handle => $script ) {
		if ( in_array( basename( $script->src ), $filenames ) ) {
			return true;
		}
	}
	return false;
}


/*
	Register Post Type and Taxonomy
*/
function wpmtst_register_cpt() {

	$testimonial_labels = array(
			'name'                  => _x( 'Testimonials', 'post type general name', WPMTST_NAME ),
			'singular_name'         => _x( 'Testimonial', 'post type singular name', WPMTST_NAME ),
			'add_new'               => __( 'Add New', WPMTST_NAME ),
			'add_new_item'          => __( 'Add New Testimonial', WPMTST_NAME ),
			'edit_item'             => __( 'Edit Testimonial', WPMTST_NAME ),
			'new_item'              => __( 'New Testimonial', WPMTST_NAME ),
			'all_items' 			      => __( 'All Testimonials', WPMTST_NAME ),
			'view_item'             => __( 'View Testimonial', WPMTST_NAME ) ,
			'search_items'          => __( 'Search Testimonials', WPMTST_NAME ),
			'not_found'             => __( 'Nothing Found', WPMTST_NAME ),
			'not_found_in_trash'    => __( 'Nothing found in Trash', WPMTST_NAME ),
			'parent_item_colon'     => ''
	);

	$testimonial_args = array(
			'labels'                => $testimonial_labels,
			'singular_label'        => __( 'testimonial', WPMTST_NAME ),
			'public'                => true,
			'show_ui'               => true,
			'capability_type'       => 'post',
			'hierarchical'          => true,
			'rewrite'               => true,
			'menu_icon'				      => 'dashicons-editor-quote',
			'menu_position'			    => 20,
			'exclude_from_search' 	=> true,
			'supports'              => array( 'title', 'excerpt', 'editor', 'thumbnail' )
			// 'supports'              => array( 'title', 'excerpt', 'editor', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'wpm-testimonial', $testimonial_args );


	$categories_labels = array(
			'name'                  => __( 'Categories', WPMTST_NAME ),
			'singular_name'         => _x( 'Category', WPMTST_NAME ),
			'all_items' 			      => __( 'All Categories', WPMTST_NAME ),
			'add_new_item'          => _x( 'Add New Category', WPMTST_NAME ),
			'edit_item'             => __( 'Edit Category', WPMTST_NAME ),
			'new_item'              => __( 'New Category', WPMTST_NAME ),
			'view_item'             => __( 'View Category', WPMTST_NAME ),
			'search_items'          => __( 'Search Category', WPMTST_NAME ),
			'not_found'             => __( 'Nothing Found', WPMTST_NAME ),
			'not_found_in_trash'    => __( 'Nothing found in Trash', WPMTST_NAME ),
			'parent_item_colon'     => ''
	);

	register_taxonomy( 'wpm-testimonial-category', array( 'wpm-testimonial' ), array(
			'hierarchical' => true,
			'labels'       => $categories_labels,
			'rewrite'      => array(
					'slug'         => 'view',
					'hierarchical' => true,
					'with_front'   => false
			)
	) );
	
}
add_action( 'init', 'wpmtst_register_cpt' );


/*
	Add Custom Columns to the Admin Screen
*/
function wpmtst_edit_columns( $columns ) {
	$columns = array(
			'cb'          => '<input type="checkbox" />',
			'title'       => __( 'Title', WPMTST_NAME ),
			'client_name' => __( 'Client', WPMTST_NAME ),
			'thumbnail'   => __( 'Thumbnail', WPMTST_NAME ),
			'category'    => __( 'Category', WPMTST_NAME ),
			'shortcode'   => __( 'Shortcode', WPMTST_NAME ),
			'date'        => __( 'Date', WPMTST_NAME ),
	);
	return $columns;
}
add_filter( 'manage_edit-wpm-testimonial_columns', 'wpmtst_edit_columns' );


/*
	Show custom values
*/
function wpmtst_custom_columns( $column ) {
	global $post;
	$custom = get_post_custom();

	if ( 'post_id' == $column ) {

		echo $post->ID;

	} elseif ( 'description' == $column ) {

		echo substr( $post->post_content, 0, 100 ) . '...';

	} elseif ( 'client_name' == $column ) {

		echo $custom['client_name'][0];

	} elseif ( 'thumbnail' == $column ) {

		echo $post->post_thumbnail;

	} elseif ( 'shortcode' == $column ) {

		echo '[wpmtst-single id="' . $post->ID . '"]';

	} elseif ( 'category' == $column ) {

		$categories = get_the_terms( 0, 'wpm-testimonial-category' );
		if ( $categories && ! is_wp_error( $categories ) ) {
			$list = array();
			foreach ( $categories as $cat ) {
				$list[] = $cat->name;
			}
			echo join( ", ", $list );		
		}

	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_custom_columns' );


/*************************/
/*   THUMBNAIL SUPPORT   */
/*************************/


/*
	Theme support for custom post type only.
*/
function wpmtst_theme_support() {
	add_theme_support( 'post-thumbnails', array( 'wpm-testimonial' ) );
}
add_action( 'after_theme_setup', 'wpmtst_theme_support' );


/*
	Add thumbnail column to admin screen list
*/
function wpmtst_add_thumbnail_column( $columns ) {
	$columns['thumbnail'] = __( 'Thumbnail', WPMTST_NAME );
	return $columns;
}
add_filter( 'manage_wpm-testimonial_posts_columns', 'wpmtst_add_thumbnail_column' );


/*
	Show thumbnail in admin screen list
*/
function wpmtst_add_thumbnail_value( $column_name, $post_id ) {
	if ( 'thumbnail' == $column_name ) {
		$width = (int) 75;
		$height = (int) 75;

		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		$attachments = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );

		if ( $thumbnail_id ) {
			$thumb = wp_get_attachment_image( $thumbnail_id, array( $width, $height ), true );
		} elseif ( $attachments ) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array( $width, $height ), true );
			}
		}

		if ( isset( $thumb ) && $thumb ) {
			echo $thumb;
		} else {
			echo __( 'None', WPMTST_NAME );
		}
	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_add_thumbnail_value', 10, 2 );


/*********************/
/*   CUSTOM FIELDS   */
/*********************/


/*
	Add custom fields to the Add / Edit screen
*/
function wpmtst_admin_init() {
	add_meta_box( 'details', 'Client Details', 'wpmtst_meta_options', 'wpm-testimonial', 'normal', 'low' );
}
add_action( 'admin_init', 'wpmtst_admin_init' );


function wpmtst_meta_options() {
	global $post;
	$custom = get_post_custom();

	if ( $custom && array_key_exists( 'client_name', $custom ) )
		$client_name = $custom['client_name'][0];
	else
		$client_name = '';

	if ( $custom && array_key_exists( 'email', $custom ) )
		$email = $custom['email'][0];
	else
		$email = '';

	if ( $custom && array_key_exists( 'company_website', $custom ) )
		$company_website = $custom['company_website'][0];
	else
		$company_website = '';

	if ( $custom && array_key_exists( 'company_name', $custom ) )
		$company_name = $custom['company_name'][0];
	else
		$company_name = '';

	?>
	<table class="options">
		<tr>
			<td colspan="2">To add a client's photo, use the <strong>Featured Image</strong> option. <div class="dashicons dashicons-arrow-right-alt"></div></td>
		</tr>
		<tr>
			<th><label for="client_name"><?php _e( 'Name', WPMTST_NAME ); ?></label></td>
			<td><input type="text" id="client_name" name="client_name" value="<?php echo $client_name; ?>" size="40"/></td>
		</tr>
		<tr>
			<th><label for="email"><?php _e( 'Email', WPMTST_NAME ); ?></label></td>
			<td><input type="text" id="email" name="email" value="<?php echo $email; ?>" size="40"/></td>
		</tr>
		<tr>
			<th><label for="company_website"><?php _e( 'Website', WPMTST_NAME ); ?></label></td>
			<td><input type="text" id="company_website" name="company_website" value="<?php echo $company_website; ?>" size="40"/></td>
		</tr>
		<tr>
			<th><label for="company_name"><?php _e( 'Company Name', WPMTST_NAME ); ?></label></td>
			<td><input type="text" id="company_name" name="company_name" value="<?php echo $company_name; ?>" size="40"/></td>
		</tr>
	</table>
	<?php
}


/*
	Update custom fields.
*/
function wpmtst_save_details() {
	// check Custom Post Type
	if ( ! isset( $_POST['post_type'] ) || 'wpm-testimonial' != $_POST['post_type'] )
		return;
	
	global $post;
	$custom_meta_fields = array( 'client_name', 'email', 'company_website', 'company_name' );

	foreach ( $custom_meta_fields as $field ) {
		// Update every field even if empty.
		if ( isset( $_POST[$field] ) ) {
			update_post_meta( $post->ID, $field, $_POST[$field] );
		}
	}
}
// add_action( 'save_post_wpm-testimonial', 'wpmtst_save_details' ); // WP 3.7+
add_action( 'save_post', 'wpmtst_save_details' );


/******************/
/*   CATEGORIES   */
/******************/


/*
	Add Columns to the Testimonials Categories Screen
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
	Show custom column
*/
function wpmtst_manage_columns( $out, $column_name, $id ) {
	if ( 'shortcode' == $column_name ) {
		$output = '[wpmtst-all category="' . $id . '"]';
	} elseif ( 'ID' == $column_name ) {
		$output = $id;
	} else {
		$output = '';
	}
	return $output;
}
add_filter( 'manage_wpm-testimonial-category_custom_column', 'wpmtst_manage_columns', 10, 3 );


/******************/
/*   SHORTCODES   */
/******************/


/*
	Add custom fields to post object.
*/
function wpmtst_get_post( $post ) {
	$custom = get_post_custom( $post->ID );
	foreach ( $custom as $key => $field ) {
		// exclude '_edit_last' and '_edit_lock'
		$keyt = trim( $key );
		if ( '_' != $keyt{0} ) {
			$post->$key = $field[0];
		}
	}
	return $post;
}


/*
	Single Testimonial LAYOUT
*/
function wpmtst_single( $post ) {
	ob_start();
	?>
	<div class="testimonial">

		<div class="inner">

			<?php if ( ! empty( $post->post_title ) ) : ?>
			<h3 class="heading"><?php echo $post->post_title; ?></h3>
			<?php endif; ?>

			<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
			<div class="photo"><?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?></div>
			<?php endif; ?>

			<div class="content"><?php echo wpautop( $post->post_content ); ?></div><!-- content -->

			<div class="clear"></div>

			<div class="client">
				<div class="name"><?php echo $post->client_name; ?></div>
				<?php if ( ! empty( $post->company_name ) && ! empty( $post->company_website ) ) : ?>
					<div class="company">
						<a href="<?php echo wpmtst_get_website( $post->company_website ); ?>" target="blank"><?php echo $post->company_name; ?></a>
					</div>
				<?php elseif ( ! empty( $post->company_name ) ) : ?>
					<div class="company"><?php echo $post->company_name; ?></div>
				<?php elseif ( ! empty( $post->company_website ) ) : ?>
					<div class="website"><?php echo $post->company_website; ?></div>
				<?php endif; ?>
			</div><!-- client -->

		</div><!-- inner -->

	</div><!-- testimonial -->

	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return do_shortcode( $html );
}


/*
	Single Testimonial Shortcode
*/
function wpmtst_single_shortcode( $atts ) {
	extract( shortcode_atts( array( 'id' => '' ), $atts ) );
	$post = wpmtst_get_post( get_post( $id ) );
	$display = wpmtst_single( $post );
	return $display;
}
add_shortcode( 'wpmtst-single', 'wpmtst_single_shortcode' );


/*
	Random Testimonial Shortcode
*/
function wpmtst_random_shortcode( $atts ) {
	extract( shortcode_atts( array( 'category' => '', 'limit' => '1' ), $atts ) );

	if ( '' != $category ) {
		$term = get_term_by( 'id', $category, 'wpm-testimonial-category' );
		$term_taxonomy = $term->taxonomy;
		$term_slug = $term->slug;
	} else {
		$term_taxonomy = '';
		$term_slug = '';
	}

	$args = array(
			$term_taxonomy   => $term_slug,
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => $limit,
			'orderby'        => 'rand',
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$posts_array  = $wp_query->query( $args );
	$display = '';

	foreach ( $posts_array as $post ) {
		$display .= wpmtst_single( wpmtst_get_post( $post ) );
	}

	return $display;
}
add_shortcode( 'wpmtst-random', 'wpmtst_random_shortcode' );


/*
	All Testimonials Shortcode
*/
function wpmtst_all_shortcode( $atts ) {
	extract( shortcode_atts( array( 'category' => '' ), $atts ) );

	if ( '' != $category ) {
		$term = get_term_by( 'id', $category, 'wpm-testimonial-category' );
		$term_taxonomy = $term->taxonomy;
		$term_slug = $term->slug;
	} else {
		$term_taxonomy = '';
		$term_slug = '';
	}

	// @todo: sort options
	$args = array(
			$term_taxonomy   => $term_slug,
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => -1,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$posts_array = $wp_query->query( $args );

	$display = '<div id="wpmtst-container">';

	foreach ( $posts_array as $post ) {
		$display .= '<div class="result">' . wpmtst_single( wpmtst_get_post( $post ) ) . '</div><!-- result -->';
	}

	$display .= '</div><!-- wpmtst-container -->';
	$display .= '<div id="pagingControls"></div>';

	return $display;
}
add_shortcode( 'wpmtst-all', 'wpmtst_all_shortcode' );


/*
	Submission Form shortcode
*/
function wpmtst_form_shortcode( $atts ) {

	$client_name     = '';
	$email           = '';
	$company_name    = '';
	$company_website = '';
	$headline        = '';
	$text            = '';
	$agree           = 1;
  
	$options = get_option( 'wpmtst_options' );
	$captcha = $options['captcha'];
  $errors = array();
	
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_submission_form' ) ) {
		
		// --------------------------------
		// start: CAPTCHA plugin handlers 
	
		switch ( $captcha ) {
		
			// Captcha by BestWebSoft
			case 'bwsmath' :
				if ( function_exists( 'cptch_check_custom_form' ) && cptch_check_custom_form() !== true ) {
					$errors['captcha'] = 'Please complete the CAPTCHA.';
				}
				break;
			
			// Simple reCAPTCHA by WPMission
			case 'wpmsrc' :
				if ( function_exists( 'wpmsrc_check' ) ) {
					// check for empty user response first
					if ( empty( $_POST['recaptcha_response_field'] ) ) {
						$errors['captcha'] = __( 'Please complete the CAPTCHA.', WPMTST_NAME );
					} else {
						// check captcha
						$response = wpmsrc_check();
						if ( ! $response->is_valid ) {
							$errors['captcha'] = __( 'The CAPTCHA was not entered correctly. Please try again.', WPMTST_NAME );
							// $response['error'] contains the actual error message, e.g. "incorrect-captcha-sol"
						}
					}
				}
				break;
		
			default :
			
		}
		
		// end: CAPTCHA plugin handlers
		// --------------------------------
		
		// --------
		// sanitize
		// --------
		
		// custom
		$client_name     = sanitize_text_field( $_POST['wpmtst_client_name'] );
		$email           = sanitize_text_field( $_POST['wpmtst_email'] );
		$company_name    = sanitize_text_field( $_POST['wpmtst_company_name'] );
		$company_website = sanitize_text_field( $_POST['wpmtst_company_website'] );
		
		// common
		$headline        = sanitize_text_field( $_POST['wpmtst_headline'] );
		$text            = sanitize_text_field( $_POST['wpmtst_text'] );
		$agree           = isset( $_POST['wpmtst_agree'] ) ? 1 : 0;

		// --------
		// validate
		// --------
		
		// custom
		if ( empty( $client_name ) )
			$errors['client_name'] = 'Please enter your name.'; 
		
		if ( empty( $email ) )
			$errors['email'] = 'Please enter your email address.';
		
		/*
		if ( empty( $company_name ) )
			$errors['company_name'] = ''; 
		
		if ( empty( $company_website ) )
			$errors['company_website'] = ''; 
		*/
		
		// common
		
		/*
		if ( empty( $headline ) )
			$errors['headline'] = ''; 
		*/
		
		if ( empty( $text ) )
			$errors['text'] = 'Please enter your testimonial.'; 
		
		if ( empty( $agree ) )
			$errors['agree'] = 'Please let us share your testimonial.'; 
		
		
    if ( ! count( $errors ) ) {
		
			// create new testimonial post
			$testimonial_data = array(
					'post_title'   => $headline,
					'post_content' => $text,
					'post_status'  => 'pending',
					'post_type'    => 'wpm-testimonial'
			);
    
			if ( $testimonial_id = wp_insert_post( $testimonial_data ) ) {

				// save custom fields
				add_post_meta( $testimonial_id, 'client_name', $client_name );
				add_post_meta( $testimonial_id, 'email', $email );
				add_post_meta( $testimonial_id, 'company_name', $company_name );
				add_post_meta( $testimonial_id, 'company_website', $company_website );

				if ( $_FILES['wpmtst_client_photo']['size'] > 1 ) {
					foreach ( $_FILES as $field => $file ) {

						// Upload File
						$overrides = array( 'test_form' => false );
						$uploaded_file = wpmtst_wp_handle_upload( $_FILES['wpmtst_client_photo'], $overrides );
						$wpmtst_client_photo = $uploaded_file['url'];

						// Create an Attachment
						$attachment = array(
								'post_title'     => $file['name'],
								'post_content'   => '',
								'post_type'      => 'attachment',
								'post_parent'    => $testimonial_id,
								'post_mime_type' => $file['type'],
								'guid'           => $uploaded_file['url']
						);

						$attach_id = wp_insert_attachment( $attachment, $uploaded_file['file'], $testimonial_id );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_file['file'] );
						wp_update_attachment_metadata( $attach_id,  $attach_data );
						add_post_meta( $testimonial_id, 'client_photo', $wpmtst_client_photo ); // ~!~ is this needed?
						set_post_thumbnail( $testimonial_id, $attach_id );

					}
				}

				$admin_notify = $options['admin_notify'];
				$admin_email  = $options['admin_email'];

				if ( $admin_notify && $admin_email ) {
					$subject = 'New testimonial for ' . get_option( 'blogname' );
					$headers = 'From: noreply@' . str_replace( 'www.', '', $_SERVER['HTTP_HOST'] );
					$message = 'New testimonial submission for ' . get_option( 'blogname' ) . '. This is awaiting action from the website administrator.';
					// More info here? A copy of testimonial? A link to admin page? A link to approve directly from email?
					wp_mail( $admin_email, $subject, $message, $headers );
				}

				return '<div class="testimonial-success">' .  __( 'Thank you! Your testimonial is awaiting moderation.', WPMTST_NAME ) .'</div>';
				
			} else {
				// need post insert error handling
			}
    
    } // if errors

	} // if posted

	/*---------------------------------*/
	/*   Testimonial Submission Form   */
	/*---------------------------------*/
	ob_start();
	?>

	<div id="wpmtst-form">

		<p class="required-notice"><span class="required symbol"></span><?php _e( 'Required Field', WPMTST_NAME ); ?></p>

		<form id="wpmtst-submission-form" method="post" action="" enctype="multipart/form-data">
			<?php echo wp_nonce_field( 'wpmtst_submission_form', 'wpmtst_form_submitted' ); ?>

			<p class="form-field">
				<label for="wpmtst_client_name"><?php _e( 'Full Name', WPMTST_NAME ); ?></label><span class="required symbol"></span>
				<input id="wpmtst_client_name" 
								class="text" 
								type="text" 
								name="wpmtst_client_name" 
								value="<?php echo $client_name; ?>"
								minlength="2" 
								required>
				<?php if ( isset( $errors['client_name'] ) ) : ?>
					<span class="error"><label class="error"><?php echo $errors['client_name']; ?></label></span>
				<?php endif; ?>
				<span class="help"><?php _e( 'What is your full name?', WPMTST_NAME ); ?></span>
			</p>
			
			<p class="form-field">
				<label for="wpmtst_email"><?php _e( 'Email', WPMTST_NAME ); ?></label><span class="required symbol"></span>
				<input id="wpmtst_email" 
								class="text email" 
								type="email" 
								name="wpmtst_email" 
								value="<?php echo $email; ?>"
								required>
				<?php if ( isset( $errors['email'] ) ) : ?>
					<span class="error"><label class="error"><?php echo $errors['email']; ?></label></span>
				<?php endif; ?>
				<span class="help"><?php _e( 'What is your email address?', WPMTST_NAME ); ?></span>
			</p>

			<p class="form-field">
				<label for="wpmtst_company_name"><?php _e( 'Company Name', WPMTST_NAME ); ?></label>
				<input id="wpmtst_company_name" 
								class="text"
								type="text" 
								name="wpmtst_company_name" 
								value="<?php echo $company_name; ?>">
				<span class="help"><?php _e( 'What is your company name?', WPMTST_NAME ); ?></span>
			</p>

			<p class="form-field">
				<label for="wpmtst_company_website"><?php _e( 'Company Website', WPMTST_NAME ); ?></label>
				<input id="wpmtst_company_website" 
								class="text"
								type="text" 
								name="wpmtst_company_website" 
								value="<?php echo $company_website; ?>">
				<span class="help"><?php _e( 'Does your company have a website?', WPMTST_NAME ); ?></span>
			</p>

			<p class="form-field">
				<label for="wpmtst_headline"><?php _e( 'Heading', WPMTST_NAME ); ?></label>
				<input id="wpmtst_headline" 
								class="text"
								type="text" 
								name="wpmtst_headline" 
								value="<?php echo $headline; ?>">
				<span class="help"><?php _e( 'A headline for your testimonial.', WPMTST_NAME ); ?></span>
			</p>

			<p class="form-field">
				<label for="wpmtst_text"><?php _e( 'Testimonial', WPMTST_NAME ); ?></label><span class="required symbol"></span>
				<textarea id="wpmtst_text" 
									class="textarea"
									name="wpmtst_text"
									required><?php echo $text; ?></textarea>
				<?php if ( isset( $errors['text'] ) ) : ?>
					<span class="error"><label class="error"><?php echo $errors['text']; ?></label></span>
				<?php endif; ?>
				<span class="help"><?php _e( 'What do you think about us?', WPMTST_NAME ); ?></span>
			</p>

			<div class="clear"></div>

			<p class="form-field">
				<label for="wpmtst_client_photo"><?php _e( 'Photo', WPMTST_NAME ); ?></label>
				<input id="wpmtst_client_photo" 
								class="text"
								type="file" 
								name="wpmtst_client_photo">
				<span class="help"><?php _e( 'Would you like to include a photo?', WPMTST_NAME ); ?></span>
			</p>

			<p class="form-field agree">
				<input id="wpmtst_agree"
							 class="checkbox" 
							 type="checkbox" 
							 name="wpmtst_agree"
							 checked="<?php checked( $agree ); ?>"
							 required>
				<?php if ( isset( $errors['agree'] ) ) : ?>
					<span class="error"><label class="error"><?php echo $errors['agree']; ?></label></span>
				<?php endif; ?>
				<span class="required symbol"></span><span><?php _e( 'I agree this testimonial may be published.', WPMTST_NAME ); ?></span>
			</p>

			<?php if ( $captcha ) : ?>
			<div class="wpmtst-captcha">
				<label for="wpmtst_captcha"><?php _e( 'Captcha', WPMTST_NAME ); ?></label><span class="required symbol"></span>
				<div class="wrap">
					<?php do_action( 'wpmtst_captcha', $captcha ); ?>
					<?php if ( isset( $errors['captcha'] ) ) : ?>
					<p><label class="error"><?php echo $errors['captcha']; ?></label></p>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			
			<p class="form-field">
				<input type="submit" 
								id="wpmtst_submit_testimonial" 
								name="wpmtst_submit_testimonial" 
								value="<?php _e( 'Add Testimonial', WPMTST_NAME ); ?>" 
								class="button" 
								validate="required:true" />
			</p>

		</form>

	</div><!-- wpmtst-form -->

	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode( 'wpmtst-form', 'wpmtst_form_shortcode' );


function wpmtst_wp_handle_upload( $file_handler, $overrides ) {
	require_once( admin_url( 'includes/image.php' ) );
	require_once( admin_url( 'includes/file.php' ) );
	require_once( admin_url( 'includes/media.php' ) );

	$upload = wp_handle_upload( $file_handler, $overrides );
	return $upload ;
}


/**************/
/*   WIDGET   */
/**************/


function wpmtst_widget_script( $arg1, $arg2, $arg3, $arg4 ) {
	// Load jQuery Cycle2 plugin (http://jquery.malsup.com/cycle2/) from CDN 
	// **if not already enqueued** by the theme or another plugin.
	
	// ---------------------------------------------------------------------
	// This checks by handle but handles can be different so this misses it:
	// (Seems to be intended for checks within the plugin itself.)
	// ---------------------------------------------------------------------
	// $list = 'enqueued';
	// if ( ! wp_script_is( 'jquery.cycle2.min.js', $list ) || ! wp_script_is( 'jquery.cycle2.js', $list ) ) {
	
	// -------------------------------------------------
	// This custom function checks by file name instead:
	// -------------------------------------------------
	if ( ! wpmtst_is_queued( array( 'jquery.cycle2.min.js', 'jquery.cycle2.js' ) ) ) {
		wp_enqueue_script( 'wpmtst-slider', '//cdn.jsdelivr.net/cycle2/20140314/jquery.cycle2.min.js', array( 'jquery' ) );
	}

	// Send arguments to Cycle function call and load it **in the footer**.
	wp_enqueue_script( 'wpmtst-widget', plugins_url( '/js/wpmtst-widget.js', __FILE__ ), array ( 'jquery' ), false, true );
	$args = array ( 'effect' => $arg1, 'speed' => $arg2 * 1000, 'timeout' => $arg3 * 1000, 'pause' => $arg4 );
	wp_localize_script( 'wpmtst-widget', 'tcycle', $args );
}
// custom hook
add_action( 'wpmtst_widget_hook', 'wpmtst_widget_script', 10, 4 );


function wpmtst_load_widget() {
	register_widget( 'WpmTst_Widget' );
}
add_action( 'widgets_init', 'wpmtst_load_widget' );


class WpmTst_Widget extends WP_Widget {

	// setup
	function WpmTst_Widget() {

		$widget_ops = array(
				'classname'   => 'wpmtst-widget',
				'description' => __( 'Several ways to show testimonials.' )
		);

		$control_ops = array(
				'id_base' => 'wpmtst-widget'
		);
		
		$this->cycle_options = array(
				'effects' => array(
						'fade'       => 'Fade',
						'scrollHorz' => 'Scroll horizontally',
						'none'       => 'None',
				)
		);

		$this->WP_Widget( 'wpmtst-widget', __( 'Strong Testimonials', WPMTST_NAME ), $widget_ops, $control_ops );

		$this->defaults = array(
				'title'         => 'Testimonials',
				'category'      => 'all',
				'mode'          => 'cycle',	// 'cycle' or 'static'
				'order'         => 'rand',
				'cycle-limit'   => 3,
				'cycle-all'     => 0,
				'cycle-timeout' => 8,
				'cycle-effect'  => 'fade',
				'cycle-speed'   => 1.5,
				'cycle-pause'   => 1,
				'static-limit'  => 2,
				'char-switch'   => 1,
				'char-limit'    => 200,
				'images'        => 0,
				'more'          => 0,
				'more_page'     => ''
		);

	}

	// display
	function widget( $args, $instance ) {
		if ( is_active_widget( false, false, $this->id_base ) ) {
			wp_enqueue_style( 'wpmtst-style' );
			// load slider with widget parameters
			do_action( 'wpmtst_widget_hook', $instance['cycle-effect'], $instance['cycle-speed'], $instance['cycle-timeout'], $instance['cycle-pause'] );
		}

		$data = array_merge( $args, $instance );

		echo $data['before_widget'];

		if ( ! empty( $data['title'] ) ) {
			echo $data['before_title'] . $data['title'] . $data['after_title'];
		};

		if ( 'rand' == $data['order'] ) {
			$orderby = 'rand';
			$order = '';
		} elseif ( 'oldest' == $data['order'] ) {
			$orderby = 'post_date';
			$order = 'ASC';
		} else {
			$orderby = 'post_date';
			$order = 'DESC';
		}

		if ( 'cycle' == $data['mode'] ) {

			if ( $data['cycle-all'] ) {
				$num = -1;
			} elseif ( ! empty( $data['cycle-limit'] ) ) {
				$num = $data['cycle-limit'];
			} else {
				$num = $this->defaults['cycle-limit'];
			}

		} else {

			if ( ! empty( $data['static-limit'] ) ) {
				$num = $data['static-limit'];
			} else {
				$num = $this->defaults['static-limit'];
			}

		}

		$char_switch = $data['char-switch'];

		if ( (int) $data['char-limit'] ) {
			$char_limit = $data['char-limit'];
		} else {
			$char_limit = $this->defaults['char-limit'];
		}

		$term_taxonomy = '';
		$term_slug = '';
		if ( 'all' != $data['category'] ) {
			$term = get_term_by( 'id', $data['category'], 'wpm-testimonial-category' );
			if ( $term ) {
				$term_taxonomy = $term->taxonomy;
				$term_slug = $term->slug;
			}
		}

		$args = array(
				$term_taxonomy   => $term_slug,
				'posts_per_page' => $num,
				'orderby'        => $orderby,
				'order'          => $order,
				'post_type'      => 'wpm-testimonial',
				'post_status'    => 'publish'
		);

		$wp_query = new WP_Query();
		$posts_array = $wp_query->query( $args );

		if ( 'cycle' == $data['mode'] ) {
			echo '<div id="tcycle">';
		}

		foreach ( $posts_array as $post ) {

			$post = wpmtst_get_post( $post );

			echo '<div class="testimonial-widget">';

			if ( ! empty( $post->post_title ) ) {
				echo '<h5>' . $post->post_title . '</h5>';
			}

			if ( $data['images'] ) {
				if ( has_post_thumbnail( $post->ID ) ) {
					echo '<div class="photo">' . get_the_post_thumbnail( $post->ID, array( 75, 75 ) ) . '</div>';
				}
			}

			// trim on word boundary
			$content = wpautop( $post->post_content );
			if ( $char_switch && strlen( $content ) > $char_limit ) {
				// find space
				$content = substr( $content, 0, strpos( $content, ' ', $char_limit ) ) . ' . . . ';
			}
			echo '<div class="content">' . $content . '</div><!-- content -->';

			echo '<div class="clear"></div>';

			echo '<div class="client">';

			echo '<div class="name">' . $post->client_name . '</div>';

			if ( ! empty( $post->company_name ) && ! empty( $post->company_website ) ) {

				echo '<div class="company">';
				echo '<a href="' . wpmtst_get_website( $post->company_website ) .'" target="blank">' . $post->company_name . '</a>';
				echo '</div>';

			} elseif ( ! empty( $post->company_name ) ) {

				echo '<div class="company">' . $post->company_name . '</div>';

			} elseif ( ! empty( $post->company_website ) ) {

				echo '<div class="website">' . $post->company_website . '</div>';

			}

		 	echo '</div><!-- client -->';

		 echo '</div><!-- testimonial-widget -->';

		}

		if ( 'cycle' == $data['mode'] ) {
			echo '</div><!-- tcycle --><div class="clear"></div>';
		}

		if ( $data['more'] ) {
			$link = get_permalink( $data['more_page'] );
			echo '<p class="wpmtst-widget-readmore"><a href="' . $link . '">'. __( 'Read More Testimonials', WPMTST_NAME ) .'</a></p>';
		}

		echo $data['after_widget'];

	}

	// form
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$order_list = array(
				'rand'   => 'Random',
				'recent' => 'Newest first',
				'oldest' => 'Oldest first'
		);

		$category_list = get_terms( 'wpm-testimonial-category', array(
				'hide_empty' 	=> false,
				'order_by'		=> 'name',
				'pad_counts'	=> true
		) );

		$pages_list = get_pages( array(
				'sort_order'  => 'ASC',
				'sort_column' => 'post_title',
				'post_type'   => 'page',
				'post_status' => 'publish'
		) );

		?>
		<div class="wpmtst-widget">

			<!-- TITLE -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ) ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="omega" />
			</p>

			<!-- CATEGORY -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category' ) ?>:</label>
				<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="omega">
					<option value="all"><?php _e( 'Show all' ) ?></option>
					<?php
					foreach ( $category_list as $category ) {
						$data['categories'][$category->term_id] = $category->name . ' (' . $category->count . ')';
						echo '<option value="' . $category->term_id . '"' . selected( $category->term_id, $instance['category'] ) . '>' . $category->name . ' (' . $category->count . ')' . '</option>';
					}
					?>
				</select>
			</p>

			<!-- ORDER -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order' ) ?>:</label>
				<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="omega">
					<?php
					foreach ( $order_list as $order => $order_label ) {
						echo '<option value="' . $order . '"' . selected( $order, $instance['order'] ) . '>' . $order_label . '</option>';
					}
					?>
				</select>
			</p>

			<!-- DISPLAY MODE -->
			<div class="wpmtst-mode">

				<ul>
					<li class="radio-tab <?php if ( 'cycle' == $instance['mode'] ) { echo ' radio-current'; } ?>">
						<label for="<?php echo $this->get_field_id( 'mode-cycle' ); ?>">
							<input  id="<?php echo $this->get_field_id( 'mode-cycle' ); ?>"
											type="radio"
											name="<?php echo $this->get_field_name( 'mode' ); ?>"
											value="cycle"
											class="wpmtst-mode-setting"
											<?php checked( $instance['mode'], 'cycle' ); ?>>
								<?php _e( 'Cycle Mode' ) ?></label>
					</li>
					<li class="radio-tab <?php if ( 'static' == $instance['mode'] ) { echo ' radio-current'; } ?>">
						<label for="<?php echo $this->get_field_id( 'mode-static' ); ?>">
							<input  id="<?php echo $this->get_field_id( 'mode-static' ); ?>"
											type="radio"
											name="<?php echo $this->get_field_name( 'mode' ); ?>"
											value="static"
											class="wpmtst-mode-setting"
											<?php checked( $instance['mode'], 'static' ); ?>>
								<?php _e( 'Static Mode' ) ?></label>
					</li>
				</ul>

				<!-- CYCLE MODE -->
				<div class="wpmtst-mode-cycle"<?php if ( 'static' == $instance['mode'] ) { echo ' style="display: none;"'; } ?>>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-limit' ); ?>"><?php _e( 'Number to show', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<input  type="text"
											id="<?php echo $this->get_field_id( 'cycle-limit' ); ?>"
											name="<?php echo $this->get_field_name( 'cycle-limit' ); ?>"
											value="<?php echo $instance['cycle-limit']; ?>"
											size="3"
											<?php if ( $instance['cycle-all'] ) { echo ' readonly="readonly"'; } ?> />
						</div>
						<div class="divider">
							<input  type="checkbox"
											id="<?php echo $this->get_field_id( 'cycle-all' ); ?>"
											name="<?php echo $this->get_field_name( 'cycle-all' ); ?>"
											<?php checked( $instance['cycle-all'], 1 ); ?>
											class="checkbox" />
							<label for="<?php echo $this->get_field_id( 'cycle-all' ); ?>"><?php _e( 'Show all', WPMTST_NAME ); ?></label>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>"><?php _e( 'Show each for', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>" name="<?php echo $this->get_field_name( 'cycle-timeout' ); ?>" value="<?php echo $instance['cycle-timeout']; ?>" size="3" /> <?php _e( 'seconds', WPMTST_NAME ); ?>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-effect' ); ?>"><?php _e( 'Transition effect', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<select id="<?php echo $this->get_field_id( 'cycle-effect' ); ?>" name="<?php echo $this->get_field_name( 'cycle-effect' ); ?>">
								<?php foreach ( $this->cycle_options['effects'] as $key => $label ) : ?>
								<option value="<?php echo $key; ?>" <?php selected( $instance['cycle-effect'], $key ); ?>><?php _e( $label ) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-speed' ); ?>"><?php _e( 'Effect duration', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-speed' ); ?>" name="<?php echo $this->get_field_name( 'cycle-speed' ); ?>" value="<?php echo $instance['cycle-speed']; ?>" size="3" /> <?php _e( 'seconds', WPMTST_NAME ); ?>
						</div>
					</div>

					<div class="row tall">
						<div>
							<input type="checkbox" id="<?php echo $this->get_field_id( 'cycle-pause' ); ?>" name="<?php echo $this->get_field_name( 'cycle-pause' ); ?>" <?php checked( $instance['cycle-pause'] ); ?>  class="checkbox" />
							<label for="<?php echo $this->get_field_id( 'cycle-pause' ); ?>"><?php _e( 'Pause on hover', WPMTST_NAME ); ?></label>
						</div>
					</div>

				</div>

				<!-- STATIC MODE -->
				<div class="wpmtst-mode-static"<?php if ( 'cycle' == $instance['mode'] ) { echo ' style="display: none;"'; } ?>>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'static-limit' ); ?>"><?php _e( 'Number to show', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'static-limit' ); ?>" name="<?php echo $this->get_field_name( 'static-limit' ); ?>" value="<?php echo $instance['static-limit']; ?>" size="3" />
						</div>
					</div>


				</div>

			</div><!-- wpmtst-mode -->

			<!-- CHARACTER LIMIT -->
			<p>
				<input  type="checkbox"
								id="<?php echo $this->get_field_id( 'char-switch' ); ?>"
								name="<?php echo $this->get_field_name( 'char-switch' ); ?>"
								<?php checked( $instance['char-switch'] ); ?>  class="checkbox" />

				<label for="<?php echo $this->get_field_id( 'char-limit' ); ?>"><?php _e( 'Character limit', WPMTST_NAME ); ?>:</label>
				<input  type="text"
								id="<?php echo $this->get_field_id( 'char-limit' ); ?>"
								name="<?php echo $this->get_field_name( 'char-limit' ); ?>"
								value="<?php echo $instance['char-limit']; ?>"
								size="3"
								<?php if ( ! $instance['char-switch'] ) { echo ' readonly="readonly"'; } ?> />
				<span class="help">Will break line on a space and add an ellipsis.</span>
			</p>

			<!-- FEATURED IMAGES -->
			<p>
				<input  type="checkbox"
								id="<?php echo $this->get_field_id( 'images' ); ?>"
								name="<?php echo $this->get_field_name( 'images' ); ?>"
								<?php checked( $instance['images'] ); ?>
								class="checkbox" />
				<label for="<?php echo $this->get_field_id('images'); ?>"><?php _e( 'Show Featured Images', WPMTST_NAME ); ?></label>
			</p>

			<!-- READ MORE LINK -->
			<p>
				<input  type="checkbox"
								id="<?php echo $this->get_field_id( 'more' ); ?>"
								name="<?php echo $this->get_field_name( 'more' ); ?>"
								<?php checked( $instance['more'] ); ?>
								class="checkbox" />
				<label for="<?php echo $this->get_field_id( 'more' ); ?>"><?php _e( 'Show "Read More" link to this page', WPMTST_NAME ); ?>:</label>
			</p>

			<p>
				<select id="<?php echo $this->get_field_id( 'more_page' ); ?>" name="<?php echo $this->get_field_name( 'more_page' ); ?>" class="widefat">
					<option value="*"><?php _e( 'Select page' ) ?></option>
					<?php foreach ( $pages_list as $pages ) : ?>
						<option value="<?php echo $pages->ID; ?>" <?php selected( $instance['more_page'], $pages->ID ); ?>><?php echo $pages->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</p>

		</div><!-- wpmtst-widget -->
		<?php
	}

	// save
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$defaults = $this->defaults;

		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['category']      = strip_tags( $new_instance['category'] );
		$instance['order']         = strip_tags( $new_instance['order'] );
		$instance['mode']          = strip_tags( $new_instance['mode'] );

		if ( ! $new_instance['cycle-limit'] ) {
			$instance['cycle-limit'] = $defaults['cycle-limit'];
		} else {
			$instance['cycle-limit'] = (int) strip_tags( $new_instance['cycle-limit'] );
		}

		$instance['cycle-all']     = isset( $new_instance['cycle-all'] ) ? 1 : 0;

		if ( ! $new_instance['cycle-timeout'] ) {
			$instance['cycle-timeout'] = $defaults['cycle-timeout'];
		} else {
			$instance['cycle-timeout'] = (float) strip_tags( $new_instance['cycle-timeout'] );
		}

		$instance['cycle-effect']  = strip_tags( $new_instance['cycle-effect'] );

		if ( ! $new_instance['cycle-speed'] ) {
			$instance['cycle-speed'] = $defaults['cycle-speed'];
		} else {
			$instance['cycle-speed'] = (float) strip_tags( $new_instance['cycle-speed'] );
		}

		$instance['cycle-pause']   = isset( $new_instance['cycle-pause'] ) ? 1 : 0;

		$instance['static-limit']  = (int) strip_tags( $new_instance['static-limit'] );

		$instance['char-switch']   = isset( $new_instance['char-switch'] ) ? 1 : 0;
		$instance['char-limit']    = (int) strip_tags( $new_instance['char-limit'] );

		if ( $instance['char-switch'] && ! $instance['char-limit'] ) {
			// if limit turned on and value cleared out then restore default value
			$instance['char-limit'] = $defaults['char-limit'];
		}

		$instance['images']        = isset( $new_instance['images'] ) ? 1 : 0;

		$instance['more']          = isset( $new_instance['more'] ) ? 1 : 0;
		$instance['more_page']     = strip_tags( $new_instance['more_page'] );

		return $instance;
	}

}


/****************/
/*   SETTINGS   */
/****************/


function wpmtst_settings_menu() {
	add_submenu_page( 'edit.php?post_type=wpm-testimonial', // $parent_slug
										'Settings',                           // $page_title
										'Settings',                           // $menu_title
										'manage_options',                     // $capability
										'settings',                           // $menu_slug
										'wpmtst_settings_page' );             // $function
										
	add_submenu_page( 'edit.php?post_type=wpm-testimonial', 
										'Shortcodes', 
										'Shortcodes', 
										'manage_options', 
										'shortcodes', 
										'wpmtst_settings_shortcodes' );
										
	add_action( 'admin_init', 'wpmtst_register_settings' );
}
add_action( 'admin_menu', 'wpmtst_settings_menu' );


/*
	Make admin menu title unique if necessary.
*/
function wpmtst_unique_menu_title() {
	// GC Testimonials (any others?)
	if ( is_plugin_active( 'gc-testimonials/testimonials.php' ) ) {
		$need_unique = true;
	} else {
		$need_unique = false;
	}

	if ( ! $need_unique ) {
		return;
	}
	
	global $menu;
	
	foreach ( $menu as $key => $menu_item ) {
		// set unique menu title
		if ( 'Testimonials' == $menu_item[0] && 'edit.php?post_type=wpm-testimonial' == $menu_item[2] ) {
			$menu[$key][0] = 'Strong Testimonials';
		}
	}
}
add_action( 'admin_menu', 'wpmtst_unique_menu_title', 100 );


function wpmtst_register_settings() {
	register_setting( 'wpmtst-settings-group', 'wpmtst_options', 'wpmtst_sanitize_options' );
}


function wpmtst_sanitize_options( $input ) {
	$input['per_page']     = (int) sanitize_text_field( $input['per_page'] );
	$input['admin_notify'] = isset( $input['admin_notify'] ) ? 1 : 0;
	$input['admin_email']  = sanitize_email( $input['admin_email'] );
	
	return $input;
}


function wpmtst_settings_page() {
	if ( ! current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	$wpmtst_options = get_option( 'wpmtst_options' );
	
	// Build list of supported Captcha plugins.
	$plugins = array(
			'bwsmath' => array( 'name' => 'Captcha by BestWebSoft', 'file' => 'captcha/captcha.php', 'active' => false ),
			'wpmsrc'  => array( 'name' => 'Simple reCAPTCHA by WPMission', 'file' => 'simple-recaptcha/simple-recaptcha.php', 'active' => false ),
	);
	
	foreach ( $plugins as $key => $plugin ) {
		$plugins[$key]['active'] = is_plugin_active( $plugin['file'] );
		// If current Captcha plugin has been deactivated, disable Captcha
		// so corresponding div does not appear on form.
		if ( $key == $wpmtst_options['captcha'] && ! $plugins[$key]['active'] ) {
			$wpmtst_options['captcha'] = '';
			update_option( 'wpmtst_options', $wpmtst_options );
		}
	}
	?>

	<div class="wrap wpmtst">

		<h2><?php _e( 'Testimonial Settings', WPMTST_NAME ); ?></h2>

		<?php if( isset( $_GET['settings-updated'] ) ) : ?>
			<div id="message" class="updated">
				<p><strong><?php _e( 'Settings saved.' ) ?></strong></p>
			</div>
		<?php endif; ?>

		<form method="post" action="options.php">

			<?php settings_fields( 'wpmtst-settings-group' ); ?>
			<?php $wpmtst_options = get_option( 'wpmtst_options' ); ?>
			
			<table class="form-table">
			
				<tr valign="top">
					<th scope="row">Number of testimonials to show per page</th>
					<td>
						<input type="text" 
										name="wpmtst_options[per_page]" size="3"
										value="<?php echo esc_attr( $wpmtst_options['per_page'] ); ?>">
					</td>
				</tr>				

				<tr valign="top">
					<th scope="row">When new testimonial is submitted</th>
					<td>
						<label>
							<input id="wpmtst-options-admin-notify"
											type="checkbox" 
											name="wpmtst_options[admin_notify]"
											<?php checked( $wpmtst_options['admin_notify'] ); ?>>
							<?php _e( 'Send notification email to', WPMTST_NAME ); ?>
						</label>
						<input id="wpmtst-options-admin-email"
										type="email" 
										size="30" 
										placeholder="email address"
										name="wpmtst_options[admin_email]" 
										value="<?php echo esc_attr( $wpmtst_options['admin_email'] ); ?>">
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">CAPTCHA plugin</th>
					<td>
						<select name="wpmtst_options[captcha]">
							<option value="">None</option>
							<?php foreach ( $plugins as $key => $plugin ) : ?>
							<?php if ( $plugin['active'] ) : ?>
							<option value="<?php echo $key; ?>" <?php selected( $wpmtst_options['captcha'], $key ); ?>><?php echo $plugin['name']; ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				
			</table>

			<?php submit_button(); ?>

		</form>

	</div> <!-- wrap -->

<?php
}

function wpmtst_settings_shortcodes() {
?>
	<div class="wrap wpmtst">

		<h2><?php _e( 'Shortcodes', WPMTST_NAME ); ?></h2>

		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>All Testimonials</h3></td>
			</tr>
			<tr>
				<td>Show all from all categories.</td><td>[wpmtst-all]</td>
			</tr>
			<tr>
				<td>Show all from a specific category.<br> Find these on the <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial' ); ?>">categories screen</a>.</td><td>[wpmtst-all category="xx"]</td>
			</tr>
		</table>
		
		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>Random Testimonial</h3></td>
			</tr>
			<tr>
				<td>Show a single random testimonial.</td><td>[wpmtst-random]</td>
			</tr>
			<tr>
				<td>Show a certain number of testimonials.</td><td>[wpmtst-random limit="x"]</td>
			</tr>
			<tr>
				<td>Show a single random testimonial from a specific category.<br>Find these on the <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial' ); ?>">categories screen</a>.</td>
				<td>[wpmtst-random category="xx"]</td>
			</tr>
			<tr>
				<td>Show a certain number from a specific category.</td><td>[wpmtst-random category="xx" limit="x"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>Single Testimonial</h3></td>
			</tr>
			<tr>
				<td> Show one specific testimonial.<br>Find these on the <a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial' ); ?>">testimonials screen</a>.</td><td>[wpmtst-single id="xx"]</td>
			</tr>
		</table>
		
		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>Testimonial Submission Form</h3></td>
			</tr>
			<tr>
				<td>Show a form for visitors to submit testimonials.<br>New testimonials are in "Pending" status until<br> published by an administrator.</td><td>[wpmtst-form]</td>
			</tr>
		</table>

	</div><!-- wrap -->

	<?php
}


/***************/
/*   CAPTCHA   */
/***************/


function wpmtst_add_captcha( $captcha ) {

	switch ( $captcha ) {
		
		case 'akismet' :
			break;
		
		case 'bwsmath' : // Captcha by BestWebSoft
			if ( function_exists( 'cptch_display_captcha_custom' ) ) {
				?><input type="hidden" name="cntctfrm_contact_action" value="true"><?php
				echo cptch_display_captcha_custom();
			}
			break;
			
		case 'wpmsrc' : // Strong reCAPTCHA by WPMission
			if ( function_exists( 'wpmsrc_display' ) ) { 
				echo wpmsrc_display();
			}
			break;
			
		default :
			// no captcha
			
	}
}

add_action( 'wpmtst_captcha', 'wpmtst_add_captcha', 50, 1 );
