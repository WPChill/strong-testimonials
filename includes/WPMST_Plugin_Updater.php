<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'WPMISSION_STORE_URL', 'http://edd-seller.dev' );

// the name of your product. This should match the download name in EDD exactly
define( 'WPM_ST_MF_ADDON', 'Multiple Forms Add-on' );

if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}


/**
 * Class WPMST_Plugin_Updater
 */
class WPMST_Plugin_Updater {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'plugin_updater' ), 0 ); // Don't change this priority
		add_action( 'admin_init', array( $this, 'activate_license' ) );
		add_action( 'admin_init', array( $this, 'deactivate_license' ) );
	}

	function plugin_updater() {
		if ( !defined( 'WPMST_MF_FILE' ) ) return;

		// TODO loop thru all licenses
		// retrieve our license key from the DB
		$license_key = trim( get_option( 'wpmst_mf_license_key' ) );
		$version     = get_option( 'wpmst_mf_plugin_version' );
		if ( !$license_key || !$version ) return;

		// setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( WPMISSION_STORE_URL, WPMST_MF_FILE, array(
				'version'   => $version,         // current installed version number
				'license'   => $license_key,     // license key
				'item_name' => WPM_ST_MF_ADDON,   // name of this plugin
				'author'    => 'Chris Dillon'    // author of this plugin
			)
		);

	}

	/**
	 * Activate a license key.
	 *
	 * @return bool
	 */
	function activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['wpmst_mf_license_activate'] ) ) {

			// run a quick security check
			if ( !check_admin_referer( 'wpmst_mf_nonce', 'wpmst_mf_nonce' ) )
				return false; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'wpmst_mf_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( WPM_ST_MF_ADDON ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( WPMISSION_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "valid" or "invalid"

			update_option( 'wpmst_mf_license_status', $license_data->license );

		}

		return true;
	}

	/**
	 * Deactivate a license key. This will also decrease the site count.
	 *
	 * @return bool
	 */
	function deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['edd_license_deactivate'] ) ) {

			// run a quick security check
			if ( !check_admin_referer( 'wpmst_mf_nonce', 'wpmst_mf_nonce' ) )
				return false; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'wpmst_mf_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( WPM_ST_MF_ADDON ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( WPMISSION_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' )
				delete_option( 'wpmst_mf_license_status' );

		}

		return true;
	}

}

new WPMST_Plugin_Updater();
