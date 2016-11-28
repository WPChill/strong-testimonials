<?php
/**
 * Plugin Name: Strong Testimonials
 * Plugin URI: https://www.wpmission.com/plugins/strong-testimonials/
 * Description: A full-featured plugin that works right out of the box for beginners and offers advanced features for pros.
 * Author: Chris Dillon
 * Version: 2.14.3
 * Author URI: https://www.wpmission.com/
 * Text Domain: strong-testimonials
 * Domain Path: /languages
 * Requires: 3.6 or higher
 * License: GPLv3 or later
 *
 * Copyright 2014-2016 Chris Dillon chris@wpmission.com
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
 * @since 1.15.0
 */
final class Strong_Testimonials {

	private static $instance;

	private static $db_version = '1.0';
	public static $styles = array( 'normal' => array(), 'later' => array() );
	public static $scripts = array( 'normal' => array(), 'later' => array() );
	public static $css = array();
	public static $script_vars;
	public static $shortcode2;
	public static $shortcode2_lb;
	public static $view_defaults = array();
	public static $view_atts = array();
	public static $query;
	public static $form_values;
	public static $form_errors;
	public static $post_list = array();
	public static $post_list_transient_name = '';
	public static $plugin_data;

	/**
	 * @var Strong_Mail
	 */
	public $mail;

	/**
	 * @var Strong_Templates
	 */
	public $templates;

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


		if ( ! defined( 'WPMTST_COMMON' ) )
			define( 'WPMTST_COMMON', plugin_dir_path( __FILE__ ) . 'common/' );
		if ( ! defined( 'WPMTST_COMMON_URL' ) )
			define( 'WPMTST_COMMON_URL', plugin_dir_url( __FILE__ ) . 'common/' );


		if ( ! defined( 'WPMTST_DEF_TPL' ) )
			define( 'WPMTST_DEF_TPL', plugin_dir_path( __FILE__ ) . 'templates/default/' );
		if ( ! defined( 'WPMTST_DEF_TPL_URI' ) )
			define( 'WPMTST_DEF_TPL_URI', plugin_dir_url( __FILE__ ) . 'templates/default/' );


