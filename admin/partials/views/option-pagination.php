<?php /* translators: On the Views admin screen. */ ?>
<th>
	<input type="checkbox" id="view-pagination" class="if toggle" name="view[data][pagination]" value="1" <?php checked( $view['pagination'] ); ?> class="checkbox">
	<label for="view-pagination">
		<?php _e( 'Pagination', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row then then_pagination" style="display: none;">

		<div class="inline">
			<label for="view-per_page">
				<?php _ex( 'Per page', 'quantity', 'strong-testimonials' ); ?>
			</label>
			<input id="view-per_page" class="input-incremental" type="number" min="1" name="view[data][per_page]" value="<?php echo $view['per_page']; ?>" size="3">
		</div>

		<div class="inline">
			<label for="view-nav">
				<?php _e( 'Navigation', 'strong-testimonials' ); ?>
			</label>
			<select id="view-nav" name="view[data][nav]">
				<option value="before" <?php selected( in_array( 'before', $view['nav'] ) ); ?>>
					<?php _e( 'before', 'strong-testimonials' ); ?>
				</option>
				<option value="after" <?php selected( in_array( 'after', $view['nav'] ) ); ?>>
					<?php _e( 'after', 'strong-testimonials' ); ?>
				</option>
				<option value="before,after" <?php selected( in_array( 'before', $view['nav'] ) && in_array( 'after', $view['nav'] ) ); ?>>
					<?php _e( 'before & after', 'strong-testimonials' ); ?>
				</option>
			</select>
		</div>

		<?php do_action( 'wpmtst_view_editor_pagination_row_end' ); ?>

	</div>
</td>
