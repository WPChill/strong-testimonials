<?php
/**
 * Register scripts and styles.
 */

function wpmtst_scripts() {

	$plugin_version = get_option( 'wpmtst_plugin_version' );
	$options        = get_option( 'wpmtst_options' );

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	/**
	 * Fonts
	 */
	wp_register_style( 'wpmtst-font-awesome', WPMTST_PUBLIC_URL . 'fonts/font-awesome-4.6.3/css/font-awesome.min.css', array(), '4.6.3' );

	/**
	 * Simple pagination
	 */
	wp_register_script( 'wpmtst-pager-plugin', WPMTST_PUBLIC_URL . 'js/jquery.quickpager.js', array( 'jquery' ), false, true );
	wp_register_script( 'wpmtst-pager-script', WPMTST_PUBLIC_URL . 'js/pager.js', array( 'wpmtst-pager-plugin' ), $plugin_version, true );
	wp_register_style( 'wpmtst-pager-simple-style', WPMTST_PUBLIC_URL . 'css/pagination-simple.css', array(), $plugin_version );
	wp_register_style( 'wpmtst-pager-standard-style', WPMTST_PUBLIC_URL . 'css/pagination-standard.css', array(), $plugin_version );

	/**
	 * View custom style
	 */
	wp_register_style( 'wpmtst-custom-style', WPMTST_PUBLIC_URL . 'css/custom.css' );

	/**
	 * Masonry
	 */
	wp_register_script( 'imagesloaded-script', WPMTST_PUBLIC_URL . 'js/lib/imagesloaded/imagesloaded.pkgd.min.js', array(), false, true );
	wp_register_script( 'wpmtst-masonry-script', WPMTST_PUBLIC_URL . 'js/masonry.js', array( 'jquery-masonry', 'imagesloaded-script' ), $plugin_version, true );
	wp_register_style( 'wpmtst-masonry-style', WPMTST_PUBLIC_URL . 'css/masonry.css', array(), $plugin_version );

	/**
	 * Columns
	 */
	wp_register_style( 'wpmtst-columns-style', WPMTST_PUBLIC_URL . 'css/columns.css', array(), $plugin_version );

	/**
	 * Grid
	 */
	wp_register_script( 'wpmtst-grid-script', WPMTST_PUBLIC_URL . 'js/grid.js', array( 'jquery' ), $plugin_version, true );
	wp_register_style( 'wpmtst-grid-style', WPMTST_PUBLIC_URL . 'css/grid.css', array(), $plugin_version );

	/**
	 * Ratings
	 */
	$deps = array();
	if ( isset( $options['load_font_awesome'] ) && $options['load_font_awesome'] ) {
		$deps = array( 'wpmtst-font-awesome' );
	}
	wp_register_style( 'wpmtst-rating-form', WPMTST_PUBLIC_URL . 'css/rating-form.css', $deps, $plugin_version );
	wp_register_style( 'wpmtst-rating-display', WPMTST_PUBLIC_URL . 'css/rating-display.css', $deps, $plugin_version );

	/**
	 * Form handling
	 */
	wp_register_script( 'wpmtst-validation-plugin', WPMTST_PUBLIC_URL . 'js/lib/validate/jquery.validate.min.js', array( 'jquery' ), false, true );

	wp_register_script( 'wpmtst-form-validation', WPMTST_PUBLIC_URL . 'js/form-validation.js', array( 'wpmtst-validation-plugin', 'jquery-form' ), $plugin_version, true );

	/**
	 * Localize jQuery Validate plugin.
	 *
	 * @since 1.16.0
	 */
	$locale = get_locale();
	if ( 'en_US' != $locale ) {
		$lang_parts = explode( '_', $locale );
		$lang_file  = 'js/lib/validate/localization/messages_' . $lang_parts[0] . '.min.js';
		if ( file_exists( WPMTST_PUBLIC . $lang_file ) ) {
			wp_register_script( 'wpmtst-validation-lang', WPMTST_PUBLIC_URL . $lang_file, array( 'wpmtst-validation-plugin' ), false, true );
		}
	}

	/**
	 * Slider
	 */
	wp_register_script( 'jquery-actual', WPMTST_PUBLIC_URL . 'js/lib/actual/jquery.actual.js', array( 'jquery' ), false, true );

	wp_register_script( 'wpmslider', WPMTST_PUBLIC_URL . "js/lib/wpmslider/jquery.wpmslider{$min}.js", array( 'jquery-actual' ), $plugin_version, true );

	wp_register_script( 'strongslider', WPMTST_PUBLIC_URL . 'js/jquery.strongslider.js', array( 'wpmslider', 'underscore' ), $plugin_version, true );

	if ( ! defined( 'MEGAMENU_VERSION' ) ) {
		wp_register_script( 'wpmtst-slider', WPMTST_PUBLIC_URL . 'js/slider.js', array( 'strongslider' ), $plugin_version, true );
	}

}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts' );


function wpmtst_scripts_later() {

	$plugin_version = get_option( 'wpmtst_plugin_version' );

	/**
	 * Custom slider handler for Max Mega Menu plugin.
	 */
	if ( defined( 'MEGAMENU_VERSION' ) ) {
		wp_register_script( 'wpmtst-slider', WPMTST_PUBLIC_URL . 'js/slider-megamenu.js', array( 'strongslider' ), $plugin_version, true );
	}

}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts_later', 20 );


/**
 * Enqueue scripts and styles
 *
 * @since 1.15.0
 * @since 2.3.0 As separate function.
 * @since 2.16.0 As one array without separate priorities.
 */
function wpmtst_view_scripts() {
	$styles = WPMST()->get_styles();
	if ( $styles ) {
		foreach ( $styles as $key => $style ) {
			wp_enqueue_style( $style );
		}
	}

	$scripts = WPMST()->get_scripts();
	if ( $scripts ) {
		foreach ( $scripts as $key => $script ) {
			wp_enqueue_script( $script );
		}
	}
}
