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
				<?php _ex( 'abbreviated', 'display setting', 'strong-testimonials' ); ?>
			</option>
		</select>
	</div>

	<!-- Excerpt length -->
	<div class="row then then_not_truncated then_not_entire then_excerpt" style="display: none;">

		<div class="row-inner">
			<div class="inline">
				<span>
					<?php printf( wp_kses( __( 'If no manual excerpt, use the <a href="%s" target="_blank">automatic excerpt</a> with', 'strong-testimonials' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) ) ), esc_url( 'http://buildwpyourself.com/wordpress-manual-excerpts-more-tag/' ) ); ?>
				</span>
				<label>
					<select id="view-use_default_length" class="if selectgroup" name="view[data][use_default_length]">
						<option value="1" <?php selected( $view['use_default_length'] ); ?>>
							<?php _ex( 'the default length', 'display setting', 'strong-testimonials' ); ?>
						</option>
						<option value="0" <?php selected( ! $view['use_default_length'] ); ?>>
							<?php _ex( 'a custom length', 'display setting', 'strong-testimonials' ); ?>
						</option>
					</select>
				</label>
				<span class="then fast then_use_default_length then_1 then_not_0" style="display: none;">
					<?php _e( 'as set by your theme', 'strong-testimonials' ); ?>
				</span>
				<span class="then fast then_use_default_length then_0 then_not_1" style="display: none;">
					<label class="inline inline-middle">
						<span>
							<?php _ex( 'of the first', 'As in "the first 20 words"', 'strong-testimonials' ); ?>
						</span>
						<span>
							<input id="view-excerpt_length" class="input-incremental" type="number" min="1" max="999" name="view[data][excerpt_length]" value="<?php echo $view['excerpt_length']; ?>">
						</span>
						<span>
							<?php _e( 'words', 'strong-testimonials' ); ?>
						</span>
					</label>
				</span>
			</div>
		</div>

	</div>

	<!-- Truncated -->
	<div class="inline then then_truncated then_not_entire then_not_excerpt" style="display: none;">

		<div class="inline">
			<label class="inline inline-middle">
				<span>
					<?php _ex( 'using the first', 'As in "the first 20 words"', 'strong-testimonials' ); ?>
				</span>
				<span>
					<input id="view-word_count" class="input-incremental" type="number" min="1" max="999" name="view[data][word_count]" value="<?php echo $view['word_count']; ?>">
				</span>
				<span>
					<?php _e( 'words', 'strong-testimonials' ); ?>
				</span>
			</label>
		</div>
		<div class="inline">
			<p class="description tall">
				<?php _e( 'This will strip tags like &lt;em&gt; and &lt;strong&gt;.', 'strong-testimonials' ); ?>
			</p>
		</div>

	</div>

	<!-- info & screenshot -->
	<div class="row then fast then_not_truncated then_not_entire then_excerpt" style="display: none;">
		<div class="row-inner">
			<p class="description">
				<?php
				$url = '#TB_inline?width=&height=210&inlineId=screenshot-screen-options';
				$allowed_html = array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) );
				printf( wp_kses( __( 'To create manual excerpts, you may need to enable them in the post editor like in this <a href="%s" class="%s">screenshot</a>.', 'strong-testimonials' ), $allowed_html ), esc_url( $url ), 'thickbox' );
				?>
				<span class="screenshot" id="screenshot-screen-options" style="display: none;">
					<img src="<?php echo WPMTST_URL; ?>images/screen-options.png" width="600">
				</span>
			</p>
		</div>
	</div>

	<!-- Read More link -->
	<div class="row then then_truncated then_not_entire then_excerpt" style="display: none;">
		<div class="row-inner">
			<input type="checkbox" id="view-more_post" class="if toggle checkbox" name="view[data][more_post]" value="1"
				<?php checked( isset( $view['more_post'] ) && $view['more_post'] );?>>
			<label for="view-more_post">
				<?php _e( '"Read more" link', 'strong-testimonials' ); ?>
			</label>
			<span class="then then_more_post" style="display: none;">
				<span id="option-link-text">
					<label for="view-use_default_more">
						<?php _e( 'using', 'strong-testimonials' ); ?>
					</label>
					<select id="view-use_default_more" class="if selectgroup" name="view[data][use_default_more]">
						<option value="1" <?php selected( $view['use_default_more'] ); ?>>
							<?php _ex( 'the default link text', 'display setting', 'strong-testimonials' ); ?>
						</option>
						<option value="0" <?php selected( ! $view['use_default_more'] ); ?>>
							<?php _ex( 'custom link text', 'display setting', 'strong-testimonials' ); ?>
						</option>
					</select>
				</span>
				<span class="then fast then_use_default_more then_1 then_not_0" style="display: none;">
					<?php _e( 'as set by your theme', 'strong-testimonials' ); ?>
				</span>
				<span class="then fast then_use_default_more then_0 then_not_1" style="display: none;">
					<span id="option-link-text" class="inline-span">
						<label for="view-more_post_text">
							<input type="text" id="view-more_post_text" name="view[data][more_post_text]"
								   value="<?php echo $view['more_post_text']; ?>" size="22">
						</label>
					</span>
					<span id="option-ellipsis">
						<input type="checkbox" id="view-more_post_ellipsis" class="if toggle checkbox" name="view[data][more_post_ellipsis]" value="1"
							<?php checked( isset( $view['more_post_ellipsis'] ) && $view['more_post_ellipsis'] );?>>
						<label for="view-more_post_ellipsis">
							<?php _e( 'ellipsis between content and link', 'strong-testimonials' ); ?>
						</label>
					</span>
				</span>
			</span><!-- .then_more_post -->
		</div><!-- .row-inner -->
	</div><!-- .row -->

</td>
