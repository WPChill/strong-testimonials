<?php /* translators: On the Views admin screen. */ ?>
<div class="then then_not_display then_not_form then_slideshow" style="display: none;">
	<h3>
		<?php _e( 'Appearance', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-select">
		<tr>
			<?php include( 'option-timing.php' ); ?>
		</tr>
		<tr>
			<?php include( 'option-slideshow-nav.php' ); ?>
		</tr>
		<tr class="then then_slideshow_nav" style="display: none;">
			<?php include( 'option-stretch.php' ); ?>
		</tr>
	</table>
</div>
