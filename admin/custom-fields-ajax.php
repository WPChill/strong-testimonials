<?php
/**
 * Ajax Functions
 */


/**
 * [Add New Field] Ajax receiver
 */
function wpmtst_add_field_function() {

	if ( ! current_user_can( 'manage_options' ) ) {
	    wp_die();
	}

	check_ajax_referer( 'wpmtst-admin', 'security' );

	// when adding, leave Name empty so it will be populated from Label
	$empty_field = array(
		'name'         => 'new_field',
		'name_mutable' => 1,
		'record_type'  => 'custom',
		'input_type'   => 'text',
		'label'        => esc_html__( 'New Field', 'strong-testimonials' ),
		'show_label'   => 1,
	);
	echo wpmtst_show_field( intval( $_REQUEST['nextKey'] ), $empty_field, true );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_add_field', 'wpmtst_add_field_function' );


/**
 * [Add New Field 2] Ajax receiver
 */
function wpmtst_add_field_2_function() {

	if ( ! current_user_can( 'manage_options' ) ) {
	    wp_die();
	}

	check_ajax_referer( 'wpmtst-admin', 'security' );

	$new_field_type  = sanitize_text_field( $_REQUEST['fieldType'] );
	$new_field_class = sanitize_text_field( $_REQUEST['fieldClass'] );
	$fields          = apply_filters( 'wpmtst_fields', get_option( 'wpmtst_fields' ) );

	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	echo wpmtst_show_field_secondary( intval( $_REQUEST['nextKey'] ), $empty_field );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_add_field_2', 'wpmtst_add_field_2_function' );


/**
 * [Add New Field 3] Ajax receiver
 */
function wpmtst_add_field_3_function() {

	if ( ! current_user_can( 'manage_options' ) ) {
	    wp_die();
	}

	check_ajax_referer( 'wpmtst-admin', 'security' );

	$new_field_type  = sanitize_text_field( $_REQUEST['fieldType'] );
	$new_field_class = sanitize_text_field( $_REQUEST['fieldClass'] );
	$fields          = apply_filters( 'wpmtst_fields', get_option( 'wpmtst_fields' ) );

	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	echo wpmtst_show_field_hidden( intval( $_REQUEST['nextKey'] ), $empty_field );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_add_field_3', 'wpmtst_add_field_3_function' );


/**
 * [Add New Field 4] Ajax receiver
 */
function wpmtst_add_field_4_function() {

	if ( ! current_user_can( 'manage_options' ) ) {
	    add_filter( 'show_admin_bar', '__return_false' );
	}

	check_ajax_referer( 'wpmtst-admin', 'security' );

	$new_field_type  = sanitize_text_field( $_REQUEST['fieldType'] );
	$new_field_class = sanitize_text_field( $_REQUEST['fieldClass'] );
	$fields          = apply_filters( 'wpmtst_fields', get_option( 'wpmtst_fields' ) );
	$empty_field     = array();
	if ( isset( $fields['field_types'][$new_field_class][$new_field_type] ) ) {
		$empty_field = array_merge(
			$fields['field_types'][ $new_field_class ][ $new_field_type ],
			array( 'record_type' => $new_field_class )
		);
	}
	echo wpmtst_show_field_admin_table( intval( $_REQUEST['nextKey'] ), $empty_field );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_add_field_4', 'wpmtst_add_field_4_function' );


/**
 * Return the category count.
 */
function wpmtst_ajax_cat_count() {

	if ( ! current_user_can( 'manage_options' ) ) {
	    wp_die();
	}
	
	check_ajax_referer( 'wpmtst-admin', 'security' );

	echo wpmtst_get_cat_count();
	wp_die();
}
add_action( 'wp_ajax_wpmtst_get_cat_count', 'wpmtst_ajax_cat_count' );