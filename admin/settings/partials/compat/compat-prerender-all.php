<?php
/**
 * All
 */
?>
<div class="row">
	<div>
		<label for="prerender-all">
			<input type="radio" id="prerender-all" name="wpmtst_compat_options[prerender]"
			       value="all" <?php checked( $options['prerender'], 'all' ); ?>/>
			<?php _e( 'All views', 'strong-testimonials' ); ?>
		</label>
	</div>
	<div>
		<p class="about"><?php _e( 'Load resources for all views. Required for Ajax page loading.', 'strong-testimonials' ); ?></p>
		<p class="description"><?php _e( 'Then select an option for <strong>Ajax Page Loading</strong> below.', 'strong-testimonials' ); ?></p>
	</div>
</div>
