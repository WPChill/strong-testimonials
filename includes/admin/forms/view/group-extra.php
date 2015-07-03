<?php /* translators: On the Views admin screen. */ ?>
<div class="then then_display then_not_form then_slideshow" style="display: none;">
	<h3><?php _e( 'Extra', 'strong-testimonials' ); ?></h3>
	<table class="form-table multiple group-layout" cellpadding="0" cellspacing="0">
		<tr style="display: none;" class="then then_display then_not_form then_not_slideshow then_not_id">
			<?php include( 'option-pagination.php' ); ?>
		</tr>
		<tr style="display: none;" class="then then_display then_not_form then_slideshow pair-top">
			<?php include( 'option-read-more.php' ); ?>
		</tr>
		<tr class="pair-bottom">
			<?php include( 'option-read-more-to.php' ); ?>
		</tr>
	</table>
</div>
