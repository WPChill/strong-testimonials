<?php
/**
 * Settings
 *
 * @package Strong_Testimonials
 */

/**
 * Menus
 */
function wpmtst_settings_menu() {
	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
		__( 'Views', 'strong-testimonials' ),  // page title
		__( 'Views', 'strong-testimonials' ),  // menu title
		'manage_options',
		'testimonial-views',
		'wpmtst_views_admin' );

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
		apply_filters( 'wpmtst_fields_page_title', __( 'Fields', 'strong-testimonials' ) ),
		apply_filters( 'wpmtst_fields_menu_title', __( 'Fields', 'strong-testimonials' ) ),
		'manage_options',
		'testimonial-fields',
		'wpmtst_form_admin' );

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
		__( 'Settings', 'strong-testimonials' ),
		__( 'Settings', 'strong-testimonials' ),
		'manage_options',
		'testimonial-settings',
		'wpmtst_settings_page' );

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
		__( 'Guide', 'strong-testimonials' ),
		__( 'Guide', 'strong-testimonials' ),
		'manage_options',
		'testimonial-guide',
		'wpmtst_guide' );
}
add_action( 'admin_menu', 'wpmtst_settings_menu' );

/**
 * Register settings
 */
function wpmtst_register_settings() {
	register_setting( 'wpmtst-settings-group', 'wpmtst_options', 'wpmtst_sanitize_options' );
	register_setting( 'wpmtst-form-group', 'wpmtst_form_options', 'wpmtst_sanitize_form' );
	register_setting( 'wpmtst-license-group', 'wpmtst_addons', 'wpmtst_sanitize_licenses' );
}
add_action( 'admin_init', 'wpmtst_register_settings' );


/**
 * Sanitize licenses.
 *
 * @param $new_licenses
 *
 * @return mixed
 */
function wpmtst_sanitize_licenses( $new_licenses ) {
	$old_licenses = get_option( 'wpmtst_addons' );
	// Check existence. May have been erased by Reset plugin.
	if ( $old_licenses ) {
		foreach ( $new_licenses as $addon => $new_info ) {
			$old_license = isset( $old_licenses[ $addon ]['license'] ) ? $old_licenses[ $addon ]['license'] : '';
			if ( isset( $old_license['key'] ) && $old_license['key'] != $new_info['license']['key'] ) {
				// new license has been entered, so must reactivate
				unset( $new_licenses[ $addon ]['license']['status'] );
			}
		}
	}

	return $new_licenses;
}


/**
 * Check for active add-ons.
 *
 * @since 2.1
 */
function wpmtst_active_addons() {
	return has_action( 'wpmtst_licenses' );
}


/**
 * Sanitize general settings
 *
 * @param $input
 *
 * @return mixed
 */
function wpmtst_sanitize_options( $input ) {
	$input['email_log_level']       = ! isset( $input['email_log_level'] ) ? 1 : (int) $input['email_log_level'];
	$input['embed_width']           = intval( sanitize_text_field( $input['embed_width'] ) );
	$input['load_font_awesome']     = wpmtst_sanitize_checkbox( $input, 'load_font_awesome' );
	$input['nofollow']              = wpmtst_sanitize_checkbox( $input, 'nofollow' );
	$input['pending_indicator']     = wpmtst_sanitize_checkbox( $input, 'pending_indicator' );
	$input['remove_whitespace']     = wpmtst_sanitize_checkbox( $input, 'remove_whitespace' );
	$input['reorder']               = wpmtst_sanitize_checkbox( $input, 'reorder' );
	$input['scrolltop']             = wpmtst_sanitize_checkbox( $input, 'scrolltop' );
	$input['scrolltop_offset']      = intval( sanitize_text_field( $input['scrolltop_offset'] ) );
	$input['support_comments']      = wpmtst_sanitize_checkbox( $input, 'support_comments' );
	$input['support_custom_fields'] = wpmtst_sanitize_checkbox( $input, 'support_custom_fields' );

	return $input;
}

/**
 * Sanitize form settings
 *
 * An unchecked checkbox is not posted.
 *
 * @param $input
 * @since 1.13
 *
 * @return mixed
 */
