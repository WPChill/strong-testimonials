<?php
/**
 * FORM TEMPLATE FUNCTIONS
 */

function wpmtst_form_info() {
	echo 'id="wpmtst-submission-form" method="post" enctype="multipart/form-data" autocomplete="off"';
}

function wpmtst_form_setup() {
	$form_values = WPMST()->get_form_values();
	wp_nonce_field( 'wpmtst_form_action', 'wpmtst_form_nonce', true, true );
	echo '<input type="hidden" name="action" value="wpmtst_form">'."\n";
	echo '<input type="hidden" name="category" value="'. $form_values['category'] .'">'."\n";
	echo '<input type="hidden" name="form_id" value="'. WPMST()->atts( 'form_id' ) .'">'."\n";
}

function wpmtst_form_message( $part ) {
	echo wpmtst_get_form_message( $part );
}

function wpmtst_get_form_message( $part ) {
	$form_options = get_option( 'wpmtst_form_options' );
	$messages = $form_options['messages'];
	if ( isset( $messages[$part]['text'] ) ) {
		return apply_filters( 'wpmtst_l10n', $messages[$part]['text'], 'strong-testimonials-form-messages', $messages[$part]['description'] );
	}
}

function wpmtst_all_form_fields( $fields = null ) {
	if ( ! $fields ) {
		$fields = wpmtst_get_form_fields( WPMST()->atts( 'form_id' ) );
	}

	foreach ( $fields as $key => $field ) {
		wpmtst_single_form_field( $field );
	}
}

function wpmtst_form_field( $field_name ) {
	$fields = wpmtst_get_form_fields( WPMST()->atts( 'form_id' ) );

	foreach ( $fields as $key => $field ) {
		if ( $field['name'] == $field_name ) {
			wpmtst_single_form_field( $field );
		}
	}
}

function wpmtst_single_form_field( $field ) {
	$form_values = WPMST()->get_form_values();

	echo '<div class="form-field">';

	if ( ! isset( $field['show_label'] ) || $field['show_label'] ) {
		$label = '<label for="wpmtst_' . $field['name'] . '">' . apply_filters( 'wpmtst_l10n', $field['label'], 'strong-testimonials-form-fields', $field['name'] . ' : label' ) . '</label>';
		echo $label;
	}

	wpmtst_field_required_symbol( $field );
	wpmtst_field_before( $field );

	switch ( $field['input_type'] ) {

		case 'categories' :

			$value = isset( $form_values[ $field['name'] ] ) ? $form_values[ $field['name'] ] : '';

			$category_list = wpmtst_get_category_list();

			echo '<select id="wpmtst_' . $field['name']. '"'
				. ' name="' . $field['name'] . '"'
				. ' class="' . wpmtst_field_classes( $field['input_type'], $field['name'] ) . '"'
				. wpmtst_field_required_tag( $field ) . '>';
			echo '<option value="">&mdash;</option>';
			foreach ( $category_list as $category ) {
				echo '<option value="' . $category->term_id . '" ' . selected( $category->term_id, $value ) . '>';
				echo $category->name;
				echo '</option>';
			}
			echo '</select>';
			break;

		case 'textarea' :

			$value = ( isset( $form_values[ $field['name'] ] ) && $form_values[ $field['name'] ] ) ? $form_values[ $field['name'] ] : '';

			// textarea tags must be on same line for placeholder to work
			echo '<textarea id="wpmtst_' . $field['name'] . '"'
			     . ' class="' . wpmtst_field_classes( $field['input_type'], $field['name'] ) . '"'
			     . ' name="' . $field['name'] . '"'
			     . wpmtst_field_required_tag( $field )
				 . wpmtst_field_placeholder( $field )
			     . '>' . esc_textarea( $value ) . '</textarea>';
			break;

		case 'file' :

			echo '<input id="wpmtst_' . $field['name'] . '" type="file" name="' . $field['name'] . '"' . wpmtst_field_required_tag( $field ) . '>';
			break;

		case 'shortcode' :
			if ( isset( $field['shortcode_on_form'] ) && $field['shortcode_on_form'] ) {
				echo do_shortcode( $field['shortcode_on_form'], true );
			}
			break;

		case 'rating' :
			wpmtst_star_rating_form( $field, 0, 'in-form' );
			break;

		default: // text, email, url
			echo '<input id="wpmtst_' . $field['name'] . '"'
			     . ' type="' . $field['input_type'] . '"'
			     . ' class="' . wpmtst_field_classes( $field['input_type'], $field['name'] ) . '"'
			     . ' name="' . $field['name'] . '"'
			     . wpmtst_field_value( $field, $form_values )
			     . wpmtst_field_placeholder( $field )
				 . wpmtst_field_required_tag( $field ) . '>';
	}

	wpmtst_field_error( $field );
	wpmtst_field_after( $field );
	echo '</div><!-- .form-field -->' . "\n";

}

