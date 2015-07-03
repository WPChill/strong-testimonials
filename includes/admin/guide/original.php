<div class="guide-content original-shortcodes">
	<section>
		<h3><?php _e( 'The Original Shortcodes', 'strong-testimonials' ); ?></h3>
		
		<?php wpmtst_update_nag( __( 'These shortcodes will be removed soon.' ) ); ?>
		
		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h4><?php _e( 'Show All', 'strong-testimonials' ); ?></h4></th>
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
				<th colspan="2"><h4><?php _e( 'Cycle', 'strong-testimonials' ); ?></h4></th>
			</tr>
			<tr>
				<td><?php printf( _x( '<a href="%s">configure</a>', 'verb', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=settings&tab=cycle' ) ); ?></td>
				<td>[wpmtst-cycle]</td>
			</tr>
			</table>
		
			<table class="shortcode-table">
			<tr>
				<th colspan="2"><h4><?php _ex( 'Random', 'display order', 'strong-testimonials' ); ?></h4></th>
			</tr>
			<tr>
				<td><?php _e( 'a single random testimonial', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-random]</td>
			</tr>
			<tr>
				<td class="indented"><?php _ex( 'or a certain number', 'quantity', 'strong-testimonials' ); ?></td>
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
				<td><?php _ex( 'a certain number from a single category', 'quantity', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-random limit="5" category="1"]</td>
			</tr>
			<tr>
				<td class="indented"><?php _e( 'or multiple categories', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-random limit="5" category="1,2,3"]</td>
			</tr>
		</table>
		
		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h4><?php _ex( 'Single', 'quantity', 'strong-testimonials' ); ?></h4></th>
			</tr>
			<tr>
				<td><?php _e( 'one specific testimonial', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-single id="1"]</td>
			</tr>
		</table>
		
		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h4><?php _e( 'Submission Form', 'strong-testimonials' ); ?></h4></th>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<code>[wpmtst-form]</code> was removed. Use <code>[strong form]</code> or create a View instead.
				</td>
			</tr>
		</table>
	</section>
</div>
