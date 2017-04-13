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
	 * Build our query based on view attributes.
	 *
	 * @return WP_Query
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
		}
		else {
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
			}
			else {
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

	public function gradient_rules( $c1, $c2 ) {
		return "background: {$c1};\n"
			. "background: -moz-linear-gradient(top, {$c1} 0%, {$c2} 100%);\n"
			. "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, {$c1}), color-stop(100%, {$c2}));\n"
			. "background: -webkit-linear-gradient(top,  {$c1} 0%, {$c2} 100%);\n"
			. "background: -o-linear-gradient(top, {$c1} 0%, {$c2} 100%);\n"
			. "background: -ms-linear-gradient(top, {$c1} 0%, {$c2} 100%);\n"
			. "background: linear-gradient(to bottom, {$c1} 0%, {$c2} 100%);"
			. "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='{$c1}', endColorstr='{$c2}', GradientType=0);\n";
	}

}

endif;
