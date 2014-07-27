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
	register_setting( 'wpmtst-cycle-group', 'wpmtst_cycle', 'wpmtst_sanitize_cycle' );
}


/*
 * Sanitize general settings
 */
function wpmtst_sanitize_options( $input ) {
	$input['per_page']          = (int) sanitize_text_field( $input['per_page'] );
	$input['admin_notify']      = isset( $input['admin_notify'] ) ? 1 : 0;
	$input['admin_email']       = sanitize_email( $input['admin_email'] );
	$input['load_page_style']   = isset( $input['load_page_style'] ) ? 1 : 0;
	$input['load_widget_style'] = isset( $input['load_widget_style'] ) ? 1 : 0;
	$input['load_form_style']   = isset( $input['load_form_style'] ) ? 1 : 0;
	return $input;
}


/*
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
	$input['char-limit'] = (int) sanitize_text_field( $input['char-limit'] );
	$input['images']     = isset( $input['images'] ) ? 1 : 0;
	$input['client']     = isset( $input['client'] ) ? 1 : 0;
	// $input['more']
	$input['more-page']  = strip_tags( $input['more-page'] );
	$input['timeout']    = (float) sanitize_text_field( $input['timeout'] );
	$input['effect']     = strip_tags( $input['effect'] );
	$input['speed']      = (float) sanitize_text_field( $input['speed'] );
	$input['pause']      = isset( $input['pause'] ) ? 1 : 0;
	
	return $input;
}

/*
 * Settings page
 */
function wpmtst_settings_page() {
	if ( ! current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap wpmtst">

		<h2><?php _e( 'Testimonial Settings', 'strong-testimonials' ); ?></h2>

		<?php if( isset( $_GET['settings-updated'] ) ) : ?>
			<div id="message" class="updated">
				<p><strong><?php _e( 'Settings saved.' ) ?></strong></p>
			</div>
		<?php endif; ?>

		<?php /* tabs */ ?>
		<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general'; ?>
		<h2 class="nav-tab-wrapper">
			<a href="?post_type=wpm-testimonial&page=settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'strong-testimonials' ); ?></a>
			<a href="?post_type=wpm-testimonial&page=settings&tab=cycle" class="nav-tab <?php echo $active_tab == 'cycle' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Cycle Shortcode', 'strong-testimonials' ); ?></a>
		</h2>

		<form method="post" action="options.php">
			<?php
			if( $active_tab == 'general' ) {
				settings_fields( 'wpmtst-settings-group' );
				wpmtst_settings_section();
			} 
			else {
				settings_fields( 'wpmtst-cycle-group' );
				wpmtst_cycle_section();
			}
			submit_button();
			?>
		</form>

	</div><!-- wrap -->

	<?php
}

function wpmtst_settings_section() {
	$options = get_option( 'wpmtst_options' );

	// Build list of supported Captcha plugins.
	// @TODO - Move this to options array
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
		if ( $key == $options['captcha'] && ! $plugins[$key]['active'] ) {
			$options['captcha'] = '';
			update_option( 'wpmtst_options', $options );
		}
	}
	?>
	<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>" />
	
	<table class="form-table" cellpadding="0" cellspacing="0">
	
	<tr valign="top">
	<th scope="row"><?php _e( 'Load stylesheets', 'strong-testimonials' );?></th>
	<td class="stackem">
		<label>
			<input type="checkbox" name="wpmtst_options[load_page_style]" <?php checked( $options['load_page_style'] ); ?> />
			<?php _e( 'Pages', 'strong-testimonials' ); ?>
		</label>
		<label>
			<input type="checkbox" name="wpmtst_options[load_widget_style]" <?php checked( $options['load_widget_style'] ); ?> />
			<?php _e( 'Widget', 'strong-testimonials' ); ?>
		</label>
		<label>
			<input type="checkbox" name="wpmtst_options[load_form_style]" <?php checked( $options['load_form_style'] ); ?> />
			<?php _e( 'Submission Form', 'strong-testimonials' ); ?>
		</label>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e( 'The number of testimonials to show per page', 'strong-testimonials' ); ?></th>
	<td>
		<input type="text" name="wpmtst_options[per_page]" size="3" value="<?php echo esc_attr( $options['per_page'] ); ?>" />
		<?php echo sprintf( __( 'This applies to the %s shortcode.', 'strong-testimonials' ), '<span class="code">[wpmtst-all]</span>' ); ?>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e( 'When a new testimonial is submitted', 'strong-testimonials' );?></th>
	<td>
		<label>
			<input id="wpmtst-options-admin-notify" type="checkbox" name="wpmtst_options[admin_notify]" <?php checked( $options['admin_notify'] ); ?> />
			<?php _e( 'Send notification email to', 'strong-testimonials' ); ?>
		</label>
		<input id="wpmtst-options-admin-email" type="email" size="30" placeholder="email address" name="wpmtst_options[admin_email]" value="<?php echo esc_attr( $options['admin_email'] ); ?>" />
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e( 'CAPTCHA plugin', 'strong-testimonials' );?></th>
	<td>
		<select name="wpmtst_options[captcha]" autocomplete="off">
			<option value=""><?php _e( 'none' );?></option>
			<?php foreach ( $plugins as $key => $plugin ) : ?>
			<?php if ( $plugin['active'] ) : ?>
			<option value="<?php echo $key; ?>" <?php selected( $options['captcha'], $key ); ?>><?php echo $plugin['name']; ?></option>
			<?php endif; ?>
			<?php endforeach; ?>
		</select>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e( 'Client Template', 'strong-testimonials' ); ?></th>
	<td>
		<p><?php _e( 'Use these shortcode options to show client information below each testimonial', 'strong-testimonials' ); ?>:</p>
		<div class="shortcode-example code">
			<p class="indent">
				<span class="outdent">[wpmtst-text </span>
						<br>field="{ <?php _e( 'your custom text field', 'strong-testimonials' ); ?> }" 
						<br>class="{ <?php _e( 'your CSS class', 'strong-testimonials' ); ?> }"]
			</p>
			<p class="indent">
				<span class="outdent">[wpmtst-link </span>
						<br>url="{ <?php _e( 'your custom URL field', 'strong-testimonials' ); ?> }" 
						<br>text="{ <?php _e( 'your custom text field', 'strong-testimonials' ); ?> }" 
						<br>target="_blank" <br>class="{ <?php _e( 'your CSS class', 'strong-testimonials' ); ?> }"]
			</p>
		</div>
		
		<p><textarea id="client-section" class="widefat code" name="wpmtst_options[client_section]" rows="3"><?php echo $options['client_section']; ?></textarea></p>
		
		<p><input type="button" class="button" id="restore-default-template" value="<?php _e( 'Restore Default Template', 'strong-testimonials' ); ?>" /></p>
	</td>
	</tr>
	
	</table>
	<?php
}

