<?php
/**
 * Register scripts and styles.
 */
function wpmtst_scripts() {

	$wpmst = WPMST();

	// Formerly WPMTST_URL/css/wpmtst.css
	wp_register_style( 'wpmtst-style', WPMTST_TPL_URI . 'original/testimonials.css' );

	// Formerly WPMTST_URL/css/wpmtst-form.css
	wp_register_style( 'wpmtst-form-style', WPMTST_TPL_URI . 'original/testimonial-form.css' );
	
	wp_register_style( 'wpmtst-widget-style', WPMTST_URL . 'css/wpmtst-widget.css' );

	wp_register_style( 'wpmtst-rtl-style', WPMTST_URL . 'css/wpmtst-rtl.css' );
	wp_register_style( 'wpmtst-widget-rtl-style', WPMTST_URL . 'css/wpmtst-widget-rtl.css' );

	wp_register_script( 'wpmtst-pager-plugin', WPMTST_URL . 'js/quickpager.jquery.js', array( 'jquery' ), false, true );
	wp_register_script( 'wpmtst-pager-script', WPMTST_URL . 'js/wpmtst-pager.js', array( 'wpmtst-pager-plugin' ), false, true );

	// original with broken dependency:
	//wp_register_script( 'wpmtst-form-script', WPMTST_URL . 'js/wpmtst-form.js', array( 'wpmtst-validation-plugin', 'wpmtst-validation-lang' ), false, true );

	/**
	 * Allow disabling of client-side form validation via filter.
	 *
	 * @since 1.21.0
	 */
	if ( apply_filters( 'wpmtst_field_required_tag', true ) && apply_filters( 'wpmtst_form_validation_script', true ) ) {
		wp_register_script( 'wpmtst-form-script', WPMTST_URL . 'js/wpmtst-form.js', array( 'wpmtst-validation-plugin' ), false, true );
		wp_register_script( 'wpmtst-validation-plugin', WPMTST_URL . 'js/validate/jquery.validate.min.js', array( 'jquery' ), false, true );
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
	 * Enqueue "normal" scripts and styles
	 *
	 * @since 1.15.0
	 */

	$styles = $wpmst->get_styles();

	if ( $styles ) {
		foreach ( $styles['normal'] as $key => $style ) {
			wp_enqueue_style( $style );
		}
	}

	$scripts = $wpmst->get_scripts();

	if ( $scripts ) {
		foreach ( $scripts['normal'] as $key => $script ) {
			wp_enqueue_script( $script );
		}
	}

}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts' );


/**
 * Enqueue styles and scripts after theme.
 *
 * @since 1.15.0
 */
function wpmtst_scripts_after_theme() {

	$wpmst = WPMST();

	/**
	 * Register jQuery Cycle plugin after theme to prevent conflicts.
	 *
	 * Everybody loves Cycle!
	 *
	 * In case the theme loads cycle.js for a slider, we check after it's enqueue function.
	 * If registered, we register our slider script using existing Cycle handle.
	 * If not registered, we register it with our Cycle handle.
	 *
	 * @since 1.14.1
	 */

	$filenames = array(
		'jquery.cycle.all.min.js',
		'jquery.cycle.all.js',
		'jquery.cycle2.min.js',
		'jquery.cycle2.js'
	);

	$cycle_handle = wpmtst_is_registered( $filenames );

	if ( !$cycle_handle ) {
		/**
		 * Using unique handle and loading Cycle instead of Cycle2 for compatibility.
		 * @since 1.16.9
		 */
		$cycle_handle = 'jquery-cycle-in-wpmtst';
		wp_register_script( $cycle_handle, WPMTST_URL . 'js/jquery.cycle.all.js', array( 'jquery' ), '2.9999.5', true );
	}

	// Our slider handler, dependent on whichever jQuery Cycle plugin is being used.
	wp_register_script( 'wpmtst-slider', WPMTST_URL . 'js/wpmtst-cycle.js', array ( $cycle_handle ), false, true );

	/**
	 * Enqueue "later" scripts and styles.
	 *
	 * @since 1.15.0
	 */

	$styles = $wpmst->get_styles();

	if ( $styles ) {
		foreach ( $styles['later'] as $key => $style ) {
			wp_enqueue_style( $style );
		}
	}

	$scripts = $wpmst->get_scripts();

	if ( $scripts ) {
		foreach ( $scripts['later'] as $key => $script ) {
			wp_enqueue_script( $script );
		}
	}

}
add_action( 'wp_enqueue_scripts', 'wpmtst_scripts_after_theme', 200 );
