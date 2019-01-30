<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-option">
		<?php esc_html_e( 'Option Name', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="inline">
		<select id="view-option" class="if selectper" name="view[data][option]">
			<option value="yes" <?php selected( 'yes', $view['option'] ); ?>>
				<?php esc_html_e( 'yes', 'strong-testimonials' ); ?>
			</option>
			<option value="no" <?php selected( 'no', $view['option'] ); ?>>
				<?php esc_html_e( 'no', 'strong-testimonials' ); ?>
			</option>
			<option value="maybe" <?php selected( 'maybe', $view['option'] ); ?>>
				<?php esc_html_e( 'maybe', 'strong-testimonials' ); ?>
			</option>
		</select>
	</div>

	<div class="inline then fast then_yes then_not_no then_not_maybe" style="display: none;">
		<p class="description tall">
			<?php esc_html_e( 'Yes', 'strong-testimonials' ); ?><br>
		</p>
	</div>

	<div class="inline then fast then_not_yes then_no then_not_maybe" style="display: none;">
		<p class="description tall">
			<?php esc_html_e( 'No', 'strong-testimonials' ); ?><br>
		</p>
	</div>

	<div class="inline then fast then_not_yes then_not_no then_maybe" style="display: none;">
		<p class="description tall">
			<?php esc_html_e( 'Maybe', 'strong-testimonials' ); ?><br>
		</p>
	</div>
</td>
