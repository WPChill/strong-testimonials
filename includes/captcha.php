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
				$html .= '<span>' . __( 'Input this code:', 'strong-testimonials' ) 
							. '&nbsp;<input type="hidden" name="captchac" value="'.$prefix.'" />'
							. '<img class="captcha" src="' . plugins_url( 'really-simple-captcha/tmp/' ) . $image . '"></span>';
				$html .= '<input type="text" class="captcha" name="captchar" maxlength="4" size="5" />';
			}
			break;
		
		case 'zerospam' :
			// add_action( 'zero_spam_enqueue_scripts', 'wpmtst_zerospam' );
			// add_action( 'zero_spam_enqueue_scripts', 'wpmtst_zerospam', 20 );
			echo '<script type="text/javascript">';
			echo '/* <![CDATA[ */';
			echo 'var forms = "#commentform, #registerform, #wpmtst-submission-form";';
			echo '/* ]]> */';
			echo '</script>';
			break;
			
		default :
			// no captcha

	}
	return $html;
}
add_filter( 'wpmtst_captcha', 'wpmtst_add_captcha', 50, 1 );


/*
 * Check form input
 */
function wpmtst_captcha_check( $captcha, $errors ) {
	switch ( $captcha ) {

		// Captcha by BestWebSoft
		case 'bwsmath' :
			if ( function_exists( 'cptch_check_custom_form' ) && cptch_check_custom_form() !== true ) {
				$errors['captcha'] = __( 'Please complete the CAPTCHA.', 'strong-testimonials' );
			}
			break;

		// Simple reCAPTCHA by WP Mission
		case 'wpmsrc' :
			if ( function_exists( 'wpmsrc_check' ) ) {
				// check for empty user response first
				if ( empty( $_POST['recaptcha_response_field'] ) ) {
					$errors['captcha'] = __( 'Please complete the CAPTCHA.', 'strong-testimonials' );
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
						$error_codes['invalid-site-private-key'] = __( 'Invalid keys. Please contact the site administrator.', 'strong-testimonials' );
						$error_codes['invalid-request-cookie']   = __( 'Invalid parameter. Please contact the site administrator.', 'strong-testimonials' );
						$error_codes['incorrect-captcha-sol']    = __( 'The CAPTCHA was not entered correctly. Please try again.', 'strong-testimonials' );
						$error_codes['captcha-timeout']          = __( 'The process timed out. Please try again.', 'strong-testimonials' );
						// $error_codes['recaptcha-not-reachable']  = 'Unable to reach reCAPTCHA server. Please contact the site administrator.';
						$errors['captcha'] = $error_codes[ $response->error ];
					}
				}
			}
			break;

		// Really Simple Captcha by Takayuki Miyoshi
		case 'miyoshi' :
			if ( class_exists( 'ReallySimpleCaptcha' ) ) {
				$captcha_instance = new ReallySimpleCaptcha();
				$prefix = isset( $_POST['captchac'] ) ? (string) $_POST['captchac'] : '';
				$response = isset( $_POST['captchar'] ) ? (string) $_POST['captchar'] : '';
				$correct = $captcha_instance->check( $prefix, $response );
				if ( ! $correct )
					$errors['captcha'] = __( 'The CAPTCHA was not entered correctly. Please try again.', 'strong-testimonials' );
				// remove the temporary image and text files (except on Windows)
				if ( '127.0.0.1' != $_SERVER['SERVER_ADDR'] )
					$captcha_instance->remove( $prefix );
			}
			break;
		
		default :
	}
	return $errors;
}