function wpmtst_field_classes( $type = null, $name = null ) {
	$errors = WPMST()->get_form_errors();
	$class_list = array();

	switch( $type ) {
		case 'email':
			$class_list[] = 'text';
			$class_list[] = 'email';
			break;
		case 'url':
			$class_list[] = 'text';
			$class_list[] = 'url';
			break;
		case 'text':
			$class_list[] = 'text';
			break;
		default:
			break;
	}

	if ( isset( $errors[ $name ] ) ) {
		$class_list[] = 'error';
	}

	return apply_filters( 'wpmtst_form_field_class', implode( ' ', $class_list ), $type, $name );
}

/**
 * Display default value if no value submitted.
 *
 * @param $field
 * @param $form_values
 *
 * @return string
 */
function wpmtst_field_value( $field, $form_values ) {
	$value = '';
	if ( isset( $form_values[ $field['name'] ] ) && $form_values[ $field['name'] ] ) {
		$value = $form_values[ $field['name'] ];
	}
	elseif ( isset( $field['default_form_value'] ) && $field['default_form_value'] ) {
		$value = $field['default_form_value'];
	}

	return ' value="' . esc_attr( $value ) . '"';
}

function wpmtst_field_placeholder( $field ) {
	if ( isset( $field['placeholder'] ) && $field['placeholder'] ) {
		return ' placeholder="' . esc_attr( apply_filters( 'wpmtst_l10n', $field['placeholder'], 'strong-testimonials-form-fields', $field['name'] . ' : placeholder' ) ) . '"';
	}
}

/**
 * HTML tag: required
 *
 * @param $field
 * @return string
 */
function wpmtst_field_required_tag( $field ) {
	if ( isset( $field['required'] ) && apply_filters( 'wpmtst_field_required_tag', $field['required'] ) )
		return ' required';
}

function wpmtst_field_required_symbol( $field ) {
	if ( isset( $field['required'] ) && $field['required'] )
		echo '<span class="required symbol"></span>';
}

function wpmtst_field_before( $field ) {
	if ( isset( $field['before'] ) && $field['before'] ) {
		echo '<span class="before">' . esc_html( apply_filters( 'wpmtst_l10n', $field['before'], 'strong-testimonials-form-fields', $field['name'] . ' : before' ) ) . '</span>';
	}
}

function wpmtst_field_after( $field ) {
	if ( isset( $field['after'] ) && $field['after'] ) {
		echo '<span class="after">' . esc_html( apply_filters( 'wpmtst_l10n', $field['after'], 'strong-testimonials-form-fields', $field['name'] . ' : after' ) ) . '</span>';
	}
}

function wpmtst_field_error( $field ) {
	$errors = WPMST()->get_form_errors();
	if ( isset( $errors[ $field['name'] ] ) ) {
		echo '<span class="error">' . esc_html( $errors[ $field['name'] ] ) . '</span>';
	}
}

function wpmtst_form_honeypot_before() {
	$form_options = get_option( 'wpmtst_form_options' );
	if ( $form_options['honeypot_before'] ) {
		?>
		<style>#wpmtst-form .wpmtst_if_visitor * { display: none !important; visibility: hidden !important; }</style>
		<span class="wpmtst_if_visitor"><label for="wpmtst_if_visitor">Visitor?</label><input id="wpmtst_if_visitor" type="text" name="wpmtst_if_visitor" size="40" tabindex="-1" autocomplete="off"></span>
		<?php
	}
}
add_action( 'wpmtst_form_after_fields', 'wpmtst_form_honeypot_before' );

function wpmtst_form_captcha() {
	$errors = WPMST()->get_form_errors();
	$form_options = get_option( 'wpmtst_form_options' );
	if ( $form_options['captcha'] ) {
		// Only display Captcha label if properly configured.
		$captcha_html = apply_filters( 'wpmtst_captcha', $form_options['captcha'] );
		if ( $captcha_html ) {
			?>
			<div class="form-field wpmtst-captcha">
				<label for="wpmtst_captcha"><?php wpmtst_form_message('captcha'); ?></label><span class="required symbol"></span>
				<div>
					<?php echo $captcha_html; ?>
					<?php if ( isset( $errors['captcha'] ) ) : ?>
						<p><label class="error"><?php echo esc_html( $errors['captcha'] ); ?></label></p>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}

}
add_action( 'wpmtst_form_after_fields', 'wpmtst_form_captcha' );

function wpmtst_form_submit_button( $preview = false ) {
	$form_options = get_option( 'wpmtst_form_options' );
	$messages     = $form_options['messages'];

	$string  = $messages['form-submit-button']['text'];
	$context = 'strong-testimonials-form-messages';
	$name    = $messages['form-submit-button']['description'];

	$type = $preview ? 'button' : 'submit';
	?>
	<p class="form-field submit">
		<input type="<?php echo $type; ?>" id="wpmtst_submit_testimonial" name="wpmtst_submit_testimonial" value="<?php echo esc_attr( apply_filters( 'wpmtst_l10n', $string, $context, $name ) ); ?>" class="button">
	</p>
	<?php
	// validate="required:true"
}
