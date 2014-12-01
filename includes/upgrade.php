<?php
/**
 * Plugin activation and upgrade.
 *
 * @package Strong_Testimonials
 */

function wpmtst_default_settings() {
	// placeholders
	$cycle = array();
	$form_options = array();
	
	// -1- DEFAULTS
	$plugin_data    = get_plugin_data( dirname( dirname( __FILE__ ) ) . '/strong-testimonials.php', false );
	$plugin_version = $plugin_data['Version'];
	include( WPMTST_INC . 'defaults.php' );

	// -2- GET OPTIONS
	$options = get_option( 'wpmtst_options' );
	if ( ! $options ) {
		// -2A- NEW ACTIVATION
		update_option( 'wpmtst_options', $default_options );
	}
	else {
		// -2B- UPDATE
		if ( ! isset( $options['plugin_version'] )
					|| $options['plugin_version'] != $plugin_version 
					|| 'strong.dev' == $_SERVER['SERVER_NAME'] ) {

			// Fix captcha inconsistency
			if ( isset( $options['captcha'] ) && 'none' == $options['captcha'] )
				$options['captcha'] = '';
				
			// Change target parameter in client section
			$options['default_template'] = str_replace( 'target="_blank"', 'new_tab', $options['default_template'] );
			
			// Merge in new options
			$options = array_merge( $default_options, $options );
			$options['plugin_version'] = $plugin_version;
			update_option( 'wpmtst_options', $options );
		}
	}
	
	// -3- GET FIELDS
	$fields = get_option( 'wpmtst_fields' );
	if ( ! $fields ) {
		// -3A- NEW ACTIVATION
		update_option( 'wpmtst_fields', $default_fields );
	}
	
	// -4- GET CYCLE
	$cycle = get_option( 'wpmtst_cycle' );
	if ( ! $cycle ) {
		// -4A- NEW ACTIVATION
		update_option( 'wpmtst_cycle', $default_cycle );
	}
	else {
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
			$cycle = array(
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
	
	/*
	 * -5- GET FORM OPTIONS
	 *
	 * @since 1.13
	 */
	$form_options = get_option( 'wpmtst_form_options' );
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
	}
	else {
		// -5C- UPDATE
		if ( ! isset( $options['plugin_version'] )
					|| $options['plugin_version'] != $plugin_version 
					|| 'strong.dev' == $_SERVER['SERVER_NAME'] ) {

			// Merge in new options
			$form_options = array_merge( $default_form_options, $form_options );
			update_option( 'wpmtst_form_options', $form_options );
		}
	}
	
}
