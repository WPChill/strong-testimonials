<?php
/**
 * Class Strong_Testimonials_Admin_Scripts
 */
class Strong_Testimonials_Admin_Scripts {

	/**
	 * Strong_Testimonials_Admin_Scripts constructor.
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
		add_action( 'admin_init', array( __CLASS__, 'admin_register' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_dequeue_scripts' ), 500 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );

		add_action( 'admin_print_styles-wpm-testimonial_page_testimonial-views', array( __CLASS__, 'admin_views' ) );
		add_action( 'admin_print_styles-wpm-testimonial_page_testimonial-fields', array( __CLASS__, 'admin_fields' ) );
		add_action( 'admin_print_styles-wpm-testimonial_page_testimonial-settings', array( __CLASS__, 'admin_settings' ) );
		add_action( 'admin_print_styles-wpm-testimonial_page_about-strong-testimonials', array( __CLASS__, 'admin_about' ) );
		add_action( 'admin_print_styles-wpm-testimonial_page_testimonial-shortcodes', array( __CLASS__, 'admin_shortcodes' ) );
		add_action( 'admin_print_styles-wpm-testimonial_page_strong-testimonials-logs', array( __CLASS__, 'admin_logs_view' ) );

		add_action( 'load-edit.php', array( __CLASS__, 'admin_load_edit' ) );
		add_action( 'load-post.php', array( __CLASS__, 'admin_load_post' ) );
		add_action( 'load-post-new.php', array( __CLASS__, 'admin_load_post' ) );
		add_action( 'load-edit-tags.php', array( __CLASS__, 'admin_load_edit_tags' ) );
	}

	/**
	 * Register admin scripts.
	 */
	public static function admin_register() {

		$plugin_version = get_option( 'wpmtst_plugin_version' );

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'wpmtst-help',
			WPMTST_ADMIN_URL . 'js/help.js',
			array( 'jquery' ),
			$plugin_version,
			true );


		wp_register_script(
			'wpmtst-admin-script',
			WPMTST_ASSETS_JS . 'admin-js.js',
			array( 'jquery', 'underscore' ),
			$plugin_version,
			true );

		wp_register_style(
			'wpmtst-admin-global-style',
			WPMTST_ASSETS_CSS . 'admin-global.css',
			array(),
			$plugin_version );
                                    
	 	wp_register_style(
			'wpmtst-admin-style',
			WPMTST_ASSETS_CSS . 'admin.css',
			array( ),
			$plugin_version );

		wp_register_style( 'wpmtst-post-editor',
			WPMTST_ADMIN_URL . 'css/post-editor.css',
			array( ),
			$plugin_version );

		wp_register_style( 'wpmtst-lite-vs-pro',
			WPMTST_ADMIN_URL . 'css/lite-vs-pro.css',
			array( ),
			$plugin_version );

		wp_register_style( 'wpmtst-admin-upsells-style',
			WPMTST_ASSETS_CSS . 'admin-upsells.css',
			array( ),
			$plugin_version );

		wp_register_script( 'wpmtst-custom-spinner',
				WPMTST_ADMIN_URL . 'js/custom-spinner.js',
				array( 'jquery' ),
				$plugin_version,
				true );

		wp_register_script( 'autosize',
			WPMTST_ADMIN_URL . 'js/lib/autosize/autosize.min.js',
			array(),
			'4.0.1',
			true );

		/**
		 * Form tab
		 */
		wp_register_style( 'wpmtst-admin-form-style',
		                   WPMTST_ADMIN_URL . 'css/admin-form.css',
		                   array( 'wpmtst-admin-style' ),
		                   $plugin_version );

		wp_register_script( 'wpmtst-admin-form-script',
		                    WPMTST_ADMIN_URL . 'js/admin-form.js',
		                    array( 'wpmtst-admin-script', 'autosize' ),
		                    $plugin_version,
		                    true );

		/**
		 * Compatibility tab
		 */
		wp_register_style( 'wpmtst-admin-compat-style',
				WPMTST_ADMIN_URL . 'css/admin-compat.css',
				array(),
				$plugin_version );

