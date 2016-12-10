<?php
/**
 * Strong Testimonials - Custom fields admin functions
 */

function wpmtst_form_admin() {
	do_action( 'wpmtst_form_admin' );
}

function wpmtst_form_admin2() {
	wpmtst_settings_custom_fields( 'edit', 1 );
}

/**
 * Custom Fields page
 *
 * @param string $action
 * @param null   $form_id
 *
 * @return bool
 */
// TODO is $action still used?
// TODO use admin-post.php instead
function wpmtst_settings_custom_fields( $action = '', $form_id = null ) {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	if ( ! $form_id ) {
		echo '<div class="wrap wpmtst"><p>' . __( 'No fields selected.', 'strong-testimonials' ) .'</p></div>';
		return false;
	}

	$field_options = get_option( 'wpmtst_fields' );
	$forms         = get_option( 'wpmtst_custom_forms' );
	$fields        = $forms[ $form_id ]['fields'];

	$message_format = '<div id="message" class="updated notice is-dismissible"><p>%s</p></div>';

	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_custom_fields_form' ) ) {

		if ( isset( $_POST['reset'] ) ) {

			// Undo changes
			$fields = $forms[ $form_id ]['fields'];
			echo sprintf( $message_format, __( 'Changes undone.', 'strong-testimonials' ) );

		}
		elseif ( isset( $_POST['restore-defaults'] ) ) {

			// Restore defaults
			include_once WPMTST_INC . 'defaults.php';
			$default_forms = wpmtst_get_default_base_forms();
			$fields = $default_forms['default']['fields'];
			$forms[ $form_id ]['fields'] = $fields;
			update_option( 'wpmtst_fields', $field_options );
			update_option( 'wpmtst_custom_forms', $forms );
			do_action( 'wpmtst_fields_updated', $fields );

			echo sprintf( $message_format, __( 'Defaults restored.', 'strong-testimonials' ) );

		}
		else {

			// Save changes
			$fields = array();
			$new_key = 0;

			/**
			 * Strip the dang slashes from the dang magic quotes.
			 *
			 * @since 2.0.0
			 */
			$post_fields = stripslashes_deep( $_POST['fields'] );

			foreach ( $post_fields as $key => $field ) {

				/*
				 * Before merging onto base field, catch fields that are "off"
				 * which the form does not submit. Otherwise, the default "on"
				 * would override the requested (but not submitted) "off".
				 */
				$field['show_label']              = isset( $field['show_label'] ) ? 1 : 0;
				$field['required']                = isset( $field['required'] ) ? 1 : 0;

				$field = array_merge( $field_options['field_base'], $field );

				$field['name']                    = sanitize_text_field( $field['name'] );
				$field['label']                   = sanitize_text_field( $field['label'] );

				$field['default_form_value']      = sanitize_text_field( $field['default_form_value'] );
				$field['default_display_value']   = sanitize_text_field( $field['default_display_value'] );

				$field['placeholder']             = sanitize_text_field( $field['placeholder'] );

				$field['before']                  = sanitize_text_field( $field['before'] );
				$field['after']                   = sanitize_text_field( $field['after'] );

				$field['shortcode_on_form']      = sanitize_text_field( $field['shortcode_on_form'] );
				$field['shortcode_on_display']   = sanitize_text_field( $field['shortcode_on_display'] );
				$field['show_shortcode_options'] = $field['show_shortcode_options'] ? 1 : 0;

				// Hidden options (no need to check if isset)
				$field['admin_table']             = $field['admin_table'] ? 1 : 0;
				$field['show_admin_table_option'] = $field['show_admin_table_option'] ? 1 : 0;
				$field['show_placeholder_option'] = $field['show_placeholder_option'] ? 1 : 0;
				$field['show_default_options']    = $field['show_default_options'] ? 1 : 0;

				// add to fields array in display order
				$fields[ $new_key++ ] = $field;

			}

			$forms[ $form_id ]['fields'] = $fields;

			if ( isset( $_POST['field_group_label'] ) ) {
				// TODO Catch if empty.
				$new_label = sanitize_text_field( $_POST['field_group_label'] );
				$forms[ $form_id ]['label'] = $new_label;
			}

			update_option( 'wpmtst_fields', $field_options );
			update_option( 'wpmtst_custom_forms', $forms );

			do_action( 'wpmtst_fields_updated', $fields );

			echo sprintf( $message_format, __( 'Fields saved.', 'strong-testimonials' ) );
		}

	} // if POST

	// ------------------
	// Custom Fields Form
	// ------------------
	?>
	<div class="wrap wpmtst">

		<h2><?php _e( 'Fields', 'strong-testimonials' ); ?></h2>

		<?php do_action( 'wpmtst_fields_editor_before_fields_intro' ); ?>

		<div id="left-col">
			<div>
				<h3>Editor</h3>
				<p>
					<?php _e( 'Click a field to open its options panel.', 'strong-testimonials' ); ?>
					<?php _e( 'More on the <strong>Help</strong> tab above.', 'strong-testimonials' ); ?>
				</p>
			</div>

			<!-- Custom Fields Form -->
			<form id="wpmtst-custom-fields-form" method="post" action="" autocomplete="off">
				<?php wp_nonce_field( 'wpmtst_custom_fields_form', 'wpmtst_form_submitted' ); ?>

				<?php do_action( 'wpmtst_fields_editor_before_fields_editor', $forms[ $form_id ] ); ?>

				<ul id="custom-field-list">
					<?php
					foreach ( $fields as $key => $field ) {
						echo '<li id="field-' . $key . '">' . wpmtst_show_field( $key, $field, false ) . '</li>' . "\n";
					}
					?>
				</ul>

				<div id="add-field-bar">
					<input id="add-field" type="button" class="button" name="add-field" value="<?php _e( 'Add New Field', 'strong-testimonials' ); ?>">
				</div>

				<div id="field-group-actions">
                    <div><?php submit_button( '', 'primary', 'submit', false ); ?></div>
                    <div><?php submit_button( __( 'Undo Changes', 'strong-testimonials' ), 'secondary', 'reset', false ); ?></div>
                    <div><?php submit_button( __( 'Restore Defaults', 'strong-testimonials' ), 'secondary', 'restore-defaults', false ); ?></div>
				</div>
			</form>
		</div><!-- #left-col -->

		<div id="right-col">
			<div class="intro">
				<h3><?php _e( 'Basic Preview', 'strong-testimonials' ); ?></h3>
				<p><?php _e( 'Only to demonstrate the fields. May look different in your theme.', 'strong-testimonials' ); ?></p>
			</div>
			<div id="fields-editor-preview">
				<div><!-- placeholder --></div>
			</div>
		</div><!-- #right-col -->

	</div><!-- wrap -->
	<?php
}

