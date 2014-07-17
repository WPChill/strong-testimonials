<?php
/**
 * Strong Testimonials - Template admin functions
 */

 
/*
 * Template page
 */
function wpmtst_settings_template() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	
	$message_format = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';
	
	// Get current fields
	$options = get_option( 'wpmtst_options' );
	$field_options = get_option( 'wpmtst_fields' );
	$field_groups = $field_options['field_groups'];
	$current_field_group = $field_options['current_field_group'];  // "custom", only one for now
	$field_group = $field_groups[$current_field_group];

	
	// *** THIS BELONGS IN { else } BELOW ***
	// Get current template
	$templates = get_option( 'wpmtst_templates' );
	$template = $templates['templates'][ $templates['current_template'] ];
	$sections = $template['sections'];

	
	// ------------
	// Form Actions
	// ------------
	if ( isset( $_POST['wpmtst_form_submitted'] )
			&& wp_verify_nonce( $_POST['wpmtst_form_submitted'], 'wpmtst_template_form' ) ) {

	if ( isset( $_POST['reset'] ) ) {

			/*
			// Undo changes
			$fields = $field_group['fields'];
			echo sprintf( $message_format, __( 'Changes undone.', WPMTST_NAME ) );
			*/

		}
		elseif ( isset( $_POST['restore-defaults'] ) ) {
			
			/*
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
			
			echo sprintf( $message_format, __( 'Defaults restored.', WPMTST_NAME ) );
			*/

		}
		else {

			/*
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
			echo sprintf( $message_format, __( 'Fields saved.', WPMTST_NAME ) );
			*/
		}

	}
	else {

		// Get current template
		$templates = get_option( 'wpmtst_templates' );
		$template = $templates['templates'][ $templates['current_template'] ];
		$sections = $template['sections'];

	}	
	
	echo '<div class="wrap wpmtst">' . "\n";
	echo '<h2>' . __( 'Template', WPMTST_NAME ) . '</h2>' . "\n";	
	

	// -----
	// debug
	// -----
	echo debug('field group',$field_group,'yellow');
	echo debug('template',$template,'green');
	
	$custom_field_list = array();
	foreach ( $field_group['fields'] as $field ) {
		if ( $field['template'] )
			$custom_field_list[ $field['name'] ] = $field['label'];
	}
	echo debug('custom fields',$custom_field_list,'yellow');
	
	$template_field_list = array();
	foreach ( $sections as $section ) {
		foreach ( $section['fields'] as $field ) {
			$template_field_list[] = $field['name'];
		}
	}
	echo debug('template fields',$template_field_list,'green');
	
	echo debug('diff',array_diff(array_keys($custom_field_list), $template_field_list));

	echo debug('POST',$_POST,'orange');
	
	
	// -------------
	// Template Form
	// -------------
	echo '<!-- Template Form -->' . "\n";
	echo '<form id="wpmtst-template-form" method="post" action="">' . "\n";
	wp_nonce_field( 'wpmtst_template_form', 'wpmtst_form_submitted' ); 
	echo '<input type="hidden" name="save_template" value="" />';
	
	// 1. display
	echo '<table border="1">';
	echo '<tr>';
	echo '<th style="min-width: 150px;">Fields</th><th style="min-width: 150px;">Template</th><th>Preview</th>';
	echo '</tr>';
	
	echo '<tr>';
	
	// -----------
	// field group
	// -----------
	echo '<td>';
	echo '<ul id="field-list" class="template-list connected-sortable">';
	// foreach ( $field_group['fields'] as $field ) {
	// foreach ( $custom_field_list as $field ) {
	$diff = array_diff( array_keys( $custom_field_list ), $template_field_list );
	foreach ( $diff as $field ) {
		echo '<li>' . $field . '</li>' . "\n";
	}
	echo '</ul>';
	echo '</td>';
	
	
	
	$data_format = 'data-name="%s" data-type="%s" data-classname="%s"';
	
	// -----------------
	// template sections
	// -----------------
	echo '<td>';
	foreach ( $sections as $section_key => $section ) {
		echo '<div class="template-section">' . "\n";
		
		echo '<div class="section-name">' . $section_key . '</div>' . "\n";
		echo '<div class="section-class">' . $section['wrapper_class'] . '</div>' . "\n";
		
		echo '<ul class="template-list connected-sortable" data-section="' . $section_key . '" data-classname="' . $section['wrapper_class'] . '">' . "\n";
		
		foreach ( $section['fields'] as $field_key => $field ) {
		
			$classes = array();
			if ( isset( $field['locked'] ) && $field['locked'] )
				$classes[] = 'locked';
				
			// echo '<li class="' . implode( ' ', $classes ) . '">';
			echo '<li class="' . implode( ' ', $classes ) . '" ' . sprintf( $data_format, $field['name'], $field['type'], $field['class'] ) . '>';
			
			// each field needs hidden inputs so it can be fully reconstituted upon POST
			// foreach ( array_keys( $field ) as $key ) {	
				// echo sprintf( $hidden_format, $section_key, $field_key, $key, $field[$key] );
			// }
			
			// name
			echo '<div class="field-label unselectable">' . $custom_field_list[ $field['name'] ] . '</div>';
			
			// properties
			echo '<div class="field-prop">';
			// echo '<div>' . $field['name'] . '</div>';
			echo '<div>class: ' . $field['class'] . '</div>';
			// echo '<div><input type="text" name="' . $field['name'] . '-class" value="' . $field['class'] . '" /></div>';
			echo '</div>';
			
			echo '</li>' . "\n";
			
		}
		
		echo '</ul>' . "\n";
		
		echo '</div><!-- .template-section -->' . "\n";
	}
	echo '</td>';
	
	echo '<td>';
	echo '</td>';
	
	echo '</tr>';
	echo '</table>';

	echo '<a href="'.$_SERVER['REQUEST_URI'].'">refresh</a>';
	echo '<p class="submit">' . "\n";
	echo '<input type="button" class="button" style="background: orange" id="check" value="check" />';
	submit_button( '', 'primary', 'submit', false );
	// submit_button( 'Undo Changes', 'secondary', 'reset', false );
	// submit_button( 'Restore Defaults', 'secondary', 'restore-defaults', false );
	echo '</p>' . "\n";

	echo '</form><!-- Template Form -->';
	
	echo '</div><!-- .wrap -->' . "\n";
}

function debug( $title = '', $array = array(), $bg = '' ) {
	return '<div class="print_r-wrap"><div class="print_r-heading ' . $bg . '">' . $title . '</div><pre class="print_r-pre">' . print_r($array,true) . '</pre></div>';
}

function wpmtst_save_template_function() {
	logmore($_REQUEST['sections']);
	echo "success";
	die();
}
add_action( 'wp_ajax_wpmtst_save_template', 'wpmtst_save_template_function' );
