<?php
/**
 * Plugin Name: Strong Testimonials
 * Plugin URI: https://strongplugins.com/plugins/strong-testimonials/
 * Description: A full-featured plugin that works right out of the box for beginners and offers advanced features for pros.
 * Author: Chris Dillon
 * Version: 2.26.3
 * Author URI: https://strongplugins.com/
 * Text Domain: strong-testimonials
 * Domain Path: /languages
 * Requires: 3.6 or higher
 * License: GPLv3 or later
 *
 * Copyright 2014-2017 Chris Dillon chris@strongplugins.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_Testimonials' ) ) :

/**
 * Main plugin class.
 *
 * @property  Strong_Mail mail
 * @property  Strong_Templates templates
 * @property  Strong_Debug debug
 * @property  Strong_Testimonials_Form form
 * @since 1.15.0
 */
final class Strong_Testimonials {

	private static $instance;

	private $db_version = '1.0';

	public $plugin_data;

	public $styles = array();

	public $scripts = array();

	public $css = array();

	public $script_vars = array();

	public $shortcode2;

	public $shortcode2_lb;

	public $view_defaults = array();

	public $view_atts = array();

	public $query;

	public $form_values;

	public $form_errors;

	public $post_list = array();

	/**
	 * @var Strong_Mail
	 */
	public $mail;

	/**
	 * @var Strong_Templates
	 */
	public $templates;

