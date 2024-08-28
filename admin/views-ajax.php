<?php
/**
 * Views Ajax Functions
 */


/**
 * Check for forced options.
 *
 * @since 1.25.0
 */
function wpmtst_force_check() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) {//phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$atts  = array( 'template' => isset( $_POST['template'] ) ? sanitize_text_field( wp_unslash( $_POST['template'] ) ) : 'default' );
	$force = WPMST()->templates->get_template_config( $atts, 'force', false );
	if ( $force ) {
		wp_send_json_success( (array) $force );
	}
	wp_send_json_error();
}
add_action( 'wp_ajax_wpmtst_force_check', 'wpmtst_force_check' );


/**
 * [Add New Field] Ajax receiver
 *
 * @since 1.21.0
 */
function wpmtst_view_add_field_function() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$new_key     = isset( $_POST['key'] ) ? absint( $_POST['key'] ) : 0;
	$empty_field = array(
		'field' => '',
		'type'  => 'text',
		'class' => '',
	);
		$source  = 'view[data]';
	if ( isset( $_POST['source'] ) && ! empty( $_POST['source'] ) ) {
		$source = sanitize_text_field( wp_unslash( $_POST['source'] ) );
	}
	wpmtst_view_field_inputs( $new_key, $empty_field, true, $source );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_view_add_field', 'wpmtst_view_add_field_function' );


/**
 * [Field Type: Link] Ajax receiver
 *
 * @since 1.21.0
 */
function wpmtst_view_add_field_link_function() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$key         = isset( $_POST['key'] ) ? absint( $_POST['key'] ) : 0;
	$field_name  = isset( $_POST['fieldName'] ) ? sanitize_text_field( wp_unslash( $_POST['fieldName'] ) ) : 'new_field';
	$type        = isset( $_POST['fieldType'] ) ? sanitize_text_field( wp_unslash( $_POST['fieldType'] ) ) : 'text';
	$empty_field = array(
		'url'       => '',
		'link_text' => '',
		'new_tab'   => true,
	);
		$source  = 'view[data]';
	if ( isset( $_POST['source'] ) && ! empty( $_POST['source'] ) ) {
		$source = sanitize_text_field( wp_unslash( $_POST['source'] ) );
	}
	wpmtst_view_field_link( $key, $field_name, $type, $empty_field, false, $source );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_link', 'wpmtst_view_add_field_link_function' );


/**
 * [Field name change] Ajax receiver
 *
 * @since 1.24.0
 */
function wpmtst_view_get_label_function() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$field = array( 'field' => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '' );
	$label = wpmtst_get_field_label( $field );
	echo esc_html( $label );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_view_get_label', 'wpmtst_view_get_label_function' );


/**
 * [Field Type: Date] Ajax receiver
 *
 * @since 1.21.0
 */
function wpmtst_view_add_field_date_function() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$key         = isset( $_POST['key'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['key'] ) ) : 0;
	$empty_field = array( 'format' => '' );
		$source  = 'view[data]';
	if ( isset( $_POST['source'] ) && ! empty( $_POST['source'] ) ) {
		$source = sanitize_text_field( wp_unslash( $_POST['source'] ) );
	}
	wpmtst_view_field_date( $key, $empty_field, false, $source );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_date', 'wpmtst_view_add_field_date_function' );

/**
 * [Field Type: Checkbox Value] Ajax receiver
 *
 * @since 2.40.4
 */
function wpmtst_view_add_field_checkbox_function() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

		$key         = isset( $_POST['key'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['key'] ) ) : 0;
		$field       = array(
			'field' => isset( $_POST['fieldName'] ) ? sanitize_text_field( wp_unslash( $_POST['fieldName'] ) ) : 'new_field',
			'type'  => isset( $_POST['fieldType'] ) ? sanitize_text_field( wp_unslash( $_POST['fieldType'] ) ) : 'text',
		);
		$empty_field = array(
			'custom_label'    => '',
			'checked_value'   => '',
			'unchecked_value' => '',
		);
		$source      = 'view[data]';
		if ( isset( $_POST['source'] ) && ! empty( $_POST['source'] ) ) {
			$source = sanitize_text_field( wp_unslash( $_POST['source'] ) );
		}
		wpmtst_view_field_checkbox( $key, $field, $empty_field, $source );
		wp_die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_checkbox', 'wpmtst_view_add_field_checkbox_function' );


/**
 * Fetch the view mode description.
 *
 * @since 2.22.0
 */
function wpmtst_view_get_mode_description() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$mode    = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : 'display';
	$options = get_option( 'wpmtst_view_options' );
	if ( isset( $options['mode'][ $mode ]['description'] ) ) {
		$description         = $options['mode'][ $mode ]['description'];
				$description = apply_filters( 'wpmtst_mode_description', $description, $mode );
				echo wp_kses_post( $description );
	}
	wp_die();
}
add_action( 'wp_ajax_wpmtst_view_get_mode_description', 'wpmtst_view_get_mode_description' );


/**
 * Get background color presets in View editor.
 */
function wpmtst_get_background_preset_colors() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$preset = wpmtst_get_background_presets( isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : 0 );
	echo json_encode( $preset );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_get_background_preset_colors', 'wpmtst_get_background_preset_colors' );


/**
 * [Restore Default Breakpoints] Ajax receiver.
 *
 * @since 2.32.2
 */
function wpmtst_restore_default_breakpoints_function() {

	if ( ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-admin-views-script-nonce' ) ) { //phpcs:ignore
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
		die();
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$options     = Strong_Testimonials_Defaults::get_default_view();
	$breakpoints = $options['slideshow_settings']['breakpoints'];
	echo json_encode( $breakpoints );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_restore_default_breakpoints', 'wpmtst_restore_default_breakpoints_function' );


/**
 * [Field Type: Category] Ajax receiver
 *
 * @since 3.1.8
 */
function wpmtst_view_add_field_category_type_select() {
	if ( ! isset( $_POST['nonce'] ) ) {
		// Nonce doesn't exist.
		wp_send_json_error( array( 'message' => __( 'Nonce does not exist.', 'strong-testimonials' ) ) );
	}
	check_ajax_referer( 'wpmtst-admin-views-script-nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient capabilities.', 'strong-testimonials' ) ) );
		die();
	}

	$key         = isset( $_POST['key'] ) ? absint( $_POST['key'] ) : 0;
	$field_name  = isset( $_POST['fieldName'] ) ? sanitize_text_field( wp_unslash( $_POST['fieldName'] ) ) : 'category';
	$type        = isset( $_POST['fieldType'] ) ? sanitize_text_field( wp_unslash( $_POST['fieldType'] ) ) : 'select';
	$empty_field = array(
		'url'       => '',
		'link_text' => '',
		'new_tab'   => true,
	);
	$source      = 'view[data]';

	wpmtst_view_field_category( $key, $field_name );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_category_type_select', 'wpmtst_view_add_field_category_type_select' );
