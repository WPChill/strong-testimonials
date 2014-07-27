<?php
/**
 * Strong Testimonials - Captcha functions
 */

 
/*
 * Add to form
 */
function wpmtst_add_captcha( $captcha ) {
	$html = '';
	switch ( $captcha ) {

		case 'akismet' :
			break;

		// Captcha by BestWebSoft
		case 'bwsmath' :
			if ( function_exists( 'cptch_display_captcha_custom' ) ) {
				$html .= '<input type="hidden" name="cntctfrm_contact_action" value="true" />';
				$html .= cptch_display_captcha_custom();
			}
			break;

		// Strong reCAPTCHA by WP Mission
		case 'wpmsrc' :
			if ( function_exists( 'wpmsrc_display' ) ) {
				$html .= wpmsrc_display();
			}
			break;

		// Really Simple Captcha by Takayuki Miyoshi
		case 'miyoshi' :
			if ( class_exists( 'ReallySimpleCaptcha' ) ) {
				$captcha_instance = new ReallySimpleCaptcha();
				$word = $captcha_instance->generate_random_word();
				$prefix = mt_rand();
				$image = $captcha_instance->generate_image( $prefix, $word );
				$html .= '<span>Input this code: <input type="hidden" name="captchac" value="'.$prefix.'" /><img class="captcha" src="' . plugins_url( 'really-simple-captcha/tmp/' ) . $image . '"></span>';
				$html .= '<input type="text" class="captcha" name="captchar" maxlength="4" size="5" />';
			}
			break;
			
		default :
			// no captcha

	}
	return $html;
}
// add_action( 'wpmtst_captcha', 'wpmtst_add_captcha', 50, 1 );
add_filter( 'wpmtst_captcha', 'wpmtst_add_captcha', 50, 1 );


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

			// Really Simple Captcha by Takayuki Miyoshi
			case 'miyoshi':
				if ( class_exists( 'ReallySimpleCaptcha' ) ) {
					$captcha_instance = new ReallySimpleCaptcha();
					$prefix = isset( $_POST['captchac'] ) ? (string) $_POST['captchac'] : '';
					$response = isset( $_POST['captchar'] ) ? (string) $_POST['captchar'] : '';
					$correct = $captcha_instance->check( $prefix, $response );
					if ( ! $correct )
						$errors['captcha'] = __( 'The Captcha was not entered correctly. Please try again.', WPMTST_NAME );
					// remove the temporary image and text files
					// (except on Windows)
					if ( '127.0.0.1' != $_SERVER['SERVER_ADDR'] )
						$captcha_instance->remove( $prefix );
				}
				break;
				
		default :

	}
	return $errors;
}
