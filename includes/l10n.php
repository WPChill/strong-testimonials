<?php
/**
 * Localization functions.
 *
 * @since 1.21.0
 */

/**
 * Return default translation from po/mo files if no active translation plugin.
 *
 * @since 2.23.2
 * @param $string
 *
 * @return string
 */
function wpmtst_l10n_default( $string ) {
	return __( $string, 'strong-testimonials' );
}
add_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );

/**
 * Help link on form settings screen.
 */
function wpmtst_l10n_before_form_settings() {

	// WPML
	if ( wpmtst_is_plugin_active( 'wpml' ) ) {
		echo '<p>';
		echo '<span class="dashicons dashicons-info icon-blue"></span>&nbsp;';
		printf( __( 'Translate these fields in <a href="%s">WPML String Translations</a>', 'strong-testimonials' ),
			admin_url( 'admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=strong-testimonials-form-messages' ) );
		echo '</p>';
	}

	// Polylang
	if ( wpmtst_is_plugin_active( 'polylang' ) ) {
		echo '<p>';
		echo '<span class="dashicons dashicons-info icon-blue"></span>&nbsp;';
		printf( __( 'Translate these fields in <a href="%s">Polylang String Translations</a>', 'strong-testimonials' ),
			admin_url( 'options-general.php?page=mlang&tab=strings&s&group=strong-testimonials-form-messages&paged=1' ) );
		echo '</p>';
	}

}
add_action( 'wpmtst_before_form_settings', 'wpmtst_l10n_before_form_settings' );

/**
 * Help link on form notification settings screen.
 */
function wpmtst_l10n_after_notification_fields() {

	// WPML
	if ( wpmtst_is_plugin_active( 'wpml' ) ) {
		echo '<p>';
		echo '<span class="dashicons dashicons-info icon-blue"></span>&nbsp;';
		printf( __( 'Translate these fields in <a href="%s">WPML String Translations</a>', 'strong-testimonials' ),
				admin_url( 'admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=strong-testimonials-notification' ) );
		echo '</p>';
	}

	// Polylang
	if ( wpmtst_is_plugin_active( 'polylang' ) ) {
		echo '<p>';
		echo '<span class="dashicons dashicons-info icon-blue"></span>&nbsp;';
		printf( __( 'Translate these fields in <a href="%s">Polylang String Translations</a>', 'strong-testimonials' ),
				admin_url( 'options-general.php?page=mlang&tab=strings&s&group=strong-testimonials-notification&paged=1' ) );

		echo '<p>';
	}

}
add_action( 'wpmtst_after_notification_fields', 'wpmtst_l10n_after_notification_fields' );

/**
 * Update strings when custom fields change.
 *
 * @param $fields
 */
function wpmtst_update_l10n_strings( $fields ) {

	// WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		wpmtst_form_fields_wpml( $fields );
	}

	// Polylang
	if ( defined( 'POLYLANG_VERSION' ) ) {
		wpmtst_form_fields_polylang( $fields );
	}

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
	if ( $name && isset( $contexts[$name] ) )
		return __( $contexts[$name], 'strong-testimonials' );

	return 'default';
}

/**
 * WPML
 *
 * @param $string
 * @param $context
 * @param $name
 *
 * @return mixed
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
 * @return bool|string
 */
function wpmtst_l10n_polylang( $string, $context, $name ) {
	if ( function_exists( 'pll__' ) ) {
		return pll__( $string );
	}
	return $string;
}

/**
 * WPGlobus
 *
 * @since 2.26.2
 */
function wpmtst_l10n_wpglobus() {
	add_filter( 'wpmtst_the_content', array( 'WPGlobus_Filters', 'filter__text' ), 0 );
	add_filter( 'wpmtst_get_the_excerpt', array( 'WPGlobus_Filters', 'filter__text' ), 0 );
}

/**
 * Add our translation filters.
 */
function wpmtst_l10n_filters() {
	// WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		remove_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );
		add_filter( 'wpmtst_l10n', 'wpmtst_l10n_wpml', 10, 3 );
		add_filter( 'wpmtst_l10n_cats', 'wpmtst_wpml_translate_object_ids', 10, 2 );
		add_filter( 'get_term', 'wpmtst_wpml_get_term', 10, 2 );
	}

	// Polylang
	if ( defined( 'POLYLANG_VERSION' ) ) {
		remove_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );
		add_filter( 'wpmtst_l10n', 'wpmtst_l10n_polylang', 20, 3 );
		// TODO handle cat IDs like WPML
	}

	// WPGlobus
	if ( defined( 'WPGLOBUS_VERSION' ) ) {
		remove_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );
		wpmtst_l10n_wpglobus();
	}
}
add_action( 'plugins_loaded', 'wpmtst_l10n_filters' );

