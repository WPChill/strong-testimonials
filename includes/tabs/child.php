<!-- CHILD SHORTCODES -->
<div id="tabs-child">
	<table class="shortcode-table wide">
		<tr>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<th><h3><?php _e( 'Child Shortcodes', 'strong-testimonials' ); ?></h3></th>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<th><em><?php _e( 'must be inside a parent [ strong ] shortcode', 'strong-testimonials' ); ?></em></th>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _e( 'the client section', 'strong-testimonials' ); ?><br />
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<em><?php _e( 'required for displaying custom fields', 'strong-testimonials' ); ?></em>
			</td>
			<td>[client]</td>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _e( 'a custom field', 'strong-testimonials' ); ?><br />
			</td>
			<td>[field name="my_custom_field"]</td
		</tr>
		<tr>
			<td class="indented">
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _e( 'with a CSS class', 'strong-testimonials' ); ?><br />
			</td>
			<td>[field name="my_custom_field" class="css-class"]</td>
		</tr>
		<tr>
			<td class="indented">
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _e( 'as a link with another field<br /> that opens in a new tab', 'strong-testimonials' ); ?><br />
			</td>
			<td>[field name="my_custom_field_1" url="my_custom_field_2" new_tab]</td>
		</tr>

		<tr>
			<th colspan="2"><h3><?php _e( 'Examples', 'strong-testimonials' ); ?></h3></th>
		</tr>
		<tr class="example">
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<td><?php _ex( 'minimal', 'adjective', 'strong-testimonials' ); ?></td>
			<td>[client][field name="client_name"][/client]</td>
		</tr>
		<tr class="example">
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<td><?php _ex( 'full', 'adjective', 'strong-testimonials' ); ?></td>
			<td>[client]<br />&nbsp;&nbsp;[field name="client_name" class="name"]<br />&nbsp;&nbsp;[field name="company_name" class="company" url="company_website" new_tab]<br />[/client]</td>
		</tr>
	</table>
</div>
