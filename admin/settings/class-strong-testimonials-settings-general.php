<?php
/**
 * Class Strong_Testimonials_Settings_General
 */
class Strong_Testimonials_Settings_General {

	const TAB_NAME = 'general';

	const OPTION_NAME = 'wpmtst_options';

	const GROUP_NAME = 'wpmtst-settings-group';

	/**
	 * Strong_Testimonials_Settings_General constructor.
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
		add_action( 'wpmtst_settings_tabs', array( __CLASS__, 'register_tab' ), 1, 2 );
		add_filter( 'wpmtst_settings_callbacks', array( __CLASS__, 'register_settings_page' ) );
	}

	/**
	 * Register settings tab.
	 *
	 * @param $active_tab
	 * @param $url
	 */
	public static function register_tab( $active_tab, $url ) {
		printf(
			'<a href="%s" class="nav-tab %s">%s</a>',
			esc_url( add_query_arg( 'tab', self::TAB_NAME, $url ) ),
			esc_attr( self::TAB_NAME === $active_tab ? 'nav-tab-active' : '' ),
			esc_html_x( 'General', 'adjective', 'strong-testimonials' )
		);
	}

	/**
	 * Register settings.
	 */
	public static function register_settings() {
		register_setting( self::GROUP_NAME, self::OPTION_NAME, array( __CLASS__, 'sanitize_options' ) );
	}

	/**
	 * Register settings page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public static function register_settings_page( $pages ) {
		$pages[ self::TAB_NAME ] = array( __CLASS__, 'settings_page' );
		return $pages;
	}

	/**
	 * Print settings page.
	 */
	public static function settings_page() {
		settings_fields( self::GROUP_NAME );
		include WPMTST_ADMIN . 'settings/partials/general.php';
	}

	/**
	 * Sanitize settings.
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public static function sanitize_options( $input ) {
		$input['embed_width']       = $input['embed_width'] ? (int) sanitize_text_field( $input['embed_width'] ) : '';
		$input['nofollow']          = wpmtst_sanitize_checkbox( $input, 'nofollow' );
		$input['noopener']          = wpmtst_sanitize_checkbox( $input, 'noopener' );
		$input['noreferrer']        = wpmtst_sanitize_checkbox( $input, 'noreferrer' );
		$input['disable_rewrite']   = wpmtst_sanitize_checkbox( $input, 'disable_rewrite' );
		$input['pending_indicator'] = wpmtst_sanitize_checkbox( $input, 'pending_indicator' );
		$input['remove_whitespace'] = wpmtst_sanitize_checkbox( $input, 'remove_whitespace' );
		/* @todo : delete commented line. For the moment let it be */
		/*$input['reorder']                 = wpmtst_sanitize_checkbox( $input, 'reorder' );*/
		$input['scrolltop']               = wpmtst_sanitize_checkbox( $input, 'scrolltop' );
		$input['scrolltop_offset']        = (int) sanitize_text_field( $input['scrolltop_offset'] );
		$input['support_comments']        = wpmtst_sanitize_checkbox( $input, 'support_comments' );
		$input['support_custom_fields']   = wpmtst_sanitize_checkbox( $input, 'support_custom_fields' );
		$input['single_testimonial_slug'] = sanitize_text_field( $input['single_testimonial_slug'] );
		$input['lazyload']                = wpmtst_sanitize_checkbox( $input, 'lazyload' );
		$input['no_lazyload_plugin']      = wpmtst_sanitize_checkbox( $input, 'no_lazyload_plugin' );
		$input['touch_enabled']           = wpmtst_sanitize_checkbox( $input, 'touch_enabled' );
		$input['disable_upsells']         = wpmtst_sanitize_checkbox( $input, 'disable_upsells' );

		return apply_filters( 'wpmtst_sanitize_general_options', $input );
	}
}

Strong_Testimonials_Settings_General::init();
