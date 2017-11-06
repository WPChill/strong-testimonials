<?php
/**
 * General
 */
?>
<div class="row">
	<div>
		<label for="page-loading-general">
			<input type="radio" id="page-loading-general" name="wpmtst_compat_options[page_loading]"
			       value="general" <?php checked( $options['page_loading'], 'general' ); ?>/>
			<?php _e( 'General', 'strong-testimonials' ); ?>
		</label>
	</div>
	<div>
		<p class="about"><?php _e( 'Be ready to render any view at any time.', 'strong-testimonials' ); ?></p>
		<p class="about"><?php _e( 'This works well with common Ajax methods.', 'strong-testimonials' ); ?></p>
	</div>
</div>
