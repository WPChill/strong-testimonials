<?php

function wpmtst_help_fields_editor() {

	//$content = '<p>' . __( '', 'strong-testimonials' ) . '</p>';

	$content = '<p>' . __( 'The default fields are designed to fit most situations. You can quickly add or remove fields and change several display properties to meet your needs.', 'strong-testimonials' ) . '</p>';

	$content .= '<p>' . __( 'Fields will appear in this order on the form.', 'strong-testimonials' ) . '&nbsp;';

	$content .= sprintf( __( 'Reorder by grabbing the %s icon.', 'strong-testimonials' ), '<span class="dashicons dashicons-menu"></span>' ) . '</p>';

	$content .= '<p>' . __( 'Click a field to open or close its options panel.', 'strong-testimonials' ) . '</p>';

	$content .= '<p>' . __( 'Keep in mind that any changes here also affect the custom fields available in the post editor and the view editor. In other words, you\'re really doing two things: (1) modifying the form as it appears on your site, and (2) modifying the custom fields.', 'strong-testimonials' ) . '</p>';


	$content .= '<p><a href="https://www.wpmission.com/tutorials/how-to-customize-the-form-in-strong-testimonials/" target="_blank">' . _x( 'Here is a full tutorial on customizing the form', 'link', 'strong-testimonials' ) . '</a>.</p>';

	$content .= '<p>' . sprintf( __( 'More form settings <a href="%s">here</a>.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form' ) ) . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'wpmtst-help',
		'title'   => __( 'Strong Testimonials', 'strong-testimonials' ),
		'content' => $content,
	) );
}
add_action( 'load-wpm-testimonial_page_testimonial-fields', 'wpmtst_help_fields_editor' );
