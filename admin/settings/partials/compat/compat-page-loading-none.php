<?php
/**
 * None (default)
 */
?>
<div class="row">
	<div>
		<label for="page-loading-none">
			<input type="radio" id="page-loading-none" name="wpmtst_compat_options[page_loading]"
			       value="" <?php checked( $options['page_loading'], '' ); ?>/>
			<?php _e( 'None', 'strong-testimonials' ); ?> <em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
		</label>
	</div>
	<div>
		<p class="about"><?php _e( 'No compatibility needed.', 'strong-testimonials' ); ?></p>
		<p class="about"><?php _e( 'This works well for most themes.', 'strong-testimonials' ); ?></p>
	</div>
</div>
