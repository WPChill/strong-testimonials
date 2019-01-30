<?php
$then_classes = array(
	'then',
	'then_display',
	'then_not_form',
	'then_slideshow',
	'then_not_single_template',
	apply_filters( 'wpmtst_view_section', '', 'extra' ),
);
?>
<div class="<?php echo esc_attr( join( array_filter( $then_classes ), ' ' ) ); ?>" style="display: none;">
	<h3>
		<?php /* translators: On the Views admin screen. */ ?>
		<?php esc_html_e( 'Extra', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-layout">
		<tr class="then then_display then_not_form then_not_slideshow then_not_single then_multiple" style="display: none;">
			<?php require 'option-pagination.php'; ?>
		</tr>
		<tr class="then then_display then_not_form then_slideshow read-more" style="display: none;">
			<?php require 'option-read-more-page.php'; ?>
		</tr>
	</table>
</div>
