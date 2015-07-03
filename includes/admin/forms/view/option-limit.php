<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-count"><?php _ex( 'Quantity', 'strong-testimonials' ); ?></label>
</th>
<td colspan="2">
	<select id="view-count" name="view[data][all]" class="if select" autocomplete="off">
		<option value="1" <?php selected( $view['all'] ); ?>>
			<?php _e( 'All', 'strong-testimonials' ); ?>
		</option>
		<option class="trip" value="0" <?php selected( ! $view['all'] ); ?>>
			<?php _ex( 'Count', 'noun', 'strong-testimonials' ); ?>
		</option>
	</select>
	&nbsp;
	<label>
		<input id="view-count" class="input-incremental then_count" type="number" min="1" name="view[data][count]" value="<?php echo $view['count']; ?>" size="5" style="display: none;">
	</label>
</td>
