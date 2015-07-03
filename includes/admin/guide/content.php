<!-- CONTENT ATTRIBUTES -->
<tr class="section">
	<th colspan="4">
		<h4><?php _ex( 'Content', 'noun', 'strong-testimonials' ); ?></h4>
	</th>
</tr>
<tr class="sub">
	<th>attribute</th>
	<th>purpose</th>
	<th>default</th>
	<th>accepted values</th>
</tr>
<tr class="att">
	<td></td>
	<td><?php _e( 'to show the full post', 'strong-testimonials' ); ?></td>
	<td><span class="dashicons dashicons-yes"></span></td>
	<td></td>
</tr>
<tr class="att">
	<td>excerpt</td>
	<td>
		<?php _e( 'to show the excerpt', 'strong-testimonials' ); ?><br>
		<em><?php _e( 'overrides length attribute', 'strong-testimonials' ); ?></em>
	</td>
	<td></td>
	<td></td>
</tr>
<tr class="att">
	<td>length</td>
	<td>
		<?php _ex( 'to show up to a certain length', 'content', 'strong-testimonials' ); ?><br>
		<em><?php _e( 'will break on a space and add an ellipsis', 'strong-testimonials' ); ?></em>
	</td>
	<td></td>
	<td>a positive integer</td>
</tr>
<tr class="att">
	<td>title</td>
	<td>
		<?php _e( 'to show the Title', 'strong-testimonials' ); ?><br>
		<em><?php _e( 'if included in Fields', 'strong-testimonials' ); ?></em>
	</td>
	<td></td>
	<td></td>
</tr>
<tr class="att">
	<td>thumbnail</td>
	<td>
		<?php _e( 'to show the Featured Image', 'strong-testimonials' ); ?><br>
		<em><?php _e( 'if included in Fields', 'strong-testimonials' ); ?></em>
	</td>
	<td></td>
	<td></td>
</tr>
<tr class="att">
	<td>thumbnail_size</td>
	<td>
		<?php _e( 'to set the Featured Image size', 'strong-testimonials' ); ?><br>
	</td>
	<td class="code">thumbnail</td>
	<td class="code">
		medium<br>large<br>full<br>
		(width in pixels),(height in pixels)<br>
		<a href="https://codex.wordpress.org/Post_Thumbnails" target="_blank" rel="nofollow">more about thumbnail sizes</a>
	</td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'examples', 'strong-testimonials' ); ?></th>
			</tr>
			<tr>
				<td>[strong excerpt &hellip;]</td>
			</tr>
			<tr>
				<td>[strong length="200" &hellip;]</td>
			</tr>
			<tr>
				<td>[strong title thumbnail &hellip;]</td>
			</tr>
			<tr>
				<td>[strong title thumbnail thumbnail_size="300,225" &hellip;]</td>
			</tr>
		</table>
	</td>
</tr>
