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
			admin_url( 'admin.php?page=mlang_strings&group=strong-testimonials-form-messages&paged=1' ) );
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
				admin_url( 'admin.php?page=mlang_strings&group=strong-testimonials-notification&paged=1' ) );
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

	/*
	 * Polylang - nothing to do
	 *
	 * Polylang does not store the source string, only the translated string.
	 * The source strings are added only when needed:
	 * for the Strings Translations screen on the 'load-languages_page_mlang_strings' hook.
	 */

	/* WP Globus - nothing to do */
}
add_action( 'wpmtst_fields_updated', 'wpmtst_update_l10n_strings' );

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
 * ----------------------------------------
 * WPML
 * ----------------------------------------
 */

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
 * Load custom style for WPML.
 *
 * @since 1.21.0
 */
function wpmtst_admin_scripts_wpml() {
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		$plugin_version = get_option( 'wpmtst_plugin_version' );
		wp_enqueue_style( 'wpmtst-admin-style-wpml', WPMTST_ADMIN_URL . 'css/wpml.css', array(), $plugin_version );
	}
}
add_action( 'admin_head-wpml-string-translation/menu/string-translation.php', 'wpmtst_admin_scripts_wpml' );
add_action( 'admin_head-edit-tags.php', 'wpmtst_admin_scripts_wpml' );

/**
 * Register form field strings.
 *
 * @param $fields
 */
function wpmtst_form_fields_wpml( $fields ) {
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
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
}

/**
 * Register form option strings.
 *
 * @param $options
 */
function wpmtst_form_wpml( $options ) {
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		// Form messages
		$context = 'strong-testimonials-form-messages';
		// Reverse field order to match the form.
		$wpml = $options['messages'];
		krsort( $wpml );
		foreach ( $wpml as $key => $field ) {
			// We can translate here because the description was localized when added.
			do_action( 'wpml_register_single_string', $context, __( $field['description'], 'strong-testimonials' ), $field['text'] );
		}

		// Form notification
		$context = 'strong-testimonials-notification';
		do_action( 'wpml_register_single_string', $context, __( 'Email message', 'strong-testimonials' ), $options['email_message'] );
		do_action( 'wpml_register_single_string', $context, __( 'Email subject', 'strong-testimonials' ), $options['email_subject'] );
	}
}

/**
 * Register "Read more" link text.
 *
 * @since 2.11.17
 *
 * @param $options
 */
function wpmtst_readmore_wpml( $options ) {
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		$context = 'strong-testimonials-read-more';

		/* Translators: %s is the View ID. */
		$string = sprintf( __( 'View %s : Read more (testimonial)', 'strong-testimonials' ), $options['id'] );
		do_action( 'wpml_register_single_string', $context, $string, $options['more_post_text'] );

		$string = sprintf( __( 'View %s : Read more (page or post)', 'strong-testimonials' ), $options['id'] );
		do_action( 'wpml_register_single_string', $context, $string, $options['more_page_text'] );
	}
}


/**
 * ----------------------------------------
 * POLYLANG
 * ----------------------------------------
 *
 * pll_register_string($name, $string, $group, $multiline);
 *   '$name' => (required) name provided for sorting convenience (ex: ‘myplugin’)
 *   '$string' => (required) the string to translate
 *   '$group' => (optional) the group in which the string is registered, defaults to ‘polylang’
 *   '$multiline' => (optional) if set to true, the translation text field will be multiline, defaults to false
 */

/**
 * Register form field strings.
 *
 * @param $fields
 */
function wpmtst_form_fields_polylang( $fields ) {
	if ( function_exists( 'pll_register_string' ) ) {
		$context = 'strong-testimonials-form-fields';
		foreach ( $fields as $field ) {
			$name = $field['name'] . ' : ';
			if ( isset( $field['after'] ) && $field['after'] ) {
				pll_register_string( $name . __( 'after', 'strong-testimonials' ), $field['after'], $context );
			}
			if ( isset( $field['before'] ) && $field['before'] ) {
				pll_register_string( $name . __( 'before', 'strong-testimonials' ), $field['before'], $context );
			}
			if ( isset( $field['placeholder'] ) && $field['placeholder'] ) {
				pll_register_string( $name . __( 'placeholder', 'strong-testimonials' ), $field['placeholder'], $context );
			}
			if ( isset( $field['label'] ) && $field['label'] ) {
				pll_register_string( $name . __( 'label', 'strong-testimonials' ), $field['label'], $context );
			}
			if ( isset( $field['default_form_value'] ) && $field['default_form_value'] ) {
				pll_register_string( $name . __( 'default form value', 'strong-testimonials' ), $field['default_form_value'], $context );
			}
			if ( isset( $field['default_display_value'] ) && $field['default_display_value'] ) {
				pll_register_string( $name . __( 'default display value', 'strong-testimonials' ), $field['default_display_value'], $context );
			}
		}
	}
}

/**
 * Register form strings.
 *
 * @param $options
 */
function wpmtst_form_polylang( $options ) {
	if ( function_exists( 'pll_register_string' ) ) {
		// Form messages
		$context = 'strong-testimonials-form-messages';
		foreach ( $options['messages'] as $key => $field ) {
			pll_register_string( __( $field['description'], 'strong-testimonials' ), $field['text'], $context );
		}

		// Form notification
		$context = 'strong-testimonials-notification';
		pll_register_string( __( 'Email subject', 'strong-testimonials' ), $options['email_subject'], $context );
		pll_register_string( __( 'Email message', 'strong-testimonials' ), $options['email_message'], $context, true );
	}
}

/**
 * Register "Read more" link text.
 *
 * @since 2.11.17
 */
function wpmtst_readmore_polylang() {
	if ( function_exists( 'pll_register_string' ) ) {
		$context = 'strong-testimonials-views';

		$views = wpmtst_get_views();
		if ( ! $views ) {
			return;
		}

		foreach ( $views as $key => $view ) {
			$view_data = unserialize( $view['value'] );
			if ( ! is_array( $view_data ) ) {
				continue;
			}

			pll_register_string( sprintf( __( 'View %s : Read more (testimonial)', 'strong-testimonials', false ),
				$view['id'] ), $view_data['more_post_text'], $context );

			pll_register_string( sprintf( __( 'View %s : Read more (page or post)', 'strong-testimonials', false ),
				$view['id'] ), $view_data['more_page_text'], $context );
		}
	}
}

/**
 * Polylang conditional loading
 *
 * @since 1.21.0
 */
function wpmtst_admin_polylang() {
	if ( defined( 'POLYLANG_VERSION' ) ) {
		// Minor improvements to list table style
		$plugin_version = get_option( 'wpmtst_plugin_version' );
		wp_enqueue_style( 'wpmtst-admin-style-polylang', WPMTST_ADMIN_URL . 'css/polylang.css', array(), $plugin_version );

		// Register strings for translation
		wpmtst_form_fields_polylang( wpmtst_get_all_fields() );
		//$form_options = get_option( 'wpmtst_form_options' );
		//wpmtst_form_messages_polylang( $form_options['messages'] );
		//wpmtst_form_options_polylang( $form_options );
		wpmtst_form_polylang( get_option( 'wpmtst_form_options' ) );
		wpmtst_readmore_polylang();
	}
}
add_action( 'load-languages_page_mlang_strings', 'wpmtst_admin_polylang' );
