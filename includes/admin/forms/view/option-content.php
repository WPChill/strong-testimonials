<?php /* translators: On the Views admin screen. */ ?>
<th rowspan="2">
	<label for="view-content"><?php _e( 'Content', 'strong-testimonials' ); ?></label>
</th>
<td>
	<div class="inline">
		<select id="view-content" class="if selectper" name="view[data][content]" autocomplete="off">
			<option <?php selected( 'entire', $view['content'] ); ?> value="entire">
			<?php _ex( 'the full testimonial', 'display setting', 'strong-testimonials' ); ?>
			</option>
			<option <?php selected( 'excerpt', $view['content'] ); ?> value="excerpt">
			<?php _ex( 'the excerpt', 'display setting', 'strong-testimonials' ); ?>
			</option>
			<option <?php selected( 'truncated', $view['content'] ); ?> value="truncated">
			<?php _ex( 'a specific length', 'display setting', 'strong-testimonials' ); ?>
			</option>
		</select>
	</div>
</td>
<td>
	<div class="">
		<div style="display: none;" class="then fast then_truncated then_not_entire then_not_excerpt">
			<label class="inline inline-middle">
				<input id="view-length" class="input-incremental" type="number" min="10" max="995" step="5" name="view[data][length]" value="<?php echo $view['length']; ?>">&nbsp; characters
			</label>
			<p class="description inline inline-middle">
				<?php _e( 'Will break on a space and add an ellipsis.', 'strong-testimonials' ); ?>
			</p>
		</div>
	</div>

	<div class="">
		<div style="display: none;" class="then fast then_not_truncated then_not_entire then_excerpt">
			<p class="description">
				<?php _e( 'Excerpts are hand-crafted summaries of your testimonial.', 'strong-testimonials' ); ?>
				<br>
				<?php _e( 'You may need to enable them in the post editor like in this <a id="toggle-screen-options">screenshot</a>.', 'strong-testimonials' ); ?>
			</p>
		</div>
	</div>
</td>
