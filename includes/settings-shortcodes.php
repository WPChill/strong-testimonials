<?php
/**
 * Settings > Shortcodes Page > Original Shortcodes Tab
 *
 * @package Strong_Testimonials
 */
?>

<div class="update-nag"><?php _e( 'These shortcodes will be deprecated soon. Please migrate to the <code>[strong]</code> shortcode.', 'strong-testimonials' ); ?></div>

<table class="shortcode-table">
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<th colspan="2"><h3><?php _e( 'Show All', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td><?php _e( 'from all categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-all]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td class="indented"><?php _e( 'or a single category', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-all category="1"]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-all category="1,2,3"]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<th colspan="2"><h3><?php _e( 'Cycle', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td><?php printf( _x( '<a href="%s">configure</a>', 'verb', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=settings&tab=cycle' ) ); ?></td>
	<td>[wpmtst-cycle]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<th colspan="2"><h3><?php _ex( 'Random', 'display order', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td><?php _e( 'a single random testimonial', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td class="indented"><?php _ex( 'or a certain number', 'quantity', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random limit="5"]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td><?php _e( 'a single testimonial from a single category', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random category="1"]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random category="1,2,3"]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td><?php _ex( 'a certain number from a single category', 'quantity', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random limit="5" category="1"]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random limit="5" category="1,2,3"]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<th colspan="2"><h3><?php _ex( 'Single', 'quantity', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td><?php _e( 'one specific testimonial', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-single id="1"]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<th colspan="2"><h3><?php _e( 'Submission Form', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td><?php _e( 'a form for visitors to submit testimonials', 'strong-testimonials' );?></td>
	<td>[wpmtst-form]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td class="indented"><?php _e( 'and add them to a single category', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-form category="1"]</td>
</tr>
<tr>
	<?php /* translators: This appears on the Original Shortcodes admin screen. */ ?>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-form category="1,2,3"]</td>
</tr>
</table>
