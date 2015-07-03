<?php /* translators: This is on the Views admin screen. */ ?>
<div class="then then_display then_not_form then_slideshow" style="display: none;">
	<h3><?php _e( 'Fields', 'strong-testimonials' ); ?></h3>
	<table class="form-table multiple group-show" cellpadding="0" cellspacing="0">
		<tr>
			<?php include( 'option-title.php' ); ?>
		</tr>
		<tr style="display: none;" class="then then_display then_not_form then_slideshow">
			<?php include( 'option-thumbnail.php' ); ?>
		</tr>
		<tr style="display: none;" class="then then_display then_not_form then_slideshow pair-top">
			<?php include( 'option-content.php' ); ?>
		</tr>
		<tr class="pair-bottom no-padding">
			<td colspan="2">
				<div class="screenshot" id="screenshot-screen-options" style="display: none;">
					<div style="background: url(<?php echo WPMTST_URL; ?>images/screen-options.png); height: 241px; width: 730px;"></div>
				</div>
			</td>
		</tr>
		<tr style="display: none;" class="then then_display then_not_form then_slideshow">
			<?php include( 'option-client-section.php' ); ?>
		</tr>
	</table>
</div>