function wpmtst_sanitize_form( $input ) {
	$input['post_status']       = sanitize_text_field( $input['post_status'] );
	$input['admin_notify']      = isset( $input['admin_notify'] ) ? 1 : 0;
	$input['mail_queue']        = isset( $input['mail_queue'] ) ? 1 : 0;
	$input['sender_name']       = sanitize_text_field( $input['sender_name'] );
	$input['sender_site_email'] = intval( $input['sender_site_email'] );
	$input['sender_email']      = sanitize_email( $input['sender_email'] );
	if ( ! $input['sender_email'] && ! $input['sender_site_email'] ) {
		$input['sender_site_email'] = 1;
	}

	/**
	 * Multiple recipients.
	 *
	 * @since 1.18
	 */
	$new_recipients = array();
	foreach ( $input['recipients'] as $recipient ) {

		if ( isset( $recipient['primary'] ) ) {
			$recipient['primary'] = 1;
			if ( isset( $recipient['admin_site_email'] ) && ! $recipient['admin_site_email'] ) {
				if ( ! $recipient['admin_email'] ) {
					$recipient['admin_site_email'] = 1;
				}
			}
		} else {
			// Don't save if both fields are empty.
			if ( ! isset( $recipient['admin_name'] ) && ! isset( $recipient['admin_email'] ) ) {
				continue;
			}
			if ( ! $recipient['admin_name'] && ! $recipient['admin_email'] ) {
				continue;
			}
		}

		if ( isset( $recipient['admin_name'] ) ) {
			$recipient['admin_name'] = sanitize_text_field( $recipient['admin_name'] );
		}

		if ( isset( $recipient['admin_email'] ) ) {
			$recipient['admin_email'] = sanitize_email( $recipient['admin_email'] );
		}

		$new_recipients[] = $recipient;

	}
	$input['recipients'] = $new_recipients;

	$input['default_recipient'] = maybe_unserialize( $input['default_recipient'] );
	$input['email_subject']     = isset( $input['email_subject'] ) ? sanitize_text_field( $input['email_subject'] ) : '';
	$input['email_message']     = isset( $input['email_message'] ) ? wp_kses_post( $input['email_message'] ) : '';

	$input['honeypot_before']   = isset( $input['honeypot_before'] ) ? 1 : 0;
	$input['honeypot_after']    = isset( $input['honeypot_after'] ) ? 1 : 0;
	$input['captcha']           = sanitize_text_field( $input['captcha'] );

	foreach ( $input['messages'] as $key => $message ) {
	    if ( 'submission-success' == $key ) {
			$input['messages'][ $key ]['text'] = $message['text'];
		} else {
	        if ( 'required-field' == $key ) {
	            $input['messages'][ $key ]['enabled'] = wpmtst_sanitize_checkbox( $input['messages'][ $key ], 'enabled' );
	        }
			$input['messages'][ $key ]['text'] = wp_kses_data( $message['text'] );
		}
	}

	$input['scrolltop_error']          = wpmtst_sanitize_checkbox( $input, 'scrolltop_error' );
	$input['scrolltop_error_offset']   = intval( sanitize_text_field( $input['scrolltop_error_offset'] ) );
	$input['scrolltop_success']        = wpmtst_sanitize_checkbox( $input, 'scrolltop_success' );
	$input['scrolltop_success_offset'] = intval( sanitize_text_field( $input['scrolltop_success_offset'] ) );

	/**
	 * Success redirect
     * @since 2.18.0
	 */

	$input['success_action'] = sanitize_text_field( $input['success_action'] );

	if ( filter_var( $input['success_redirect_url'], FILTER_VALIDATE_URL ) ) {
		$input['success_redirect_url'] = wp_validate_redirect( $input['success_redirect_url'] );
	} else {
		$input['success_redirect_url'] = '';
	}

	// Check the "ID or slug" field next
    if ( isset( $input['success_redirect_2']) && $input['success_redirect_2'] ) {

        // is post ID?
        $id = sanitize_text_field( $input['success_redirect_2'] );
        if ( is_numeric( $id ) ) {
            if ( ! get_posts( array( 'p' => $id, 'post_type' => array( 'page' ), 'post_status' => 'publish' ) ) ) {
                $id = null;
            }
        } else {
            // is post slug?
            $target = get_posts( array( 'name' => $id, 'post_type' => array( 'page' ), 'post_status' => 'publish' ) );
            if ( $target ) {
                $id = $target[0]->ID;
            }
        }

        if ( $id ) {
            $input['success_redirect_id'] = $id;
        }

    } else {

        if ( isset( $input['success_redirect_id'] ) ) {
            $input['success_redirect_id'] = (int) sanitize_text_field( $input['success_redirect_id'] );
        }

    }

    unset( $input['success_redirect_2'] );
	ksort( $input );

	return $input;
}

