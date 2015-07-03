<?php

/**
 * Load post ordering class if enabled.
 *
 * @since 1.16
 */
function wpmtst_load_order_class() {
	$options = get_option( 'wpmtst_options' );
	if ( isset( $options['reorder'] ) && $options['reorder'] ) {
		require_once WPMTST_INC . 'class-strong-testimonials-order.php';
	}
}
add_action( 'init', 'wpmtst_load_order_class' );


/**
 * Add theme support for this custom post type only.
 *
 * Bug: Was calling non-existent hook until 1.19.1
 * Good thing too because simple use of add_theme_support may overwrite theme.
 * See this thread:
 * @link https://wordpress.org/support/topic/missing-featured-image?replies=8
 *
 * In 1.19.1, this now appends our testimonial post type to the existing array,
 * at a later priority, and only if thumbnails are not already global for all
 * post types (an array means not global).
 *
 * @since 1.4.0
 */
function wpmtst_theme_support() {
	global $_wp_theme_features;
	if ( is_array( $_wp_theme_features['post-thumbnails'] ) ) {
		$_wp_theme_features['post-thumbnails'][0][] = 'wpm-testimonial';
	}

	/**
	 * Add widget thumbnail size.
	 *
	 * @since 1.21.0
	 */
	add_image_size( 'widget-thumbnail', 75, 75, false );
}
add_action( 'after_setup_theme', 'wpmtst_theme_support' );
add_action( 'admin_init', 'wpmtst_theme_support' );


/**
 * Be sure to process shortcodes in widget.
 *
 * @since 1.15.5
 */
add_filter( 'widget_text', 'do_shortcode' );


function wpmtst_restrict_mime( $mimes ) {
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
	);

	return $mimes;
}

add_filter( 'upload_mimes', 'wpmtst_restrict_mime' );


// function wpmtst_wp_handle_upload_prefilter( $file ) {
// return $file;
// }
// add_filter( 'wp_handle_upload_prefilter', 'wpmtst_wp_handle_upload_prefilter' );


