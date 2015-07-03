<?php /* translators: On the Views admin screen. */ ?>
<div class="then then_display then_not_form then_slideshow" style="display: none;">
	
	<div class="then then_display then_not_form then_slideshow" style="display: none;">
		<h3><?php _e( 'Select', 'strong-testimonials' ); ?></h3>
	</div>
	
	<table class="form-table multiple group-select" cellpadding="0" cellspacing="0">
		<tr style="display: none;" class="then then_display then_not_slideshow then_not_form pair-top">
			<th rowspan="2">
				<label for="view-single_or_multiple"><?php _ex( 'Show', 'verb', 'strong-testimonials' ); ?></label>
			</th>
			<td class="w1">
				<select id="view-single_or_multiple" class="if selectper" name="view[data][select]" autocomplete="off">
					<option value="multiple" <?php echo (int) $view['id'] == 0 ? 'selected' : ''; ?>>multiple testimonials</option>
					<option value="single" <?php echo (int) $view['id'] >=1 ? 'selected' : ''; ?>>a single testimonial</option>
				</select>
			</td>
			<td></td>
		</tr>
		<tr style="display: none;" class="then then_display then_not_slideshow then_not_form pair-bottom">
			<?php include( 'option-id.php' ); ?>
		</tr>
		<tr style="display: none; " class="then then_slideshow then_not_single then_multiple">
			<?php include( 'option-category.php' ); ?>
		</tr>
		<tr style="display: none;" class="then then_slideshow then_not_single then_multiple">
			<?php include( 'option-order.php' ); ?>
		</tr>
		<tr style="display: none;" class="then then_slideshow then_not_single then_multiple">
			<?php include( 'option-limit.php' ); ?>
		</tr>
	</table>
</div>
