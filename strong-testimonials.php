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


/**
 * Setup
 */
define( 'WPMTST_NAME', 'strong-testimonials' );
// define( 'WPMTST_DIR', plugins_url( false, __FILE__ ) );
define( 'WPMTST_DIR', plugin_dir_url( __FILE__ ) );
define( 'WPMTST_INC', plugin_dir_path( __FILE__ ) . 'includes/' );


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
 * Text domain
 */
function wpmtst_textdomain() {
	// load_plugin_textdomain( WPMTST_NAME, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	load_plugin_textdomain( WPMTST_NAME, FALSE, WPMTST_DIR . 'languages/' );
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
 * Plugin activation and upgrade.
 */
function wpmtst_activation() {
	// -1- DEFAULTS
	$plugin_data = get_plugin_data( __FILE__, false );
	$plugin_version = $plugin_data['Version'];
	include( WPMTST_INC . 'defaults.php');

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
			if ( $fields )
				$fields = array_merge( $default_fields, $fields );
			else
				$fields = $default_fields;
			
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

	wp_register_style( 'wpmtst-style', WPMTST_DIR . 'css/wpmtst.css' );
	wp_register_style( 'wpmtst-form-style', WPMTST_DIR . 'css/wpmtst-form.css' );

	wp_register_script( 'wpmtst-pager-plugin', WPMTST_DIR . 'js/quickpager.jquery.js', array( 'jquery' ) );
	wp_register_script( 'wpmtst-validation-plugin', WPMTST_DIR . 'js/jquery.validate.min.js', array( 'jquery' ) );

	wp_register_script( 'wpmtst-cycle-plugin', WPMTST_DIR . 'js/jquery.cycle2.min.js', array( 'jquery' ) );
	wp_register_script( 'wpmtst-cycle-script', WPMTST_DIR . 'js/wpmtst-cycle.js', array ( 'jquery' ), false, true );

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
 * Includes
 */

// Functions
include( WPMTST_INC . 'functions.php');

// Shims
include( WPMTST_INC . 'shims.php');

// Admin
include( WPMTST_INC . 'admin.php');

// Settings
include( WPMTST_INC . 'settings.php');

// Shortcodes
include( WPMTST_INC . 'shortcodes.php');
 
// Widget
include( WPMTST_INC . 'widget.php');

// Custom fields
include( WPMTST_INC . 'admin-custom-fields.php');

// Captcha
include( WPMTST_INC . 'captcha.php');
