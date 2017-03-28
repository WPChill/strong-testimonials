<?php /* translators: On the Views admin screen. */ ?>
<th>
	<input type="checkbox" id="view-pagination" class="if toggle" name="view[data][pagination]" value="1" <?php checked( $view['pagination'] ); ?> class="checkbox">
	<label for="view-pagination">
		<?php _e( 'Pagination', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row then then_pagination" style="display: none;">
		<div class="row-inner">
			<div class="inline">
				<label for="view-pagination_type">
					<select id="view-pagination_type" name="view[data][pagination_type]" class="if selectper">
						<option value="simple" <?php selected( 'simple', $view['pagination_type'] ); ?>>
							<?php _e( 'simple', 'strong-testimonials' ); ?>
						</option>
						<option value="standard" <?php selected( 'standard', $view['pagination_type'] ); ?>>
							<?php _e( 'WordPress standard', 'strong-testimonials' ); ?>
						</option>
					</select>
				</label>
			</div>
			<div class="inline then fast then_simple then_not_standard" style="display: none;">
				<p class="description">
					<?php _e( 'Using JavaScript. Intended for small scale.', 'strong-testimonials' ); ?>
					<a href="#tab-panel-wpmtst-help-pagination" class="open-help-tab"><?php _e( 'Help' ); ?></a> |
                    <a href="https://support.strongplugins.com/article/comparing-pagination-methods-strong-testimonials/" target="_blank"><?php _e( 'Compare methods', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<div class="inline then fast then_not_simple then_standard" style="display: none;">
				<p class="description">
					<?php _e( 'Using paged URLs: /page/2, /page/3, etc. Best for large scale.', 'strong-testimonials' ); ?>
					<a href="#" class="open-help-tab"><?php _e( 'Help' ); ?></a> |
                    <a href="https://support.strongplugins.com/article/comparing-pagination-methods-strong-testimonials/" target="_blank"><?php _e( 'Compare methods', 'strong-testimonials' ); ?></a>
				</p>
			</div>
		</div>
	</div>

	<div class="row then then_pagination" style="display: none;">
		<div class="row-inner">
			<div class="inline">
				<label for="view-per_page">
					<?php _ex( 'Per page', 'quantity', 'strong-testimonials' ); ?>
				</label>
				<input id="view-per_page" class="input-incremental" type="number" min="1" name="view[data][per_page]"
					   value="<?php echo $view['per_page']; ?>" size="3">
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
		</div>
		<?php do_action( 'wpmtst_view_editor_pagination_row_end' ); ?>
	</div>
</td>
