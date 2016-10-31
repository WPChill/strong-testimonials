<?php
/**
 * Ajax Functions
 */


/**
 * [Add New Field] Ajax receiver
 */
function wpmtst_add_field_function() {
	check_ajax_referer( 'wpmtst-admin', 'security', false );

	$new_key = intval( $_REQUEST['nextKey'] );
	// when adding, leave Name empty so it will be populated from Label
	$empty_field = array(
		'record_type' => 'custom',
		'input_type'  => '',
		'label'       => 'New Field',
		'show_label'  => 1,
	);
	$new_field = wpmtst_show_field( $new_key, $empty_field, true );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field', 'wpmtst_add_field_function' );


/**
 * [Add New Field 2] Ajax receiver
 */
function wpmtst_add_field_2_function() {
	check_ajax_referer( 'wpmtst-admin', 'security', false );

	$new_key         = intval( $_REQUEST['nextKey'] );
	$new_field_type  = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields          = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_secondary( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_2', 'wpmtst_add_field_2_function' );


/**
 * [Add New Field 3] Ajax receiver
 */
function wpmtst_add_field_3_function() {
	check_ajax_referer( 'wpmtst-admin', 'security', false );

	$new_key         = intval( $_REQUEST['nextKey'] );
	$new_field_type  = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields          = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_hidden( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_3', 'wpmtst_add_field_3_function' );


/**
 * [Add New Field 4] Ajax receiver
 */
function wpmtst_add_field_4_function() {
	check_ajax_referer( 'wpmtst-admin', 'security', false );

	$new_key         = intval( $_REQUEST['nextKey'] );
	$new_field_type  = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields          = get_option( 'wpmtst_fields' );
	$empty_field     = array();
	if ( isset( $fields['field_types'][$new_field_class][$new_field_type] ) ) {
		$empty_field = array_merge(
			$fields['field_types'][ $new_field_class ][ $new_field_type ],
			array( 'record_type' => $new_field_class )
		);
	}
	$new_field = wpmtst_show_field_admin_table( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_4', 'wpmtst_add_field_4_function' );