		if ( ! defined( 'WPMTST_TPL' ) )
			define( 'WPMTST_TPL', plugin_dir_path( __FILE__ ) . 'templates' );
		if ( ! defined( 'WPMTST_TPL_URI' ) )
			define( 'WPMTST_TPL_URI', plugin_dir_url( __FILE__ ) . 'templates' );

	}


	/**
	 * Instantiate our classes.
	 */
	public function init() {
		self::$instance->mail      = new Strong_Mail();
		self::$instance->templates = new Strong_Templates();
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
		require_once WPMTST_INC . 'class-strong-templates.php';
		require_once WPMTST_INC . 'class-strong-mail.php';
		require_once WPMTST_INC . 'l10n.php';
		require_once WPMTST_INC . 'post-types.php';
		require_once WPMTST_INC . 'functions.php';
		require_once WPMTST_INC . 'rating-functions.php';
		require_once WPMTST_INC . 'retro.php';
		require_once WPMTST_INC . 'widget2.php';

		/**
		 * These are not normally needed in admin.
		 * Including here for compatibility with page builders.
		 *
		 * @since 1.25.3
		 * @todo Load only when needed.
		 */
		require_once WPMTST_INC . 'shortcodes.php';
		require_once WPMTST_INC . 'template-functions.php';
		require_once WPMTST_INC . 'form-template-functions.php';
		require_once WPMTST_INC . 'captcha.php';
		require_once WPMTST_INC . 'scripts.php';

		if ( is_admin() ) {

			require_once WPMTST_INC . 'class-strong-testimonials-list-table.php';
			require_once WPMTST_INC . 'class-strong-views-list-table.php';
			require_once WPMTST_INC . 'class-walker-wpmst-category-checklist.php';
			require_once WPMTST_INC . 'class-walker-wpmst-form-category-checklist.php';

			require_once WPMTST_ADMIN . 'admin.php';
			require_once WPMTST_ADMIN . 'admin-ajax.php';

			require_once WPMTST_ADMIN . 'partials/compat.php';
			require_once WPMTST_ADMIN . 'partials/custom-fields.php';
			require_once WPMTST_ADMIN . 'partials/custom-fields-ajax.php';
			require_once WPMTST_ADMIN . 'partials/form-preview.php';
			require_once WPMTST_ADMIN . 'partials/guide.php';
			require_once WPMTST_ADMIN . 'partials/help.php';
			require_once WPMTST_ADMIN . 'partials/install.php';
			require_once WPMTST_ADMIN . 'partials/settings.php';
			require_once WPMTST_ADMIN . 'partials/upgrade.php';
			require_once WPMTST_ADMIN . 'partials/views.php';
			require_once WPMTST_ADMIN . 'partials/views-ajax.php';
			require_once WPMTST_ADMIN . 'partials/views-validate.php';

		}

		/**
		 * Add-on plugin updater.
		 *
		 * @since 2.1
		 */
		require_once WPMTST_INC . 'edd/WPMST_Plugin_Updater.php';
	}

	/**
	 * Text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'strong-testimonials', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Plugin data.
	 *
	 * @since 2.12.0
	 */
	public function set_plugin_data() {
		self::$plugin_data = get_plugin_data( __FILE__, false );
	}

	public function get_plugin_data() {
		return self::$plugin_data;
	}

	/**
	 * Action hooks.
	 */
	private function add_actions() {

		add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

		if ( is_admin() ) {

			add_action( 'wpmtst_form_admin', 'wpmtst_form_admin2' );

		}
		else {

			// Process form data.
			add_action( 'init', array( $this, 'process_form' ) );

			// Catch email errors.
			add_action( 'wp_mail_failed', array( $this, 'catch_mail_failed' ) );

			/**
			 * Actions on 'wp' hook allow us to properly enqueue styles and scripts.
			 * TODO Consolidate. Make conditional where possible; i.e. only check for Page Builder widgets if that plugin is active.
			 */

			// Preprocess the post content for our shortcodes.
			add_action( 'wp', array( $this, 'find_views' ), 20 );
			add_action( 'wp', array( $this, 'find_views_in_postmeta' ), 20 );
			add_action( 'wp', array( $this, 'find_views_in_postexcerpt' ), 20 );

			// Page Builder by Site Origin
			add_action( 'wp', array( $this, 'find_pagebuilder_widgets' ), 20 );

			// Beaver Builder
			add_action( 'wp', array( $this, 'find_beaverbuilder_widgets' ), 20 );

			// Black Studio TinyMCE Widget
			add_action( 'wp', array( $this, 'find_blackstudio_widgets' ), 20 );

			// Preprocess the page for widgets.
			add_action( 'wp', array( $this, 'find_widgets' ), 20 );

			// Elegant Themes - Home page content areas
			add_action( 'wp', array( $this, 'find_views_elegant_themes' ), 20 );

			// Profit Builder - stores the rendered shortcode (!)
			add_action( 'wp', array( $this, 'find_rendered_views' ), 20 );

			// Bretheon theme - stores content in post_meta using base 64 encode (!)
			add_action( 'wp', array( $this, 'find_views_in_postmeta_encoded' ), 20 );

		}

		/**
		 * Theme support for thumbnails.
		 */
		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );
		add_action( 'admin_init', array( $this, 'theme_support' ) );

		add_action( 'init', array( $this, 'reorder_check' ) );
		add_action( 'init', array( $this, 'set_view_defaults' ) );

		/**
		 * Localize scripts.
		 *
		 * TODO Check if theme does not call wp_footer.
		 *
		 * @since 1.16.11
		 */
		add_action( 'wp_footer', array( $this, 'localize_vars' ) );

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

		/**
		 * Action hooks after a view has been rendered.
		 */
		add_action( 'wpmtst_view_rendered', array( $this, 'view_rendered' ), 10, 1 );
		add_action( 'wpmtst_form_rendered', array( $this, 'form_rendered' ), 10, 1 );
		add_action( 'wpmtst_form_success', array( $this, 'form_success' ), 10, 1 );

		/**
		 * Ajax form submission handler
		 *
		 * @since 1.25.0
		 */
		add_action( 'wp_ajax_wpmtst_form2', array( $this, 'form_handler2' ) );
		add_action( 'wp_ajax_nopriv_wpmtst_form2', array( $this, 'form_handler2' ) );
	}

	public function form_handler2() {
		if ( isset( $_POST['wpmtst_form_nonce'] ) ) {
			require_once WPMTST_INC . 'shortcodes.php';
			require_once WPMTST_INC . 'form-handler-functions.php';
			$success = wpmtst_form_handler();
			if ( $success ) {
				$return = array( 'success' => true, 'message' => '<div class="testimonial-success">' . wpmtst_get_form_message( 'submission-success' ) . '</div>' );
			}
			else {
				$return = array( 'success' => false, 'errors' => WPMST()->get_form_errors() );
			}
			echo json_encode( $return );
		}

		die();
	}

	/**
	 * Do stuff after the form is rendered like load stylesheets and scripts.
	 * For compatibility with page builders and popup makers.
	 *
	 * @since 1.25.0 Checking $atts['compat']
	 * @since 2.3 Added wp_script_is( $handle ) as last check.
	 *
	 * @param $atts
	 */
	public function form_rendered( $atts ) {
		$handle = self::find_stylesheet( $atts, false );

		if ( ( isset( $atts['compat'] ) && $atts['compat'] ) || ! wp_script_is( $handle ) ) {

			self::find_stylesheet( $atts, true, false );

		}

		self::after_form( $atts );
	}

	public function form_success( $atts ) {
		$handle = self::find_stylesheet( $atts, false );

		if ( ( isset( $atts['compat'] ) && $atts['compat'] ) || ! wp_script_is( $handle ) ) {

			self::find_stylesheet( $atts, true, false );

		}

		self::after_form_success();
	}

	/**
	 * Compatibility mode: Load stylesheet and scripts if not already.
	 * For compatibility with page builders and plugins like
	 * Posts For Page and Custom Content Shortcode
	 * that pull in other posts so this plugin cannot preprocess them.
	 *
	 * Required for the template function strong_testimonials_view.
	 *
	 * @since 1.25.0 Checking $atts['compat']
	 * @since 2.3    Added wp_script_is( $handle ) as last check.
	 * @since 2.5.2  Added pagination. When using `strong_testimonials_view` template function.
	 *
	 * @param $atts
	 */
	public function view_rendered( $atts ) {

		$handle = self::find_stylesheet( $atts, false );

		if ( ( isset( $atts['compat'] ) && $atts['compat'] ) || ! wp_script_is( $handle ) ) {

			wp_enqueue_style( $handle );

			self::custom_background( $atts['view'], $atts['background'] );

			if ( isset( $atts['slideshow'] ) && $atts['slideshow'] ) {
				self::after_slideshow( $atts );
			}

			if ( isset( $atts['pagination'] ) && $atts['pagination'] ) {
				self::after_pagination( $atts );
			}

		}

		/**
		 * Enqueue layout stuff if preprocessing fails.
		 * @since 2.5.7
		 */
		if ( 'masonry' == $atts['layout'] ) {
			if ( ! wp_script_is('wpmtst-masonry-script' ) ) {
				wp_enqueue_script( 'wpmtst-masonry-script' );
			}
			if ( ! wp_style_is('wpmtst-masonry-style' ) ) {
				wp_enqueue_style( 'wpmtst-masonry-style' );
			}
		}
		elseif ( 'columns' == $atts['layout'] ) {
			if ( ! wp_style_is('wpmtst-columns-style' ) ) {
				wp_enqueue_style( 'wpmtst-columns-style' );
			}
		}
		elseif ( 'grid' == $atts['layout'] ) {
			if ( ! wp_script_is('wpmtst-grid-script' ) ) {
				wp_enqueue_script( 'wpmtst-grid-script' );
			}
			if ( ! wp_style_is('wpmtst-grid-style' ) ) {
				wp_enqueue_style( 'wpmtst-grid-style' );
			}
		}

		/**
		 * Stars
		 */
		if ( isset( $atts['client_section'] ) ) {
			foreach ( $atts['client_section'] as $field ) {
				if ( 'rating' == $field['type'] ) {
					if ( ! wp_style_is('wpmtst-rating-display' ) ) {
						wp_enqueue_style( 'wpmtst-rating-display' );
					}
				}
			}
		}

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
	 * @return mixed|void
	 */
	public function get_background_defaults() {
		return apply_filters( 'wpmtst_default_template_background', array(
			'color'              => '',
			'type'               => '',
			'preset'             => '',
			'gradient1'          => '',
			'gradient2'          => '',
			'example-font-color' => 'dark',
		) );
	}

	/**
	 * @param null $preset
	 *
	 * TODO Move to options and add a filter.
	 * @return array|bool
	 */
	public function get_background_presets( $preset = null ) {
		$presets = array(
			'light-gray-gradient' => array(
				'label'  => __( 'light gray gradient', 'strong-testimonials' ),
				'color'  => '#FBFBFB',
				'color2' => '#EDEDED',
			),
			'light-blue-gradient' => array(
				'label'  => __( 'light blue gradient', 'strong-testimonials' ),
				'color'  => '#E7EFFE',
				'color2' => '#B8CFFB',
			),
			'sky-blue-gradient' => array(
				'label'  => __( 'sky blue gradient', 'strong-testimonials' ),
				'color'  => '#E9F6FB',
				'color2' => '#C8E9F6',
			),
			'light-latte-gradient' => array(
				'label'  => __( 'light latte gradient', 'strong-testimonials' ),
				'color'  => '#F8F3EC',
				'color2' => '#E0C8AB',
			),
			'light-green-mist-gradient' => array(
				'label'  => __( 'light green mist gradient', 'strong-testimonials' ),
				'color'  => '#F2FBE9',
				'color2' => '#E0F7CC',
			),
			'light-plum-gradient' => array(
				'label'  => __( 'light plum gradient', 'strong-testimonials' ),
				'color'  => '#F7EEF7',
				'color2' => '#E9D0E9',
			),
		);

		ksort( $presets );

		if ( !$preset )
			return $presets;

		if ( isset( $presets[ $preset] ) )
			return $presets[ $preset ];

		return false;
	}

	/**
	 * Set shortcode.
	 */
	public function set_shortcodes() {
		self::$shortcode2    = 'testimonial_view';
		self::$shortcode2_lb = '[' . self::$shortcode2;
	}

	/**
	 * Get shortcodes.
	 */
	public function get_shortcode2() {
		return self::$shortcode2;
	}

	/**
	 * Do not texturize shortcode.
	 *
	 * @since 1.11.5
	 * @param $shortcodes
	 * @return array
	 */
	function no_texturize_shortcodes( $shortcodes ) {
		$shortcodes[] = self::$shortcode2;
		return $shortcodes;
	}

	/**
	 * Getter for the shortcode defaults.
	 *
	 * @return array
	 */
	public static function get_view_defaults() {
		return self::$view_defaults;
	}

	/**
	 * Set the defaults for a parsed View.
	 * These are different than the default settings used by the View editor.
	 * DO NOT COMBINE!
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
			'compat'             => 0,
			'container_class'    => '',
			'container_data'     => '',
			'count'              => 1,
			'display'            => '',
			'excerpt'            => '',
			'excerpt_length'     => 55,
			'form'               => '',
			'form-ajax'          => 0,
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
			'use_default_length' => 1,
			'use_default_more'   => 0,
			'view'               => '',
		);
		self::$view_defaults = $defaults;
	}

	/**
	 * Set atts.
	 *
	 * @param $atts
	 */
	public function set_atts( $atts ) {
		self::$view_atts = $atts;
	}

	/**
	 * Get att(s). Return false if not found.
	 *
	 * @param null $keys
	 *
	 * @return array|bool
	 */
	public function atts( $keys = null ) {
		// return all
		if ( ! $keys )
			return self::$view_atts;

		// return some
		if ( is_array( $keys ) ) {
			$found = array();
			foreach ( $keys as $key ) {
				if ( isset( self::$view_atts[ $key ] ) ) {
					$found[ $key ] = self::$view_atts[ $key ];
				}
			}
			return $found;
		}

		// return one
		if ( isset( self::$view_atts[ $keys ] ) )
			return self::$view_atts[ $keys ];

		// return none
		return false;
	}


	public function set_query( $query ) {
		self::$query = $query;
	}
	public function get_query() {
		return self::$query;
	}

	/**
	 * Get database tables version.
	 *
	 * @return string
	 */
	public function get_db_version() {
		return self::$db_version;
	}

	/**
	 * Access to the stylesheets needed for this page.
	 *
	 * @access public
	 * @return array  An array of stylesheet handles.
	 */
	public static function get_styles() {
		return self::$styles;
	}

	/**
	 * Access to the scripts needed for this page.
	 *
	 * @access public
	 * @return array  An array of script handles.
	 */
	public static function get_scripts() {
		return self::$scripts;
	}

	/**
	 * Add a stylesheet handle for enqueueing.
	 *
	 * @access private
	 *
	 * @param string $style_name The stylesheet handle.
	 * @param string $when The enqueue priority. normal = priority 10, later = after theme ~200.
	 */
	private static function add_style( $style_name, $when = 'normal' ) {
		if ( ! in_array( $style_name, self::$styles[ $when ] ) ) {
			self::$styles[ $when ][] = $style_name;
		}
	}

	/**
	 * Add a script handle for enqueueing.
	 *
	 * @access private
	 *
	 * @param string $script_name The script handle.
	 * @param string $when The enqueue priority. normal|later
	 */
	private static function add_script( $script_name, $when = 'normal' ) {
		if ( ! in_array( $script_name, self::$scripts[ $when ] ) ) {
			self::$scripts[ $when ][] = $script_name;
		}
	}

	/**
	 * Add a script variable for localizing.
	 *
	 * @access private
	 *
	 * @param string $script_name The script handle.
	 * @param string $var_name The script variable name.
	 * @param string $var The script variable.
	 */
	private static function add_script_var( $script_name, $var_name, $var ) {
		self::$script_vars[] = array( 'script_name' => $script_name, 'var_name' => $var_name, 'var' => $var );
	}

	/**
	 * Localize scripts with their variables.
	 *
	 * @access public
	 */
	public static function localize_vars() {
		$vars = self::$script_vars;
		if ( $vars ) {
			foreach ( $vars as $var ) {
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
	private static function check_content( $content ) {
		if ( false === strpos( $content, self::$shortcode2_lb ) )
			return false;

		return true;
	}

	/**
	 * Check the content for shortcodes that have been rendered already.
	 * For some hacky page builders.
	 *
	 * @param $content
	 *
	 * @return bool
	 */
	private static function check_content_for_rendered_shortcodes( $content ) {
		if ( preg_match_all( '/div class=(.*?) (strong-view-id-([0-9]*))/', $content, $matches ) ) {
			return $matches[3];
		}

		return false;
	}

	/**
	 * Build list of all shortcode views on a page.
	 *
	 * @access public
	 */
	public static function find_views() {

		global $post;
		if ( empty( $post ) )
			return false;

		$content = $post->post_content;
		if ( ! self::check_content( $content ) )
			return false;

		self::process_content( $content );

	}

	/**
	 * Build list of all shortcode views in a page's meta fields.
	 *
	 * To handle page builders that store shortcodes and widgets in post meta.
	 *
	 * @access public
	 * @since 1.15.11
	 */
	public static function find_views_in_postmeta() {

		global $post;
		if ( empty( $post ) )
			return false;

		$meta_content            = get_post_meta( $post->ID );
		$meta_content_serialized = maybe_serialize( $meta_content );
		if ( ! self::check_content( $meta_content_serialized ) )
			return false;

		self::process_content( $meta_content_serialized );

	}

	/**
	 * Build list of all shortcode views in a page's excerpt.
	 *
	 * WooCommerce stores product short description in post_excerpt field.
	 *
	 * @access public
	 * @since 1.15.12
	 */
	public static function find_views_in_postexcerpt() {

		global $post;
		if ( empty( $post ) )
			return false;

		if ( ! self::check_content( $post->post_excerpt ) )
			return false;

		self::process_content( $post->post_excerpt );

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
	public static function find_widgets() {

		// Get all widgets
		$all_widgets = get_option( 'sidebars_widgets' );
		if ( ! $all_widgets )
			return;

		// Get active strong widgets
		$strong_widgets = get_option( 'widget_strong-testimonials-view-widget' );
		/*
		Array
		(
			[wp_inactive_widgets] => Array
				(
				)

			[pinbin_footer] => Array
				(
					[0] => search-2
					[1] => recent-posts-2
					[2] => recent-comments-2
					[3] => archives-2
					[4] => categories-2
					[5] => meta-2
				)

			[mega-menu] => Array
				(
					[0] => strong-testimonials-view-widget-3
				)

			[array_version] => 3
		)
		 */

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
								$parsed_atts = self::parse_view( $atts, self::get_view_defaults(), $atts );
								if ( self::view_not_found( $parsed_atts ) )
									continue;

								self::find_single_view( $parsed_atts );
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
							self::process_content( $widget['text'] );
						}

					}

				}

			} // foreach $widgets

		} // foreach $all_widgets
	}

	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For widgets in [Page Builder by SiteOrigin] panels.
	 */
	public static function find_pagebuilder_widgets() {

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
						$parsed_atts = self::parse_view( $atts, self::get_view_defaults(), $atts );
						if ( self::view_not_found( $parsed_atts ) )
							continue;

						self::find_single_view( $parsed_atts );
					}

				}
				elseif ( 'WP_Widget_Text' == $widget['panels_info']['class'] ) {

					// Is a Text widget?
					self::process_content( $widget['text'] );

				}

			}

		}
	}

	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For widgets in [Page Builder by SiteOrigin] panels.
	 */
	public static function find_beaverbuilder_widgets() {

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
					$parsed_atts = self::parse_view( $atts, self::get_view_defaults(), $atts );
					if ( self::view_not_found( $parsed_atts ) )
						continue;

					self::find_single_view( $parsed_atts );
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
	public static function find_blackstudio_widgets() {

		global $post;
		if ( empty( $post ) )
			return;

		$widget_content = get_option( 'widget_black-studio-tinymce' );
		if ( ! $widget_content )
			return;

		$widget_content_serialized = maybe_serialize( $widget_content );
		if ( ! self::check_content( $widget_content_serialized ) )
			return;

		self::process_content( $widget_content_serialized );

	}

	/**
	 * Build list of all shortcode views in the various convolutions of Elegant Themes.
	 *
	 * @since 1.23
	 * @access public
	 */
	public static function find_views_elegant_themes() {

		global $post;
		if ( empty( $post ) )
			return;

		if ( get_option( 'mycuisine_home_page_1' ) ) {
			$target = get_post( get_option( 'mycuisine_home_page_1' ) );
			if ( $target ) {
				$content = $target->post_content;
				if ( self::check_content( $content ) ) {
					self::process_content( $content );
				}
			}
		}
		if ( get_option( 'mycuisine_home_page_2' ) ) {
			$target = get_post( get_option( 'mycuisine_home_page_2' ) );
			if ( $target ) {
				$content = $target->post_content;
				if ( self::check_content( $content ) ) {
					self::process_content( $content );
				}
			}
		}
		if ( get_option( 'mycuisine_home_page_3' ) ) {
			$target = get_post( get_option( 'mycuisine_home_page_3' ) );
			if ( $target ) {
				$content = $target->post_content;
				if ( self::check_content( $content ) ) {
					self::process_content( $content );
				}
			}
		}

	}

	/**
	 * Build list of all shortcode views on a page.
	 *
	 * @access public
	 */
	public static function find_rendered_views() {

		global $post;
		if ( empty( $post ) )
			return;

		$content = $post->post_content;
		$view_ids = self::check_content_for_rendered_shortcodes( $content );
		if ( !$view_ids )
			return;

		self::process_content_for_rendered_shortcodes( $view_ids );

	}

	/**
	 * Build list of all encoded shortcode views in a page's meta fields.
	 *
	 * To handle page builders that encode and store shortcodes and widgets in post meta.
	 * - Bretheon theme
	 *
	 * @access public
	 * @since 2.5.6
	 */
	public static function find_views_in_postmeta_encoded() {

		global $post;
		if ( empty( $post ) ) {
			return false;
		}

		$meta_content = get_post_meta( $post->ID, 'mfn-page-items', true );
		if ( ! is_string( $meta_content ) ) {
			return false;
		}

		$mfn_tmp_fn   = 'base' . '64_decode';
		$meta_content = call_user_func( $mfn_tmp_fn, $meta_content );
		if ( ! self::check_content( $meta_content ) ) {
			return false;
		}

		self::process_content( $meta_content );

	}

	/**
	 * @param $atts
	 *
	 * @return bool
	 */
	private static function view_not_found( $atts ) {
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
	 *
	 * @return bool
	 */
	private static function process_content( $content ) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) )
			return false;

		foreach ( $matches as $key => $shortcode ) {

			if ( self::$shortcode2 === $shortcode[2]) {

				/**
				 * Adding html_entity_decode.
				 * @since 1.16.13
				 */
				// Retrieve all attributes from the shortcode.
				$original_atts = shortcode_parse_atts( html_entity_decode( $shortcode[3] ) );

				// Incorporate attributes from the View and defaults.
				$parsed_atts = self::parse_view( $original_atts, self::get_view_defaults(), $original_atts );
				if ( self::view_not_found( $parsed_atts ) )
					continue;

				self::find_single_view( $parsed_atts );

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
				self::process_content( $shortcode[5] );

			}

		}
	}

	/**
	 * Process content for rendered shortcodes.
	 *
	 * @access private
	 *
	 * @param array|null $view_ids
	 */
	private static function process_content_for_rendered_shortcodes( $view_ids = null ) {
		if ( !$view_ids )
			return;

		foreach ( $view_ids as $view_id ) {

			// Incorporate attributes from the View and defaults.
			$original_atts = array( 'id' => $view_id );
			$parsed_atts = self::parse_view( $original_atts, self::get_view_defaults(), $original_atts );

			self::find_single_view( $parsed_atts );

		}
	}

	/**
	 * Process a single [testimonial_view] shortcode.
	 *
	 * TODO Move all this to hooks and filters.
	 * TODO So not DRY -- improve in version 2.0
	 *
	 * @since 1.21.0 [testimonial_view]
	 * @param $atts
	 * @return array
	 */
	private static function find_single_view( $atts ) {
		// Turn empty atts into switches.
		$atts = normalize_empty_atts( $atts );

		$preprocess      = false;
		$preprocess_form = false;
		$handle          = false;

		/**
		 * ==============================
		 * Modes
		 * ==============================
		 */

		if ( isset( $atts['form'] ) ) {

			/**
			 * ------------------------------
			 * Form
			 * ------------------------------
			 */
			$view            = array( 'mode' => 'form', 'atts' => $atts );
			$preprocess_form = true;

			if ( ! isset( $atts['compat']) || ! $atts['compat'] ) {
				$handle = self::find_stylesheet( $atts );
			}

			self::after_form( $atts );

		}
		elseif ( isset( $atts['slideshow'] ) ) {

			/**
			 * ------------------------------
			 * Slideshow
			 * ------------------------------
			 */
			$view       = array( 'mode' => 'slideshow', 'atts' => $atts );
			$preprocess = true;

			if ( ! isset( $atts['compat'] ) || ! $atts['compat'] ) {
				$handle = self::find_stylesheet( $atts );
			}

		}
		else {

			/**
			 * Display (default)
			 */
			$view       = array( 'mode' => 'display', 'atts' => $atts );
			$preprocess = true;
			$handle     = self::find_stylesheet( $atts );

		}

		/**
		 * Process attributes to check for required styles & scripts.
		 *
		 * Add check for compatibility mode @since 1.25.0
		 */
		if ( ! isset( $atts['compat'] ) || ! $atts['compat'] ) {
			if ( $preprocess )
				self::preprocess( $view, $handle );
			elseif ( $preprocess_form )
				self::preprocess_form( $view, $handle );
		}

		return $view;
	}

	/**
	 * Load form validation and add honeypot actions.
	 *
	 * @since 1.25.0
	 *
	 * @param array $atts
	 */
	private static function after_form( $atts = array() ) {
		$form_options = get_option('wpmtst_form_options');

		if ( ! wp_style_is( 'wpmtst-rating-form' ) ) {
			wp_enqueue_style( 'wpmtst-rating-form' );
		}

		wp_localize_script( 'wpmtst-form', 'formError', array(
			'scrollTop' => $form_options['scrolltop_error'],
			'offset'    => $form_options['scrolltop_error_offset']
		) );
		wp_enqueue_script( 'wpmtst-form' );

		if ( wpmtst_using_form_validation_script() ) {
			if ( wp_script_is( 'wpmtst-validation-lang', 'registered' ) ) {
				wp_enqueue_script( 'wpmtst-validation-lang' );
			}

			wp_localize_script( 'wpmtst-form-validation', 'form_ajax_object', array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'ajaxSubmit' => isset( $atts['form_ajax'] ) && $atts['form_ajax'] ? 1 : 0,
			) );
			wp_enqueue_script( 'wpmtst-form-validation' );
		}

		if ( $form_options['honeypot_before'] ) {
			add_action( 'wp_footer', 'wpmtst_honeypot_before_script' );
		}

		if ( $form_options['honeypot_after'] ) {
			add_action( 'wp_footer', 'wpmtst_honeypot_after_script' );
		}
	}

	private static function after_form_success() {
		$form_options = get_option( 'wpmtst_form_options' );
		wp_localize_script( 'wpmtst-form-success', 'formSuccess', array(
			'scrollTop' => $form_options['scrolltop_success'],
			'offset'    => $form_options['scrolltop_success_offset']
		) );
		wp_enqueue_script( 'wpmtst-form-success' );
	}

	/**
	 * Set up the slideshow.
	 *
	 * @since 2.3 As separate function.
	 * @param array $atts
	 */
	private static function after_slideshow( $atts = array() ) {

		// Script
		if ( ! wp_script_is( 'wpmtst-slider' ) ) {
			wp_enqueue_script( 'wpmtst-slider' );
		}

		// Populate variable
		$sig  = self::slideshow_signature( $atts );
		$args = self::slideshow_args( $atts );
		wp_localize_script( 'wpmtst-slider', $sig, $args );
	}

	/**
	 * Create unique slideshow signature.
	 *
	 * @since 2.7.0
	 * @private
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	private static function slideshow_signature( $atts ) {
		return 'strong_slider_id_' . $atts['view'];
	}

	/**
	 * Create unique pager signature.
	 *
	 * @since 2.13.2
	 * @private
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	private static function pager_signature( $atts ) {
		return 'strong_pager_id_' . $atts['view'];
	}

	/**
	 * Return slideshow signature.
	 *
	 * @since 2.7.0
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function get_slideshow_signature( $atts ) {
		return self::slideshow_signature( $atts );
	}

	/**
	 * Return pager signature.
	 *
	 * @since 2.13.2
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function get_pager_signature( $atts ) {
		return self::pager_signature( $atts );
	}

	/**
	 * Assemble slideshow settings.
	 *
	 * @since 2.7.0
	 * @private
	 * @param $atts
	 *
	 * @return array
	 */
	private static function slideshow_args( $atts ) {

		$view_options = get_option( 'wpmtst_view_options' );

		$args = array(
			'mode'                => $atts['slideshow_settings']['effect'],
			'speed'               => $atts['slideshow_settings']['speed'] * 1000,
			'pause'               => $atts['slideshow_settings']['pause'] * 1000,
			'autoHover'           => $atts['slideshow_settings']['auto_hover'] ? 1 : 0,
			'autoStart'           => $atts['slideshow_settings']['auto_start'] ? 1 : 0,
			'stopAutoOnClick'     => $atts['slideshow_settings']['stop_auto_on_click'] ? 1 : 0,
			'adaptiveHeight'      => $atts['slideshow_settings']['adapt_height'] ? 1 : 0,
			'adaptiveHeightSpeed' => $atts['slideshow_settings']['adapt_height_speed'] * 1000,
			'controls'            => 0,
			'autoControls'        => 0,
			'pager'               => 0
		);
		if ( ! $atts['slideshow_settings']['adapt_height'] ) {
			$args['stretch'] = $atts['slideshow_settings']['stretch'] ? 1 : 0;
		}

		// Controls
		$options = $view_options['slideshow_nav_method']['controls'];
		$control_setting = $atts['slideshow_settings']['controls_type'];
		if ( ! $control_setting ) {
			$control_setting = 'none';
		}
		if ( isset( $options[ $control_setting ] ) && isset( $options[ $control_setting ]['args'] ) ) {
			$args = array_merge( $args, $options[ $control_setting ]['args'] );
		}

		if ( 'none' != $control_setting ) {
			$options = $view_options['slideshow_nav_style']['controls'];
			$setting = $atts['slideshow_settings']['controls_style'];
			if ( ! $setting ) {
				$setting = 'none';
			}
			if ( isset( $options[ $setting ] ) && isset( $options[ $setting ]['args'] ) ) {
				$args = array_merge( $args, $options[ $setting ]['args'] );
			}
		}

		// Pager
		$options = $view_options['slideshow_nav_method']['pager'];
		$pager_setting = $atts['slideshow_settings']['pager_type'];
		if ( ! $pager_setting ) {
			$pager_setting = 'none';
		}
		if ( isset( $options[ $pager_setting ] ) && isset( $options[ $pager_setting ]['args'] ) ) {
			$args = array_merge( $args, $options[ $pager_setting ]['args'] );
		}

		if ( 'none' != $pager_setting ) {
			$options = $view_options['slideshow_nav_style']['pager'];
			$setting = $atts['slideshow_settings']['pager_style'];
			if ( ! $setting ) {
				$setting = 'none';
			}
			if ( isset( $options[ $setting ] ) && isset( $options[ $setting ]['args'] ) ) {
				$args = array_merge( $args, $options[ $setting ]['args'] );
			}
		}

		return $args;
	}

	/**
	 * Return slideshow settings.
	 *
	 * @since 2.7.0
	 * @param $atts
	 *
	 * @return array
	 */
	public function get_slideshow_args( $atts ) {
		return self::slideshow_args( $atts );
	}

	/**
	 * Assemble pager settings.
	 *
	 * @since 2.13.2
	 * @private
	 * @param $atts
	 *
	 * @return array
	 */
	private static function pager_args( $atts ) {
		$options = get_option( 'wpmtst_options' );

		$nav = $atts['nav'];
		if ( false !== strpos( $atts['nav'], 'before' ) && false !== strpos( $atts['nav'], 'after' ) ) {
			$nav = 'both';
		}

		$pager = array(
			'pageSize'      => $atts['per_page'],
			'currentPage'   => 1,
			'pagerLocation' => $nav,
			'scrollTop'     => $options['scrolltop'],
			'offset'        => apply_filters( 'wpmtst_pagination_scroll_offset', $options['scrolltop_offset'] ),
		);

		return $pager;
	}

	/**
	 * Return pager settings.
	 *
	 * @since 2.13.2
	 * @param $atts
	 *
	 * @return array
	 */
	public function get_pager_args( $atts ) {
		return self::pager_args( $atts );
	}

	/**
	 * Set up the pagination.
	 *
	 * @since 2.5.2
	 * @param array $atts
	 */
	private static function after_pagination( $atts = array() ) {
		if ( 'simple' == $atts['pagination_type'] ) {
			wp_enqueue_script( 'wpmtst-pager-script' );
			wp_localize_script( 'wpmtst-pager-script', self::pager_signature( $atts ), self::pager_args( $atts ) );
		}
	}

	/**
	 * Find a template's associated stylesheet.
	 *
	 * @since 1.23.0
	 *
	 * @param array $atts      Our View attributes
	 * @param bool  $enqueue   True = enqueue the stylesheet, @since 2.3
	 * @param bool  $deferred  True = add to list for wp_enqueue_style, False = enqueue now (in footer)
	 *
	 * @return bool|string
	 */
	private static function find_stylesheet( $atts, $enqueue = true, $deferred = true ) {
		// In case of deactivated widgets still referencing deleted Views
		if ( !isset( $atts['template'] ) || !$atts['template'] )
			return false;

		$plugin_version = get_option( 'wpmtst_plugin_version' );
		$handle = false;
		$stylesheet = self::$instance->templates->get_template_attr( $atts, 'stylesheet', false );
		if ( $stylesheet ) {
			$handle = 'testimonials-' . str_replace( ':', '-', $atts['template'] );
			wp_register_style( $handle, $stylesheet, array(), $plugin_version );
			if ( $enqueue ) {
				if ( $deferred )
					self::add_style( $handle );
				else
					wp_enqueue_style( $handle );
			}
		}

		return $handle;
	}

	/**
	 * Preprocess a view to gather styles, scripts and script vars.
	 *
	 * @param $view
	 * @param bool $handle
	 * @since 1.25.0
	 * @since 2.5.0  Move some processing to Strong_View class.
	 *
	 * @todo Move add_script and add_style to Strong_View class.
	 *
	 * @return string
	 */
	private static function preprocess( $view, $handle = false ) {
		$options = get_option( 'wpmtst_options' );

		// subset of all shortcode atts
		$atts = shortcode_atts(
			self::get_view_defaults(),
			$view['atts']
		);

		$new_view = new Strong_View( $atts );
		$new_view->process();

		/**
		 * Slideshow
		 */
		if ( $atts['slideshow'] ) {

			// TODO Is this still beneficial?
			self::add_script( 'wpmtst-slider', 'later' );

		}
		else {

			/**
			 * Stars
			 */
			if ( isset( $atts['client_section'] ) ) {
				foreach ( $atts['client_section'] as $field ) {
					if ( 'rating' == $field['type'] ) {
						self::add_style( 'wpmtst-rating-display' );
					}
				}
			}

			/**
			 * Pagination
			 */
			if ( $atts['per_page']
				&& $new_view->query->post_count > $atts['per_page']
				&& 'simple' == $atts['pagination_type']
				&& apply_filters( 'wpmtst_use_default_pagination', true, $atts ) )
			{
				self::add_script( 'wpmtst-pager-script' );
				self::add_script_var( 'wpmtst-pager-script', self::pager_signature( $atts ), self::pager_args( $atts ) );
			}

			/**
			 * Layouts
			 */
			if ( 'masonry' == $atts['layout'] ) {
				self::add_script( 'wpmtst-masonry-script' );
				self::add_style( 'wpmtst-masonry-style' );
			}
			elseif ( 'columns' == $atts['layout'] ) {
				self::add_style( 'wpmtst-columns-style' );
			}
			elseif ( 'grid' == $atts['layout'] ) {
				self::add_script( 'wpmtst-grid-script' );
				self::add_style( 'wpmtst-grid-style' );
			}

		}

		/**
		 * Load template's script and/or dependencies.
		 *
		 * @since 1.25.0
		 */
		$deps = self::$instance->templates->get_template_attr( $atts, 'deps', false );
		$deps_array = $deps ? explode( ',', str_replace( ' ', '', $deps ) ) : array();

		$script = self::$instance->templates->get_template_attr( $atts, 'script', false );
		if ( $script ) {
			$handle = 'testimonials-' . str_replace( ':', '-', $atts['template'] );
			wp_register_script( $handle, $script, $deps_array );
			self::add_script( $handle );
		}
		else {
			foreach ( $deps_array as $handle ) {
				self::add_script( $handle );
			}
		}

		/**
		 * Load template's extra stylesheets.
		 *
		 * @since 2.11.12
		 */
		$styles = self::$instance->templates->get_template_attr( $atts, 'styles', false );
		if ( $styles ) {
			$styles_array = explode( ',', str_replace( ' ', '', $styles ) );
			foreach ( $styles_array as $handle ) {
				self::add_style( $handle );
			}
		}

		self::custom_background( $atts['view'], $atts['background'], $handle );
	}

	/**
	 * Custom CSS for Views only.
	 *
	 * @param $view int    The view id, not the view object.
	 * @param $background
	 * @param $handle
	 */
	private static function custom_background( $view = null, $background, $handle = 'wpmtst-custom-style' ) {
		if ( ! $view || ! isset( $background['type'] ) ) return;

		$c1 = '';
		$c2 = '';

		switch ( $background['type'] ) {
			case 'preset':
				$preset = WPMST()->get_background_presets( $background['preset'] );
				$c1 = $preset['color'];
				if ( isset( $preset['color2'] ) ) {
					$c2 = $preset['color2'];
				}
				break;
			case 'gradient':
				$c1 = $background['gradient1'];
				$c2 = $background['gradient2'];
				break;
			case 'single':
				$c1 = $background['color'];
				break;
			default:
		}

		if ( !wp_style_is( $handle ) ) {
			wp_enqueue_style( $handle );
		}

		// Includes special handling for Large Widget template.
		// TODO Add option to include background for all templates.
		if ( $c1 && $c2 ) {

			$gradient = self::gradient_rules( $c1, $c2 );
			wp_add_inline_style( $handle, ".strong-view-id-$view .testimonial-inner { $gradient }" );
			if ( 'large-widget:widget' == WPMST()->atts( 'template' ) ) {
				wp_add_inline_style( $handle, ".strong-view-id-$view .readmore-page { background: $c2 }" );
			}

		} elseif ( $c1 ) {

			wp_add_inline_style( $handle, ".strong-view-id-$view .testimonial-inner { background: $c1; }" );
			if ( 'large-widget:widget' == WPMST()->atts( 'template' ) ) {
				wp_add_inline_style( $handle, ".strong-view-id-$view .readmore-page { background: $c1 }" );
			}

		}
	}

	private static function gradient_rules( $c1, $c2 ) {
		return "background: {$c1};
		background: -moz-linear-gradient(top, {$c1} 0%, {$c2} 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, {$c1}), color-stop(100%, {$c2}));
		background: -webkit-linear-gradient(top,  {$c1} 0%, {$c2} 100%);
		background: -o-linear-gradient(top, {$c1} 0%, {$c2} 100%);
		background: -ms-linear-gradient(top, {$c1} 0%, {$c2} 100%);
		background: linear-gradient(to bottom, {$c1} 0%, {$c2} 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='{$c1}', endColorstr='{$c2}', GradientType=0);";
	}

	/**
	 * Preprocess a form.
	 *
	 * @param      $view
	 * @param bool $handle
	 */
	private static function preprocess_form( $view, $handle = false ) {
		// subset of all shortcode atts
		extract( shortcode_atts(
			self::get_view_defaults(),
			$view['atts']
		) );

		self::add_style( 'wpmtst-rating-form' );
	}

	/**
	 * Process a form.
	 * Moved to `init` hook for strong_testimonials_view() template function.
	 *
	 * @since 2.3.0
	 */
	public static function process_form() {
		if ( isset( $_POST['wpmtst_form_nonce'] ) ) {
			require_once WPMTST_INC . 'form-handler-functions.php';
			$success = wpmtst_form_handler();
			if ( $success ) {
				$goback = add_query_arg( 'success', 1, wp_get_referer() );
				wp_redirect( $goback );
				exit;
			}
		}
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
		$this->log( $error );
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
	public static function parse_view( $out, $pairs, $atts ) {
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

		//if ( 'entire' == $view_data['content'] ) {
		//	unset( $view_data['length'] );
		//}

		if ( 'slideshow' == $view_data['mode'] ) {
			unset( $view_data['id'] );
			// $view_data['no_pause'] = ! $view_data['pause'];
		} else {
			unset( $view_data['show_for'] );
			unset( $view_data['effect_for'] );
			unset( $view_data['no_pause'] );
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
	 * Process the form.
	 * @todo Move to form object.
	 */
	public function set_form_values( $form_values ) {
		self::$form_values = $form_values;
	}

	public function get_form_values() {
		return self::$form_values;
	}

	public function set_form_errors( $form_errors ) {
		self::$form_errors = $form_errors;
	}

	public function get_form_errors() {
		return self::$form_errors;
	}

	/**
	 * Show version number in <head> section.
	 *
	 * For troubleshooting only.
	 *
	 * @since 1.12.0
	 */
	function show_version_info() {
		global $wp_version;
		$plugin_info = $this->get_plugin_info();
		$comment = array(
			'WordPress ' . $wp_version,
			$plugin_info['name'] . ' ' . $plugin_info['version'],
		);

		echo "\n" . '<!-- versions: ' . implode( ' | ', $comment ) . ' -->' . "\n";
	}

	/**
	 * Return plugin info.
	 *
	 * @return array
	 */
	public function get_plugin_info() {
		return get_file_data( __FILE__, array( 'name' => 'Plugin Name', 'version' => 'Version' ) );
	}

	/**
	 * Generic logging function.
	 *
	 * @param string $log
	 * @param bool   $label
	 * @param string $filename
	 */
	public function log( $log = '', $label = false, $filename = 'strong-debug.log' )  {

		if ( ! $log ) return;

		$entry = '[' . date('Y-m-d H:i:s') . '] ';

		if ( $label )
			$entry .= strtoupper( $label ) . ' = ';

		if ( is_array( $log ) || is_object( $log ) )
			$entry .= print_r( $log, true );
		else
			$entry .= $log . PHP_EOL;

		$filepath = WPMTST_DIR . $filename;

		error_log( $entry, 3, $filepath );

	}

}

endif; // class_exists check

function WPMST() {
	return Strong_Testimonials::instance();
}

// Get plugin running
WPMST();
