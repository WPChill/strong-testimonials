<?php

class Strong_Testimonials_Advanced_Settings {
	const TAB_NAME    = 'Advanced Settings';
	const OPTION_NAME = 'strong_testimonials_advanced_settings';
	const GROUP_NAME  = 'wpmtst-advanced-settings-group';

	public static function init() {
		add_action( 'wpmtst_register_settings', array( __CLASS__, 'register_settings' ) );
		add_action( 'wpmtst_settings_tabs', array( __CLASS__, 'register_tab' ), 90, 2 );
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
			esc_html__( 'Advanced Settings', 'strong-testimonials' )
		);
	}

	/**
	 * Register settings.
	 */
	public static function register_settings() {
		register_setting( self::GROUP_NAME, self::OPTION_NAME );
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
		include plugin_dir_path( __FILE__ ) . 'partials/advanced-settings.php';
	}
}
Strong_Testimonials_Advanced_Settings::init();
