<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label><?php _e( 'Assign new submissions to one or more categories', 'strong-testimonials' ); ?></label>
</th>
<td>
	<?php if ( $category_ids ) : ?>
		<ul id="view_category_list_form" class="checkbox-horizontal">
			<?php foreach ( $category_list as $cat ) : ?>
			<li>
				<label>
					<input type="checkbox" name="view[data][category-form][]" value="<?php echo $cat->term_id; ?>" <?php checked( in_array( $cat->term_id, $view_cats_array ) ); ?>><?php echo $cat->name; ?>
				</label>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p class="description"><?php _e( 'No categories found.', 'strong-testimonials' ); ?></p>
	<?php endif; ?>
</td>
