<!-- SELECTION -->
<tr class="section">
	<th colspan="4">
		<h4><?php _e( 'Selection', 'strong-testimonials' ); ?></h4>
	</th>
</tr>
<tr class="sub">
	<th><?php _e( 'attribute', 'strong-testimonials' ); ?></th>
	<th>purpose</th>
	<th>default</th>
	<th>accepted values</th>
</tr>
<tr class="att">
	<td></td>
	<td><?php _e( 'to show all', 'strong-testimonials' ); ?></td>
	<td><span class="dashicons dashicons-yes"></span></td>
	<td></td>
</tr>
<tr class="att">
	<td>count</td>
	<td><?php _ex( 'to show a certain number', 'quantity', 'strong-testimonials' ); ?></td>
	<td></td>
	<td><?php _e( 'a positive integer', 'strong-testimonials' ); ?></td>
</tr>
<tr class="att">
	<td>category</td>
	<td><?php _e( 'to select specific categories', 'strong-testimonials' ); ?></td>
	<td></td>
	<td><?php _e( 'a list of category ID\'s, separated by commas', 'strong-testimonials' ); ?></td>
</tr>
<tr class="att">
	<td>id</td>
	<td><?php _e( 'to select by post ID', 'strong-testimonials' ); ?></td>
	<td></td>
	<td><?php _e( 'a list of post ID\'s, separated by commas', 'strong-testimonials' ); ?></td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'examples', 'strong-testimonials' ); ?></th>
			</tr>
			<tr>
				<td>[strong count="10" &hellip;]</td>
			</tr>
			<tr>
				<td>[strong category="2,4" &hellip;]</td>
			</tr>
			<tr>
				<td>[strong id="65" &hellip;]</td>
			</tr>
		</table>
	</td>
</tr>
