<?php
/**
 * Plugin Name: Strong Testimonials
 * Plugin URI: http://www.wpmission.com/plugins/strong-testimonials/
 * Description: Collect and display testimonials.
 * Author: Chris Dillon
 * Version: 1.7
 * Forked From: GC Testimonials version 1.3.2 by Erin Garscadden
 * Author URI: http://www.wpmission.com/contact
 * Text Domain: strong-testimonials
 * Requires: 3.5 or higher
 * License: GPLv3 or later
 *
 * Copyright 2014  Chris Dillon  chris@wpmission.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/*----------------------------------------------------------------------------*
 * Setup
 *----------------------------------------------------------------------------*/

define( 'WPMTST_NAME', 'strong-testimonials' );

/*
 * Plugin action links
 */
function wpmtst_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=wpm-testimonial&page=settings' ) . '">' . __( 'Settings', WPMTST_NAME ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'wpmtst_plugin_action_links', 10, 2 );

/*
 * Plugin meta row
 */
function wpmtst_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
	if ( $plugin_file == plugin_basename( __FILE__ ) ) {
		$plugin_meta[] = '<a href="http://www.wpmission.com/donate" target="_blank">Donate</a>';
	}
	return $plugin_meta;
}
add_filter( 'plugin_row_meta', 'wpmtst_plugin_row_meta', 10, 4 );

/*
 * Text domain.
 */
