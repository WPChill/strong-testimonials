<?php
/**
 * Class Strong_Testimonials_Settings_Licenses_Tab
 */
class Strong_Testimonials_Settings_Licenses_Tab {

	/**
	 * Strong_Testimonials_Settings_Licenses_Tab constructor.
	 */
	public function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init() {
		self::add_actions();
	}

	/**
	 * Add actions and filters.
	 */
	public static function add_actions() {
	    add_action( 'wpmtst_register_settings', array( __CLASS__, 'register_settings' ) );
		add_filter( 'wpmtst_settings_callbacks', array( __CLASS__, 'register_settings_page' ) );
	}

	/**
	 * Register settings.
	 */
	public static function register_settings() {
		register_setting( 'wpmtst-license-group', 'wpmtst_addons', array( __CLASS__, 'sanitize_options' ) );
	}

	/**
	 * Register settings page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public static function register_settings_page( $pages ) {
		$pages['licenses'] = array( __CLASS__, 'settings_page' );
		return $pages;
	}

	/**
	 * Print settings page.
	 */
	public static function settings_page() {
		settings_fields( 'wpmtst-license-group' );
		include( WPMTST_ADMIN . 'partials/settings/licenses.php' );
	}

	/**
	 * Sanitize settings.
	 *
	 * @param $new_licenses
	 *
	 * @return array
	 */
	public static function sanitize_options( $new_licenses ) {
		$old_licenses = get_option( 'wpmtst_addons' );
		// Check existence. May have been erased by Reset plugin.
		if ( $old_licenses ) {
			foreach ( $new_licenses as $addon => $new_info ) {
				$old_license = isset( $old_licenses[ $addon ]['license'] ) ? $old_licenses[ $addon ]['license'] : '';
				if ( isset( $old_license['key'] ) && $old_license['key'] != $new_info['license']['key'] ) {
					// new license has been entered, so must reactivate
					unset( $new_licenses[ $addon ]['license']['status'] );
				}
			}
		}

		return $new_licenses;
	}

}

Strong_Testimonials_Settings_Licenses_Tab::init();
