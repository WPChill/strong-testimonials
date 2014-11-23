<!-- FORM -->
<div id="tabs-form">
	<table class="shortcode-table wide">
	<tr>
		<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
		<th colspan="2"><h3><?php _e( 'The Form', 'strong-testimonials' ); ?></h3></th>
	</tr>
	<tr>
		<td></td>
		<td>form</td>
	</tr>
	<tr>
		<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
		<td><?php _e( 'add new submissions to a category', 'strong-testimonials' ); ?></td>
		<td>category="2"</td>
		<td>category="2,4,6"</td>
	</tr>
	<tr>
		<td>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<?php _ex( 'add classes to <code>div.strong-container</code>', 'CSS', 'strong-testimonials' ); ?><br />
		</td>
		<td>class="abc"</td>
		<td>class="abc,xyz"</td>
	</tr>
	<tr>
		<td>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<?php _e( 'skip plugin stylesheet', 'strong-testimonials' ); ?><br />
		</td>
		<td>no_stylesheet</td>
	</tr>

	<tr>
		<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
		<th colspan="2"><h3><?php _e( 'Examples', 'strong-testimonials' ); ?></h3></th>
	</tr>
	<tr class="example">
		<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
		<td><?php _ex( 'minimal', 'adjective', 'strong-testimonials' ); ?></td>
		<td colspan="2">[strong form]</td>
	</tr>
	<tr class="example">
		<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
		<td><?php _ex( 'full', 'adjective', 'strong-testimonials' ); ?></td>
		<td colspan="2">[strong form category="27" no_stylesheet class="full-width"]</td>
	</tr>
	</table>
</div>
