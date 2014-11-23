<!-- READ MORE -->
<div id="tabs-readmore">
	<table class="shortcode-table wide">
		<tr>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<th><h3><?php _e( '"Read More" link', 'strong-testimonials' ); ?></h3></th>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<th><em><?php _e( 'must not be inside another [ strong ] shortcode', 'strong-testimonials' ); ?></em></th>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _e( 'appears after the loop', 'strong-testimonials' ); ?><br />
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<em><?php _e( 'default text is "Read more" (translatable)', 'strong-testimonials' ); ?></em>
			</td>
			<td>
				read_more
			</td>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _e( 'to another page (required)', 'strong-testimonials' ); ?><br />
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<em><?php _e( 'ID or slug', 'strong-testimonials' ); ?></em>
			</td>
			<td>
				page
			</td>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _ex( 'add classes', 'CSS', 'strong-testimonials' ); ?><br />
			</td>
			<td>
				class
			</td>
		</tr>

		<tr>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<th><h3><?php _e( 'Examples', 'strong-testimonials' ); ?></h3></th>
		</tr>
		<tr class="example">
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _ex( 'minimal', 'adjective', 'strong-testimonials' ); ?><br />
			</td>
			<td>
				[strong read_more page="27" /]
			</td>
		</tr>
		<tr class="example">
			<td>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<?php _ex( 'full', 'adjective', 'strong-testimonials' ); ?><br />
			</td>
			<td>
				[strong read_more page="all-testimonials" class="strong-more"]Read more testimonials[/strong]
			</td>
		</tr>
	</table>
</div>
