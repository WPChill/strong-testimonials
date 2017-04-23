<?php /* translators: On the Views admin screen. */ ?>
<div class="then then_display then_form then_slideshow" style="display: none;">
	<h3>
		<?php _e( 'Options', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-compat">
		<tr class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-compat.php' ); ?>
		</tr>
	</table>
</div>
<?php
// Use multiple checks here until better technique is found.
if ( wpmtst_divi_builder_active() ) : ?>
<div class="then then_display then_form then_slideshow" style="display: none;">
	<h3>
		<?php _e( 'Options', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-compat">
		<tr class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-divi.php' ); ?>
		</tr>
	</table>
</div>
<?php endif; ?>
