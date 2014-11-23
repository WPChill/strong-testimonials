<!-- EXAMPLES -->
<div id="tabs-examples">
	<table class="shortcode-table wide">
		<tr>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<th colspan="2"><h3><?php _e( 'Examples', 'strong-testimonials' ); ?></h3></th>
		</tr>
		<tr class="example">
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<td><?php _e( 'the form', 'strong-testimonials' ); ?></td>
			<td>[strong form]</td>
		</tr>
		<tr class="example">
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<td><?php _e( 'show all, 5 per page, with pagination above and below', 'strong-testimonials' ); ?></td>
			<td>[strong per_page="5" nav="before,after"]</td>
		</tr>
		<tr class="example">
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<td><?php _e( 'show 3 random, limited to about 200 characters', 'strong-testimonials' ); ?></td>
			<td>[strong count="3" random length="200"]</td>
		</tr>
		<tr class="example">
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<td><?php _e( 'a slideshow with excerpts<br /> from categories 2 and 3', 'strong-testimonials' ); ?></td>
			<td>[strong slideshow excerpt show_for="5" effect_for="1" category="2,3"]</td>
		</tr>
		<tr class="example">
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _e( 'show the full-length testimonial<br />
				with the title and thumbnail,<br />
				in random order,<br />
				with client name, company, and website,<br />
				and a "read more" link', 'strong-testimonials' ); ?>
			</td>
			<td>
				[strong count="10" random title thumbnail]<br />
				&nbsp;&nbsp;[client]<br />
				&nbsp;&nbsp;&nbsp;&nbsp;[field name="client_name" class="name"]<br />
				&nbsp;&nbsp;&nbsp;&nbsp;[field name="company_name" url="company_website" class="company" new_tab]<br />
				&nbsp;&nbsp;[/client]<br />
				[/strong]<br />
				[strong read_more page="all-testimonials" /]
			</td>
		</tr>
	</table>
	<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
	<h3><em><?php printf( __( 'Many more examples on the <a href="%s" target="_blank">demo site</a>.', 'strong-testimonials' ), 'http://demos.wpmission.com/strong-testimonials/' ); ?></em></h3>
</div>
