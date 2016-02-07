<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-category">
		<?php _e( 'Categories', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div id="view-category" class="row">
		<?php if ( $category_ids ) : ?>
			<div class="table">
				<div class="table-row">

					<div class="table-cell select-cell then_display then_slideshow then_not_form">

						<select id="view-category-select" class="if selectper" name="view[data][category_all]">
							<option value="allcats" <?php selected( $view['category'], 'all' ); ?>><?php _e( 'all', 'strong-testimonials' ); ?></option>
							<option value="somecats" <?php echo ( 'all' != $view['category'] ? 'selected' : '' ); ?>><?php _ex( 'select', 'verb', 'strong-testimonials' ); ?></option>
						</select>

					</div>

					<div class="table-cell then then_not_allcats then_somecats" style="display: none;">
						<div class="view-category-list-panel">
							<div class="fc-search-wrap"></div>
							<ul class="view-category-list">
								<?php $args = array(
									'descendants_and_self'  => 0,
									'selected_cats'         => $view_cats_array,
									'popular_cats'          => false,
									'walker'                => new Walker_Testimonial_Category_Checklist(),
									'taxonomy'              => 'wpm-testimonial-category',
									'checked_ontop'         => true
								); ?>
								<?php wp_terms_checklist( 0, $args ); ?>
							</ul>
						</div>
					</div>

				</div>
			</div>
		<?php else : ?>
			<input type="hidden" name="view[data][category_all]" value="all">
			<p class="description tall"><?php _e( 'No categories found', 'strong-testimonials' ); ?></p>
		<?php endif; ?>
	</div>
</td>
