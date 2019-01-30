<?php /* translators: In the view editor. */ ?>
<div class="row">

	<div class="inline inline-middle">
		<label>
			<select id="view-slider_type" name="view[data][slideshow_settings][type]" class="if selectgroup">
				<option value="show_single" <?php selected( $view['slideshow_settings']['type'], 'show_single' ); ?>><?php esc_html_e( 'single', 'strong-testimonials' ); ?></option>
				<option value="show_multiple" <?php selected( $view['slideshow_settings']['type'], 'show_multiple' ); ?>><?php esc_html_e( 'multiple', 'strong-testimonials' ); ?></option>
			</select>
		</label>
		<div class="option-desc singular" style="display: none;">
			<?php esc_html_e( 'slide at a time', 'strong-testimonials' ); ?>
		</div>
		<div class="option-desc plural" style="display: none;">
			<?php esc_html_e( 'slides at a time with these responsive breakpoints:', 'strong-testimonials' ); ?>
		</div>
	</div>

</div>