		wp_register_script( 'wpmtst-admin-compat-script',
				WPMTST_ADMIN_URL . 'js/admin-compat.js',
				array( 'jquery', 'wpmtst-custom-spinner', 'wpmtst-help' ),
				$plugin_version,
				true );

		/**
		 * Fields
		 */
		wp_register_style( 'wpmtst-admin-fields-style',
			WPMTST_ADMIN_URL . 'css/fields.css',
			array(),
			$plugin_version );

		wp_register_style( 'wpmtst-admin-form-preview',
			WPMTST_ADMIN_URL . 'css/form-preview.css',
			array(),
			$plugin_version );

		wp_register_script( 'wpmtst-admin-fields-script',
			WPMTST_ADMIN_URL . 'js/admin-fields.js',
			array( 'jquery', 'jquery-ui-sortable', 'wpmtst-help' ),
			$plugin_version,
			true );

		$params = array(
			'ajax_nonce' => wp_create_nonce( 'wpmtst-admin' ),
			'newField'   => esc_html_x( 'New Field', 'Field editor: The default label for new fields', 'strong-testimonials' ),
			'inUse'      => esc_html_x( '(in use)', 'Fields editor: To indicate when a field type can only be used once.', 'strong-testimonials' ),
			'noneFound'  => esc_html_x( '(none found)', 'Fields editor: To indicate when no categories have been found.', 'strong-testimonials' ),
		);
		wp_localize_script( 'wpmtst-admin-fields-script', 'wpmtstAdmin', $params );

		/**
		 * Ratings
		 */
		wp_register_style( 'wpmtst-rating-display',
			WPMTST_PUBLIC_URL . 'css/rating-display.css',
			array( ),
			$plugin_version );

		wp_register_style( 'wpmtst-rating-form',
			WPMTST_PUBLIC_URL . 'css/rating-form.css',
			array( ),
			$plugin_version );

		wp_register_script( 'wpmtst-rating-script',
			WPMTST_ADMIN_URL . 'js/rating-edit.js',
			array( 'jquery' ),
			$plugin_version,
			true );

		/**
		 * Views
		 */
		wp_register_style( 'wpmtst-admin-views-style',
			WPMTST_ADMIN_URL . 'css/views.css',
			array(),
			$plugin_version );

		wp_register_script( 'wpmtst-admin-views-script',
			WPMTST_ADMIN_URL . 'js/views.js',
			array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-masonry', 'wpmtst-help' ),
			$plugin_version,
			true );

		/**
		 * Category filter in View editor.
		 *
		 * JavaScript adapted under GPL-2.0+ license
		 * from Post Category Filter plugin by Javier Villanueva (http://www.jahvi.com)
		 *
		 * @since 2.2.0
		 */
		wp_register_script( 'wpmtst-view-category-filter-script',
			WPMTST_ADMIN_URL . 'js/view-category-filter.js',
			array( 'jquery' ),
			$plugin_version,
			true );

		wp_register_style( 'wpmtst-about-style',
			WPMTST_ADMIN_URL . 'css/about.css',
			array(),
			$plugin_version );

		/**
		 * Add-on licenses
		 *
		 * @since 2.18
		 */
		wp_register_script( 'wpmtst-addons-script',
			WPMTST_ADMIN_URL . 'js/addon-licenses.js',
			array( 'jquery' ),
			$plugin_version,
			true );

		$params = array(
			'ajax_nonce'     => wp_create_nonce( 'wpmtst-admin' ),
			'requiredField'  => esc_html__( 'This field is required.', 'strong-testimonials' ),
			'errorMessage'   => esc_html__( 'An error occurred, please try again.', 'strong-testimonials' ),
			'restoreDefault' => esc_html__( 'Restore the default settings?', 'strong-testimonials' ),
		);
		wp_localize_script( 'wpmtst-addons-script', 'strongAddonAdmin', $params );

		/**
		 * Are You Sure? for dirty forms
		 *
		 * @since 2.18
		 */
		wp_register_script( 'wpmtst-ays-script',
			WPMTST_ADMIN_URL . "js/lib/are-you-sure/jquery.are-you-sure{$min}.js",
			array( 'jquery' ),
			$plugin_version,
			true );

