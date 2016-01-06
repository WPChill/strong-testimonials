<?php /* translators: On the Views admin screen. */ ?>
<div class="then then_display then_not_form then_slideshow" style="display: none;">
	<h3>
		<?php _e( 'Fields', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-show">
		<tr>
			<?php include( 'option-title.php' ); ?>
		</tr>
		<tr class="then then_display then_not_form then_slideshow" style="display: none;">
			<?php include( 'option-thumbnail.php' ); ?>
		</tr>
		<tr class="then then_display then_not_form then_slideshow" style="display: none;">
			<?php include( 'option-content.php' ); ?>
		</tr>
		<tr class="then then_display then_not_form then_slideshow" style="display: none;">
			<?php include( 'option-client-section.php' ); ?>
		</tr>
	</table>
</div>