function wpmtst_cycle_section() {
	$cycle = get_option( 'wpmtst_cycle' );
	
	$cycle_options = array(
			'effects' => array(
					'fade'       => 'Fade',
					// 'scrollHorz' => 'Scroll horizontally',
					// 'none'       => 'None',
			)
	);
	
	// @TODO: de-duplicate (in widget too)
	$order_list = array(
			'rand'   => __( 'Random', 'strong-testimonials' ),
			'recent' => __( 'Newest first', 'strong-testimonials' ),
			'oldest' => __( 'Oldest first', 'strong-testimonials' ),
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
	
	include( WPMTST_INC . 'form-cycle-settings.php' );
}

/*
 * Shortcodes page
 */
function wpmtst_settings_shortcodes() {
	$links = array(
			'categories'   => admin_url( 'edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial' ),
			'testimonials' => admin_url( 'edit.php?post_type=wpm-testimonial' ),
	);
	?>
	<div class="wrap wpmtst">

		<h2><?php _e( 'Shortcodes', 'strong-testimonials' ); ?></h2>

		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h3><?php _e( 'All Testimonials', 'strong-testimonials' ); ?></h3></th>
			</tr>
			<tr>
				<td><?php _e( 'Show all from all categories.', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-all]</td>
			</tr>
			<tr>
				<td><?php printf( __( 'Show all from a specific <a href="%s">category</a>.', 'strong-testimonials' ), $links['categories'] ); ?></a></td>
				<td>[wpmtst-all category="xx"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h3><?php _e( 'Testimonials Cycle', 'strong-testimonials' ); ?></h3></th>
			</tr>
			<tr>
				<td><?php printf( __( 'Cycle through testimonials. <a href="%s">configure</a>', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=settings&tab=cycle' ) ); ?></td>
				<td>[wpmtst-cycle]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h3><?php _e( 'Random Testimonial', 'strong-testimonials' ); ?></h3></th>
			</tr>
			<tr>
				<td><?php _e( 'Show a single random testimonial.', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-random]</td>
			</tr>
			<tr>
				<td><?php _e( 'Show a certain number of testimonials.', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-random limit="x"]</td>
			</tr>
			<tr>
				<td><?php printf( __( 'Show a single random testimonial from a specific <a href="%s">category</a>.', 'strong-testimonials' ), $links['categories'] ); ?></td>
				<td>[wpmtst-random category="xx"]</td>
			</tr>
			<tr>
				<td><?php printf( __( 'Show a certain number from a specific <a href="%s">category</a>.', 'strong-testimonials' ), $links['categories'] ); ?></td>
				<td>[wpmtst-random category="xx" limit="x"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h3><?php _e( 'Single Testimonial', 'strong-testimonials' ); ?></h3></th>
			</tr>
			<tr>
				<td><?php printf( __( 'Show one specific <a href="%s">testimonial</a>.', 'strong-testimonials' ), $links['testimonials'] ); ?></td>
				<td>[wpmtst-single id="xx"]</td>
			</tr>
		</table>

		<table class="shortcode-table">
			<tr>
				<th colspan="2"><h3><?php _e( 'Testimonial Submission Form', 'strong-testimonials' ); ?></h3></th>
			</tr>
			<tr>
				<td><?php _e( 'Show a form for visitors to submit testimonials.', 'strong-testimonials' );?><br><?php _e( 'New testimonials are in "Pending" status until published by an administrator.', 'strong-testimonials' ); ?></td>
				<td>[wpmtst-form]</td>
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
