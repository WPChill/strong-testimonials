<?php
/**
 * Function for managing the last sort order on the view list table per user.
 */

/**
 * Save
 */
function wpmtst_save_view_list_order() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$order   = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : '';
	$success = '';
	if ( in_array( $name, array( 'name', 'id' ), true ) ) {
		$success = update_user_meta( get_current_user_id(), 'strong_view_list_order', array( $name, $order ) );
	}
	echo esc_html( $success );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_save_view_list_order', 'wpmtst_save_view_list_order' );


/**
 * Fetch
 */
function wpmtst_fetch_view_list_order() {
	global $pagenow;

	if ( 'edit.php' === $pagenow
		&& isset( $_GET['post_type'] )
		&& 'wpm-testimonial' === $_GET['post_type']
		&& isset( $_GET['page'] )
		&& 'testimonial-views' === $_GET['page']
		&& ! isset( $_GET['orderby'] )
		&& ! isset( $_GET['action'] ) ) {
		$order = get_user_meta( get_current_user_id(), 'strong_view_list_order', true );
		if ( $order ) {
			$url = admin_url( "edit.php?post_type=wpm-testimonial&page=testimonial-views&orderby={$order[0]}&order={$order[1]}" );
			wp_redirect( $url );
			exit;
		}
	}
}
add_action( 'admin_init', 'wpmtst_fetch_view_list_order' );


/**
 * Clear
 */
function wpmtst_clear_view_list_order() {
	delete_user_meta( get_current_user_id(), 'strong_view_list_order' );
	$url = 'edit.php?post_type=wpm-testimonial&page=testimonial-views';
	wp_redirect( $url );
}
add_action( 'admin_post_clear-view-sort', 'wpmtst_clear_view_list_order' );
