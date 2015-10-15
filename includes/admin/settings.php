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
		__( 'Settings', 'strong-testimonials' ),
		__( 'Settings', 'strong-testimonials' ),
		'manage_options',
		'settings',
		'wpmtst_settings_page' );

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
		__( 'Views', 'strong-testimonials' ),
		__( 'Views', 'strong-testimonials' ),
		'manage_options',
		'views',
		'wpmtst_views' );

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
		__( 'Fields', 'strong-testimonials' ),
		__( 'Fields', 'strong-testimonials' ),
		'manage_options',
		'fields',
		'wpmtst_settings_custom_fields' );

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
		_x( 'Guide', 'noun', 'strong-testimonials' ),
		'<span id="wpmtst-guide">' . _x( 'Guide', 'noun', 'strong-testimonials' ) . '</span>',
		'manage_options',
		'guide',
		'wpmtst_guide' );

	if ( ! is_multisite() ) {
		add_submenu_page( 'admin.php',
			__( 'Welcome', 'strong-testimonials' ),
			__( 'Welcome', 'strong-testimonials' ),
			'manage_options',
			'strong-testimonials-welcome',
			'wpmtst_welcome' );
	}
}

add_action( 'admin_menu', 'wpmtst_settings_menu' );

/**
 * Make admin menu title unique if necessary.
 */
function wpmtst_unique_menu_title() {
	$need_unique = false;

	// GC Testimonials
	if ( is_plugin_active( 'gc-testimonials/testimonials.php' ) )
		$need_unique = true;

	// Testimonials by Aihrus
	if ( is_plugin_active( 'testimonials-widget/testimonials-widget.php' ) )
		$need_unique = true;

	// Clean Testimonials
	if ( is_plugin_active( 'clean-testimonials/clean-testimonials.php' ) )
		$need_unique = true;
		
	// Easy Testimonials
	if ( is_plugin_active( 'easy-testimonials/easy-testimonials.php' ) )
		$need_unique = true;
	
	if ( ! $need_unique ) return;

	global $menu;

	foreach ( $menu as $key => $menu_item ) {
		// set unique menu title
		if ( 'Testimonials' == $menu_item[0] && 'edit.php?post_type=wpm-testimonial' == $menu_item[2] ) {
			$menu[$key][0] = 'Strong Testimonials';
		}
	}
}
add_action( 'admin_menu', 'wpmtst_unique_menu_title', 100 );

/**
 * Register settings
 */
function wpmtst_register_settings() {
	register_setting( 'wpmtst-settings-group', 'wpmtst_options',      'wpmtst_sanitize_options' );
	register_setting( 'wpmtst-cycle-group',    'wpmtst_cycle',        'wpmtst_sanitize_cycle' );
	register_setting( 'wpmtst-form-group',     'wpmtst_form_options', 'wpmtst_sanitize_form' );
}
add_action( 'admin_init', 'wpmtst_register_settings' );

/**
 * Sanitize general settings
 */
function wpmtst_sanitize_options( $input ) {
	$input['per_page']          = (int) sanitize_text_field( $input['per_page'] );
	$input['load_page_style']   = isset( $input['load_page_style'] ) ? 1 : 0;
	$input['load_widget_style'] = isset( $input['load_widget_style'] ) ? 1 : 0;
	$input['load_form_style']   = isset( $input['load_form_style'] ) ? 1 : 0;
	$input['load_rtl_style']    = isset( $input['load_rtl_style'] ) ? 1 : 0;
	$input['reorder']           = isset( $input['reorder'] ) ? 1 : 0;
	return $input;
}

/**
 * Sanitize cycle settings
 */
function wpmtst_sanitize_cycle( $input ) {
	// an unchecked checkbox is not posted
	// radio buttons always return a value, no need to sanitize
	
	$input['category']   = strip_tags( $input['category'] );
	// $input['order']
	// $input['all']
	$input['limit']      = (int) strip_tags( $input['limit'] );
	$input['title']      = isset( $input['title'] ) ? 1 : 0;
	// $input['content']
	$input['char_limit'] = (int) sanitize_text_field( $input['char_limit'] );
	$input['images']     = isset( $input['images'] ) ? 1 : 0;
	$input['client']     = isset( $input['client'] ) ? 1 : 0;
	// $input['more']
	$input['more_page']  = strip_tags( $input['more_page'] );
	$input['timeout']    = (float) sanitize_text_field( $input['timeout'] );
	$input['effect']     = strip_tags( $input['effect'] );
	$input['speed']      = (float) sanitize_text_field( $input['speed'] );
	$input['pause']      = isset( $input['pause'] ) ? 1 : 0;
	
	return $input;
}