function wpmtst_textdomain() {
	load_plugin_textdomain( WPMTST_NAME, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wpmtst_textdomain' );

/*
 * Plugin activation
 */
register_activation_hook( __FILE__, 'wpmtst_activation' );
register_activation_hook( __FILE__, 'wpmtst_register_cpt' );
register_activation_hook( __FILE__, 'wpmtst_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'wpmtst_flush_rewrite_rules' );

function wpmtst_flush_rewrite_rules() {
	flush_rewrite_rules();
}

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
 * Plugin activation and upgrade.
 */
function wpmtst_activation() {
	// -1- DEFAULTS
	$plugin_data = get_plugin_data( __FILE__, false );
	$plugin_version = $plugin_data['Version'];
	include( plugin_dir_path( __FILE__ ) . 'defaults.php');

	// -2- GET OPTIONS
	$options = get_option( 'wpmtst_options' );
	$fields = get_option( 'wpmtst_fields' );

	if ( ! $options ) {
		// -2A- NEW ACTIVATION
		update_option( 'wpmtst_options', $default_options );
		update_option( 'wpmtst_fields', $default_fields );
	}
	else {
		// -2B- UPGRADE?
		if ( ! isset( $options['plugin_version'] )
					|| $options['plugin_version'] != $plugin_version
					|| '127.0.0.1' === $_SERVER['SERVER_ADDR'] ) {
			
			// if updating from 1.5+ to 1.7
			// individual cycle shortcode settings are now grouped
			if ( isset( $options['cycle-order'] ) ) {
				$options['cycle'] = array(
						'cycle-order'   => $options['cycle-order'],
						'cycle-effect'  => $options['cycle-effect'],
						'cycle-speed'   => $options['cycle-speed'],
						'cycle-timeout' => $options['cycle-timeout'],
						'cycle-pause'   => $options['cycle-pause'],
				);
				unset( 
					$options['cycle-order'],
					$options['cycle-effect'],
					$options['cycle-speed'],
					$options['cycle-timeout'],
					$options['cycle-pause']
				);
			}

			// merge in new options
			$options = array_merge( $default_options, $options );
			$options['plugin_version'] = $plugin_version;
			update_option( 'wpmtst_options', $options );
			
			// merge in new fields
			$fields = array_merge( $default_fields, $fields );
			update_option( 'wpmtst_fields', $fields );
		}
	}
}

/*
 * Register Post Type and Taxonomy
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
 * Theme support for this custom post type only.
 */
function wpmtst_theme_support() {
	add_theme_support( 'post-thumbnails', array( 'wpm-testimonial' ) );
}
add_action( 'after_theme_setup', 'wpmtst_theme_support' );

/*
 * Register scripts and styles.
 */
function wpmtst_scripts() {
	global $post;

	wp_register_style( 'wpmtst-style', plugins_url( '/css/wpmtst.css', __FILE__ ) );
	wp_register_style( 'wpmtst-form-style', plugins_url( '/css/wpmtst-form.css', __FILE__ ) );

	wp_register_script( 'wpmtst-pager-plugin', plugins_url( '/js/quickpager.jquery.js', __FILE__ ), array( 'jquery' ) );
	wp_register_script( 'wpmtst-validation-plugin', plugins_url( '/js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ) );

	wp_register_script( 'wpmtst-cycle-plugin', plugins_url( '/js/jquery.cycle2.min.js', __FILE__ ), array( 'jquery' ) );
	wp_register_script( 'wpmtst-cycle-script', plugins_url( '/js/wpmtst-cycle.js', __FILE__ ), array ( 'jquery' ), false, true );

	if ( $post ) {

		if ( has_shortcode( $post->post_content, 'wpmtst-all' ) ) {
			wp_enqueue_style( 'wpmtst-style' );
			wp_enqueue_script( 'wpmtst-pager-plugin' );
			add_action( 'wp_footer', 'wpmtst_pagination_function' );
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-form' ) ) {
			wp_enqueue_style( 'wpmtst-style' );
			wp_enqueue_style( 'wpmtst-form-style' );
			wp_enqueue_script( 'wpmtst-validation-plugin' );
			add_action( 'wp_footer', 'wpmtst_validation_function' );
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-cycle' ) ) {
			wp_enqueue_style( 'wpmtst-style' );
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-single' ) ) {
			wp_enqueue_style( 'wpmtst-style' );
		}

		if ( has_shortcode( $post->post_content, 'wpmtst-random' ) ) {
			wp_enqueue_style( 'wpmtst-style' );
		}

	}
}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts' );

/*
 * Pagination on "All Testimonials" shortcode.
 */
function wpmtst_pagination_function() {
	// $per_page = get_option( 'wpmtst_options' )['per_page']; // only PHP 5.3+ ?
	$options  = get_option( 'wpmtst_options' );
	$per_page = $options['per_page'] ? $options['per_page'] : 5;
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#wpmtst-container").quickPager({ pageSize: <?php echo $per_page; ?>, currentPage: 1, pagerLocation: "after" });
		});
	</script>
	<?php
}

/*
 * Submission form validation.
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


/*----------------------------------------------------------------------------*
 * Getters, Shims, Helpers
 *----------------------------------------------------------------------------*/

/*
 * Add post custom fields to post object.
 */
function wpmtst_get_post( $post ) {
	$custom = get_post_custom( $post->ID );
	$options = get_option( 'wpmtst_options' );
	$fields = get_option( 'wpmtst_fields' );
	$field_groups = $fields['field_groups'];

	// Only add on fields from current field group.
	foreach ( $field_groups[ $fields['current_field_group'] ]['fields'] as $key => $field ) {
		if ( isset( $custom[$key] ) )
			$post->$key = $custom[$key][0];
		else
			$post->$key = '';
	}
	return $post;
}

/*
 * Normalize empty shortcode attributes
 * (turns atts into tags - brilliant!)
 * Thanks http://wordpress.stackexchange.com/a/123073/32076
 */
if ( ! function_exists( 'normalize_empty_atts' ) ) {
	function normalize_empty_atts( $atts ) {
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
			return $atts;
		}
	}
}

/*
 * Get category.
 */
function wpmtst_get_terms( $category ) {
	if ( '' != $category ) {
		$term = get_term_by( 'id', $category, 'wpm-testimonial-category' );
		$term_taxonomy = $term->taxonomy;
		$term_slug     = $term->slug;
	}
	else {
		$term_taxonomy = '';
		$term_slug     = '';
	}
	return array( 'taxo' => $term_taxonomy, 'term' => $term_slug );
}

/*
 * Helper: Format URL.
 */
function wpmtst_get_website( $url ) {
	if ( ! preg_match( "~^(?:f|ht)tps?://~i", $url ) )
		$url = 'http://' . $url;

	return $url;
}

/*
 * Check whether a script is registered by file name instead of handle.
 *
 * @param array $filenames possible versions of one script, e.g. plugin.js, plugin-min.js, plugin-1.2.js
 * @return bool
 */
function wpmtst_is_queued( $filenames ) {
	global $wp_scripts;
	$registered = false;
	foreach ( $wp_scripts->registered as $handle => $script ) {
		if ( in_array( basename( $script->src ), $filenames ) ) {
			$registered = true;
			break;
		}
	}
	if ( $registered ) {
		if ( in_array( $handle, $wp_scripts->queue ) ) {
			return true;
		}
	}
	return false;
}

/*
 * Shim: has_shortcode < WP 3.6
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
 * Shim: shortcode_exists < WP 3.6
 */
if( ! function_exists( 'shortcode_exists' ) ) {
	function shortcode_exists( $tag ) {
		global $shortcode_tags;
		return array_key_exists( $tag, $shortcode_tags );
	}
}


/*----------------------------------------------------------------------------*
 * Admin
 *----------------------------------------------------------------------------*/

function wpmtst_admin_init() {
	// Check WordPress version
	wpmtst_version_check();
}
add_action( 'admin_init', 'wpmtst_admin_init' );

/*
 * Admin scripts.
 */
function wpmtst_admin_scripts() {
	wp_enqueue_style( 'wpmtst-admin-style', plugins_url( '/css/wpmtst-admin.css', __FILE__ ) );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'wpmtst-admin-script', plugins_url( '/js/wpmtst-admin.js', __FILE__ ), array( 'jquery' ) );
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
 * Update custom fields
 */
function wpmtst_save_details() {
	// check Custom Post Type
	if ( ! isset( $_POST['post_type'] ) || 'wpm-testimonial' != $_POST['post_type'] )
		return;

	global $post;

	foreach ( $_POST['custom'] as $key => $value ) {
		// Allow empty values to replace existing values.
		update_post_meta( $post->ID, $key, $value );
	}
}
// add_action( 'save_post_wpm-testimonial', 'wpmtst_save_details' ); // WP 3.7+
add_action( 'save_post', 'wpmtst_save_details' );

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


/*----------------------------------------------------------------------------*
 * Shortcodes
 *----------------------------------------------------------------------------*/

/*
 * Single Testimonial LAYOUT
 */
function wpmtst_single( $post ) {
	$html = '<div class="testimonial">';
	$html .= '<div class="inner">';
	
	if ( ! empty( $post->post_title ) )
		$html .= '<h3 class="heading">' . $post->post_title .'</h3>';
	
	if ( has_post_thumbnail( $post->ID ) )
		$html .= '<div class="photo">' . get_the_post_thumbnail( $post->ID, 'thumbnail' ) . '</div>';

	$html .= '<div class="content">' . wpautop( $post->post_content ) . '</div>';
	$html .= '<div class="clear"></div>';

	$html .= '<div class="client">';
	$html .= wpmtst_client_info( $post );
	$html .= '</div><!-- client -->';
	
	$html .= '</div><!-- inner -->';
	$html .= '</div><!-- testimonial -->';
	
	// render other shortcodes in content,
	// this will render the client_info shortcodes too
	return do_shortcode( $html );
}

/*
 * Assemble and display client info
 */
function wpmtst_client_info( $post ) {
	// ---------------------------------------------------------------------
	// Get the client template, populate it with data from the current post,
	// then render it.
	//
	// Third approach. Took me all day on 6/30/2014.
	// ---------------------------------------------------------------------
	
	$html = '';
	$options = get_option( 'wpmtst_options' );
	$fields = get_option( 'wpmtst_fields' );
	$template = $options['client_section'];
	
	$lines = explode( PHP_EOL, $template );
	// [wpmtst-text field="client_name" class="name"]
	// [wpmtst-link url="company_website" text="company_name" target="_blank" class="company"]
	
	foreach ( $lines as $line ) {
		// to get shortcode:
		$pattern = '/\[([a-z0-9_\-]+)/';
		preg_match( $pattern, $line, $matches );
		if ( $matches ) {
			$shortcode = $matches[1];
			if ( 'wpmtst-text' == $shortcode ) {
				// to get field:
				$pattern = '/field="(\w+)"/';
				preg_match( $pattern, $line, $matches2 );
				if ( $matches2 ) {
					$field_name = $matches2[1];
					$post_value = $post->$field_name;
					// add to line as content and close shortcode
					$line .= $post_value . '[/' . $shortcode . ']';
					$html .= $line;
				}
			}
			elseif ( 'wpmtst-link' == $shortcode ) {
				// (\w+)="(\w+)"
				// to get url:
				$pattern = '/url="(\w+)"/';
				preg_match( $pattern, $line, $matches2 );
				if ( $matches2 ) {
					$field_name = $matches2[1];
					$post_value = $post->$field_name;
					// add to line as content with separator
					$line .= $post_value . '|';
				}
				// to get text:
				$pattern = '/text="(\w+)"/';
				preg_match( $pattern, $line, $matches3 );
				if ( $matches3 ) {
					$field_name = $matches3[1];
					$post_value = $post->$field_name;
					// add to line as content
					$line .= $post_value;
				}
				// close shortcode
				$line .= '[/' . $shortcode . ']';
				$html .= $line;
			}
		}
	}
	// return do_shortcode( $html );
	return $html;
}

/*
 * Client text field shortcode.
 */
function wpmtst_text_shortcode( $atts, $content = null ) {
	// bail if no content
	if ( empty( $content ) || '|' === $content )
		return;
		
	extract( shortcode_atts(
		array( 'field' => '', 'class' => '' ),
		normalize_empty_atts( $atts )
	) );
	return '<div class="' . $class . '">' . $content . '</div>';
}
add_shortcode( 'wpmtst-text', 'wpmtst_text_shortcode' );

/*
 * Client link shortcode.
 */
function wpmtst_link_shortcode( $atts, $content = null ) {
	// content like "company_website|company_name"
	// bail if no content
	if ( empty( $content ) || '|' === $content )
		return;

	extract( shortcode_atts(
		array( 'url' => '', 'target' => '_blank', 'text' => '', 'class' => '' ),
		normalize_empty_atts( $atts )
	) );
		
	list( $url, $text ) = explode( '|', $content );
	
	// if no company name, use domain name
	if ( ! $text )
		$text = preg_replace( "(^https?://)", "", $url );
		
	// if no url, return text_shortcode instead
	if ( $url )
		return '<div class="' . $class . '"><a href="' . $url . '" target="' . $target . '">' . $text . '</a></div>';
	else
		return '<div class="' . $class . '">' . $text . '</div>';
}
add_shortcode( 'wpmtst-link', 'wpmtst_link_shortcode' );

/*
 * Single testimonial shortcode
 */
function wpmtst_single_shortcode( $atts ) {
	extract( shortcode_atts( array( 'id' => '' ), $atts ) );
	$post = wpmtst_get_post( get_post( $id ) );
	$display = wpmtst_single( $post );
	return $display;
}
add_shortcode( 'wpmtst-single', 'wpmtst_single_shortcode' );

/*
 * Random testimonial shortcode
 */
function wpmtst_random_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 'category' => '', 'limit' => '1' ),
		normalize_empty_atts( $atts )
	) );

	$terms = wpmtst_get_terms( $category );

	$args = array(
			$terms['taxo']   => $terms['term'],
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => $limit,
			'orderby'        => 'rand',
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$results  = $wp_query->query( $args );

	$display = '';
	foreach ( $results as $post ) {
		$display .= wpmtst_single( wpmtst_get_post( $post ) );
	}
	return $display;
}
add_shortcode( 'wpmtst-random', 'wpmtst_random_shortcode' );

/*
 * All testimonials shortcode
 *
 * @TODO:
 * - sort options in query
 */
function wpmtst_all_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 'category' => '' ),
		normalize_empty_atts( $atts )
	) );

	$terms = wpmtst_get_terms( $category );

	$args = array(
			$terms['taxo']   => $terms['term'],
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => -1,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$results = $wp_query->query( $args );

	$display = '<div id="wpmtst-container">';
	foreach ( $results as $post ) {
		$display .= '<div class="result">' . wpmtst_single( wpmtst_get_post( $post ) ) . '</div><!-- result -->';
	}
	$display .= '</div><!-- wpmtst-container -->';
	$display .= '<div id="pagingControls"></div>';

	return $display;
}
add_shortcode( 'wpmtst-all', 'wpmtst_all_shortcode' );

