<?php
/*
 * Submission form shortcode
 */
 
function wpmtst_restrict_mime( $mimes ) {
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
	);
	return $mimes;
}
add_filter( 'upload_mimes', 'wpmtst_restrict_mime' );


// function wpmtst_wp_handle_upload_prefilter( $file ) {
	// return $file;
// }
// add_filter( 'wp_handle_upload_prefilter', 'wpmtst_wp_handle_upload_prefilter' );


function wpmtst_form_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 
				'category' => false,
		),
		normalize_empty_atts( $atts )
	) );
	
	$options      = get_option( 'wpmtst_options' );
	$form_options = get_option( 'wpmtst_form_options' );
	$messages     = $form_options['messages'];
	
	$field_options       = get_option( 'wpmtst_fields' );
	$field_groups        = $field_options['field_groups'];
	$current_field_group = $field_groups[ $field_options['current_field_group'] ];
	$fields              = $current_field_group['fields'];
  
	$captcha         = $form_options['captcha'];
	$honeypot_before = $form_options['honeypot_before'];
	$honeypot_after  = $form_options['honeypot_after'];
	if ( $honeypot_before ) {
		add_action( 'wp_footer', 'wpmtst_honeypot_before_script' );
		add_action( 'wpmtst_honeypot_before', 'wpmtst_honeypot_before' );
	}
	if ( $honeypot_after ) {
		add_action( 'wp_footer', 'wpmtst_honeypot_after_script' );
		add_action( 'wpmtst_honeypot_after', 'wpmtst_honeypot_after' );
	}
	
	$errors = array();
	
	// explode categories
	if ( $category )
		$categories = explode( ',', $category );

	// Init three arrays: post, post_meta, attachment(s).
	$testimonial_post = array(
			'post_status'  => $form_options['post_status'],
			'post_type'    => 'wpm-testimonial'
	);
	$testimonial_meta = array();
	$testimonial_att  = array();

	foreach ( $fields as $key => $field ) {
		$testimonial_inputs[ $field['name'] ] = '';
	}

	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] ) ) {
	
		if ( ! wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_submission_form' ) )
			die( $messages['submission-error']['text'] );

		if ( $captcha )
			$errors = wpmtst_captcha_check( $captcha, $errors );

		if ( $honeypot_before )
			do_action('wpmtst_honeypot_before');

		if ( $honeypot_after )
			do_action('wpmtst_honeypot_after');
			
		// -------------------
		// sanitize & validate
		// -------------------
		foreach ( $fields as $key => $field ) {

			if ( isset( $field['required'] ) && $field['required'] && empty( $_POST[ $field['name'] ] ) ) {
				$errors[ $field['name'] ] = $field['error'];
			}
			else {
			
				if ( 'post' == $field['record_type'] ) {
				
					if ( 'file' == $field['input_type'] ) {
						$testimonial_att[ $field['name'] ] = array( 'field' => isset( $field['map'] ) ? $field['map'] : 'post' );
					}
					else {
						$testimonial_post[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
					}
					
				}
				elseif ( 'custom' == $field['record_type'] ) {
				
					if ( 'email' == $field['input_type'] ) {
						$testimonial_meta[ $field['name'] ] = sanitize_email( $_POST[ $field['name'] ] );
					}
					elseif ( 'url' == $field['input_type'] ) {
						// wpmtst_get_website() will prefix with "http://" so don't add that to an empty input
						if ( $_POST[ $field['name'] ] )
							$testimonial_meta[ $field['name'] ] = esc_url_raw( wpmtst_get_website( $_POST[ $field['name'] ] ) );
					}
					elseif ( 'text' == $field['input_type'] ) {
						$testimonial_meta[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
					}
					
				}
				
			}

		} // foreach $field

		//
		// No missing required fields, carry on.
		//
    if ( ! count( $errors ) ) {
		
			// special handling:
			// if post_title is not required, create one from post_content
			if ( ! isset( $testimonial_post['post_title'] ) || ! $testimonial_post['post_title'] ) {
				$words_array = explode( ' ', $testimonial_post['post_content'] );
				$five_words = array_slice( $words_array, 0, 5 );
				$testimonial_post['post_title'] = implode( ' ', $five_words );
			}
			
			// validate image attachments and store WP error messages
			foreach ( $testimonial_att as $name => $atts ) {
				if ( isset( $_FILES[$name] ) && $_FILES[$name]['size'] > 1 ) {
					$file = $_FILES[$name];
					
					// Upload file
					$overrides = array( 'test_form' => false );
					$uploaded_file = wpmtst_wp_handle_upload( $file, $overrides );
					/* 
					 * $uploaded_file = array (size=3)
					 *   'file' => string 'M:\wp\strong\site/wp-content/uploads/Lotus8.jpg' (length=47)
					 *   'url' => string 'http://strong.dev/wp-content/uploads/Lotus8.jpg' (length=47)
					 *   'type' => string 'image/jpeg' (length=10)
					 */
					if ( isset( $uploaded_file['error'] ) )	{
						$errors[ $name ] = $uploaded_file['error'];
						break;
					}
					else {
						// Create an attachment
						$attachment = array(
								'post_title'     => $file['name'],
								'post_content'   => '',
								'post_type'      => 'attachment',
								'post_parent'    => null, // populated after inserting post
								'post_mime_type' => $file['type'],
								'guid'           => $uploaded_file['url']
						);
						
						$testimonial_att[$name]['attachment'] = $attachment;
						$testimonial_att[$name]['uploaded_file'] = $uploaded_file;
					}
					
				}
			}
		}
		
		//
		// No faulty uploads, carry on.
		//
    if ( ! count( $errors ) ) {
		
			// create new testimonial post
			if ( $testimonial_id = wp_insert_post( $testimonial_post ) ) {

				// add to categories
				if ( $category ) {
					$category_success = wp_set_post_terms( $testimonial_id, $categories, 'wpm-testimonial-category' );
					// @TODO improve error handling
					// if ( $categories && ! $category_success ) ...
				}

				// save custom fields
				foreach ( $testimonial_meta as $key => $field ) {
					add_post_meta( $testimonial_id, $key, $field );
				}

				// save attachments
				foreach ( $testimonial_att as $name => $atts ) {
					if ( isset( $atts['attachment'] ) ) {
						$atts['attachment']['post_parent'] = $testimonial_id;
						$attach_id = wp_insert_attachment( $atts['attachment'], $atts['uploaded_file']['file'], $testimonial_id );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $atts['uploaded_file']['file'] );
						$result = wp_update_attachment_metadata( $attach_id,  $attach_data );
						add_post_meta( $testimonial_id, $name, $atts['uploaded_file']['url'] );
						if ( 'featured_image' == $atts['field'] ) {
							set_post_thumbnail( $testimonial_id, $attach_id );
						}
					}
				}
			
			}
			else {
				// @TODO Add general error message to top of form.
				$errors['post'] = $messages['submission-error']['text'];
			}

		}
		
		//
		// Post inserted successfully, carry on.
		//
		if ( ! count( $errors ) ) {
			wpmtst_notify_admin( array_merge( $testimonial_post, $testimonial_meta ) );
			return '<div class="testimonial-success">' . $messages['submission-success']['text'] .'</div>';
		}
		
		$testimonial_inputs = array_merge( $testimonial_inputs, $testimonial_post, $testimonial_meta );

	}  // if posted

	
	// ---------------------------
	// Testimonial Submission Form
	// ---------------------------
	// output buffering made this incredibly unreadable
	
	$html = '<div id="wpmtst-form">';
	$html .= '<p class="required-notice"><span class="required symbol"></span>' . $messages['required-field']['text'] . '</p>';
	$html .= '<form id="wpmtst-submission-form" method="post" action="" enctype="multipart/form-data">';
	$html .= wp_nonce_field( 'wpmtst_submission_form', 'wpmtst_form_submitted', true, false );

	foreach ( $fields as $key => $field ) {

		if ( 'text' == $field['input_type'] )
			$classes = 'text';
		elseif ( 'email' == $field['input_type'] )
			$classes = 'text email';
		elseif ( 'url' == $field['input_type'] )
			$classes = 'text url';
		else
			$classes = '';

		$html .= '<p class="form-field">';
		$html .= '<label for="wpmtst_' . $field['name'] . '">' . $field['label'] . '</label>';

		if ( isset( $field['required'] ) && $field['required'] )
			$html .= '<span class="required symbol"></span>';

		if ( isset( $field['before'] ) && $field['before'] )
			$html .= '<span class="before">' . $field['before'] . '</span>';

		if ( isset( $errors[ $field['name'] ] ) )
			$classes .= ' error';

		// -----------------------------
		// input types: text, email, url
		// -----------------------------
		if ( in_array( $field['input_type'], array( 'text', 'email', 'url' ) ) ) {
		
			/*
			 * Switching out url type until more themes adopt it.
			 * @since 1.11.0
			 */
			$input_type = ( $field['input_type'] = 'url' ? 'text' : $field['input_type'] );
			
			$html .= '<input id="wpmtst_' . $field['name'] . '"'
						// . ' type="' . $field['input_type'] . '"'
						. ' type="' . $input_type . '"'
						. ' class="' . $classes . '"'
						. ' name="' . $field['name'] . '"'
						. ' value="' . $testimonial_inputs[ $field['name'] ] . '"';

			if ( isset( $field['placeholder'] ) && $field['placeholder'] )
				$html .= ' placeholder="' . $field['placeholder'] . '"';

			if ( isset( $field['required'] ) && $field['required'] )
				$html .= ' required';

			$html .= ' />';

		}
		// ------------------------------------------
		// input type: textarea <-- post_content ONLY
		// ------------------------------------------
		elseif ( 'textarea' == $field['input_type'] ) {

			$html .= '<textarea id="wpmtst_' . $field['name'] . '" class="' . $classes . '" name="' . $field['name'] . '"';
			
			// if ( isset( $field['required'] ) && $field['required'] )
				// $html .= ' required';
				
			if ( isset( $field['placeholder'] ) && $field['placeholder'] )
				$html .= ' placeholder="' . $field['placeholder'] . '"';
			
			$html .= '>' . $testimonial_inputs[ $field['name'] ] . '</textarea>';

		}
		// -----------------
		// input type: image
		// -----------------
		elseif ( 'file' == $field['input_type'] ) {

			$html .= '<input id="wpmtst_' . $field['name'] . '" class="" type="file" name="' . $field['name'] . '" />';

		}

		// Add error message
		if ( isset( $errors[ $field['name'] ] ) )
			$html .= '<span class="error">' . $errors[ $field['name'] ] . '</span>';

		// Add "after" message
		if ( isset( $field['after']) && $field['after'] )
			$html .= '<span class="after">' . $field['after'] . '</span>';

		$html .= '</p>';

	} // foreach $field

	if ( $honeypot_before ) {
		$html .= '<style>#wpmtst-form .wpmtst_if_visitor * { display: none !important; visibility: hidden !important; }</style>';
		$html .= '<span class="wpmtst_if_visitor"><label for="wpmtst_if_visitor">Visitor?</label><input id="wpmtst_if_visitor" type="text" name="wpmtst_if_visitor" size="40" tabindex="-1" autocomplete="off" /></span>';
	}
	
	if ( $captcha ) {
		// Only display Captcha label if properly configured.
		$captcha_html = apply_filters( 'wpmtst_captcha', $captcha );
		if ( $captcha_html ) {
			$html .= '<div class="wpmtst-captcha">';
			$html .= '<label for="wpmtst_captcha">' . $messages['captcha']['text'] . '</label><span class="required symbol"></span>';
			$html .= '<div>';
			$html .= $captcha_html;
			if ( isset( $errors['captcha'] ) )
				$html .= '<p><label class="error">' . $errors['captcha'] . '</label></p>';
			$html .= '</div>';
			$html .= '</div>';
		}
	}

	// /* translators: The Submit button on testimonial form.*/
	$html .= '<p class="form-field">';
	$html .= '<input type="submit" id="wpmtst_submit_testimonial"'
				.' name="wpmtst_submit_testimonial"'
				.' value="' . $messages['form-submit-button']['text'] . '"'
				.' class="button" validate="required:true" />';
	$html .= '</p>';
	
	$html .= '</form>';
	$html .= '</div><!-- wpmtst-form -->' . "\n";

	return $html;
}
add_shortcode( 'wpmtst-form', 'wpmtst_form_shortcode' );


/**
 * Honeypot preprocessor
 */
function wpmtst_honeypot_before() {
	if ( isset( $_POST['wpmtst_if_visitor'] ) && ! empty( $_POST['wpmtst_if_visitor'] ) ) {
		do_action( 'honeypot_before_spam_testimonial', $_POST );
		$form_options = get_option( 'wpmtst_form_options' );
		die( $form_options['messages']['submission-error']['text'] );
	}
	return;
}


/**
 * Honeypot preprocessor
 */
function wpmtst_honeypot_after() {
	if ( ! isset ( $_POST['wpmtst_after'] ) ) {
		do_action( 'honeypot_after_spam_testimonial', $_POST );
		$form_options = get_option( 'wpmtst_form_options' );
		die( $form_options['messages']['submission-error']['text'] );
	}
	return;
}


/**
 * Honeypot
 */
function wpmtst_honeypot_before_script() {
	?>
<script type="text/javascript">jQuery('#wpmtst_if_visitor').val('');</script>
	<?php 
}


/**
 * Honeypot
 */
function wpmtst_honeypot_after_script() {
	?>
<script type="text/javascript">
	( function( $ ) {
		'use strict';
		var forms = "#wpmtst-submission-form";
		$( forms ).submit( function() {
			$( "<input>" ).attr( "type", "hidden" )
			.attr( "name", "wpmtst_after" )
			.attr( "value", "1" )
			.appendTo( forms );
			return true;
		});
	})( jQuery );
</script>
	<?php
}


/*
 * File upload handler
 */
function wpmtst_wp_handle_upload( $file_handler, $overrides ) {
  require_once( ABSPATH . 'wp-admin/includes/image.php' );
  require_once( ABSPATH . 'wp-admin/includes/file.php' );
  require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$upload = wp_handle_upload( $file_handler, $overrides );
	return $upload ;
}


/*
 * Submission form validation.
 */
function wpmtst_validation_function() {
	echo "\r"; ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#wpmtst-submission-form").validate({});
	});
</script>
	<?php
}