/**
 * Sanitize form settings
 *
 * An unchecked checkbox is not posted.
 * 
 * @since 1.13
 */
function wpmtst_sanitize_form( $input ) {
	$input['post_status']       = sanitize_text_field( $input['post_status'] );
	$input['admin_notify']      = isset( $input['admin_notify'] ) ? 1 : 0;
	//$input['sender_name']       = isset( $input['sender_name'] ) ? sanitize_text_field( $input['sender_name'] ) : '';
	$input['sender_name']       = sanitize_text_field( $input['sender_name'] );
	//$input['sender_site_email'] = isset( $input['sender_site_email'] ) ? intval( $input['sender_site_email'] ) : '';
	$input['sender_site_email'] = intval( $input['sender_site_email'] );
	//$input['sender_email']      = isset( $input['sender_email'] ) ? sanitize_email( $input['sender_email'] ) : '';
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
	//if ( isset( $input['recipients'] ) ) {
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
	//}
	$input['recipients'] = $new_recipients;
	
	$input['default_recipient'] = maybe_unserialize( $input['default_recipient'] ); 
	$input['email_subject']     = isset( $input['email_subject'] ) ? sanitize_text_field( $input['email_subject'] ) : '';
	$input['email_message']     = isset( $input['email_message'] ) ? wp_kses_post( $input['email_message'] ) : '';
	$input['honeypot_before']   = isset( $input['honeypot_before'] ) ? 1 : 0;
	$input['honeypot_after']    = isset( $input['honeypot_after'] ) ? 1 : 0;
	$input['captcha']           = sanitize_text_field( $input['captcha'] );
	
	foreach ( $input['messages'] as $key => $message ) {
		$input['messages'][$key]['text'] = sanitize_text_field( $message['text'] );
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
				<p><strong><?php _e( 'Settings saved.' ) ?></strong></p>
			</div>
		<?php endif; ?>

		<?php 
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		$url = admin_url( 'edit.php?post_type=wpm-testimonial&page=settings' )
		?>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo add_query_arg( 'tab', 'general', $url ); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _ex( 'General', 'adjective', 'strong-testimonials' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'form', $url ); ?>" class="nav-tab <?php echo $active_tab == 'form' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Form', 'strong-testimonials' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'cycle', $url ); ?>" class="nav-tab <?php echo $active_tab == 'cycle' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Cycle Shortcode', 'strong-testimonials' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'client', $url ); ?>" class="nav-tab <?php echo $active_tab == 'client' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Client Section', 'strong-testimonials' ); ?></a>
		</h2>
		
		<form id="<?php echo $active_tab; ?>-form" method="post" action="options.php">
			<?php
			switch( $active_tab ) {
				case 'form' :
					settings_fields( 'wpmtst-form-group' );
					wpmtst_form_settings();
					do_action( 'wpmtst_settings_form_tab' );
					break;
				case 'client' :
					settings_fields( 'wpmtst-settings-group' );
					wpmtst_client_settings();
					break;
				case 'cycle' :
					settings_fields( 'wpmtst-cycle-group' );
					wpmtst_cycle_settings();
					break;
				default :
					settings_fields( 'wpmtst-settings-group' );
					wpmtst_settings();
			}
			?>
			<p class="submit">
				<input id="submit" class="button button-primary" type="submit" value="<?php _e( 'Save Changes' ); ?>" name="submit">
				<span class="reminder"><?php _e( 'Remember to save changes before switching tabs.', 'strong-testimonials' ); ?></span>
			</p>
		</form>
		
	</div><!-- wrap -->
	<?php
}

/**
 * General settings
 */
function wpmtst_settings() {
	$options = get_option( 'wpmtst_options' );	
	include( 'forms/settings/general.php' );
}

/**
 * Cycle shortcode settings
 */
