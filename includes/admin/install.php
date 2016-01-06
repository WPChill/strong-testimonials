<?php
/**
 * Install Function
 *
 * @package     Strong_Testimonials
 * @since       1.18
 */

/**
 * Plugin activation
 */
function wpmtst_plugin_activation() {
	wpmtst_register_cpt();
	flush_rewrite_rules();
	wpmtst_update_tables();
	wpmtst_activate_about_page();
}
register_activation_hook( __FILE__, 'wpmtst_plugin_activation' );

/**
 * Activate the auto redirection of the about page on the next page load
 */
function wpmtst_activate_about_page() {
	set_transient( 'wpmtst_about_page_activated', 1, 300 );
}

/**
 * Plugin activation
 */
function wpmtst_plugin_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'wpmtst_plugin_deactivation' );

/**
 * Add tables for Views.
 *
 * @since 1.21.0
 */
function wpmtst_update_tables() {
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . 'strong_views';

	$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL,
			value text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

	$result = dbDelta( $sql );
	WPMST()->log( $result, __FUNCTION__, 'install.log' );

	update_option( 'wpmtst_db_version', WPMST()->get_db_version() );
}

/**
 * Update tables.
 *
 * @since 1.21.0
 */
function wpmtst_update_db_check() {
	if ( get_option( 'wpmtst_db_version' ) != WPMST()->get_db_version() ) {
		wpmtst_update_tables();
	}
}
add_action( 'plugins_loaded', 'wpmtst_update_db_check' );
