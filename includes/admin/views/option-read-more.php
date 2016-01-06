<?php
/* translators: On the Views admin screen. */
$more_post_selected = isset( $view['more_post'] ) && $view['more_post'];
$more_page_selected = isset( $view['more_page'] ) && $view['more_page'];
$read_more = $more_post_selected || $more_page_selected;
?>
<!-- READ MORE CHECKBOX -->
<th>
	<div class="checkbox">
		<input type="checkbox" id="view-read_more" class="if toggle" name="view[data][read_more]" value="1" <?php checked( $read_more );?> class="checkbox">
		<label for="view-read_more">
			<?php _e( '"Read more" link', 'strong-testimonials' ); ?>
		</label>
	</div>
</th>

<!-- READ MORE TO -- POST or PAGE -->
<td>
	<div class="row then then_read_more" style="display: none;">

		<div class="row">
			<div class="row-inner">

				<div class="inline">
					<div>
						<label>
							<select id="view-read_more_to" class="if select" name="view[data][read_more_to]">
								<option id="view-more_post" class="if toggle" name="view[data][more_post]" value="more_post" <?php selected( $more_post_selected );?>><?php _e( 'to the testimonial', 'strong-testimonials' ); ?></option>
								<option id="view-more_page" class="if toggle trip" name="view[data][more_page]" value="more_page" <?php selected( $more_page_selected );?>><?php _e( 'to a page', 'strong-testimonials' ); ?></option>
							</select>
						</label>
					</div>
				</div>

				<div class="inline">
					<!-- LINK TEXT -->
					<div>
						<label for="view-more_text">
							<?php _e( 'Link text', 'strong-testimonials' ); ?>
						</label>
						<input type="text" id="view-more_text" name="view[data][more_text]" value="<?php echo $view['more_text']; ?>" size="30">
					</div>
				</div>

			</div><!-- .row-inner -->
		</div><!-- .row -->


		<!-- MORE PAGE -->
		<div class="row then then_read_more_to" style="display: none;">
			<div class="row-inner">

				<label>
					<select id="view-page" name="view[data][more_page]">
						<option value=""><?php _e( '&mdash; select &mdash;' ); ?></option>
						<?php foreach ( $pages_list as $pages ) : ?>
							<option value="<?php echo $pages->ID; ?>" <?php selected( isset( $view['more_page'] ) ? $view['more_page'] : 0, $pages->ID ); ?>><?php echo $pages->post_title; ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label for="view-page_id">
					<?php _ex( 'or enter its ID or slug', 'to select a target page', 'strong-testimonials' ); ?>
				</label>
				<input type="text" id="view-page_id" name="view[data][more_page_id]" size="30">

			</div><!-- .row-inner -->
		</div><!-- .row -->

	</div>

</td>