/*
 * Cycle testimonials shortcode
 *
 * @TODO:
 * - sort options in query
 */
function wpmtst_cycle_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 'category' => '' ),
		normalize_empty_atts( $atts )
	) );
	$options = get_option( 'wpmtst_options' );
	$cycle = $options['cycle'];

	do_action( 
		'wpmtst_cycle_hook', 
		$cycle['cycle-effect'], 
		$cycle['cycle-speed'], 
		$cycle['cycle-timeout'], 
		$cycle['cycle-pause'],
		'#wpmtst-container',
		'cycleShortcode'
	);

	if ( 'rand' == $cycle['cycle-order'] ) {
		$orderby = 'rand';
		$order   = '';
	}
	elseif ( 'oldest' == $cycle['cycle-order'] ) {
		$orderby = 'post_date';
		$order   = 'ASC';
	}
	else {
		$orderby = 'post_date';
		$order   = 'DESC';
	}

	$terms = wpmtst_get_terms( $category );

	$args = array(
			$terms['taxo']   => $terms['term'],
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => -1,
			'orderby'        => $orderby,
			'order'          => $order,
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$results = $wp_query->query( $args );

	$display = '<div id="wpmtst-container" class="tcycle">';
	foreach ( $results as $post ) {
		$display .= '<div class="result">' . wpmtst_single( wpmtst_get_post( $post ) ) . '</div><!-- result -->';
	}
	$display .= '</div><!-- wpmtst-container -->';

	return $display;
}
add_shortcode( 'wpmtst-cycle', 'wpmtst_cycle_shortcode' );

/*
 * Submission form shortcode
 */
