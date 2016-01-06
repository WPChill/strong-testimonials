<?php /* translators: On the Views admin screen. */ ?>
<th>
	<?php _e( 'Assign new submissions to a category', 'strong-testimonials' ); ?>
</th>
<td class="valign-middle">
	<?php if ( $category_ids ) : ?>
		<ul id="view_category_list_form" class="checkbox-horizontal">
			<?php foreach ( $category_list as $cat ) : ?>
			<li>
				<input type="checkbox" id="category-form-<?php echo $cat->term_id; ?>" name="view[data][category-form][]" value="<?php echo $cat->term_id; ?>" <?php checked( in_array( $cat->term_id, $view_cats_array ) ); ?>>
				<label for="category-form-<?php echo $cat->term_id; ?>">
					<?php echo $cat->name; ?>
				</label>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p class="description tall"><?php _e( 'No categories found', 'strong-testimonials' ); ?></p>
	<?php endif; ?>
</td>
