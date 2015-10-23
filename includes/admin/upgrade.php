<?php
/**
 * Plugin activation and upgrade.
 *
 * @package Strong_Testimonials
 */

function wpmtst_default_settings() {
	
	$current_plugin_version = get_option( 'wpmtst_plugin_version' );
	$plugin_data            = get_plugin_data( dirname( dirname( dirname( __FILE__ ) ) ) . '/strong-testimonials.php', false );
	$plugin_version         = $plugin_data['Version'];
	
	if ( $current_plugin_version == $plugin_version )
		return;
	
	// -1- DEFAULTS
	include_once WPMTST_INC . 'defaults.php';
	$default_options       = wpmtst_get_default_options();
	$default_fields        = wpmtst_get_default_fields();
	$default_cycle         = wpmtst_get_default_cycle();
	$default_form_options  = wpmtst_get_default_form_options();
	$default_view_options  = wpmtst_get_default_view_options();
	$default_view          = wpmtst_get_default_view();
	$default_l10n_contexts = wpmtst_get_default_l10n_contexts();
	
	// -2- GET OPTIONS
	$options = get_option( 'wpmtst_options' );
	if ( ! $options ) {
		// -2A- NEW ACTIVATION
		update_option( 'wpmtst_options', $default_options );
	} else {
		// -2B- UPDATE

		// Fix captcha inconsistency
		if ( isset( $options['captcha'] ) && 'none' == $options['captcha'] )
			$options['captcha'] = '';
			
		// Change target parameter in client section
		$options['default_template'] = str_replace( 'target="_blank"', 'new_tab', $options['default_template'] );

		// Remove plugin version
		if ( isset( $options['plugin_version'] ) ) 
			unset( $options['plugin_version'] );
		
		// Merge in new options
		$options = array_merge( $default_options, $options );
		update_option( 'wpmtst_options', $options );
	}
	
	// -3- GET FIELDS
	$fields = get_option( 'wpmtst_fields', array() );
	if ( ! $fields ) {
		// -3A- NEW ACTIVATION
		update_option( 'wpmtst_fields', $default_fields );
		// WPML
		wpmtst_form_fields_wpml( $default_fields['field_groups']['custom']['fields'] );
	} else {
		// -3B- UPDATE
		// Field update was missing from 1.18 - 1.20. Added in 1.20.1.

		// ----------
		// field base
		// ----------
		$new_field_base = array_merge( $default_fields['field_base'], $fields['field_base'] );

		// -----------
		// field types
		// -----------
		$new_field_types = $fields['field_types'];
		// first check for new default types like "optional"
		foreach ( $default_fields['field_types'] as $type_name => $type_array ) {
			if ( ! isset( $new_field_types[ $type_name ] ) ) {
				$new_field_types[ $type_name ] = $type_array;
			}
		}
		// now update existing types like "post" and "custom"
		foreach ( $new_field_types as $type_name => $type_array ) {
			foreach ( $type_array as $field_name => $field_atts ) {
				$new_field_types[ $type_name ][ $field_name ] = array_merge( $default_fields['field_types'][ $type_name ][ $field_name ], $field_atts );
			}
		}

		// ------------
		// field_groups
		// ------------
		$new_field_groups = $fields['field_groups'];
		foreach ( $new_field_groups as $group_name => $group_array ) {
			// custom fields are in display order (not associative)
			// so we must find them by name
			foreach ( $group_array['fields'] as $key => $new_field ) {
				$updated_default = null;
				foreach ( $default_fields['field_groups']['default']['fields'] as $a_field ) {
					if ( $a_field['name'] = $new_field['name'] ) {
						$updated_default = $a_field;
						break;
					}
				}
				if ( $updated_default ) {
					$new_field_groups[ $group_name ]['fields'][ $key ] = array_merge( $updated_default, $new_field );
				}
			}
		}

		// Re-assemble fields and save.
		$fields['field_base']   = $new_field_base;
		$fields['field_types']  = $new_field_types;
		$fields['field_groups'] = $new_field_groups;
		update_option( 'wpmtst_fields', $fields );
		// WPML
		wpmtst_form_fields_wpml( $fields['field_groups']['custom']['fields'] );
	}
	
	// -4- GET CYCLE
	$cycle = get_option( 'wpmtst_cycle' );
	if ( ! $cycle ) {
		// -4A- NEW ACTIVATION
		update_option( 'wpmtst_cycle', $default_cycle );
	} else {
		// -4B- UPDATE
		// if updating from 1.5 - 1.6
		if ( isset( $options['cycle-order'] ) ) {
			$cycle = array(
				'order'   => $options['cycle-order'],
				'effect'  => $options['cycle-effect'],
				'speed'   => $options['cycle-speed'],
				'timeout' => $options['cycle-timeout'],
				'pause'   => $options['cycle-pause'],
			);
			unset(
				$options['cycle-order'],
				$options['cycle-effect'],
				$options['cycle-speed'],
				$options['cycle-timeout'],
				$options['cycle-pause']
			);
			update_option( 'wpmtst_options', $options );
		}

		// if updating to 1.11
		// change hyphenated to underscored
		if ( isset( $cycle['char-limit'] ) ) {
			$cycle['char_limit'] = $cycle['char-limit'];
			unset( $cycle['char-limit'] );
		}
		if ( isset( $cycle['more-page'] ) ) {
			$cycle['more_page'] = $cycle['more-page'];
			unset( $cycle['more-page'] );
		}

		// if updating from 1.7
		// moving cycle options to separate option
		if ( isset( $options['cycle']['cycle-order'] ) ) {
			$old_cycle = $options['cycle'];
			$cycle     = array(
				'order'   => $old_cycle['cycle-order'],
				'effect'  => $old_cycle['cycle-effect'],
				'speed'   => $old_cycle['cycle-speed'],
				'timeout' => $old_cycle['cycle-timeout'],
				'pause'   => $old_cycle['cycle-pause'],
			);
			unset( $options['cycle'] );
			update_option( 'wpmtst_options', $options );
		}

		$cycle = array_merge( $default_cycle, $cycle );
		update_option( 'wpmtst_cycle', $cycle );
	}
	
	/**
	 * -5- GET FORM OPTIONS
	 *
	 * @since 1.13
	 */
	$form_options = get_option( 'wpmtst_form_options', array() );
	if ( ! $form_options ) {
		// -5A- NEW ACTIVATION
		$form_options = $default_form_options;
		
		// -5B- MOVE EXISTING OPTIONS
		if ( isset( $options['admin_notify'] ) ) {
			$form_options['admin_notify']    = $options['admin_notify'];
			$form_options['admin_email']     = $options['admin_email'];
			$form_options['captcha']         = $options['captcha'];
			$form_options['honeypot_before'] = $options['honeypot_before'];
			$form_options['honeypot_after']  = $options['honeypot_after'];	
			
			unset( $options['admin_notify'] );
			unset( $options['admin_email'] );
			unset( $options['captcha'] );
			unset( $options['honeypot_before'] );
			unset( $options['honeypot_after'] );
			update_option( 'wpmtst_options', $options );
		}
		
		update_option( 'wpmtst_form_options', $form_options );
		// WPML
		wpmtst_form_messages_wpml( $form_options['messages'] );
		wpmtst_form_options_wpml( $form_options );
	} else {
		// -5C- UPDATE
		/**
		 * Update single email recipient to multiple.
		 * 
		 * @since 1.18
		 */
		$recipients = array(
			array(
				'admin_name'       => isset( $form_options['admin_name'] ) ? $form_options['admin_name'] : '',
				'admin_site_email' => isset( $form_options['admin_site_email'] ) ? $form_options['admin_site_email'] : 1,
				'admin_email'      => isset( $form_options['admin_email'] ) ? $form_options['admin_email'] : '',
				'primary'          => 1,  // cannot be deleted
			),
		);
		$form_options['recipients'] = $recipients;
		
		unset( $form_options['admin_name'] );
		unset( $form_options['admin_site_email'] );
		unset( $form_options['admin_email'] );
		
		// Merge in new options
		$form_options = array_merge( $default_form_options, $form_options );
		update_option( 'wpmtst_form_options', $form_options );
		// WPML
		wpmtst_form_messages_wpml( $form_options['messages'] );
		wpmtst_form_options_wpml( $form_options );
	}

	/**
	 * -6- GET VIEW OPTIONS
	 *
	 * @since 1.21.0
	 */
	$view_options = get_option( 'wpmtst_view_options' );
	if ( ! $view_options ) {
		// -6A- NEW ACTIVATION
		update_option( 'wpmtst_view_options', $default_view_options );
	} else {
		// -6B- UPDATE
		// Merge in new options
		$view_options = array_merge( $default_view_options, $view_options );
		update_option( 'wpmtst_view_options', $view_options );
	}

	/**
	 * -7- GET DEFAULT VIEW
	 *
	 * @since 1.21.0
	 */
	$view_default = get_option( 'wpmtst_view_default' );
	if ( ! $view_default ) {
		// -7A- NEW ACTIVATION
		update_option( 'wpmtst_view_default', $default_view );
	} else {
		// -7B- UPDATE
		// Merge in new options
		$view_default = array_merge( $default_view, $view_default );
		update_option( 'wpmtst_view_default', $view_default );
	}
	
	/**
	 * Update views
	 */
	$views = wpmtst_get_views();
	foreach ( $views as $view ) {
		$view['data'] = array_merge( $view_default, unserialize( $view['value'] ) );
		wpmtst_save_view( $view );
	}

	/**
	 * -8- GET L10N CONTEXTS
	 * 
	 * @since 1.21.0
	 */
	$contexts = get_option( 'wpmtst_l10n_contexts' );
	if ( ! $contexts ) {
		// -8A- NEW ACTIVATION
		update_option( 'wpmtst_l10n_contexts', $default_l10n_contexts );
	} else {
		// -8B- UPDATE
		// Merge in new options
		$contexts = array_merge( $default_l10n_contexts, $contexts );
		update_option( 'wpmtst_l10n_contexts', $contexts );
	}

	/**
	 * Final step: Update the plugin version.
	 */
	update_option( 'wpmtst_plugin_version', $plugin_version );
	delete_option( 'wpmtst_admin_notices' );
	delete_option( 'wpmtst_news_flag' );
	// if ( version_compare( $current_plugin_version, '1.21', '<' ) ) {
		// add_option( 'wpmtst_update_redirect', true );
	// }
	
}
