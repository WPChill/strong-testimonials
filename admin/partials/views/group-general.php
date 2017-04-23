<?php
// Use multiple checks here until better technique is found.
if ( wpmtst_divi_builder_active() ) : ?>
<div class="then then_display then_form then_slideshow" style="display: none;">
	<h3>
		<?php _e( 'Options', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-general">
		<tr class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-divi.php' ); ?>
		</tr>
	</table>
</div>
<?php endif; ?>
