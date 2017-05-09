<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-single_or_multiple"><?php _ex( 'Show', 'verb', 'strong-testimonials' ); ?></label>
</th>
<td>

	<div class="row">
		<div class="row-inner">
			<select id="view-single_or_multiple" class="if selectper" name="view[data][select]">
				<option value="multiple" <?php echo (int) $view['id'] == 0 ? 'selected' : ''; ?>><?php _e( 'one or more testimonials', 'strong-testimonials' ); ?></option>
				<option value="single" <?php echo (int) $view['id'] >= 1 ? 'selected' : ''; ?>><?php _e( 'a specific testimonial', 'strong-testimonials' ); ?></option>
			</select>
		</div>
	</div>

	<div class="row">
		<?php include( 'option-id.php' ); ?>
	</div>

</td>
