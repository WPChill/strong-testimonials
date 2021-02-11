<?php
// avoiding the tab character before the shortcode for better copy-n-paste
if ( 'edit' == $action ) {
	$shortcode = '<div class="saved">';
	$shortcode .= '<input id="view-shortcode" type="text" value="[testimonial_view id=&quot;' . esc_attr( $view_id ) . '&quot;]" readonly />';
	$shortcode .= '<input id="copy-shortcode" class="button small" type="button" value="' . esc_attr__( 'copy to clipboard', 'strong-testimonials' ) . '" data-copytarget="#view-shortcode" />';
	$shortcode .= '<span id="copy-message">copied</span>';
	$shortcode .= '</div>';
} else {
	$shortcode = '<div class="unsaved">' . _x( 'will be available after you save this', 'The shortcode for a new View.', 'strong-testimonials' ) . '</div>';
}

$then_classes = array(
	'then',
	'then_display',
	'then_form',
	'then_slideshow',
	'then_not_single_template',
	apply_filters( 'wpmtst_view_section', '', 'shortcode' ),
);
?>

<div class="table-row form-view-shortcode <?php echo esc_attr( implode( ' ', array_filter( $then_classes ) ) ); ?>">
	<div class="table-cell">
        <label for="view-shortcode">
		    <?php _e( 'Shortcode', 'strong-testimonials' ); ?>
        </label>
	</div>
	<div class="table-cell">
		<?php echo $shortcode; ?>
	</div>
</div>
