<!-- FORM -->
<?php /* translators: In the [strong] shortcode instructions. */ ?>
<tr class="heading">
	<th colspan="4"><?php _e( 'The Form', 'strong-testimonials' ); ?></th>
</tr>
<tr class="sub">
	<th>attribute</th>
	<th>purpose</th>
	<th>default</th>
	<th>accepted values</th>
</tr>
<tr class="att">
	<td>form</td>
	<td>to show the form</td>
	<td></td>
	<td></td>
</tr>
<tr class="att">
	<td>category</td>
	<td><?php _e( 'to add new testimonials to a category', 'strong-testimonials' ); ?></td>
	<td></td>
	<td>a list of category ID's, separated by commas</td>
</tr>
<tr class="att">
	<td>class</td>
	<td>
		<?php _ex( 'to add CSS classes to <code>div.strong-container</code>', 'CSS', 'strong-testimonials' ); ?>
	</td>
	<td></td>
	<td>a list of class names, separated by commas</td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'examples', 'strong-testimonials' ); ?></th>
			</tr>
			<tr>
				<td>[strong form]</td>
			</tr>
			<tr>
				<td>[strong form category="27" class="full-width"]</td>
			</tr>
		</table>
	</td>
</tr>
