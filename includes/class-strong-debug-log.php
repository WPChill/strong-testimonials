<?php
/**
 * Debug Logger
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_Debug' ) ) :

class Strong_Debug {

	public function __construct() {
		$this->define_constants();
		$this->add_actions();
	}

	public function define_constants() {
		$upload_dir = wp_upload_dir();
		$log        = 'strong-debug.log';

		if ( ! defined( 'WPMTST_DEBUG_LOG_PATH' ) )
			define( 'WPMTST_DEBUG_LOG_PATH', trailingslashit( $upload_dir['basedir'] ) . $log );

		if ( ! defined( 'WPMTST_DEBUG_LOG_URL' ) )
			define( 'WPMTST_DEBUG_LOG_URL', trailingslashit( $upload_dir['baseurl'] ) . $log );
	}

	public function add_actions() {
		add_action( 'init', array( $this, 'init' ), 20 );
	}

	public function init() {
		$options = get_option( 'wpmtst_options' );
		if ( ( isset( $options['debug_log'] ) && $options['debug_log'] )
			|| apply_filters( 'strong_debug_log', false ) )
		{
			add_action( 'strong_debug_log', array( $this, 'debug_log' ), 10, 3 );
			add_action( 'shutdown', array( $this, 'on_shutdown' ) );
		}
	}

	/**
	 * Debug log entries.
	 *
	 * @param $entry
	 * @param string $label
	 * @param string $function
	 */
	public function debug_log( $entry, $label = '', $function = '' ) {
		$this->log( $entry, $label, $function );
	}

	/**
	 * Disable debug logging on shutdown.
	 */
	public function on_shutdown() {
		if ( get_transient( 'strong_debug_log' ) ) {
			do_action( 'strong_debug_log', str_repeat( '-', 50 ), '', current_filter() );
			delete_transient( 'strong_debug_log' );
		}
	}

	/**
	 * Generic logging function.
	 *
	 * @param array|string $data
	 * @param string $label
	 * @param string $function
	 */
	public function log( $data, $label = '', $function = '' )  {

		$entry = '[' . date('Y-m-d H:i:s') . ']';

		if ( wp_doing_ajax() ) {
			$entry .= ' | DOING_AJAX';
		}

		if ( $function ) {
			$entry .= ' | FN: ' . $function;
		}

		$entry .= ' | ';

		if ( $label ) {
			$entry .= $label . ' = ';
		}

		if ( is_array( $data ) || is_object( $data ) ) {
			$entry .= print_r( $data, true );
		} elseif ( is_bool( $data ) ) {
			$entry .= ( $entry ? 'true' : 'false' ) . PHP_EOL;
		} else {
			$entry .= $data . PHP_EOL;
		}

		$entry .= PHP_EOL;

		error_log( $entry, 3, WPMTST_DEBUG_LOG_PATH );

		set_transient( 'strong_debug_log', true );
	}

}

endif;
