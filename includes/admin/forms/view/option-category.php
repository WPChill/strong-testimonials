<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-category"><?php _e( 'Categories', 'strong-testimonials' ); ?></label>
</th>
<td colspan="2">
	<div id="view-category">
		<?php if ( $category_ids ) : ?>
			<div class="checkbox-toggle then_display then_slideshow then_not_form">
				<label>
					<input type="checkbox" id="view_category_all" name="view[data][category_all]" value="all" <?php checked( 'all', $view['category'] ); ?>>
					<?php _e( 'All', 'strong-testimonials' ); ?>
				</label>
			</div>
			<ul id="view_category_list" class="checkbox-horizontal">
				<?php foreach ( $category_list as $cat ) : ?>
					<li>
						<label>
							<input type="checkbox" name="view[data][category][]" value="<?php echo $cat->term_id; ?>" <?php checked( in_array( $cat->term_id, $view_cats_array ) ); ?>>
							<?php echo $cat->name . ' (' . $cat->count . ')'; ?>
						<label>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<input type="hidden" name="view[data][category_all]" value="all">
			<p class="description">No categories found.</p>
		<?php endif; ?>
	</div>
</td>
