<!-- SLIDESHOW -->
<?php /* translators: In the [strong] shortcode instructions. */ ?>
<tr class="blank"><td colspan="3">&nbsp;</td></tr>
<tr class="heading">
	<th colspan="4"><?php _e( 'Slideshow', 'strong-testimonials' ); ?></th>
</tr>
<tr class="section">
	<th colspan="4">
		<div class="description"><?php _e( 'Use these along with attributes above (will override <strong>pagination</strong>)', 'strong-testimonials' ); ?></div>
	</th>
</tr>
<tr class="sub">
	<th><?php _e( 'attribute', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'purpose', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'default', 'strong-testimonials' ); ?></th>
	<th><?php _e( 'accepted values', 'strong-testimonials' ); ?></th>
</tr>
<tr class="att">
	<td>slideshow</td>
	<td><?php _e( 'to create a slideshow', 'strong-testimonials' ); ?><br>
		<em><?php _e( 'by default, it will pause when the mouse hovers over it', 'strong-testimonials' ); ?></em></td>
	<td></td>
	<td></td>
</tr>
<tr class="att">
	<td>show_for</td>
	<td>
		<?php _e( 'how long to show each for (in seconds)', 'strong-testimonials' ); ?>
	</td>
	<td class="code">8</td>
	<td><?php _e( 'a positive number', 'strong-testimonials' ); ?></td>
</tr>
<tr class="att">
	<td>effect_for</td>
	<td>
		<?php _e( 'how long the transition effect should last (in seconds)', 'strong-testimonials' ); ?>
	</td>
	<td class="code">1.5</td>
	<td><?php _e( 'a positive number', 'strong-testimonials' ); ?></td>
</tr>
<tr class="att">
	<td>no_pause</td>
	<td>
		<?php _e( 'to not pause the slideshow when the mouse hovers over it', 'strong-testimonials' ); ?>
	</td>
	<td></td>
	<td></td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'examples', 'strong-testimonials' ); ?></th>
			</tr>
			<tr>
				<td>[strong slideshow &hellip;]</td>
			</tr>
			<tr>
				<td>[strong slideshow show_for="5" effect_for=".5" &hellip;]</td>
			</tr>
		</table>
	</td>
</tr>
