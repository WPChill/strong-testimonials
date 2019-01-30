<th>
	<label for="view-class">
		<?php esc_html_e( 'CSS Classes', 'strong-testimonials' ); ?>
	</label>
</th>
<td colspan="2">
	<div class="then then_display then_form then_slideshow input" style="display: none;">
		<input type="text" id="view-class" class="long inline" name="view[data][class]" value="<?php echo esc_attr( $view['class'] ); ?>">
		<p class="inline description tall">
			<?php esc_html_e( 'For advanced users.', 'strong-testimonials' ); ?>
			<?php esc_html_e( 'Separate class names by spaces.', 'strong-testimonials' ); ?>
		</p>
	</div>
</td>
