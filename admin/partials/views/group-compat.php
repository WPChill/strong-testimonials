<?php
$then_classes = array(
	'then',
	apply_filters( 'wpmtst_view_section', '', 'compat' ),
);
?>
<div class="<?php echo esc_attr( join( array_filter( $then_classes ), ' ' ) ); ?>" style="display: none;">
	<h3>
		<?php /* translators: On the Views admin screen. */ ?>
		<?php _e( 'Compatibility', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-general">
		<tr class="then then_display then_form then_slideshow then_not_single_template" style="display: none;">
			<?php include( 'option-divi.php' ); ?>
		</tr>
	</table>
</div>
