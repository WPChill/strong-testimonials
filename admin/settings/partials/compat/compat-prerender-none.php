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
		<p class="about"><?php _e( 'Load resources when the shortcode is rendered. May result in a flash of unstyled content.', 'strong-testimonials' ); ?></p>
		<p class="description"><?php _e( 'Try this if the other options don\'t help.', 'strong-testimonials' ); ?></p>
	</div>
</div>
