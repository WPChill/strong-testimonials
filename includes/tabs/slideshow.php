<!-- SLIDESHOW -->
<div id="tabs-slideshow">
	<table class="shortcode-table wide">
		<tr>
			<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
			<th colspan="2"><h3><?php _e( 'Slideshow', 'strong-testimonials' ); ?></h3></th>
		</tr>
		<tr>
			<td></td>
			<td>slideshow</td>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _ex( 'show each for (in seconds)', 'setting', 'strong-testimonials' ); ?><br />
			</td>
			<td>show_for="5"</td>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _ex( 'transition effect (in seconds)', 'setting', 'strong-testimonials' ); ?><br />
			</td>
			<td>effect_for=".5"</td>
		</tr>
		<tr>
			<td>
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
				<?php _ex( 'no pause on hover', 'setting', 'strong-testimonials' ); ?><br />
				<?php /* translators: This appears in the [strong] shortcode instructions. */ ?>
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
