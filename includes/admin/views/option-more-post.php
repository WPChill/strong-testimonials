<?php /* translators: On the Views admin screen. */ ?>
<th colspan="2">
	<div>
		<div class="inline checkbox">
			<input type="checkbox" id="view-more_post" class="if toggle" name="view[data][more_post]" value="1" <?php checked( $view['more_post'] );?> class="checkbox">
			<label for="view-more_post">
				<?php _e( '"Read more" link to the testimonial', 'strong-testimonials' ); ?>
			</label>
		</div>
		<div class="inline then_more_post">
			<label for="view-more_text">
				<?php _e( 'Link text', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="view-more_text" name="view[data][more_text]" value="<?php echo $view['more_text']; ?>" size="30">
		</div>
	</div>
	<p class="description under-checkbox">
		<?php _e( 'Typically used with excerpts and truncated content.', 'strong-testimonials' ); ?>
	</p>
</th>
