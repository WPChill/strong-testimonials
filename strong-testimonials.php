<?php
/**
 * Plugin Name: Strong Testimonials
 * Plugin URI: http://www.wpmission.com/strong-testimonials/
 * Description: Collect and display testimonials with a plugin that offers strong features and strong support.
 * Author: Chris Dillon
 * Version: 1.22
 * Forked From: GC Testimonials version 1.3.2 by Erin Garscadden
 * Author URI: http://www.wpmission.com/contact
 * Text Domain: strong-testimonials
 * Domain Path: /languages
 * Requires: 3.5 or higher
 * License: GPLv3 or later
 *
 * Copyright 2014-2015  Chris Dillon  chris@wpmission.com
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
 * @since 1.15.0
 */
final class Strong_Testimonials {

	private static $instance;
	
	private static $db_version = '1.0';
	public static $view_count = 0;
	public static $styles = array( 'normal' => array(), 'later' => array() );
	public static $scripts = array( 'normal' => array(), 'later' => array() );
	public static $css = array();
	public static $script_vars;
	public static $shortcode;
	public static $shortcode_lb;
	public static $shortcode2;
	public static $shortcode2_lb;
	public static $view_defaults = array();
	public static $strong_atts = array();
	public static $form_values;
	public static $form_errors;

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
			
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();
			self::$instance->set_shortcodes();
			self::$instance->set_view_defaults();
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
		
		if ( ! defined( 'WPMTST_URL' ) )
			define( 'WPMTST_URL', plugin_dir_url( __FILE__ ) );

		if ( ! defined( 'WPMTST_DIR' ) )
			define( 'WPMTST_DIR', plugin_dir_path( __FILE__ ) );

		if ( ! defined( 'WPMTST_INC' ) )
			define( 'WPMTST_INC', plugin_dir_path( __FILE__ ) . 'includes/' );
		
		if ( ! defined( 'WPMTST_DEF_TPL' ) )
			define( 'WPMTST_DEF_TPL', plugin_dir_path( __FILE__ ) . 'templates/default/' );
		
		if ( ! defined( 'WPMTST_DEF_TPL_URI' ) )
			define( 'WPMTST_DEF_TPL_URI', plugin_dir_url( __FILE__ ) . 'templates/default/' );
		
		if ( ! defined( 'WPMTST_TPL' ) )
			define( 'WPMTST_TPL', plugin_dir_path( __FILE__ ) . 'templates/' );
		
