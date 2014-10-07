<?php
/*
 * Submission form shortcode
 */
 
function wpmtst_form_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 
				'category' => '',
		),
		normalize_empty_atts( $atts )
	) );
	
	// explode categories
	$categories = explode( ',', $category );

	$options = get_option( 'wpmtst_options' );
	
	$field_options       = get_option( 'wpmtst_fields' );
	$field_groups        = $field_options['field_groups'];
	$current_field_group = $field_groups[ $field_options['current_field_group'] ];
	$fields              = $current_field_group['fields'];
  
	$captcha         = $options['captcha'];
	$honeypot_before = $options['honeypot_before'];
	$honeypot_after  = $options['honeypot_after'];

	$errors = array();
	
	// Init three arrays: post, post_meta, attachment(s).
	$testimonial_post = array(
			'post_status'  => 'pending',
			'post_type'    => 'wpm-testimonial'
	);
	$testimonial_meta = array();
	$testimonial_att = array();

	foreach ( $fields as $key => $field ) {
		$testimonial_inputs[ $field['name'] ] = '';
	}

	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] ) ) {
	
		if ( ! wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_submission_form' ) )
			die( __( 'There was a problem processing your testimonial.', 'strong-testimonials' ) );

		if ( $captcha )
			$errors = wpmtst_captcha_check( $captcha, $errors );

		if ( $options['honeypot_before'] )
			do_action('wpmtst_honeypot_before');

		if ( $options['honeypot_after'] )
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
				
					if ( 'file' == $field['input_type'] )
						$testimonial_att[ $field['name'] ] = isset( $field['map'] ) ? $field['map'] : 'post';
					else
						$testimonial_post[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
						
				}
				elseif ( 'custom' == $field['record_type'] ) {
				
					if ( 'email' == $field['input_type'] ) {
						$testimonial_meta[ $field['name'] ] = sanitize_email( $_POST[ $field['name'] ] );
					}
					elseif ( 'url' == $field['input_type'] ) {
						// wpmtst_get_website() will prefix with "http://"
						// so don't add that to an empty input
						if ( $_POST[ $field['name'] ] )
							$testimonial_meta[ $field['name'] ] = esc_url_raw( wpmtst_get_website( $_POST[ $field['name'] ] ) );
					}
					elseif ( 'text' == $field['input_type'] ) {
						$testimonial_meta[ $field['name'] ] = sanitize_text_field( $_POST[ $field['name'] ] );
					}
					
				}
				
			}

		}

    if ( ! count( $errors ) ) {
		
			// special handling:
			// if post_title is not required, create one from post_content
			if ( ! isset( $testimonial_post['post_title'] ) || ! $testimonial_post['post_title'] ) {
				$words_array = explode( ' ', $testimonial_post['post_content'] );
				$five_words = array_slice( $words_array, 0, 5 );
				$testimonial_post['post_title'] = implode( ' ', $five_words );
			}

			// create new testimonial post
			if ( $testimonial_id = wp_insert_post( $testimonial_post ) ) {

				// add to categories
				$category_success = wp_set_post_terms( $testimonial_id, $categories, 'wpm-testimonial-category' );
				// @TODO improve error handling
				// if ( $categories && ! $category_success ) ...

				// save custom fields
				foreach ( $testimonial_meta as $key => $field ) {
					add_post_meta( $testimonial_id, $key, $field );
				}

				// save attachments
				foreach ( $testimonial_att as $name => $map ) {
					if ( isset( $_FILES[$name] ) && $_FILES[$name]['size'] > 1 ) {
						$file = $_FILES[$name];
						
						// Upload file
						$overrides = array( 'test_form' => false );
						$uploaded_file = wpmtst_wp_handle_upload( $file, $overrides );
						$image = $uploaded_file['url'];

						// Create an attachment
						$attachment = array(
								'post_title'     => $file['name'],
								'post_content'   => '',
								'post_type'      => 'attachment',
								'post_parent'    => $testimonial_id,
								'post_mime_type' => $file['type'],
								'guid'           => $uploaded_file['url']
						);

						$attach_id = wp_insert_attachment( $attachment, $uploaded_file['file'], $testimonial_id );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_file['file'] );
						$result = wp_update_attachment_metadata( $attach_id,  $attach_data );
						add_post_meta( $testimonial_id, $name, $image );
						if ( 'featured_image' == $map ) {
							set_post_thumbnail( $testimonial_id, $attach_id );
						}
					}
				}

				wpmtst_notify_admin();
				return '<div class="testimonial-success">' .  __( 'Thank you! Your testimonial is awaiting moderation.', 'strong-testimonials' ) .'</div>';

			}
			else {
				// @TODO post insert error handling
			}

		}
		else {  // errors
			$testimonial_inputs = array_merge( $testimonial_inputs, $testimonial_post, $testimonial_meta );
    }

	}  // if posted

	// ---------------------------
	// Testimonial Submission Form
	// ---------------------------
	// output buffering made this incredibly unreadable
	
	$html = '<div id="wpmtst-form">';
	$html .= '<p class="required-notice"><span class="required symbol"></span>' . __( 'Required Field', 'strong-testimonials' ) . '</p>';
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

		// -----------------------------
		// input types: text, email, url
		// -----------------------------
		if ( in_array( $field['input_type'], array( 'text', 'email', 'url' ) ) ) {

			$html .= '<input id="wpmtst_' . $field['name'] . '"'
						. ' type="' . $field['input_type'] . '"'
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

			$html .= '<textarea id="wpmtst_' . $field['name'] . '" class="textarea" name="' . $field['name'] . '"';
			
			if ( isset( $field['required'] ) && $field['required'] )
				$html .= ' required';
				
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

		if ( isset( $errors[ $field['name'] ] ) )
			$html .= '<span class="error">' . $errors[ $field['name'] ] . '</span>';

		if ( isset( $field['after']) && $field['after'] )
			$html .= '<span class="after">' . $field['after'] . '</span>';

		$html .= '</p>';

	}

	if ( $options['honeypot_before'] )
		$html .= '<span class="wpmtst_if_visitor"><label for="wpmtst_if_visitor">Visitor?</label><input id="wpmtst_if_visitor" type="text" name="wpmtst_if_visitor" size="40" tabindex="-1" autocomplete="off" /></span>';
	
	if ( $captcha ) {
		// Only display Captcha label if properly configured.
		$captcha_html = apply_filters( 'wpmtst_captcha', $captcha );
		if ( $captcha_html ) {
			$html .= '<div class="wpmtst-captcha">';
			$html .= '<label for="wpmtst_captcha">' . __( 'Captcha', 'strong-testimonials' ) . '</label><span class="required symbol"></span>';
			$html .= '<div>';
			$html .= $captcha_html;
			if ( isset( $errors['captcha'] ) )
				$html .= '<p><label class="error">' . $errors['captcha'] . '</label></p>';
			$html .= '</div>';
			$html .= '</div>';
		}
	}

	$html .= '<p class="form-field">';
	$html .= '<input type="submit" id="wpmtst_submit_testimonial"'
				.' name="wpmtst_submit_testimonial"'
				.' value="' . __( 'Add Testimonial', 'strong-testimonials' ) . '"'
				.' class="button" validate="required:true" />';
	$html .= '</p>';
	
	$html .= '</form>';
	$html .= '</div><!-- wpmtst-form -->';

	return $html;
}
add_shortcode( 'wpmtst-form', 'wpmtst_form_shortcode' );


