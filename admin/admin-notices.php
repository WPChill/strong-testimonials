<?php
/**
 * Admin notices
 */


/**
 * Dismiss persistent notices.
 *
 * @since 2.29.0
 */
function wpmtst_dismiss_notice_ajax() {
	if ( ! isset( $_POST['key'] ) || ! $_POST['key'] ) {
		echo 0;
		exit;
	}

	check_ajax_referer( 'wpmtst-admin', 'nonce' );
	wpmtst_delete_admin_notice( $_POST['key'] );
	exit;
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
        $message = apply_filters( 'wpmtst_admin_notice', '', $key );
        if ( $message ) {
            echo $message;
        }
	    if ( ! $notice['persist'] ) {
		    wpmtst_delete_admin_notice( $key );
	    }
    }
}
add_action( 'admin_notices', 'wpmtst_admin_notices' );


/**
 * Return specific admin notice text.
 *
 * @since 2.28.5
 * @param string $html
 * @param $key
 *
 * @return string
 */
function wpmtst_admin_notice_text( $html = '', $key, $persist = false ) {

	switch ( $key ) {
		case 'defaults-restored' :
			ob_start();
			?>
			<div class="wpmtst notice notice-success is-dismissible" data-key="<?php esc_attr_e( $key ); ?>">
				<p>
					<?php _e( 'Defaults restored.', 'strong-testimonials' ); ?>
				</p>
			</div>
			<?php
			$html = ob_get_clean();
			break;

		case 'fields-saved' :
			ob_start();
			?>
			<div class="wpmtst notice notice-success is-dismissible" data-key="<?php esc_attr_e( $key ); ?>">
				<p>
					<?php _e( 'Fields saved.', 'strong-testimonials' ); ?>
				</p>
			</div>
			<?php
			$html = ob_get_clean();
			break;

		case 'changes-cancelled' :
			ob_start();
			?>
			<div class="wpmtst notice notice-success is-dismissible" data-key="<?php esc_attr_e( $key ); ?>">
				<p>
					<?php _e( 'Changes cancelled.', 'strong-testimonials' ); ?>
				</p>
			</div>
			<?php
			$html = ob_get_clean();
			break;

		case 'captcha-options-changed' :
			$tags          = array( 'a' => array( 'class' => array(), 'href' => array() ) );
			$settings_url  = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form#captcha-section' );
			$settings_link = sprintf( wp_kses( __( '<a href="%s">%s</a>', 'strong-testimonials' ), $tags ), esc_url( $settings_url ), __( 'Go to settings', 'strong-testimonials' ) );

			ob_start();
			?>
            <div class="wpmtst notice notice-warning is-dismissible" data-key="<?php esc_attr_e( $key ); ?>">
                <p>
					<?php _e( 'Captcha options have changed in <strong>Strong Testimonials</strong>.', 'strong-testimonials' ); ?>
					<?php echo $settings_link; ?>
                </p>
            </div>
			<?php
			$html = ob_get_clean();
			break;

		default :
			// nothing
	}

	return $html;
}
add_filter( 'wpmtst_admin_notice', 'wpmtst_admin_notice_text', 10, 2 );


/**
 * Add admin notice to queue.
 *
 * @since 2.24.0
 *
 * @param $key
 * @param $persist
 */
function wpmtst_add_admin_notice( $key, $persist = false ) {
	$notices = get_option( 'wpmtst_admin_notices', array() );
	$notices[ $key ] = array( 'persist' => $persist );
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
	unset( $notices[ $key ] );
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
// TODO Move to main class
function wpmtst_updated_option( $option, $old_value, $value ) {
	if ( 'wpmtst_' == substr( $option, 0, 7 ) ) {
		do_action( 'wpmtst_check_config' );
	}
}
add_action( 'updated_option', 'wpmtst_updated_option', 10, 3 );


/**
 * Automatically dismiss specific notices when settings are saved.
 *
 * @since 2.29.0
 */
function wpmtst_auto_dismiss_notices() {
    $notices = get_option( 'wpmtst_admin_notices', array() );
    if ( isset( $notices['captcha-options-changed'] ) ) {
        unset( $notices['captcha-options-changed'] );
        update_option( 'wpmtst_admin_notices', $notices );
    }
}
add_action( 'wpmtst_check_config', 'wpmtst_auto_dismiss_notices', 10, 3 );


/**
 * Store configuration error.
 *
 * @since 2.24.0
 * @param $key
 */
// TODO Move to main class
function wpmtst_add_config_error( $key ) {
	$errors = get_option( 'wpmtst_config_errors', array() );
	$errors[] = $key;
	update_option( 'wpmtst_config_errors', array_unique( $errors ) );

	wpmtst_add_admin_notice( array( $key => array( 'persist' => true ) ) );
}


/**
 * Delete configuration error.
 *
 * @since 2.24.0
 * @param $key
 */
// TODO Move to main class
function wpmtst_delete_config_error( $key ) {
	$errors = get_option( 'wpmtst_config_errors', array() );
	$errors = array_diff( $errors, array ( $key ) );
	update_option( 'wpmtst_config_errors', $errors );

	wpmtst_delete_admin_notice( $key );
}