		if ( ! defined( 'WPMTST_TPL_URI' ) )
			define( 'WPMTST_TPL_URI', plugin_dir_url( __FILE__ ) . 'templates/' );
		
	}


	/**
	 * Include required files
	 *
	 * @access private
	 * @since 1.21.0
	 * @return void
	 */
	private function includes() {

		require_once WPMTST_INC . 'l10n.php';
		require_once WPMTST_INC . 'post-types.php';
		require_once WPMTST_INC . 'setup.php';
		require_once WPMTST_INC . 'functions.php';
		require_once WPMTST_INC . 'child-shortcodes.php';
		require_once WPMTST_INC . 'shims.php';
		require_once WPMTST_INC . 'widget.php';
		require_once WPMTST_INC . 'widget2.php';
		
		if ( is_admin() ) {
			
			require_once WPMTST_INC . 'class-strong-testimonials-list-table.php';
			require_once WPMTST_INC . 'class-strong-views-list-table.php';
			require_once WPMTST_INC . 'admin/admin.php';
			require_once WPMTST_INC . 'admin/custom-fields.php';
			require_once WPMTST_INC . 'admin/guide/guide.php';
			require_once WPMTST_INC . 'admin/install.php';
			require_once WPMTST_INC . 'admin/pointers.php';
			require_once WPMTST_INC . 'admin/settings.php';
			require_once WPMTST_INC . 'admin/upgrade.php';
			require_once WPMTST_INC . 'admin/views.php';
			require_once WPMTST_INC . 'admin/welcome.php';
		
		} else {
			
			require_once WPMTST_INC . 'scripts.php';
			require_once WPMTST_INC . 'shortcodes.php';
			require_once WPMTST_INC . 'shortcode-form.php';
			require_once WPMTST_INC . 'shortcode-strong.php';
			require_once WPMTST_INC . 'captcha.php';
			require_once WPMTST_INC . 'template-functions.php';
			
		}

	}

	/**
	 * Text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'strong-testimonials', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Action hooks.
	 */
	private function add_actions() {

		/**
		 * Actions on 'wp' hook allow us to properly enqueue styles and scripts.
		 */
		
		// Preprocess the post content for the our shortcodes.
		add_action( 'wp', array( $this, 'find_views' ) );
		add_action( 'wp', array( $this, 'find_views_in_postmeta' ) );
		add_action( 'wp', array( $this, 'find_views_in_postexcerpt' ) );

		// Page Builder by Site Origin
		add_action( 'wp', array( $this, 'find_pagebuilder_widgets' ) );

		// Black Studio TinyMCE Widget
		add_action( 'wp', array( $this, 'find_blackstudio_widgets' ) );

		// Preprocess the page for widgets.
		add_action( 'wp', array( $this, 'find_widgets' ) );
		add_action( 'wp', array( $this, 'find_view_widgets' ) );

		// Preprocess the post content for the original shortcodes.
		add_action( 'wp', array( $this, 'find_original_shortcodes' ) );

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
		 * Filter page templates to exclude testimonial templates.
		 */
		add_action( 'load-post.php', array( $this, 'add_page_templates_filter' ) );
		
		/**
		 * Flush rewrite rules after theme switch.
		 * 
		 * In case the previous or current theme skips this and it has a "testimonial" post type.
		 * 
		 * @since 1.21.0
		 */
		add_action( 'after_switch_theme', 'flush_rewrite_rules' );

		/**
		 * @since 1.14.1
		 */
		add_filter( 'no_texturize_shortcodes', array( $this, 'no_texturize_shortcodes' ) );

		/**
		 * Print our custom CSS.
		 */
		add_action( 'wp_head', array( $this, 'custom_css' ) );

	}

	/**
	 * @since 1.21.0
	 */
	public function custom_css() {
		echo "<style>\n";
		foreach ( self::$css as $line ) {
			echo $line . "\n";
		}
		echo "</style>\n";
	}

	/**
	 * @since 1.21.0
	 */
	public function add_page_templates_filter() {
		add_filter( 'theme_page_templates', array( $this, 'page_templates_filter' ), 10, 3 );
	}

	/**
	 * Remove testimonial templates from Template dropdown in Page Attributes metabox.
	 * 
	 * @param $page_templates
	 * @param $theme_object
	 * @param $post
	 *
	 * @return mixed
	 */
	public function page_templates_filter( $page_templates, $theme_object, $post ) {
		foreach ( $page_templates as $file_name => $template_name ) {
			if ( 'testimonials.php' == $file_name 
			        || 'testimonials-' == substr( $file_name, 0, 13 ) 
			        || 'testimonial-form' == substr( $file_name, 0, 16 ) ) {
				unset( $page_templates[ $file_name ] );
			}
		}
		return $page_templates;
	}

	/**
	 * Set shortcode.
	 */
	public function set_shortcodes() {
		$options = get_option( 'wpmtst_options' );
		self::$shortcode     = isset( $options['shortcode'] ) ? $options['shortcode'] : 'strong';
		self::$shortcode_lb  = '[' . self::$shortcode;
		self::$shortcode2    = 'testimonial_view';
		self::$shortcode2_lb = '[' . self::$shortcode2;
	}

	/**
	 * Get shortcodes.
	 */
	public function get_shortcode() {
		return self::$shortcode;
	}
	
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
		$shortcodes[] = self::$shortcode;
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
	 * Setter for the shortcode defaults.
	 * 
	 * TODO Provide a filter.
	 * TODO Consolidate with wpmtst_get_default_view()
	 * 
	 * @param array $defaults
	 */
	private static function set_view_defaults( $defaults = array() ) {
		$defaults = array_merge( 
			array(
				'background'       => '',
				'category'         => '',
				'class'            => '',
				'client_section'   => null,
				'count'            => -1,
				'display'          => '',
				'effect_for'       => '1.5',
				'excerpt'          => '',
				'form'             => '',
				'gravatar'         => 'no',
				'id'               => '',
				'length'           => '',
				'lightbox'         => '',
				'menu_order'       => '',  // @since 1.16.0
				'mode'             => '',
				'more_page'        => '',  // @since 1.20.0
				'more_page_on'     => '',  // @since 1.20.0
				'more_post'        => '',
				'more_text'        => _x( 'Read more', 'link', 'strong-testimonials' ),
				'nav'              => 'after',
				'newest'           => '',
				'no_pause'         => 0,  // must be zero not boolean or string!
				'oldest'           => '',
				'per_page'         => '',
				'random'           => '',
				'read_more'        => '',
				'show_for'         => '8',
				'slideshow'        => '',
				'template'         => '',
				'thumbnail'        => '',
				'thumbnail_size'   => 'thumbnail',
				'thumbnail_height' => null,
				'thumbnail_width'  => null,
				'title'            => '',
				'view'             => '',
			),
			$defaults
		);
		self::$view_defaults      = $defaults;
	}

	/**
	 * Set atts.
	 * 
	 * @param $atts
	 */
	public function set_atts( $atts ) {
		self::$strong_atts = $atts;
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
			return self::$strong_atts;
		
		// return some
		if ( is_array( $keys ) ) {
			$found = array();
			foreach ( $keys as $key ) {
				if ( isset( self::$strong_atts[ $key ] ) ) {
					$found[ $key ] = self::$strong_atts[ $key ];
				}
			}
			return $found;
		}
		
		// return one
		if ( isset( self::$strong_atts[ $keys ] ) )
			return self::$strong_atts[ $keys ];
		
		// return none
		return false;
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
	
	private static function add_css( $line ) {
		self::$css[] = $line;
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
				$success = wp_localize_script( $var['script_name'], $var['var_name'], $var['var'] );
			}
		}
	}

	private static function check_content( $content ) {
		if ( false === strpos( $content, self::$shortcode_lb ) && false === strpos( $content, self::$shortcode2_lb ) )
			return false;
		
		return true;
	}

	/**
	 * Build list of all shortcode views on a page.
	 *
	 * @access public
	 */
	public static function find_views() {

		if ( is_admin() )
			return false;

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

		if ( is_admin() )
			return false;

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

		if ( is_admin() )
			return false;

		global $post;
		if ( empty( $post ) )
			return false;

		if ( ! self::check_content( $post->post_excerpt ) )
			return false;

		self::process_content( $post->post_excerpt );

	}

	/**
	 * Build list of all shortcode views in Black Studio TinyMCE Widget.
	 *
	 * @access public
	 * @since 1.16.14
	 */
	public static function find_blackstudio_widgets() {

		if ( is_admin() )
			return false;

		global $post;
		if ( empty( $post ) )
			return false;

		$widget_content = get_option( 'widget_black-studio-tinymce' );
		if ( ! $widget_content )
			return false;

		$widget_content_serialized = maybe_serialize( $widget_content );
		if ( ! self::check_content( $widget_content_serialized ) )
			return false;

		self::process_content( $widget_content_serialized );

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

			if ( self::$shortcode === $shortcode[2] ) {

				/**
				 * Adding html_entity_decode.
				 * @since 1.16.13
				 */
				// Retrieve all attributes from the shortcode.
				$original_atts = shortcode_parse_atts( html_entity_decode( $shortcode[3] ) );

				// Turn empty atts into switches.
				$atts = normalize_empty_atts( $original_atts );

				// Build the shortcode signature.
				$att_string = serialize( $original_atts );

				self::find_single_view( self::$view_count++, $atts, $att_string );
				
			} elseif ( self::$shortcode2 === $shortcode[2]) {
				
				/**
				 * Adding html_entity_decode.
				 * @since 1.16.13
				 */
				// Retrieve all attributes from the shortcode.
				$original_atts = shortcode_parse_atts( html_entity_decode( $shortcode[3] ) );
				
				// Incorporate attributes from the View and defaults.
				$parsed_atts = self::parse_view( $original_atts, self::get_view_defaults(), $original_atts );
				
				// Turn empty atts into switches.
				$atts = normalize_empty_atts( $parsed_atts );

				// Build the shortcode signature.
				$att_string = serialize( $original_atts );

				self::find_single_view( self::$view_count++, $atts, $att_string );

			} else {

				/**
				 * Recursively process nested shortcodes.
				 *
				 * Handles:
				 * Elegant Themes page builder (Divi theme).
				 *
				 * @since 1.15.5
				 */
				self::process_content( $shortcode[5] );

			}

		}
	}

	/**
	 * Process a single [strong] or [testimonial_view] shortcode.
	 *
	 * @since 1.15.4
	 */
	private static function find_single_view( $counter, $atts, $att_string ) {
		$options         = get_option( 'wpmtst_options' );
		$preprocess      = false;
		$preprocess_form = false;

		/**
		 * Modes
		 */
		if ( isset( $atts['read_more'] ) ) {
		
			/**
			 * "Read more"
			 * 
			 * TODO remove in 2.0
			 */
			$view = array( 'mode' => 'read_more', 'atts' => $atts );
		
		} elseif ( isset( $atts['form'] ) ) {
			
			/**
			 * Form
			 */
			$form_options = get_option( 'wpmtst_form_options' );
			$view = array( 'mode' => 'form', 'atts' => $atts );
			$preprocess_form = true;

			/**
			 * If this is a view, look for accompanying stylesheet.
			 * If this is a configured shortcode, check the option for loading its stylesheet.
			 */
			//TODO DRY (below)
			if ( isset( $atts['view'] ) && $atts['view'] ) {

				// Use default template if unspecified.
				if ( ! isset( $atts['template'] ) || ! $atts['template'] ) {
					$default_template = wpmtst_find_form_template( '', $atts['view'] );
					$atts['template'] = str_replace( WPMTST_TPL, '', $default_template );
				}

				$stylesheet = str_replace( '.php', '.css', $atts['template'] );
				$stylesheet_path = WPMTST_TPL . $stylesheet;
				$stylesheet_url  = WPMTST_TPL_URI . $stylesheet;

				if ( file_exists( $stylesheet_path ) ) {
					$handle = str_replace( array( '/', '.php' ), array( '-', '-template' ), $atts['template'] ) . '-style';
					wp_register_style( $handle, $stylesheet_url );
					self::add_style( $handle );
				}

			} else {
				
				// [strong]
				// load original stylesheet
				if ( $options['load_form_style'] ) {
					self::add_style( 'wpmtst-form-style' );
				}

				// RTL
				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}
				
			}

			if ( apply_filters( 'wpmtst_field_required_tag', true ) && apply_filters( 'wpmtst_form_validation_script', true ) ) {
				self::add_script( 'wpmtst-form-script' );
				// TODO localize too?
			}

			if ( $form_options['honeypot_before'] ) {
				add_action( 'wp_footer', 'wpmtst_honeypot_before_script' );
			}

			if ( $form_options['honeypot_after'] ) {
				add_action( 'wp_footer', 'wpmtst_honeypot_after_script' );
			}

		} elseif ( isset( $atts['slideshow'] ) ) {
			
			/**
			 * Slideshow
			 */
			$view = array( 'mode' => 'slideshow', 'atts' => $atts );
			$preprocess = true;

			/**
			 * If this is a view, look for accompanying stylesheet.
			 * If this is a configured shortcode, check the option for loading its stylesheet.
			 */

			//TODO DRY (below)
			if ( isset( $atts['view'] ) && $atts['view'] ) {

				// Use default template if unspecified.
				if ( ! isset( $atts['template'] ) || ! $atts['template'] ) {
					$default_template = wpmtst_find_template( '', $atts['view'] );
					$atts['template'] = str_replace( WPMTST_TPL, '', $default_template );
				}

				$stylesheet = str_replace( '.php', '.css', $atts['template'] );
				$stylesheet_path = WPMTST_TPL . $stylesheet;
				$stylesheet_url = WPMTST_TPL_URI . $stylesheet;

				if ( file_exists( $stylesheet_path ) ) {
					$handle = str_replace( array( '/', '.php' ), array( '-', '-template' ), $atts['template'] ) . '-style';
					wp_register_style( $handle, $stylesheet_url );
					self::add_style( $handle );
				}

			} else {

				// [strong]
				// Load original stylesheet.
				if ( $options['load_page_style'] ) {
					self::add_style( 'wpmtst-style' );
				}

				// RTL
				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}
			}
		
		} else {

			/**
			 * Display (default)
			 */
			$view = array( 'mode' => 'display', 'atts' => $atts );
			$preprocess = true;

			/**
			 * If this is a view, look for accompanying stylesheet.
			 * If this is a configured shortcode, check the option for loading its stylesheet.
			 */

			// TODO DRY (above)
			if ( isset( $atts['view'] ) && $atts['view'] ) {

				// Use default template if unspecified.
				if ( ! isset( $atts['template'] ) || ! $atts['template'] ) {
					$default_template = wpmtst_find_template( '', $atts['view'] );
					$atts['template'] = str_replace( WPMTST_TPL, '', $default_template );
				}

				// Load template stylesheet
				$stylesheet = str_replace( '.php', '.css', $atts['template'] );
				$stylesheet_path = WPMTST_TPL . $stylesheet;
				$stylesheet_url = WPMTST_TPL_URI . $stylesheet;

				if ( file_exists( $stylesheet_path ) ) {
					$handle = str_replace( array( '/', '.php' ), array( '-', '-template' ), $atts['template'] ) . '-style';
					wp_register_style( $handle, $stylesheet_url );
					self::add_style( $handle );
				}
				
			} else {

				// [strong]
				// Load original stylesheet.
				if ( $options['load_page_style'] ) {
					self::add_style( 'wpmtst-style' );
				}

				// RTL
				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}

			}
			
		}

		// Process attributes to check for required styles & scripts.
		if ( $preprocess ) {
			self::pre_process( $view, $counter, $atts, $att_string );
		} elseif ( $preprocess_form ) {
			self::pre_process_form( $view, $counter, $atts, $att_string );
		}

		return $view;
	}

	/**
	 * Preprocess a view to gather styles, scripts and script vars.
	 *
	 * @param $view
	 * @param $counter
	 * @param $atts
	 * @param $att_string
	 *
	 * @return string
	 */
	private static function pre_process( $view, $counter, $atts, $att_string ) {
		// subset of all shortcode atts
		extract( shortcode_atts(
			self::get_view_defaults(),
			$view['atts']
		) );
		
		// extract comma-separated values
		$categories = explode( ',', $category );
		$ids        = explode( ',', $id );

		// assemble query arguments
		$args = array(
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => $count,
			'orderby'        => 'post_date',
			'post_status'    => 'publish',
		);

		// id overrides category
		if ( $id ) {
			$args['post__in'] = $ids;
		} elseif ( $category ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'wpm-testimonial-category',
					'field'    => 'id',
					'terms'    => $categories
				)
			);
		}

		$query      = new WP_Query( $args );
		$post_count = $query->post_count;
		wp_reset_postdata();
	
		if ( $slideshow ) {
	
			// Populate variable for Cycle script.
			$args = array(
				'fx'      => 'fade',
				'speed'   => $effect_for * 1000,
				'timeout' => $show_for * 1000,
				'pause'   => $no_pause ? 0 : 1
			);
			self::add_script( 'wpmtst-slider', 'later' );
			self::add_script_var( 'wpmtst-slider', 'strong_cycle_' . hash( 'md5', serialize( $args ) ), $args );
			/**
			 * Example result:
			 * var strong_cycle_b17e46f93ef619819cdfe5e26b66a3e9 = {"fx":"fade","speed":"1000","timeout":"5000","pause":"1"};
			 */
	
		} else {
			
			if ( $per_page && $post_count > $per_page ) {
	
				// Paginated.
				// Populate variable for QuickPager script.
				if ( false !== strpos( $nav, 'before' ) && false !== strpos( $nav, 'after' ) ) {
					$nav = 'both';
				}
	
				$pager = array(
					'id'            => '.strong-paginated',
					'pageSize'      => $per_page,
					'currentPage'   => 1,
					'pagerLocation' => $nav
				);
				self::add_script( 'wpmtst-pager-script' );
				self::add_script_var( 'wpmtst-pager-script', 'pagerVar', $pager );
	
			}
			
		}

		/**
		 * Custom CSS for Views only.
		 */
		if ( isset( $atts['view'] ) && $atts['view'] ) {
			$template_class = $template ? '.' . strtok( $template, '/' ) : '';
			if ( $background ) {
				self::add_css( ".strong-view{$template_class} .testimonial-inner { background: {$background}; }" );
			}
		}

	}
	
	/**
	 * Preprocess a form.
	 *
	 * @param $view
	 * @param $counter
	 * @param $atts
	 * @param $att_string
	 *
	 * @return string
	 */
	private static function pre_process_form( $view, $counter, $atts, $att_string ) {
		// subset of all shortcode atts
		extract( shortcode_atts(
			self::get_view_defaults(),
			$view['atts']
		) );

		// validate form entries here
		if ( isset( $_POST['wpmtst_form_nonce'] ) ) {
			require_once WPMTST_INC . 'form-handler-functions.php';
			$success = wpmtst_form_handler();
			if ( $success ) {
				$goback = add_query_arg( 'success', 1, wp_get_referer() );
				wp_redirect( $goback );
				exit;
			}
		}
		
		/**
		 * Custom CSS for View Forms only.
		 */
		if ( isset( $atts['view'] ) && $atts['view'] ) {
			$template_class = $template ? '.' . strtok( $template, '/' ) : '';
			if ( $background ) {
				self::add_css( ".strong-form{$template_class} { background: {$background}; }" );
			}
		}
	}

	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For standard widgets NOT in [Page Builder by SiteOrigin] panels.
	 *
	 * @access public
	 */
	public static function find_view_widgets() {
		if ( is_admin() )
			return false;

		// Get all widgets
		$all_widgets = get_option( 'sidebars_widgets' );
		if ( ! $all_widgets )
			return false;

		// Get active strong widgets
		$strong_widgets = get_option( 'widget_strong-testimonials-view-widget' );

		foreach ( $all_widgets as $sidebar => $widgets ) {

			// active widget areas only
			if ( ! $widgets || 'wp_inactive_widgets' == $sidebar || 'array_version' == $sidebar )
				continue;

			foreach ( $widgets as $key => $widget_name ) {

				// Is our widget active?
				if ( 0 === strpos( $widget_name, 'strong-testimonials' ) ) {

					if ( $strong_widgets ) {

						$name_parts = explode( '-', $widget_name );
						$id         = array_pop( $name_parts );

						if ( isset( $strong_widgets[ $id ] ) ) {

							$widget = $strong_widgets[ $id ];

							// Incorporate attributes from the View and defaults, just like the shortcode filter.
							if ( isset( $widget['view'] ) && $widget['view'] ) {
								$atts        = array( 'view' => $widget['view'] );
								$parsed_atts = self::parse_view( $atts, self::get_view_defaults(), $atts );

								// Build the shortcode signature.
								$att_string = serialize( $atts );

								// Turn empty atts into switches.
								$atts = normalize_empty_atts( $parsed_atts );

								self::find_single_view( self::$view_count++, $atts, $att_string );
							}
							
						}

					}

				}

			}

		}

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
		if ( is_admin() )
			return false;

		// Get all widgets
		$all_widgets = get_option( 'sidebars_widgets' );
		if ( ! $all_widgets )
			return false;

		$options = get_option( 'wpmtst_options' );

		// Get active strong widgets
		$strong_widgets = get_option( 'widget_wpmtst-widget' );

		foreach ( $all_widgets as $sidebar => $widgets ) {

			// active widget areas only (see notes.txt)
			if ( ! $widgets || 'wp_inactive_widgets' == $sidebar || 'array_version' == $sidebar )
				continue;

			foreach ( $widgets as $key => $widget_name ) {

				// Is our widget active?
				if ( 0 === strpos( $widget_name, 'wpmtst-widget-' ) ) {

					// Enqueue stylesheets
					//TODO move these to functions
					if ( $options['load_widget_style'] ) {
						self::add_style( 'wpmtst-widget-style' );
					}
					if ( is_rtl() && $options['load_rtl_style'] ) {
						self::add_style( 'wpmtst-widget-rtl-style' );
					}

					if ( $strong_widgets ) {
						$name_parts = explode( '-', $widget_name );
						$id         = array_pop( $name_parts );

						if ( isset( $strong_widgets[ $id ] ) ) {
							$widget = $strong_widgets[ $id ];

							if ( 'cycle' == $widget['mode'] ) {
								// Populate variable for Cycle script.
								$args = array(
									'fx'      => 'fade',
									'speed'   => $widget['cycle-speed'] * 1000,
									'timeout' => $widget['cycle-timeout'] * 1000,
									'pause'   => $widget['cycle-pause'] ? true : false,
								);
								self::add_script( 'wpmtst-slider', 'later' );
								self::add_script_var( 'wpmtst-slider', 'tcycle_' . str_replace( '-', '_', $widget_name ), $args );
							}
						}
					}

				} elseif ( 0 === strpos( $widget_name, 'text-' ) ) {
					
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
	 *
	 * @access public
	 * @see notes-pagebuilder-post-meta-panels_data.txt
	 */
	public static function find_pagebuilder_widgets() {
		if ( is_admin() )
			return false;

		// Get all widgets
		$panels_data = get_post_meta( get_the_ID(), 'panels_data', true );
		if ( ! $panels_data )
			return false;

		$all_widgets = $panels_data['widgets'];
		if ( ! $all_widgets )
			return false;

		$options = get_option( 'wpmtst_options' );
		
		// Need to group by cell to replicate Page Builder rendering order,
		// whether these are Strong widgets or not.
		$cells = array();
		foreach ( $all_widgets as $key => $widget ) {
			$cell_id             = $widget['panels_info']['cell'];
			$cells[ $cell_id ][] = $widget;
		}

		foreach ( $cells as $cell_widgets ) {

			foreach ( $cell_widgets as $key => $widget ) {

				// Is a Strong widget?
				if ( 'Strong_Testimonials_Widget' == $widget['panels_info']['class'] ) {

					// Enqueue stylesheets
					if ( $options['load_widget_style'] ) {
						self::add_style( 'wpmtst-widget-style' );
					}
					if ( is_rtl() && $options['load_rtl_style'] ) {
						self::add_style( 'wpmtst-widget-rtl-style' );
					}

					if ( 'cycle' == $widget['mode'] ) {

						// PageBuilder assembles name like `widget-0-0-0`
						$widget_name = implode( '_', array(
							'tcycle_widget',
							$widget['panels_info']['grid'],
							$widget['panels_info']['cell'],
							$key
						) );

						// Populate variable for Cycle script.
						$args = array(
							'fx'      => 'fade',
							'speed'   => $widget['cycle-speed'] * 1000,
							'timeout' => $widget['cycle-timeout'] * 1000,
							'pause'   => $widget['cycle-pause'] ? true : false,
						);
						self::add_script( 'wpmtst-slider', 'later' );
						self::add_script_var( 'wpmtst-slider', $widget_name, $args );

					}

				} elseif ( 'Strong_Testimonials_View_Widget' == $widget['panels_info']['class'] ) {

					// Incorporate attributes from the View and defaults, just like the shortcode filter.
					if ( isset( $widget['view'] ) && $widget['view'] ) {
						//TODO DRY
						$atts        = array( 'view' => $widget['view'] );
						$parsed_atts = self::parse_view( $atts, self::get_view_defaults(), $atts );

						// Build the shortcode signature.
						$att_string = serialize( $atts );

						// Turn empty atts into switches.
						$atts = normalize_empty_atts( $parsed_atts );

						self::find_single_view( self::$view_count++, $atts, $att_string );
					}

				} elseif ( 'WP_Widget_Text' == $widget['panels_info']['class'] ) {
					
					// Is a Text widget?
					self::process_content( $widget['text'] );
					
				}

			}

		}

	}

	/**
	 * Build list of all shortcode views on a page.
	 *
	 * A combination of has_shortcode and shortcode_parse_atts.
	 */
	public static function find_original_shortcodes() {
		if ( is_admin() )
			return false;

		global $post;
		if ( empty( $post ) )
			return false;

		$content = $post->post_content;
		if ( false === strpos( $content, '[' ) )
			return false;

		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) )
			return false;

		$options      = get_option( 'wpmtst_options' );
		$form_options = get_option( 'wpmtst_form_options' );

		foreach ( $matches as $key => $shortcode ) {

			// Check for original shortcodes. Keep these exploded!

			if ( 'wpmtst-all' == $shortcode[2] ) {
				if ( $options['load_page_style'] ) {
					self::add_style( 'wpmtst-style' );
				}

				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}

				self::add_script( 'wpmtst-pager-plugin' );
				add_action( 'wp_footer', 'wpmtst_pagination_function' );
			}

			if ( 'wpmtst-form' == $shortcode[2] ) {
				if ( $options['load_form_style'] ) {
					self::add_style( 'wpmtst-form-style' );
				}

				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}

				self::add_script( 'wpmtst-validation-plugin' );
				self::add_script( 'wpmtst-validation-lang' );
				add_action( 'wp_footer', 'wpmtst_validation_function' );

				if ( $form_options['honeypot_before'] ) {
					add_action( 'wp_footer', 'wpmtst_honeypot_before_script' );
					add_action( 'wpmtst_honeypot_before', 'wpmtst_honeypot_before' );
				}

				if ( $form_options['honeypot_after'] ) {
					add_action( 'wp_footer', 'wpmtst_honeypot_after_script' );
					add_action( 'wpmtst_honeypot_after', 'wpmtst_honeypot_after' );
				}
			}

			if ( 'wpmtst-cycle' == $shortcode[2] ) {
				if ( $options['load_page_style'] ) {
					self::add_style( 'wpmtst-style' );
				}

				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}

				$cycle = get_option( 'wpmtst_cycle' );

				// Populate variable for Cycle script.
				$var = array(
					'fx'      => 'fade',
					'speed'   => $cycle['speed'] * 1000,
					'timeout' => $cycle['timeout'] * 1000,
					'pause'   => $cycle['pause'] ? true : false,
				);
				self::add_script( 'wpmtst-slider', 'later' );
				self::add_script_var( 'wpmtst-slider', 'tcycle_cycle_shortcode', $var );

			}

			if ( 'wpmtst-single' == $shortcode[2] ) {
				if ( $options['load_page_style'] ) {
					self::add_style( 'wpmtst-style' );
				}

				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}
			}

			if ( 'wpmtst-random' == $shortcode[2] ) {
				if ( $options['load_page_style'] ) {
					self::add_style( 'wpmtst-style' );
				}

				if ( is_rtl() && $options['load_rtl_style'] ) {
					self::add_style( 'wpmtst-rtl-style' );
				}
			}

		}
	}

	/**
	 * Parse view attributes.
	 *
	 * @param array $out The output array of shortcode attributes.
	 * @param array $pairs The supported attributes and their defaults.
	 * @param array $atts The user defined shortcode attributes.
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
		if ( ! $view ) {
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

		if ( 'entire' == $view_data['content'] ) {
			unset( $view_data['length'] );
		}

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
	 */
	//public function form_handler() {
	//	require_once WPMTST_INC . 'form-handler-functions.php';
	//	wpmtst_form_handler();
	//}
	
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
	
}
	
endif; // class_exists check

function WPMST() {
	return Strong_Testimonials::instance();
}

// Get plugin running
WPMST();
