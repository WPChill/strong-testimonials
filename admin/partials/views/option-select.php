<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-single_or_multiple"><?php echo esc_html_x( 'Select', 'verb', 'strong-testimonials' ); ?></label>
</th>
<td>

	<div class="row">
		<div class="row-inner">
			<select id="view-single_or_multiple" class="if selectper" name="view[data][select]">
				<option value="multiple" <?php echo (int) $view['id'] == 0 ? 'selected' : ''; ?>><?php esc_html_e( 'one or more testimonials', 'strong-testimonials' ); ?></option>
				<option value="single" <?php echo (int) $view['id'] >= 1 ? 'selected' : ''; ?>><?php esc_html_e( 'a specific testimonial', 'strong-testimonials' ); ?></option>
			</select>
		</div>
	</div>

	<div class="row">
		<?php require 'option-id.php'; ?>
	</div>

</td>
<td class="divider">
	<p><?php echo wp_kses_post( _e( '<code>post_ids</code>', 'strong-testimonials' ) ); ?></p>
</td>
<td>
	<p><?php echo wp_kses_post( _e( 'a comma-separated list of post ID\'s', 'strong-testimonials' ) ); ?></p>
</td>
<td>
	<p><?php echo wp_kses_post( _e( '<code>post_ids="123,456"</code>', 'strong-testimonials' ) ); ?></p>
</td>