function wpmtst_honeypot_before() {
	$value = isset( $_POST['wpmtst_if_visitor'] ) ? $_POST['wpmtst_if_visitor'] : '';
	if ( isset( $_POST['wpmtst_if_visitor'] ) && ! empty( $_POST['wpmtst_if_visitor'] ) ) {
		do_action( 'honeypot_before_spam_testimonial', $_POST );
		die( __( 'There was a problem processing your testimonial.', 'strong-testimonials' ) );
	}
	return;
}


function wpmtst_honeypot_after() {
	if ( ! isset ( $_POST['zero-spam'] ) ) {
		do_action( 'honeypot_after_spam_testimonial', $_POST );
		die( __( 'There was a problem processing your testimonial.', 'strong-testimonials' ) );
	}
	return;
}


function wpmtst_honeypot_before_script() { 
	?><script type="text/javascript">jQuery('#wpmtst_if_visitor').val('');</script><?php 
}


function wpmtst_honeypot_after_script() {
	?><script type="text/javascript">
( function( $ ) {
	'use strict';
	var forms = "#wpmtst-submission-form";
	$( forms ).submit( function() {
		$( "<input>" ).attr( "type", "hidden" )
		.attr( "name", "zero-spam" )
		.attr( "value", "1" )
		.appendTo( forms );
		return true;
	});
})( jQuery );
</script><?php
}