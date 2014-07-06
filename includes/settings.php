<?php
/**
 * Strong Testimonials - Settings functions
 */
 
 
/*
 * Menus
 */
function wpmtst_settings_menu() {
	add_submenu_page( 'edit.php?post_type=wpm-testimonial', // $parent_slug
										'Settings',                           // $page_title
										'Settings',                           // $menu_title
										'manage_options',                     // $capability
										'settings',                           // $menu_slug
										'wpmtst_settings_page' );             // $function

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
										'Fields',
										'Fields',
										'manage_options',
										'fields',
										'wpmtst_settings_custom_fields' );

	add_submenu_page( 'edit.php?post_type=wpm-testimonial',
										'Shortcodes',
										'Shortcodes',
										'manage_options',
										'shortcodes',
										'wpmtst_settings_shortcodes' );

	add_action( 'admin_init', 'wpmtst_register_settings' );
}
add_action( 'admin_menu', 'wpmtst_settings_menu' );


/*
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

	if ( ! $need_unique )
		return;

	global $menu;

	foreach ( $menu as $key => $menu_item ) {
		// set unique menu title
		if ( 'Testimonials' == $menu_item[0] && 'edit.php?post_type=wpm-testimonial' == $menu_item[2] ) {
			$menu[$key][0] = 'Strong Testimonials';
		}
	}
}
add_action( 'admin_menu', 'wpmtst_unique_menu_title', 100 );


/*
 * Register settings
 */
function wpmtst_register_settings() {
	register_setting( 'wpmtst-settings-group', 'wpmtst_options', 'wpmtst_sanitize_options' );
}


/*
 * Sanitize settings
 */
function wpmtst_sanitize_options( $input ) {
	$input['per_page']      = (int) sanitize_text_field( $input['per_page'] );
	$input['admin_notify']  = isset( $input['admin_notify'] ) ? 1 : 0;
	$input['admin_email']   = sanitize_email( $input['admin_email'] );
	$input['cycle']['cycle-timeout'] = (float) sanitize_text_field( $input['cycle']['cycle-timeout'] );
	// $input['cycle']['cycle-effect']
	$input['cycle']['cycle-speed']   = (float) sanitize_text_field( $input['cycle']['cycle-speed'] );
	$input['cycle']['cycle-pause']   = isset( $input['cycle']['cycle-pause'] ) ? 1 : 0;

	return $input;
}


/*
 * Settings page
 */
