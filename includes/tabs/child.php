<!-- CHILD SHORTCODES -->
<div id="tabs-child">
	<table class="shortcode-table wide">
		<tr>
			<th><h3><?php _e( 'Child Shortcodes', 'strong-testimonials' ); ?></h3></th>
			<th><em><?php _e( 'must be inside a parent [ strong ] shortcode', 'strong-testimonials' ); ?></em></th>
		</tr>
		<tr>
			<td>
				<?php _e( 'the client section', 'strong-testimonials' ); ?><br />
				<em><?php _e( 'required for displaying custom fields', 'strong-testimonials' ); ?></em>
			</td>
			<td>[client]</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'a custom field', 'strong-testimonials' ); ?><br />
				<em><?php _e( '', 'strong-testimonials' ); ?></em>
			</td>
			<td>[field name="my_custom_field"]</td
		</tr>
		<tr>
			<td class="indented">
				<?php _e( 'with a CSS class', 'strong-testimonials' ); ?><br />
				<em><?php _e( '', 'strong-testimonials' ); ?></em>
			</td>
			<td>[field name="my_custom_field" class="css-class"]</td>
		</tr>
		<tr>
			<td class="indented">
				<?php _e( 'as a link with another field<br /> that opens in a new tab', 'strong-testimonials' ); ?><br />
				<em><?php _e( '', 'strong-testimonials' ); ?></em>
			</td>
			<td>[field name="my_custom_field_1" url="my_custom_field_2" new_tab]</td>
		</tr>

		<tr>
			<th colspan="2"><h3><?php _e( 'Examples', 'strong-testimonials' ); ?></h3></th>
		</tr>
		<tr class="example">
			<td><?php _e( 'minimal', 'strong-testimonials' ); ?></td>
			<td>[client][field name="client_name"][/client]</td>
		</tr>
		<tr class="example">
			<td><?php _e( 'full', 'strong-testimonials' ); ?></td>
			<td>[client]<br />&nbsp;&nbsp;[field name="client_name" class="name"]<br />&nbsp;&nbsp;[field name="company_name" class="company" url="company_website" new_tab]<br />[/client]</td>
		</tr>
	</table>
</div>