function wpmtst_form_shortcode( $atts ) {
	$options = get_option( 'wpmtst_options' );
	$field_options = get_option( 'wpmtst_fields' );
	$captcha = $options['captcha'];

	$field_groups = $field_options['field_groups'];
	$current_field_group = $field_groups[ $field_options['current_field_group'] ];
	$fields = $current_field_group['fields'];
  
	$errors = array();
	
	// Init three arrays: post, post_meta, attachment(s).
	$testimonial_post = array(
			'post_status'  => 'pending',
			'post_type'    => 'wpm-testimonial'
	);
	$testimonial_meta = array();
	$testimonial_att = array();

	foreach ( $fields as $key => $field ) {
		$testimonial_inputs[ $field['name'] ] = '';
	}

	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_submission_form' ) ) {

		$errors = wpmtst_captcha_check( $captcha, $errors );

		// -------------------
		// sanitize & validate
		// -------------------
		foreach ( $fields as $key => $field ) {

			if ( isset( $field['required'] ) && $field['required'] && empty( $_POST[ $field['name'] ] ) ) {
				$errors[ $field['name'] ] = $field['error'];
			}
			else {
			
				if ( 'post' == $field['record_type'] ) {
				
					if ( 'file' == $field['input_type'] )
						$testimonial_att[ $field['name'] ] = isset( $field['map'] ) ? $field['map'] : 'post';
					else
						$testimonial_post[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
						
				}
				elseif ( 'custom' == $field['record_type'] ) {
				
					if ( 'email' == $field['input_type'] ) {
						$testimonial_meta[ $field['name'] ] = sanitize_email( $_POST[ $field['name'] ] );
					}
					elseif ( 'url' == $field['input_type'] ) {
						// wpmtst_get_website() will prefix with "http://"
						// so don't add that to an empty input
						if ( $_POST[ $field['name'] ] )
							$testimonial_meta[ $field['name'] ] = esc_url_raw( wpmtst_get_website( $_POST[ $field['name'] ] ) );
					}
					elseif ( 'text' == $field['input_type'] ) {
						$testimonial_meta[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
					}
					
				}
				
			}

		}

		// special handling:
		// if post_title is not required, create one from post_content
		if ( ! $testimonial_post['post_title'] ) {
			$words_array = explode( ' ', $testimonial_post['post_content'] );
			$five_words = array_slice( $words_array, 0, 5 );
			$testimonial_post['post_title'] = implode( ' ', $five_words );
		}

    if ( ! count( $errors ) ) {
	
			// create new testimonial post
			if ( $testimonial_id = wp_insert_post( $testimonial_post ) ) {

				// save custom fields
				foreach ( $testimonial_meta as $key => $field ) {
					add_post_meta( $testimonial_id, $key, $field );
				}

				// save attachments
				foreach ( $testimonial_att as $name => $map ) {
					if ( isset( $_FILES[$name] ) && $_FILES[$name]['size'] > 1 ) {
						$file = $_FILES[$name];
						
						// Upload file
						$overrides = array( 'test_form' => false );
						$uploaded_file = wpmtst_wp_handle_upload( $file, $overrides );
						$image = $uploaded_file['url'];

						// Create an attachment
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
						$result = wp_update_attachment_metadata( $attach_id,  $attach_data );
						add_post_meta( $testimonial_id, $name, $image );
						if ( 'featured_image' == $map ) {
							set_post_thumbnail( $testimonial_id, $attach_id );
						}
					}
				}

				wpmtst_notify_admin();
				return '<div class="testimonial-success">' .  __( 'Thank you! Your testimonial is awaiting moderation.', WPMTST_NAME ) .'</div>';

			}
			else {
				// @TODO post insert error handling
			}

		}
		else {  // errors
			$testimonial_inputs = array_merge( $testimonial_inputs, $testimonial_post, $testimonial_meta );
    }

	}  // if posted

	// ---------------------------
	// Testimonial Submission Form
	// ---------------------------
	// output buffering made this incredibly unreadable
	
	$html = '<div id="wpmtst-form">';
	$html .= '<p class="required-notice"><span class="required symbol"></span>' . __( 'Required Field', WPMTST_NAME ) . '</p>';
	$html .= '<form id="wpmtst-submission-form" method="post" action="" enctype="multipart/form-data">';
	$html .= wp_nonce_field( 'wpmtst_submission_form', 'wpmtst_form_submitted', true, false );

	foreach ( $fields as $key => $field ) {

		if ( 'text' == $field['input_type'] )
			$classes = 'text';
		elseif ( 'email' == $field['input_type'] )
			$classes = 'text email';
		elseif ( 'url' == $field['input_type'] )
			$classes = 'text url';
		else
			$classes = '';

		$html .= '<p class="form-field">';
		$html .= '<label for="wpmtst_' . $field['name'] . '">' . __( $field['label'], WPMTST_NAME ) . '</label>';

		if ( isset( $field['required'] ) && $field['required'] )
			$html .= '<span class="required symbol"></span>';

		if ( isset( $field['before'] ) && $field['before'] )
			$html .= '<span class="before">' . $field['before'] . '</span>';

		// -----------------------------
		// input types: text, email, url
		// -----------------------------
		if ( in_array( $field['input_type'], array( 'text', 'email', 'url' ) ) ) {

			$html .= '<input id="wpmtst_' . $field['name'] . '"'
						. ' type="' . $field['input_type'] . '"'
						. ' class="' . $classes . '"'
						. ' name="' . $field['name'] . '"'
						. ' value="' . $testimonial_inputs[ $field['name'] ] . '"';

			if ( isset( $field['placeholder'] ) && $field['placeholder'] )
				$html .= ' placeholder="' . $field['placeholder'] . '"';

			if ( isset( $field['required'] ) && $field['required'] )
				$html .= ' required';

			$html .= ' />';

		}
		// ------------------------------------------
		// input type: textarea <-- post_content ONLY
		// ------------------------------------------
		elseif ( 'textarea' == $field['input_type'] ) {

			$html .= '<textarea id="wpmtst_' . $field['name'] . '" class="textarea" name="' . $field['name'] . '"';
			
			if ( isset( $field['required'] ) && $field['required'] )
				$html .= ' required';
				
			if ( isset( $field['placeholder'] ) && $field['placeholder'] )
				$html .= ' placeholder="' . $field['placeholder'] . '"';
			
			$html .= '>' . $testimonial_inputs[ $field['name'] ] . '</textarea>';

		}
		// -----------------
		// input type: image
		// -----------------
		elseif ( 'file' == $field['input_type'] ) {

			$html .= '<input id="wpmtst_' . $field['name'] . '" class="" type="file" name="' . $field['name'] . '" />';

		}

		if ( isset( $errors[ $field['name'] ] ) )
			$html .= '<span class="error">' . $errors[ $field['name'] ] . '</span>';

		if ( isset( $field['after']) && $field['after'] )
			$html .= '<span class="after">' . $field['after'] . '</span>';

		$html .= '</p>';

	}

	if ( $captcha ) {
		$html .= '<div class="wpmtst-captcha">';
		$html .= '<label for="wpmtst_captcha">' . __( 'Captcha', WPMTST_NAME ) . '</label><span class="required symbol"></span>';
		$html .= '<div class="wrap">';
		do_action( 'wpmtst_captcha', $captcha );
		if ( isset( $errors['captcha'] ) )
			$html .= '<p><label class="error">' . $errors['captcha'] . '</label></p>';
		$html .= '</div>';
		$html .= '</div>';
	}

	$html .= '<p class="form-field">';
	$html .= '<input type="submit" id="wpmtst_submit_testimonial"'
				.' name="wpmtst_submit_testimonial"'
				.' value="' . __( 'Add Testimonial', WPMTST_NAME ) . '"'
				.' class="button" validate="required:true" />';
	$html .= '</p>';
	$html .= '</form>';
	$html .= '</div><!-- wpmtst-form -->';

	return $html;
}
add_shortcode( 'wpmtst-form', 'wpmtst_form_shortcode' );

/*
 * Notify admin upon testimonial submission.
 */
function wpmtst_notify_admin() {
	$options = get_option( 'wpmtst_options' );
	$admin_notify = $options['admin_notify'];
	$admin_email  = $options['admin_email'];

	if ( $admin_notify && $admin_email ) {
		$subject = 'New testimonial for ' . get_option( 'blogname' );
		$headers = 'From: noreply@' . preg_replace( '/^www\./', '', $_SERVER['HTTP_HOST'] );
		$message = 'New testimonial submission for ' . get_option( 'blogname' ) . '. This is awaiting action from the website administrator.';
		// More info here? A copy of testimonial? A link to admin page? A link to approve directly from email?
		wp_mail( $admin_email, $subject, $message, $headers );
	}
}

/*
 * File upload handler
 */
function wpmtst_wp_handle_upload( $file_handler, $overrides ) {
  require_once( ABSPATH . 'wp-admin/includes/image.php' );
  require_once( ABSPATH . 'wp-admin/includes/file.php' );
  require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$upload = wp_handle_upload( $file_handler, $overrides );
	return $upload ;
}


/*----------------------------------------------------------------------------*
 * Widget
 *----------------------------------------------------------------------------*/

class WpmTst_Widget extends WP_Widget {

	// setup
	function WpmTst_Widget() {

		$widget_ops = array(
				'classname'   => 'wpmtst-widget',
				'description' => __( 'Several ways to show testimonials.' )
		);

		$control_ops = array(
				'id_base' => 'wpmtst-widget',
				'width'   => '285px',
		);

		$this->cycle_options = array(
				'effects' => array(
						'fade'       => 'Fade',
						// 'scrollHorz' => 'Scroll horizontally',
						// 'none'       => 'None',
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
			// custom action hook:
			// load slider with widget parameters
			do_action(
				'wpmtst_cycle_hook',
				$instance['cycle-effect'],
				$instance['cycle-speed'],
				$instance['cycle-timeout'],
				$instance['cycle-pause'],
				'.wpmtst-widget-container',
				'cycleWidget'
			);
		}

		$data = array_merge( $args, $instance );
		$classes = array();

		// build query

		if ( 'rand' == $data['order'] ) {
			$orderby = 'rand';
			$order   = '';
		}
		elseif ( 'oldest' == $data['order'] ) {
			$orderby = 'post_date';
			$order   = 'ASC';
		}
		else {
			$orderby = 'post_date';
			$order   = 'DESC';
		}

		if ( 'cycle' == $data['mode'] ) {

			$classes[] = 'tcycle';
			if ( $data['cycle-all'] )
				$num = -1;
			elseif ( ! empty( $data['cycle-limit'] ) )
				$num = $data['cycle-limit'];
			else
				$num = $this->defaults['cycle-limit'];

		}
		else {

			if ( ! empty( $data['static-limit'] ) )
				$num = $data['static-limit'];
			else
				$num = $this->defaults['static-limit'];

		}

		$char_switch = $data['char-switch'];

		if ( (int) $data['char-limit'] )
			$char_limit = $data['char-limit'];
		else
			$char_limit = $this->defaults['char-limit'];

		$term_taxonomy = '';
		$term_slug = '';
		if ( 'all' != $data['category'] ) {
			$term = get_term_by( 'id', $data['category'], 'wpm-testimonial-category' );
			if ( $term ) {
				$term_taxonomy = $term->taxonomy;
				$term_slug     = $term->slug;
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
		$results = $wp_query->query( $args );

		// start HTML output

		echo $data['before_widget'];

		if ( ! empty( $data['title'] ) )
			echo $data['before_title'] . $data['title'] . $data['after_title'];

		echo '<div class="wpmtst-widget-container ' . join( ' ', $classes ) . '">';

		foreach ( $results as $post ) {
			$post = wpmtst_get_post( $post );

			echo '<div class="testimonial-widget">';

			if ( ! empty( $post->post_title ) )
				echo '<h5>' . $post->post_title . '</h5>';

			if ( $data['images'] ) {
				if ( has_post_thumbnail( $post->ID ) ) {
					echo '<div class="photo">' . get_the_post_thumbnail( $post->ID, array( 75, 75 ) ) . '</div>';
				}
			}

			// trim on word boundary
			$content = wpautop( $post->post_content );
			if ( $char_switch && strlen( $content ) > $char_limit ) {
				// Find first space after char_limit (e.g. 200).
				// If not found then char_limit is in the middle of the
				// last word (e.g. string length = 203) so no need to truncate.
				$space_pos = strpos( $content, ' ', $char_limit );
				if ( $space_pos )
					$content = substr( $content, 0, $space_pos ) . ' . . . ';
			}
			echo '<div class="content">' . $content . '</div><!-- content -->';
			echo '<div class="client">' . do_shortcode( wpmtst_client_info( $post ) ) . '</div><!-- client -->';
			echo '</div><!-- testimonial-widget -->';
		}

		echo '</div><!-- wpmtst-widget-container -->';

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
				<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="omega" autocomplete="off">
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
				<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="omega" autocomplete="off">
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
							<input  id="<?php echo $this->get_field_id( 'mode-cycle' ); ?>" type="radio" name="<?php echo $this->get_field_name( 'mode' ); ?>" value="cycle" class="wpmtst-mode-setting" <?php checked( $instance['mode'], 'cycle' ); ?> />
							<?php _e( 'Cycle Mode' ) ?></label>
					</li>
					<li class="radio-tab <?php if ( 'static' == $instance['mode'] ) { echo ' radio-current'; } ?>">
						<label for="<?php echo $this->get_field_id( 'mode-static' ); ?>">
							<input  id="<?php echo $this->get_field_id( 'mode-static' ); ?>" type="radio" name="<?php echo $this->get_field_name( 'mode' ); ?>" value="static" class="wpmtst-mode-setting" <?php checked( $instance['mode'], 'static' ); ?> />
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
							<input  type="text" id="<?php echo $this->get_field_id( 'cycle-limit' ); ?>" name="<?php echo $this->get_field_name( 'cycle-limit' ); ?>" value="<?php echo $instance['cycle-limit']; ?>" size="3" <?php if ( $instance['cycle-all'] ) { echo ' readonly="readonly"'; } ?> />
						</div>
						<div class="divider">
							<input  type="checkbox" id="<?php echo $this->get_field_id( 'cycle-all' ); ?>" name="<?php echo $this->get_field_name( 'cycle-all' ); ?>" <?php checked( $instance['cycle-all'], 1 ); ?> class="checkbox" />
							<label for="<?php echo $this->get_field_id( 'cycle-all' ); ?>"><?php _e( 'Show all', WPMTST_NAME ); ?></label>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>"><?php _e( 'Show each for', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>" name="<?php echo $this->get_field_name( 'cycle-timeout' ); ?>" value="<?php echo $instance['cycle-timeout']; ?>" size="3" />
							<?php _e( 'seconds', WPMTST_NAME ); ?>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-effect' ); ?>"><?php _e( 'Transition effect', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<select id="<?php echo $this->get_field_id( 'cycle-effect' ); ?>" name="<?php echo $this->get_field_name( 'cycle-effect' ); ?>" autocomplete="off">
								<?php foreach ( $this->cycle_options['effects'] as $key => $label ) : ?>
								<option value="<?php echo $key; ?>" <?php selected( $instance['cycle-effect'], $key ); ?>><?php _e( $label ) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<p><em><a href="http://wordpress.org/support/topic/settings-bug-1" target="_blank">Fade is the only effect for now</a>.</em></p>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-speed' ); ?>"><?php _e( 'Effect duration', WPMTST_NAME ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-speed' ); ?>" name="<?php echo $this->get_field_name( 'cycle-speed' ); ?>" value="<?php echo $instance['cycle-speed']; ?>" size="3" />
							<?php _e( 'seconds', WPMTST_NAME ); ?>
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
				<input type="checkbox" id="<?php echo $this->get_field_id( 'char-switch' ); ?>" name="<?php echo $this->get_field_name( 'char-switch' ); ?>" <?php checked( $instance['char-switch'] ); ?>  class="checkbox" />

				<label for="<?php echo $this->get_field_id( 'char-limit' ); ?>"><?php _e( 'Character limit', WPMTST_NAME ); ?>:</label>
				<input  type="text" id="<?php echo $this->get_field_id( 'char-limit' ); ?>" name="<?php echo $this->get_field_name( 'char-limit' ); ?>" value="<?php echo $instance['char-limit']; ?>" size="3" <?php if ( ! $instance['char-switch'] ) { echo ' readonly="readonly"'; } ?> />
				<span class="help">Will break line on a space and add an ellipsis.</span>
			</p>

			<!-- FEATURED IMAGES -->
			<p>
				<input  type="checkbox" id="<?php echo $this->get_field_id( 'images' ); ?>" name="<?php echo $this->get_field_name( 'images' ); ?>" <?php checked( $instance['images'] ); ?> class="checkbox" />
				<label for="<?php echo $this->get_field_id('images'); ?>"><?php _e( 'Show Featured Images', WPMTST_NAME ); ?></label>
			</p>

			<!-- READ MORE LINK -->
			<p>
				<input  type="checkbox" id="<?php echo $this->get_field_id( 'more' ); ?>" name="<?php echo $this->get_field_name( 'more' ); ?>" <?php checked( $instance['more'] ); ?> class="checkbox" />
				<label for="<?php echo $this->get_field_id( 'more' ); ?>"><?php _e( 'Show "Read More" link to this page', WPMTST_NAME ); ?>:</label>
			</p>

			<p>
				<select id="<?php echo $this->get_field_id( 'more_page' ); ?>" name="<?php echo $this->get_field_name( 'more_page' ); ?>" class="widefat" autocomplete="off">
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
		}
		else {
			$instance['cycle-limit'] = (int) strip_tags( $new_instance['cycle-limit'] );
		}

		$instance['cycle-all']     = isset( $new_instance['cycle-all'] ) ? 1 : 0;

		if ( ! $new_instance['cycle-timeout'] ) {
			$instance['cycle-timeout'] = $defaults['cycle-timeout'];
		}
		else {
			$instance['cycle-timeout'] = (float) strip_tags( $new_instance['cycle-timeout'] );
		}

		$instance['cycle-effect']  = strip_tags( $new_instance['cycle-effect'] );

		if ( ! $new_instance['cycle-speed'] ) {
			$instance['cycle-speed'] = $defaults['cycle-speed'];
		}
		else {
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

/*
 * Load widget
 */
function wpmtst_load_widget() {
	register_widget( 'WpmTst_Widget' );
}
add_action( 'widgets_init', 'wpmtst_load_widget' );

/*
 * Custom hook action to conditionally load Cycle script
 */
function wpmtst_cycle_check( $effect, $speed, $timeout, $pause, $div, $var ) {
	// Load jQuery Cycle2 plugin (http://jquery.malsup.com/cycle2/) only if
	// either Cycle or Cycle 2 is not already enqueued by a theme or another
	// plugin. Both versions use same function name
	// (see http://jquery.malsup.com/cycle2/faq/) so we can't load both
	// but either version will work for our purposes.
	
	// ----------------------------------------------------------
	// This WordPress function checks by *handle* but handles can
	// be different so `wp_script_is` misses it.
	// (Seems to be intended for use within the plugin itself.)
	// http://codex.wordpress.org/Function_Reference/wp_script_is
	// ----------------------------------------------------------
	// $list = 'enqueued';
	// if ( ! wp_script_is( 'jquery.cycle2.min.js', $list ) ) {
	
	// ---------------------------------------------------
	// This custom function checks by *file name* instead:
	// ---------------------------------------------------
	if ( ! wpmtst_is_queued( array( 'jquery.cycle.all.min.js', 'jquery.cycle.all.js' ) )
			&& ! wpmtst_is_queued( array( 'jquery.cycle2.min.js', 'jquery.cycle2.js' ) ) ) {
		wp_enqueue_script( 'wpmtst-cycle-plugin' ); // Cycle2
	}
	
	// Load Cycle script and populate its variable.
	$args = array (
			'fx'      => $effect,
			'speed'   => $speed * 1000, 
			'timeout' => $timeout * 1000, 
			'pause'   => $pause,
			'div'     => $div,
	);
	wp_enqueue_script( 'wpmtst-cycle-script' );
	wp_localize_script( 'wpmtst-cycle-script', $var, $args );
}
// custom hook
add_action( 'wpmtst_cycle_hook', 'wpmtst_cycle_check', 10, 6 );


/*----------------------------------------------------------------------------*
 * Settings
 *----------------------------------------------------------------------------*/

function wpmtst_settings_menu() {
	add_submenu_page( 'edit.php?post_type=wpm-testimonial', // $parent_slug
										'Settings',                           // $page_title
										'Settings',                           // $menu_title
										'manage_options',                     // $capability
										'settings',                           // $menu_slug
										'wpmtst_settings_page' );             // $function

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
										'Fields',
										'Fields',
										'manage_options',
										'fields',
										'wpmtst_settings_custom_fields' );

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
 * Make admin menu title unique if necessary.
 */
function wpmtst_unique_menu_title() {
	$need_unique = false;

		// GC Testimonials
	if ( is_plugin_active( 'gc-testimonials/testimonials.php' ) )
		$need_unique = true;

	// Testimonials by Aihrus
	if ( is_plugin_active( 'testimonials-widget/testimonials-widget.php' ) )
		$need_unique = true;

	if ( ! $need_unique )
		return;

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
	$input['per_page']      = (int) sanitize_text_field( $input['per_page'] );
	$input['admin_notify']  = isset( $input['admin_notify'] ) ? 1 : 0;
	$input['admin_email']   = sanitize_email( $input['admin_email'] );
	$input['cycle']['cycle-timeout'] = (float) sanitize_text_field( $input['cycle']['cycle-timeout'] );
	// $input['cycle']['cycle-effect']
	$input['cycle']['cycle-speed']   = (float) sanitize_text_field( $input['cycle']['cycle-speed'] );
	$input['cycle']['cycle-pause']   = isset( $input['cycle']['cycle-pause'] ) ? 1 : 0;

	return $input;
}

/*
 * Settings page
 */
function wpmtst_settings_page() {
	if ( ! current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$wpmtst_options = get_option( 'wpmtst_options' );
	$cycle_options = array(
			'effects' => array(
					'fade'       => 'Fade',
					// 'scrollHorz' => 'Scroll horizontally',
					// 'none'       => 'None',
			)
	);
	$order_list = array(
			'rand'   => 'Random',
			'recent' => 'Newest first',
			'oldest' => 'Oldest first'
	);

	// Build list of supported Captcha plugins.
	$plugins = array(
			'bwsmath' => array(
					'name' => 'Captcha by BestWebSoft',
					'file' => 'captcha/captcha.php',
					'active' => false
			),
			'wpmsrc'  => array(
					'name' => 'Simple reCAPTCHA by WP Mission',
					'file' => 'simple-recaptcha/simple-recaptcha.php',
					'active' => false
			),
			'miyoshi' => array(
					'name' => 'Really Simple Captcha by Takayuki Miyoshi',
					'file' => 'really-simple-captcha/really-simple-captcha.php',
					'active' => false
			),
	);

	foreach ( $plugins as $key => $plugin ) {
		$plugins[$key]['active'] = is_plugin_active( $plugin['file'] );
		// If current Captcha plugin has been deactivated, disable Captcha
		// so corresponding div does not appear on front-end form.
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
			<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $wpmtst_options['default_template'] ); ?>" />
			
			<table class="form-table">

				<tr valign="top">
					<th scope="row">The number of testimonials to show per page</th>
					<td>
						<input type="text" name="wpmtst_options[per_page]" size="3" value="<?php echo esc_attr( $wpmtst_options['per_page'] ); ?>" />
						This applies to the <span class="code">[wpmtst-all]</span> shortcode.
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">When a new testimonial is submitted</th>
					<td>
						<label>
							<input id="wpmtst-options-admin-notify" type="checkbox" name="wpmtst_options[admin_notify]" <?php checked( $wpmtst_options['admin_notify'] ); ?> />
							<?php _e( 'Send notification email to', WPMTST_NAME ); ?>
						</label>
						<input id="wpmtst-options-admin-email" type="email" size="30" placeholder="email address" name="wpmtst_options[admin_email]" value="<?php echo esc_attr( $wpmtst_options['admin_email'] ); ?>" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">CAPTCHA plugin</th>
					<td>
						<select name="wpmtst_options[captcha]" autocomplete="off">
							<option value="">None</option>
							<?php foreach ( $plugins as $key => $plugin ) : ?>
							<?php if ( $plugin['active'] ) : ?>
							<option value="<?php echo $key; ?>" <?php selected( $wpmtst_options['captcha'], $key ); ?>><?php echo $plugin['name']; ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Cycle Shortcode Settings</th>
					<td>
						<div class="box">

							<div class="row">
								<div class="alpha">
									<label for="cycle-order"><?php _e( 'Order' ) ?>:</label>
								</div>
								<div>
									<select id="cycle-order" name="wpmtst_options[cycle][cycle-order]" autocomplete="off">
										<?php
										foreach ( $order_list as $order => $order_label ) {
											echo '<option value="' . $order . '"' . selected( $order, $wpmtst_options['cycle']['cycle-order'] ) . '>' . $order_label . '</option>';
										}
										?>
									</select>
								</div>
							</div>

							<div class="row">
								<div class="alpha">
									<label for="cycle-timeout"><?php _e( 'Show each for', WPMTST_NAME ); ?>:</label>
								</div>
								<div>
									<input type="text" id="cycle-timeout" name="wpmtst_options[cycle][cycle-timeout]" value="<?php echo $wpmtst_options['cycle']['cycle-timeout']; ?>" size="3" />
									<?php _e( 'seconds', WPMTST_NAME ); ?>
								</div>
							</div>

							<div class="row">
								<div class="alpha">
									<label for="cycle-effect"><?php _e( 'Transition effect', WPMTST_NAME ); ?>:</label>
								</div>
								<div>
									<select id="cycle-effect" name="wpmtst_options[cycle][cycle-effect]" autocomplete="off">
										<?php foreach ( $cycle_options['effects'] as $key => $label ) : ?>
										<option value="<?php echo $key; ?>" <?php selected( $wpmtst_options['cycle']['cycle-effect'], $key ); ?>><?php _e( $label ) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<p><em><a href="http://wordpress.org/support/topic/settings-bug-1" target="_blank">Fade is the only effect for now</a>.</em></p>
							</div>

							<div class="row">
								<div class="alpha">
									<label for="cycle-speed"><?php _e( 'Effect duration', WPMTST_NAME ); ?>:</label>
								</div>
								<div>
									<input type="text" id="cycle-speed" name="wpmtst_options[cycle][cycle-speed]" value="<?php echo $wpmtst_options['cycle']['cycle-speed']; ?>" size="3" />
									<?php _e( 'seconds', WPMTST_NAME ); ?>
								</div>
							</div>

							<div class="row">
								<div>
									<input type="checkbox" id="cycle-pause" name="wpmtst_options[cycle][cycle-pause]" <?php checked( $wpmtst_options['cycle']['cycle-pause'] ); ?>  class="checkbox" />
									<label for="cycle-pause"><?php _e( 'Pause on hover', WPMTST_NAME ); ?></label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e( 'Client Template', WPMTST_NAME ); ?></th>
					<td>
						<p><?php _e( 'Use these shortcode options to show client information below each testimonial', WPMTST_NAME ); ?>:</p>
						<div class="shortcode-example code">
							<p class="indent">
								<span class="outdent">[wpmtst-text </span>
										<br>field="{ <?php _e( 'your custom text field', WPMTST_NAME ); ?> }" 
										<br>class="{ <?php _e( 'your CSS class', WPMTST_NAME ); ?> }"]
							</p>
							<p class="indent">
								<span class="outdent">[wpmtst-link </span>
										<br>url="{ <?php _e( 'your custom URL field', WPMTST_NAME ); ?> }" 
										<br>text="{ <?php _e( 'your custom text field', WPMTST_NAME ); ?> }" 
										<br>target="_blank" <br>class="{ <?php _e( 'your CSS class', WPMTST_NAME ); ?> }"]
							</p>
						</div>
						
						<p><textarea id="client-section" class="widefat code" name="wpmtst_options[client_section]" rows="3"><?php echo $wpmtst_options['client_section']; ?></textarea></p>
						
						<p><input type="button" class="button" id="restore-default-template" value="Restore Default Template" /></p>
					</td>
				</tr>
				
			</table>

			<?php submit_button(); ?>

		</form>

	</div><!-- wrap -->

	<?php
}

/*
 * Shortcodes page
 */
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
				<td colspan="2"><h3>Testimonials Cycle</h3></td>
			</tr>
			<tr>
				<td>Cycle through all from all categories.</td><td>[wpmtst-cycle]</td>
			</tr>
			<tr>
				<td>Cycle through all from a specific category.<br> Find these on the <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial' ); ?>">categories screen</a>.</td><td>[wpmtst-cycle category="xx"]</td>
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

/*
 * Custom Fields page
 */
function wpmtst_settings_custom_fields() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	$options = get_option( 'wpmtst_options' );
	$field_options = get_option( 'wpmtst_fields' );
	$field_groups = $field_options['field_groups'];
	$current_field_group = $field_options['current_field_group'];  // "custom", only one for now
	$field_group = $field_groups[$current_field_group];

	$message_format = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';

	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_custom_fields_form' ) ) {

		if ( isset( $_POST['reset'] ) ) {

			// Undo changes
			$fields = $field_group['fields'];
			echo sprintf( $message_format, __( 'Changes undone.', WPMTST_NAME ) );

		}
		elseif ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			$fields = $field_options['field_groups']['default']['fields'];
			$field_options['field_groups']['custom']['fields'] = $fields;
			update_option( 'wpmtst_fields', $field_options );
			echo sprintf( $message_format, __( 'Defaults restored.', WPMTST_NAME ) );

		}
		else {

			// Save changes
			$fields = array();
			$new_key = 0;
			foreach ( $_POST['fields'] as $key => $field ) {
				$field = array_merge( $field_options['field_base'], $field );

				// sanitize & validate
				$field['name']        = sanitize_text_field( $field['name'] );
				$field['label']       = sanitize_text_field( $field['label'] );
				$field['placeholder'] = sanitize_text_field( $field['placeholder'] );
				$field['before']      = sanitize_text_field( $field['before'] );
				$field['after']       = sanitize_text_field( $field['after'] );
				$field['required']    = $field['required'] ? 1 : 0;
				$field['admin_table'] = $field['admin_table'] ? 1 : 0;

				// add to fields array in display order
				$fields[$new_key++] = $field;

			}
			$field_options['field_groups']['custom']['fields'] = $fields;
			update_option( 'wpmtst_fields', $field_options );
			echo sprintf( $message_format, __( 'Fields saved.', WPMTST_NAME ) );
		}

	}
	else {

		// Get current fields
		$fields = $field_group['fields'];

	}

	// ------------------
	// Custom Fields Form
	// ------------------
	echo '<div class="wrap wpmtst">' . "\n";
	echo '<h2>' . __( 'Fields', WPMTST_NAME ) . '</h2>' . "\n";
	echo '<p>Fields will appear in this order on the form. Sort by grabbing the <span class="dashicons dashicons-menu"></span> icon. Click the field name to expand its options panel.</p>' . "\n";
	
	echo '<!-- Custom Fields Form -->' . "\n";
	echo '<form id="wpmtst-custom-fields-form" method="post" action="">' . "\n";
	wp_nonce_field( 'wpmtst_custom_fields_form', 'wpmtst_form_submitted' ); 
	
	echo '<ul id="custom-field-list">' . "\n";
	
	foreach ( $fields as $key => $field ) {
		echo '<li id="field-' . $key . '">' . wpmtst_show_field( $key, $field, false ) . '</li>' . "\n";
	}
	
	echo '</ul>' . "\n";
	
	echo '<div id="add-field-bar">';
	echo '<input id="add-field" type="button" class="button-primary" name="add-field" value="' . __( 'Add New Field', WPMTST_NAME ) . '" />';
	echo '</div>' . "\n";
	
	echo '<p class="submit">' . "\n";
	submit_button( '', 'primary', 'submit', false );
	submit_button( 'Undo Changes', 'secondary', 'reset', false );
	submit_button( 'Restore Defaults', 'secondary', 'restore-defaults', false );
	echo '</p>' . "\n";
	
	echo '</form><!-- Custom Fields -->' . "\n";
	echo '</div><!-- wrap -->' . "\n";
}

/*
 * Add a field to the form
 */
function wpmtst_show_field( $key, $field, $adding ) {
	$fields = get_option( 'wpmtst_fields' );
	$field_types = $fields['field_types'];
	$field_link = $field['label'] ? $field['label'] : ucwords( $field['name'] );
	$is_core = ( isset( $field['core'] ) && $field['core'] );

	// ------------
	// Field Header
	// ------------
	$html = '<div class="custom-field-header">' . "\n";
	$html .= '<span class="handle"><div class="dashicons dashicons-menu"></div></span>' . "\n";
	$html .= '<span class="link"><a class="field" href="#">' . $field_link . '</a></span>' . "\n";
	$html .= '</div>' . "\n";
	
	$html .= '<div class="custom-field">' . "\n";
	
	$html .= '<table class="field-table">' . "\n";
	
	// -----------
	// Field Label
	// -----------
	$html .= '<tr>' . "\n";
	$html .= '<th>Label</th>' . "\n";
	$html .= '<td>' . "\n";
	$html .= '<input type="text" class="first-field field-label" name="fields[' . $key . '][label]" value="' . $field['label'] . '" />' . "\n";
	$html .= '<span class="help">This appears on the form.</span>' . "\n";
	$html .= '</td>' . "\n";
	$html .= '</td>' . "\n";
	
	// ----------
	// Field Name
	// ----------
	$html .= '<tr>' . "\n";
	$html .= '<th>Name</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( 'custom' == $field['record_type'] ) {
		// if adding, the field Name is blank so it can be populated from Label
		$html .= '<input type="text" class="field-name" name="fields['.$key.'][name]" value="' . ( isset( $field['name'] ) ? $field['name'] : '' ) . '" />' . "\n";
		$html .= '<span class="help field-name-help">Use only lowercase letters, numbers, and underscores.</span>' . "\n";
	}
	else {
		$html .= '<input type="text" class="field-name" value="' . $field['name'] . '" disabled="disabled" />' . "\n";
		// disabled inputs are not posted so store the field name in a hidden input
		$html .= '<input type="hidden" name="fields[' . $key . '][name]" value="' . $field['name'] . '" />' . "\n";
	}
	$html .= '</td>' . "\n";
	$html .= '</td>' . "\n";
	
	// ---------------------------
	// Field Type (Post or Custom)
	// ---------------------------
	// If disabled, create <select> with single option
	// and add hidden input with current value.
	// Separate code! Readability trumps ultra-minor efficiency.
	
	$html .= '<tr>' . "\n";
	$html .= '<th>Type</th>' . "\n";
	$html .= '<td>' . "\n";
	
	// Restrict field choice to this record type
	// unless we're adding a new field.
	if ( $adding ) {
	
		$html .= '<select class="field-type new" name="fields[' . $key . '][input_type]" autocomplete="off">' . "\n";
	
		// start with a blank option with event trigger to update optgroups...
		$html .= '<option class="no-selection" value="none" name="none">&mdash;</option>' . "\n";
		
		// If pre-selecting a record type in event handler:
		/*
		if ( 'custom' == $field['record_type'] ) {
			// compare field *name*
			$selected = selected( $field['name'], $field_key, false );
		}
		elseif ( 'post' == $field['record_type'] {
			// compare field *type*
			$selected = selected( $field['input_type'], $field_key, false );
		}
		*/
		// ...then add $selected to <option>.
		
		// Post fields
		$html .= '<optgroup class="post" label="Post Fields">' . "\n";
		foreach ( $field_types['post'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>' . "\n";
		}
		$html .= '</optgroup>' . "\n";
		
		// Custom fields
		$html .= '<optgroup class="custom" label="Custom Fields">' . "\n";
		foreach ( $field_types['custom'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>' . "\n";
		}
		$html .= '</optgroup>' . "\n";
		
		$html .= '</select>' . "\n";

	}
	else {
	
		if ( 'post' == $field['record_type'] ) {
			// -----------
			// Post fields
			// -----------
			// Disable <select>. Display current value as only option.
			// Disabled inputs are not posted so store the value in hidden field.
			$html .= '<input type="hidden" name="fields[' . $key . '][input_type]" value="' . $field['name'] . '" />' . "\n";
			$html .= '<select id="current-field-type" class="field-type" disabled="disabled">' . "\n";
			foreach ( $field_types['post'] as $field_key => $field_parts ) {
				// compare field *name*
				if ( $field['name'] == $field_key )
					$html .= '<option value="' . $field_key . '" selected="selected">' . $field_parts['option_label'] . '</option>' . "\n";
			}
			$html .= '</select>' . "\n";
		}
		else {
			// -------------
			// Custom fields
			// -------------
			$html .= '<select class="field-type" name="fields[' . $key . '][input_type]" autocomplete="off">' . "\n";
			$html .= '<optgroup class="custom" label="Custom Fields">' . "\n";
			foreach ( $field_types['custom'] as $field_key => $field_parts ) {
				// compare field *type*
				$selected = selected( $field['input_type'], $field_key, false );
				$html .= '<option value="' . $field_key . '" ' . $selected . '>' . $field_parts['option_label'] . '</option>' . "\n";
			}
			$html .= '</optgroup>' . "\n";
			$html .= '</select>' . "\n";
		}
		
	} // adding
	$html .= '</td>' . "\n";
	
	if ( ! $adding ) {
		$html .= wpmtst_show_field_secondary( $key, $field );
		$html .= wpmtst_show_field_admin_table( $key, $field );
	}
	
	$html .= '</table>' . "\n";

	if ( ! $adding )
		$html .= wpmtst_show_field_hidden( $key, $field );
	
	// --------
	// Controls
	// --------
	$html .= '<div class="controls">' . "\n";
	if ( $adding || ! $is_core ) {
		$html .= '<span><a href="#" class="delete-field">Delete</a></span>';
	}
	$html .= '<span class="close-field"><a href="#">Close</a></span>';
	$html .= '</div>' . "\n";
	
	$html .= '</div><!-- .custom-field -->' . "\n";
	
	return $html;
}

/*
 * Create the secondary inputs for a new custom field.
 * Called after field type is chosen (Post or Custom).
 */
function wpmtst_show_field_secondary( $key, $field ) {
	// --------
	// Required
	// --------
	// Disable option if this is a core field like post_content.
	if ( isset( $field['core'] ) && $field['core'] )
		$disabled = ' disabled="disabled"';
	else
		$disabled = false;
		
	$html = '<tr>' . "\n";
	$html .= '<th>Required</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( $disabled ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][required]" value="' . $field['required'] . '" />' . "\n";
		$html .= '<input type="checkbox" ' . checked( $field['required'], true, false ) . $disabled . ' />' . "\n";
	}
	else {
		$html .= '<input type="checkbox" name="fields[' . $key . '][required]" ' . checked( $field['required'], true, false ) . ' />' . "\n";
	}
	$html .= '</td>' . "\n";
	$html .= '</td>' . "\n";
	
	// -----------
	// Placeholder
	// -----------
	if ( isset( $field['placeholder'] ) ) {
		$html .= '<tr>' . "\n";
		$html .= '<th>Placeholder</th>' . "\n";
		$html .= '<td><input type="text" name="fields[' . $key . '][placeholder]" value="' . $field['placeholder'] . '" /></td>' . "\n";
		$html .= '</td>' . "\n";
	}
	
	// ------
	// Before
	// ------
	$html .= '<tr>' . "\n";
	$html .= '<th>Before</th>' . "\n";
	$html .= '<td><input type="text" name="fields[' . $key . '][before]" value="' . $field['before'] . '" /></td>' . "\n";
	$html .= '</td>' . "\n";
	
	// -----
	// After
	// -----
	$html .= '<tr>' . "\n";
	$html .= '<th>After</th>' . "\n";
	$html .= '<td><input type="text" name="fields[' . $key . '][after]" value="' . $field['after'] . '" /></td>' . "\n";
	$html .= '</td>' . "\n";
	
	return $html;
}

/*
 * Add type-specific [Admin Table] setting to form.
 */
function wpmtst_show_field_admin_table( $key, $field ) {
	// -------------------
	// Show in Admin Table
	// -------------------
	$html = '<tr class="field-admin-table">' . "\n";
	$html .= '<th>Admin Table</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( $field['admin_table_option'] ) {
		$html .= '<input type="checkbox" class="field-admin-table" name="fields[' . $key . '][admin_table]" ' . checked( $field['admin_table'], 1, false ) . ' />' . "\n";
	}
	else {
		$html .= '<input type="checkbox" ' . checked( $field['admin_table'], 1, false ) . ' disabled="disabled" /> <em>required</em>' . "\n";
		$html .= '<input type="hidden" name="fields[' . $key . '][admin_table]" value="' . $field['admin_table'] . '" />' . "\n";
	}
	$html .= '</td>' . "\n";
	
	return $html;
}

/*
 * Add hidden fields to form.
 */
function wpmtst_show_field_hidden( $key, $field ) {
	// -------------
	// Hidden Values
	// -------------
	$html = '<input type="hidden" name="fields[' . $key . '][record_type]" value="' . $field['record_type'] . '">' . "\n";
	$html .= '<input type="hidden" name="fields[' . $key . '][input_type]" value="' . $field['input_type'] . '">' . "\n";
	$html .= '<input type="hidden" name="fields[' . $key . '][admin_table_option]" value="' . $field['admin_table_option'] . '">' . "\n";
	if ( isset( $field['map'] ) ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][map]" value="' . $field['map'] . '">' . "\n";
	}
	if ( isset( $field['core'] ) ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][core]" value="' . $field['core'] . '">' . "\n";
	}
	
	return $html;
}


