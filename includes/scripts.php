<?php
/**
 * Register scripts and styles.
 */

function wpmtst_scripts() {

	$plugin_version = get_option( 'wpmtst_plugin_version' );

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
	wp_register_style( 'wpmtst-rating-form', WPMTST_COMMON_URL . 'css/rating-form.css', array( 'wpmtst-font-awesome' ), $plugin_version );
	wp_register_style( 'wpmtst-rating-display', WPMTST_COMMON_URL . 'css/rating-display.css', array( 'wpmtst-font-awesome' ), $plugin_version );

	/**
	 * Form handling
	 */
	wp_register_script( 'wpmtst-form', WPMTST_PUBLIC_URL . 'js/form.js', array( 'jquery' ), $plugin_version, true );
	wp_register_script( 'wpmtst-form-success', WPMTST_PUBLIC_URL . 'js/form-success.js', array( 'jquery' ), $plugin_version, true );

	if ( wpmtst_using_form_validation_script() ) {

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
			$lang_file  = 'js/validate/localization/messages_' . $lang_parts[0] . '.min.js';
			if ( file_exists( WPMTST_DIR . $lang_file ) ) {
				wp_register_script( 'wpmtst-validation-lang', WPMTST_URL . $lang_file, array( 'wpmtst-validation-plugin' ), false, true );
			}
		}

	}

	/**
	 * Fonts
	 */
	wp_register_style( 'wpmtst-font-awesome', WPMTST_COMMON_URL . 'fonts/font-awesome-4.6.3/css/font-awesome.min.css', array(), '4.6.3' );

	/**
	 * Slider
	 */
	wp_register_script( 'jquery-actual', WPMTST_PUBLIC_URL . 'js/lib/actual/jquery.actual.js', array( 'jquery' ), false, true );
	wp_register_script( 'wpmslider', WPMTST_PUBLIC_URL . 'js/lib/wpmslider/jquery.wpmslider.js', array( 'jquery-actual' ), $plugin_version, true );
	wp_register_script( 'strongslider', WPMTST_PUBLIC_URL . 'js/jquery.strongslider.js', array( 'wpmslider' ), $plugin_version, true );
	if ( defined( 'MEGAMENU_VERSION' ) ) {
		wp_register_script( 'wpmtst-slider', WPMTST_PUBLIC_URL . 'js/slider-megamenu.js', array( 'strongslider' ), $plugin_version, true );
	} else {
		wp_register_script( 'wpmtst-slider', WPMTST_PUBLIC_URL . 'js/slider.js', array( 'strongslider' ), $plugin_version, true );
	}

}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts' );


/**
 * Enqueue "normal" scripts and styles
 *
 * @since 1.15.0
 * @since 2.3 As separate function.
 */
function wpmtst_scripts_normal() {

	$styles = WPMST()->get_styles();

	if ( $styles ) {
		foreach ( $styles['normal'] as $key => $style ) {
			wp_enqueue_style( $style );
		}
	}

	$scripts = WPMST()->get_scripts();

	if ( $scripts ) {
		foreach ( $scripts['normal'] as $key => $script ) {
			wp_enqueue_script( $script );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts_normal' );


/**
 * Enqueue styles and scripts after theme.
 *
 * @since 1.15.0
 */
function wpmtst_scripts_after_theme() {

	$styles = WPMST()->get_styles();

	if ( $styles ) {
		foreach ( $styles['later'] as $key => $style ) {
			wp_enqueue_style( $style );
		}
	}

	$scripts = WPMST()->get_scripts();

	if ( $scripts ) {
		foreach ( $scripts['later'] as $key => $script ) {
			wp_enqueue_script( $script );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts_after_theme', 200 );