/**
 * Settings page
 */
function wpmtst_settings_page() {
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	?>
	<div class="wrap wpmtst">

		<h1><?php _e( 'Testimonial Settings', 'strong-testimonials' ); ?></h1>

		<?php if( isset( $_GET['settings-updated'] ) ) : ?>
			<div id="message" class="updated notice is-dismissible">
				<p><?php _e( 'Settings saved.' ) ?></p>
			</div>
		<?php endif; ?>

		<?php
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		$url        = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings' );
		?>
		<h2 class="nav-tab-wrapper">

			<a href="<?php echo add_query_arg( 'tab', 'general', $url ); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _ex( 'General', 'adjective', 'strong-testimonials' ); ?></a>

			<a href="<?php echo add_query_arg( 'tab', 'form', $url ); ?>" class="nav-tab <?php echo $active_tab == 'form' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Form', 'strong-testimonials' ); ?></a>

			<?php do_action( 'wpmtst_settings_tabs', $active_tab, $url ); ?>

			<?php if ( wpmtst_active_addons() ): ?>
				<a href="<?php echo add_query_arg( 'tab', 'licenses', $url ); ?>" class="nav-tab <?php echo $active_tab == 'licenses' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Licenses', 'strong-testimonials' ); ?></a>
			<?php endif; ?>

		</h2>

		<form id="<?php echo $active_tab; ?>-form" method="post" action="options.php">
			<?php
			$callbacks = apply_filters( 'wpmtst_settings_callbacks', array(
				'general'  => 'wpmtst_settings_general',
				'form'     => 'wpmtst_settings_form',
				'licenses' => 'wpmtst_settings_licenses',
			) );
			if ( isset( $callbacks[ $active_tab ] ) && wpmtst_callback_exists( $callbacks[ $active_tab ] ) ) {
				call_user_func( $callbacks[ $active_tab ] );
			} else {
				call_user_func( $callbacks['general'] );
			}
			?>
			<p class="submit-row">
				<?php submit_button( '', 'primary', 'submit-form', false ); ?>
				<?php do_action( 'wpmtst_settings_submit_row'); ?>
			</p>
		</form>

	</div><!-- .wrap -->
	<?php
}

function wpmtst_settings_general() {
	settings_fields( 'wpmtst-settings-group' );
	include( 'partials/settings/general.php' );
}

function wpmtst_settings_form() {
	settings_fields( 'wpmtst-form-group' );
	include( 'partials/settings/form.php' );
}

function wpmtst_settings_licenses() {
	settings_fields( 'wpmtst-license-group' );
	include( 'partials/settings/licenses.php' );
}

/**
 * [Restore Default Messages] Ajax receiver
 *
 * @since 1.13
 */
function wpmtst_restore_default_messages_function() {
	// hard restore from file
	include_once WPMTST_INC . 'defaults.php';
	$default_form_options = wpmtst_get_default_form_options();
	$messages = $default_form_options['messages'];
	echo json_encode( $messages );
	die();
}
add_action( 'wp_ajax_wpmtst_restore_default_messages', 'wpmtst_restore_default_messages_function' );

/**
 * [Restore Default] for single message Ajax receiver
 *
 * @since 1.13
 */
function wpmtst_restore_default_message_function() {
	$input = str_replace( '_', '-', $_REQUEST['field'] );
	// hard restore from file
	include_once WPMTST_INC . 'defaults.php';
	$default_form_options = wpmtst_get_default_form_options();
	$message = $default_form_options['messages'][$input];
	echo json_encode( $message );
	die();
}
add_action( 'wp_ajax_wpmtst_restore_default_message', 'wpmtst_restore_default_message_function' );

/**
 * Update WPML string translations.
 *
 * @param $oldvalue
 * @param $newvalue
 */
function wpmtst_on_update_form_options( $oldvalue, $newvalue ) {
	$form_options = get_option( 'wpmtst_form_options' );
	if ( ! $form_options ) return;

	// WPML
	wpmtst_form_messages_wpml( $form_options['messages'] );
	wpmtst_form_options_wpml( $form_options );

	// Polylang
	wpmtst_form_messages_polylang( $form_options['messages'] );
	wpmtst_form_options_polylang( $form_options );
}
add_action( 'update_option_wpmtst_form_options', 'wpmtst_on_update_form_options', 10, 2 );
