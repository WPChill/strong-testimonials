<?php
/**
 * Current (default)
 */
?>
<div class="row">
	<div>
		<label for="prerender-current">
			<input type="radio" id="prerender-current" name="wpmtst_compat_options[prerender]"
			       value="current" <?php checked( $options['prerender'], 'current' ); ?>/>
			<?php _e( 'Current page', 'strong-testimonials' ); ?> <em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
		</label>
	</div>
	<div>
		<p class="about"><?php _e( 'For the current page only.', 'strong-testimonials' ); ?></p>
		<p class="about"><?php _e( 'This works well for most themes.', 'strong-testimonials' ); ?></p>
	</div>
</div>
