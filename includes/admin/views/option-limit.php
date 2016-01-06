<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-all">
		<?php _e( 'Quantity', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row">
		<select id="view-all" name="view[data][all]" class="if select">
			<option value="1" <?php selected( $view['all'] ); ?>>
				<?php _e( 'all', 'strong-testimonials' ); ?>
			</option>
			<option value="0" class="trip" <?php selected( $view['all'], 0 ); ?>>
				<?php _ex( 'count', 'noun', 'strong-testimonials' ); ?>
			</option>
		</select>
		&nbsp;
		<label>
			<input id="view-count" class="input-incremental then_all" type="number" min="1" name="view[data][count]" value="<?php echo $view['count']; ?>" size="5" style="display: none;">
		</label>
	</div>
</td>
