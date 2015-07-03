<?php
/**
 * Captcha functions.
 *
 * @package Strong_Testimonials
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

		// Advanced noCaptcha reCaptcha by Shamim Hasan
		case 'advnore' :
			if ( function_exists( 'anr_captcha_form_field' ) ) {
				$html .= anr_captcha_form_field( false );
			}
			break;

		default :
			// no captcha

	}
	return $html;
}
add_filter( 'wpmtst_captcha', 'wpmtst_add_captcha', 50, 1 );
