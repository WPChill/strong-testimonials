<?php

namespace ElementorStrongTestimonials;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Strong_Testimonials_Elementor_Widget_Activation {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function include_widgets_files() {
		require_once WPMTST_INC . 'elementor/class-strong-testimonials-elementor-widget.php';
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function register_widgets() {
		$this->include_widgets_files();
		// Register Widgets
		if ( method_exists( \Elementor\Plugin::instance()->widgets_manager, 'register' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Strong_Testimonials_Elementor_Widget() );
		} else {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Strong_Testimonials_Elementor_Widget() );
		}
	}

	public function __construct() {

		// Register widgets
		if ( has_action( 'elementor/widgets/register' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		} else {
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
		}

		// Enqueue needed scripts for elementor Editor
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'strong_testimonials_elementor_enqueue_editor_scripts' ) );

		// Enqueue needed scripts and styles in Elementor preview
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'strong_testimonials_elementor_enqueue_scripts' ) );
		add_action( 'wp_ajax_strong_testimonials_elementor_ajax_search', array( $this, 'strong_testimonials_elementor_ajax_search' ) );
	}

	public function strong_testimonials_elementor_enqueue_editor_scripts() {

		wp_enqueue_script( 'strong-testimonials-elementor-editor', WPMTST_URL . 'admin/js/strong-testimonials-elementor-editor.js', null, WPMTST_VERSION, true );
		wp_localize_script(
			'strong-testimonials-elementor-editor',
			'strongAjax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_enqueue_script( 'st-selectize', WPMTST_URL . 'admin/js/selectize.js', null, WPMTST_VERSION, true );
		wp_enqueue_style( 'st-selectize-css', WPMTST_URL . 'admin/css/selectize.default.css' );
	}

	/**
	 * Enqueue scripts in Elementor preview
	 */
	public function strong_testimonials_elementor_enqueue_scripts() {
	}

	public function strong_testimonials_elementor_ajax_search() {

		if ( isset( $_POST['action'] ) && 'strong_testimonials_elementor_ajax_search' === $_POST['action'] ) {
			$galleries = wpmtst_unserialize_views( wpmtst_get_views() );

			wp_send_json_success( $galleries );
		} else {
			wp_send_json_error();
		}
		die();
	}
}

// Instantiate Plugin Class
Strong_Testimonials_Elementor_Widget_Activation::instance();
