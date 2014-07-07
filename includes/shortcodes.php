<?php
/**
 * Strong Testimonials - Shortcode functions
 */


/*
 * Normalize empty shortcode attributes
 * (turns atts into tags - brilliant!)
 * Thanks http://wordpress.stackexchange.com/a/123073/32076
 */
if ( ! function_exists( 'normalize_empty_atts' ) ) {
	function normalize_empty_atts( $atts ) {
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
			return $atts;
		}
	}
}


/*
 * Single Testimonial LAYOUT
 */
function wpmtst_single( $post ) {
	$html = '<div class="testimonial">';
	$html .= '<div class="inner">';
	
	if ( ! empty( $post->post_title ) )
		$html .= '<h3 class="heading">' . $post->post_title .'</h3>';
	
	if ( has_post_thumbnail( $post->ID ) )
		$html .= '<div class="photo">' . get_the_post_thumbnail( $post->ID, 'thumbnail' ) . '</div>';

	$html .= '<div class="content">' . wpautop( $post->post_content ) . '</div>';
	$html .= '<div class="clear"></div>';

	$html .= '<div class="client">';
	$html .= wpmtst_client_info( $post );
	$html .= '</div><!-- client -->';
	
	$html .= '</div><!-- inner -->';
	$html .= '</div><!-- testimonial -->';
	
	// render other shortcodes in content,
	// this will render the client_info shortcodes too
	return do_shortcode( $html );
}


/*
 * Assemble and display client info
 */
function wpmtst_client_info( $post ) {
	// ---------------------------------------------------------------------
	// Get the client template, populate it with data from the current post,
	// then render it.
	//
	// Third approach. Took me all day on 6/30/2014.
	// ---------------------------------------------------------------------
	
	$html = '';
	$options = get_option( 'wpmtst_options' );
	$fields = get_option( 'wpmtst_fields' );
	$template = $options['client_section'];
	
	$lines = explode( PHP_EOL, $template );
	// [wpmtst-text field="client_name" class="name"]
	// [wpmtst-link url="company_website" text="company_name" target="_blank" class="company"]
	
	foreach ( $lines as $line ) {
		// to get shortcode:
		$pattern = '/\[([a-z0-9_\-]+)/';
		preg_match( $pattern, $line, $matches );
		if ( $matches ) {
			$shortcode = $matches[1];
			if ( 'wpmtst-text' == $shortcode ) {
				// to get field:
				$pattern = '/field="(\w+)"/';
				preg_match( $pattern, $line, $matches2 );
				if ( $matches2 ) {
					$field_name = $matches2[1];
					$post_value = $post->$field_name;
					// add to line as content and close shortcode
					$line .= $post_value . '[/' . $shortcode . ']';
					$html .= $line;
				}
			}
			elseif ( 'wpmtst-link' == $shortcode ) {
				// (\w+)="(\w+)"
				// to get url:
				$pattern = '/url="(\w+)"/';
				preg_match( $pattern, $line, $matches2 );
				if ( $matches2 ) {
					$field_name = $matches2[1];
					$post_value = $post->$field_name;
					// add to line as content with separator
					$line .= $post_value . '|';
				}
				// to get text:
				$pattern = '/text="(\w+)"/';
				preg_match( $pattern, $line, $matches3 );
				if ( $matches3 ) {
					$field_name = $matches3[1];
					$post_value = $post->$field_name;
					// add to line as content
					$line .= $post_value;
				}
				// close shortcode
				$line .= '[/' . $shortcode . ']';
				$html .= $line;
			}
		}
	}
	// return do_shortcode( $html );
	return $html;
}


/*
 * Client text field shortcode.
 */
function wpmtst_text_shortcode( $atts, $content = null ) {
	// bail if no content
	if ( empty( $content ) || '|' === $content )
		return;
		
	extract( shortcode_atts(
		array( 'field' => '', 'class' => '' ),
		normalize_empty_atts( $atts )
	) );
	return '<div class="' . $class . '">' . $content . '</div>';
}
add_shortcode( 'wpmtst-text', 'wpmtst_text_shortcode' );

