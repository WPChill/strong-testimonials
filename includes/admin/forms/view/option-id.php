<?php /* translators: On the Views admin screen. */ ?>
<td colspan="2" style="display: none;" class="then then_not_slideshow then_single then_not_multiple">
	<div>
		<div class="row top-of-cell">
			<label>
				<select id="view-id" name="view[data][id]" autocomplete="off">
					<option value="0"><?php _e( '&mdash; select &mdash;' ); ?></option>
					<?php foreach ( $posts_list as $post ) : ?>
						<option value="<?php echo $post->ID; ?>" <?php selected( $view['id'], $post->ID ); ?> autocomplete="off">
							<?php echo sprintf( '%d: %s', $post->ID, $post->post_title ? $post->post_title : __( '(untitled)', 'strong-testimonials' ) ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
			<label for="view-post_id">
				<?php _ex( 'or enter its ID or slug', 'to select a testimonial', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="view-post_id" name="view[data][post_id]" size="30">
		</div>
	</div>
</td>
