<!-- READ MORE ATTRIBUTES -->
<tr class="section">
	<th colspan="4">
		<h4><?php _e( 'Read More', 'strong-testimonials' ); ?></h4>
	</th>
</tr>
<tr class="sub">
	<th><?php _e( 'attribute', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'purpose', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'default', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'accepted', 'strong-testimonials' ); ?> values</th>
</tr>
<tr class="att">
	<td>more_post</td>
	<td>
		<?php _e( 'to add a "Read more" link to the full post', 'strong-testimonials' ); ?><br>
		<em><?php _e( 'for use with <b>excerpt</b> or <b>length</b> only', 'strong-testimonials' ); ?></em>
	</td>
	<td></td>
	<td></td>
</tr>
<tr class="att">
	<td>more_page</td>
	<td>
		<?php _e( 'to add a "Read more" link to a page', 'strong-testimonials' ); ?><br>
	</td>
	<td></td>
	<td><?php _e( 'a page ID or slug', 'strong-testimonials' ); ?></td>
</tr>
<tr class="att">
	<td>more_text</td>
	<td>
		<?php _e( 'to change the "Read more" phrase', 'strong-testimonials' ); ?><br>
	</td>
	<td><?php _e( 'Read more', 'strong-testimonials' ); ?></td>
	<td><?php _e( 'text', 'strong-testimonials' ); ?></td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'examples', 'strong-testimonials' ); ?></th>
			</tr>
			<tr>
				<td>[strong more_post &hellip;]</td>
			</tr>
			<tr>
				<td>[strong more_page="27" more_text="More testimonials" &hellip;]</td>
			</tr>
		</table>
	</td>
</tr>
