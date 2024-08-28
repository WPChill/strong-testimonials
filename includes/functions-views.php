<?php
/**
 * View Functions
 */

/**
 * Return the default view settings.
 *
 * @param bool $unfiltered
 * @since 2.30.5
 *
 * @return array
 */
function wpmtst_get_view_default( $unfiltered = false ) {
	$default = get_option( 'wpmtst_view_default' );
	if ( ! $unfiltered ) {
		$default = apply_filters( 'wpmtst_view_default', $default );
	}

	return $default;
}

/**
 * @return array|mixed|null|object
 */
function wpmtst_get_views() {
	global $wpdb;
	$wpdb->show_errors();
	$table_name = $wpdb->prefix . 'strong_views';
	$results    = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id ASC", ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$wpdb->hide_errors();
	if ( $wpdb->last_error ) {

		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		deactivate_plugins( 'strong-testimonials/strong-testimonials.php' );
		$message  = '<p><span style="color: #CD0000;">';
		$message .= esc_html__( 'An error occurred.', 'strong-testimonials' ) . '</span>&nbsp;';
		$message .= esc_html__( 'The plugin has been deactivated.', 'strong-testimonials' ) . '&nbsp;';
		// translators: %s is the URL to the WordPress dashboard.
		$message .= '<p>' . sprintf( __( '<a href="%s">Go back to Dashboard</a>', 'strong-testimonials' ), esc_url( admin_url() ) ) . '</p>';
		wp_die( sprintf( '<div class="error strong-view-error">%s</div>', wp_kses_post( $message ) ) );
	}

	return apply_filters( 'wpmtst_views_query_results', $results );
}

/**
 * @param $views
 *
 * @return mixed
 */
function wpmtst_unserialize_views( $views ) {
	foreach ( $views as $key => $view ) {
		$views[ $key ]['data'] = unserialize( $view['value'] );
	}

	return $views;
}

/**
 * @param $id
 *
 * @return array
 */
function wpmtst_get_view( $id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'strong_views';
	$row        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", (int) $id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

	return $row;
}

/**
 * Find the view for the single template.
 *
 * @return bool|array
 */
function wpmtst_find_single_template_view() {
	$views = wpmtst_get_views();
	/*
	 * [id] => 1
	 * [name] => TEST
	 * [value] => {serialized_array}
	 */

	foreach ( $views as $view ) {
		$view_data = maybe_unserialize( $view['value'] );
		if ( isset( $view_data['mode'] ) && 'single_template' === $view_data['mode'] ) {
			return $view_data;
		}
	}

	return false;
}
