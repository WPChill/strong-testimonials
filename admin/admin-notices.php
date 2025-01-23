<?php
/**
 * Backwards compatibility - Admin notices
*/

/**
 * Dismiss persistent notices.
 *
 * @since 2.29.0
 */
function wpmtst_dismiss_notice_ajax() {
	if ( ! isset( $_POST['key'] ) || ! $_POST['key'] ) {
		echo 0;
		wp_die();
	}

	check_ajax_referer( 'wpmtst-admin', 'nonce' );
	wpmtst_delete_admin_notice( sanitize_text_field( wp_unslash( $_POST['key'] ) ) );
	wp_die();
}

add_action( 'wp_ajax_wpmtst_dismiss_notice', 'wpmtst_dismiss_notice_ajax' );

/**
 * Print admin notices.
 *
 * @since 2.24.0
 */
function wpmtst_admin_notices() {
	$notices = get_option( 'wpmtst_admin_notices' );
	if ( ! $notices ) {
		return;
	}

	foreach ( $notices as $key => $notice ) {
		$message = apply_filters( 'wpmtst_' . $key . '_notice', '' );
		if ( $message ) {
			echo wp_kses_post( $message );
		}
		if ( ! $notice['persist'] ) {
			wpmtst_delete_admin_notice( $key );
		}
	}
}
add_action( 'admin_notices', 'wpmtst_admin_notices' );

/**
 * Add admin notice to queue.
 *
 * @since 2.24.0
 *
 * @param $key
 * @param $persist
 */
function wpmtst_add_admin_notice( $key, $persist = false ) {
	$notices         = get_option( 'wpmtst_admin_notices', array() );
	$notices[ $key ] = array( 'persist' => $persist );
	update_option( 'wpmtst_admin_notices', $notices, 'no' );
}


/**
 * Delete admin notice from queue.
 *
 * @since 2.24.0
 *
 * @param $key
 */
function wpmtst_delete_admin_notice( $key ) {
	$notices = get_option( 'wpmtst_admin_notices', array() );
	if ( isset( $notices[ $key ] ) ) {
		unset( $notices[ $key ] );
		update_option( 'wpmtst_admin_notices', $notices, 'no' );
	}
}
