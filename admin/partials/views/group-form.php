<?php
$then_classes = array(
	'then',
	'then_not_display',
	'then_not_slideshow',
	'then_form',
	'then_not_single_template',
	apply_filters( 'wpmtst_view_section', '', 'form' ),
);
?>
<div class="<?php echo esc_attr( join( array_filter( $then_classes ), ' ' ) ); ?>" style="display: none;">
	<h3>
		<?php /* translators: On the Views admin screen. */ ?>
		<?php esc_html_e( 'Actions', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-select">
		<tr>
			<?php require 'option-form-category.php'; ?>
		</tr>
		<tr>
			<?php require 'option-form-ajax.php'; ?>
		</tr>
	</table>
</div>
