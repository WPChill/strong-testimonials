<?php
/**
 * Localization functions.
 * 
 * @since 1.21.0
 */

/**
 * Update strings when custom fields change.
 * 
 * @param $fields
 */
function wpmtst_update_l10n_strings( $fields ) {
	// WPML
	wpmtst_form_fields_wpml( $fields );
	// Polylang
	wpmtst_form_fields_polylang( $fields );
}
add_action( 'wpmtst_fields_updated', 'wpmtst_update_l10n_strings', 10 );

/**
 * Get the translated context description.
 * 
 * @param string $name
 *
 * @return mixed
 */
function wpmtst_get_l10n_context( $name = '' ) {
	$contexts = get_option( 'wpmtst_l10n_contexts' );
	return __( $contexts[$name], 'strong-testimonials' );
}

/**
 * WPML
 * 
 * @param $string
 * @param $context
 * @param $name
 *
 * @return mixed|void
 */
function wpmtst_l10n_wpml( $string, $context, $name ) {
	return apply_filters( 'wpml_translate_single_string', $string, $context, $name );
}

/**
 * Polylang
 * 
 * @param $string
 * @param $context
 * @param $name
 *
 * @return bool|string|void
 */
function wpmtst_l10n_polylang( $string, $context, $name ) {
	if ( function_exists( 'pll__' ) ) {
		return pll__( $string );
	}
	return $string;
}

/**
 * Add our translation filters. 
 */
function wpmtst_l10n_filters() {
	// WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) )
		add_filter( 'wpmtst_l10n', 'wpmtst_l10n_wpml', 10, 3 );
	
	// Polylang
	if ( defined( 'POLYLANG_VERSION' ) )
		add_filter( 'wpmtst_l10n', 'wpmtst_l10n_polylang', 20, 3 );
}
add_action( 'plugins_loaded', 'wpmtst_l10n_filters' );

/*
 * ----------------------------------------
 * WPML
 * ----------------------------------------
 */
 
/**
 * Add form fields to WPML String Translation.
 * 
 * I prefer this granular approach and the UI control it provides, as opposed to the group approach below.
 *
 * @param $fields
 */
function wpmtst_form_fields_wpml( $fields ) {
	/* Translators: This is for String Translation in WPML & Polylang plugins. */
	//$domain = __( 'Testimonial Form Fields', 'strong-testimonials');
	$domain = wpmtst_get_l10n_context( 'form-fields' );
	// Reverse LIFO field order to match the form.
	$wpml = $fields;
	krsort( $wpml );
	foreach ( $wpml as $field ) {
		$name = $field['name'] . ' : ';
		/* Translators: A form field name on the String Translation screen. */
		do_action( 'wpml_register_single_string', $domain, $name . __( 'after', 'strong-testimonials' ), $field['after'] );
		do_action( 'wpml_register_single_string', $domain, $name . __( 'before', 'strong-testimonials' ), $field['before'] );
		do_action( 'wpml_register_single_string', $domain, $name . __( 'placeholder', 'strong-testimonials' ), $field['placeholder'] );
		do_action( 'wpml_register_single_string', $domain, $name . __( 'label', 'strong-testimonials' ), $field['label'] );
	}
}

/**
 * Add form messages to WPML String Translation.
 *
 * @param $fields
 */
function wpmtst_form_messages_wpml( $fields ) {
	/* Translators: This is for String Translation in WPML & Polylang plugins. */
	//$domain = __( 'Testimonial Form Messages', 'strong-testimonials' );
	$domain = wpmtst_get_l10n_context( 'form-messages' );
	// Reverse LIFO field order to match the form.
	$wpml = $fields;
	krsort( $wpml );
	foreach ( $wpml as $key => $field ) {
		
		// Method 1 -- To also add the admin label to the String Translation list:
		//$name = $key . ' : ';
		//do_action( 'wpml_register_single_string', $domain, $name . __( 'text', 'strong-testimonials' ), $field['text'] );
		//do_action( 'wpml_register_single_string', $domain, $name . __( 'label on admin screen', 'strong-testimonials' ), $field['description'] );
		
		// Method 2 -- To use the plugin's translation for the admin label:
		// (We can translate here because the description was localized when added.)
		do_action( 'wpml_register_single_string', $domain, __( $field['description'], 'strong-testimonials' ), $field['text'] );
	}
}

