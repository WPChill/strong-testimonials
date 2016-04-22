<?php
/**
 * Testimonial form handler.
 *
 * @since 1.21.0
 */
function wpmtst_form_handler() {

	if ( empty( $_POST ) )
		return false;

	if ( ! check_admin_referer( 'wpmtst_form_action', 'wpmtst_form_nonce' ) )
		return false;

	add_filter( 'upload_mimes', 'wpmtst_restrict_mime' );

	$form_options = get_option( 'wpmtst_form_options' );

	// Init three arrays: post, post_meta, attachment(s).
	$testimonial_post = array(
		'post_status' => $form_options['post_status'],
		'post_type'   => 'wpm-testimonial'
	);
	$testimonial_meta = array();
	$testimonial_att  = array();

	$form_errors = array();

	$form_options = get_option( 'wpmtst_form_options' );
	$messages     = $form_options['messages'];

	$form_name = isset( $_POST['form_id'] ) ? $_POST['form_id'] : 'custom';
	$fields = wpmtst_get_form_fields( $form_name );

	if ( $form_options['captcha'] )
		$form_errors = wpmtst_captcha_check( $form_options['captcha'], $form_errors );

	if ( $form_options['honeypot_before'] )
		wpmtst_honeypot_before();

	if ( $form_options['honeypot_after'] )
		wpmtst_honeypot_after();

	/**
	 * sanitize & validate
	 */
	foreach ( $fields as $key => $field ) {

		if ( isset( $field['required'] ) && $field['required'] ) {
			if ( ( 'file' == $field['input_type'] ) ) {
				if ( ! isset( $_FILES[ $field['name'] ] ) || ! $_FILES[ $field['name'] ]['size'] ) {
					$form_errors[ $field['name'] ] = $field['error'];
					continue;
				}
			} elseif ( empty( $_POST[ $field['name'] ] ) ) {
				$form_errors[ $field['name'] ] = $field['error'];
				continue;
			}
		}

		switch( $field['record_type'] ) {
			case 'post':
				if ( 'file' == $field['input_type'] ) {
					$testimonial_att[ $field['name'] ] = array( 'field' => isset( $field['map'] ) ? $field['map'] : 'post' );
				} else {
					$testimonial_post[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
				}
				break;

			case 'custom':
				if ( 'email' == $field['input_type'] ) {
					$testimonial_meta[ $field['name'] ] = sanitize_email( $_POST[ $field['name'] ] );
				} elseif ( 'url' == $field['input_type'] ) {
					// wpmtst_get_website() will prefix with "http://" so don't add that to an empty input
					if ( $_POST[ $field['name'] ] )
						$testimonial_meta[ $field['name'] ] = esc_url_raw( wpmtst_get_website( $_POST[ $field['name'] ] ) );
				} elseif ( 'text' == $field['input_type'] ) {
					$testimonial_meta[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
				}
				break;

			case 'optional':
				if ( 'categories' == $field['input_type'] ) {
					//$category   = true;
					$testimonial_meta[ $field['name'] ] = $_POST[ $field['name'] ];
				}
				break;

			default:
		}

	}

	/**
	 * No missing required fields, carry on.
	 */
	if ( ! count( $form_errors ) ) {

		// special handling:
		// if post_title is not required, create one from post_content
		if ( ! isset( $testimonial_post['post_title'] ) || ! $testimonial_post['post_title'] ) {
			$words_array                    = explode( ' ', $testimonial_post['post_content'] );
			$five_words                     = array_slice( $words_array, 0, 5 );
			$testimonial_post['post_title'] = implode( ' ', $five_words );
		}

		// validate image attachments and store WP error messages
		foreach ( $testimonial_att as $name => $atts ) {
			if ( isset( $_FILES[ $name ] ) && $_FILES[ $name ]['size'] > 1 ) {
				$file = $_FILES[ $name ];

				// Upload file
				$overrides     = array( 'test_form' => false );
				$uploaded_file = wpmtst_wp_handle_upload( $file, $overrides );
				/*
				 * $uploaded_file = array (size=3)
				 *   'file' => string 'M:\wp\strong\site/wp-content/uploads/Lotus8.jpg' (length=47)
				 *   'url' => string 'http://strong.dev/wp-content/uploads/Lotus8.jpg' (length=47)
				 *   'type' => string 'image/jpeg' (length=10)
				 */
				if ( isset( $uploaded_file['error'] ) ) {
					$form_errors[ $name ] = $uploaded_file['error'];
					break;
				} else {
					// Create an attachment
					$attachment = array(
						'post_title'     => $file['name'],
						'post_content'   => '',
						'post_type'      => 'attachment',
						'post_parent'    => null, // populated after inserting post
						'post_mime_type' => $file['type'],
						'guid'           => $uploaded_file['url']
					);

					$testimonial_att[ $name ]['attachment']    = $attachment;
					$testimonial_att[ $name ]['uploaded_file'] = $uploaded_file;
				}

			}
		}
	}

	/**
	 * No faulty uploads, carry on.
	 */
	if ( ! count( $form_errors ) ) {

		// create new testimonial post
		$testimonial_id = wp_insert_post( $testimonial_post );
		if ( is_wp_error( $testimonial_id ) ) {

			WPMST()->log( $testimonial_id, __FUNCTION__ );
			// TODO report errors in admin
			$form_errors['post'] = $messages['submission-error']['text'];

		}
		else {

			// add to categories
			if ( $_POST['category'] ) {
				$categories = explode( ',', $_POST['category'] );
				$category_success = wp_set_post_terms( $testimonial_id, $categories, 'wpm-testimonial-category' );
				if ( $categories && ! $category_success ) {
					// TODO improve error handling
				}
			}

			// save custom fields
			foreach ( $testimonial_meta as $key => $field ) {
				add_post_meta( $testimonial_id, $key, $field );
			}

			// save attachments
			foreach ( $testimonial_att as $name => $atts ) {
				if ( isset( $atts['attachment'] ) ) {
					$atts['attachment']['post_parent'] = $testimonial_id;
					$attach_id   = wp_insert_attachment( $atts['attachment'], $atts['uploaded_file']['file'], $testimonial_id );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $atts['uploaded_file']['file'] );
					$result      = wp_update_attachment_metadata( $attach_id, $attach_data );
					add_post_meta( $testimonial_id, $name, $atts['uploaded_file']['url'] );
					if ( 'featured_image' == $atts['field'] ) {
						set_post_thumbnail( $testimonial_id, $attach_id );
					}
				}
			}

		}

	}

	remove_filter( 'upload_mimes', 'wpmtst_restrict_mime' );

	/**
	 * Post inserted successfully, carry on.
	 */
	$form_values = array_merge( $testimonial_post, $testimonial_meta );

	if ( ! count( $form_errors ) ) {
		// Clear saved form data and errors.
		WPMST()->set_form_values( null );
		WPMST()->set_form_errors( null );
		wpmtst_notify_admin( $form_values, $form_name );
		return true;
	}

	WPMST()->set_form_values( $form_values );
	WPMST()->set_form_errors( $form_errors );
	return false;
}

/**
 * Restrict MIME types for security reasons.
 *
 * @param $mimes
 *
 * @return array
 */
function wpmtst_restrict_mime( $mimes ) {
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
	);

	return $mimes;
}

