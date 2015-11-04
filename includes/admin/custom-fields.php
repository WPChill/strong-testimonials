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

	$message_format = '<div id="message" class="updated notice is-dismissible"><p><strong>%s</strong></p></div>';

	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_custom_fields_form' ) ) {

		if ( isset( $_POST['reset'] ) ) {

			// Undo changes
			$fields = $field_group['fields'];
			echo sprintf( $message_format, __( 'Changes undone.', 'strong-testimonials' ) );

		} elseif ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			// ----------------
			// 1.7 - soft restore from database
			// $fields = $field_options['field_groups']['default']['fields'];
			// $field_options['field_groups']['custom']['fields'] = $fields;
			// update_option( 'wpmtst_fields', $field_options );
			
			// 1.7.1 - hard restore from file
			include_once WPMTST_INC . 'defaults.php';
			$default_fields = wpmtst_get_default_fields();
			update_option( 'wpmtst_fields', $default_fields );
			$fields = $default_fields['field_groups']['custom']['fields'];
			do_action( 'wpmtst_fields_updated', $fields );

			echo sprintf( $message_format, __( 'Defaults restored.', 'strong-testimonials' ) );

		} else {

			// Save changes
			$fields = array();
			$new_key = 0;
			foreach ( $_POST['fields'] as $key => $field ) {
				$field = array_merge( $field_options['field_base'], $field );

				// sanitize & validate
				$field['name'] = sanitize_text_field( $field['name'] );
				$field['label'] = sanitize_text_field( $field['label'] );
				$field['placeholder'] = sanitize_text_field( $field['placeholder'] );
				$field['show_placeholder_option'] = $field['show_placeholder_option'] ? 1 : 0;
				$field['before'] = sanitize_text_field( $field['before'] );
				$field['after'] = sanitize_text_field( $field['after'] );
				$field['required'] = $field['required'] ? 1 : 0;
				$field['admin_table'] = $field['admin_table'] ? 1 : 0;
				$field['show_admin_table_option'] = $field['show_admin_table_option'] ? 1 : 0;

				// add to fields array in display order
				$fields[$new_key++] = $field;

			}
			$field_options['field_groups']['custom']['fields'] = $fields;
			update_option( 'wpmtst_fields', $field_options );
			do_action( 'wpmtst_fields_updated', $fields );

			echo sprintf( $message_format, __( 'Fields saved.', 'strong-testimonials' ) );
		}

	} else {

		// Get current fields
		$fields = $field_group['fields'];

	}

	// ------------------
	// Custom Fields Form
	// ------------------
	?>
	<div class="wrap wpmtst">
		<h2><?php _e( 'Fields', 'strong-testimonials' ); ?></h2>
		<div class="intro">
			<p><?php _e( 'Fields will appear in this order on the form.', 'strong-testimonials' ); ?></p>
			<p><?php printf( __( 'Reorder by grabbing the %s icon.', 'strong-testimonials' ), '<span class="dashicons dashicons-menu"></span>' ); ?></p>
			<p><?php _e( 'Click the field name to expand its options panel.', 'strong-testimonials' ); ?></p>
			<p>
				<a href="https://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/" target="_blank"><?php _ex( 'Full tutorial', 'link', 'strong-testimonials' ); ?></a>
			</p>
		</div>
	
	<!-- Custom Fields Form -->
	<form id="wpmtst-custom-fields-form" method="post" action="">
	<?php wp_nonce_field( 'wpmtst_custom_fields_form', 'wpmtst_form_submitted' ); ?> 
	
	<ul id="custom-field-list">
	
	<?php foreach ( $fields as $key => $field ) : ?>
		<li id="field-<?php echo $key; ?>"><?php echo wpmtst_show_field( $key, $field, false ); ?></li>
	<?php endforeach; ?>
	
	</ul>
	
	<div id="add-field-bar">
		<input id="add-field" type="button" class="button" name="add-field" value="<?php _e( 'Add New Field', 'strong-testimonials' ); ?>">
	</div>
	
	<p class="submit">
		<?php
		submit_button( '', 'primary', 'submit', false );
		submit_button( __( 'Undo Changes', 'strong-testimonials' ), 'secondary', 'reset', false );
		submit_button( __( 'Restore Defaults', 'strong-testimonials' ), 'secondary', 'restore-defaults', false );
		?>
	</p>
	
	</form><!-- Custom Fields -->
	
	<p><em><?php printf( __( 'Customize other form elements <a href="%s">here</a>.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=settings&tab=form' ) ); ?></em></p>
		
	</div><!-- wrap -->
	<?php
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
	$html = '<div class="custom-field-header">';
	$html .= '<span class="handle"><div class="dashicons dashicons-menu"></div></span>';
	$html .= '<span class="link"><a class="field" href="#">' . $field_link . '</a></span>';
	$html .= '</div>';
	
	$html .= '<div class="custom-field">';
	
	$html .= '<table class="field-table">';
	
	// -----------
	// Field Label
	// -----------
	$html .= '<tr>';
	$html .= '<th>' . _x( 'Label', 'noun', 'strong-testimonials' ) . '</th>';
	$html .= '<td>';
	$html .= '<input type="text" class="first-field field-label" name="fields[' . $key . '][label]" value="' . $field['label'] . '">';
	$html .= '<span class="help">' . __( 'This appears on the form.', 'strong-testimonials' ) . '</span>';
	$html .= '</td>';
	$html .= '</td>';
	
	// ----------
	// Field Name
	// ----------
	$html .= '<tr>';
	$html .= '<th>' . _x( 'Name', 'noun', 'strong-testimonials' ) . '</th>';
	$html .= '<td>';
	if ( in_array( $field['record_type'], array( 'custom', 'optional' ) ) ) {
		// if adding, the field Name is blank so it can be populated from Label
		$html .= '<input type="text" class="field-name" name="fields['.$key.'][name]" value="' . ( isset( $field['name'] ) ? $field['name'] : '' ) . '">';
		$html .= '<span class="help field-name-help">' . __( 'Use only lowercase letters, numbers, and underscores.', 'strong-testimonials' ) . '</span>';
		$html .= '<span class="help field-name-help important">' . __( 'Cannot be "name" or "date"', 'strong-testimonials' ) . '</span>';
	} else {
		$html .= '<input type="text" class="field-name" value="' . $field['name'] . '" disabled="disabled">';
		// disabled inputs are not posted so store the field name in a hidden input
		$html .= '<input type="hidden" name="fields[' . $key . '][name]" value="' . $field['name'] . '">';
	}
	$html .= '</td>';
	$html .= '</td>';
	
	// ---------------------------
	// Field Type (Post or Custom)
	// ---------------------------
	// If disabled, create <select> with single option
	// and add hidden input with current value.
	// Separate code! Readability trumps ultra-minor efficiency.
	
	$html .= '<tr>';
	$html .= '<th>' . _x( 'Type', 'noun', 'strong-testimonials' ) . '</th>';
	$html .= '<td>';
	
	// Restrict field choice to this record type
	// unless we're adding a new field.
	if ( $adding ) {
	
		$html .= '<select class="field-type new" name="fields[' . $key . '][input_type]" autocomplete="off">';
	
		// start with a blank option with event trigger to update optgroups...
		$html .= '<option class="no-selection" value="none" name="none">&mdash;</option>';
		
		// If pre-selecting a record type in event handler:
		/*
		if ( 'custom' == $field['record_type'] ) {
			// compare field *name*
			$selected = selected( $field['name'], $field_key, false );
		} elseif ( 'post' == $field['record_type'] {
			// compare field *type*
			$selected = selected( $field['input_type'], $field_key, false );
		}
		*/
		// ...then add $selected to <option>.
		
		// Post fields
		$html .= '<optgroup class="post" label="' . __( 'Post Fields', 'strong-testimonials' ) . '">';
		foreach ( $field_types['post'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>';
		}
		$html .= '</optgroup>';
		
		// Custom fields
		$html .= '<optgroup class="custom" label="' . __( 'Custom Fields', 'strong-testimonials' ) . '">';
		foreach ( $field_types['custom'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>';
		}
		$html .= '</optgroup>';

		/**
		 * Optional fields
		 * 
		 * @since 1.18
		 */
		$html .= '<optgroup class="optional" label="' . __( 'Optional Fields', 'strong-testimonials' ) . '">';
		foreach ( $field_types['optional'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>';
		}
		$html .= '</optgroup>';

		$html .= '</select>';

	} else {
	
		if ( 'post' == $field['record_type'] ) {
			// -----------
			// Post fields
			// -----------
			// Disable <select>. Display current value as only option.
			// Disabled inputs are not posted so store the value in hidden field.
			$html .= '<input type="hidden" name="fields[' . $key . '][input_type]" value="' . $field['name'] . '">';
			$html .= '<select id="current-field-type" class="field-type" disabled="disabled">';
			foreach ( $field_types['post'] as $field_key => $field_parts ) {
				// compare field *name*
				if ( $field['name'] == $field_key )
					$html .= '<option value="' . $field_key . '" selected="selected">' . $field_parts['option_label'] . '</option>';
			}
			$html .= '</select>';
		} elseif ( 'custom' == $field['record_type'] ) {
			// -------------
			// Custom fields
			// -------------
			$html .= '<select class="field-type" name="fields[' . $key . '][input_type]" autocomplete="off">';
			$html .= '<optgroup class="custom" label="Custom Fields">';
			foreach ( $field_types['custom'] as $field_key => $field_parts ) {
				// compare field *type*
				$selected = selected( $field['input_type'], $field_key, false );
				$html .= '<option value="' . $field_key . '" ' . $selected . '>' . $field_parts['option_label'] . '</option>';
			}
			$html .= '</optgroup>';
			$html .= '</select>';
		} elseif ( 'optional' == $field['record_type'] ) {
			// -------------
			// Optional fields
			// -------------
			$html .= '<select class="field-type" name="fields[' . $key . '][input_type]" autocomplete="off">';
			$html .= '<optgroup class="optional" label="Optional Fields">';
			foreach ( $field_types['optional'] as $field_key => $field_parts ) {
				// compare field *type*
				$selected = selected( $field['input_type'], $field_key, false );
				$html .= '<option value="' . $field_key . '" ' . $selected . '>' . $field_parts['option_label'] . '</option>';
			}
			$html .= '</optgroup>';
			$html .= '</select>';
		} 
		
	} // adding
	$html .= '</td>';
	
	if ( ! $adding ) {
		$html .= wpmtst_show_field_secondary( $key, $field );
		$html .= wpmtst_show_field_admin_table( $key, $field );
	}
	
	$html .= '</table>';

	if ( ! $adding )
		$html .= wpmtst_show_field_hidden( $key, $field );
	
	// --------
	// Controls
	// --------
	$html .= '<div class="controls">';
	if ( $adding || ! $is_core ) {
		$html .= '<span><a href="#" class="delete-field">' . __( 'Delete' ) . '</a></span>';
	}
	$html .= '<span class="close-field"><a href="#">' . _x( 'Close', 'verb', 'strong-testimonials' ) . '</a></span>';
	$html .= '</div>';
	
	$html .= '</div><!-- .custom-field -->';
	
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
		
	$html = '<tr>';
	$html .= '<th>' . __( 'Required', 'strong-testimonials' ) . '</th>';
	$html .= '<td>';
	if ( $disabled ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][required]" value="' . $field['required'] . '">';
		$html .= '<input type="checkbox" ' . checked( $field['required'], true, false ) . $disabled . '>';
	} else {
		$html .= '<input type="checkbox" name="fields[' . $key . '][required]" ' . checked( $field['required'], true, false ) . '>';
	}
	$html .= '</td>';
	$html .= '</td>';
	
	// -----------
	// Placeholder
	// -----------
	if ( $field['show_placeholder_option'] ) {
		if ( isset( $field['placeholder'] ) ) {
			$html .= '<tr>';
			$html .= '<th>' . __( 'Placeholder', 'strong-testimonials' ) . '</th>';
			$html .= '<td><input type="text" name="fields[' . $key . '][placeholder]" value="' . $field['placeholder'] . '"></td>';
			$html .= '</td>';
		}
	}
	
	// ------
	// Before
	// ------
	$html .= '<tr>';
	$html .= '<th>' . __( 'Before', 'strong-testimonials' ) . '</th>';
	$html .= '<td><input type="text" name="fields[' . $key . '][before]" value="' . $field['before'] . '"></td>';
	$html .= '</td>';
	
	// -----
	// After
	// -----
	$html .= '<tr>';
	$html .= '<th>' . __( 'After', 'strong-testimonials' ) . '</th>';
	$html .= '<td><input type="text" name="fields[' . $key . '][after]" value="' . $field['after'] . '"></td>';
	$html .= '</td>';
	
	return $html;
}


/*
 * Add type-specific [Admin Table] setting to form.
 */
function wpmtst_show_field_admin_table( $key, $field ) {
	// -------------------
	// Show in Admin Table
	// -------------------
	if ( ! $field['show_admin_table_option'] ) {
		$html = '<input type="hidden" name="fields[' . $key . '][show_admin_table_option]" value="' . $field['show_admin_table_option'] . '">';
		return $html;
	}
	
	$html = '<tr class="field-admin-table">';
	$html .= '<th>' . __( 'Admin Table', 'strong-testimonials' ) . '</th>';
	$html .= '<td>';
	if ( $field['admin_table_option'] ) {
		$html .= '<input type="checkbox" class="field-admin-table" name="fields[' . $key . '][admin_table]" ' . checked( $field['admin_table'], 1, false ) . '>';
	} else {
		$html .= '<input type="checkbox" ' . checked( $field['admin_table'], 1, false ) . ' disabled="disabled"> <em>' . __( 'required', 'strong-testimonials' ) . '</em>';
		$html .= '<input type="hidden" name="fields[' . $key . '][admin_table]" value="' . $field['admin_table'] . '">';
	}
	$html .= '</td>';
	
	return $html;
}


/*
 * Add hidden fields to form.
 */
function wpmtst_show_field_hidden( $key, $field ) {
	// -------------
	// Hidden Values
	// -------------
	$html = '<input type="hidden" name="fields[' . $key . '][record_type]" value="' . $field['record_type'] . '">';
	$html .= '<input type="hidden" name="fields[' . $key . '][input_type]" value="' . $field['input_type'] . '">';
	$html .= '<input type="hidden" name="fields[' . $key . '][show_placeholder_option]" value="' . $field['show_placeholder_option'] . '">';
	$html .= '<input type="hidden" name="fields[' . $key . '][admin_table_option]" value="' . $field['admin_table_option'] . '">';
	$html .= '<input type="hidden" name="fields[' . $key . '][show_admin_table_option]" value="' . $field['show_admin_table_option'] . '">';
	
	if ( isset( $field['map'] ) ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][map]" value="' . $field['map'] . '">';
	}
	
	if ( isset( $field['core'] ) ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][core]" value="' . $field['core'] . '">';
	}
	
	return $html;
}


/*
 * [Add New Field] Ajax receiver
 */
function wpmtst_add_field_function() {
	$new_key = intval( $_REQUEST['key'] );
	//$fields = get_option( 'wpmtst_fields' );
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
