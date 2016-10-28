<th>
	<input type="checkbox" id="view-stretch" name="view[data][stretch]" value="1" <?php checked( $view['stretch'] ); ?> class="checkbox">
	<label for="view-stretch">
		<?php _e( 'Stretch Vertically', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<p class="tall">
		<?php _e( 'Stretch each slide to fill the vertical space of the slideshow container.', 'strong-testimonials' ); ?>
	</p>
	<br />
	<p class="description">
		<?php _e( 'The height of the <b>slideshow container</b> is set to match the tallest slide in order to keep elements below it from bouncing up and down during slide transitions. With testimonials of uneven length, the result is whitespace underneath the shorter testimonials. This setting merely stretches the borders and background vertically to compensate. Use the excerpt or abbreviated content if you want to minimize the whitespace.', 'strong-testimonials' ); ?>
	</p>
</td>
