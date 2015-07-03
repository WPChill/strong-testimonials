<?php
/* translators: On the Views admin screen. */
$read_more = ( isset( $view['more_post'] ) && $view['more_post'] ) || ( isset( $view['more_page'] ) && $view['more_page'] );
?>
<!-- READ MORE CHECKBOX -->
<th rowspan="2">
	<div class="checkbox">
		<input type="checkbox" id="view-read_more" class="if toggle" name="view[data][read_more]" value="1" <?php checked( $read_more );?> class="checkbox">
		<label for="view-read_more">
			<?php _e( '"Read more" link', 'strong-testimonials' ); ?>
		</label>
	</div>
</th>

<!-- READ MORE TO -- POST or PAGE -->
<td>
	<?php
	$more_post_selected = isset( $view['more_post'] ) && $view['more_post']; 
	$more_page_selected = isset( $view['more_page'] ) && $view['more_page']; 
	?>
	<div class="checkbox then then_read_more" style="display: none;">
		<label>
			<select id="view-read_more_to" class="if select" name="view[data][read_more_to]" autocomplete="off">
				<option id="view-more_post" class="if toggle" name="view[data][more_post]" value="more_post" <?php selected( $more_post_selected );?>>to the testimonial</option>
				<option id="view-more_page" class="if toggle trip" name="view[data][more_page]" value="more_page" <?php selected( $more_page_selected );?>>to a page</option>
			</select>
		</label>
	</div>
</td>

<!-- LINK TEXT -->
<td>
	<div class="inline then_read_more" style="display: none;">
		<label for="view-more_text" class="">
			<?php _e( 'Link text', 'strong-testimonials' ); ?>
		</label>
		<input type="text" id="view-more_text" name="view[data][more_text]" value="<?php echo $view['more_text']; ?>" size="30">
	</div>
</td>	