/*
 * [Add New Field] Ajax receiver
 */
function wpmtst_add_field_function() {
	$new_key = intval( $_REQUEST['key'] );
	$fields = get_option( 'wpmtst_fields' );
	// when adding, leave Name empty so it will be populated from Label
	$empty_field = array( 'record_type' => 'custom', 'label' => 'New Field' );
	$new_field = wpmtst_show_field( $new_key, $empty_field, true );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field', 'wpmtst_add_field_function' );


/*
 * [Add New Field 2] Ajax receiver
 */
function wpmtst_add_field_2_function() {
	$new_key = intval( $_REQUEST['key'] );
	$new_field_type = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_secondary( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_2', 'wpmtst_add_field_2_function' );


/*
 * [Add New Field 3] Ajax receiver
 */
function wpmtst_add_field_3_function() {
	$new_key = intval( $_REQUEST['key'] );
	$new_field_type = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_hidden( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_3', 'wpmtst_add_field_3_function' );


/*
 * [Add New Field 4] Ajax receiver
 */
function wpmtst_add_field_4_function() {
	$new_key = intval( $_REQUEST['key'] );
	$new_field_type = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_admin_table( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_4', 'wpmtst_add_field_4_function' );


/*
 * [Restore Default Template] event handler
 */
function wpmtst_restore_default_template_script() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#restore-default-template").click(function(e){
			var data = {
				'action' : 'wpmtst_restore_default_template',
			};
			$.get( ajaxurl, data, function( response ) {
				$("#client-section").val(response);
			});
		});
	});
	</script>
	<?php
}
add_action( 'admin_footer', 'wpmtst_restore_default_template_script' );

/*
 * [Restore Default Template] Ajax receiver
 */
function wpmtst_restore_default_template_function() {
	$options = get_option( 'wpmtst_options' );
	$template = $options['default_template'];
	echo $template;
	die();
}
add_action( 'wp_ajax_wpmtst_restore_default_template', 'wpmtst_restore_default_template_function' );


/*----------------------------------------------------------------------------*
 * CAPTCHA
 *----------------------------------------------------------------------------*/

/*
 * Add to form
 */
function wpmtst_add_captcha( $captcha ) {

	switch ( $captcha ) {

		case 'akismet' :
			break;

		// Captcha by BestWebSoft
		case 'bwsmath' :
			if ( function_exists( 'cptch_display_captcha_custom' ) ) {
				echo '<input type="hidden" name="cntctfrm_contact_action" value="true" />';
				echo cptch_display_captcha_custom();
			}
			break;

		// Strong reCAPTCHA by WP Mission
		case 'wpmsrc' :
			if ( function_exists( 'wpmsrc_display' ) ) {
				echo wpmsrc_display();
			}
			break;

		// Really Simple Captcha by Takayuki Miyoshi
		case 'miyoshi' :
			if ( class_exists( 'ReallySimpleCaptcha' ) ) {
				$captcha_instance = new ReallySimpleCaptcha();
				$word = $captcha_instance->generate_random_word();
				$prefix = mt_rand();
				$image = $captcha_instance->generate_image( $prefix, $word );
				echo '<span>Input this code: <input type="hidden" name="captchac" value="'.$prefix.'" /><img class="captcha" src="' . plugins_url( 'really-simple-captcha/tmp/' ) . $image . '"></span>';
				echo '<input type="text" class="captcha" name="captchar" maxlength="4" size="5" />';
			}

			break;
		default :
			// no captcha

	}
}
add_action( 'wpmtst_captcha', 'wpmtst_add_captcha', 50, 1 );

/*
 * Check form input
 */
function wpmtst_captcha_check( $captcha, $errors ) {
	switch ( $captcha ) {

		// Captcha by BestWebSoft
		case 'bwsmath' :
			if ( function_exists( 'cptch_check_custom_form' ) && cptch_check_custom_form() !== true ) {
				$errors['captcha'] = 'Please complete the CAPTCHA.';
			}
			break;

		// Simple reCAPTCHA by WP Mission
		case 'wpmsrc' :
			if ( function_exists( 'wpmsrc_check' ) ) {
				// check for empty user response first
				if ( empty( $_POST['recaptcha_response_field'] ) ) {
					$errors['captcha'] = __( 'Please complete the CAPTCHA.', WPMTST_NAME );
				}
				else {
					// check captcha
					$response = wpmsrc_check();
					if ( ! $response->is_valid ) {
						// -------------------------------------------------------
						// MOVE THIS TO RECAPTCHA PLUGIN ~!~
						// with log and auto-report email
						// -------------------------------------------------------
						// see https://developers.google.com/recaptcha/docs/verify
						// -------------------------------------------------------
						$error_codes['invalid-site-private-key'] = 'Invalid keys. Please contact the site administrator.';
						$error_codes['invalid-request-cookie']   = 'Invalid parameter. Please contact the site administrator.';
						$error_codes['incorrect-captcha-sol']    = 'The CAPTCHA was not entered correctly. Please try again.';
						$error_codes['captcha-timeout']          = 'The process timed out. Please try again.';
						// $error_codes['recaptcha-not-reachable']  = 'Unable to reach reCAPTCHA server. Please contact the site administrator.';
						$errors['captcha'] = __( $error_codes[ $response->error ], WPMTST_NAME );
					}
				}
			}
			break;

		default :

	}
	return $errors;
}