function wpmtst_settings_page() {
	if ( ! current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$wpmtst_options = get_option( 'wpmtst_options' );
	$cycle_options = array(
			'effects' => array(
					'fade'       => 'Fade',
					// 'scrollHorz' => 'Scroll horizontally',
					// 'none'       => 'None',
			)
	);
	$order_list = array(
			'rand'   => 'Random',
			'recent' => 'Newest first',
			'oldest' => 'Oldest first'
	);

	// Build list of supported Captcha plugins.
	$plugins = array(
			'bwsmath' => array(
					'name' => 'Captcha by BestWebSoft',
					'file' => 'captcha/captcha.php',
					'active' => false
			),
			'wpmsrc'  => array(
					'name' => 'Simple reCAPTCHA by WP Mission',
					'file' => 'simple-recaptcha/simple-recaptcha.php',
					'active' => false
			),
			'miyoshi' => array(
					'name' => 'Really Simple Captcha by Takayuki Miyoshi',
					'file' => 'really-simple-captcha/really-simple-captcha.php',
					'active' => false
			),
	);

	foreach ( $plugins as $key => $plugin ) {
		$plugins[$key]['active'] = is_plugin_active( $plugin['file'] );
		// If current Captcha plugin has been deactivated, disable Captcha
		// so corresponding div does not appear on front-end form.
		if ( $key == $wpmtst_options['captcha'] && ! $plugins[$key]['active'] ) {
			$wpmtst_options['captcha'] = '';
			update_option( 'wpmtst_options', $wpmtst_options );
		}
	}
	?>
	<div class="wrap wpmtst">

		<h2><?php _e( 'Testimonial Settings', WPMTST_NAME ); ?></h2>

		<?php if( isset( $_GET['settings-updated'] ) ) : ?>
			<div id="message" class="updated">
				<p><strong><?php _e( 'Settings saved.' ) ?></strong></p>
			</div>
		<?php endif; ?>

		<form method="post" action="options.php">

			<?php settings_fields( 'wpmtst-settings-group' ); ?>
			<?php $wpmtst_options = get_option( 'wpmtst_options' ); ?>
			<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $wpmtst_options['default_template'] ); ?>" />
			
			<table class="form-table">

				<tr valign="top">
					<th scope="row">The number of testimonials to show per page</th>
					<td>
						<input type="text" name="wpmtst_options[per_page]" size="3" value="<?php echo esc_attr( $wpmtst_options['per_page'] ); ?>" />
						This applies to the <span class="code">[wpmtst-all]</span> shortcode.
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">When a new testimonial is submitted</th>
					<td>
						<label>
							<input id="wpmtst-options-admin-notify" type="checkbox" name="wpmtst_options[admin_notify]" <?php checked( $wpmtst_options['admin_notify'] ); ?> />
							<?php _e( 'Send notification email to', WPMTST_NAME ); ?>
						</label>
						<input id="wpmtst-options-admin-email" type="email" size="30" placeholder="email address" name="wpmtst_options[admin_email]" value="<?php echo esc_attr( $wpmtst_options['admin_email'] ); ?>" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">CAPTCHA plugin</th>
					<td>
						<select name="wpmtst_options[captcha]" autocomplete="off">
							<option value="">None</option>
							<?php foreach ( $plugins as $key => $plugin ) : ?>
							<?php if ( $plugin['active'] ) : ?>
							<option value="<?php echo $key; ?>" <?php selected( $wpmtst_options['captcha'], $key ); ?>><?php echo $plugin['name']; ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Cycle Shortcode Settings</th>
					<td>
						<div class="box">

							<div class="row">
								<div class="alpha">
									<label for="cycle-order"><?php _e( 'Order' ) ?>:</label>
								</div>
								<div>
									<select id="cycle-order" name="wpmtst_options[cycle][cycle-order]" autocomplete="off">
										<?php
										foreach ( $order_list as $order => $order_label ) {
											echo '<option value="' . $order . '"' . selected( $order, $wpmtst_options['cycle']['cycle-order'] ) . '>' . $order_label . '</option>';
										}
										?>
									</select>
								</div>
							</div>

							<div class="row">
								<div class="alpha">
									<label for="cycle-timeout"><?php _e( 'Show each for', WPMTST_NAME ); ?>:</label>
								</div>
								<div>
									<input type="text" id="cycle-timeout" name="wpmtst_options[cycle][cycle-timeout]" value="<?php echo $wpmtst_options['cycle']['cycle-timeout']; ?>" size="3" />
									<?php _e( 'seconds', WPMTST_NAME ); ?>
								</div>
							</div>

							<div class="row">
								<div class="alpha">
									<label for="cycle-effect"><?php _e( 'Transition effect', WPMTST_NAME ); ?>:</label>
								</div>
								<div>
									<select id="cycle-effect" name="wpmtst_options[cycle][cycle-effect]" autocomplete="off">
										<?php foreach ( $cycle_options['effects'] as $key => $label ) : ?>
										<option value="<?php echo $key; ?>" <?php selected( $wpmtst_options['cycle']['cycle-effect'], $key ); ?>><?php _e( $label ) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<p><em><a href="http://wordpress.org/support/topic/settings-bug-1" target="_blank">Fade is the only effect for now</a>.</em></p>
							</div>

							<div class="row">
								<div class="alpha">
									<label for="cycle-speed"><?php _e( 'Effect duration', WPMTST_NAME ); ?>:</label>
								</div>
								<div>
									<input type="text" id="cycle-speed" name="wpmtst_options[cycle][cycle-speed]" value="<?php echo $wpmtst_options['cycle']['cycle-speed']; ?>" size="3" />
									<?php _e( 'seconds', WPMTST_NAME ); ?>
								</div>
							</div>

							<div class="row">
								<div>
									<input type="checkbox" id="cycle-pause" name="wpmtst_options[cycle][cycle-pause]" <?php checked( $wpmtst_options['cycle']['cycle-pause'] ); ?>  class="checkbox" />
									<label for="cycle-pause"><?php _e( 'Pause on hover', WPMTST_NAME ); ?></label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e( 'Client Template', WPMTST_NAME ); ?></th>
					<td>
						<p><?php _e( 'Use these shortcode options to show client information below each testimonial', WPMTST_NAME ); ?>:</p>
						<div class="shortcode-example code">
							<p class="indent">
								<span class="outdent">[wpmtst-text </span>
										<br>field="{ <?php _e( 'your custom text field', WPMTST_NAME ); ?> }" 
										<br>class="{ <?php _e( 'your CSS class', WPMTST_NAME ); ?> }"]
							</p>
							<p class="indent">
								<span class="outdent">[wpmtst-link </span>
										<br>url="{ <?php _e( 'your custom URL field', WPMTST_NAME ); ?> }" 
										<br>text="{ <?php _e( 'your custom text field', WPMTST_NAME ); ?> }" 
										<br>target="_blank" <br>class="{ <?php _e( 'your CSS class', WPMTST_NAME ); ?> }"]
							</p>
						</div>
						
						<p><textarea id="client-section" class="widefat code" name="wpmtst_options[client_section]" rows="3"><?php echo $wpmtst_options['client_section']; ?></textarea></p>
						
						<p><input type="button" class="button" id="restore-default-template" value="Restore Default Template" /></p>
					</td>
				</tr>
				
			</table>

			<?php submit_button(); ?>

		</form>

	</div><!-- wrap -->

	<?php
}


