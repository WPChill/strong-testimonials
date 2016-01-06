<th>
	<input type="checkbox" id="view-title" name="view[data][title]" value="1" <?php checked( $view['title'] ); ?> class="checkbox" <?php disabled( $has_title_field, false ); ?>>
	<label for="view-title">
		<?php _e( 'Title', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<p class="description tall">
		<?php if ( !$has_title_field ) _e( 'not found in Fields', 'strong-testimonials' ); ?>
	</p>
</td>
