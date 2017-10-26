<?php
/**
 * None
 */
?>
<div class="row">
	<div>
		<label for="method-none">
			<input type="radio" id="method-none" name="wpmtst_compat_options[ajax][method]" value=""
					<?php checked( $options['ajax']['method'], '' ); ?> />
			<?php _e( 'None', 'strong-testimonials' ); ?> <em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
		</label>
	</div>
	<div>
		<span class="about"><?php _e( 'about this option', 'strong-testimonials' ); ?></span>
	</div>
</div>
