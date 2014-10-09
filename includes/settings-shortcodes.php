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
	<th colspan="2"><h3><?php _e( 'Show All', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<td><?php _e( 'from all categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-all]</td>
</tr>
<tr>
	<td class="indented"><?php _e( 'or a single category', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-all category="1"]</td>
</tr>
<tr>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-all category="1,2,3"]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<th colspan="2"><h3><?php _e( 'Cycle', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<td><?php printf( __( '<a href="%s">configure</a>', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=settings&tab=cycle' ) ); ?></td>
	<td>[wpmtst-cycle]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<th colspan="2"><h3><?php _e( 'Random', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<td><?php _e( 'a single random testimonial', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random]</td>
</tr>
<tr>
	<td class="indented"><?php _e( 'or a certain number', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random limit="5"]</td>
</tr>
<tr>
	<td><?php _e( 'a single testimonial from a single category', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random category="1"]</td>
</tr>
<tr>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random category="1,2,3"]</td>
</tr>
<tr>
	<td><?php _e( 'a certain number from a single category', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random limit="5" category="1"]</td>
</tr>
<tr>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-random limit="5" category="1,2,3"]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<th colspan="2"><h3><?php _e( 'Single', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<td><?php _e( 'one specific testimonial', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-single id="1"]</td>
</tr>
</table>

<table class="shortcode-table">
<tr>
	<th colspan="2"><h3><?php _e( 'Submission Form', 'strong-testimonials' ); ?></h3></th>
</tr>
<tr>
	<td><?php _e( 'a form for visitors to submit testimonials', 'strong-testimonials' );?></td>
	<td>[wpmtst-form]</td>
</tr>
<tr>
	<td class="indented"><?php _e( 'and add them to a single category', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-form category="1"]</td>
</tr>
<tr>
	<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
	<td>[wpmtst-form category="1,2,3"]</td>
</tr>
</table>