function wpmtst_cycle_settings() {
	$cycle = get_option( 'wpmtst_cycle' );
	
	$order_list = array(
			'rand'   => _x( 'Random', 'display order', 'strong-testimonials' ),
			'menu'   => _x( 'Menu order', 'display order', 'strong-testimonials' ),
			'recent' => _x( 'Newest first', 'display order', 'strong-testimonials' ),
			'oldest' => _x( 'Oldest first', 'display order', 'strong-testimonials' ),
	);

	$category_list = get_terms( 'wpm-testimonial-category', array(
			'hide_empty' 	=> false,
			'order_by'		=> 'name',
			'pad_counts'	=> true
	) );

	$pages_list = get_pages( array(
			'sort_order'  => 'ASC',
			'sort_column' => 'post_title',
			'post_type'   => 'page',
			'post_status' => 'publish'
	) );
	
	include( 'forms/settings/cycle.php' );
}

/**
 * Client section settings
 */
function wpmtst_client_settings() {
	$options = get_option( 'wpmtst_options' );

	// ----------------------------
	// Build list of custom fields.
	// ----------------------------
	$field_options = get_option( 'wpmtst_fields' );
	$field_groups = $field_options['field_groups'];
	$current_field_group = $field_options['current_field_group'];  // "custom", only one for now
	$fields = $field_groups[$current_field_group]['fields'];
	$fields_array = array();
	foreach ( $fields as $field ) {
		if ( ! in_array( $field['name'], array( 'post_title', 'post_content', 'featured_image' ) ) ) {
			$fields_array[] = '<span class="code wide">' . $field['name'] . '</span>';
		}
	}
	
	include( 'forms/settings/client.php' );
}

/**
 * Form settings
 */
function wpmtst_form_settings() {
	$form_options = get_option( 'wpmtst_form_options' );

	/**
	 * Build list of supported Captcha plugins.
	 *
	 * TODO - Move this to options array
	 */
	$plugins = array(
		'bwsmath' => array(
			'name' => 'Captcha by BestWebSoft',
			'file' => 'captcha/captcha.php',
			'settings' => 'admin.php?page=captcha.php',
			'search' => 'plugin-install.php?tab=search&s=Captcha',
			'url' => 'http://wordpress.org/plugins/captcha/',
			'installed' => false,
			'active' => false
		),
		'miyoshi' => array(
			'name' => 'Really Simple Captcha by Takayuki Miyoshi',
			'file' => 'really-simple-captcha/really-simple-captcha.php',
			'search' => 'plugin-install.php?tab=search&s=Really+Simple+Captcha',
			'url' => 'http://wordpress.org/plugins/really-simple-captcha/',
			'installed' => false,
			'active' => false
		),
		'advnore'  => array(
			'name' => 'Advanced noCaptcha reCaptcha by Shamim Hasan',
			'file' => 'advanced-nocaptcha-recaptcha/advanced-nocaptcha-recaptcha.php',
			'settings' => 'admin.php?page=anr-admin-settings',
			'search' => 'plugin-install.php?tab=search&s=Advanced+noCaptcha+reCaptcha',
			'url' => 'http://wordpress.org/plugins/advanced-nocaptcha-recaptcha',
			'installed' => false,
			'active' => false
		),
	);

	foreach ( $plugins as $key => $plugin ) {
	
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin['file'] ) ) 
			$plugins[$key]['installed'] = true;
			
		$plugins[$key]['active'] = is_plugin_active( $plugin['file'] );
		
		// If current Captcha plugin has been deactivated, disable Captcha
		// so corresponding div does not appear on front-end form.
		if ( $key == $form_options['captcha'] && ! $plugins[$key]['active'] ) {
			$form_options['captcha'] = '';
			update_option( 'wpmtst_form_options', $form_options );
		}
		
	}

	include( 'forms/settings/form.php' );
}

/**
 * [Restore Default Messages] event handler
 *
 * @since 1.13
 */
function wpmtst_restore_default_messages_script() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		$("#restore-default-messages").click(function(e){
			var data = {
				'action' : 'wpmtst_restore_default_messages'
			};
			$.get( ajaxurl, data, function( response ) {
				var object = JSON.parse( response );
				for (var key in object) {
					if (object.hasOwnProperty(key)) {
						$("input[id='" + key + "']").val( object[key]["text"] );
					}
				}
			});
		});

		$(".restore-default-message").click(function(e){
			var input = $(e.target).closest("tr").find("input[type='text']").attr("id");
			var data = {
				'action' : 'wpmtst_restore_default_message',
				'field'  : input
			};
			$.get( ajaxurl, data, function( response ) {
				var object = JSON.parse( response );
				$("input[id='" + input + "']").val( object["text"] );
			});
		});

	});
	</script>
	<?php
}
add_action( 'wpmtst_settings_form_tab', 'wpmtst_restore_default_messages_script' );

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
