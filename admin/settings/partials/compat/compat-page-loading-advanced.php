<?php
/**
 * Advanced
 */
?>
<div class="row">
	<div>
		<label for="page-loading-advanced">
			<input type="radio" id="page-loading-advanced" name="wpmtst_compat_options[page_loading]"
			       value="advanced" <?php checked( $options['page_loading'], 'advanced' ); ?>
                   data-group="advanced"/>
			<?php _e( 'Advanced', 'strong-testimonials' ); ?>
		</label>
	</div>
	<div>
		<p class="about"><?php _e( 'For specific configurations.', 'strong-testimonials' ); ?></p>
	</div>
</div>
