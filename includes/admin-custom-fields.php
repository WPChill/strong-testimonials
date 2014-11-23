<?php
/**
 * Strong Testimonials - Custom fields admin functions
 */

 
/*
 * Custom Fields page
 */
function wpmtst_settings_custom_fields() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	$options = get_option( 'wpmtst_options' );
	$field_options = get_option( 'wpmtst_fields' );
	$field_groups = $field_options['field_groups'];
	$current_field_group = $field_options['current_field_group'];  // "custom", only one for now
	$field_group = $field_groups[$current_field_group];

	$message_format = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';

	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_custom_fields_form' ) ) {

		if ( isset( $_POST['reset'] ) ) {

			// Undo changes
			$fields = $field_group['fields'];
			echo sprintf( $message_format, __( 'Changes undone.', 'strong-testimonials' ) );

		}
		elseif ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			// ----------------
			// 1.7 - soft restore from database
			// $fields = $field_options['field_groups']['default']['fields'];
			// $field_options['field_groups']['custom']['fields'] = $fields;
			// update_option( 'wpmtst_fields', $field_options );
			
			// 1.7.1 - hard restore from file
			include( WPMTST_INC . 'defaults.php' );
			update_option( 'wpmtst_fields', $default_fields );
			$fields = $default_fields['field_groups']['custom']['fields'];
			
			echo sprintf( $message_format, __( 'Defaults restored.', 'strong-testimonials' ) );

		}
		else {

			// Save changes
			$fields = array();
			$new_key = 0;
			foreach ( $_POST['fields'] as $key => $field ) {
				$field = array_merge( $field_options['field_base'], $field );

				// sanitize & validate
				$field['name']        = sanitize_text_field( $field['name'] );
				$field['label']       = sanitize_text_field( $field['label'] );
				$field['placeholder'] = sanitize_text_field( $field['placeholder'] );
				$field['before']      = sanitize_text_field( $field['before'] );
				$field['after']       = sanitize_text_field( $field['after'] );
				$field['required']    = $field['required'] ? 1 : 0;
				$field['admin_table'] = $field['admin_table'] ? 1 : 0;

				// add to fields array in display order
				$fields[$new_key++] = $field;

			}
			$field_options['field_groups']['custom']['fields'] = $fields;
			update_option( 'wpmtst_fields', $field_options );
			echo sprintf( $message_format, __( 'Fields saved.', 'strong-testimonials' ) );
		}

	}
	else {

		// Get current fields
		$fields = $field_group['fields'];

	}

	// ------------------
	// Custom Fields Form
	// ------------------
	echo '<div class="wrap wpmtst">' . "\n";
	echo '<h2>' . __( 'Fields', 'strong-testimonials' ) . '</h2>' . "\n";
	echo '<ul>' . "\n";
	echo '<li>' . __( 'Fields will appear in this order on the form.', 'strong-testimonials' ) . '</li>' . "\n";
	/* translators: %s is an icon. */
	echo '<li>' . sprintf( __( 'Sort by grabbing the %s icon.', 'strong-testimonials' ), '<span class="dashicons dashicons-menu"></span>' ) . '</li>';
	echo '<li>' . __( 'Click the field name to expand its options panel.', 'strong-testimonials' ) . '</li>' . "\n";
	echo '<li>' . "\n";
	echo '<a href="http://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/" target="_blank">' . _x( 'Full tutorial', 'link', 'strong-testimonials' ) .'</a>';
	echo ' | ' . "\n";
	echo '<a href="http://wordpress.org/support/plugin/strong-testimonials" target="_blank">' . _x( 'Plugin support', 'link', 'strong-testimonials' ) . '</a>';
	echo ' | ' . "\n";
	echo '<a href="http://www.wpmission.com/contact/" target="_blank">' . _x( 'Developer', 'contact link', 'strong-testimonials' ) . '</a>' . "\n";
	echo '</li>' . "\n";
	echo '</ul>' . "\n";
	
	echo '<!-- Custom Fields Form -->' . "\n";
	echo '<form id="wpmtst-custom-fields-form" method="post" action="">' . "\n";
	wp_nonce_field( 'wpmtst_custom_fields_form', 'wpmtst_form_submitted' ); 
	
	echo '<ul id="custom-field-list">' . "\n";
	
	foreach ( $fields as $key => $field ) {
		echo '<li id="field-' . $key . '">' . wpmtst_show_field( $key, $field, false ) . '</li>' . "\n";
	}
	
	echo '</ul>' . "\n";
	
	echo '<div id="add-field-bar">';
	echo '<input id="add-field" type="button" class="button-primary" name="add-field" value="' . __( 'Add New Field', 'strong-testimonials' ) . '" />';
	echo '</div>' . "\n";
	
	echo '<p class="submit">' . "\n";
	submit_button( '', 'primary', 'submit', false );
	submit_button( __( 'Undo Changes', 'strong-testimonials' ), 'secondary', 'reset', false );
	submit_button( __( 'Restore Defaults', 'strong-testimonials' ), 'secondary', 'restore-defaults', false );
	echo '</p>' . "\n";
	
	echo '</form><!-- Custom Fields -->' . "\n";
	echo '</div><!-- wrap -->' . "\n";
}


