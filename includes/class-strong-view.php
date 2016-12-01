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
	 * The number of posts.
	 *
	 * @var int
	 */
	public $post_count;
	public $found_posts;

	/**
	 * The number of pages.
	 *
	 * @var int
	 */
	public $page_count = 1;

	/**
	 * Strong_View constructor.
	 *
	 * @param array $atts
	 */
	public function __construct( $atts = array() ) {
		$this->atts = apply_filters( 'wpmtst_view_atts', $atts );
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
	public function process() {
		$this->build_query();
		$this->build_classes();

		$this->find_stylesheet();
		$this->has_slideshow();
		$this->has_stars();
		$this->has_pagination();
		$this->has_layouts();
		$this->load_dependent_scripts();
		$this->load_extra_stylesheets();

		wp_reset_postdata();
	}

	/**
	 * Build the view.
	 */
	public function build() {
		/**
		 * Reset any hooks and filters that may have been set by other Views on the page.
		 *
		 * @since 2.11.4
		 */
		remove_action( 'wpmtst_after_testimonial', 'wpmtst_excerpt_more_full_post' );

		do_action( 'wpmtst_view_build_before', $this );

		$this->build_query();
		$this->build_classes();

		$this->find_stylesheet();
		$this->has_slideshow();
		$this->has_stars();
		$this->has_pagination();
		$this->has_layouts();
		$this->load_dependent_scripts();
		$this->load_extra_stylesheets();
		$this->custom_background();

		/**
		 * Add filters.
		 */
		add_filter( 'get_avatar', 'wpmtst_get_avatar', 10, 3 );
		add_filter( 'embed_defaults', 'wpmtst_embed_size', 10, 2 );

		/**
		 * Add actions.
		 */

		// Standard pagination
		if ( $this->atts['pagination'] && 'standard' == $this->atts['pagination_type'] ) {
			if ( false !== strpos( $this->atts['nav'], 'before' ) ) {
				add_action( 'wpmtst_view_header', 'wpmtst_standard_pagination' );
			}
			if ( false !== strpos( $this->atts['nav'], 'after' ) ) {
				add_action( 'wpmtst_view_footer', 'wpmtst_standard_pagination' );
			}
		}

		// Read more page
		add_action( $this->atts['more_page_hook'] , 'wpmtst_read_more_page' );

		/**
		 * Locate template.
		 */
		$this->template_file = WPMST()->templates->get_template_attr( $this->atts, 'template' );

		/**
		 * Allow add-ons to hijack the output generation.
		 */
		$query = $this->query;
		if ( has_filter( 'wpmtst_render_view_template' ) ) {
			$html = apply_filters( 'wpmtst_render_view_template', '', $this );
		} else {
			ob_start();
			/** @noinspection PhpIncludeInspection */
			include( $this->template_file );
			$html = ob_get_clean();
		}
		// TODO apply content filters

		/**
		 * Remove filters.
		 */
		remove_filter( 'get_avatar', 'wpmtst_get_avatar' );
		remove_filter( 'embed_defaults', 'wpmtst_embed_size' );

		/**
		 * Remove actions.
		 */
		remove_action( $this->atts['more_page_hook'], 'wpmtst_read_more_page' );
		remove_action( 'wpmtst_view_header', 'wpmtst_standard_pagination' );
		remove_action( 'wpmtst_view_footer', 'wpmtst_standard_pagination' );

		/**
		 * Hook to enqueue scripts.
		 */
		do_action( 'wpmtst_view_rendered', $this->atts );

		wp_reset_postdata();

		$this->html = apply_filters( 'strong_view_html', $html, $this );

	}

	/**
	 * Build our query based on view attributes.
	 *
	 * @return WP_Query
	 */
	public function build_query() {
		$categories = apply_filters( 'wpmtst_l10n_cats', explode( ',', $this->atts['category'] ) );
		$ids        = explode( ',', $this->atts['id'] );

		$args = array(
			'post_type'   => 'wpm-testimonial',
			'post_status' => 'publish',
		);

		if ( $this->atts['pagination'] && 'standard' == $this->atts['pagination_type'] ) {
			$args['posts_per_page'] = $this->atts['per_page'];
			$args['paged']          = wpmtst_get_paged();
		}
		else {
			$args['posts_per_page'] = -1;
			$args['paged']          = null;
		}

		// id overrides category
		if ( $this->atts['id'] ) {
			$args['post__in'] = $ids;
		}
		elseif ( $this->atts['category'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'wpm-testimonial-category',
					'field'    => 'id',
					'terms'    => $categories
				)
			);
		}

		// order by
		if ( $this->atts['menu_order'] ) {
			$args['orderby'] = 'menu_order';
			$args['order']   = 'ASC';
		}
		else {
			$args['orderby'] = 'post_date';
			if ( $this->atts['newest'] ) {
				$args['order'] = 'DESC';
			}
			else {
				$args['order'] = 'ASC';
			}
		}

		// For Post Types Order plugin
		$args['ignore_custom_sort'] = true;

		$query = new WP_Query( apply_filters( 'wpmtst_query_args', $args ) );

		/**
		 * Shuffle array in PHP instead of SQL.
		 *
		 * @since 1.16
		 */
		if ( $this->atts['random'] ) {
			shuffle( $query->posts );
		}

		/**
		 * Extract slice of array, which may be shuffled.
		 *
		 * Use lesser value: requested count or actual count.
		 * Thanks chestozo.
		 * @link https://github.com/cdillon/strong-testimonials/pull/5
		 *
		 * @since 1.16.1
		 */
		if ( ! $this->atts['all'] && $this->atts['count'] > 0 ) {
			$count              = min( $this->atts['count'], count( $query->posts ) );
			$query->posts       = array_slice( $query->posts, 0, $count );
			$query->post_count  = $count;
			$query->found_posts = $count;
		}

		$this->post_count  = $query->post_count;
		$this->found_posts = $query->found_posts;

		WPMST()->set_query( $query );

		$this->query = $query;

		if ( $this->atts['pagination'] ) {
			if ( $this->query->post_count <= $this->atts['per_page'] ) {
				$this->atts['pagination'] = apply_filters( 'wpmtst_use_default_pagination', true, $this->atts );
			}
		}

	}

	/**
	 * Build class list based on view attributes.
	 *
	 * This must happen after the query.
	 */
	public function build_classes() {

		$options = get_option( 'wpmtst_view_options' );

		$container_class_list = array(
			'strong-view-id-' . $this->atts['view'],
			$this->get_template_css_class(),
		);

		if ( is_rtl() ) {
			$container_class_list[] = 'rtl';
		}

		if ( $this->atts['class'] ) {
			$container_class_list[] = $this->atts['class'];
		}

		$container_data_list = array();
		$content_class_list  = array();
		$post_class_list     = array( 'testimonial' );

		// excerpt overrides length
		if ( $this->atts['excerpt'] ) {
			$post_class_list[] = 'excerpt';
		}

		/**
		 * Slideshow
		 */
		if ( $this->atts['slideshow'] ) {

			$settings = $this->atts['slideshow_settings'];

			$container_class_list[] = 'slider-container';

			$container_class_list[] = 'slider-mode-' . $settings['effect'];

			if ( $settings['adapt_height'] ) {
				$container_class_list[] = 'slider-adaptive';
			}
			elseif ( $settings['stretch'] ) {
				$container_class_list[] = 'slider-stretch';
			}


			$nav_methods   = $options['slideshow_nav_method'];
			$nav_styles    = $options['slideshow_nav_style'];
			$control       = $settings['controls_type'];
			$control_style = $settings['controls_style'];
			$pager         = $settings['pager_type'];
			$pager_style   = $settings['pager_style'];

			// Controls
			if ( isset( $nav_methods['controls'][ $control ]['class'] ) && $nav_methods['controls'][ $control ]['class'] ) {
				$container_class_list[] = $nav_methods['controls'][ $control ]['class'];
			}

			if ( 'none' != $control ) {
				if ( isset( $nav_styles['controls'][ $control_style ]['class'] ) && $nav_styles['controls'][ $control_style ]['class'] ) {
					$container_class_list[] = $nav_styles['controls'][ $control_style ]['class'];
				}
			}

			// Pager
			if ( isset( $nav_methods['pager'][ $pager ]['class'] ) && $nav_methods['pager'][ $pager ]['class'] ) {
				$container_class_list[] = $nav_methods['pager'][ $pager ]['class'];
			}

			if ( 'none' != $pager ) {
				if ( isset( $nav_styles['pager'][ $pager_style ]['class'] ) && $nav_styles['pager'][ $pager_style ]['class'] ) {
					$container_class_list[] = $nav_styles['pager'][ $pager_style ]['class'];
				}
			}

			// Position
			if ( 'none' != $pager || ( 'none' != $control && 'sides' != $control ) ) {
				$container_class_list[] = 'nav-position-' . $settings['nav_position'];
			}

			$container_data_list['slider-var'] = $this->slideshow_signature( $this->atts );

			$content_class_list[] = 'slider-wrapper';

			$post_class_list[] = 't-slide';

		}
		else {

			if ( $this->atts['pagination'] && 'masonry' != $this->atts['layout'] ) {
				$content_class_list[] = 'strong-paginated';
				$content_class_list[] = WPMST()->get_pager_signature( $this->atts );
			}

			// layouts
			$content_class_list[] = 'strong-' . ( $this->atts['layout'] ? $this->atts['layout'] : 'normal' );
			$content_class_list[] = 'columns-' . ( $this->atts['layout'] ? $this->atts['column_count'] : '1' );

		}

		/**
		 * Filter classes.
		 */
		$this->atts['container_data']  = apply_filters( 'wpmtst_view_container_data', $container_data_list );
		$this->atts['container_class'] = join( ' ', apply_filters( 'wpmtst_view_container_class', $container_class_list ) );
		$this->atts['content_class']   = join( ' ', apply_filters( 'wpmtst_view_content_class', $content_class_list ) );
		$this->atts['post_class']      = join( ' ', apply_filters( 'wpmtst_view_post_class', $post_class_list ) );

		/**
		 * Store updated atts.
		 */
		WPMST()->set_atts( $this->atts );

	}

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
	 * Layouts
	 *
	 * @since 2.16.0 In Strong_View class.
	 */
	public function has_layouts() {
		if ( 'masonry' == $this->atts['layout'] ) {
			WPMST()->add_script( 'wpmtst-masonry-script' );
			WPMST()->add_style( 'wpmtst-masonry-style' );
		}
		elseif ( 'columns' == $this->atts['layout'] ) {
			WPMST()->add_style( 'wpmtst-columns-style' );
		}
		elseif ( 'grid' == $this->atts['layout'] ) {
			WPMST()->add_script( 'wpmtst-grid-script' );
			WPMST()->add_style( 'wpmtst-grid-style' );
		}
	}

	/**
	 * Pagination
	 *
	 * @since 2.16.0 In Strong_View class.
	 */
	public function has_pagination() {
		if ( $this->atts['pagination'] && 'simple' == $this->atts['pagination_type'] ) {
			WPMST()->add_script( 'wpmtst-pager-script' );
			$sig  = WPMST()->pager_signature( $this->atts );
			$args = WPMST()->pager_args( $this->atts );
			WPMST()->add_script_var( 'wpmtst-pager-script', $sig, $args );
		}

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
				}
			}
		}
	}

	/**
	 * Slideshow
	 *
	 * @since 2.16.0 In Strong_View class.
	 */
	public function has_slideshow() {
		if ( $this->atts['slideshow'] ) {
			$plugin_version = get_option( 'wpmtst_plugin_version' );

			$settings          = $this->atts['slideshow_settings'];
			$not_full_controls = ( 'none' != $settings['controls_type'] || 'full' != $settings['controls_type'] );

			/** Controls with or without Pagination */
			if ( isset( $settings['controls_type'] ) && 'none' != $settings['controls_type'] ) {

				$filename = 'slider-controls-' . $settings['controls_type'] . '-' . $settings['controls_style'];

				if ( 'full' != $settings['controls_type'] ) {
					if ( isset( $settings['pager_style'] ) && 'none' != $settings['pager_style'] ) {
						$filename .= '-pager-' . $settings['pager_style'];
					}
				}

				if ( file_exists( WPMTST_PUBLIC . "css/$filename.css" ) ) {
					wp_register_style( "wpmtst-$filename", WPMTST_PUBLIC_URL . "css/$filename.css", array(), $plugin_version );
					WPMST()->add_style( "wpmtst-$filename" );
				}

			}
			elseif ( $not_full_controls ) {

				/** Pagination only */
				if ( isset( $settings['pager_type'] ) && 'none' != $settings['pager_type'] ) {

					//TODO Adapt for multiple pager types (only one right now).
					$filename = 'slider-pager-' . $settings['pager_style'] ;

					if ( file_exists( WPMTST_PUBLIC . "css/$filename.css" ) ) {
						wp_register_style( "wpmtst-$filename", WPMTST_PUBLIC_URL . "css/$filename.css", array(), $plugin_version );
						WPMST()->add_style( "wpmtst-$filename" );
					}

				}

			}

			WPMST()->add_script( 'wpmtst-slider' );
			$sig  = $this->slideshow_signature( $this->atts );
			$args = $this->slideshow_args( $this->atts );
			wpmst()->add_script_var( 'wpmtst-slider', $sig, $args );
		}
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
	private function slideshow_signature( $atts ) {
		return 'strong_slider_id_' . $atts['view'];
	}

	/**
	 * Assemble slideshow settings.
	 *
	 * @since 2.7.0
	 * @private
	 * @param $atts
	 *
	 * @return string|bool
	 */
	private function slideshow_args( $atts ) {

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

		$plugin_version = get_option( 'wpmtst_plugin_version' );

		$stylesheet = WPMST()->templates->get_template_attr( $this->atts, 'stylesheet', false );
		if ( $stylesheet ) {
			$handle = 'testimonials-' . str_replace( ':', '-', $this->atts['template'] );
			wp_register_style( $handle, $stylesheet, array(), $plugin_version );
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
	 * Construct template CSS class name.
	 *
	 * @since 2.11.0
	 *
	 * @return mixed
	 */
	public function get_template_css_class() {
		return str_replace( ':', '-', str_replace( ':content', '', $this->atts['template'] ) );
	}

	public function custom_background( $handle = 'wpmtst-custom-style' ) {
		$background = $this->atts['background'];
		if ( ! isset( $background['type'] ) ) return;

		$c1 = '';
		$c2 = '';

		switch ( $background['type'] ) {
			case 'preset':
				$preset = wpmtst_get_background_presets( $background['preset'] );
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

		if ( ! wp_style_is( $handle ) ) {
			wp_enqueue_style( $handle );
		}

		// Includes special handling for Large Widget template.
		// TODO Add option to include background for all templates.
		if ( $c1 && $c2 ) {

			$gradient = self::gradient_rules( $c1, $c2 );
			wp_add_inline_style( $handle, ".strong-view-id-{$this->atts['view']} .testimonial-inner { $gradient }" );
			if ( 'large-widget:widget' == WPMST()->atts( 'template' ) ) {
				wp_add_inline_style( $handle, ".strong-view-id-{$this->atts['view']} .readmore-page { background: $c2 }" );
			}

		} elseif ( $c1 ) {

			wp_add_inline_style( $handle, ".strong-view-id-{$this->atts['view']} .testimonial-inner { background: $c1; }" );
			if ( 'large-widget:widget' == WPMST()->atts( 'template' ) ) {
				wp_add_inline_style( $handle, ".strong-view-id-{$this->atts['view']} .readmore-page { background: $c1 }" );
			}

		}
	}

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

}

endif;
