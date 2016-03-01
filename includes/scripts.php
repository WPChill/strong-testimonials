<?php
/**
 * Register scripts and styles.
 */

function wpmtst_scripts() {

	$plugin_version = get_option( 'wpmtst_plugin_version' );

	wp_register_script( 'wpmtst-pager-plugin', WPMTST_URL . 'js/quickpager.jquery.js', array( 'jquery' ), false, true );
	wp_register_script( 'wpmtst-pager-script', WPMTST_URL . 'js/wpmtst-pager.js', array( 'wpmtst-pager-plugin' ), $plugin_version, true );

	wp_register_style( 'wpmtst-custom-style', WPMTST_URL . 'css/wpmtst-custom.css' );

	wp_register_script( 'imagesloaded-script', WPMTST_URL . 'js/imagesloaded.pkgd.min.js', array(), false, true );
	wp_register_script( 'wpmtst-masonry-script', WPMTST_URL . 'js/wpmtst-masonry.js', array( 'jquery-masonry', 'imagesloaded-script' ), $plugin_version, true );
	wp_register_style( 'wpmtst-masonry-style', WPMTST_URL . 'css/wpmtst-masonry.css', array(), $plugin_version );

	wp_register_style( 'wpmtst-columns-style', WPMTST_URL . 'css/wpmtst-columns.css', array(), $plugin_version );

	wp_register_script( 'wpmtst-grid-script', WPMTST_URL . 'js/wpmtst-grid.js', array( 'jquery' ), $plugin_version, true );
	wp_register_style( 'wpmtst-grid-style', WPMTST_URL . 'css/wpmtst-grid.css', array(), $plugin_version );

	if ( wpmtst_using_form_validation_script() ) {

		wp_register_script( 'wpmtst-validation-plugin', WPMTST_URL . 'js/validate/jquery.validate.min.js', array( 'jquery' ), false, true );

		wp_register_script( 'wpmtst-form-script', WPMTST_URL . 'js/wpmtst-form.js', array( 'wpmtst-validation-plugin', 'jquery-form' ), $plugin_version, true );

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

	wpmtst_register_cycle();

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


/**
 * Register our slider if necessary.
 *
 * @since 2.3 As separate function.
 */
function wpmtst_register_cycle() {
	$plugin_version = get_option( 'wpmtst_plugin_version' );
	$filenames = array(
		'jquery.cycle.all.min.js',
		'jquery.cycle.all.js',
		'jquery.cycle2.min.js',
		'jquery.cycle2.js'
	);

	$cycle_handle = wpmtst_is_registered( $filenames );

	if ( ! $cycle_handle ) {
		// Using unique handle and loading Cycle2 for better dimension handling.
		$cycle_handle = 'jquery-cycle-in-wpmtst';
		wp_register_script( $cycle_handle, WPMTST_URL . 'js/cycle/jquery.cycle2.min.js', array( 'jquery' ), '2.1.6', true );
	}

	// Our slider handler, dependent on whichever jQuery Cycle plugin is being used.
	wp_register_script( 'jquery-actual', WPMTST_URL . 'js/actual/jquery.actual.min.js', array( 'jquery' ), false, true );
	wp_register_script( 'wpmtst-slider', WPMTST_URL . 'js/wpmtst-cycle.js', array( $cycle_handle, 'jquery-actual' ), $plugin_version, true );
}
