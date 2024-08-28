<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Strong_Testimonials_Elementor_Check {

	/**
	 * Plugin Version
	 *
	 * @since 2.40.5
	 * @var string The plugin version.
	 */
	const VERSION = '2.40.5';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 2.40.5
	 * @var string Minimum Elementor version required to run the elementor block.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.4.5';

	/**
	 * Minimum PHP Version
	 *
	 * @since 2.40.5
	 * @var string Minimum PHP version required to run the elementor block.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 2.40.5
	 * @access public
	 */
	public function __construct() {

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		if ( has_action( 'elementor/widgets/register' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'remove_strong_testimonials_widget' ), 15 );
		} else {
			add_action(
				'elementor/widgets/widgets_registered',
				array(
					$this,
					'remove_strong_testimonials_widget',
				),
				15
			);
		}

		// Once we get here, We have passed all validation checks so we can safely include our elementor block activation
		require_once WPMTST_INC . 'elementor/class-strong-testimonials-elementor-widget-activation.php';
	}


	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 2.40.5
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		// translators: %1$s is the message stating that the Elementor widget requires a specific Elementor version.
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', sprintf( esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'strong-testimonials' ), '<strong>' . esc_html__( 'Strong Testimonials Elementor widget', 'strong-testimonials' ) . '</strong>', '<strong>' . esc_html__( 'Elementor', 'strong-testimonials' ) . '</strong>', self::MINIMUM_ELEMENTOR_VERSION ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		// translators: %1$s is the message stating that the Elementor widget requires a specific Elementor version.
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', sprintf( esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'strong-testimonials' ), '<strong>' . esc_html__( 'Strong Testimonials Elementor widget', 'strong-testimonials' ) . '</strong>', '<strong>' . esc_html__( 'PHP', 'strong-testimonials' ) . '</strong>', self::MINIMUM_PHP_VERSION ) );
	}

	/* Remove WordPress widget because we have a dedicated Elementor Widget */
	public function remove_strong_testimonials_widget( $widget_manager ) {
		if ( method_exists( $widget_manager, 'unregister' ) ) {
			$widget_manager->unregister( 'strong-testimonials-view-widget' );
		} else {
			$widget_manager->unregister_widget_type( 'strong-testimonials-view-widget' );
		}
	}
}

new Strong_Testimonials_Elementor_Check();