/*
 * Add a field to the form
 */
function wpmtst_show_field( $key, $field, $adding ) {
	$fields = get_option( 'wpmtst_fields' );
	$field_types = $fields['field_types'];
	$field_link = $field['label'] ? $field['label'] : ucwords( $field['name'] );
	$is_core = ( isset( $field['core'] ) && $field['core'] );

	// ------------
	// Field Header
	// ------------
	$html = '<div class="custom-field-header">' . "\n";
	$html .= '<span class="handle"><div class="dashicons dashicons-menu"></div></span>' . "\n";
	$html .= '<span class="link"><a class="field" href="#">' . $field_link . '</a></span>' . "\n";
	$html .= '</div>' . "\n";
	
	$html .= '<div class="custom-field">' . "\n";
	
	$html .= '<table class="field-table">' . "\n";
	
	// -----------
	// Field Label
	// -----------
	$html .= '<tr>' . "\n";
	$html .= '<th>' . _x( 'Label', 'noun', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";
	$html .= '<input type="text" class="first-field field-label" name="fields[' . $key . '][label]" value="' . $field['label'] . '" />' . "\n";
	$html .= '<span class="help">' . __( 'This appears on the form.', 'strong-testimonials' ) . '</span>' . "\n";
	$html .= '</td>' . "\n";
	$html .= '</td>' . "\n";
	
	// ----------
	// Field Name
	// ----------
	$html .= '<tr>' . "\n";
	$html .= '<th>' . _x( 'Name', 'noun', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( 'custom' == $field['record_type'] ) {
		// if adding, the field Name is blank so it can be populated from Label
		$html .= '<input type="text" class="field-name" name="fields['.$key.'][name]" value="' . ( isset( $field['name'] ) ? $field['name'] : '' ) . '" />' . "\n";
		$html .= '<span class="help field-name-help">' . __( 'Use only lowercase letters, numbers, and underscores.', 'strong-testimonials' ) . '</span>' . "\n";
	}
	else {
		$html .= '<input type="text" class="field-name" value="' . $field['name'] . '" disabled="disabled" />' . "\n";
		// disabled inputs are not posted so store the field name in a hidden input
		$html .= '<input type="hidden" name="fields[' . $key . '][name]" value="' . $field['name'] . '" />' . "\n";
	}
	$html .= '</td>' . "\n";
	$html .= '</td>' . "\n";
	
	// ---------------------------
	// Field Type (Post or Custom)
	// ---------------------------
	// If disabled, create <select> with single option
	// and add hidden input with current value.
	// Separate code! Readability trumps ultra-minor efficiency.
	
	$html .= '<tr>' . "\n";
	$html .= '<th>' . _x( 'Type', 'noun', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";
	
	// Restrict field choice to this record type
	// unless we're adding a new field.
	if ( $adding ) {
	
		$html .= '<select class="field-type new" name="fields[' . $key . '][input_type]" autocomplete="off">' . "\n";
	
		// start with a blank option with event trigger to update optgroups...
		$html .= '<option class="no-selection" value="none" name="none">&mdash;</option>' . "\n";
		
		// If pre-selecting a record type in event handler:
		/*
		if ( 'custom' == $field['record_type'] ) {
			// compare field *name*
			$selected = selected( $field['name'], $field_key, false );
		}
		elseif ( 'post' == $field['record_type'] {
			// compare field *type*
			$selected = selected( $field['input_type'], $field_key, false );
		}
		*/
		// ...then add $selected to <option>.
		
		// Post fields
		$html .= '<optgroup class="post" label="' . __( 'Post Fields', 'strong-testimonials' ) . '">' . "\n";
		foreach ( $field_types['post'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>' . "\n";
		}
		$html .= '</optgroup>' . "\n";
		
		// Custom fields
		$html .= '<optgroup class="custom" label="' . __( 'Custom Fields', 'strong-testimonials' ) . '">' . "\n";
		foreach ( $field_types['custom'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>' . "\n";
		}
		$html .= '</optgroup>' . "\n";
		
		$html .= '</select>' . "\n";

	}
	else {
	
		if ( 'post' == $field['record_type'] ) {
			// -----------
			// Post fields
			// -----------
			// Disable <select>. Display current value as only option.
			// Disabled inputs are not posted so store the value in hidden field.
			$html .= '<input type="hidden" name="fields[' . $key . '][input_type]" value="' . $field['name'] . '" />' . "\n";
			$html .= '<select id="current-field-type" class="field-type" disabled="disabled">' . "\n";
			foreach ( $field_types['post'] as $field_key => $field_parts ) {
				// compare field *name*
				if ( $field['name'] == $field_key )
					$html .= '<option value="' . $field_key . '" selected="selected">' . $field_parts['option_label'] . '</option>' . "\n";
			}
			$html .= '</select>' . "\n";
		}
		else {
			// -------------
			// Custom fields
			// -------------
			$html .= '<select class="field-type" name="fields[' . $key . '][input_type]" autocomplete="off">' . "\n";
			$html .= '<optgroup class="custom" label="Custom Fields">' . "\n";
			foreach ( $field_types['custom'] as $field_key => $field_parts ) {
				// compare field *type*
				$selected = selected( $field['input_type'], $field_key, false );
				$html .= '<option value="' . $field_key . '" ' . $selected . '>' . $field_parts['option_label'] . '</option>' . "\n";
			}
			$html .= '</optgroup>' . "\n";
			$html .= '</select>' . "\n";
		}
		
	} // adding
	$html .= '</td>' . "\n";
	
	if ( ! $adding ) {
		$html .= wpmtst_show_field_secondary( $key, $field );
		$html .= wpmtst_show_field_admin_table( $key, $field );
	}
	
	$html .= '</table>' . "\n";

	if ( ! $adding )
		$html .= wpmtst_show_field_hidden( $key, $field );
	
	// --------
	// Controls
	// --------
	$html .= '<div class="controls">' . "\n";
	if ( $adding || ! $is_core ) {
		$html .= '<span><a href="#" class="delete-field">' . __( 'Delete' ) . '</a></span>';
	}
	$html .= '<span class="close-field"><a href="#">' . _x( 'Close', 'verb', 'strong-testimonials' ) . '</a></span>';
	$html .= '</div>' . "\n";
	
	$html .= '</div><!-- .custom-field -->' . "\n";
	
	return $html;
}


/*
 * Create the secondary inputs for a new custom field.
 * Called after field type is chosen (Post or Custom).
 */
function wpmtst_show_field_secondary( $key, $field ) {
	// --------
	// Required
	// --------
	// Disable option if this is a core field like post_content.
	if ( isset( $field['core'] ) && $field['core'] )
		$disabled = ' disabled="disabled"';
	else
		$disabled = false;
		
	$html = '<tr>' . "\n";
	$html .= '<th>' . __( 'Required', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( $disabled ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][required]" value="' . $field['required'] . '" />' . "\n";
		$html .= '<input type="checkbox" ' . checked( $field['required'], true, false ) . $disabled . ' />' . "\n";
	}
	else {
		$html .= '<input type="checkbox" name="fields[' . $key . '][required]" ' . checked( $field['required'], true, false ) . ' />' . "\n";
	}
	$html .= '</td>' . "\n";
	$html .= '</td>' . "\n";
	
	// -----------
	// Placeholder
	// -----------
	if ( isset( $field['placeholder'] ) ) {
		$html .= '<tr>' . "\n";
		$html .= '<th>' . __( 'Placeholder', 'strong-testimonials' ) . '</th>' . "\n";
		$html .= '<td><input type="text" name="fields[' . $key . '][placeholder]" value="' . $field['placeholder'] . '" /></td>' . "\n";
		$html .= '</td>' . "\n";
	}
	
	// ------
	// Before
	// ------
	$html .= '<tr>' . "\n";
	$html .= '<th>' . __( 'Before', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td><input type="text" name="fields[' . $key . '][before]" value="' . $field['before'] . '" /></td>' . "\n";
	$html .= '</td>' . "\n";
	
	// -----
	// After
	// -----
	$html .= '<tr>' . "\n";
	$html .= '<th>' . __( 'After', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td><input type="text" name="fields[' . $key . '][after]" value="' . $field['after'] . '" /></td>' . "\n";
	$html .= '</td>' . "\n";
	
	return $html;
}


/*
 * Add type-specific [Admin Table] setting to form.
 */
function wpmtst_show_field_admin_table( $key, $field ) {
	// -------------------
	// Show in Admin Table
	// -------------------
	$html = '<tr class="field-admin-table">' . "\n";
	$html .= '<th>' . __( 'Admin Table', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( $field['admin_table_option'] ) {
		$html .= '<input type="checkbox" class="field-admin-table" name="fields[' . $key . '][admin_table]" ' . checked( $field['admin_table'], 1, false ) . ' />' . "\n";
	}
	else {
		$html .= '<input type="checkbox" ' . checked( $field['admin_table'], 1, false ) . ' disabled="disabled" /> <em>' . __( 'required', 'strong-testimonials' ) . '</em>' . "\n";
		$html .= '<input type="hidden" name="fields[' . $key . '][admin_table]" value="' . $field['admin_table'] . '" />' . "\n";
	}
	$html .= '</td>' . "\n";
	
	return $html;
}


/*
 * Add hidden fields to form.
 */
function wpmtst_show_field_hidden( $key, $field ) {
	// -------------
	// Hidden Values
	// -------------
	$html = '<input type="hidden" name="fields[' . $key . '][record_type]" value="' . $field['record_type'] . '">' . "\n";
	$html .= '<input type="hidden" name="fields[' . $key . '][input_type]" value="' . $field['input_type'] . '">' . "\n";
	$html .= '<input type="hidden" name="fields[' . $key . '][admin_table_option]" value="' . $field['admin_table_option'] . '">' . "\n";
	if ( isset( $field['map'] ) ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][map]" value="' . $field['map'] . '">' . "\n";
	}
	if ( isset( $field['core'] ) ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][core]" value="' . $field['core'] . '">' . "\n";
	}
	
	return $html;
}


/*
 * [Add New Field] Ajax receiver
 */
function wpmtst_add_field_function() {
	$new_key = intval( $_REQUEST['key'] );
	$fields = get_option( 'wpmtst_fields' );
	// when adding, leave Name empty so it will be populated from Label
	$empty_field = array( 'record_type' => 'custom', 'label' => 'New Field' );
	$new_field = wpmtst_show_field( $new_key, $empty_field, true );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field', 'wpmtst_add_field_function' );


/*
 * [Add New Field 2] Ajax receiver
 */
function wpmtst_add_field_2_function() {
	$new_key = intval( $_REQUEST['key'] );
	$new_field_type = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_secondary( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_2', 'wpmtst_add_field_2_function' );


/*
 * [Add New Field 3] Ajax receiver
 */
function wpmtst_add_field_3_function() {
	$new_key = intval( $_REQUEST['key'] );
	$new_field_type = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_hidden( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_3', 'wpmtst_add_field_3_function' );


/*
 * [Add New Field 4] Ajax receiver
 */
function wpmtst_add_field_4_function() {
	$new_key = intval( $_REQUEST['key'] );
	$new_field_type = $_REQUEST['fieldType'];
	$new_field_class = $_REQUEST['fieldClass'];
	$fields = get_option( 'wpmtst_fields' );
	$empty_field = array_merge(
		$fields['field_types'][$new_field_class][$new_field_type],
		array( 'record_type' => $new_field_class )
	);
	$new_field = wpmtst_show_field_admin_table( $new_key, $empty_field );
	echo $new_field;
	die();
}
add_action( 'wp_ajax_wpmtst_add_field_4', 'wpmtst_add_field_4_function' );
