<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Strong_File_Logging Class
 *
 * A general use class for logging events and errors.
 *
 */
class Strong_File_Logging {

	public $is_writable = true;
	private $filename   = '';
	private $file       = '';

	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'setup_log_file' ), 0 );

	}

	/**
	 * Sets up the log file if it is writable
	 *
	 * @return void
	 */
	public function setup_log_file() {

		$upload_dir       = wp_upload_dir();
		$this->filename   = wp_hash( home_url( '/' ) ) . '-ST-debug.log';
		$this->file       = trailingslashit( $upload_dir['basedir'] ) . $this->filename;

		if ( ! is_writeable( $upload_dir['basedir'] ) ) {
			$this->is_writable = false;
		}

	}

	/**
	 * Retrieve the log data
	 *
	 * @return string
	 */
	public function get_file_contents() {
		return $this->get_file();
	}

	/**
	 * Log message to file
	 *
	 * @return void
	 */
	public function log_to_file( $message = '' ) {
		$message = date( 'Y-n-d H:i:s' ) . ' - ' . $message . "\r\n";
		$this->write_to_log( $message );

	}

	/**
	 * Retrieve the file data is written to
	 *
	 * @return string
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
	 */
	protected function write_to_log( $message = '' ) {
		$file = $this->get_file();
		$file .= $message;
		@file_put_contents( $this->file, $file );
	}

	/**
	 * Delete the log file or removes all contents in the log file if we cannot delete it
	 *
	 * @return void
	 */
	public function clear_log_file() {
		@unlink( $this->file );

		if ( file_exists( $this->file ) ) {

			// it's still there, so maybe server doesn't have delete rights
			chmod( $this->file, 0664 ); // Try to give the server delete rights
			@unlink( $this->file );

			// See if it's still there
			if ( @file_exists( $this->file ) ) {

				/*
				 * Remove all contents of the log file if we cannot delete it
				 */
				if ( is_writeable( $this->file ) ) {

					file_put_contents( $this->file, '' );

				} else {

					return false;

				}

			}

		}

		$this->file = '';
		return true;

	}

	/**
	 * Return the location of the log file that Strong_File_Logging will use.
	 *
	 * @return string
	 */
	public function get_log_file_path() {
		return $this->file;
	}

}

$GLOBALS['strong_logs'] = new Strong_File_Logging();


/**
 * Logs a message to the debug log file
 *
 *
 * @param string $message
 * @global $strong_logs Strong_File_Logging Object
 * @return void
 */
function ST_debug_log( $message = '', $force = false ) {
	global $strong_logs;

		$strong_logs->log_to_file( $message );

}

