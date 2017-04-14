<?php

/**
 * @param $field
 * @param int $value
 * @param $class
 * @param bool $echo
 *
 * @return mixed|string
 */
function wpmtst_star_rating_form( $field, $value = 0, $class, $echo = true ) {
    $value = (int) $value;
	if ( $field && is_array( $field ) && isset( $field['name'] ) ) {
		$name = $field['name'];
	} else {
		$name = 'rating';
	}
	ob_start(); ?>
	<div class="strong-rating-wrapper <?php echo $class; ?>"><!-- cheap trick to collapse whitespace around inline-blocks
		--><div id="wpmtst_<?php echo $field['name']; ?>" class="strong-rating"><!--
			--><input type="radio" id="<?php echo $field['name']; ?>-star0" name="<?php echo $name; ?>" value="0" <?php checked( $value, 0 ); ?> /><!--
			--><label for="<?php echo $field['name']; ?>-star0" title="No stars"></label><!--
			--><input type="radio" id="<?php echo $field['name']; ?>-star1" name="<?php echo $name; ?>" value="1" <?php checked( $value, 1 ); ?> /><!--
			--><label for="<?php echo $field['name']; ?>-star1" title="1 star"></label><!--
			--><input type="radio" id="<?php echo $field['name']; ?>-star2" name="<?php echo $name; ?>" value="2" <?php checked( $value, 2 ); ?> /><!--
			--><label for="<?php echo $field['name']; ?>-star2" title="2 stars"></label><!--
			--><input type="radio" id="<?php echo $field['name']; ?>-star3" name="<?php echo $name; ?>" value="3" <?php checked( $value, 3 ); ?> /><!--
			--><label for="<?php echo $field['name']; ?>-star3" title="3 stars"></label><!--
			--><input type="radio" id="<?php echo $field['name']; ?>-star4" name="<?php echo $name; ?>" value="4" <?php checked( $value, 4 ); ?> /><!--
			--><label for="<?php echo $field['name']; ?>-star4" title="4 stars"></label><!--
			--><input type="radio" id="<?php echo $field['name']; ?>-star5" name="<?php echo $name; ?>" value="5" <?php checked( $value, 5 ); ?> /><!--
			--><label for="<?php echo $field['name']; ?>-star5" title="5 stars"></label><!--
		--></div><!--
	--></div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );
	if ( !$echo ) {
		return $html;
	}
	echo $html;
}

/**
 * @param int $value
 * @param $class
 * @param bool $echo
 *
 * @return mixed|string
 */
function wpmtst_star_rating_display( $value = 0, $class, $echo = true ) {
    $value = (int) $value;
	ob_start(); ?>
	<span class="strong-rating-wrapper <?php echo $class; ?>">
		<span class="strong-rating"><!-- cheap trick to collapse whitespace around inline-blocks
			--><span class="star0 star <?php echo ( 0 == $value ) ? 'current' : '' ; ?>"></span><!--
			--><span class="star <?php echo ( 1 == $value ) ? 'current' : '' ; ?>"></span><!--
			--><span class="star <?php echo ( 2 == $value ) ? 'current' : '' ; ?>"></span><!--
			--><span class="star <?php echo ( 3 == $value ) ? 'current' : '' ; ?>"></span><!--
			--><span class="star <?php echo ( 4 == $value ) ? 'current' : '' ; ?>"></span><!--
			--><span class="star <?php echo ( 5 == $value ) ? 'current' : '' ; ?>"></span><!--
		--></span>
	</span>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );
	if ( !$echo ) {
		return $html;
	}
	echo $html;
}
