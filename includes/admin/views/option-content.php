<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-content">
		<?php _e( 'Content', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="inline">
		<select id="view-content" class="if selectper" name="view[data][content]">
			<option value="entire" <?php selected( 'entire', $view['content'] ); ?>>
				<?php _ex( 'the full testimonial', 'display setting', 'strong-testimonials' ); ?>
			</option>
			<option value="excerpt" <?php selected( 'excerpt', $view['content'] ); ?>>
				<?php _ex( 'the excerpt', 'display setting', 'strong-testimonials' ); ?>
			</option>
			<option value="truncated" <?php selected( 'truncated', $view['content'] ); ?>>
				<?php _ex( 'a specific length', 'display setting', 'strong-testimonials' ); ?>
			</option>
		</select>
	</div>

	<div class="inline then fast then_truncated then_not_entire then_not_excerpt" style="display: none;">
		<label class="inline inline-middle">
			<input id="view-length" class="input-incremental" type="number" min="10" max="995" step="5" name="view[data][length]" value="<?php echo $view['length']; ?>">&nbsp; characters
		</label>
		<div class="inline">
			<p class="description">
				<?php _e( 'Will break on a space and add an ellipsis.', 'strong-testimonials' ); ?><br>
				<?php _e( 'Will strip tags like &lt;em&gt; and &lt;strong&gt;.', 'strong-testimonials' ); ?>
			</p>
		</div>
	</div>

	<div class="inline then fast then_not_truncated then_not_entire then_excerpt" style="display: none;">
		<p class="description">
			<?php _e( 'Excerpts are hand-crafted summaries of your testimonial.', 'strong-testimonials' ); ?><br>
			<?php
				$url = '#TB_inline?width=&height=210&inlineId=screenshot-screen-options';
				$allowed_html = array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) );
				printf( wp_kses( __( 'You may need to enable them in the post editor like in this <a href="%s" class="%s">screenshot</a>.', 'strong-testimonials' ), $allowed_html ), esc_url( $url ), 'thickbox' );
			?>
			<span class="screenshot" id="screenshot-screen-options" style="display: none;">
				<img src="<?php echo WPMTST_URL; ?>images/screen-options.png" width="600">
			</span>
		</p>
	</div>
</td>
