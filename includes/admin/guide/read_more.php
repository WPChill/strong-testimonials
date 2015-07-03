<?php /* translators: In the [strong] shortcode instructions. */ ?>
<tr class="blank"><td colspan="3">&nbsp;</td></tr>
<tr class="heading">
	<th colspan="4"><?php _e( 'A separate "Read more" link to a page', 'strong-testimonials' ); ?></th>
</tr>
<tr class="section">
	<th colspan="4">
		<div class="description"><?php _e( '<code>[strong read_more]</code> is deprecated. Use <code>[read_more]</code> instead.', 'strong-testimonials' ); ?></div>
	</th>
</tr>
<tr class="sub">
	<th><?php _e( 'attribute', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'purpose', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'default', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'accepted values', 'strong-testimonials' ); ?></th>
</tr>
<tr class="att">
	<td>page</td>
	<td><?php _e( 'a page ID or slug', 'strong-testimonials' ); ?></td>
	<td></td>
	<td></td>
</tr>
<tr class="att">
	<td>class</td>
	<td><?php _e( 'to add a CSS class', 'strong-testimonials' ); ?></td>
	<td></td>
	<td>a list of class names, separated by commas</td>
</tr>
<tr class="att">
	<td>{content}</td>
	<td>the link text</td>
	<td><?php _e( 'Read more', 'strong-testimonials' ); ?></td>
	<td></td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'examples', 'strong-testimonials' ); ?></th>
			</tr>
			<tr>
				<td>[read_more page="27"]</td>
			</tr>
			<tr>
				<td>[read_more page="27" class="red"]More testimonials &raquo;[/read_more]</td>
			</tr>
		</table>
	</td>
</tr>