	/**
	 * @var Strong_Debug
	 */
	public $debug;

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
	 * Heavily copied from Easy Digital Downloads by Pippin Williamson.
	 *
	 * @return Strong_Testimonials  Strong_Testimonials object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Strong_Testimonials ) ) {
			self::$instance = new Strong_Testimonials;
			self::$instance->setup_constants();
			self::$instance->includes();

			add_action( 'init', array( self::$instance, 'init' ) );

			self::$instance->add_actions();
			self::$instance->set_shortcodes();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.21.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'strong-testimonials' ), '1.21' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.21.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'strong-testimonials' ), '1.21' );
	}

	/**
	 * Plugin activation
	 */
	static function plugin_activation() {
		wpmtst_register_cpt();
		flush_rewrite_rules();
		wpmtst_update_tables();
	}

	/**
	 * Plugin deactivation
	 */
	static function plugin_deactivation() {
		flush_rewrite_rules();
	}

	/**
	 * Setup plugin constants
	 *
	 * @access private
	 * @return void
	 */
	private function setup_constants() {

		// plugin slug: `strong-testimonials` used by template search
		if ( ! defined( 'WPMTST_PLUGIN' ) )
			define( 'WPMTST_PLUGIN', plugin_basename( __FILE__ ) );

		if ( ! defined( 'WPMTST' ) )
			define( 'WPMTST', dirname( WPMTST_PLUGIN ) );

		if ( ! defined( 'WPMTST_DIR' ) )
			define( 'WPMTST_DIR', plugin_dir_path( __FILE__ ) );
		if ( ! defined( 'WPMTST_URL' ) )
			define( 'WPMTST_URL', plugin_dir_url( __FILE__ ) );

		if ( ! defined( 'WPMTST_INC' ) )
			define( 'WPMTST_INC', plugin_dir_path( __FILE__ ) . 'includes/' );

		if ( ! defined( 'WPMTST_ADMIN' ) )
			define( 'WPMTST_ADMIN', plugin_dir_path( __FILE__ ) . 'admin/' );
		if ( ! defined( 'WPMTST_ADMIN_URL' ) )
			define( 'WPMTST_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin/' );

		if ( ! defined( 'WPMTST_PUBLIC' ) )
			define( 'WPMTST_PUBLIC', plugin_dir_path( __FILE__ ) . 'public/' );
		if ( ! defined( 'WPMTST_PUBLIC_URL' ) )
			define( 'WPMTST_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public/' );

		if ( ! defined( 'WPMTST_DEF_TPL' ) )
			define( 'WPMTST_DEF_TPL', plugin_dir_path( __FILE__ ) . 'templates/default/' );
		if ( ! defined( 'WPMTST_DEF_TPL_URI' ) )
			define( 'WPMTST_DEF_TPL_URI', plugin_dir_url( __FILE__ ) . 'templates/default/' );

		if ( ! defined( 'WPMTST_TPL' ) )
			define( 'WPMTST_TPL', plugin_dir_path( __FILE__ ) . 'templates' );
		if ( ! defined( 'WPMTST_TPL_URI' ) )
			define( 'WPMTST_TPL_URI', plugin_dir_url( __FILE__ ) . 'templates' );

		// This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed.
		if ( ! defined( 'STRONGPLUGINS_STORE_URL' ) )
			define( 'STRONGPLUGINS_STORE_URL', 'https://strongplugins.com' );

	}

	/**
	 * Instantiate our classes.
	 */
	public function init() {
		$this->mail      = new Strong_Mail();
		$this->templates = new Strong_Templates();
		$this->debug     = new Strong_Debug();
		$this->form      = new Strong_Testimonials_Form();
	}

	/**
	 * Include required files
	 *
	 * @access private
	 * @since 1.21.0
	 * @return void
	 */
	private function includes() {

		require_once WPMTST_INC . 'class-strong-view.php';
		require_once WPMTST_INC . 'class-strong-view-display.php';
		require_once WPMTST_INC . 'class-strong-view-slideshow.php';
		require_once WPMTST_INC . 'class-strong-view-form.php';

		require_once WPMTST_INC . 'class-strong-templates.php';
		require_once WPMTST_INC . 'class-strong-mail.php';
		require_once WPMTST_INC . 'class-strong-debug.php';
		require_once WPMTST_INC . 'class-strong-form.php';
		require_once WPMTST_INC . 'class-walker-strong-category-checklist-front.php';

		require_once WPMTST_INC . 'captcha.php';
		require_once WPMTST_INC . 'deprecated.php';
		require_once WPMTST_INC . 'l10n.php';
		require_once WPMTST_INC . 'functions.php';
		require_once WPMTST_INC . 'functions-content.php';
		require_once WPMTST_INC . 'functions-rating.php';
		require_once WPMTST_INC . 'functions-image.php';
		require_once WPMTST_INC . 'functions-template.php';
		require_once WPMTST_INC . 'functions-template-form.php';
		require_once WPMTST_INC . 'post-types.php';
		require_once WPMTST_INC . 'retro.php';
		require_once WPMTST_INC . 'scripts.php';
		require_once WPMTST_INC . 'shortcodes.php';
		require_once WPMTST_INC . 'widget2.php';

		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

			require_once WPMTST_INC . 'class-strong-testimonials-list-table.php';
			require_once WPMTST_INC . 'class-strong-views-list-table.php';
			require_once WPMTST_INC . 'class-walker-strong-category-checklist.php';
			require_once WPMTST_INC . 'class-walker-strong-form-category-checklist.php';

			require_once WPMTST_ADMIN . 'admin.php';
			require_once WPMTST_ADMIN . 'admin-notices.php';
			require_once WPMTST_ADMIN . 'admin-ajax.php';
			require_once WPMTST_ADMIN . 'compat.php';
			require_once WPMTST_ADMIN . 'custom-fields.php';
			require_once WPMTST_ADMIN . 'custom-fields-ajax.php';
			require_once WPMTST_ADMIN . 'form-preview.php';
			require_once WPMTST_ADMIN . 'guide.php';
			require_once WPMTST_ADMIN . 'help.php';
			require_once WPMTST_ADMIN . 'settings.php';
			require_once WPMTST_ADMIN . 'upgrade.php';
			require_once WPMTST_ADMIN . 'views.php';
			require_once WPMTST_ADMIN . 'views-ajax.php';
			require_once WPMTST_ADMIN . 'view-list-order.php';
			require_once WPMTST_ADMIN . 'views-validate.php';

			/**
			 * Add-on plugin updater.
			 *
			 * @since 2.1
			 */
			if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				include WPMTST_INC . 'edd/EDD_SL_Plugin_Updater.php';
			}
			include WPMTST_INC . 'edd/Strong_Plugin_Updater.php';
		}
	}

	/**
	 * Text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'strong-testimonials', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Set plugin data.
	 *
	 * @since 2.12.0
	 */
	public function set_plugin_data() {
		$this->plugin_data = get_plugin_data( __FILE__, false );
	}

	/**
	 * Get plugin data.
	 *
	 * @since 2.12.0
	 *
	 * @return array
	 */
	public function get_plugin_data() {
		return $this->plugin_data;
	}

	/**
	 * Action hooks.
	 */
	private function add_actions() {

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		if ( is_admin() ) {
			// Custom fields editor
			add_action( 'wpmtst_form_admin', 'wpmtst_form_admin2' );
		}

		/**
		 * Plugin setup.
		 */
		add_action( 'init', array( $this, 'reorder_check' ) );
		add_action( 'init', array( $this, 'font_check' ) );
		add_action( 'init', array( $this, 'set_view_defaults' ) );

		/**
		 * Theme support for thumbnails.
		 */
		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );
		add_action( 'admin_init', array( $this, 'theme_support' ) );

		/**
		 * Action hook: Delete a view.
		 *
		 * @since 1.21.0
		 */
		add_action( 'admin_action_delete-strong-view', 'wpmtst_delete_view_action_hook' );

		/**
		 * @since 1.14.1
		 */
		add_filter( 'no_texturize_shortcodes', array( $this, 'no_texturize_shortcodes' ) );

		/**
		 * Be sure to process shortcodes in widget.
		 *
		 * @since 1.15.5
		 */
		add_filter( 'widget_text', 'do_shortcode' );

		add_action( 'wp_head', array( $this, 'show_version_info' ), 999 );
		add_action( 'wp_footer', array( $this, 'on_wp_footer' ), 999 );

		/**
		 * Load scripts and styles.
		 *
		 * The default behavior is to enqueue the stylesheets and scripts for
		 * a view when the shortcode is rendered, like every other plugin.
		 *
		 * We will attempt to preprocess the content in order to move the
		 * stylesheets to `wp_enqueue_scripts` in order to load them in the
		 * <head> section.
		 */
		add_action( 'wpmtst_view_rendered', array( $this, 'view_rendered' ) );
		add_action( 'wpmtst_form_rendered', array( $this, 'view_rendered' ) );
		add_action( 'wpmtst_form_success',  array( $this, 'view_rendered' ) );

		/**
		 * Conditionally enqueue styles and scripts.
		 */

		// Look for our shortcodes in post content and widgets.
		add_action( 'wp_enqueue_scripts', array( $this, 'find_views' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'find_views_in_postmeta' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'find_views_in_postexcerpt' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'find_widgets' ), 1 );

		// Page Builder by Site Origin
		if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'find_pagebuilder_widgets' ), 1 );
		}

		// Beaver Builder
		if ( defined( 'FL_BUILDER_VERSION' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'find_beaverbuilder_widgets' ), 1 );
		}

		// Black Studio TinyMCE Widget
		if ( class_exists( 'Black_Studio_TinyMCE_Plugin' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'find_blackstudio_widgets' ), 1 );
		}

	}

	/**
	 * Load stylesheet and scripts if not preprocessed.
	 *
	 * For compatibility with
	 * (1) page builders,
	 * (2) plugins like [Posts For Page] and [Custom Content Shortcode]
	 *     that pull in other posts so this plugin cannot preprocess them,
	 * (3) using the form in popup makers.
	 */
	public function view_rendered() {
		$this->load_styles();
		$this->load_scripts();

		/**
		 * Print script variables on footer hook.
		 * @since 2.25.2
		 */
		add_action( 'wp_footer',  array( $this, 'view_rendered_after' ) );
	}

	/**
	 * Add script variables only after view is rendered.
	 * To prevent duplicate variables.
	 *
	 * @since 2.24.1
	 */
	public function view_rendered_after() {
		$this->localize_scripts();
	}

	/**
	 * Add theme support for this custom post type only.
	 *
	 * Since 1.19.1, this appends our testimonial post type to the existing array,
	 * at a later priority, and only if thumbnails are not already global for all
	 * post types (an array means not global).
	 *
	 * @since 1.4.0
	 * @since 1.19.1
	 */
	public function theme_support() {
		global $_wp_theme_features;
		if ( isset( $_wp_theme_features['post-thumbnails']) && is_array( $_wp_theme_features['post-thumbnails'] ) ) {
			$_wp_theme_features['post-thumbnails'][0][] = 'wpm-testimonial';
		}

		/**
		 * Add widget thumbnail size.
		 *
		 * @since 1.21.0
		 */
		// name, width, height, crop = false
		add_image_size( 'widget-thumbnail', 75, 75, true );
	}

	/**
	 * Load reorder class if enabled.
	 */
	public function reorder_check() {
		$options = get_option( 'wpmtst_options' );
		if ( isset( $options['reorder'] ) && $options['reorder'] ) {
			require_once WPMTST_INC . 'class-strong-testimonials-order.php';
		}
	}

	/**
	 * Forgo Font Awesome.
	 */
	public function font_check() {
		$options = get_option( 'wpmtst_options' );
		if ( isset( $options['load_font_awesome'] ) && ! $options['load_font_awesome'] ) {
			add_filter( 'wpmtst_load_font_awesome', '__return_false' );
		}
	}

	/**
	 * Set shortcode.
	 */
	public function set_shortcodes() {
		$this->shortcode2    = 'testimonial_view';
		$this->shortcode2_lb = '[' . $this->shortcode2;
	}

	/**
	 * Get shortcodes.
	 */
	public function get_shortcode2() {
		return $this->shortcode2;
	}

	/**
	 * Do not texturize shortcode.
	 *
	 * @since 1.11.5
	 * @param $shortcodes
	 * @return array
	 */
	public function no_texturize_shortcodes( $shortcodes ) {
		$shortcodes[] = $this->shortcode2;
		return $shortcodes;
	}

	/**
	 * Getter for the shortcode defaults.
	 *
	 * @return array
	 */
	public function get_view_defaults() {
		return $this->view_defaults;
	}

	/**
	 * Set the defaults for a parsed View.
	 * These are different than the default settings used by the View editor.
	 * DO NOT COMBINE!
	 * @todo Find a way to DRY up.
	 */
	public function set_view_defaults() {
		$defaults = array(
			'all'                => 1,
			'background'         => array(
				'color'              => '',
				'type'               => '',
				'preset'             => '',
				'example-font-color' => 'dark',
			),
			'category'           => '',
			'class'              => '',
			'client_section'     => null,
			'column_count'       => 2,
			'container_class'    => '',
			'container_data'     => '',
			'count'              => 1,
			'display'            => '',
			'divi_builder'       => 0,
			'excerpt'            => '',
			'excerpt_length'     => 55,
			'form'               => '',
			'form_ajax'          => 0,
			'gravatar'           => 'no',
			'id'                 => '',
			'layout'             => '',
			'lightbox'           => '',
			'menu_order'         => '',
			'mode'               => '',
			'more_full_post'     => 0,
			'more_post'          => 1,
			'more_post_ellipsis' => 1,
			'more_post_text'     => _x( 'Read more', 'link', 'strong-testimonials' ),
			'more_page'          => '',
			'more_page_hook'     => 'wpmtst_view_footer',
			'more_page_id'       => 0,
			'more_page_text'     => _x( 'Read more testimonials', 'link', 'strong-testimonials' ),
			'nav'                => 'after',
			'newest'             => '',
			'note'               => '',
			'oldest'             => '',
			'pagination'         => '',
			'pagination_type'    => 'simple',
			'per_page'           => '',
			'random'             => '',
			'slideshow'          => '',
			'slideshow_settings' => array(
				'effect'             => 'fade',
				'speed'              => 1,
				'pause'              => 8,
				'auto_start'         => true,
				'auto_hover'         => true,
				'adapt_height'       => true,
				'adapt_height_speed' => '.5',
				'stretch'            => 0,
				'stop_auto_on_click' => true,
				'controls_type'      => 'none',
				'controls_style'     => 'buttons',
				'pager_type'         => 'none',
				'pager_style'        => 'buttons',
				'nav_position'       => 'inside',
			),
			'template'           => '',
			'thumbnail'          => '',
			'thumbnail_size'     => 'thumbnail',
			'thumbnail_height'   => null,
			'thumbnail_width'    => null,
			'title'              => '',
			'title_link'         => 0,
			'use_default_length' => 1,
			'use_default_more'   => 0,
			'view'               => '',
		);
		$this->view_defaults = apply_filters( 'wpmtst_view_default', $defaults );
	}

	/**
	 * Set atts.
	 *
	 * @param $atts
	 */
	public function set_atts( $atts ) {
		$this->view_atts = $atts;
	}

	/**
	 * Get att(s).
	 *
	 * @param null $keys
	 *
	 * @return array|bool
	 */
	public function atts( $keys = null ) {
		// return all
		if ( ! $keys )
			return $this->view_atts;

		// return some
		if ( is_array( $keys ) ) {
			$found = array();
			foreach ( $keys as $key ) {
				if ( isset( $this->view_atts[ $key ] ) ) {
					$found[ $key ] = $this->view_atts[ $key ];
				}
			}
			return $found;
		}

		// return one
		if ( isset( $this->view_atts[ $keys ] ) )
			return $this->view_atts[ $keys ];

		// return none
		return false;
	}


	/**
	 * Store current query.
	 *
	 * @param $query
	 */
	public function set_query( $query ) {
		$this->query = $query;
	}

	/**
	 * Return current query.
	 *
	 * @return mixed
	 */
	public function get_query() {
		return $this->query;
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
	 * Add a stylesheet handle for enqueueing.
	 *
	 * @access private
	 *
	 * @param string $style_name The stylesheet handle.
	 */
	public function add_style( $style_name ) {
		if ( ! in_array( $style_name, $this->styles ) ) {
			$this->styles[] = $style_name;
		}
	}

	/**
	 * Add a script handle for enqueueing.
	 *
	 * @param string $script_name The script handle.
	 *
	 * @since 2.17.4 Using script handle as key.
	 */
	public function add_script( $script_name ) {
		$this->scripts[ $script_name ] = $script_name;
	}

	/**
	 * Add a script variable for localizing.
	 *
	 * @param string $script_name The script handle.
	 * @param string $var_name The script variable name.
	 * @param array $var The script variable.
	 *
	 * @since 2.17.5 Using variable name as key.
	 */
	public function add_script_var( $script_name, $var_name, $var ) {
		unset($this->script_vars[ $var_name ]);
		$this->script_vars[ $var_name ] = array(
			'script_name' => $script_name,
			'var_name'    => $var_name,
			'var'         => $var,
		);
	}

	/**
	 * Enqueue stylesheets for the view being processed.
	 *
	 * @since 2.22.3
	 */
	public function load_styles() {
		$styles = $this->styles;
		if ( $styles ) {
			foreach ( $styles as $key => $style ) {
				if ( ! wp_style_is( $style ) ) {
					wp_enqueue_style( $style );
				}
			}
		}
		wp_enqueue_style( 'wpmtst-custom-style' );
	}

	/**
	 * Enqueue scripts for the view being processed.
	 *
	 * @since 2.22.3
	 */
	public function load_scripts() {
		$scripts = $this->scripts;
		if ( $scripts ) {
			foreach ( $scripts as $key => $script ) {
				if ( ! wp_script_is( $script ) ) {
					wp_enqueue_script( $script );
				}
			}
		}
	}

	/**
	 * Print script variables for the view being processed.
	 *
	 * @access public
	 * @since 2.22.3
	 * @since 2.24.1 Setting state to 'printed'.
	 */
	public function localize_scripts() {
		$vars = $this->script_vars;
		if ( $vars ) {
			foreach ( $vars as $key => $var ) {
				wp_localize_script( $var['script_name'], $var['var_name'], $var['var'] );
			}
		}
	}

	/**
	 * Check the content for our shortcodes.
	 *
	 * @param $content
	 *
	 * @return bool
	 */
	private function check_content( $content ) {
		if ( false === strpos( $content, $this->shortcode2_lb ) )
			return false;

		return true;
	}

	/**
	 * Build list of all shortcode views on a page.
	 *
	 * @access public
	 */
	public function find_views() {

		global $post;
		if ( empty( $post ) )
			return;

		$content = $post->post_content;
		if ( ! $this->check_content( $content ) )
			return;

		$this->process_content( $content );

	}

	/**
	 * Build list of all shortcode views in a page's meta fields.
	 *
	 * To handle page builders that store shortcodes and widgets in post meta.
	 *
	 * @access public
	 * @since 1.15.11
	 */
	public function find_views_in_postmeta() {

		global $post;
		if ( empty( $post ) )
			return;

		$meta_content            = get_post_meta( $post->ID );
		$meta_content_serialized = maybe_serialize( $meta_content );
		if ( ! $this->check_content( $meta_content_serialized ) )
			return;

		$this->process_content( $meta_content_serialized );

	}

	/**
	 * Build list of all shortcode views in a page's excerpt.
	 *
	 * WooCommerce stores product short description in post_excerpt field.
	 *
	 * @access public
	 * @since 1.15.12
	 */
	public function find_views_in_postexcerpt() {

		global $post;
		if ( empty( $post ) )
			return;

		if ( ! $this->check_content( $post->post_excerpt ) )
			return;

		$this->process_content( $post->post_excerpt );

	}

	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For standard widgets NOT in [Page Builder by SiteOrigin] panels.
	 *
	 * Thanks to Matthew Harris for catching strict pass-by-reference error
	 * on $id = array_pop( explode( '-', $widget_name ) ).
	 * @link https://github.com/cdillon/strong-testimonials/issues/3
	 *
	 * @access public
	 */
	public function find_widgets() {

		// Get all widgets
		$all_widgets = get_option( 'sidebars_widgets' );
		if ( ! $all_widgets )
			return;

		// Get active strong widgets
		$strong_widgets = get_option( 'widget_strong-testimonials-view-widget' );

		foreach ( $all_widgets as $sidebar => $widgets ) {

			// active widget areas only
			if ( ! $widgets || 'wp_inactive_widgets' == $sidebar || 'array_version' == $sidebar )
				continue;

			foreach ( $widgets as $key => $widget_name ) {

				// Is our widget active?
				if ( 0 === strpos( $widget_name, 'strong-testimonials-view-widget-' ) ) {

					if ( $strong_widgets ) {
						$name_parts = explode( '-', $widget_name );
						$id         = array_pop( $name_parts );

						if ( isset( $strong_widgets[ $id ] ) ) {
							$widget = $strong_widgets[ $id ];

							if ( isset( $widget['view'] ) && $widget['view'] ) {
								//TODO DRY
								$atts        = array( 'view' => $widget['view'] );
								$parsed_atts = $this->parse_view( $atts, $this->get_view_defaults(), $atts );
								if ( $this->view_not_found( $parsed_atts ) )
									continue;

								$this->preprocess( $parsed_atts );
							}

						}

					}

				}
				elseif ( 0 === strpos( $widget_name, 'text-' ) ) {

					// Get text widget content to scan for shortcodes.

					$text_widgets = get_option( 'widget_text' );

					if ( $text_widgets ) {

						$name_parts = explode( '-', $widget_name );
						$id         = array_pop( $name_parts );

						if ( isset( $text_widgets[ $id ] ) ) {
							$widget = $text_widgets[ $id ];
							$this->process_content( $widget['text'] );
						}

					}

				}

			} // foreach $widgets

		} // foreach $all_widgets
	}

	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For widgets in Page Builder by SiteOrigin.
	 */
	public function find_pagebuilder_widgets() {

		// Get all widgets
		$panels_data = get_post_meta( get_the_ID(), 'panels_data', true );
		if ( ! $panels_data )
			return;

		$all_widgets = $panels_data['widgets'];
		if ( ! $all_widgets )
			return;

		// Need to group by cell to replicate Page Builder rendering order,
		// whether these are Strong widgets or not.
		$cells = array();
		foreach ( $all_widgets as $key => $widget ) {
			$cell_id             = $widget['panels_info']['cell'];
			$cells[ $cell_id ][] = $widget;
		}

		foreach ( $cells as $cell_widgets ) {

			foreach ( $cell_widgets as $key => $widget ) {

				if ( 'Strong_Testimonials_View_Widget' == $widget['panels_info']['class'] ) {

					// Incorporate attributes from the View and defaults, just like the shortcode filter.
					if ( isset( $widget['view'] ) && $widget['view'] ) {
						//TODO DRY
						$atts        = array( 'view' => $widget['view'] );
						$parsed_atts = $this->parse_view( $atts, $this->get_view_defaults(), $atts );
						if ( $this->view_not_found( $parsed_atts ) )
							continue;

						$this->preprocess( $parsed_atts );
					}

				}
				elseif ( 'WP_Widget_Text' == $widget['panels_info']['class'] ) {

					// Is a Text widget?
					$this->process_content( $widget['text'] );

				}

			}

		}
	}

	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For widgets in Beaver Builder.
	 */
	public function find_beaverbuilder_widgets() {

		$nodes = get_post_meta( get_the_ID(), '_fl_builder_data', true );
		if ( ! $nodes )
			return;

		foreach ( $nodes as $key => $node ) {

			if ( 'module' != $node->type )
				continue;

			if ( 'widget' != $node->settings->type )
				continue;

			if ( 'Strong_Testimonials_View_Widget' == $node->settings->widget ) {

				$settings = (array) $node->settings;
				$widget   = (array) $settings['widget-strong-testimonials-view-widget'];
				if ( isset( $widget['view'] ) && $widget['view'] ) {
					$atts        = array( 'view' => $widget['view'] );
					$parsed_atts = $this->parse_view( $atts, $this->get_view_defaults(), $atts );
					if ( $this->view_not_found( $parsed_atts ) )
						continue;

					$this->preprocess( $parsed_atts );
				}

			}

		}
	}


	/**
	 * Build list of all shortcode views in Black Studio TinyMCE Widget.
	 *
	 * @access public
	 * @since 1.16.14
	 */
	public function find_blackstudio_widgets() {

		global $post;
		if ( empty( $post ) )
			return;

		$widget_content = get_option( 'widget_black-studio-tinymce' );
		if ( ! $widget_content )
			return;

		$widget_content_serialized = maybe_serialize( $widget_content );
		if ( ! $this->check_content( $widget_content_serialized ) )
			return;

		$this->process_content( $widget_content_serialized );

	}

	/**
	 * @param $atts
	 *
	 * @return bool
	 */
	private function view_not_found( $atts ) {
		return ( isset( $atts['view_not_found'] ) && $atts['view_not_found'] );
	}

	/**
	 * Process content for shortcodes.
	 *
	 * A combination of has_shortcode and shortcode_parse_atts.
	 * This seems to solve the unenclosed shortcode issue too.
	 *
	 * @access private
	 *
	 * @param string $content Post content or widget content.
	 */
	private function process_content( $content ) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) )
			return;

		foreach ( $matches as $key => $shortcode ) {

			if ( $this->shortcode2 === $shortcode[2]) {

				/**
				 * Adding html_entity_decode.
				 * @since 1.16.13
				 */
				// Retrieve all attributes from the shortcode.
				$original_atts = shortcode_parse_atts( html_entity_decode( $shortcode[3] ) );

				// Incorporate attributes from the View and defaults.
				$parsed_atts = $this->parse_view( $original_atts, $this->get_view_defaults(), $original_atts );
				if ( $this->view_not_found( $parsed_atts ) )
					continue;

				$this->preprocess( $parsed_atts );

			}
			else {

				/**
				 * Recursively process nested shortcodes.
				 *
				 * Handles:
				 * Elegant Themes page builder.
				 *
				 * @since 1.15.5
				 */
				$this->process_content( $shortcode[5] );

			}

		}
	}

	/**
	 * Preprocess a view to gather styles, scripts, and script vars.
	 *
	 * Similar to wpmtst_render_view in shortcodes.php.
	 *
	 * @param $atts
	 * @since 1.25.0
	 * @since 2.5.0  Move some processing to Strong_View class.
	 * @since 2.16.0 Move all processing to Strong_View class.
	 */
	private function preprocess( $atts ) {
		$atts = shortcode_atts(
			$this->get_view_defaults(),
			$atts
		);
		$this->view_atts = $atts;

		if ( $atts['form'] ) {
			$new_view = new Strong_View_Form( $atts );
		}  elseif ( $atts['slideshow'] ) {
			$new_view = new Strong_View_Slideshow( $atts );
		}  else {
			$new_view = new Strong_View_Display( $atts );
		}
		$new_view->process();

		/**
		 * Move scripts and styles to normal hook.
		 * The whole purpose of preprocessing is to load our styles
		 * in <head> to avoid FOUC.
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'view_rendered' ) );

		/**
		 * Allow themes and plugins to do stuff like add extra stylesheets.
		 *
		 * @since 2.22.0
		 */
		do_action( 'wpmtst_view_found', $atts );
	}

	/**
	 * A WP_Error object with the phpmailerException code, message, and an array
	 * containing the mail recipient, subject, message, headers, and attachments.
	 *
	 * @since 2.4.0
	 *
	 * @param $error
	 */
	public function catch_mail_failed( $error ) {
		$this->debug->log( $error );
	}


	/**
	 * Parse view attributes.
	 *
	 * @param array $out   The output array of shortcode attributes.
	 * @param array $pairs The supported attributes and their defaults.
	 * @param array $atts  The user defined shortcode attributes.
	 *
	 * @return array
	 */
	public function parse_view( $out, $pairs, $atts ) {
		/**
		 * Convert "id" to "view"
		 */
		if ( isset( $atts['id'] ) && $atts['id'] ) {
			$out['view'] = $atts['id'];
			$out['id'] = null;
			unset( $atts['id'] );
		}

		// fetch the view
		$view = wpmtst_get_view( $out['view'] );

		/**
		 * Add error attribute for shortcode handler.
		 *
		 * @since 1.21.0
		 */
		if ( !$view ) {
			return array_merge( array( 'view_not_found' => 1 ), $out );
		}

		$view_data = unserialize( $view['value'] );

		// =============================================================
		// DECENTRALIZE
		// This is necessary because of the way we use empty attributes;
		// e.g. random --> random="true"
		// =============================================================

		// -----------------------------------------------------------------
		// rule: unset unused defaults that interfere (i.e. dependent rules)
		// -----------------------------------------------------------------

		if ( 'all' == $view_data['category'] ) {
			unset( $view_data['category'] );
		}

		if ( ! $view_data['id'] ) {
			unset( $view_data['id'] );
		}

		if ( $view_data['all'] ) {
			unset( $view_data['count'] );
		}

		if ( ! $view_data['pagination'] ) {
			unset( $view_data['per_page'] );
		}

		if ( 'slideshow' == $view_data['mode'] ) {
			unset( $view_data['id'] );
		} else {
			unset( $view_data['slideshow_settings'] );
		}

		// ------------------------------
		// rule: extract value from array
		// ------------------------------

		$out[ $view_data['mode'] ] = true;
		unset( $view_data['mode'] );

		$out[ $view_data['order'] ] = true;
		unset( $view_data['order'] );

		$out[ $view_data['content'] ] = true;
		unset( $view_data['content'] );

		// -----------------------------
		// merge view onto user settings
		// -----------------------------

		$out = array_merge( $out, $view_data );
		return $out;
	}

	/**
	 * Store form values.
	 *
	 * TODO Move to form object.
	 *
	 * @param $form_values
	 */
	public function set_form_values( $form_values ) {
		$this->form_values = $form_values;
	}

	/**
	 * Return form values.
	 *
	 * @return mixed
	 */
	public function get_form_values() {
		return $this->form_values;
	}

	/**
	 * Store from errors.
	 *
	 * @param $form_errors
	 */
	public function set_form_errors( $form_errors ) {
		$this->form_errors = $form_errors;
	}

	/**
	 * Return form errors.
	 *
	 * @return mixed
	 */
	public function get_form_errors() {
		return $this->form_errors;
	}

	/**
	 * Show version number in <head> section.
	 *
	 * For troubleshooting only.
	 *
	 * @since 1.12.0
	 * @since 2.19.0 Including add-ons.
	 */
	function show_version_info() {
		global $wp_version;
		$plugin_info = $this->get_plugin_info();
		$comment = array(
			'WordPress ' . $wp_version,
			$plugin_info['name'] . ' ' . $plugin_info['version'],
		);
		$addons = get_option( 'wpmtst_addons' );
		if ( $addons ) {
			foreach( $addons as $addon ) {
				$comment[] = $addon['name'] . ' ' . $addon['version'];
			}
		}

		echo "<!-- versions: " . implode( ' | ', $comment ) . " -->\n";
	}

	/**
	 * Did the theme call wp_footer?
	 *
	 * @since 2.22.3 As separate function
	 */
	public function on_wp_footer() {
		echo "<!-- wp_footer called -->\n";
	}

	/**
	 * Return plugin info.
	 *
	 * @return array
	 */
	public function get_plugin_info() {
		return get_file_data( __FILE__, array( 'name' => 'Plugin Name', 'version' => 'Version' ) );
	}

}

endif; // class_exists check

register_activation_hook( __FILE__, array( 'Strong_Testimonials', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Strong_Testimonials', 'plugin_deactivation' ) );

function WPMST() {
	return Strong_Testimonials::instance();
}

// Get plugin running
WPMST();
