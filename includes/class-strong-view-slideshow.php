<?php
/**
 * View Slideshow Mode class.
 *
 * @since 2.16.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_View_Slideshow' ) ) :

class Strong_View_Slideshow extends Strong_View_Display {

	/**
	 * Strong_View constructor.
	 *
	 * @param array $atts
	 */
	public function __construct( $atts = array() ) {
		parent::__construct();
		$this->atts = apply_filters( 'wpmtst_view_atts', $atts );
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

		$this->load_dependent_scripts();
		$this->load_extra_stylesheets();

		// If we can preprocess, we can add the inline style in the <head>.
		add_action( 'wp_enqueue_scripts', array( $this, 'add_custom_style' ), 20 );

		wp_reset_postdata();
	}

	/**
	 * Build the view.
	 */
	public function build() {
		// May need to remove any hooks or filters that were set by other Views on the page.

		do_action( 'wpmtst_view_build_before', $this );

		$this->build_query();
		$this->build_classes();

		$this->find_stylesheet();
		$this->has_slideshow();
		$this->has_stars();

		$this->load_dependent_scripts();
		$this->load_extra_stylesheets();
		$this->custom_background();

		/*
		 * If we cannot preprocess, add the inline style to the footer.
		 * If we were able to preprocess, this will not duplicate the code
		 * since `wpmtst-custom-style` was already enqueued (I think).
		 */
		add_action( 'wp_footer', array( $this, 'add_custom_style' ) );

		/**
		 * Add filters.
		 */
		$this->add_content_filters();
		add_filter( 'get_avatar', 'wpmtst_get_avatar', 10, 3 );
		add_filter( 'embed_defaults', 'wpmtst_embed_size', 10, 2 );

		/**
		 * Add actions.
		 */

		// Read more page
		add_action( $this->atts['more_page_hook'], 'wpmtst_read_more_page' );

		/**
		 * Locate template.
		 */
		$this->template_file = WPMST()->templates->get_template_attr( $this->atts, 'template' );

		/**
		 * Allow add-ons to hijack the output generation.
		 */
		$query = $this->query;
		$atts  = $this->atts;
		if ( has_filter( 'wpmtst_render_view_template' ) ) {
			$html = apply_filters( 'wpmtst_render_view_template', '', $this );
		} else {
			ob_start();
			/** @noinspection PhpIncludeInspection */
			include( $this->template_file );
			$html = ob_get_clean();
		}

		/**
		 * Remove filters.
		 */
		$this->remove_content_filters();
		remove_filter( 'get_avatar', 'wpmtst_get_avatar' );
		remove_filter( 'embed_defaults', 'wpmtst_embed_size' );

		/**
		 * Remove actions.
		 */
		remove_action( $this->atts['more_page_hook'], 'wpmtst_read_more_page' );

		/**
		 * Hook to enqueue scripts.
		 */
		do_action( 'wpmtst_view_rendered', $this->atts );

		wp_reset_postdata();

		$this->html = apply_filters( 'strong_view_html', $html, $this );

	}

	/**
	 * Build our query based on view attributes.
	 */
	public function build_query() {
		$ids = explode( ',', $this->atts['id'] );

		$args = array(
			'post_type'      => 'wpm-testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'paged'          => null,
		);

		// id overrides category
		if ( $this->atts['id'] ) {
			$args['post__in'] = $ids;
		}
		elseif ( $this->atts['category'] ) {
			$categories        = apply_filters( 'wpmtst_l10n_cats', explode( ',', $this->atts['category'] ) );
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'wpm-testimonial-category',
					'field'    => 'id',
					'terms'    => $categories,
				),
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

		$query = new WP_Query( apply_filters( 'wpmtst_query_args', $args, $this->atts ) );

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
		 *
		 * @link  https://github.com/cdillon/strong-testimonials/pull/5
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

			$content_class_list[] = 'wpmslider-wrapper';

			$post_class_list[] = 't-slide';

		}

		/**
		 * Filter classes.
		 */
		$this->atts['container_data']  = apply_filters( 'wpmtst_view_container_data', $container_data_list, $this->atts );
		$this->atts['container_class'] = join( ' ', apply_filters( 'wpmtst_view_container_class', $container_class_list, $this->atts ) );
		$this->atts['content_class']   = join( ' ', apply_filters( 'wpmtst_view_content_class', $content_class_list, $this->atts ) );
		$this->atts['post_class']      = join( ' ', apply_filters( 'wpmtst_view_post_class', $post_class_list, $this->atts ) );

		/**
		 * Store updated atts.
		 */
		WPMST()->set_atts( $this->atts );

	}

	/**
	 * Slideshow
	 *
	 * @since 2.16.0 In Strong_View class.
	 */
	public function has_slideshow() {

		if ( apply_filters( 'wpmtst_load_font_awesome', true ) ) {
			WPMST()->add_style( 'wpmtst-font-awesome' );
		}

		$settings          = $this->atts['slideshow_settings'];
		$not_full_controls = ( 'none' != $settings['controls_type'] || 'full' != $settings['controls_type'] );

		/*
		 * Controls with or without Pagination
		 */
		if ( isset( $settings['controls_type'] ) && 'none' != $settings['controls_type'] ) {

			$filename = 'slider-controls-' . $settings['controls_type'] . '-' . $settings['controls_style'];

			if ( 'full' != $settings['controls_type'] ) {
				if ( isset( $settings['pager_style'] ) && 'none' != $settings['pager_style'] ) {
					$filename .= '-pager-' . $settings['pager_style'];
				}
			}

			if ( file_exists( WPMTST_PUBLIC . "css/$filename.css" ) ) {
				wp_register_style( "wpmtst-$filename", WPMTST_PUBLIC_URL . "css/$filename.css", array(), $this->plugin_version );
				WPMST()->add_style( "wpmtst-$filename" );
			}

		} elseif ( $not_full_controls ) {

			/*
			 * Pagination only
			 */
			if ( isset( $settings['pager_type'] ) && 'none' != $settings['pager_type'] ) {

				//TODO Adapt for multiple pager types (only one right now).
				$filename = 'slider-pager-' . $settings['pager_style'];

				if ( file_exists( WPMTST_PUBLIC . "css/$filename.css" ) ) {
					wp_register_style( "wpmtst-$filename", WPMTST_PUBLIC_URL . "css/$filename.css", array(), $this->plugin_version );
					WPMST()->add_style( "wpmtst-$filename" );
				}

			}

		}

		$sig  = $this->slideshow_signature();
		$args = $this->slideshow_args();
		WPMST()->add_script( 'wpmtst-slider' );
		WPMST()->add_script_var( 'wpmtst-slider', $sig, $args );
	}

	/**
	 * Create unique slideshow signature.
	 *
	 * @since 2.7.0
	 * @private
	 *
	 * @return string
	 */
	private function slideshow_signature() {
		return 'strong_slider_id_' . $this->atts['view'];
	}

	/**
	 * Assemble slideshow settings.
	 *
	 * @since 2.7.0
	 * @private
	 *
	 * @return array
	 */
	private function slideshow_args() {

		$view_options = apply_filters( 'wpmtst_view_options', get_option( 'wpmtst_view_options' ) );

		$args = array(
			'mode'                => $this->atts['slideshow_settings']['effect'],
			'speed'               => $this->atts['slideshow_settings']['speed'] * 1000,
			'pause'               => $this->atts['slideshow_settings']['pause'] * 1000,
			'autoHover'           => $this->atts['slideshow_settings']['auto_hover'] ? 1 : 0,
			'autoStart'           => $this->atts['slideshow_settings']['auto_start'] ? 1 : 0,
			'stopAutoOnClick'     => $this->atts['slideshow_settings']['stop_auto_on_click'] ? 1 : 0,
			'adaptiveHeight'      => $this->atts['slideshow_settings']['adapt_height'] ? 1 : 0,
			'adaptiveHeightSpeed' => $this->atts['slideshow_settings']['adapt_height_speed'] * 1000,
			'controls'            => 0,
			'autoControls'        => 0,
			'pager'               => 0,
		);
		if ( ! $this->atts['slideshow_settings']['adapt_height'] ) {
			$args['stretch'] = $this->atts['slideshow_settings']['stretch'] ? 1 : 0;
		}

		// Controls
		$options         = $view_options['slideshow_nav_method']['controls'];
		$control_setting = $this->atts['slideshow_settings']['controls_type'];
		if ( ! $control_setting ) {
			$control_setting = 'none';
		}
		if ( isset( $options[ $control_setting ] ) && isset( $options[ $control_setting ]['args'] ) ) {
			$args = array_merge( $args, $options[ $control_setting ]['args'] );
		}

		if ( 'none' != $control_setting ) {
			$options = $view_options['slideshow_nav_style']['controls'];
			$setting = $this->atts['slideshow_settings']['controls_style'];
			if ( ! $setting ) {
				$setting = 'none';
			}
			if ( isset( $options[ $setting ] ) && isset( $options[ $setting ]['args'] ) ) {
				$args = array_merge( $args, $options[ $setting ]['args'] );
			}
		}

		// Pager
		$options       = $view_options['slideshow_nav_method']['pager'];
		$pager_setting = $this->atts['slideshow_settings']['pager_type'];
		if ( ! $pager_setting ) {
			$pager_setting = 'none';
		}
		if ( isset( $options[ $pager_setting ] ) && isset( $options[ $pager_setting ]['args'] ) ) {
			$args = array_merge( $args, $options[ $pager_setting ]['args'] );
		}

		if ( 'none' != $pager_setting ) {
			$options = $view_options['slideshow_nav_style']['pager'];
			$setting = $this->atts['slideshow_settings']['pager_style'];
			if ( ! $setting ) {
				$setting = 'none';
			}
			if ( isset( $options[ $setting ] ) && isset( $options[ $setting ]['args'] ) ) {
				$args = array_merge( $args, $options[ $setting ]['args'] );
			}
		}

		return $args;
	}

}

endif;
