<?php
/**
 * Class Strong_Testimonials - Main plugin class
 *
 * @since 3.1.16
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Strong_Testimonials' ) ) :

	/**
	 * Main plugin class.
	 *
	 * @property  Strong_Testimonials_View_Shortcode shortcode
	 * @property  Strong_Testimonials_Render render
	 * @property  Strong_Mail mail
	 * @property  Strong_Templates templates
	 * @property  Strong_Testimonials_Form form
	 * @since 1.15.0
	 */
	final class Strong_Testimonials {

		private static $instance;

		private $db_version = '1.0';

		public $plugin_data;

		/**
		 * @var Strong_Testimonials_View_Shortcode
		 */
		public $shortcode;

		/**
		 * @var Strong_Testimonials_Render
		 */
		public $render;

		/**
		 * @var Strong_Mail
		 */
		public $mail;

		/**
		 * @var Strong_Templates
		 */
		public $templates;

		/**
		 * @var Strong_Testimonials_Form
		 */
		public $form;

		/**
		 * A singleton instance.
		 *
		 * Used for preprocessing shortcodes and widgets to properly enqueue styles and scripts
		 * (1) to improve overall plugin flexibility,
		 * (2) to improve compatibility with page builder plugins, and
		 * (3) to maintain conditional loading best practices.
		 *
		 * Also used to store testimonial form data during Post-Redirect-Get.
		 *
		 * @return Strong_Testimonials  Strong_Testimonials object
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Strong_Testimonials ) ) {
				self::$instance = new Strong_Testimonials();
				self::$instance->setup_constants();
				self::$instance->includes();

				add_action( 'init', array( self::$instance, 'init' ) );

				self::$instance->add_actions();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @return void
		 * @since 1.21.0
		 * @access protected
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'strong-testimonials' ), '1.21' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @return void
		 * @since 1.21.0
		 * @access protected
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'strong-testimonials' ), '1.21' );
		}

		/**
		 * Plugin activation
		 */
		public static function plugin_activation( $network_wide = false ) {

			$first_install = ! get_option( 'wpmtst_db_version' ) ? true : false;

			// check if
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			// check if it's multisite.
			if ( is_multisite() && true === $network_wide ) {

				// get websites
				$sites = get_sites();

				// loop
				if ( count( $sites ) > 0 ) {
					foreach ( $sites as $site ) {

						// switch to blog
						switch_to_blog( $site->blog_id );

						// run installer on blog
						wpmtst_update_tables();

						// restore current blog
						restore_current_blog();
					}
				}
			} else {
				// no multisite so do normal install
				wpmtst_update_tables();
			}

			wpmtst_register_cpt();
			flush_rewrite_rules();

			do_action( 'wpmtst_after_update_setup', $first_install );
		}


		/**
		 * Run installer for new blogs on multisite when plugin is network activated
		 *
		 * @param array $site Blog - WP_Site object.
		 *
		 * @since 3.1.8
		 */
		public static function mu_new_blog( $site ) {

			// check if plugin is network activated.
			if ( is_plugin_active_for_network( 'strong-testimonials/strong-testimonials.php' ) ) {

				// switch to new blog
				switch_to_blog( $site->blog_id );

				// run installer on blog
				wpmtst_update_tables();

				// restore current blog
				restore_current_blog();
			}
		}

		/**
		 * Plugin deactivation
		 */
		public static function plugin_deactivation() {
			flush_rewrite_rules();
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @return void
		 */
		private function setup_constants() {
			defined( 'WPMTST_DIR' ) || define( 'WPMTST_DIR', plugin_dir_path( __FILE__ ) );
			defined( 'WPMTST_URL' ) || define( 'WPMTST_URL', plugin_dir_url( __FILE__ ) );

			defined( 'WPMTST_INC' ) || define( 'WPMTST_INC', WPMTST_DIR . 'includes/' );

			defined( 'WPMTST_ADMIN' ) || define( 'WPMTST_ADMIN', WPMTST_DIR . 'admin/' );
			defined( 'WPMTST_ADMIN_URL' ) || define( 'WPMTST_ADMIN_URL', WPMTST_URL . 'admin/' );

			defined( 'WPMTST_PUBLIC' ) || define( 'WPMTST_PUBLIC', WPMTST_DIR . 'assets/public/' );
			defined( 'WPMTST_PUBLIC_URL' ) || define( 'WPMTST_PUBLIC_URL', WPMTST_URL . 'assets/public/' );

			defined( 'WPMTST_DEF_TPL' ) || define( 'WPMTST_DEF_TPL', WPMTST_DIR . 'templates/default/' );
			defined( 'WPMTST_DEF_TPL_URI' ) || define( 'WPMTST_DEF_TPL_URI', WPMTST_URL . 'templates/default/' );

			defined( 'WPMTST_TPL' ) || define( 'WPMTST_TPL', WPMTST_DIR . 'templates' );
			defined( 'WPMTST_TPL_URI' ) || define( 'WPMTST_TPL_URI', WPMTST_URL . 'templates' );

			defined( 'WPMTST_ASSETS_CSS' ) || define( 'WPMTST_ASSETS_CSS', WPMTST_URL . 'assets/css/' );
			defined( 'WPMTST_ASSETS_JS' ) || define( 'WPMTST_ASSETS_JS', WPMTST_URL . 'assets/dist/' );
			defined( 'WPMTST_ASSETS_SRC' ) || define( 'WPMTST_ASSETS_SRC', WPMTST_URL . 'assets/src/' );
			defined( 'WPMTST_ASSETS_IMG' ) || define( 'WPMTST_ASSETS_IMG', WPMTST_URL . 'assets/img/' );
		}

		/**
		 * Instantiate our classes.
		 */
		public function init() {
			$this->shortcode = new Strong_Testimonials_View_Shortcode();
			$this->render    = new Strong_Testimonials_Render();
			$this->mail      = new Strong_Mail();
			$this->templates = new Strong_Templates();
			$this->form      = new Strong_Testimonials_Form();

			new Strong_Testimonials_Count_Shortcode();
			new Strong_Testimonials_Average_Shortcode();
			new Strong_Testimonials_Privacy();
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 * @since 1.21.0
		 */
		private function includes() {
			require_once WPMTST_INC . 'logs/class-strong-testimonials-logger.php';
			require_once WPMTST_INC . 'class-strong-log.php';

			require_once WPMTST_INC . 'class-strong-testimonials-privacy.php';

			require_once WPMTST_INC . 'class-strong-testimonials-view-shortcode.php';
			require_once WPMTST_INC . 'class-strong-gutemberg.php';
			require_once WPMTST_INC . 'elementor/class-strong-testimonials-elementor-check.php';
			require_once WPMTST_INC . 'strong-testimonials-beaver-block/class-strong-testimonials-beaver.php';
			require_once WPMTST_INC . 'class-strong-testimonials-count-shortcode.php';
			require_once WPMTST_INC . 'class-strong-testimonials-average-shortcode.php';
			require_once WPMTST_INC . 'class-strong-testimonials-render.php';
			require_once WPMTST_INC . 'class-strong-view.php';
			require_once WPMTST_INC . 'class-strong-view-display.php';
			require_once WPMTST_INC . 'class-strong-view-slideshow.php';
			require_once WPMTST_INC . 'class-strong-view-form.php';

			require_once WPMTST_INC . 'class-strong-testimonials-templates.php';
			require_once WPMTST_INC . 'class-strong-mail.php';
			require_once WPMTST_INC . 'class-strong-testimonials-form.php';
			require_once WPMTST_INC . 'class-walker-strong-category-checklist-front.php';

			require_once WPMTST_INC . 'deprecated.php';
			require_once WPMTST_INC . 'filters.php';
			require_once WPMTST_INC . 'functions.php';
			require_once WPMTST_INC . 'functions-activation.php';
			require_once WPMTST_INC . 'functions-content.php';
			require_once WPMTST_INC . 'functions-rating.php';
			require_once WPMTST_INC . 'functions-image.php';
			require_once WPMTST_INC . 'functions-template.php';
			require_once WPMTST_INC . 'functions-template-form.php';
			require_once WPMTST_INC . 'functions-views.php';
			require_once WPMTST_INC . 'post-types.php';
			require_once WPMTST_INC . 'retro.php';
			require_once WPMTST_INC . 'scripts.php';
			require_once WPMTST_INC . 'class-strong-testimonials-view-widget.php';

			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
				require_once WPMTST_ADMIN . 'menu/class-strong-testimonials-menu.php';
				require_once WPMTST_ADMIN . 'menu/class-strong-testimonials-menu-fields.php';
				require_once WPMTST_ADMIN . 'menu/class-strong-testimonials-menu-settings.php';
				require_once WPMTST_ADMIN . 'menu/class-strong-testimonials-menu-views.php';
				require_once WPMTST_ADMIN . 'menu/class-strong-testimonials-menu-shortcodes.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-page-shortcodes.php';

				require_once WPMTST_ADMIN . 'settings/class-strong-testimonials-settings.php';
				require_once WPMTST_ADMIN . 'settings/class-strong-testimonials-settings-general.php';
				require_once WPMTST_ADMIN . 'settings/class-strong-testimonials-settings-form.php';
				require_once WPMTST_ADMIN . 'settings/class-strong-testimonials-settings-compat.php';
				require_once WPMTST_ADMIN . 'settings/class-strong-testimonials-advanced-settings.php';
				require_once WPMTST_ADMIN . 'settings/class-strong-testimonials-forms.php';

				require_once WPMTST_ADMIN . 'class-strong-testimonials-addons.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-defaults.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-list-table.php';
				require_once WPMTST_ADMIN . 'class-strong-views-list-table.php';
				require_once WPMTST_ADMIN . 'class-walker-strong-category-checklist.php';
				require_once WPMTST_ADMIN . 'class-walker-strong-form-category-checklist.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-help.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-admin-scripts.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-admin-list.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-admin-category-list.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-post-editor.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-exporter.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-wpchill-upsells.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-upsell.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-updater.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-review.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-helper.php';
				require_once WPMTST_ADMIN . 'class-strong-testimonials-lite-vs-pro-page.php';

				require_once WPMTST_ADMIN . 'admin.php';
				require_once WPMTST_ADMIN . 'admin-notices.php';
				require_once WPMTST_ADMIN . 'compat.php';
				require_once WPMTST_ADMIN . 'custom-fields.php';
				require_once WPMTST_ADMIN . 'custom-fields-ajax.php';
				require_once WPMTST_ADMIN . 'form-preview.php';
				require_once WPMTST_ADMIN . 'views.php';
				require_once WPMTST_ADMIN . 'views-ajax.php';
				require_once WPMTST_ADMIN . 'view-list-order.php';
				require_once WPMTST_ADMIN . 'views-validate.php';

				require_once WPMTST_INC . 'class-strong-testimonials-order.php';

				// Uninstall form
				require_once WPMTST_ADMIN . 'uninstall/class-strong-testimonials-uninstall.php';

				// WPMTST Challenge Modal
				//require_once WPMTST_ADMIN . 'challenge/class-wpmtst-challenge-modal.php';

				// Admin Helpers
				require_once WPMTST_ADMIN . 'class-wpmtst-admin-helpers.php';

				// WPMTST Onboarding
				require_once WPMTST_ADMIN . 'class-wpmtst-onboarding.php';

				// WPMTST Debuging
				require_once WPMTST_ADMIN . 'class-strong-testimonials-debug.php';
			}
		}

		/**
		 * Text domain
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'strong-testimonials', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Action and filters.
		 */
		private function add_actions() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'plugins_loaded', array( $this, 'remove_license_tab' ) );

			/**
			 * Plugin setup.
			 */
			add_action( 'init', array( $this, 'l10n_check' ) );
			//add_action( 'init', array( $this, 'reorder_check' ) );

			/**
			 * Theme support for thumbnails.
			 */
			add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );

			/**
			 * Add image size for widget.
			 */
			add_action( 'after_setup_theme', array( $this, 'add_image_size' ) );

			add_filter( 'views_edit-wpm-testimonial', array( $this, 'add_onboarding_view' ), 10, 1 );

			if ( is_admin() ) {
				// Check if we need to add lite vs pro page
				$license = get_option( 'strong_testimonials_license_key' );
				$status  = get_option( 'strong_testimonials_license_status', false );
				if ( '' === $license || false === $status || ! isset( $status->license ) || 'valid' !== $status->license ) {
					if ( class_exists( 'Strong_Testimonials_Lite_Vs_PRO_Page' ) ) {
						new Strong_Testimonials_Lite_Vs_PRO_Page();
					}
				}
			}
		}

		/**
		 * Remove license tab added by premium extensions.
		 */
		public function remove_license_tab() {
			if ( class_exists( 'Strong_Testimonials_Settings_License' ) ) {
				remove_action(
					'wpmtst_settings_tabs',
					array( 'Strong_Testimonials_Settings_License', 'register_tab' ),
					90
				);
			}
		}

		/**
		 * Add theme support for this custom post type only.
		 *
		 * @since 1.4.0
		 * @since 1.19.1 Appends our testimonial post type to the existing array.
		 * @since 2.26.5 Simply using add_theme_support(). Let the chips fall where they may.
		 */
		public function add_theme_support() {
			/**
			 * This will fail if the theme uses add_theme_support incorrectly;
			 * e.g. add_theme_support( 'post-thumbnails', 'post' );
			 * which WordPress does not catch.
			 *
			 * The plugin attempted to handle this in versions 1.19.1 - 2.26.4
			 * but now it lets the condition occur so the underlying problem
			 * will surface and can be fixed.
			 */
			add_theme_support( 'post-thumbnails', array( 'wpm-testimonial' ) );
		}

		/**
		 * Add widget thumbnail size.
		 *
		 * @since 1.21.0
		 */
		public function add_image_size() {
			// name, width, height, crop = false
			add_image_size( 'widget-thumbnail', 75, 75, true );
		}

		/**
		 * Load specific files for translation plugins.
		 */
		public function l10n_check() {
			// WPML
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				require_once WPMTST_INC . 'l10n-wpml.php';
			}

			// Polylang
			if ( defined( 'POLYLANG_VERSION' ) ) {
				require_once WPMTST_INC . 'l10n-polylang.php';
			}

			// WP Globus
			if ( defined( 'WPGLOBUS_VERSION' ) ) {
				// Translate
				remove_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );
				add_filter( 'wpmtst_the_content', array( 'WPGlobus_Filters', 'filter__text' ), 0 );
				add_filter( 'wpmtst_get_the_excerpt', array( 'WPGlobus_Filters', 'filter__text' ), 0 );
			}
		}

		/**
		 * Load reorder class if enabled.
		 */
		// public function reorder_check() {
		// $options = get_option( 'wpmtst_options' );
		// if ( isset( $options['reorder'] ) && $options['reorder'] ) {
		// require_once WPMTST_INC . 'class-strong-testimonials-order.php';
		// }
		//  }

		/**
		 * Get att(s).
		 *
		 * @param null $keys
		 *
		 * @return array|bool
		 */
		public function atts( $keys = null ) {
			// return all
			if ( ! $keys ) {
				return $this->render->view_atts;
			}

			// return some
			if ( is_array( $keys ) ) {
				$found = array();
				foreach ( $keys as $key ) {
					if ( isset( $this->render->view_atts[ $key ] ) ) {
						$found[ $key ] = $this->render->view_atts[ $key ];
					}
				}

				return $found;
			}

			// return one
			if ( isset( $this->render->view_atts[ $keys ] ) ) {
				return $this->render->view_atts[ $keys ];
			}

			// return none
			return false;
		}

		/**
		 * Set atts.
		 *
		 * @param $atts
		 */
		public function set_atts( $atts ) {
			$this->render->set_atts( $atts );
		}

		/**
		 * Store current query.
		 *
		 * @param $query
		 */
		public function set_query( $query ) {
			$this->render->query = $query;
		}

		/**
		 * Return current query.
		 *
		 * @return mixed
		 */
		public function get_query() {
			return $this->render->query;
		}

		/**
		 * Get database tables version.
		 *
		 * @return string
		 */
		public function get_db_version() {
			return $this->db_version;
		}

		/**
		 * Set plugin data.
		 *
		 * @since 2.12.0
		 */
		public function set_plugin_data() {
			$this->plugin_data = array(
				'Version' => WPMTST_VERSION,
			);
		}

		/**
		 * Get plugin data.
		 *
		 * @return array
		 * @since 2.12.0
		 *
		 */
		public function get_plugin_data() {
			return $this->plugin_data;
		}

		/**
		 * Return plugin info.
		 *
		 * @return array
		 * @deprecated
		 *
		 */
		public function get_plugin_info() {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				if ( file_exists( ABSPATH . 'wp-admin/includes/admin.php' ) ) {
					require_once ABSPATH . 'wp-admin/includes/admin.php';
				}
			}

			return get_file_data(
				__FILE__,
				array(
					'name'    => 'Plugin Name',
					'version' => 'Version',
				)
			);
		}

		public function add_onboarding_view( $views ) {

			$query = new WP_Query(
				array(
					'post_type'   => 'wpm-testimonial',
					'post_status' => array(
						'publish',
						'future',
						'trash',
						'draft',
						'inherit',
						'pending',
					),
				)
			);

			$this->display_extension_tab();

			if ( ! $query->have_posts() ) {
				global $wp_list_table;
				$wp_list_table = new WPMTST_Onboarding();

				return array();
			}

			return $views;
		}

		public function display_extension_tab() {
			?>
			<h2 class="nav-tab-wrapper">
				<?php
				$tabs = array(
					'testimonials'    => array(
						'name'     => esc_html_x( 'Testimonials', 'post type general name', 'strong-testimonials' ),
						'url'      => admin_url( 'edit.php?post_type=wpm-testimonial' ),
						'priority' => '1',
					),
					'suggest_feature' => array(
						'name'     => esc_html__( 'Suggest a feature', 'strong-testimonials' ),
						'icon'     => 'dashicons-external',
						'url'      => 'https://docs.google.com/forms/d/e/1FAIpQLScch0AchtnzxJsSrjUcW9ypcr1fZ9r-vyk3emEp8Sv47brb2g/viewform',
						'target'   => '_blank',
						'priority' => '10',
					),
				);

				if ( current_user_can( 'install_plugins' ) ) {
					$tabs['extensions'] = array(
						'name'     => esc_html__( 'Extensions', 'strong-testimonials' ),
						'url'      => admin_url( 'edit.php?post_type=wpm-testimonial&page=strong-testimonials-addons' ),
						'priority' => '5',
					);
				}

				$tabs = apply_filters( 'wpmtst_add_edit_tabs', $tabs );

				uasort( $tabs, array( 'Strong_Testimonials_Helper', 'sort_data_by_priority' ) );

				WPMTST_Admin_Helpers::wpmtst_tab_navigation( $tabs, 'testimonials' );
				?>
			</h2>
			<br/>
			<?php
		}
	}

endif; // class_exists check.