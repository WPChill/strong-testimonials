<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label class="checkbox">
		<input type="checkbox" id="view-pagination" class="if toggle" name="view[data][pagination]" value="1" <?php checked( $view['pagination'] ); ?> class="checkbox">
		<?php _e( 'Pagination', 'strong-testimonials' ); ?>
	</label>
</th>
<td class="w1">
	<div class="inline checkbox then then_pagination" style="display: none;">
		<label for="view-per_page">
			<?php _ex( 'Per page', 'quantity', 'strong-testimonials' ); ?>
		</label>
		<input id="view-per_page" class="input-incremental" type="number" min="1" name="view[data][per_page]" value="<?php echo $view['per_page']; ?>" size="3">
	</div>
</td>
<td>
	<div class="inline checkbox then then_pagination" style="display: none;">
		<label for="view-nav">
			<?php _e( 'Navigation', 'strong-testimonials' ); ?>
		</label>
		<select id="view-nav" name="view[data][nav]" autocomplete="off">
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
</td>
