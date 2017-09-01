<?php
/**
 * Localization functions.
 *
 * @since 1.21.0
 */

/**
 * Add our translation filters.
 */
function wpmtst_l10n_filters() {

	/**
	 * WPML
	 */
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		// Translate
		remove_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );
		add_filter( 'wpmtst_l10n', 'wpmtst_l10n_wpml', 10, 3 );
		add_filter( 'wpmtst_l10n_cats', 'wpmtst_wpml_translate_object_ids', 10, 2 );
		add_filter( 'get_term', 'wpmtst_wpml_get_term', 10, 2 );

		// Update strings
		add_action( 'update_option_wpmtst_custom_forms', 'wpmtst_form_fields_wpml', 10, 2 );
		add_action( 'update_option_wpmtst_form_options', 'wpmtst_form_wpml', 10, 2 );
		add_action( 'wpmtst_view_saved', 'wpmtst_update_view_wpml' );

		// Help
		add_action( 'wpmtst_before_form_settings', 'wpmtst_help_link_wpml' );
		add_action( 'wpmtst_before_fields_settings', 'wpmtst_help_link_wpml' );
		add_action( 'wpmtst_after_notification_fields', 'wpmtst_help_link_wpml' );
	}

	/**
	 * Polylang
	 */
	if ( defined( 'POLYLANG_VERSION' ) ) {
		// Translate
		remove_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );
		add_filter( 'wpmtst_l10n', 'wpmtst_l10n_polylang', 20, 3 );
		// TODO handle cat IDs like WPML

		// Help
		add_action( 'wpmtst_before_form_settings', 'wpmtst_help_link_polylang' );
		add_action( 'wpmtst_before_fields_settings', 'wpmtst_help_link_polylang' );
		add_action( 'wpmtst_after_notification_fields', 'wpmtst_help_link_polylang' );
	}

	/**
	 * WPGlobus
	 */
	if ( defined( 'WPGLOBUS_VERSION' ) ) {
		// Translate
		remove_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );
		add_filter( 'wpmtst_the_content', array( 'WPGlobus_Filters', 'filter__text' ), 0 );
		add_filter( 'wpmtst_get_the_excerpt', array( 'WPGlobus_Filters', 'filter__text' ), 0 );
	}

}
add_action( 'init', 'wpmtst_l10n_filters', 20 );
