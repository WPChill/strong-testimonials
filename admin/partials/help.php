<?php

function wpmtst_help_fields_editor() {

	$content = '<p>' . __( 'The default fields are designed to fit most situations. You can quickly add or remove fields and change several display properties to meet your needs.', 'strong-testimonials' ) . '</p>';

	$content .= '<p>' . __( 'Fields will appear in this order on the form.', 'strong-testimonials' ) . '&nbsp;';

	$content .= sprintf( __( 'Reorder by grabbing the %s icon.', 'strong-testimonials' ), '<span class="dashicons dashicons-menu"></span>' ) . '</p>';

	$content .= '<p>' . __( 'Keep in mind that any changes here also affect the custom fields available in the post editor and the view editor. In other words, you\'re really doing two things: (1) modifying the form as it appears on your site, and (2) modifying the custom fields.', 'strong-testimonials' ) . '</p>';


	// Links

	$links = array(
		'<a href="https://www.wpmission.com/tutorials/how-to-customize-the-form-in-strong-testimonials/" target="_blank">' . __( 'Tutorial', 'strong-testimonials' ) . '</a>',
		'<a href="' . admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form' ) . '">' . __( 'Form settings', 'strong-testimonials' ) . '</a>'
	);

	// WPML
	if ( wpmtst_is_plugin_active( 'wpml' ) ) {
		$links[] = sprintf( __( 'Translate these fields in <a href="%s">WPML String Translations</a>', 'strong-testimonials' ),
			admin_url( 'admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=strong-testimonials-form-fields' ) );
	}

	// Polylang
	if ( wpmtst_is_plugin_active( 'polylang' ) ) {
		$links[] = sprintf( __( 'Translate these fields in <a href="%s">Polylang String Translations</a>', 'strong-testimonials' ),
			admin_url( 'options-general.php?page=mlang&tab=strings&s&group=strong-testimonials-form-fields&paged=1' ) );
	}

	$content .= '<p>' . implode( ' | ', $links ) . '</p>';


	get_current_screen()->add_help_tab( array(
		'id'      => 'wpmtst-help',
		'title'   => __( 'Form Fields', 'strong-testimonials' ),
		'content' => $content,
	) );
}
add_action( 'load-wpm-testimonial_page_testimonial-fields', 'wpmtst_help_fields_editor' );