		wp_register_style( 'wpmtst-logs-style',
			WPMTST_ADMIN_URL . 'css/logs.css',
			array(),
			$plugin_version );
	}

	/**
	 * Enqueue global admin scripts.
	 */
	public static function admin_enqueue_scripts() {
                $screen = get_current_screen();
		$plugin_version = get_option( 'wpmtst_plugin_version' );

		wp_enqueue_style( 'wpmtst-admin-global-style' );

		wp_enqueue_script( 'wpmtst-admin-global',
		                    WPMTST_ADMIN_URL . 'js/admin-global.js',
		                    array( 'jquery' ),
		                    $plugin_version,
		                    true );

		wp_localize_script(
			'wpmtst-admin-global',
			'wpmtst_admin',
			array(
				'nonce'              => wp_create_nonce( 'wpmtst-admin' ),
				'templateTagTitle'   => esc_html__( 'click to insert into message at caret', 'strong-testimonials' ),
				'something_wrong'    => esc_html__( 'Something went wrong. Please try again.', 'strong-testimonials' ),
				'email_license_sent' => esc_html__( 'An email containing the license was sent.', 'strong-testimonials' ),
				'activate'           => esc_html__( 'Activate', 'strong-testimonials' ),
				'deactivate'         => esc_html__( 'Deactivate', 'strong-testimonials' ),
				'enter_email'        => esc_html__( 'Please enter your email address.', 'strong-testimonials' ),
				'activating'         => esc_html__( 'Activating...', 'strong-testimonials' ),
				'deactivating'       => esc_html__( 'Deactivating...', 'strong-testimonials' ),
				'retrieving_data'    => esc_html__( 'Retrieving data...', 'strong-testimonials' ),
				'enter_license'      => esc_html__( 'Please enter your license key.', 'strong-testimonials' ),
			)
		);

	}

	/**
	 * Enqueue specific styles and scripts.
	 *
	 * Using separate hooks for readability, troubleshooting, and future refactoring. Focus on _where_.
	 *
	 * @since 2.12.0
	 */

	/**
	 * Views
	 */
	public static function admin_views() {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );

		wp_add_inline_script(
			'wpmtst-admin-script',
			'const wpmtst_admin_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-script-nonce' ) . '";',
			'before'
		);

		wp_enqueue_style( 'wpmtst-admin-views-style' );
		wp_enqueue_script( 'wpmtst-admin-views-script' );
		wp_enqueue_script( 'wpmtst-view-category-filter-script' );

		wp_enqueue_style( 'wp-color-picker' );
		// Add nonce to the script.
		wp_add_inline_script(
			'wpmtst-admin-views-script',
			'const wpmtst_admin_views_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-views-script-nonce' ) . '";',
			'before'
		);
	}

	/**
	 * Fields
	 */
	public static function admin_fields() {
        $tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';
        wp_enqueue_style( 'wpmtst-admin-style' );
        wp_enqueue_script( 'wpmtst-admin-script' );
        
		wp_add_inline_script(
			'wpmtst-admin-script',
			'const wpmtst_admin_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-script-nonce' ) . '";',
			'before'
		);

        switch ( $tab ) {
			case 'form':
				wp_enqueue_style( 'wpmtst-admin-form-style' );
				wp_enqueue_script( 'wpmtst-admin-form-script' );
				wp_add_inline_script(
					'wpmtst-admin-form-script',
					'const wpmtst_admin_form_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-form-script-nonce' ) . '";',
					'before'
				);
				break;
			case 'fields':
                wp_enqueue_style( 'wpmtst-admin-fields-style' );
                wp_enqueue_script( 'wpmtst-admin-fields-script' );

                wp_enqueue_style( 'wpmtst-admin-form-preview' );
                wp_enqueue_style( 'wpmtst-rating-form' );
				break;
            default:
                wp_enqueue_style( 'wpmtst-admin-fields-style' );
                wp_enqueue_script( 'wpmtst-admin-fields-script' );

                wp_enqueue_style( 'wpmtst-admin-form-preview' );
                wp_enqueue_style( 'wpmtst-rating-form' );
		}
	}

	/**
	 * Settings
	 */
	public static function admin_settings() {
		$tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';

		switch ( $tab ) {
			case 'compat':
				wp_enqueue_style( 'wpmtst-admin-compat-style' );
				wp_enqueue_script( 'wpmtst-admin-compat-script' );
				break;
			case 'licenses':
				wp_enqueue_style( 'wpmtst-admin-style' );
				wp_enqueue_script( 'wpmtst-addons-script' );
				break;
			default:
				wp_enqueue_style( 'wpmtst-admin-style' );
				wp_enqueue_script( 'wpmtst-admin-script' );
				wp_add_inline_script(
					'wpmtst-admin-script',
					'const wpmtst_admin_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-script-nonce' ) . '";',
					'before'
				);		
		}
	}

	/**
	 * About
	 */
	public static function admin_about() {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );
		
		wp_add_inline_script(
			'wpmtst-admin-script',
			'const wpmtst_admin_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-script-nonce' ) . '";',
			'before'
		);

		wp_enqueue_style( 'wpmtst-about-style' );
	}

	/**
	 * Shortcodes
	 *
	 * @since 2.31.0
	 */
	public static function admin_shortcodes() {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_style( 'wpmtst-rating-display' );
	}

	/**
	 * List table
	 */
	public static function admin_load_edit() {
		if ( wpmtst_is_testimonial_screen() ) {
			wp_enqueue_style( 'wpmtst-admin-style' );
			wp_enqueue_script( 'wpmtst-admin-script' );
			
			wp_add_inline_script(
				'wpmtst-admin-script',
				'const wpmtst_admin_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-script-nonce' ) . '";',
				'before'
			);

			wp_enqueue_style( 'wpmtst-rating-display' );
		}
	}

	/**
	 * Logs View
	 */
	public static function admin_logs_view(){
		wp_enqueue_style( 'wpmtst-logs-style' );
	}

	/**
	 * Categories
	 */
	public static function admin_load_edit_tags() {
		if ( wpmtst_is_testimonial_screen() ) {
			wp_enqueue_style( 'wpmtst-admin-style' );
		}
	}

	/**
	 * Edit post
	 */
	public static function admin_load_post() {
		if ( wpmtst_is_testimonial_screen() ) {
			wp_enqueue_style( 'wpmtst-admin-style' );
			wp_enqueue_style( 'wpmtst-post-editor' );
			wp_enqueue_script( 'wpmtst-admin-script' );

			wp_add_inline_script(
				'wpmtst-admin-script',
				'const wpmtst_admin_script_nonce = "' . wp_create_nonce( 'wpmtst-admin-script-nonce' ) . '";',
				'before'
			);
	
			wp_enqueue_style( 'wpmtst-rating-display' );
			wp_enqueue_style( 'wpmtst-rating-form' );
			wp_enqueue_script( 'wpmtst-rating-script' );
		}
	}

	/**
	 * Known theme and plugin conflicts.
	 *
	 * @param $hook
	 */
	public static function admin_dequeue_scripts( $hook ) {
		if ( wp_style_is( 'CPTStyleSheets' ) ) {
			wp_dequeue_style( 'CPTStyleSheets' );
		}

		$hooks_to_script = array(
			'wpm-testimonial_page_testimonial-views',
			'wpm-testimonial_page_testimonial-fields',
			'wpm-testimonial_page_testimonial-settings',
			'wpm-testimonial_page_about-strong-testimonials',
		);

		if ( wpmtst_is_testimonial_screen() ) {
			$hooks_to_script = array_merge( $hooks_to_script, array( 'edit.php' ) );
		}

		/**
		 * Block RT Themes and their overzealous JavaScript on our admin pages.
		 * @since 2.2.12.1
		 */
		if ( in_array( $hook, $hooks_to_script ) ) {
			if ( class_exists( 'RTThemeAdmin' ) && wp_script_is( 'admin-scripts' ) ) {
				wp_dequeue_script( 'admin-scripts' );
			}
		}
	}

}

Strong_Testimonials_Admin_Scripts::init();
