<!-- PAGINATION ATTRIBUTES -->
<tr class="section">
	<th colspan="4">
		<h4><?php _e( 'Pagination', 'strong-testimonials' ); ?></h4>
	</th>
</tr>
<tr class="sub">
	<th><?php _e( 'attribute', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'purpose', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'default', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'accepted values', 'strong-testimonials' ); ?></th>
</tr>
<tr class="att">
	<td>per_page</td>
	<td><?php _e( 'how many to show per page', 'strong-testimonials' ); ?></td>
	<td></td>
	<td><?php _e( 'a positive integer', 'strong-testimonials' ); ?></td>
</tr>
<tr class="att">
	<td>nav</td>
	<td><?php _e( 'where to add page controls', 'strong-testimonials' ); ?></td>
	<td class="code">after</td>
	<td class="code">before<br>before,after</td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'examples', 'strong-testimonials' ); ?></th>
			</tr>
			<tr>
				<td>[strong per_page="5" &hellip;]</td>
			</tr>
			<tr>
				<td>[strong per_page="5" nav="before,after" &hellip;]</td>
			</tr>
		</table>
	</td>
</tr>
