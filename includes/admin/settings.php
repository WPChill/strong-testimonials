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
		_x( 'Guide', 'noun', 'strong-testimonials' ),
		_x( 'Guide', 'noun', 'strong-testimonials' ),
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
	register_setting( 'wpmtst-mf-license', 'wpmst_mf_license_key', 'wpmtst_sanitize_license' );
}
add_action( 'admin_init', 'wpmtst_register_settings' );

/**
 * Sanitize licenses.
 *
 * @param $new
 *
 * @return mixed
 */
function wpmtst_sanitize_license( $new ) {
	$old = get_option( 'wpmst_mf_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'wpmst_mf_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

/**
 * Check for active add-ons.
 *
 * @since 2.1
 */
function wpmtst_active_addons() {
	$addons = array (
		'strong-testimonials-multiple-forms/strong-testimonials-multiple-forms.php',
	);
	return array_intersect( $addons, get_option( 'active_plugins' ) );
}

/**
 * Our add-on licenses.
 */
function wpmtst_licenses_settings() {
	include( 'settings/licenses.php' );
}

/**
 * Sanitize general settings
 *
 * @param $input
 *
 * @return mixed
 */
function wpmtst_sanitize_options( $input ) {
	/**
	 * Store values as 0 or 1.
	 * Checked checkbox value is "on".
	 * Unchecked checkboxes are not submitted.
	 */
	/* LONGHAND
	if ( isset( $input['reorder'] ) ) {
		if ( 'on' == $input['reorder'] ) { // checked checkbox
			$new_input['reorder'] = 1;
		} else { // hidden input
			$new_input['reorder'] = $input['reorder']; // 0 or 1
		}
	} else { // unchecked checkbox
		$new_input['reorder'] = 0;
	}
	*/

	// shorthand
	$input['reorder']           = !isset( $input['reorder'] ) ? 0 : ( 'on' == $input['reorder'] ? 1 : $input['reorder'] );
	$input['scrolltop']         = !isset( $input['scrolltop'] ) ? 0 : ( 'on' == $input['scrolltop'] ? 1 : $input['scrolltop'] );
	$input['remove_whitespace'] = !isset( $input['remove_whitespace'] ) ? 0 : ( 'on' == $input['remove_whitespace'] ? 1 : $input['remove_whitespace'] );
	$input['support_custom_fields'] = !isset( $input['support_custom_fields'] ) ? 0 : ( 'on' == $input['support_custom_fields'] ? 1 : $input['support_custom_fields'] );
	$input['support_comments'] = !isset( $input['support_comments'] ) ? 0 : ( 'on' == $input['support_comments'] ? 1 : $input['support_comments'] );
	$input['email_log_level']   = !isset( $input['email_log_level'] ) ? 1 : (int) $input['email_log_level'];

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
		$input['messages'][ $key ]['text'] = wp_kses_data( $message['text'] );
	}

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

		<h2><?php _e( 'Testimonial Settings', 'strong-testimonials' ); ?></h2>

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
			<?php if ( wpmtst_active_addons() ): ?>
			<a href="<?php echo add_query_arg( 'tab', 'licenses', $url ); ?>" class="nav-tab <?php echo $active_tab == 'licenses' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Licenses', 'strong-testimonials' ); ?></a>
			<?php endif; ?>
		</h2>

		<form id="<?php echo $active_tab; ?>-form" method="post" action="options.php">
			<?php
			switch( $active_tab ) {
				case 'licenses' :
					settings_fields( 'wpmtst-mf-license' );
					wpmtst_licenses_settings();
					break;
				case 'form' :
					settings_fields( 'wpmtst-form-group' );
					wpmtst_form_settings();
					break;
				default :
					settings_fields( 'wpmtst-settings-group' );
					include( 'settings/general.php' );
			}
			?>
			<p>
				<input id="submit" class="button button-primary" type="submit" value="<?php _e( 'Save Changes' ); ?>" name="submit">
			</p>
		</form>

	</div><!-- wrap -->
	<?php
}

/**
 * Our form settings.
 */
function wpmtst_form_settings() {
	$form_options = get_option( 'wpmtst_form_options' );

	/**
	 * Build list of supported Captcha plugins.
	 *
	 * TODO - Move this to options array and add filter
	 */
	$plugins = array(
		'bwsmath' => array(
			'name'      => 'Captcha by BestWebSoft',
			'file'      => 'captcha/captcha.php',
			'settings'  => 'admin.php?page=captcha.php',
			'search'    => 'plugin-install.php?tab=search&s=Captcha',
			'url'       => 'http://wordpress.org/plugins/captcha/',
			'installed' => false,
			'active'    => false,
		),
		'miyoshi' => array(
			'name'      => 'Really Simple Captcha by Takayuki Miyoshi',
			'file'      => 'really-simple-captcha/really-simple-captcha.php',
			'search'    => 'plugin-install.php?tab=search&s=Really+Simple+Captcha',
			'url'       => 'http://wordpress.org/plugins/really-simple-captcha/',
			'installed' => false,
			'active'    => false,
		),
		'advnore' => array(
			'name'      => 'Advanced noCaptcha reCaptcha by Shamim Hasan',
			'file'      => 'advanced-nocaptcha-recaptcha/advanced-nocaptcha-recaptcha.php',
			'settings'  => 'admin.php?page=anr-admin-settings',
			'search'    => 'plugin-install.php?tab=search&s=Advanced+noCaptcha+reCaptcha',
			'url'       => 'http://wordpress.org/plugins/advanced-nocaptcha-recaptcha',
			'installed' => false,
			'active'    => false,
		),
	);

	foreach ( $plugins as $key => $plugin ) {

		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin['file'] ) )
			$plugins[ $key ]['installed'] = true;

		$plugins[ $key ]['active'] = is_plugin_active( $plugin['file'] );

		// If current Captcha plugin has been deactivated, disable Captcha
		// so corresponding div does not appear on front-end form.
		if ( $key == $form_options['captcha'] && !$plugins[ $key ]['active'] ) {
			$form_options['captcha'] = '';
			update_option( 'wpmtst_form_options', $form_options );
		}

	}

	include( 'settings/form.php' );
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
	$input = $_REQUEST['field'];
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

	wpmtst_form_messages_wpml( $form_options['messages'] );
	wpmtst_form_options_wpml( $form_options );
}
add_action( 'update_option_wpmtst_form_options', 'wpmtst_on_update_form_options', 10, 2 );