/**
 * Find the equivalent term ID in the current language.
 *
 * @since 2.2.3
 *
 * @param $term
 * @param $tax
 * @return mixed
 */
function wpmtst_wpml_get_term( $term, $tax ) {
	if ( 'wpm-testimonial-category' == $tax ) {
		$term->term_id = apply_filters( 'wpmtst_wpml_translate_object_ids', $term->term_id );
	}

	return $term;
}

/**
 * Returns the translated object ID (post_type or term) or original if missing
 *
 * @param $object_id integer|string|array The ID/s of the objects to check and return
 * @param object|string $type object type: post, page, {custom post type name}, nav_menu, nav_menu_item, category, tag etc.
 * @return string|array of object ids
 */
function wpmtst_wpml_translate_object_ids( $object_id, $type = 'wpm-testimonial-category' ) {

	// if array
	if ( is_array( $object_id ) ) {
		$translated_object_ids = array();
		foreach ( $object_id as $id ) {
			$translated_object_ids[] = apply_filters( 'wpml_object_id', $id, $type, true );
		}
		return $translated_object_ids;
	}
	// if string
	elseif ( is_string( $object_id ) ) {
		// check if we have a comma separated ID string
		$is_comma_separated = strpos( $object_id,"," );

		if ( $is_comma_separated !== FALSE ) {
			// explode the comma to create an array of IDs
			$object_id     = explode( ',', $object_id );

			$translated_object_ids = array();
			foreach ( $object_id as $id ) {
				$translated_object_ids[] = apply_filters ( 'wpml_object_id', $id, $type, true );
			}

			// make sure the output is a comma separated string (the same way it came in!)
			return implode ( ',', $translated_object_ids );
		}
		// if we don't find a comma in the string then this is a single ID
		else {
			return apply_filters( 'wpml_object_id', intval( $object_id ), $type, true );
		}
	}
	// if int
	else {
		return apply_filters( 'wpml_object_id', $object_id, $type, true );
	}
}

/**
 * ----------------------------------------
 * WPML
 * ----------------------------------------
 */

/**
 * Add form fields to WPML String Translation.
 *
 * @param $fields
 */
function wpmtst_form_fields_wpml( $fields ) {
	// Reverse field order to match the form.
	$wpml = $fields;
	krsort( $wpml );
	foreach ( $wpml as $field ) {
		$name    = $field['name'] . ' : ';
		$context = 'strong-testimonials-form-fields';

		/* Translators: A form field on the String Translation screen. */
		if ( isset( $field['after'] ) ) {
			do_action( 'wpml_register_single_string', $context, $name . __( 'after', 'strong-testimonials' ), $field['after'] );
		}

		if ( isset( $field['before'] ) ) {
			do_action( 'wpml_register_single_string', $context, $name . __( 'before', 'strong-testimonials' ), $field['before'] );
		}

		if ( isset( $field['placeholder'] ) ) {
			do_action( 'wpml_register_single_string', $context, $name . __( 'placeholder', 'strong-testimonials' ), $field['placeholder'] );
		}

		if ( isset( $field['label'] ) ) {
			do_action( 'wpml_register_single_string', $context, $name . __( 'label', 'strong-testimonials' ), $field['label'] );
		}

		if ( isset( $field['text'] ) ) {
			do_action( 'wpml_register_single_string', $context, $name . __( 'text', 'strong-testimonials' ), $field['text'] );
		}

		if ( isset( $field['default_form_value'] ) ) {
			do_action( 'wpml_register_single_string', $context, $name . __( 'default form value', 'strong-testimonials' ), $field['default_form_value'] );
		}

		if ( isset( $field['default_display_value'] ) ) {
			do_action( 'wpml_register_single_string', $context, $name . __( 'default display value', 'strong-testimonials' ), $field['default_display_value'] );
		}
	}
}

/**
 * Add form messages to WPML String Translation.
 *
 * @param $fields
 */
