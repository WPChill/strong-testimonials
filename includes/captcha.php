<?php
/**
 * Captcha functions.
 *
 * @package Strong_Testimonials
 */

 
/**
 * Add to form
 *
 * @param $captcha
 *
 * @return string
 */
function wpmtst_add_captcha( $captcha ) {
	$html = '';

	switch ( $captcha ) {

		case 'akismet' :
			break;

		// Advanced noCaptcha reCaptcha by Shamim Hasan
		case 'advnore' :
			if ( function_exists( 'anr_captcha_form_field' ) ) {
				$html .= anr_captcha_form_field( false );
			}
			break;

		// Captcha by BestWebSoft
		case 'bwsmath' :
		case 'bwsmathpro' :
			if ( function_exists( 'cptch_display_captcha_custom' ) ) {
				$html .= '<input type="hidden" name="cntctfrm_contact_action" value="true">';
				$html .= cptch_display_captcha_custom();
			}
			break;

		// Really Simple Captcha by Takayuki Miyoshi
		case 'miyoshi' :
			if ( class_exists( 'ReallySimpleCaptcha' ) ) {
				$captcha_instance = new ReallySimpleCaptcha();
				$word = $captcha_instance->generate_random_word();
				$prefix = mt_rand();
				$image = $captcha_instance->generate_image( $prefix, $word );
				$html .= '<span>' . _x( 'Input this code:', 'Captcha', 'strong-testimonials' ) . '&nbsp;<input type="hidden" name="captchac" value="'.$prefix.'"><img class="captcha" src="' . plugins_url( 'really-simple-captcha/tmp/' ) . $image . '"></span>';
				$html .= '<input type="text" class="captcha" name="captchar" maxlength="4" size="5">';
			}
			break;

		default :
			// no captcha

	}

	return $html;
}
add_filter( 'wpmtst_captcha', 'wpmtst_add_captcha', 50, 1 );


/**
 * Check form input
 *
 * @param $captcha
 * @param $errors
 *
 * @return mixed
 */
function wpmtst_captcha_check( $captcha, $errors ) {

	switch ( $captcha ) {

		// Advanced noCaptcha reCaptcha by Shamim Hasan
		case 'advnore' :
			if ( function_exists( 'anr_verify_captcha' ) && !anr_verify_captcha() ) {
				$errors['captcha'] = __( 'The Captcha failed. Please try again.', 'strong-testimonials' );
			}
			break;

		// Captcha by BestWebSoft
		case 'bwsmath' :
		case 'bwsmathpro' :
			if ( function_exists( 'cptch_check_custom_form' ) && cptch_check_custom_form() !== true ) {
				$errors['captcha'] = __( 'The Captcha failed. Please try again.', 'strong-testimonials' );
			}
			break;

		// Really Simple Captcha by Takayuki Miyoshi
		case 'miyoshi' :
			if ( class_exists( 'ReallySimpleCaptcha' ) ) {
				$captcha_instance = new ReallySimpleCaptcha();
				$prefix = isset( $_POST['captchac'] ) ? (string) $_POST['captchac'] : '';
				$response = isset( $_POST['captchar'] ) ? (string) $_POST['captchar'] : '';
				$correct = $captcha_instance->check( $prefix, $response );
				if ( !$correct ) {
					$errors['captcha'] = __( 'The Captcha failed. Please try again.', 'strong-testimonials' );
				}
				// remove the temporary image and text files (except on Windows)
				if ( '127.0.0.1' != $_SERVER['SERVER_ADDR'] ) {
					$captcha_instance->remove( $prefix );
				}
			}
			break;

		default :
	}

	return $errors;

}


/**
 * Build list of supported Captcha plugins.
 *
 * TODO - Move this to options array and add filter
 */
function wpmtst_get_captcha_plugins() {
	$plugins = array(
		'advnore' => array(
			'name'      => 'Advanced noCaptcha reCaptcha by Shamim Hasan',
			'file'      => 'advanced-nocaptcha-recaptcha/advanced-nocaptcha-recaptcha.php',
			'settings'  => 'admin.php?page=anr-admin-settings',
			'search'    => 'plugin-install.php?tab=search&s=Advanced+noCaptcha+reCaptcha',
			'url'       => 'http://wordpress.org/plugins/advanced-nocaptcha-recaptcha',
			'installed' => false,
			'active'    => false,
		),
		'bwsmath' => array(
			'name'      => 'Captcha by BestWebSoft',
			'file'      => 'captcha/captcha.php',
			'settings'  => 'admin.php?page=captcha.php',
			'search'    => 'plugin-install.php?tab=search&s=Captcha',
			'url'       => 'http://wordpress.org/plugins/captcha/',
			'installed' => false,
			'active'    => false,
		),
		'bwsmathpro' => array(
			'name'      => 'Captcha Pro by BestWebSoft',
			'file'      => 'captcha-pro/captcha_pro.php',
			'settings'  => 'admin.php?page=captcha_pro.php',
			'search'    => 'plugin-install.php?tab=search&s=Captcha+Pro',
			'url'       => 'https://bestwebsoft.com/products/wordpress/plugins/captcha/',
			'installed' => false,
			'active'    => false,
		),
		'miyoshi' => array(
			'name'      => 'Really Simple Captcha by Takayuki Miyoshi',
			'file'      => 'really-simple-captcha/really-simple-captcha.php',
			'search'    => 'plugin-install.php?tab=search&s=Really+Simple+Captcha',
			'url'       => 'http://wordpress.org/plugins/really-simple-captcha/',
			'installed' => false,
			'active'    => false,
		),
	);

	return $plugins;
}
