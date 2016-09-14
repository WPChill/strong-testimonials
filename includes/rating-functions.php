<?php

function wpmtst_star_rating_form( $field, $value = 0, $class, $echo = true ) {
	if ( $field && is_array( $field ) && isset( $field['name'] ) ) {
		$name = $field['name'];
	}
	else {
		$name = 'rating';
	}
	ob_start(); ?>
	<div class="strong-rating-wrapper"><!-- cheap trick to collapse whitespace around inline-blocks
		--><div class="strong-rating <?php echo $class; ?>"><!--

			--><input type="radio" id="star0" name="<?php echo $name; ?>" value="0" <?php checked( $value, 0 ); ?> /><!--
			--><label for="star0" title="No stars"></label><!--

			--><input type="radio" id="star1" name="<?php echo $name; ?>" value="1" <?php checked( $value, 1 ); ?> /><!--
			--><label for="star1" title="1 star"></label><!--

			--><input type="radio" id="star2" name="<?php echo $name; ?>" value="2" <?php checked( $value, 2 ); ?> /><!--
			--><label for="star2" title="2 stars"></label><!--

			--><input type="radio" id="star3" name="<?php echo $name; ?>" value="3" <?php checked( $value, 3 ); ?> /><!--
			--><label for="star3" title="3 stars"></label><!--

			--><input type="radio" id="star4" name="<?php echo $name; ?>" value="4" <?php checked( $value, 4 ); ?> /><!--
			--><label for="star4" title="4 stars"></label><!--

			--><input type="radio" id="star5" name="<?php echo $name; ?>" value="5" <?php checked( $value, 5 ); ?> /><!--
			--><label for="star5" title="5 stars"></label><!--

		--></div><!--
	--></div>
	<?php $html = ob_get_contents();
	ob_end_clean();
	if ( ! $echo ) {
		return $html;
	}
	echo $html;
}

function wpmtst_star_rating_display( $field, $value = 0, $class, $echo = true ) {
	ob_start(); ?>
	<div class="strong-rating-wrapper <?php echo $class; ?>">
		<div class="strong-rating"><!--
			cheap trick to collapse whitespace around inline-blocks
			--><div class="star star0 <?php echo ( 0 == $value ) ? 'current' : '' ; ?>"></div><!--
			--><div class="star star1 <?php echo ( 1 == $value ) ? 'current' : '' ; ?>"></div><!--
			--><div class="star star2 <?php echo ( 2 == $value ) ? 'current' : '' ; ?>"></div><!--
			--><div class="star star3 <?php echo ( 3 == $value ) ? 'current' : '' ; ?>"></div><!--
			--><div class="star star4 <?php echo ( 4 == $value ) ? 'current' : '' ; ?>"></div><!--
			--><div class="star star5 <?php echo ( 5 == $value ) ? 'current' : '' ; ?>"></div><!--
		--></div>
	</div>
	<?php $html = ob_get_contents();
	ob_end_clean();
	if ( ! $echo ) {
		return $html;
	}
	echo $html;
}
