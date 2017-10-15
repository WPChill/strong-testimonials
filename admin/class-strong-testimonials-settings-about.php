<?php
class Strong_Testimonials_Settings_About {

	public function __construct() {}

	public static function init() {
		self::add_actions();
	}

	public static function add_actions() {
		add_filter( 'wpmtst_submenu_pages', array( __CLASS__, 'add_submenu' ) );
	}

	public static function add_submenu( $pages ) {
		$pages[40] = self::get_submenu();
		return $pages;
	}

	public static function get_submenu() {
		return array(
			'page_title' => __( 'About' ),
	        'menu_title' => __( 'About' ),
		    'capability' => 'strong_testimonials_about',
			'menu_slug'  => 'about-strong-testimonials',
			'function'   => 'wpmtst_about_page',
		);
	}

}

Strong_Testimonials_Settings_About::init();
