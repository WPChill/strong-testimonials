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


/**
 * Delete admin notice from queue.
 *
 * @since 2.24.0
 *
 * @param $key
 */
function wpmtst_delete_admin_notice( $key ) {
	$notices = get_option( 'wpmtst_admin_notices', array() );
	$notices = array_diff( $notices, array ( $key ) );
	update_option( 'wpmtst_admin_notices', $notices );
}


/**
 * Check for configuration issues when options are updated.
 *
 * @since 2.24.0
 * @param $option
 * @param $old_value
 * @param $value
 */
function wpmtst_updated_option( $option, $old_value, $value ) {
	if ( 'wpmtst_' == substr( $option, 0, 7 ) ) {
		do_action( 'wpmtst_check_config' );
	}
}
add_action( 'updated_option', 'wpmtst_updated_option', 10, 3 );


/**
 * Store configuration error.
 *
 * @param $key
 */
function wpmtst_add_config_error( $key ) {
	$errors = get_option( 'wpmtst_config_errors', array() );
	$errors[] = $key;
	update_option( 'wpmtst_config_errors', array_unique( $errors ) );

	wpmtst_add_admin_notice( $key );
}


/**
 * Delete configuration error.
 *
 * @param $key
 */
function wpmtst_delete_config_error( $key ) {
	q2($key);
	$errors = get_option( 'wpmtst_config_errors', array() );
	$errors = array_diff( $errors, array ( $key ) );
	update_option( 'wpmtst_config_errors', $errors );
	//echo '<script>jQuery(".notice-error").remove();</script>';

	wpmtst_delete_admin_notice( $key );
}
