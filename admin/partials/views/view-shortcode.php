<?php

$then_classes = array(
	'then',
	'then_display',
	'then_form',
	'then_slideshow',
	'then_not_single_template',
	apply_filters( 'wpmtst_view_section', '', 'shortcode' ),
);
?>

<div class="table-row form-view-shortcode <?php echo esc_attr( join( array_filter( $then_classes ), ' ' ) ); ?>">
	<div class="table-cell">
		<label for="view-shortcode">
			<?php esc_html_e( 'Shortcode', 'strong-testimonials' ); ?>
		</label>
	</div>
	<div class="table-cell">
	<?php if ( 'edit' == $action ) : ?>
		<div class="saved">
			<input id="view-shortcode" type="text" value="[testimonial_view id=&quot;<?php echo esc_attr( $view_id ); ?>&quot;]" readonly />
			<input id="copy-shortcode" class="button small" type="button" value="<?php echo esc_attr__( 'copy to clipboard', 'strong-testimonials' ); ?>" data-copytarget="#view-shortcode" />
			<span id="copy-message">copied</span>
		</div>
	<?php else : ?>
		<div class="unsaved">
			<?php echo esc_html_x( 'will be available after you save this view', 'The shortcode for a new View.', 'strong-testimonials' ); ?>
		</div>
	<?php endif; ?>
	</div>
</div>
