<?php

/**
 * Print the star rating form.
 *
 * @since 2.12.0
 * @since 2.23.2 $field_array
 *
 * @param array|string $field
 * @param int $value
 * @param string $wrapper_class
 * @param bool $should_echo
 * @param string $field_array If included, set field name in array. In post editor meta box.
 *
 * @return string
 */
function wpmtst_star_rating_form( $field, $value, $wrapper_class, $should_echo = true, $field_array = '' ) {
	$value = (int) $value;
	if ( $field && is_array( $field ) && isset( $field['name'] ) ) {
		$name = $field['name'];
		if ( $field_array ) {
			$name = $field_array . '[' . $name . ']';
		}
	} else {
		$name = 'rating';
	}
	$star_solid   = wpmtst_get_star_svg( 'star_solid' );
	$star_regular = wpmtst_get_star_svg( 'star_regular' );

	$star_regular = '<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-12 -12 590 526">
						<path class="star_regular" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"></path>
						<path class="star_solid" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
					</svg>';
	$random       = wp_rand( 1, 9999 );
	$svg_args     = array(
		'svg'   => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true, // <= Must be lower case!
			'id'              => true,
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array(
			'd'     => true,
			'fill'  => true,
			'class' => true,
		),
		'style' => array( 'type' => true ),
	);
	ob_start(); ?>
	<div class="strong-rating-wrapper field-wrap <?php echo esc_attr( $wrapper_class ); ?>"><!-- cheap trick to collapse whitespace around inline-blocks
		--><fieldset contenteditable=false
					id="wpmtst_<?php echo esc_attr( $field['name'] ); ?>"
					name="<?php echo esc_attr( $field['name'] ); ?>"
					class="strong-rating"
					data-field-type="rating"
					tabindex="0">
						<legend><?php esc_html_e( 'rating fields', 'strong-testimonials' ); ?></legend><!--

			--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star0-<?php echo absint( $random ); ?>" name="<?php echo esc_attr( $name ); ?>" value="0" <?php checked( $value, 0 ); ?> /><!--
			--><label for="<?php echo esc_attr( $field['name'] ); ?>-star0-<?php echo absint( $random ); ?>" title="No stars"></label><!--

			--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star1-<?php echo absint( $random ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value, 1 ); ?> /><!--
			--><label for="<?php echo esc_attr( $field['name'] ); ?>-star1-<?php echo absint( $random ); ?>" class="star" title="1 star"><?php echo wp_kses( $star_regular, $svg_args ); ?></label><!--

			--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star2-<?php echo absint( $random ); ?>" name="<?php echo esc_attr( $name ); ?>" value="2" <?php checked( $value, 2 ); ?> /><!--
			--><label for="<?php echo esc_attr( $field['name'] ); ?>-star2-<?php echo absint( $random ); ?>" class="star" title="2 stars"><?php echo wp_kses( $star_regular, $svg_args ); ?></label><!--

			--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star3-<?php echo absint( $random ); ?>" name="<?php echo esc_attr( $name ); ?>" value="3" <?php checked( $value, 3 ); ?> /><!--
			--><label for="<?php echo esc_attr( $field['name'] ); ?>-star3-<?php echo absint( $random ); ?>" class="star" title="3 stars"><?php echo wp_kses( $star_regular, $svg_args ); ?></label><!--

			--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star4-<?php echo absint( $random ); ?>" name="<?php echo esc_attr( $name ); ?>" value="4" <?php checked( $value, 4 ); ?> /><!--
			--><label for="<?php echo esc_attr( $field['name'] ); ?>-star4-<?php echo absint( $random ); ?>" class="star" title="4 stars"><?php echo wp_kses( $star_regular, $svg_args ); ?></label><!--

			--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star5-<?php echo absint( $random ); ?>" name="<?php echo esc_attr( $name ); ?>" value="5" <?php checked( $value, 5 ); ?> /><!--
			--><label for="<?php echo esc_attr( $field['name'] ); ?>-star5-<?php echo absint( $random ); ?>" class="star" title="5 stars"><?php echo wp_kses( $star_regular, $svg_args ); ?></label><!--

		--></fieldset><!--
	--></div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );

	if ( $should_echo ) {
		echo $html;
		return true;
	}

	return $html;
}

/**
 * @param int $value
 * @param $wrapper_class
 * @param bool $should_echo
 *
 * @return bool|string
 */
function wpmtst_star_rating_display( $value = 0, $wrapper_class = 'in-view', $should_echo = true ) {
	$value        = (int) $value;
	$star_solid   = wpmtst_get_star_svg( 'star_solid' );
	$star_regular = wpmtst_get_star_svg( 'star_regular' );
	$svg_args     = array(
		'svg'   => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true, // <= Must be lower case!
			'id'              => true,
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array(
			'd'    => true,
			'fill' => true,
		),
		'style' => array( 'type' => true ),
		'span'  => array(
			'style' => array(),
			'class' => array(),
		),
	);

	ob_start();
	?>
	<span class="strong-rating-wrapper <?php echo esc_attr( $wrapper_class ); ?>">
		<span class="strong-rating"><!-- cheap trick to collapse whitespace around inline-blocks
			--><span class="star" style="display: none;"></span><!--
			--><span class="star" style="display: inline-block;"><?php echo ( 1 <= $value ) ? wp_kses( $star_solid, $svg_args ) : wp_kses( $star_regular, $svg_args ); ?></span><!--
			--><span class="star" style="display: inline-block;"><?php echo ( 2 <= $value ) ? wp_kses( $star_solid, $svg_args ) : wp_kses( $star_regular, $svg_args ); ?></span><!--
			--><span class="star" style="display: inline-block;"><?php echo ( 3 <= $value ) ? wp_kses( $star_solid, $svg_args ) : wp_kses( $star_regular, $svg_args ); ?></span><!--
			--><span class="star" style="display: inline-block;"><?php echo ( 4 <= $value ) ? wp_kses( $star_solid, $svg_args ) : wp_kses( $star_regular, $svg_args ); ?></span><!--
			--><span class="star" style="display: inline-block;"><?php echo ( 5 <= $value ) ? wp_kses( $star_solid, $svg_args ) : wp_kses( $star_regular, $svg_args ); ?></span><!--
		--></span>
	</span>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );

	if ( $should_echo ) {
		echo $html;
		return true;
	}

	return $html;
}
