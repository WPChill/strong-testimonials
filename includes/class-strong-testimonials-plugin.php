<?php
/**
 * Plugin main class.
 *
 * @since 1.15.0
 */
final class StrongTestimonials_Plugin {
	
  private static $instance;

  /**
	 * A singleton instance.
	 *
	 * For now, only used for preprocessing shortcodes and widgets to properly
	 * enqueue styles and scripts to (1) improve overall plugin flexibility and
	 * to (2) improve compatibility with PageBuilder plugin (and probably others).
	 *
	 * @return StrongTestimonials_Plugin  StrongTestimonials_Plugin object
	 */
	public static function get_instance() {
    if ( !self::$instance ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

	public static $views = false;
	public static $view_count = 0;
	public static $styles = array( 'normal' => array(), 'later' => array() );
	public static $scripts = array( 'normal' => array(), 'later' => array() );
	public static $script_vars;
	
  private function __construct() {
		
		// Preprocess the post content for the [strong] shortcode.
		add_action( 'wp', array( $this, 'find_views' ) );
		
		// Preprocess the post content for widgets.
		add_action( 'wp', array( $this, 'find_widgets' ) );
		add_action( 'wp', array( $this, 'find_pagebuilder_widgets' ) );
		
		// Preprocess the post content for the original shortcodes.
		add_action( 'wp', array( $this, 'find_original_shortcodes' ) );

		// Localize scripts. Priority 5 is important.
		add_action( 'wp_print_footer_scripts', array( $this, 'localize_vars' ), 5 );
		
		/**
		 * Filter the content.
		 * 
		 * Priority must be higher than PageBuilder.
		 */
		// add_filter( 'the_content', array( $this, 'append_order_attribute' ), 5 );
		
	}
	
	/**
	 * Access to the testimonial views on a page.
	 *
	 * @access public
	 * @return array  An array of views (only shortcodes for now).
	 */
	public static function get_views() {
		return self::$views;
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
	 * @param string $style_name  The stylesheet handle.
	 * @param string $when  The enqueue priority. normal = priority 10, later = after theme ~200.
	 */
	private static function add_style( $style_name, $when = 'normal' ) {
		if ( !in_array( $style_name, self::$styles[$when] ) )
			self::$styles[$when][] = $style_name;
	}

	/**
	 * Add a script handle for enqueueing.
	 *
	 * @access private
	 * @param string $script_name  The script handle.
	 * @param string $when  The enqueue priority. normal|later
	 */
	private static function add_script( $script_name, $when = 'normal' ) {
		if ( !in_array( $script_name, self::$scripts[$when] ) )
			self::$scripts[$when][] = $script_name;
	}
	
	/**
	 * Add a script variable for localizing.
	 *
	 * @access private
	 * @param string $script_name  The script handle.
	 * @param string $var_name  The script variable name.
	 * @param string $var  The script variable.
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
	
	/**
	 * Append an attribute to the shortcode.
	 *
	 * Used to indicate sequential order of the shortcode in a page.
	 * An elegant solution that won't work because of Page Builder's
	 * content filter. So do this in wpmtst-cycle.js instead.
	 *
	 * @access public
	 * @param string $content  The post content.
	 * @return string  The modified post content.
	 */
	public static function append_order_attribute( $content ) {
		global $post;

		if ( empty( $post ) ) return $content;
		
		if ( false === strpos( $content, '[' ) ) return $content;

		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		
		if ( empty( $matches ) ) return $content;
		
		foreach ( $matches as $key => $shortcode ) {
			
			if ( 'strong' === $shortcode[2] ) {
				$atts = shortcode_parse_atts( $shortcode[3] );
				$content = str_replace( $shortcode[3], $shortcode[3] . ' order_in_page="'.$key.'"', $content );
			}
			
		}
		return $content;
	}		
	
	/**
	 * Build list of all shortcode views on a page.
	 *
	 * A combination of has_shortcode and shortcode_parse_atts.
	 */
	public static function find_views() {
		
		if ( is_admin() ) return false;
		
		global $post;
		if ( empty( $post ) ) return false;
		
		$content = $post->post_content;
		if ( false === strpos( $content, '[' ) ) return false;

		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) ) return false;

		self::$view_count = count( $matches );
		
		$options = get_option( 'wpmtst_options' );
		$form_options = get_option( 'wpmtst_form_options' );
		
		$views = array();
		
		foreach ( $matches as $key => $shortcode ) {
			$view = '';
			
			if ( 'strong' === $shortcode[2] ) {
				
				$atts = normalize_empty_atts( shortcode_parse_atts( $shortcode[3] ) );
				
				$preprocess = false;
				
				// Read More
				if ( isset( $atts['read_more'] ) ) {
					$view = array( 'mode' => 'read_more', 'atts' => $atts );
				}
				
				// Form
				elseif ( isset( $atts['form'] ) ) {
					$view = array( 'mode' => 'form', 'atts' => $atts );
					
					if ( $options['load_form_style'] ) {
						self::add_style( 'wpmtst-form-style' );
					}
					
					self::add_script( 'wpmtst-form-script' );
					
					if ( $form_options['honeypot_before'] ) {
						add_action( 'wp_footer', 'wpmtst_honeypot_before_script' );
						add_action( 'wpmtst_honeypot_before', 'wpmtst_honeypot_before' );
					}
					
					if ( $form_options['honeypot_after'] ) {
						add_action( 'wp_footer', 'wpmtst_honeypot_after_script' );
						add_action( 'wpmtst_honeypot_after', 'wpmtst_honeypot_after' );
					}
					
				}
				
				// Slideshow
				elseif ( isset( $atts['slideshow'] ) ) {
					$view = array( 'mode' => 'slideshow', 'atts' => $atts );
					if ( $options['load_page_style'] ) {
						self::add_style( 'wpmtst-style' );
					}
					$preprocess = true;
				}
				
				// Display (default)
				else {
					$view = array( 'mode' => 'display', 'atts' => $atts );
					if ( $options['load_page_style'] ) {
						self::add_style( 'wpmtst-style' );
					}
					$preprocess = true;
				}
				
				// Process attributes to check for required styles & scripts.
				if ( $preprocess ) self::pre_process( $key, $view );
				
				$views[] = $view;
				
			}
		
		}
		
		if ( !empty( $views ) && is_rtl() && $options['load_rtl_style'] ) {
			wp_enqueue_style( 'wpmtst-rtl-style' );
		}

		self::$views = $views;
	}

	/**
	 * Preprocess a view to gather styles, scripts and script vars.
	 *
	 * @access private
	 * @param string $key  The array key that indicates the view's order in the page.
	 * @param array $view  The view.
	 */
	private static function pre_process( $key, $view ) {
		
		$options = get_option( 'wpmtst_options' );
		
		// minimal subset of all shortcode atts
		extract( shortcode_atts(
			array(
					'slideshow'  => '',
					'category'   => '',
					'count'      => -1,
					'per_page'   => '',
					'nav'        => 'after',
					'id'         => '',
					'show_for'   => '8',
					'effect_for' => '1.5',
					'no_pause'   => 'false',
			),
			$view['atts']
		) );
		
		// extract comma-separated values
		$categories = explode( ',', $category );
		$ids = explode( ',', $id );
		
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
		}
		elseif ( $category ) {
			$args['tax_query'] = array(
					array(
							'taxonomy' => 'wpm-testimonial-category',
							'field'    => 'id',
							'terms'    => $categories
					)
			);
		}
		
		// query
		$query = new WP_Query( $args );
		$post_count = $query->post_count;
		
		// display -> slideshow
		if ( $slideshow ) {
			
			// Populate variable for Cycle script.
			$args = array (
					'fx'      => 'fade',
					'speed'   => $effect_for * 1000, 
					'timeout' => $show_for * 1000, 
					'pause'   => $no_pause ? true : false,
			);
			self::add_script( 'wpmtst-slider', 'later' );
			self::add_script_var( 'wpmtst-slider', 'tcycle_' . $key, $args );

		}
		
		// display -> paginated
		else {
			if ( $per_page && $post_count > $per_page ) {
				
				// Populate variable for QuickPager script.
				if ( false !== strpos( $nav, 'before' ) && false !== strpos( $nav, 'after') )
					$nav = 'both';
		
				$pager = array (
						'id'            => '.strong-paginated',
						'pageSize'      => $per_page,
						'currentPage'   => 1, 
						'pagerLocation' => $nav
				);
				self::add_script( 'wpmtst-pager-script' );
				self::add_script_var( 'wpmtst-pager-script', 'pagerVar', $pager );
				
			}
		}

		wp_reset_postdata();
	}
	
	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For standard widgets NOT in PageBuilder panels.
	 *
	 * @access public
	 */
	public static function find_widgets() {

		if ( is_admin() ) return false;

		$options = get_option( 'wpmtst_options' );
		
		// Get all widgets
		$all_widgets = get_option( 'sidebars_widgets' );
		
		// Get active strong widgets
		$strong_widgets = get_option( 'widget_wpmtst-widget' );
		
		foreach ( $all_widgets as $sidebar => $widgets ) {
			
			// sidebars only (see notes.txt)
			if ( 'sidebar' != substr( $sidebar, 0, 7 ) ) continue;
			
			foreach ( $widgets as $key => $widget_name ) {
				
				// Is our widget active?
				if ( 0 === strpos( $widget_name, 'wpmtst-widget' ) ) {
					
					// Enqueue stylesheets
					if ( $options['load_widget_style'] ) {
						self::add_style( 'wpmtst-widget-style' );
					}
					if ( is_rtl() && $options['load_rtl_style'] ) {
						self::add_style( 'wpmtst-widget-rtl-style' );
					}
					
					$widget_id = array_pop( explode( '-', $widget_name ) );
					$widget_settings = $strong_widgets[$widget_id];
					
					if ( 'cycle' == $widget_settings['mode'] ) {
		
						// Populate variable for Cycle script.
						$args = array (
								'fx'      => 'fade',
								'speed'   => $widget_settings['cycle-speed'] * 1000, 
								'timeout' => $widget_settings['cycle-timeout'] * 1000, 
								'pause'   => $widget_settings['cycle-pause'] ? true : false,
						);
						self::add_script( 'wpmtst-slider', 'later' );
						self::add_script_var( 'wpmtst-slider', 'tcycle_' . str_replace( '-', '_', $widget_name ), $args );

					}
					
				}
				
			}
				
		}
		
	}
	
