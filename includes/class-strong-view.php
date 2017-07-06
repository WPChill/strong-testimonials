<?php
/**
 * View class.
 *
 * @since 2.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_View' ) ) :

class Strong_View {

	/**
	 * The view settings.
	 *
	 * @var array
	 */
	public $atts;

	/**
	 * The query.
	 */
	public $query;

	/**
	 * The template file.
	 */
	public $template_file;

	/**
	 * The view output.
	 *
	 * @var string
	 */
	public $html;

	/**
	 * The plugin version.
	 *
	 * @var string
	 */
	public $plugin_version;

	function __construct() {
		$this->plugin_version = get_option( 'wpmtst_plugin_version' );
	}

	/**
	 * Return our rendered template.
	 *
	 * @return string
	 */
	public function output() {
		return $this->html;
	}

	/**
	 * Process the view.
	 *
	 * Used by main class to load the scripts and styles for this View.
	 */
	public function process() {}

	/**
	 * Build the view.
	 */
	public function build() {}

	/**
	 * Add content filters.
	 */
	public function add_content_filters() {

		if ( isset( $this->atts['truncated'] ) && $this->atts['truncated'] ) {

			// Force use of content instead of manual excerpt.
			add_filter( 'wpmtst_get_the_excerpt', 'wpmtst_bypass_excerpt', 1 );

		} elseif ( isset( $this->atts['excerpt'] ) && $this->atts['excerpt'] ) {

			// Maybe add read-more to manual excerpts.
			add_filter( 'wpmtst_get_the_excerpt', 'wpmtst_custom_excerpt_more', 20 );

		} else {

			// no filters

		}

	}

	/**
	 * Add content filters.
	 */
	public function remove_content_filters() {

		if ( isset( $this->atts['truncated'] ) && $this->atts['truncated'] ) {

			remove_filter( 'wpmtst_get_the_excerpt', 'wpmtst_bypass_excerpt', 1 );

		} elseif ( isset( $this->atts['excerpt'] ) && $this->atts['excerpt'] ) {

			remove_filter( 'wpmtst_get_the_excerpt', 'wpmtst_custom_excerpt_more', 20 );

		} else {

			// no filters

		}

	}

	/**
	 * Build our query based on view attributes.
	 */
	public function build_query() {}

	/**
	 * Build class list based on view attributes.
	 *
	 * This must happen after the query.
	 */
	public function build_classes() {}

	/**
	 * Load template's extra stylesheets.
	 *
	 * @since 2.11.12
	 * @since 2.16.0 In Strong_View class.
	 */
	public function load_extra_stylesheets() {
		$styles = WPMST()->templates->get_template_attr( $this->atts, 'styles', false );
		if ( $styles ) {
			$styles_array = explode( ',', str_replace( ' ', '', $styles ) );
			foreach ( $styles_array as $handle ) {
				WPMST()->add_style( $handle );
			}
		}
	}

	/**
	 * Load template's script and/or dependencies.
	 *
	 * @since 1.25.0
	 * @since 2.16.0 In Strong_View class.
	 */
	public function load_dependent_scripts() {
		$deps = WPMST()->templates->get_template_attr( $this->atts, 'deps', false );
		$deps_array = $deps ? explode( ',', str_replace( ' ', '', $deps ) ) : array();

		$script = WPMST()->templates->get_template_attr( $this->atts, 'script', false );
		if ( $script ) {
			$handle = 'testimonials-' . str_replace( ':', '-', $this->atts['template'] );
			wp_register_script( $handle, $script, $deps_array );
			WPMST()->add_script( $handle );
		} else {
			foreach ( $deps_array as $handle ) {
				WPMST()->add_script( $handle );
			}
		}
	}

	/**
	 * Find a template's associated stylesheet.
	 *
	 * @since 1.23.0
	 * @since 2.16.0 In Strong_View class.
	 *
	 * @param bool  $enqueue   True = enqueue the stylesheet, @since 2.3
	 *
	 * @return bool|string
	 */
	public function find_stylesheet( $enqueue = true ) {
		// In case of deactivated widgets still referencing deleted Views
		if ( ! isset( $this->atts['template'] ) || ! $this->atts['template'] )
			return false;

		$stylesheet = WPMST()->templates->get_template_attr( $this->atts, 'stylesheet', false );
		if ( $stylesheet ) {
			$handle = 'testimonials-' . str_replace( ':', '-', $this->atts['template'] );
			wp_register_style( $handle, $stylesheet, array(), $this->plugin_version );
			if ( $enqueue ) {
				WPMST()->add_style( $handle );
			} else {
				return $handle;
			}
		}

		return false;
	}

	/**
	 * Add template name as CSS class.
	 *
	 * @since 2.11.0
	 *
	 * @return mixed
	 */
	public function get_template_css_class() {
		$class = str_replace( ':content', '', $this->atts['template'] );
		$class = str_replace( ':', '-', $class );
		$class = str_replace( '-form-form', '-form', $class );
		return $class;
	}

	/**
	 * Print our custom style.
	 *
	 * @since 2.22.0
	 */
	public function add_custom_style() {
		$this->custom_background();

		/**
		 * Hook to add more inline style to `wpmtst-custom-style` handle.
		 * @since 2.22.0
		 */
		do_action( 'wpmtst_view_custom_style', $this );
	}

	/**
	 * Build CSS for custom background.
	 */
	public function custom_background() {
		$background = $this->atts['background'];
		if ( ! isset( $background['type'] ) )
			return;

		$c1 = '';
		$c2 = '';

		switch ( $background['type'] ) {
			case 'preset':
				$preset = wpmtst_get_background_presets( $background['preset'] );
				$c1     = $preset['color'];
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

		// Special handling for Divi Builder
		$prefix = '';
		if ( isset( $this->atts['divi_builder'] ) && $this->atts['divi_builder'] && wpmtst_divi_builder_active() ) {
			$prefix = '#et_builder_outer_content ';
		}

		$view_el = "$prefix.strong-view-id-{$this->atts['view']}";
		$is_form = ( isset( $this->atts['form'] ) && $this->atts['form'] );

		// Includes special handling for Large Widget template.
		if ( $c1 && $c2 ) {

			$gradient = self::gradient_rules( $c1, $c2 );

			if ( $is_form ) {
				wp_add_inline_style( 'wpmtst-custom-style', "$view_el .strong-form-inner { $gradient }\n" );
			} else {
				wp_add_inline_style( 'wpmtst-custom-style', "$view_el .testimonial-inner { $gradient }\n" );

				if ( 'large-widget:widget' == WPMST()->atts( 'template' ) ) {
					wp_add_inline_style( 'wpmtst-custom-style', "$view_el .readmore-page { background: $c2 }\n" );
				}
			}

		} elseif ( $c1 ) {

			if ( $is_form ) {
				wp_add_inline_style( 'wpmtst-custom-style', "$view_el .strong-form-inner { background: $c1; }\n" );
			} else {
				wp_add_inline_style( 'wpmtst-custom-style', "$view_el .testimonial-inner { background: $c1; }\n" );

				if ( 'large-widget:widget' == WPMST()->atts( 'template' ) ) {
					wp_add_inline_style( 'wpmtst-custom-style', "$view_el .readmore-page { background: $c1 }\n" );
				}
			}

		}
	}

	/**
	 * Print gradient rules.
	 *
	 * @param $c1
	 * @param $c2
	 *
	 * @return string
	 */
	public function gradient_rules( $c1, $c2 ) {
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
	 * Stars
	 *
	 * @since 2.16.0 In Strong_View class.
	 */
	public function has_stars() {
		if ( isset( $this->atts['client_section'] ) ) {
			foreach ( $this->atts['client_section'] as $field ) {
				if ( 'rating' == $field['type'] ) {
					WPMST()->add_style( 'wpmtst-rating-display' );
					break;
				}
			}
		}
	}

}

endif;