/*
 * Client link shortcode.
 */
function wpmtst_link_shortcode( $atts, $content = null ) {
	// content like "company_website|company_name"
	// bail if no content
	if ( empty( $content ) || '|' === $content )
		return;

	extract( shortcode_atts(
		array( 'url' => '', 'target' => '_blank', 'text' => '', 'class' => '' ),
		normalize_empty_atts( $atts )
	) );
		
	list( $url, $text ) = explode( '|', $content );
	
	// if no company name, use domain name
	if ( ! $text )
		$text = preg_replace( "(^https?://)", "", $url );
		
	// if no url, return text_shortcode instead
	if ( $url )
		return '<div class="' . $class . '"><a href="' . $url . '" target="' . $target . '">' . $text . '</a></div>';
	else
		return '<div class="' . $class . '">' . $text . '</div>';
}
add_shortcode( 'wpmtst-link', 'wpmtst_link_shortcode' );


/*
 * Single testimonial shortcode
 */
function wpmtst_single_shortcode( $atts ) {
	extract( shortcode_atts( array( 'id' => '' ), $atts ) );
	$post = wpmtst_get_post( get_post( $id ) );
	$display = wpmtst_single( $post );
	return $display;
}
add_shortcode( 'wpmtst-single', 'wpmtst_single_shortcode' );

/*
 * Random testimonial shortcode
 */
function wpmtst_random_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 'category' => '', 'limit' => '1' ),
		normalize_empty_atts( $atts )
	) );

	$terms = wpmtst_get_terms( $category );

	$args = array(
			$terms['taxo']   => $terms['term'],
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => $limit,
			'orderby'        => 'rand',
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$results  = $wp_query->query( $args );

	$display = '';
	foreach ( $results as $post ) {
		$display .= wpmtst_single( wpmtst_get_post( $post ) );
	}
	return $display;
}
add_shortcode( 'wpmtst-random', 'wpmtst_random_shortcode' );

/*
 * All testimonials shortcode
 *
 * @TODO:
 * - sort options in query
 */
function wpmtst_all_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 'category' => '' ),
		normalize_empty_atts( $atts )
	) );

	$terms = wpmtst_get_terms( $category );

	$args = array(
			$terms['taxo']   => $terms['term'],
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => -1,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$results = $wp_query->query( $args );

	$display = '<div id="wpmtst-container">';
	foreach ( $results as $post ) {
		$display .= '<div class="result">' . wpmtst_single( wpmtst_get_post( $post ) ) . '</div><!-- result -->';
	}
	$display .= '</div><!-- wpmtst-container -->';
	$display .= '<div id="pagingControls"></div>';

	return $display;
}
add_shortcode( 'wpmtst-all', 'wpmtst_all_shortcode' );

/*
 * Cycle testimonials shortcode
 *
 * @TODO:
 * - sort options in query
 */
function wpmtst_cycle_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 'category' => '' ),
		normalize_empty_atts( $atts )
	) );
	$options = get_option( 'wpmtst_options' );
	$cycle = $options['cycle'];

	do_action( 
		'wpmtst_cycle_hook', 
		$cycle['cycle-effect'], 
		$cycle['cycle-speed'], 
		$cycle['cycle-timeout'], 
		$cycle['cycle-pause'],
		'#wpmtst-container',
		'cycleShortcode'
	);

	if ( 'rand' == $cycle['cycle-order'] ) {
		$orderby = 'rand';
		$order   = '';
	}
	elseif ( 'oldest' == $cycle['cycle-order'] ) {
		$orderby = 'post_date';
		$order   = 'ASC';
	}
	else {
		$orderby = 'post_date';
		$order   = 'DESC';
	}

	$terms = wpmtst_get_terms( $category );

	$args = array(
			$terms['taxo']   => $terms['term'],
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => -1,
			'orderby'        => $orderby,
			'order'          => $order,
			'post_status'    => 'publish'
	);

	$wp_query = new WP_Query();
	$results = $wp_query->query( $args );

	$display = '<div id="wpmtst-container" class="tcycle">';
	foreach ( $results as $post ) {
		$display .= '<div class="result">' . wpmtst_single( wpmtst_get_post( $post ) ) . '</div><!-- result -->';
	}
	$display .= '</div><!-- wpmtst-container -->';

	return $display;
}
add_shortcode( 'wpmtst-cycle', 'wpmtst_cycle_shortcode' );

