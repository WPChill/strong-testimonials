<?php /* translators: On the Views admin screen. */ ?>
<h3><?php _e( 'Style', 'strong-testimonials' ); ?></h3>

<table class="form-table multiple group-style" cellpadding="0" cellspacing="0">
	<tr class="then then_display then_not_form then_slideshow" style="display: none;">
		<?php include( 'option-template.php' ); ?>
	</tr>
	<tr class="then then_not_display then_form then_not_slideshow" style="display: none;">
		<?php include( 'option-template-form.php' ); ?>
	</tr>
	<tr class="then then_display then_form then_slideshow">
		<?php include( 'option-background.php' ); ?>
	</tr>
	<tr class="then then_display then_form then_slideshow">
		<?php include( 'option-classes.php' ); ?>
	</tr>
</table>
