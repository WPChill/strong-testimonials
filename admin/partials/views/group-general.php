<?php
// Use multiple checks here until better technique is found.
// Divi Builder does not conflict with single post template (yet).
if ( wpmtst_divi_builder_active() ) : ?>
<div class="then then_display then_form then_slideshow then_not_single_template" style="display: none;">
	<h3>
		<?php _e( 'Options', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-general">
		<tr class="then then_display then_form then_slideshow then_not_single_template" style="display: none;">
			<?php include( 'option-divi.php' ); ?>
		</tr>
	</table>
</div>
<?php endif; ?>