/*
 * Submission form shortcode
 */
function wpmtst_form_shortcode( $atts ) {
	$options = get_option( 'wpmtst_options' );
	$field_options = get_option( 'wpmtst_fields' );
	$captcha = $options['captcha'];

	$field_groups = $field_options['field_groups'];
	$current_field_group = $field_groups[ $field_options['current_field_group'] ];
	$fields = $current_field_group['fields'];
  
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
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_submission_form' ) ) {

		$errors = wpmtst_captcha_check( $captcha, $errors );

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

		// special handling:
		// if post_title is not required, create one from post_content
		if ( ! isset( $testimonial_post['post_title'] ) || ! $testimonial_post['post_title'] ) {
			$words_array = explode( ' ', $testimonial_post['post_content'] );
			$five_words = array_slice( $words_array, 0, 5 );
			$testimonial_post['post_title'] = implode( ' ', $five_words );
		}

    if ( ! count( $errors ) ) {
	
			// create new testimonial post
			if ( $testimonial_id = wp_insert_post( $testimonial_post ) ) {

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
				return '<div class="testimonial-success">' .  __( 'Thank you! Your testimonial is awaiting moderation.', WPMTST_NAME ) .'</div>';

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
	$html .= '<p class="required-notice"><span class="required symbol"></span>' . __( 'Required Field', WPMTST_NAME ) . '</p>';
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
		$html .= '<label for="wpmtst_' . $field['name'] . '">' . __( $field['label'], WPMTST_NAME ) . '</label>';

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

	if ( $captcha ) {
		// Only display Captcha label if properly configured.
		// do_action( 'wpmtst_captcha', $captcha );
		$captcha_html = apply_filters( 'wpmtst_captcha', $captcha );
		if ( $captcha_html ) {
			$html .= '<div class="wpmtst-captcha">';
			$html .= '<label for="wpmtst_captcha">' . __( 'Captcha', WPMTST_NAME ) . '</label><span class="required symbol"></span>';
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
				.' value="' . __( 'Add Testimonial', WPMTST_NAME ) . '"'
				.' class="button" validate="required:true" />';
	$html .= '</p>';
	
	$html .= '</form>';
	$html .= '</div><!-- wpmtst-form -->';

	return $html;
}
add_shortcode( 'wpmtst-form', 'wpmtst_form_shortcode' );


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
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#wpmtst-submission-form").validate({});
		});
	</script>
	<?php
}


/*
 * Pagination on "All Testimonials" shortcode.
 */
function wpmtst_pagination_function() {
	// $per_page = get_option( 'wpmtst_options' )['per_page']; // only PHP 5.3+ ?
	$options  = get_option( 'wpmtst_options' );
	$per_page = $options['per_page'] ? $options['per_page'] : 5;
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#wpmtst-container").quickPager({ pageSize: <?php echo $per_page; ?>, currentPage: 1, pagerLocation: "after" });
		});
	</script>
	<?php
}


/*
 * Notify admin upon testimonial submission
 */
function wpmtst_notify_admin() {
	$options = get_option( 'wpmtst_options' );
	$admin_notify = $options['admin_notify'];
	$admin_email  = $options['admin_email'];

	if ( $admin_notify && $admin_email ) {
		$subject = 'New testimonial for ' . get_option( 'blogname' );
		$headers = 'From: noreply@' . preg_replace( '/^www\./', '', $_SERVER['HTTP_HOST'] );
		$message = 'New testimonial submission for ' . get_option( 'blogname' ) . '. This is awaiting action from the website administrator.';
		// More info here? A copy of testimonial? A link to admin page? A link to approve directly from email?
		wp_mail( $admin_email, $subject, $message, $headers );
	}
}
