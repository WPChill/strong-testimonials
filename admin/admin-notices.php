<?php
/**
 * Admin notices
 */


/**
 * Print admin notices.
 *
 * @since 2.24.0
 */
function wpmtst_admin_notices() {
	$notices = get_option( 'wpmtst_admin_notices' );
	if ( $notices ) {
		foreach ( $notices as $key ) {
			$message = apply_filters( 'wpmtst_admin_notice', $key );
			if ( $message ) {
				echo $message;
			}
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
 */
function wpmtst_add_admin_notice( $key ) {
	$notices = get_option( 'wpmtst_admin_notices', array() );
	$notices[] = $key;
	update_option( 'wpmtst_admin_notices', array_unique( $notices ) );
}