/*
 * Shortcodes page
 */
function wpmtst_settings_shortcodes() {
	?>
	<div class="wrap wpmtst">

		<h2><?php _e( 'Shortcodes', WPMTST_NAME ); ?></h2>

		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>All Testimonials</h3></td>
			</tr>
			<tr>
				<td>Show all from all categories.</td><td>[wpmtst-all]</td>
			</tr>
			<tr>
				<td>Show all from a specific category.<br> Find these on the <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial' ); ?>">categories screen</a>.</td><td>[wpmtst-all category="xx"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>Testimonials Cycle</h3></td>
			</tr>
			<tr>
				<td>Cycle through all from all categories.</td><td>[wpmtst-cycle]</td>
			</tr>
			<tr>
				<td>Cycle through all from a specific category.<br> Find these on the <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial' ); ?>">categories screen</a>.</td><td>[wpmtst-cycle category="xx"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>Random Testimonial</h3></td>
			</tr>
			<tr>
				<td>Show a single random testimonial.</td><td>[wpmtst-random]</td>
			</tr>
			<tr>
				<td>Show a certain number of testimonials.</td><td>[wpmtst-random limit="x"]</td>
			</tr>
			<tr>
				<td>Show a single random testimonial from a specific category.<br>Find these on the <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial' ); ?>">categories screen</a>.</td>
				<td>[wpmtst-random category="xx"]</td>
			</tr>
			<tr>
				<td>Show a certain number from a specific category.</td><td>[wpmtst-random category="xx" limit="x"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>Single Testimonial</h3></td>
			</tr>
			<tr>
				<td> Show one specific testimonial.<br>Find these on the <a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial' ); ?>">testimonials screen</a>.</td><td>[wpmtst-single id="xx"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<td colspan="2"><h3>Testimonial Submission Form</h3></td>
			</tr>
			<tr>
				<td>Show a form for visitors to submit testimonials.<br>New testimonials are in "Pending" status until<br> published by an administrator.</td><td>[wpmtst-form]</td>
			</tr>
		</table>

	</div><!-- wrap -->
	<?php
}


/*
 * [Restore Default Template] event handler
 */
function wpmtst_restore_default_template_script() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#restore-default-template").click(function(e){
			var data = {
				'action' : 'wpmtst_restore_default_template',
			};
			$.get( ajaxurl, data, function( response ) {
				$("#client-section").val(response);
			});
		});
	});
	</script>
	<?php
}
add_action( 'admin_footer', 'wpmtst_restore_default_template_script' );


/*
 * [Restore Default Template] Ajax receiver
 */
function wpmtst_restore_default_template_function() {
	$options = get_option( 'wpmtst_options' );
	$template = $options['default_template'];
	echo $template;
	die();
}
add_action( 'wp_ajax_wpmtst_restore_default_template', 'wpmtst_restore_default_template_function' );
