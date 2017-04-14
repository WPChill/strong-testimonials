<?php /* translators: On the Views admin screen. */ ?>
<div class="then then_display then_form then_slideshow" style="display: none;">
	<h3>
		<?php _e( 'Style', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-style">
		<tr class="then then_display then_not_form then_slideshow" style="display: none;">
			<?php include( 'option-template-list.php' ); ?>
		</tr>
		<tr class="then then_not_display then_form then_not_slideshow" style="display: none;">
			<?php include( 'option-form-template-list.php' ); ?>
		</tr>
		<tr class="then then_display then_not_form then_not_slideshow" style="display: none;">
			<?php include( 'option-layout.php' ); ?>
		</tr>
		<tr id="group-style-option-background" class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-background.php' ); ?>
		</tr>
		<tr class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-classes.php' ); ?>
		</tr>
	</table>
</div>
