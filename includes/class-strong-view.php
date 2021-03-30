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

	/**
	 * The stylesheet handle. For adding inline style.
	 *
	 * @since 2.31.8
	 * @var string
	 */
	public $stylesheet;

	/**
	 * Strong_View constructor.
	 *
	 * @param array $atts
	 */
	function __construct( $atts = array() ) {
		$this->atts = apply_filters( 'wpmtst_view_atts', $atts );
		$this->plugin_version = get_option( 'wpmtst_plugin_version' );
	}

	/**
     * Return a specific view attribute.
     *
	 * @param $att
	 * @since 2.33.0
     *
	 * @return mixed|null
	 */
	public function get_att( $att ) {
	    return isset( $this->atts[ $att ] ) ? $this->atts[ $att ] : null;
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
     * Warning message of view not found.
     *
	 * @return string
	 */
	public function nothing_found() {
		ob_start();
		?>
		<p style="color: #CD0000;">
			<?php esc_html_e( 'No testimonials found. Check your view settings.', 'strong-testimonials' ); ?><br>
            <span style="color: #777; font-size: 0.9em;"><?php esc_html_e( '(Only administrators see this message.)', 'strong-testimonials' ); ?></span>
		</p>
		<?php

		return apply_filters( 'wpmtst_message_nothing_found', ob_get_clean() );
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


        if ( 'truncated' == $this->get_att( 'content' ) ) {

		    // automatic excerpt

            $this->excerpt_filters();

            $this->hybrid_content();

	        add_filter( 'wpmtst_get_the_excerpt', 'wpmtst_bypass_excerpt', 1 );

            if ( $this->get_att( 'more_post_ellipsis' ) ) {
                add_filter( 'wpmtst_use_ellipsis', '__return_true' );
            }

        } elseif ( 'excerpt' == $this->get_att( 'content' ) ) {

		    // manual excerpt (if no excerpt then use automatic excerpt)

	        $this->excerpt_filters();

            $this->hybrid_content();

	        if ( $this->get_att( 'more_full_post' ) ) {
		        add_filter( 'wpmtst_get_the_excerpt', array( $this, 'manual_excerpt_more' ), 20 );
	        }

	        if ( $this->get_att( 'more_post_ellipsis' ) ) {
		        add_filter( 'wpmtst_use_ellipsis', array( $this, 'has_no_excerpt' ) );
	        } else {
		        add_filter( 'wpmtst_use_ellipsis', '__return_false' );
	        }

        } else {

		    // full content
		    add_filter( 'wpmtst_get_the_content', 'wpmtst_the_content_filtered' );

        }

	}

	/**
	 * Add excerpt filters.
     *
     * @since 2.33.0
	 */
	public function excerpt_filters() {
        add_filter( 'wpmtst_get_the_content', 'wpmtst_the_excerpt_filtered' );
        add_filter( 'wpmtst_get_the_excerpt', 'wpmtst_trim_excerpt' );

        if ( ! $this->get_att( 'use_default_length' ) ) {
			add_filter( 'excerpt_length', array( $this, 'excerpt_length' ),999 );
		}

		if ( ! $this->get_att( 'use_default_more' ) ) {
			add_filter( 'excerpt_more', array( $this, 'excerpt_more' ), 99999 );
		}
	}

	/**
	 * Add hybrid content filters.
     *
     * @since 2.33.0
	 */
	public function hybrid_content() {
		if ( $this->get_att( 'more_post_in_place' ) ) {
			add_filter( 'wpmtst_is_hybrid_content', '__return_true' );
		} else {
			add_filter( 'wpmtst_read_more_post_link', 'wpmtst_prepend_ellipsis' );
		}
	}

	/**
	 * Remove content filters.
     *
     * @since 2.33.0
	 */
	public function remove_content_filters() {
        remove_filter( 'wpmtst_get_the_content', 'wpmtst_the_content_filtered' );
        remove_filter( 'wpmtst_get_the_content', 'wpmtst_the_excerpt_filtered' );

		remove_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
		remove_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
		remove_filter( 'wpmtst_read_more_post_link', 'wpmtst_prepend_ellipsis' );

        remove_filter( 'wpmtst_get_the_excerpt', 'wpmtst_bypass_excerpt', 1 );
        remove_filter( 'wpmtst_get_the_excerpt', 'wpmtst_hybrid_excerpt' );
        remove_filter( 'wpmtst_get_the_excerpt', 'wpmtst_trim_excerpt' );
        remove_filter( 'wpmtst_get_the_excerpt', array( $this, 'manual_excerpt_more' ), 20 );

		remove_filter( 'wpmtst_is_hybrid_content', '__return_true' );
		remove_filter( 'wpmtst_use_ellipsis', '__return_true' );
		remove_filter( 'wpmtst_use_ellipsis', array( $this, 'has_no_excerpt' ) );
	}

	/**
     * Return true if post has no manual excerpt.
     *
     * @since 2.33.0
     *
	 * @return bool
	 */
	public function has_no_excerpt() {
	    return ! has_excerpt();
	}

	/**
     * Set custom excerpt length.
     *
	 * @param $words
     * @since 2.33.0
	 *
	 * @return mixed|null
	 */
	public function excerpt_length( $words ) {
		$excerpt_length = $this->get_att( 'excerpt_length' );

		return $excerpt_length ? $excerpt_length : $words;
	}

	/**
     * The read-more link, maybe prepended with an ellipsis.
     *
	 * @param $more
     * @since 2.33.0
	 *
	 * @return string
	 */
	public function excerpt_more( $more ) {
		return wpmtst_get_excerpt_more_link();
	}

	/**
	 * Maybe add read-more to manual excerpt.
	 *
	 * @since 2.26.0
	 * @param $excerpt
	 *
	 * @return string
	 */
	public function manual_excerpt_more( $excerpt ) {
	    if ( has_excerpt() ) {
			$excerpt .= apply_filters( 'excerpt_more', ' [&hellip;]' );
		}

		return $excerpt;
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
		$styles = WPMST()->templates->get_template_config( $this->atts, 'styles', false );
		if ( $styles ) {
			$styles_array = explode( ',', str_replace( ' ', '', $styles ) );
			foreach ( $styles_array as $handle ) {
				WPMST()->render->add_style( $handle );
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
		// Scripts that are already registered.
		$deps = WPMST()->templates->get_template_config( $this->atts, 'scripts', false );
		$deps_array = $deps ? explode( ',', str_replace( ' ', '', $deps ) ) : array();

		// A single script included in directory.
		$script = WPMST()->templates->get_template_config( $this->atts, 'script', false );
                
		if ( $script ) {
			$handle = 'testimonials-' . $this->get_att( 'template' );
			wp_register_script( $handle, $script, $deps_array );
			WPMST()->render->add_script( $handle );
		} else {
			foreach ( $deps_array as $handle ) {
				WPMST()->render->add_script( $handle );
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
		if ( ! $this->get_att( 'template' ) ) {
			return false;
		}

		$stylesheet = WPMST()->templates->get_template_attr( $this->atts, 'stylesheet', false );
		if ( $stylesheet ) {
			$handle = 'testimonials-' . str_replace( ':', '-', $this->get_att( 'template' ) );
			$this->set_stylesheet( $handle );
			wp_register_style( $handle, $stylesheet, array(), $this->plugin_version );
			if ( $enqueue ) {
				WPMST()->render->add_style( $handle );
			} else {
				return $handle;
			}
		}

		return false;
	}

	/**
	 * Assemble list of CSS classes.
	 *
	 * @since 2.11.0
	 * @since 2.30.0 Adding template option classes.
	 *
	 * @return array
	 */
	public function get_template_css_class() {
		$template_name     = $this->get_att( 'template' );
        $template_settings = $this->get_att( 'template_settings' );

		// Maintain back-compat with template format version 1.0.
		$class = str_replace( ':content', '', $template_name );
		$class = str_replace( ':', '-', $class );
		$class = str_replace( '-form-form', '-form', $class );
		$class = $class . ' wpmtst-' . $class ;
		$class_list = array( $class );

		$template_object = WPMST()->templates->get_template_by_name( $template_name );

		if ( isset( $template_object['config']['options'] ) && is_array( ( $template_object['config']['options'] ) ) ) {

			foreach ( $template_object['config']['options'] as $option ) {

				if( ! isset( $option->values ) ) {
					continue;
				}

				if ( isset( $template_settings[ $template_name ][ $option->name ] ) ) {

					foreach ( $option->values as $value ) {
						if ( $value->value == $template_settings[ $template_name ][ $option->name ] ) {
							if ( isset( $value->class_name ) ) {
								$class_list[] = $value->class_name;
							}
						}
					}

				}

			}

		}

		return array_filter( $class_list );
	}

	/**
	 * Print our custom style.
	 *
	 * @since 2.22.0
	 */
	public function add_custom_style() {
		$this->custom_background();
		$this->custom_font_color();

		/**
		 * Hook to add more inline style.
		 *
		 * @since 2.22.0
		 */
		do_action( 'wpmtst_view_custom_style', $this );
	}

	/**
	 * Is this a form view?
	 *
	 * @since 2.30.0
	 *
	 * @return bool
	 */
	public function is_form() {
		return ( 'form' == $this->get_att( 'mode' ) );
	}

	/**
	 * Build CSS for custom font color.
	 *
	 * @since 2.30.0
	 */
	public function custom_font_color() {
		$font_color = $this->get_att( 'font-color' );
		if ( ! isset( $font_color['type'] ) || 'custom' != $font_color['type'] ) {
			return;
		}

		$c1 = isset( $font_color['color'] ) ? $font_color['color'] : '';

		if ( $c1 ) {
			$view_el = ".strong-view-id-{$this->get_att( 'view' )}";
			$handle = $this->get_stylesheet();

			if ( $this->is_form() ) {
				wp_add_inline_style( $handle,
				                     "$view_el .strong-form-inner { color: $c1; }" );
			}
			else {
				wp_add_inline_style( $handle,
				                     "$view_el .wpmtst-testimonial-heading," .
				                     "$view_el .wpmtst-testimonial-content p," .
				                     "$view_el .wpmtst-testimonial-content a.readmore," .
				                     "$view_el .wpmtst-testimonial-client div," .
				                     "$view_el .wpmtst-testimonial-client a { color: $c1; }" );
			}
		}
	}

	/**
	 * Build CSS for custom background.
	 */
	public function custom_background() {
		$background = $this->get_att( 'background' );
		if ( ! isset( $background['type'] ) ) {
			return;
		}

		$handle = $this->get_stylesheet();
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
		if ( $this->get_att( 'divi_builder' ) && wpmtst_divi_builder_active() ) {
			$prefix = '#et_builder_outer_content ';
		}

		$view_el = "$prefix.strong-view-id-{$this->get_att( 'view' )}";

		// Includes special handling for Bold template.
		if ( $c1 && $c2 ) {

			$gradient = self::gradient_rules( $c1, $c2 );

			if ( $this->is_form() ) {

				wp_add_inline_style( $handle, "$view_el .strong-form-inner { $gradient }" );

			} else {

				wp_add_inline_style( $handle, "$view_el .wpmtst-testimonial-inner { $gradient }" );

				if ( 'bold' == WPMST()->atts( 'template' ) ) {
					wp_add_inline_style( $handle, "$view_el .readmore-page { background: $c2 }" );
				}

			}

		} elseif ( $c1 ) {

			if ( $this->is_form() ) {

				wp_add_inline_style( $handle, "$view_el .strong-form-inner { background: $c1; }" );

			} else {

				wp_add_inline_style( $handle, "$view_el .wpmtst-testimonial-inner { background: $c1; }" );

				if ( 'bold' == WPMST()->atts( 'template' ) ) {
					wp_add_inline_style( $handle, "$view_el .readmore-page { background: $c1 }" );
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
		if ( $this->get_att( 'client_section' ) ) {
			foreach ( $this->get_att( 'client_section' ) as $field ) {
				if ( 'rating' == $field['type'] ) {
					WPMST()->render->add_style( 'wpmtst-rating-display' );
					break;
				}
			}
		}
	}

	public function set_stylesheet( $handle = '' ) {
		$this->stylesheet = $handle;
	}

	public function get_stylesheet() {
		return $this->stylesheet;
	}

}

endif;