/**
 * Our version of htmlspecialchars.
 *
 * @since 2.0.0
 * @param $string
 *
 * @return string
 */
function wpmtst_htmlspecialchars( $string ) {
	return htmlspecialchars( $string, ENT_QUOTES, get_bloginfo( 'charset' ) );
}

/**
 * Add a field to the form
 *
 * @param $key
 * @param $field
 * @param $adding
 *
 * @return string
 */
function wpmtst_show_field( $key, $field, $adding ) {
	$fields      = get_option( 'wpmtst_fields' );
	$field_types = $fields['field_types'];
	$field_link  = $field['label'] ? $field['label'] : ucwords( $field['name'] );
	$is_core     = ( isset( $field['core'] ) && $field['core'] );

	// ------------
	// Field Header
	// ------------
	$html = '<div class="custom-field-header">';
	$html .= '<span class="link" title="' . __( 'click to open or close', 'strong-testimonials' ) . '"><a class="field" href="#">' . $field_link . '</a>';
	$html .= '<span class="handle" title="' . __( 'drag and drop to reorder', 'strong-testimonials' ) . '"></span>';
	$html .= '<span class="toggle"></span></span>';
	$html .= '</div>';

	$html .= '<div class="custom-field">';
	$html .= '<table class="field-table">';

	// -----------
	// Field Label
	// -----------
	$html .= '<tr>' . "\n";
	$html .= '<th>' . _x( 'Label', 'noun', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>';
	$html .= '<input type="text" class="first-field field-label" name="fields[' . $key . '][label]" value="' . wpmtst_htmlspecialchars( $field['label'] ). '">';
	//$html .= '<span class="help">' . __( 'This appears on the form.', 'strong-testimonials' ) . '</span>';
	$html .= '<label><span class="help"><input type="checkbox"  name="fields[' . $key . '][show_label]" ' . checked( $field['show_label'], true, false ) . '>' . __( 'Show this label on the form.', 'strong-testimonials' ) . '</span></label>';
	$html .= '</td>' . "\n";
	$html .= '</tr>' . "\n";

	// ----------
	// Field Name
	// ----------
	$html .= '<tr>' . "\n";
	$html .= '<th>' . _x( 'Name', 'noun', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";

	/**
	 * Field names for certain types are read-only.
	 *
	 * @todo Move to field options.
	 * @since 2.2.2
	 */
	if ( 'post' == $field['record_type'] || 'categories' == $field['input_type'] ) {

		$html .= '<input type="text" class="field-name" value="' . $field['name'] . '" disabled="disabled">';
		// disabled inputs are not posted so store the field name in a hidden input
		$html .= '<input type="hidden" name="fields[' . $key . '][name]" value="' . $field['name'] . '">';

	}
	else {

		// if adding, the field Name is blank so it can be populated from Label
		$html .= '<input type="text" class="field-name" name="fields[' . $key . '][name]" value="' . ( isset( $field['name'] ) ? wpmtst_htmlspecialchars( $field['name'] ) : '' ) . '">';
		$html .= '<span class="help field-name-help">' . __( 'Use only lowercase letters, numbers, and underscores.', 'strong-testimonials' ) . '</span>';
		$html .= '<span class="help field-name-help important">' . __( 'Cannot be "name" or "date".', 'strong-testimonials' ) . '</span>';

	}
	$html .= '</td>' . "\n";
	$html .= '</tr>' . "\n";

	// ---------------------------
	// Field Type (Post or Custom)
	// ---------------------------
	// If disabled, create <select> with single option
	// and add hidden input with current value.
	// Separate code! Readability is better than ultra-minor efficiency.

	$html .= '<tr>' . "\n";
	$html .= '<th>' . _x( 'Type', 'noun', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";

	// Restrict field choice to this record type
	// unless we're adding a new field.
	if ( $adding ) {

		$html .= '<select class="field-type new" name="fields[' . $key . '][input_type]">';

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
		 * Special fields
		 *
		 * @since 1.18
		 */
		$html .= '<optgroup class="optional" label="' . __( 'Special Fields', 'strong-testimonials' ) . '">';
		foreach ( $field_types['optional'] as $field_key => $field_parts ) {
			$html .= '<option value="' . $field_key . '">' . $field_parts['option_label'] . '</option>';
		}
		$html .= '</optgroup>';

		$html .= '</select>';

	}
	else {

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

		}
		elseif ( 'custom' == $field['record_type'] ) {

			// -------------
			// Custom fields
			// -------------
			$html .= '<select class="field-type" name="fields[' . $key . '][input_type]">';
			$html .= '<optgroup class="custom" label="Custom Fields">';
			foreach ( $field_types['custom'] as $field_key => $field_parts ) {
				// compare field *type*
				$selected = selected( $field['input_type'], $field_key, false );
				$html .= '<option value="' . $field_key . '" ' . $selected . '>' . $field_parts['option_label'] . '</option>';
			}
			$html .= '</optgroup>';
			$html .= '</select>';

		}
		elseif ( 'optional' == $field['record_type'] ) {

			// -------------
			// Special fields
			// -------------
			$html .= '<select class="field-type" name="fields[' . $key . '][input_type]">';
			$html .= '<optgroup class="optional" label="' . __( 'Special Fields', 'strong-testimonials' ) . '">';
			foreach ( $field_types['optional'] as $field_key => $field_parts ) {
				// compare field *type*
				$selected = selected( $field['input_type'], $field_key, false );
				$html .= '<option value="' . $field_key . '" ' . $selected . '>' . $field_parts['option_label'] . '</option>';
			}
			$html .= '</optgroup>';
			$html .= '</select>';

		}

	} // editing

	$html .= '</td>' . "\n";
	$html .= '</tr>' . "\n";

	if ( ! $adding ) {
		$html .= wpmtst_show_field_secondary( $key, $field );
		$html .= wpmtst_show_field_admin_table( $key, $field );
	}

	$html .= '</table>' . "\n";

	if ( ! $adding ) {
		$html .= wpmtst_show_field_hidden( $key, $field );
	}

	// --------
	// Controls
	// --------
	$html .= '<div class="controls">' . "\n";
	if ( $adding || ! $is_core ) {
		$html .= '<span><a href="#" class="delete-field">' . __( 'Delete' ) . '</a></span>' . "\n";
	}
	$html .= '<span class="close-field"><a href="#">' . _x( 'Close', 'verb', 'strong-testimonials' ) . '</a></span>' . "\n";
	$html .= '</div><!-- .controls -->' . "\n";

	$html .= '</div><!-- .custom-field -->' . "\n";

	return $html;
}


/**
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

	$html = '<tr class="field-secondary">' . "\n";
	$html .= '<th>' . __( 'Required', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( $disabled ) {
		$html .= '<input type="hidden" name="fields[' . $key . '][required]" value="' . $field['required'] . '">';
		$html .= '<input type="checkbox" ' . checked( $field['required'], true, false ) . $disabled . '>';
	} else {
		$html .= '<input type="checkbox" name="fields[' . $key . '][required]" ' . checked( $field['required'], true, false ) . '>';
	}
	$html .= '</td>' . "\n";
	$html .= '</tr>' . "\n";

	// -----------
	// Placeholder
	// -----------
	if ( $field['show_placeholder_option'] ) {
		if ( isset( $field['placeholder'] ) ) {
			$html .= '<tr class="field-secondary">' . "\n";
			$html .= '<th>' . __( 'Placeholder', 'strong-testimonials' ) . '</th>' . "\n";
			$html .= '<td><input type="text" name="fields[' . $key . '][placeholder]" value="' . wpmtst_htmlspecialchars( $field['placeholder'] ) . '"></td>' . "\n";
			$html .= '</tr>' . "\n";
		}
	}

	// ------
	// Before
	// ------
	$html .= '<tr class="field-secondary">' . "\n";
	$html .= '<th>' . __( 'Before', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td><input type="text" name="fields[' . $key . '][before]" value="' . wpmtst_htmlspecialchars( $field['before'] ) . '"></td>' . "\n";
	$html .= '</tr>' . "\n";

	// -----
	// After
	// -----
	$html .= '<tr class="field-secondary">' . "\n";
	$html .= '<th>' . __( 'After', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td><input type="text" name="fields[' . $key . '][after]" value="' . wpmtst_htmlspecialchars( $field['after'] ) . '"></td>' . "\n";
	$html .= '</tr>' . "\n";

	// ------------------
	// Default Form Value
	// ------------------
	if ( $field['show_default_options'] ) {
		if ( isset( $field['default_form_value'] ) ) {
			$html .= '<tr class="field-secondary">' . "\n";
			$html .= '<th>' . __( 'Default Form Value', 'strong-testimonials' ) . '</th>' . "\n";
			$html .= '<td>' . "\n";
			$html .= '<input type="text" name="fields[' . $key . '][default_form_value]" value="' . wpmtst_htmlspecialchars( $field['default_form_value'] ) . '">';
			$html .= '<span class="help">' . __( 'Populate the field with this value.', 'strong-testimonials' ) . '</span>';
			$html .= '</td>' . "\n";
			$html .= '</tr>' . "\n";
		}
	}

	// ---------------------
	// Default Display Value
	// ---------------------
	if ( $field['show_default_options'] ) {
		if ( isset( $field['default_display_value'] ) ) {
			$html .= '<tr class="field-secondary">' . "\n";
			$html .= '<th>' . __( 'Default Display Value', 'strong-testimonials' ) . '</th>' . "\n";
			$html .= '<td>' . "\n";
			$html .= '<input type="text" name="fields[' . $key . '][default_display_value]" value="' . wpmtst_htmlspecialchars( $field['default_display_value'] ) . '">';
			$html .= '<span class="help">' . __( 'Display this on the testimonial if no value is submitted.', 'strong-testimonials' ) . '</span>';
			$html .= '</td>' . "\n";
			$html .= '</tr>' . "\n";
		}
	}

	// ---------------------
	// Shortcode Options
	// ---------------------
	if ( $field['show_shortcode_options'] ) {
		if ( isset( $field['shortcode_on_form'] ) ) {
			$html .= '<tr class="field-secondary">' . "\n";
			$html .= '<th>' . __( 'Shortcode on form', 'strong-testimonials' ) . '</th>' . "\n";
			$html .= '<td>' . "\n";
			$html .= '<input type="text" name="fields[' . $key . '][shortcode_on_form]" value="' . wpmtst_htmlspecialchars( $field['shortcode_on_form'] ) . '">';
			//$html .= '<span class="help">' . __( 'Display this on the testimonial if no value is submitted.', 'strong-testimonials' ) . '</span>';
			$html .= '</td>' . "\n";
			$html .= '</tr>' . "\n";
		}
		if ( isset( $field['shortcode_on_display'] ) ) {
			$html .= '<tr class="field-secondary">' . "\n";
			$html .= '<th>' . __( 'Shortcode on display', 'strong-testimonials' ) . '</th>' . "\n";
			$html .= '<td>' . "\n";
			$html .= '<input type="text" name="fields[' . $key . '][shortcode_on_display]" value="' . wpmtst_htmlspecialchars( $field['shortcode_on_display'] ) . '">';
			//$html .= '<span class="help">' . __( 'Display this on the testimonial if no value is submitted.', 'strong-testimonials' ) . '</span>';
			$html .= '</td>' . "\n";
			$html .= '</tr>' . "\n";
		}
	}

	return $html;
}


/**
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

	$html = '<tr class="field-admin-table">' . "\n";
	$html .= '<th>' . __( 'Admin List', 'strong-testimonials' ) . '</th>' . "\n";
	$html .= '<td>' . "\n";
	if ( $field['admin_table_option'] ) {
		$html .= '<label><input type="checkbox" class="field-admin-table" name="fields[' . $key . '][admin_table]" ' . checked( $field['admin_table'], 1, false ) . '>';
	} else {
		$html .= '<input type="checkbox" ' . checked( $field['admin_table'], 1, false ) . ' disabled="disabled"> <em>' . __( 'required', 'strong-testimonials' ) . '</em>';
		$html .= '<input type="hidden" name="fields[' . $key . '][admin_table]" value="' . $field['admin_table'] . '">';
	}
	$html .= '<span class="help inline">' . __( 'Show this field in the admin list table.', 'strong-testimonials' ) . '</span>';
	$html .= '</label>';
	$html .= '</td>' . "\n";
	$html .= '</tr>' . "\n";

	return $html;
}


/**
 * Add hidden fields to form.
 *
 * @param $key
 * @param $field
 *
 * @return string
 */
function wpmtst_show_field_hidden( $key, $field ) {
	$pattern = '<input type="hidden" name="fields[%s][%s]" value="%s">';

	$html = sprintf( $pattern, $key, 'record_type', $field['record_type'] ) . "\n";
	$html .= sprintf( $pattern, $key, 'input_type', $field['input_type'] ) . "\n";
	$html .= sprintf( $pattern, $key, 'name_mutable', $field['name_mutable'] ) . "\n";
	$html .= sprintf( $pattern, $key, 'show_placeholder_option', $field['show_placeholder_option'] ) . "\n";
	$html .= sprintf( $pattern, $key, 'show_default_options', $field['show_default_options'] ) . "\n";
	$html .= sprintf( $pattern, $key, 'admin_table_option', $field['admin_table_option'] ) . "\n";
	$html .= sprintf( $pattern, $key, 'show_admin_table_option', $field['show_admin_table_option'] ) . "\n";

	$html .= sprintf( $pattern, $key, 'show_shortcode_options', $field['show_shortcode_options'] ) . "\n";

	if ( isset( $field['map'] ) ) {
		$html .= sprintf( $pattern, $key, 'map', $field['map'] ) . "\n";
	}

	if ( isset( $field['core'] ) ) {
		$html .= sprintf( $pattern, $key, 'core', $field['core'] ) . "\n";
	}

	return $html;
}
