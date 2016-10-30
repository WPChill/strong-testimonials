<?php /* translators: On the Views admin screen. */ ?>
<th>
	<?php _e( 'Assign new submissions to a category', 'strong-testimonials' ); ?>
</th>
<td class="valign-middle">
	<?php if ( $category_list ) : ?>
	<div class="table">
		<div class="table-row">
			<div class="table-cell">
				<?php wpmtst_form_category_checklist( $view_cats_array ); ?>
			</div>
		</div>
	</div>
	<?php else : ?>
		<p class="description tall"><?php _e( 'No categories found', 'strong-testimonials' ); ?></p>
	<?php endif; ?>
</td>
