<?php
$links  = '<span class="help-links">';
$links .= '<a href="#tab-panel-wpmtst-help-pagination" class="open-help-tab">' . __( 'Help' ) . '</a>';
$links .= '</span>';
?>
<?php /* translators: On the Views admin screen. */ ?>
<th>
	<input class="if toggle checkbox" id="view-pagination" name="view[data][pagination]"
		   type="checkbox" value="1" <?php checked( $view['pagination'] ); ?>/>
	<label for="view-pagination">
		<?php esc_html_e( 'Pagination', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row then then_pagination" style="display: none;">
		<div class="row-inner">
			<div class="inline">
				<label for="view-pagination_type">
					<select class="if selectper" id="view-pagination_type" name="view[data][pagination_settings][type]">
						<option value="simple" <?php selected( 'simple', $view['pagination_settings']['type'] ); ?>>
							<?php esc_html_e( 'simple', 'strong-testimonials' ); ?>
						</option>
						<option value="standard" <?php selected( 'standard', $view['pagination_settings']['type'] ); ?>>
							<?php esc_html_e( 'WordPress standard', 'strong-testimonials' ); ?>
						</option>
					</select>
				</label>
			</div>
			<div class="inline then fast then_simple then_not_standard" style="display: none;">
				<p class="description">
					<?php esc_html_e( 'Using JavaScript. Intended for small scale.', 'strong-testimonials' ); ?>
					<?php echo wp_kses_post( $links ); ?>
				</p>
			</div>
			<div class="inline then fast then_not_simple then_standard" style="display: none;">
				<p class="description">
					<?php esc_html_e( 'Using paged URLs: /page/2, /page/3, etc. Best for large scale.', 'strong-testimonials' ); ?>
					<?php echo wp_kses_post( $links ); ?>
				</p>
			</div>
		</div>
	</div>

	<div class="row then then_pagination" style="display: none;">
		<div class="row-inner">
			<div class="inline">
				<label for="view-per_page">
					<?php echo esc_html_x( 'Per page', 'quantity', 'strong-testimonials' ); ?>
				</label>
				<input class="input-incremental" id="view-per_page" name="view[data][pagination_settings][per_page]" type="number" min="1" step="1" value="<?php echo esc_attr( $view['pagination_settings']['per_page'] ); ?>"/>
			</div>

			<div class="inline">
				<label for="view-nav">
					<?php esc_html_e( 'Navigation', 'strong-testimonials' ); ?>
				</label>
				<select id="view-nav" name="view[data][pagination_settings][nav]">
					<option value="before" <?php selected( $view['pagination_settings']['nav'], 'before' ); ?>>
						<?php esc_html_e( 'before', 'strong-testimonials' ); ?>
					</option>
					<option value="after" <?php selected( $view['pagination_settings']['nav'], 'after' ); ?>>
						<?php esc_html_e( 'after', 'strong-testimonials' ); ?>
					</option>
					<option value="before,after" <?php selected( $view['pagination_settings']['nav'], 'before,after' ); ?>>
						<?php esc_html_e( 'before & after', 'strong-testimonials' ); ?>
					</option>
				</select>
			</div>
		</div>

		<div class="row then then_not_simple then_standard" style="display: none;">
			<div class="row-inner">
				<div class="inline">
					<label for="view-pagination-show_all">
						<select class="if select" id="view-pagination-show_all"
								name="view[data][pagination_settings][show_all]">
							<option value="on" <?php selected( $view['pagination_settings']['show_all'] ); ?>>
								<?php esc_html_e( 'Show all page numbers', 'strong-testimonials' ); ?>
							</option>
							<option value="off" <?php selected( ! $view['pagination_settings']['show_all'] ); ?> class="trip">
								<?php esc_html_e( 'Show condensed page numbers', 'strong-testimonials' ); ?>
							</option>
						</select>
					</label>
				</div>
				<div class="inline then then_show_all" style="display: none;">
					<div class="inline">
						<label for="view-pagination-end_size">
							<?php echo esc_html_x( 'End size', 'quantity', 'strong-testimonials' ); ?>
						</label>
						<input class="input-incremental" id="view-pagination-end_size" name="view[data][pagination_settings][end_size]" type="number" min="1" step="1" value="<?php echo esc_attr( $view['pagination_settings']['end_size'] ); ?>"/>
					</div>
					<div class="inline">
						<label for="view-pagination-mid_size">
							<?php echo esc_html_x( 'Middle size', 'quantity', 'strong-testimonials' ); ?>
						</label>
						<input class="input-incremental" id="view-pagination-mid_size" name="view[data][pagination_settings][mid_size]" type="number" min="1" step="1" value="<?php echo esc_attr( $view['pagination_settings']['mid_size'] ); ?>"/>
					</div>
				</div>
			</div>
		</div>

		<div class="row then then_not_simple then_standard" style="display: none;">
			<div class="row-inner">
				<div class="inline inline-middle">
					<input class="if toggle checkbox" id="view-pagination-prev_next" name="view[data][pagination_settings][prev_next]" type="checkbox" value="1" <?php checked( $view['pagination_settings']['prev_next'] ); ?>>
					<label for="view-pagination-prev_next">
						<?php esc_html_e( 'Show previous/next links', 'strong-testimonials' ); ?>
					</label>
				</div>
				<div class="then then_prev_next inline inline-middle">
					<label for="view-pagination-prev_text">
						<?php esc_html_e( 'Previous text', 'strong-testimonials' ); ?>
					</label>
					<input class="code" id="view-pagination-prev_text" name="view[data][pagination_settings][prev_text]" type="text" value="<?php echo htmlentities( $view['pagination_settings']['prev_text'] ); ?>">
				</div>
				<div class="then then_prev_next inline inline-middle">
					<label for="view-pagination-next_text">
						<?php esc_html_e( 'Next text', 'strong-testimonials' ); ?>
					</label>
					<input class="code" id="view-pagination-next_text" name="view[data][pagination_settings][next_text]" type="text" value="<?php echo htmlentities( $view['pagination_settings']['next_text'] ); ?>">
				</div>
			</div>
		</div>

		<div class="row then then_not_simple then_standard" style="display: none;">
			<div class="row-inner">
				<div class="inline">
					<label for="view-pagination-before_page_number">
						<?php esc_html_e( 'Before page number', 'strong-testimonials' ); ?>
					</label>
					<input class="small-text" id="view-pagination-before_page_number" name="view[data][pagination_settings][before_page_number]" type="text" value="<?php echo esc_attr( $view['pagination_settings']['before_page_number'] ); ?>">
				</div>
				<div class="inline">
					<label for="view-pagination-after_page_number">
						<?php esc_html_e( 'After page number', 'strong-testimonials' ); ?>
					</label>
					<input class="small-text" id="view-pagination-after_page_number" name="view[data][pagination_settings][after_page_number]" type="text" value="<?php echo esc_attr( $view['pagination_settings']['after_page_number'] ); ?>">
				</div>
			</div>
		</div>
	</div>

	<?php do_action( 'wpmtst_view_editor_pagination_row_end' ); ?>
</td>