	/**
	 * Find widgets in a page to gather styles, scripts and script vars.
	 *
	 * For widgets in PageBuilder panels.
	 *
	 * @access public
	 * @see notes-pagebuilder-post-meta-panels_data.txt
	 */
	public static function find_pagebuilder_widgets() {

		if ( is_admin() ) return false;
		
		$options = get_option( 'wpmtst_options' );
		
		// Get all widgets
		$panels_data = get_post_meta( get_the_ID(), 'panels_data', true );
		if ( !$panels_data ) return false;
		
		$all_widgets = $panels_data['widgets'];
		if ( !$all_widgets ) return false;
		
		foreach ( $all_widgets as $key => $widget ) {
			
			// Is our widget active?
			if ( 'WpmTst_Widget' == $widget['panels_info']['class'] ) {
				
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
							$widget['panels_info']['id']
					) );
	
					// Populate variable for Cycle script.
					$args = array (
							'fx'      => 'fade',
							'speed'   => $widget['cycle-speed'] * 1000, 
							'timeout' => $widget['cycle-timeout'] * 1000, 
							'pause'   => $widget['cycle-pause'] ? true : false,
					);
					self::add_script( 'wpmtst-slider', 'later' );
					self::add_script_var( 'wpmtst-slider', $widget_name, $args );

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
		
		if ( is_admin() ) return false;
		
		global $post;
		if ( empty( $post ) ) return false;
		
		$content = $post->post_content;
		if ( false === strpos( $content, '[' ) ) return false;

		preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) ) return false;

		$options = get_option( 'wpmtst_options' );
		$form_options = get_option( 'wpmtst_form_options' );
		
		foreach ( $matches as $key => $shortcode ) {
			
			// Check for original shortcodes. Keep these exploded!

			if ( 'wpmtst-all' == $shortcode[2] ) {
				if ( $options['load_page_style'] )
					self::add_style( 'wpmtst-style' );
				
				if ( is_rtl() && $options['load_rtl_style'] )
					self::add_style( 'wpmtst-style' );
				
				self::add_script( 'wpmtst-pager-plugin' );
				add_action( 'wp_footer', 'wpmtst_pagination_function' );
			}

			if ( 'wpmtst-form' == $shortcode[2] ) {
				if ( $options['load_form_style'] )
					self::add_style( 'wpmtst-form-style' );
				
				if ( is_rtl() && $options['load_rtl_style'] )
					self::add_style( 'wpmtst-rtl-style' );
					
				self::add_script( 'wpmtst-validation-plugin' );
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
				if ( $options['load_page_style'] )
					self::add_style( 'wpmtst-style' );
				
				if ( is_rtl() && $options['load_rtl_style'] )
					self::add_style( 'wpmtst-rtl-style' );
				
				$cycle = get_option( 'wpmtst_cycle' );

				// Populate variable for Cycle script.
				$var = array (
						'fx'      => 'fade',
						'speed'   => $cycle['speed'] * 1000, 
						'timeout' => $cycle['timeout'] * 1000, 
						'pause'   => $cycle['pause'] ? true : false,
				);
				self::add_script( 'wpmtst-slider', 'later' );
				self::add_script_var( 'wpmtst-slider', 'tcycle_cycle_shortcode', $var );
			
			}

			if ( 'wpmtst-single' == $shortcode[2] ) {
				if ( $options['load_page_style'] )
					self::add_style( 'wpmtst-style' );
				
				if ( is_rtl() && $options['load_rtl_style'] )
					self::add_style( 'wpmtst-rtl-style' );
			}

			if ( 'wpmtst-random' == $shortcode[2] ) {
				if ( $options['load_page_style'] )
					self::add_style( 'wpmtst-style' );
				
				if ( is_rtl() && $options['load_rtl_style'] )
					self::add_style( 'wpmtst-rtl-style' );
			}
	
		}
		
	}

} // StrongTestimonials_Plugin

$strong_testimonials_plugin = StrongTestimonials_Plugin::get_instance();
