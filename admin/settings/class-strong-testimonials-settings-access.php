<?php
/**
 * Class Strong_Testimonials_Settings_Access
 */
class Strong_Testimonials_Settings_Access {

	const TAB_NAME = 'access';

	const OPTION_NAME = 'wpmtst_access_options';

	const GROUP_NAME = 'wpmtst-access-group';

	/**
	 * Strong_Testimonials_Settings_Access constructor.
	 */
	public function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init() {
		self::add_actions();
	}

	/**
	 * Add actions and filters.
	 */
	public static function add_actions() {
	    add_action( 'wpmtst_register_settings', array( __CLASS__, 'register_settings' ) );
	    add_action( 'wpmtst_settings_tabs', array( __CLASS__, 'register_tab' ), 4, 2 );
	    add_filter( 'wpmtst_settings_callbacks', array( __CLASS__, 'register_settings_page' ) );
	}

	/**
	 * Register settings tab.
	 *
	 * @param $active_tab
	 * @param $url
	 */
	public static function register_tab( $active_tab, $url ) {
		printf( '<a href="%s" class="nav-tab %s">%s</a>',
			esc_url( add_query_arg( 'tab', self::TAB_NAME, $url ) ),
			esc_attr( $active_tab == self::TAB_NAME ? 'nav-tab-active' : '' ),
			_x( 'Access', 'adjective', 'strong-testimonials' )
		);
	}

	/**
	 * Register settings.
	 */
	public static function register_settings() {
		register_setting( self::GROUP_NAME, self::OPTION_NAME, array( __CLASS__, 'sanitize_options' ) );
	}

	/**
     * Register settings page.
     *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public static function register_settings_page( $pages ) {
	    $pages[ self::TAB_NAME ] = array( __CLASS__, 'settings_page' );
	    return $pages;
	}

	/**
	 * Print settings page.
	 */
	public static function settings_page() {
		settings_fields( self::GROUP_NAME );
		include( WPMTST_ADMIN . 'settings/partials/access.php' );
	}

	/**
	 * Sanitize settings.
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public static function sanitize_options( $input ) {
            global $wp_roles;
            foreach (array('approve_testimonials', 'manage_settings') as $access) {
                foreach ($wp_roles->roles as $key => $role) {
                    $name = $access . '_' . $key;
                    $input[$name] = wpmtst_sanitize_checkbox( $input, $name );
                    self::set_capability($access, $key, $input[$name]);
                }
            }
            return $input;
	}
        
        /**
	 * Set capabilities
         * 	 
	 * @param $access
         * @param $role
         * @param $capability
         * 
	 */
	public static function set_capability($access, $role, $capability) {
            $role = get_role( $role );
            switch ($access) {
                case 'approve_testimonials':
                    if ($capability) {
                        $role->add_cap( 'publish_testimonials' );
                        $role->add_cap( 'delete_testimonials' );
                        $role->add_cap( 'delete_others_testimonials' );
                        $role->add_cap( 'edit_testimonial' );
                        $role->add_cap( 'delete_testimonial' );
                    } else {
                        $role->remove_cap( 'publish_testimonials' );
                        $role->remove_cap( 'delete_testimonials' );
                        $role->remove_cap( 'delete_others_testimonials' );
                        $role->remove_cap( 'edit_testimonial' );
                        $role->remove_cap( 'delete_testimonial' );
                    }
                break;
                
                case 'manage_settings':
                    if ($capability) {
                        $role->add_cap( 'strong_testimonials_options' );
                    } else {
                        $role->remove_cap( 'strong_testimonials_options' );
                    }
                break;
            }
	}

}

Strong_Testimonials_Settings_Access::init();
