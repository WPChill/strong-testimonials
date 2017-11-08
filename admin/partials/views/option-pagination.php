<?php
$links = '<a href="#tab-panel-wpmtst-help-pagination" class="open-help-tab">' . __( 'Help' ) . '</a>';
$links .= ' | ';
$links .= '<a href="https://support.strongplugins.com/article/comparing-pagination-methods-strong-testimonials/" target="_blank">' . __( 'Compare methods', 'strong-testimonials' ) . '</a>';
?>
<?php /* translators: On the Views admin screen. */ ?>
<th>
    <input type="checkbox"
           id="view-pagination"
           class="if toggle"
           name="view[data][pagination]"
           value="1" <?php checked( $view['pagination'] ); ?>
           class="checkbox">
    <label for="view-pagination">
		<?php _e( 'Pagination', 'strong-testimonials' ); ?>
    </label>
</th>
<td>
    <div class="row then then_pagination" style="display: none;">
        <div class="row-inner">
            <div class="inline">
                <label for="view-pagination_type">
                    <select id="view-pagination_type" name="view[data][pagination_settings][type]" class="if selectper">
                        <option value="simple" <?php selected( 'simple', $view['pagination_settings']['type'] ); ?>>
							<?php _e( 'simple', 'strong-testimonials' ); ?>
                        </option>
                        <option value="standard" <?php selected( 'standard', $view['pagination_settings']['type'] ); ?>>
							<?php _e( 'WordPress standard', 'strong-testimonials' ); ?>
                        </option>
                    </select>
                </label>
            </div>
            <div class="inline then fast then_simple then_not_standard" style="display: none;">
                <p class="description">
					<?php _e( 'Using JavaScript. Intended for small scale.', 'strong-testimonials' ); ?>
					<?php echo $links; ?>
                </p>
            </div>
            <div class="inline then fast then_not_simple then_standard" style="display: none;">
                <p class="description">
					<?php _e( 'Using paged URLs: /page/2, /page/3, etc. Best for large scale.', 'strong-testimonials' ); ?>
					<?php echo $links; ?>
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
                <input type="number" id="view-per_page" class="input-incremental"
                       min="1" step="1" size="3"
                       name="view[data][pagination_settings][per_page]"
                       value="<?php echo $view['pagination_settings']['per_page']; ?>" formnovalidate/>
            </div>

            <div class="inline">
                <label for="view-nav">
					<?php _e( 'Navigation', 'strong-testimonials' ); ?>
                </label>
                <select id="view-nav" name="view[data][pagination_settings][nav]">
                    <option value="before" <?php selected( $view['pagination_settings']['nav'], 'before' ); ?>>
						<?php _e( 'before', 'strong-testimonials' ); ?>
                    </option>
                    <option value="after" <?php selected( $view['pagination_settings']['nav'], 'after' ); ?>>
						<?php _e( 'after', 'strong-testimonials' ); ?>
                    </option>
                    <option value="before,after" <?php selected( $view['pagination_settings']['nav'], 'before,after' ); ?>>
						<?php _e( 'before & after', 'strong-testimonials' ); ?>
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="row then then_not_simple then_standard" style="display: none;">
        <div class="row-inner">
            <div class="inline">
                <label for="view-pagination-show_all">
                    <select id="view-pagination-show_all"
                            name="view[data][pagination_settings][show_all]"
                            class="if select">
                        <option value="on" <?php selected( $view['pagination_settings']['show_all'] ); ?>>
							<?php _e( 'Show all page numbers', 'strong-testimonials' ); ?>
                        </option>
                        <option value="off" <?php selected( ! $view['pagination_settings']['show_all'] ); ?>
                                class="trip">
							<?php _e( 'Show condensed page numbers', 'strong-testimonials' ); ?>
                        </option>
                    </select>
                </label>
            </div>
            <div class="inline then then_show_all" style="display: none;">
                <div class="inline">
                    <label for="view-pagination-end_size">
						<?php _ex( 'End size', 'quantity', 'strong-testimonials' ); ?>
                    </label>
                    <input type="number" id="view-pagination-end_size" class="input-incremental"
                           min="1" step="1" size="3"
                           name="view[data][pagination_settings][end_size]"
                           value="<?php echo $view['pagination_settings']['end_size']; ?>" formnovalidate/>
                </div>
                <div class="inline">
                    <label for="view-pagination-mid_size">
						<?php _ex( 'Middle size', 'quantity', 'strong-testimonials' ); ?>
                    </label>
                    <input type="number" id="view-pagination-mid_size" class="input-incremental"
                           min="1" step="1" size="3"
                           name="view[data][pagination_settings][mid_size]"
                           value="<?php echo $view['pagination_settings']['mid_size']; ?>" formnovalidate/>
                </div>
            </div>
        </div>
    </div>

    <div class="row then then_not_simple then_standard" style="display: none;">
        <div class="row-inner">
            <div class="inline inline-middle">
                <input type="checkbox" id="view-pagination-prev_next" class="if toggle checkbox"
                       name="view[data][pagination_settings][prev_next]" value="1"
					<?php checked( $view['pagination_settings']['prev_next'] ); ?>>
                <label for="view-pagination-prev_next">
					<?php _e( 'Show previous/next links', 'strong-testimonials' ); ?>
                </label>
            </div>
            <div class="then then_prev_next inline inline-middle">
                <label for="view-pagination-prev_text">
					<?php _e( 'Previous text', 'strong-testimonials' ); ?>
                </label>
                <input type="text"
                       id="view-pagination-prev_text"
                       class="code"
                       name="view[data][pagination_settings][prev_text]"
                       value="<?php echo htmlentities( $view['pagination_settings']['prev_text'] ); ?>">
            </div>
            <div class="then then_prev_next inline inline-middle">
                <label for="view-pagination-next_text">
					<?php _e( 'Next text', 'strong-testimonials' ); ?>
                </label>
                <input type="text"
                       id="view-pagination-next_text"
                       class="code"
                       name="view[data][pagination_settings][next_text]"
                       value="<?php echo htmlentities( $view['pagination_settings']['next_text'] ); ?>">
            </div>
        </div>
    </div>

    <div class="row then then_not_simple then_standard" style="display: none;">
        <div class="row-inner">
            <div class="inline">
                <label for="view-pagination-before_page_number">
					<?php _e( 'Before page number', 'strong-testimonials' ); ?>
                </label>
                <input type="text"
                       id="view-pagination-before_page_number"
                       class="small-text"
                       name="view[data][pagination_settings][before_page_number]"
                       value="<?php echo $view['pagination_settings']['before_page_number']; ?>">
            </div>
            <div class="inline">
                <label for="view-pagination-after_page_number">
					<?php _e( 'After page number', 'strong-testimonials' ); ?>
                </label>
                <input type="text"
                       id="view-pagination-after_page_number"
                       class="small-text"
                       name="view[data][pagination_settings][after_page_number]"
                       value="<?php echo $view['pagination_settings']['after_page_number']; ?>">
            </div>
        </div>
    </div>

	<?php do_action( 'wpmtst_view_editor_pagination_row_end' ); ?>
</td>
