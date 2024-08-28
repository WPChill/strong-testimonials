<?php
/**
 * Class Strong_Testimonials_Menu_Shortcodes
 */
class Strong_Testimonials_Menu_Shortcodes {

	/**
	 * Strong_Testimonials_Menu_Shortcodes constructor.
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
		add_filter( 'wpmtst_submenu_pages', array( __CLASS__, 'add_submenu' ) );
	}

	/**
	 * Add submenu page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public static function add_submenu( $pages ) {
		$pages[40] = self::get_submenu();
		return $pages;
	}

	/**
	 * Return submenu page parameters.
	 *
	 * @return array
	 */
	public static function get_submenu() {
		return array(
			'page_title' => apply_filters( 'wpmtst_shortcodes_page_title', esc_html__( 'Shortcodes', 'strong-testimonials' ) ),
			'menu_title' => apply_filters( 'wpmtst_shortcodes_menu_title', esc_html__( 'Shortcodes', 'strong-testimonials' ) ),
			'capability' => 'strong_testimonials_options',
			'menu_slug'  => 'testimonial-shortcodes',
			'function'   => array( 'Strong_Testimonials_Page_Shortcodes', 'render_page' ),
		);
	}
}

Strong_Testimonials_Menu_Shortcodes::init();
