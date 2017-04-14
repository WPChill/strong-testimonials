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
	echo '<input type="hidden" name="form_id" value="'. WPMST()->atts( 'form_id' ) .'">'."\n";

	echo '<input type="hidden" name="default_category" value="'. WPMST()->atts( 'category' ) .'">'."\n";

    $cats = (array) $form_values['category'];
	echo '<input type="hidden" name="category" value="'. implode( ',', $cats ) .'">'."\n";

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

	echo '<div class="form-field field-'.$field['name'].'">';

	if ( ! isset( $field['show_label'] ) || $field['show_label'] ) {
		$label = '<label for="wpmtst_' . $field['name'] . '">' . apply_filters( 'wpmtst_l10n', $field['label'], 'strong-testimonials-form-fields', $field['name'] . ' : label' ) . '</label>';
		echo $label;
	}

	wpmtst_field_required_symbol( $field );
	wpmtst_field_before( $field );

	switch ( $field['input_type'] ) {

		case 'category-selector' :

			$value = isset( $form_values[ $field['name'] ] ) ? (array) $form_values[ $field['name'] ] : array();

			$category_list = wpmtst_get_category_list();

			echo '<select id="wpmtst_' . $field['name']. '"'
				. ' name="' . $field['name'] . '"'
				. ' class="' . wpmtst_field_classes( $field['input_type'], $field['name'] ) . '"'
				. wpmtst_field_required_tag( $field ) . '>';
			echo '<option value="">&mdash;</option>';
			foreach ( $category_list as $category ) {
			    $selected = in_array( $category->term_id, $value ) ? ' selected' : '' ;
				echo '<option value="' . $category->term_id . '"' . $selected . '>' . $category->name . '</option>';
			}
			echo '</select>';

			break;

		case 'category-checklist' :

			$value = isset( $form_values[ $field['name'] ] ) ? (array) $form_values[ $field['name'] ] : array();
			wpmtst_form_category_checklist_frontend( $value );

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
			wpmtst_star_rating_form( $field, $field['default_form_value'], 'in-form' );
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
 * @since 2.19.1 wpmtst_field_value filter
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

	$value = apply_filters( 'wpmtst_field_value', $value, $field, $form_values );

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

/**
 * Print the submit button.
 *
 * @param bool $preview
 */
function wpmtst_form_submit_button( $preview = false ) {
	$form_options = get_option( 'wpmtst_form_options' );
	$messages     = $form_options['messages'];
	$string       = $messages['form-submit-button']['text'];
	$context      = 'strong-testimonials-form-messages';
	$name         = $messages['form-submit-button']['description'];
	$type         = $preview ? 'button' : 'submit';
	?>
	<div class="form-field submit">
		<label><input type="<?php echo $type; ?>" id="wpmtst_submit_testimonial" name="wpmtst_submit_testimonial" value="<?php echo esc_attr( apply_filters( 'wpmtst_l10n', $string, $context, $name ) ); ?>" class="button"></label>
	</div>
	<?php
	// validate="required:true"
}

/**
 * Print a category checklist.
 *
 * @since 2.17.0
 * @param array $default_cats
 */
function wpmtst_form_category_checklist_frontend( $default_cats = array() ) {
	?>
    <div class="strong-category-list-panel">
        <ul class="strong-category-list">
			<?php $args = array(
				'selected_cats' => $default_cats,
				'checked_ontop' => false,
			); ?>
			<?php wpmtst_terms_checklist( $args ); ?>
        </ul>
    </div>
	<?php
}

/**
 * Output an unordered list of checkbox input elements labelled with term names.
 *
 * Copied wp_terms_checklist().
 *
 * @since 2.16.4
 *
 * @param array|string $args {
 *     Optional. Array or string of arguments for generating a terms checklist. Default empty array.
 *
 *     @type int    $descendants_and_self ID of the category to output along with its descendants.
 *                                        Default 0.
 *     @type array  $selected_cats        List of categories to mark as checked. Default false.
 *     @type array  $popular_cats         List of categories to receive the "popular-category" class.
 *                                        Default false.
 *     @type object $walker               Walker object to use to build the output.
 *                                        Default is a Walker_Strong_Category_Checklist_Front instance.
 *     @type string $taxonomy             Taxonomy to generate the checklist for. Default 'wpm-testimonial-category'.
 *     @type bool   $checked_ontop        Whether to move checked items out of the hierarchy and to
 *                                        the top of the list. Default true.
 *     @type bool   $echo                 Whether to echo the generated markup. False to return the markup instead
 *                                        of echoing it. Default true.
 * }
 *
 * @return string
 */
function wpmtst_terms_checklist( $args = array() ) {
	$defaults = array(
		'descendants_and_self' => 0,
		'selected_cats'        => false,
		'popular_cats'         => false,
		'walker'               => null,
		'taxonomy'             => 'wpm-testimonial-category',
		'checked_ontop'        => true,
		'echo'                 => true,
	);

	$params = apply_filters( 'wpmtst_terms_checklist_args', $args );

	$r = wp_parse_args( $params, $defaults );

	if ( empty( $r['walker'] ) || ! ( $r['walker'] instanceof Walker ) ) {
		$walker = new Walker_Strong_Category_Checklist_Front;
	} else {
		$walker = $r['walker'];
	}

	$taxonomy = $r['taxonomy'];
	$descendants_and_self = (int) $r['descendants_and_self'];

	$args = array( 'taxonomy' => $taxonomy );

	if ( is_array( $r['selected_cats'] ) ) {
		$args['selected_cats'] = $r['selected_cats'];
	} else {
		$args['selected_cats'] = array();
	}

	if ( is_array( $r['popular_cats'] ) ) {
		$args['popular_cats'] = $r['popular_cats'];
	} else {
		$args['popular_cats'] = get_terms( $taxonomy, array(
			'fields'       => 'ids',
			'orderby'      => 'count',
			'order'        => 'DESC',
			'number'       => 10,
			'hierarchical' => false,
		) );
	}

	// Select a _single_ sibling and its descendants.
	// Assembling a list of _multiple_ siblings would go here.
	if ( $descendants_and_self ) {
		$categories = (array) get_terms( $taxonomy, array(
			'child_of'     => $descendants_and_self,
			'hierarchical' => 0,
			'hide_empty'   => 0,
		) );
		$self = get_term( $descendants_and_self, $taxonomy );
		array_unshift( $categories, $self );
	} else {
		$categories = (array) get_terms( $taxonomy, array( 'get' => 'all' ) );
	}

	$output = '';

	if ( $r['checked_ontop'] ) {
		// Post-process $categories rather than adding an exclude to the get_terms() query
        // to keep the query the same across all posts (for any query cache)
		$checked_categories = array();
		$keys = array_keys( $categories );

		foreach ( $keys as $k ) {
			if ( in_array( $categories[$k]->term_id, $args['selected_cats'] ) ) {
				$checked_categories[] = $categories[$k];
				unset( $categories[$k] );
			}
		}

		// Put checked cats on top
		$output .= call_user_func_array( array( $walker, 'walk' ), array( $checked_categories, 0, $args ) );
	}
	// Then the rest of them
	$output .= call_user_func_array( array( $walker, 'walk' ), array( $categories, 0, $args ) );

	if ( $r['echo'] ) {
		echo $output;
	}

	return $output;
}
