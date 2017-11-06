<?php
/**
 * None
 */
?>
<div class="row">
	<div>
		<label for="prerender-none">
			<input type="radio" id="prerender-none" name="wpmtst_compat_options[prerender]"
			       value="none" <?php checked( $options['prerender'], 'none' ); ?>/>
			<?php _e( 'None', 'strong-testimonials' ); ?>
		</label>
	</div>
	<div>
		<p class="about"><?php _e( 'When the shortcode is rendered. May result in a flash of unstyled content.', 'strong-testimonials' ); ?></p>
	</div>
</div>
