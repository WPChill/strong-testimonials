<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-all">
		<?php esc_html_e( 'Quantity', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row">
		<div class="inline">
			<select class="if select" id="view-all" name="view[data][all]">
				<option value="1" <?php selected( -1, $view['count'] ); ?>>
					<?php esc_html_e( 'all', 'strong-testimonials' ); ?>
				</option>
				<option class="trip" value="0" <?php selected( $view['count'] > 0 ); ?>>
					<?php echo esc_html_x( 'count', 'noun', 'strong-testimonials' ); ?>
				</option>
			</select>
			&nbsp;
			<label>
				<input class="input-incremental then_all" type="number" id="view-count" name="view[data][count]" value="<?php echo ( -1 == $view['count'] ) ? 1 : esc_attr( $view['count'] ); ?>" min="1" size="5" style="display: none;">
			</label>
		</div>
	</div>
</td>
<td class="divider">
	<p><?php echo wp_kses_post( _e( '<code>count</code>', 'strong-testimonials' ) ); ?></p>
</td>
<td></td>
<td>
	<p><?php echo wp_kses_post( _e( '<code>count=5</code>', 'strong-testimonials' ) ); ?></p>
</td>
