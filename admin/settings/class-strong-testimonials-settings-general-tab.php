<?php
/**
 * Class Strong_Testimonials_Settings_General_Tab
 */
class Strong_Testimonials_Settings_General_Tab {

	/**
	 * Strong_Testimonials_Settings_General_Tab constructor.
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
		register_setting( 'wpmtst-settings-group', 'wpmtst_options', array( __CLASS__, 'sanitize_options' ) );
	}

	/**
     * Register settings page.
     *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public static function register_settings_page( $pages ) {
	    $pages['general'] = array( __CLASS__, 'settings_page' );
	    return $pages;
	}

	/**
	 * Print settings page.
	 */
	public static function settings_page() {
		settings_fields( 'wpmtst-settings-group' );
		include( WPMTST_ADMIN . 'partials/settings/general.php' );
	}

	/**
	 * Sanitize settings.
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public static function sanitize_options( $input ) {
		$input['email_log_level']       = isset( $input['email_log_level'] ) ? (int) $input['email_log_level'] : 1;
		$input['embed_width']           = $input['embed_width'] ? (int) sanitize_text_field( $input['embed_width'] ) : '';
		$input['load_font_awesome']     = wpmtst_sanitize_checkbox( $input, 'load_font_awesome' );
		$input['nofollow']              = wpmtst_sanitize_checkbox( $input, 'nofollow' );
		$input['pending_indicator']     = wpmtst_sanitize_checkbox( $input, 'pending_indicator' );
		$input['remove_whitespace']     = wpmtst_sanitize_checkbox( $input, 'remove_whitespace' );
		$input['reorder']               = wpmtst_sanitize_checkbox( $input, 'reorder' );
		$input['scrolltop']             = wpmtst_sanitize_checkbox( $input, 'scrolltop' );
		$input['scrolltop_offset']      = (int) sanitize_text_field( $input['scrolltop_offset'] );
		$input['support_comments']      = wpmtst_sanitize_checkbox( $input, 'support_comments' );
		$input['support_custom_fields'] = wpmtst_sanitize_checkbox( $input, 'support_custom_fields' );
		$input['no_lazyload']           = wpmtst_sanitize_checkbox( $input, 'no_lazyload' );

		return $input;
	}

}

Strong_Testimonials_Settings_General_Tab::init();