/**
 * File upload handler
 *
 * @param $file_handler
 * @param $overrides
 *
 * @return array
 */
function wpmtst_wp_handle_upload( $file_handler, $overrides ) {
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$upload = wp_handle_upload( $file_handler, $overrides );
	return $upload;
}

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

		// Captcha by BestWebSoft
		case 'bwsmath' :
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

		// Advanced noCaptcha reCaptcha by Shamim Hasan
		case 'advnore' :
			if ( function_exists( 'anr_verify_captcha' ) && !anr_verify_captcha() ) {
				$errors['captcha'] = __( 'The Captcha failed. Please try again.', 'strong-testimonials' );
			}
			break;

		default :
	}

	return $errors;

}

/**
 * Notify admin upon testimonial submission.
 *
 * @param array  $post
 * @param string $form_name
 * @since 1.7.0
 * @since 2.4.0 Logging mail failure.
 */
function wpmtst_notify_admin( $post, $form_name = 'custom' ) {
	$fields = wpmtst_get_form_fields( $form_name );

	$options      = get_option( 'wpmtst_options' );
	$form_options = get_option( 'wpmtst_form_options' );

	if ( $form_options['sender_site_email'] )
		$sender_email = get_bloginfo( 'admin_email' );
	else
		$sender_email = $form_options['sender_email'];

	$sender_name = $form_options['sender_name'];

	if ( $form_options['admin_notify'] ) {

		foreach ( $form_options['recipients'] as $recipient ) {

			if ( isset( $recipient['admin_site_email'] ) && $recipient['admin_site_email'] )
				$admin_email = get_bloginfo( 'admin_email' );
			else
				$admin_email = $recipient['admin_email'];

			$admin_name = $recipient['admin_name'];

			$to = sprintf( '%s <%s>', $admin_name, $admin_email );

			// Subject line
			$subject = $form_options['email_subject'];
			$subject = str_replace( '%BLOGNAME%', get_bloginfo( 'name' ), $subject );
			$subject = str_replace( '%TITLE%', $post['post_title'], $subject );
			$subject = str_replace( '%STATUS%', $post['post_status'], $subject );

			// custom fields
			foreach ( $fields as $field ) {
				$replace      = isset( $post[ $field['name'] ] ) ? $post[ $field['name'] ] : '(blank)';
				$field_as_tag = '%' . strtoupper( $field['name'] ) . '%';
				$subject      = str_replace( $field_as_tag, $replace, $subject );
			}

			// Message text
			$message = $form_options['email_message'];
			$message = str_replace( '%BLOGNAME%', get_bloginfo( 'name' ), $message );
			$message = str_replace( '%TITLE%', $post['post_title'], $message );
			$message = str_replace( '%CONTENT%', $post['post_content'], $message );
			$message = str_replace( '%STATUS%', $post['post_status'], $message );

			// custom fields
			foreach ( $fields as $field ) {
				$replace      = isset( $post[ $field['name'] ] ) ? $post[ $field['name'] ] : '(blank)';
				$field_as_tag = '%' . strtoupper( $field['name'] ) . '%';
				$message      = str_replace( $field_as_tag, $replace, $message );
			}

			$headers = sprintf( 'From: %s <%s>', $sender_name, $sender_email );

			// @TODO More info here? A copy of testimonial? A link to admin page? A link to approve directly from email?

			$mail_sent = wp_mail( $to, $subject, $message, $headers );

			// Log email action
			if ( isset( $options['email_log_level'] ) && $options['email_log_level'] ) {

				// for both levels, log failure only
				// for level 2, log both success and failure
				if ( !$mail_sent || 2 == $options['email_log_level'] ) {
					$log_entry = array(
						'response' => $mail_sent ? 'mail successful' : 'mail failed',
						'to'       => $to,
						'subject'  => $subject,
						'message'  => $message,
						'headers'  => $headers,
					);
					WPMST()->log( $log_entry, __FUNCTION__ );
				}

			}

		} // for each recipient

	} // if notify
}
