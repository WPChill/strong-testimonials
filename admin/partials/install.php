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
}
register_activation_hook( __FILE__, 'wpmtst_plugin_activation' );

/**
 * @since 2.4.0
 */
function wpmtst_welcome_screen_activate() {
	set_transient( 'wpmtst_welcome_screen_activation_redirect', true, 30 );
}
register_activation_hook( __FILE__, 'wpmtst_welcome_screen_activate' );

/**
 * @since 2.4.0
 */
function wpmtst_welcome_screen_do_activation_redirect() {
	// Bail if no activation redirect
	if ( ! get_transient( 'wpmtst_welcome_screen_activation_redirect' ) ) {
		return;
	}

	// Delete the redirect transient
	delete_transient( 'wpmtst_welcome_screen_activation_redirect' );

	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}

	wp_safe_redirect( admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-guide') );

}
add_action( 'admin_init', 'wpmtst_welcome_screen_do_activation_redirect' );

/**
 * Plugin deactivation
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
	if ( $result )
		WPMST()->log( $result, __FUNCTION__ );

	update_option( 'wpmtst_db_version', WPMST()->get_db_version() );
}

/**
 * Update tables.
 *
 * @since 1.21.0 Checking for new table version.
 * @since 2.4.0  Checking for missing table.
 */
function wpmtst_update_db_check() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'strong_views';
	if ( get_option( 'wpmtst_db_version' ) != WPMST()->get_db_version()
		|| $wpdb->get_var( "SHOW TABLES LIKE '$table_name'") != $table_name )
	{
		wpmtst_update_tables();
	}
}
add_action( 'plugins_loaded', 'wpmtst_update_db_check' );
