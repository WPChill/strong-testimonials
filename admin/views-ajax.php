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
	$atts = array( 'template' => isset( $_REQUEST['template'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['template'] ) ) : 'default' );
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
	$new_key = isset( $_REQUEST['key'] ) ? absint( $_REQUEST['key'] ) : 0;
	$empty_field = array( 'field' => '', 'type' => 'text', 'class' => '' );
        $source = 'view[data]';
        if (isset($_REQUEST['source']) && !empty( $_REQUEST['source'] )) {
            $source = sanitize_text_field( wp_unslash( $_REQUEST['source'] ) );
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
	$key         = isset( $_REQUEST['key'] ) ? absint( $_REQUEST['key'] ) : 0;
	$field_name  = isset( $_REQUEST['fieldName'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fieldName'] ) ) : 'new_field';
	$type        = isset( $_REQUEST['fieldType'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fieldType'] ) ) : 'text';
	$empty_field = array( 'url' => '', 'link_text' => '', 'new_tab' => true );
        $source = 'view[data]';
        if (isset($_REQUEST['source']) && !empty($_REQUEST['source'])) {
            $source = sanitize_text_field( wp_unslash( $_REQUEST['source'] ) );
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
	$field = array( 'field' => isset( $_REQUEST['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ) : '' );
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
	$key = isset( $_REQUEST['key'] ) ? (int) sanitize_text_field( wp_unslash( $_REQUEST['key'] ) ) : 0;
	$empty_field = array( 'format' => '' );
        $source = 'view[data]';
        if ( isset( $_REQUEST['source'] ) && !empty( $_REQUEST['source'] ) ) {
            $source = sanitize_text_field( wp_unslash( $_REQUEST['source'] ) );
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
		$key = isset( $_REQUEST['key'] ) ? (int) sanitize_text_field( wp_unslash( $_REQUEST['key'] ) ) : 0;
        $field = array(
            'field'  => isset( $_REQUEST['fieldName'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fieldName'] ) ) : 'new_field',
            'type'   => isset( $_REQUEST['fieldType'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fieldType'] ) ) : 'text'
        );
        $empty_field = array( 'custom_label' => '', 'checked_value' => '', 'unchecked_value' => '');
        $source = 'view[data]';
        if (isset($_REQUEST['source']) && !empty($_REQUEST['source'])) {
			$source = sanitize_text_field( wp_unslash( $_REQUEST['source'] ) );
        }
	wpmtst_view_field_checkbox ( $key, $field, $empty_field, $source );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_view_add_field_checkbox', 'wpmtst_view_add_field_checkbox_function' );


/**
 * Fetch the view mode description.
 *
 * @since 2.22.0
 */
function wpmtst_view_get_mode_description() {
	$mode = isset( $_REQUEST['mode'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['mode'] ) ) : 'display';
	$options = get_option( 'wpmtst_view_options' );
	if ( isset( $options['mode'][ $mode ]['description'] ) ) {
		$description = $options['mode'][ $mode ]['description'];
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
	$preset = wpmtst_get_background_presets( isset( $_REQUEST['key'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['key'] ) ) : 0 );
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
	$options = Strong_Testimonials_Defaults::get_default_view();
	$breakpoints = $options['slideshow_settings']['breakpoints'];
	echo json_encode( $breakpoints );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_restore_default_breakpoints', 'wpmtst_restore_default_breakpoints_function' );