/**
 * Add form notification messages to WPML String Translation.
 * 
 * @param $options
 */
function wpmtst_form_options_wpml( $options ) {
	//$domain = __( 'Testimonial Notification Options', 'strong-testimonials' );
	$domain = wpmtst_get_l10n_context( 'notification' );
	do_action( 'wpml_register_single_string', $domain, __( 'Email message', 'strong-testimonials' ), $options['email_message'] );
	do_action( 'wpml_register_single_string', $domain, __( 'Email subject', 'strong-testimonials' ), $options['email_subject'] );
}

/**
 * WPML Package
 * 
 * I don't see the advantage to using this group approach in our case.
 *
 * @param $fields
 */
/*
function wpmtst_form_fields_package_wpml( $fields ) {
	$package = array(
		'kind' => 'Testimonial Forms', // in the Type dropdown; will also be lower-cased and hyphenated as first half of the Domain on the String Translation screen
		'name' => 'package-name', // resulting Domain name: testimonial-forms-package-name
		'title' => 'Testimonial Form Fields',  // the package title in the Translation Dashboard list 
		'edit_link' => 'LINK TO EDIT THE FORM',
		'view_link' => 'LINK TO VIEW THE FORM'  // this would require a preview page
	);
	foreach ( $fields as $field ) {
		//do_action( 'wpml_register_string', string $string_value, string $string_name, array $package, string $string_title, string string_type );
		do_action( 'wpml_register_string', $field['label'], $field['name'] . ': label', $package, 'Label?', LINE );
	}
}
*/


/*
 * ----------------------------------------
 * POLYLANG
 * ----------------------------------------
 */

/*
	pll_register_string($name, $string, $group, $multiline);
	‘$name’ => (required) name provided for sorting convenience (ex: ‘myplugin’)
	‘$string’ => (required) the string to translate
	‘$group’ => (optional) the group in which the string is registered, defaults to ‘polylang’
	‘$multiline’ => (optional) if set to true, the translation text field will be multiline, defaults to false
*/

function wpmtst_form_fields_polylang( $fields ) {
	if ( function_exists( 'pll_register_string' ) ) {
		//$domain = __( 'Testimonial Form Fields', 'strong-testimonials' );
		$domain = wpmtst_get_l10n_context( 'form-fields' );
		foreach ( $fields as $field ) {
			$name = $field['name'] . ' : ';
			pll_register_string( $name . __( 'after', 'strong-testimonials' ), $field['after'], $domain );
			pll_register_string( $name . __( 'before', 'strong-testimonials' ), $field['before'], $domain );
			pll_register_string( $name . __( 'placeholder', 'strong-testimonials' ), $field['placeholder'], $domain );
			pll_register_string( $name . __( 'label', 'strong-testimonials' ), $field['label'], $domain );
		}
	}
}

function wpmtst_form_messages_polylang( $fields ) {
	if ( function_exists( 'pll_register_string' ) ) {
		//$domain = __( 'Testimonial Form Messages', 'strong-testimonials' );
		$domain = wpmtst_get_l10n_context( 'form-messages' );
		foreach ( $fields as $key => $field ) {
			pll_register_string( __( $field['description'], 'strong-testimonials' ), $field['text'], $domain );
		}
	}
}

function wpmtst_form_options_polylang( $options ) {
	if ( function_exists( 'pll_register_string' ) ) {
		//$domain = __( 'Testimonial Notification Options', 'strong-testimonials' );
		$domain = wpmtst_get_l10n_context( 'notification' );
		pll_register_string( __( 'Email subject', 'strong-testimonials' ), $options['email_subject'], $domain );
		pll_register_string( __( 'Email message', 'strong-testimonials' ), $options['email_message'], $domain, true );
	}
}
