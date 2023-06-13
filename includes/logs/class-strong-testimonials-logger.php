<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Strong_File_Logging Class
 *
 * A general use class for logging events and errors.
 * 
 * @since 2.51.7
 */
class Strong_File_Logging {

	public $is_writable        = true;
	private $filename          = '';
	private $file              = '';
	public $is_logging_enabled = false;
	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 2.51.7
	 *
	 * @return object The Strong_File_Logging object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Strong_File_Logging ) ) {
			self::$instance = new Strong_File_Logging();
		}

		return self::$instance;

	}

	public function __construct() {

		$this->is_logging_enabled();
		add_filter( 'wpmtst_submenu_pages', array( $this, 'add_submenu' ) );
		add_action( 'admin_init', array( $this, 'handle_actions' ) );
	}

	/**
	 * Add submenu page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 * 
	 * @since 2.51.7
	 */
	public function add_submenu( $pages ) {
		$pages[90] = $this->get_submenu();
		return $pages;
	}

	/**
	 * Return submenu page parameters.
	 *
	 * @return array
	 * 
	 * @since 2.51.7
	 */
	public function get_submenu() {
		return array(
			'page_title' => esc_html__( 'Logs', 'strong-testimonials' ),
			'menu_title' => esc_html__( 'Logs', 'strong-testimonials' ),
			'capability' => 'strong_testimonials_options',
			'menu_slug'  => 'strong-testimonials-logs',
			'function'   => array( $this, 'status_logs_file' ),
		);
	}

	/**
	 * Check if logging is enabled
	 * 
	 * @since 2.51.7
	 */
	public function is_logging_enabled() {
		$options = get_option( 'strong_testimonials_advanced_settings' );
		if ( isset( $options['debug_log'] ) && 'on' === $options['debug_log'] ) {
			$this->is_logging_enabled = true;
		}
	}

	/**
	 * Return the log file handle.
	 *
	 * @param string $filename Filename to get the handle for.
	 * @return string
	 * 
	 * @since 2.51.7
	 */
	public static function get_log_file_handle( $filename ) {
		return substr( $filename, 0, strlen( $filename ) > 48 ? strlen( $filename ) - 48 : strlen( $filename ) - 4 );
	}

	/**
	 * Scan log files
	 *
	 * @param [type] $path
	 * @return void
	 * 
	 * @since 2.51.7
	 */
	public static function scan_log_files( $path ) {
		return @scandir( $path ); // @codingStandardsIgnoreLine
	}

	/**
	 * Get all log files in the log directory.
	 *
	 * @return array
	 * 
	 * @since 2.51.7
	 */
	public function get_log_files() {
		$files  = self::scan_log_files( WPMTST_LOGS );
		$result = array();

		if ( ! empty( $files ) ) {
			foreach ( $files as $key => $value ) {
				if ( ! in_array( $value, array( '.', '..' ), true ) ) {
					if ( ! is_dir( $value ) && strstr( $value, '.log' ) ) {
						$result[ sanitize_title( $value ) ] = $value;
					} elseif ( is_dir( WPMTST_LOGS . $value ) ) {
						$subfiles = self::scan_log_files( WPMTST_LOGS . $value );
						if ( ! empty( $subfiles ) ) {
							foreach ( $subfiles as $key => $subvalue ) {
								if ( ! in_array( $subvalue, array( '.', '..' ), true ) ) {
									if ( ! is_dir( $subvalue ) && strstr( $subvalue, '.log' ) ) {
										$result[ sanitize_title( $subvalue ) ] = $value . '/' . $subvalue;
									}
								}
							}
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Handles the delete log and download log actions.
	 * 
	 * @since 2.51.7
	 */
	public function handle_actions() {

		if( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) &&  'wpm-testimonial' === $_GET['post_type'] &&  'strong-testimonials-logs' === $_GET['page']){

			if ( ! empty( $_REQUEST['st_log_remove'] ) ) { // phpcs:ignore input var ok, CSRF ok.
				$this->remove_log();
			}

			if ( ! empty( $_REQUEST['st_log_download'] ) ) { // phpcs:ignore input var ok, CSRF ok.
				$this->download_log();
			}
		}
	}

	/**
	 * Show the log page contents for file log handler.
	 * 
	 * @since 2.51.7
	 */
	public function status_logs_file() {
		$logs = $this->get_log_files();

		if ( ! empty( $_REQUEST['log_file'] ) && isset( $logs[ sanitize_title( wp_unslash( $_REQUEST['log_file'] ) ) ] ) ) { // phpcs:ignore input var ok, CSRF ok.
			$viewed_log = $logs[ sanitize_title( wp_unslash( $_REQUEST['log_file'] ) ) ]; // phpcs:ignore input var ok, CSRF ok.
		} elseif ( ! empty( $logs ) ) {
			$viewed_log = current( $logs );
		}

		$handle = ! empty( $viewed_log ) ? self::get_log_file_handle( $viewed_log ) : '';

		include_once __DIR__ . '/html-strong-testimonials-logs-viewer.php';
	}

	/**
	 * Remove/delete the chosen file and it's directory.
	 * 
	 * @since 2.51.7
	 */
	public function remove_log() {

		if( !isset( $_REQUEST['st_log_remove'] ) || !isset( $_REQUEST['subdir'] ) ){
			wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'strong-testimonials' ) );
		}

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( wp_unslash( $_REQUEST['_wpnonce'] ), 'remove_log' ) ) { // phpcs:ignore input var ok, sanitization ok.
			wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'strong-testimonials' ) );
		}

		if ( ! empty( $_REQUEST['st_log_remove'] ) ) {  // phpcs:ignore input var ok.
			$this->remove( wp_unslash( $_REQUEST['st_log_remove'] ), wp_unslash( $_REQUEST['subdir'] ) ); // phpcs:ignore input var ok, sanitization ok.
		}

		wp_safe_redirect( esc_url_raw( admin_url( 'edit.php?post_type=wpm-testimonial&page=strong-testimonials-logs' ) ) );
		exit();
	}

	/**
	 * Remove/delete the chosen file and it's directory.
	 *
	 * @param string $handle Log handle.
	 *
	 * @return bool
	 * 
	 * @since 2.51.7
	 */
	public function remove( $handle, $subdir ) {
		$removed = false;
		$logs    = $this->get_log_files();
		$handle  = sanitize_title( $handle );
		$subdir  = sanitize_title( $subdir );

		if ( isset( $logs[ $handle ] ) && $logs[ $handle ] ) {
			$file = realpath( trailingslashit( WPMTST_LOGS ) . $logs[ $handle ] );
			if ( 0 === stripos( $file, realpath( trailingslashit( WPMTST_LOGS ) ) ) && is_file( $file ) && is_writable( $file ) ) { // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
				$removed = unlink( $file ); // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_unlink
				if ( $subdir != '' ) {
					rmdir( dirname( $file ) );
				}
			}
		}
		return $removed;
	}

	/**
	 * Download the chosen file.
	 * 
	 * @since 2.51.7
	 */
	public function download_log() {
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( wp_unslash( $_REQUEST['_wpnonce'] ), 'download_log' ) ) { // phpcs:ignore input var ok, sanitization ok.
			wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'strong-testimonials' ) );
		}

		if ( ! empty( $_REQUEST['st_log_download'] ) && ! empty( $_REQUEST['subdir'] ) ) {  // phpcs:ignore input var ok.
			$this->download( wp_unslash( $_REQUEST['st_log_download'] ), wp_unslash( $_REQUEST['subdir'] ) ); // phpcs:ignore input var ok, sanitization ok.
		}

	}

	/**
	 * Download the chosen file.
	 *
	 * @param string $handle Log handle.
	 *
	 * @return void
	 * 
	 * @since 2.51.7
	 */
	public function download( $handle, $subdir ) {
		$logs   = $this->get_log_files();
		$handle = sanitize_title( $handle );
		$subdir = sanitize_title( $subdir );
		if ( isset( $logs[ $handle ] ) && $logs[ $handle ] ) {
			$file = realpath( trailingslashit( WPMTST_LOGS ) . $logs[ $handle ] );
			if ( 0 === stripos( $file, realpath( trailingslashit( WPMTST_LOGS ) ) ) && is_file( $file ) && is_writable( $file ) ) { // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
				header( 'Content-Description: File Transfer' );
				header( 'Content-Type: application/octet-stream' );
				header( 'Content-Disposition: attachment; filename="' . basename( $file ) . '"' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate' );
				header( 'Pragma: public' );
				header( 'Content-Length: ' . filesize( $file ) );
				ob_clean();
				flush();
				readfile( $file );
				exit();
			}
		}

	}

	/**
	 * Sets up the log file if it is writable
	 *
	 * @return void
	 * 
	 * @since 2.51.7
	 */
	public function setup_log_file( $importer ) {
		$basedir = trailingslashit( WPMTST_LOGS ) . $importer;
		if ( ! is_dir( $basedir ) ) {
			mkdir( $basedir, 0777, true );
		}
		$this->filename = wp_hash( home_url( '/' ) ) . '-' . $importer . '-st_debug.log';
		$this->file     = $basedir . '/' . $this->filename;
		if ( ! is_writeable( WPMTST_LOGS ) ) {
			$this->is_writable = false;
		}

	}

	/**
	 * Retrieve the log data
	 *
	 * @return string
	 * 
	 * @since 2.51.7
	 */
	public function get_file_contents() {
		return $this->get_file();
	}

	/**
	 * Log message to file
	 *
	 * @return void
	 * 
	 * @since 2.51.7
	 */
	public function log_to_file( $message = '' ) {
		$message = wp_date( 'd-n-Y H:i:s' ) . ' - ' . $message . "\r\n";
		$this->write_to_log( $message );

	}

	/**
	 * Retrieve the file data is written to
	 *
	 * @return string
	 * 
	 * @since 2.51.7
	 */
	protected function get_file() {

		$file = '';

		if ( @file_exists( $this->file ) ) {

			if ( ! is_writeable( $this->file ) ) {
				$this->is_writable = false;
			}

			$file = @file_get_contents( $this->file );
		} else {
			@file_put_contents( $this->file, '' );
			@chmod( $this->file, 0664 );

		}

		return $file;
	}

	/**
	 * Write the log message
	 *
	 * @return void
	 * 
	 * @since 2.51.7
	 */
	protected function write_to_log( $message = '' ) {
		$file  = $this->get_file();
		$file .= $message;
		@file_put_contents( $this->file, $file );
	}

	/**
	 * Return the location of the log file that Strong_File_Logging will use.
	 *
	 * @return string
	 * 
	 * @since 2.51.7
	 */
	public function get_log_file_path() {
		return $this->file;
	}

}

$GLOBALS['strong_logs'] = Strong_File_Logging::get_instance();


/**
 * Logs a message to the debug log file
 *
 * @param string $message
 * @global $strong_logs Strong_File_Logging Object
 * @return void
 * 
 * @since 2.51.7
 */
function ST_debug_log( $importer = '', $message = '' ) {
	global $strong_logs;
	if ( $strong_logs->is_logging_enabled ) {
		$strong_logs->setup_log_file( $importer );
		$strong_logs->log_to_file( $message );
	}

}
