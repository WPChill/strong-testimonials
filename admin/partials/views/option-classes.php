<th>
	<label for="view-class">
		<?php _e( 'CSS Class Names', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="then then_display then_form then_slideshow input" style="display: none;">
		<input type="text" id="view-class" class="long inline" name="view[data][class]" value="<?php echo $view['class']; ?>">
		<p class="inline description tall">
			<?php _e( 'For advanced users. Separate class names by spaces.', 'strong-testimonials' ); ?>
			<?php printf( '<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://support.strongplugins.com/article/custom-css-strong-testimonials/' ),
				__( 'Tutorial', 'strong-testimonials' ) ); ?>
		</p>
	</div>
</td>
