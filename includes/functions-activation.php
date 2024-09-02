<?php

/**
 * Add tables for Views.
 *
 * @since 1.21.0
 */
function wpmtst_update_tables() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . 'strong_views';

	$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL,
			value text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

	$wpdb->show_errors();
	$result = dbDelta( $sql );
	$wpdb->hide_errors();

	if ( $wpdb->last_error ) {
		deactivate_plugins( 'strong-testimonials/strong-testimonials.php' );
		$message  = '<p><span style="color: #CD0000;">';
		$message .= esc_html__( 'An error occurred:', 'strong-testimonials' ) . '</span>&nbsp;';
		$message .= esc_html__( 'The plugin has been deactivated.', 'strong-testimonials' );
		$message .= '</p>';
		$message .= '<p><code>' . $wpdb->last_error . '</code></p>';
		// translators: %s is the URL to the WordPress dashboard.
		$message .= '<p>' . sprintf( __( '<a href="%s">Go back to Dashboard</a>', 'strong-testimonials' ), esc_url( admin_url() ) ) . '</p>';

		wp_die( sprintf( '<div class="error strong-view-error">%s</div>', wp_kses_post( $message ) ) );
	}

	update_option( 'wpmtst_db_version', WPMST()->get_db_version(), 'no' );
}