function wpmtst_form_messages_wpml( $fields ) {
	// Reverse field order to match the form.
	$wpml = $fields;
	krsort( $wpml );
	foreach ( $wpml as $key => $field ) {

		// Method 1 -- To also add the admin label to the String Translation list:
		//$name = $key . ' : ';
		//do_action( 'wpml_register_single_string', $domain, $name . __( 'text', 'strong-testimonials' ), $field['text'] );
		//do_action( 'wpml_register_single_string', $domain, $name . __( 'label on admin screen', 'strong-testimonials' ), $field['description'] );

		// Method 2 -- To use the plugin's translation for the admin label:
		// We can translate here because the description was localized when added.
		do_action( 'wpml_register_single_string', 'strong-testimonials-form-messages', __( $field['description'], 'strong-testimonials' ), $field['text'] );
	}
}

/**
 * Add form notification messages to WPML String Translation.
 *
 * @param $options
 */
function wpmtst_form_options_wpml( $options ) {
	$context = 'strong-testimonials-notification';
	do_action( 'wpml_register_single_string', $context, __( 'Email message', 'strong-testimonials' ), $options['email_message'] );
	do_action( 'wpml_register_single_string', $context, __( 'Email subject', 'strong-testimonials' ), $options['email_subject'] );
}

/**
 * Add "Read more" link text to WPML String Translation.
 *
 * @since 2.11.17
 *
 * @param $options
 */
function wpmtst_readmore_wpml( $options ) {
	$context = 'strong-testimonials-read-more';
	/* Translators: %s is the View ID. */
	do_action( 'wpml_register_single_string', $context, sprintf( 'View %s : Read more (testimonial)', $options['id'] ), $options['more_post_text'] );
	do_action( 'wpml_register_single_string', $context, sprintf( 'View %s : Read more (page or post)', $options['id'] ), $options['more_page_text'] );
}


/**
 * ----------------------------------------
 * POLYLANG
 * ----------------------------------------
 */

/*
	pll_register_string($name, $string, $group, $multiline);
	'$name' => (required) name provided for sorting convenience (ex: ‘myplugin’)
	'$string' => (required) the string to translate
	'$group' => (optional) the group in which the string is registered, defaults to ‘polylang’
	'$multiline' => (optional) if set to true, the translation text field will be multiline, defaults to false
*/

function wpmtst_form_fields_polylang( $fields ) {
	if ( function_exists( 'pll_register_string' ) ) {
		foreach ( $fields as $field ) {
			$name = $field['name'] . ' : ';
			$context = 'strong-testimonials-form-fields';
			pll_register_string( $name . __( 'after', 'strong-testimonials' ), $field['after'], $context );
			pll_register_string( $name . __( 'before', 'strong-testimonials' ), $field['before'], $context );
			pll_register_string( $name . __( 'placeholder', 'strong-testimonials' ), $field['placeholder'], $context );
			pll_register_string( $name . __( 'label', 'strong-testimonials' ), $field['label'], $context );
			pll_register_string( $name . __( 'default form value', 'strong-testimonials' ), $field['default_form_value'], $context );
			pll_register_string( $name . __( 'default display value', 'strong-testimonials' ), $field['default_display_value'], $context );
		}
	}
}

function wpmtst_form_messages_polylang( $fields ) {
	if ( function_exists( 'pll_register_string' ) ) {
		foreach ( $fields as $key => $field ) {
			pll_register_string( __( $field['description'], 'strong-testimonials' ), $field['text'], 'strong-testimonials-form-messages' );
		}
	}
}

function wpmtst_form_options_polylang( $options ) {
	if ( function_exists( 'pll_register_string' ) ) {
		$context = 'strong-testimonials-notification';
		pll_register_string( __( 'Email subject', 'strong-testimonials' ), $options['email_subject'], $context );
		pll_register_string( __( 'Email message', 'strong-testimonials' ), $options['email_message'], $context, true );
	}
}

/**
 * "Read more" link text
 *
 * @since 2.11.17
 * @param $options
 */
function wpmtst_readmore_polylang( $options ) {
	if ( function_exists( 'pll_register_string' ) ) {
		$context = 'strong-testimonials-notification';
		pll_register_string( sprintf( __( 'View %s : Read more (testimonial)', 'strong-testimonials' ), $options['id'] ), $options['more_post_text'], $context );
		pll_register_string( sprintf( __( 'View %s : Read more (page or post)', 'strong-testimonials' ), $options['id'] ), $options['more_page_text'], $context );
	}
}