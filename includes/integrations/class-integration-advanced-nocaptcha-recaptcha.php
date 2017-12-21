<?php
class Strong_Testimonials_Integration_Advanced_noCaptcha_reCaptcha extends Strong_Testimonials_Integration_Captcha {

	public function __contruct() {}

	public function add_captcha() {
		if ( function_exists( 'anr_captcha_form_field' ) ) {
			return anr_captcha_form_field( false );
		}

		return '';
	}

	public function check_captcha( $form_errors ) {
		if ( function_exists( 'anr_verify_captcha' ) && !anr_verify_captcha() ) {
			$form_errors['captcha'] = __( 'The Captcha failed. Please try again.', 'strong-testimonials' );
		}

		return $form_errors;
	}

}
