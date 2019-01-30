<?php /* translators: On the Views admin screen. */ ?>
<th>
	<input type="checkbox" id="view-form_ajax" class="checkbox if toggle" name="view[data][form_ajax]" value="1" <?php checked( $view['form_ajax'] ); ?>>
	<label for="view-form_ajax">
		<?php esc_html_e( 'Submit form without reloading the page (Ajax)', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<p class="description tall">
		<?php esc_html_e( 'This will override the Success Redirect setting.', 'strong-testimonials' ); ?>
	</p>
</td>
