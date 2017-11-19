<?php
/**
 * Strong Testimonials admin functions.
 *
 * 1. Check for required WordPress version.
 * 2. Check for plugin update.
 * 3. Initialize.
 */

/**
 * Check for required WordPress version.
 */
function wpmtst_version_check() {
	global $wp_version;
	$require_wp_version = "3.7";

	if ( version_compare( $wp_version, $require_wp_version ) == -1 ) {
		deactivate_plugins( WPMTST_PLUGIN );
		/* translators: %s is the name of the plugin. */
		$message = '<h2>' . sprintf( _x( 'Unable to load %s', 'installation', 'strong-testimonials' ), 'Strong Testimonials' ) . '</h2>';
		/* translators: %s is a WordPress version number. */
		$message .= '<p>' . sprintf( _x( 'This plugin requires <strong>WordPress %s</strong> or higher so it has been deactivated.', 'installation', 'strong-testimonials' ), $require_wp_version ) . '</p>';
		$message .= '<p>' . _x( 'Please upgrade WordPress and try again.', 'installation', 'strong-testimonials' ) . '</p>';
		$message .= '<p>' . sprintf( _x( 'Back to the WordPress <a href="%s">Plugins page</a>', 'installation', 'strong-testimonials' ), get_admin_url( null, 'plugins.php' ) ) . '</p>';
		wp_die( $message );
	}
}

add_action( 'admin_init', 'wpmtst_version_check', 1 );

/**
 * Check for plugin update.
 *
 * @since 2.28.4 Before other admin_init actions.
 */
function wpmtst_update_check() {
	$updater = new Strong_Testimonials_Updater();
	$updater->update();
}

add_action( 'admin_init', 'wpmtst_update_check' );

/**
 * Initialize.
 */
function wpmtst_admin_init() {

    // Remove ad banner from Captcha plugin
	remove_action( 'admin_notices', 'cptch_plugin_banner' );

	// Redirect to About page for new installs only
	wpmtst_activation_redirect();

	/**
     * Custom action hooks
     *
     * @since 1.18.4
     */
    if ( isset( $_REQUEST['action'] ) && '' != $_REQUEST['action'] ) {
        do_action( 'wpmtst_' . $_REQUEST['action'] );
    }

}

add_action( 'admin_init', 'wpmtst_admin_init', 20 );

/**
 * Redirect to About page.
 */
function wpmtst_activation_redirect() {
	if ( get_option( 'wpmtst_do_activation_redirect', false ) ) {
		delete_option( 'wpmtst_do_activation_redirect' );
		wp_redirect( admin_url( 'edit.php?post_type=wpm-testimonial&page=about-strong-testimonials' ) );
		exit;
	}
}

/**
 * Are we on a testimonial admin screen?
 *
 * Used by add-ons too!
 *
 * @return bool
 */
function wpmtst_is_testimonial_screen() {
	$screen = get_current_screen();
	return ( $screen && 'wpm-testimonial' == $screen->post_type );
}

/**
 * Add pending numbers to post types on admin menu.
 * Thanks http://wordpress.stackexchange.com/a/105470/32076
 *
 * @param $menu
 *
 * @return mixed
 */
function wpmtst_pending_indicator( $menu ) {
	if ( ! current_user_can( 'edit_posts' ) )
		return $menu;

	$options = get_option( 'wpmtst_options' );
	if ( ! isset( $options['pending_indicator'] ) || ! $options['pending_indicator'] )
		return $menu;

	$types  = array( 'wpm-testimonial' );
	$status = 'pending';
	foreach ( $types as $type ) {
		$num_posts     = wp_count_posts( $type, 'readable' );
		$pending_count = 0;
		if ( ! empty( $num_posts->$status ) )
			$pending_count = $num_posts->$status;

		if ( $type == 'post' )
			$menu_str = 'edit.php';
		else
			$menu_str = 'edit.php?post_type=' . $type;

		foreach ( $menu as $menu_key => $menu_data ) {
			if ( $menu_str != $menu_data[2] )
				continue;
			$menu[ $menu_key ][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n( $pending_count ) . '</span></span>';
		}
	}

	return $menu;
}
add_filter( 'add_menu_classes', 'wpmtst_pending_indicator' );

/**
 * The [restore default] icon.
 *
 * @param $for
 *
 * @since 2.18.0
 */
function wpmtst_restore_default_icon( $for ) {
	if ( !$for ) return;
	?>
	<input type="button" class="button secondary restore-default"
		   title="<?php _e( 'restore default', 'strong-testimonials' ); ?>"
		   value="&#xf171"
		   data-for="<?php echo $for; ?>"/>
	<?php
}

/**
 * Add plugin links.
 *
 * @param        $plugin_meta
 * @param        $plugin_file
 * @param array  $plugin_data
 * @param string $status
 *
 * @return array
 */
function wpmtst_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data = array(), $status = '' ) {

    if ( $plugin_file == WPMTST_PLUGIN ) {

		$plugin_meta[] = sprintf(
		    '<a href="%s" target="_blank" title="%s" style="color: #8224e3; font-weight: 600;">%s</a>',
			'https://support.strongplugins.com/',
            __( 'For direct support requests and documentation', 'strong-testimonials' ),
            __( 'Support', 'strong-testimonials' ) );

		$plugin_meta[] = sprintf(
            '<a href="%s" target="_blank" title="%s" style="color: #8224e3; font-weight: 600;">%s</a>',
			'https://strongplugins.com/',
            __( 'Get more features with premium add-ons', 'strong-testimonials' ),
            __( 'Add-ons', 'strong-testimonials' ) );

	}

	return $plugin_meta;
}
add_filter( 'plugin_row_meta', 'wpmtst_plugin_row_meta' , 10, 4 );
