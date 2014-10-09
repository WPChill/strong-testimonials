<!-- SLIDESHOW -->
<div id="tabs-slideshow">
	<table class="shortcode-table wide">
		<tr>
			<th colspan="2"><h3><?php _e( 'Slideshow', 'strong-testimonials' ); ?></h3></th>
		</tr>
		<tr>
			<td></td>
			<td>slideshow</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'show each for (in seconds)', 'strong-testimonials' ); ?><br />
			</td>
			<td>show_for="5"</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'transition effect (in seconds)', 'strong-testimonials' ); ?><br />
			</td>
			<td>effect_for=".5"</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'no pause on hover', 'strong-testimonials' ); ?><br />
				<em><?php _e( 'will pause by default', 'strong-testimonials' ); ?></em>
			</td>
			<td>no_pause</td>
		</tr>

		<?php include( 'part-selection.php' ); ?>
		<?php include( 'part-content.php' ); ?>
		<?php include( 'part-parts.php' ); ?>
		<?php include( 'part-style.php' ); ?>
	</table>
</div>
