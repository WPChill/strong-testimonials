<?php
/**
 * Debug Logger
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_Debug' ) ) :

class Strong_Debug {

	public $filename;

	public $action;

	public function __construct() {

		$this->add_actions();

		$this->filename = 'strong-debug.log';

		$this->action = 'strong_debug_log';

	}

	public function add_actions() {
		add_action( 'init', array( $this, 'init' ), 20 );
		add_action( 'shutdown', array( $this, 'on_shutdown' ) );
	}

	public function init() {
		if ( $this->is_enabled() ) {
			add_action( $this->action, array( $this, 'debug_log' ), 10, 3 );
		}
	}

	private function is_enabled() {
		$options    = get_option( 'wpmtst_options' );
		$is_enabled = ( isset( $options['debug_log'] ) && $options['debug_log'] );

		return apply_filters( $this->action, $is_enabled );
	}

	public function get_log_file_path() {
		return $this->get_log_file_base( 'basedir' ) . $this->filename;
	}

	public function get_log_file_url() {
		return $this->get_log_file_base( 'baseurl' ) . $this->filename;
	}

	public function get_log_file_base( $base = 'basedir' ) {
		$upload_dir = wp_upload_dir();

		if ( isset( $upload_dir[ $base ] ) ) {
			$log_file_base = $upload_dir[ $base ];
		} else {
			$log_file_base = $upload_dir['basedir'];
		}

		return trailingslashit( $log_file_base );
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
		if ( get_transient( $this->action ) ) {
			do_action( $this->action, str_repeat( '-', 50 ), '', current_filter() );
			delete_transient( $this->action );
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

		error_log( $entry, 3, $this->get_log_file_path() );

		set_transient( $this->action, true );

	}

}

endif;
