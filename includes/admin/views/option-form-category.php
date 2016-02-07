<?php /* translators: On the Views admin screen. */ ?>
<th>
	<?php _e( 'Assign new submissions to a category', 'strong-testimonials' ); ?>
</th>
<td class="valign-middle">
	<?php if ( $category_ids ) : ?>
		<div class="view-category-list-panel">
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
	<?php else : ?>
		<p class="description tall"><?php _e( 'No categories found', 'strong-testimonials' ); ?></p>
	<?php endif; ?>
</td>
