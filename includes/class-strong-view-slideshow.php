<?php
/**
 * View Slideshow Mode class.
 *
 * @since 2.16.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Strong_View_Slideshow' ) ) :

	class Strong_View_Slideshow extends Strong_View_Display {

		/**
		 * Strong_View constructor.
		 *
		 * @param array $atts
		 */
		public function __construct( $atts = array() ) {
			parent::__construct( $atts );
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
			$this->has_readmore();
				$this->has_lazyload();

			$this->load_extra_stylesheets();

			// If we can preprocess, we can add the inline style in the <head>.
			add_action( 'wp_enqueue_scripts', array( $this, 'add_custom_style' ), 20 );

			do_action( 'wpmtst_view_processed' );
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
				$this->has_readmore();

			$this->load_dependent_scripts();
			$this->load_extra_stylesheets();

			// If we cannot preprocess, add the inline style to the footer.
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
			$this->template_file = apply_filters( 'wpmtst_view_template_file_slideshow', WPMST()->templates->get_template_attr( $this->atts, 'template' ) );

			/**
			 * Allow add-ons to hijack the output generation.
			 */
			$query = $this->query;
			$atts  = $this->atts;
			if ( has_filter( 'wpmtst_render_view_template' ) ) {
				$html = apply_filters( 'wpmtst_render_view_template', '', $this );
			} else {

				/**
				 * Gutenberg. Yay.
				 * @since 2.31.9
				 */
				global $post;
				$post_before = $post;

				ob_start();
				/** @noinspection PhpIncludeInspection */
				include $this->template_file;
				$html = ob_get_clean();

				$post = $post_before;

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
		 * Build class list based on view attributes.
		 *
		 * This must happen after the query.
		 * TODO DRY
		 */
		public function build_classes() {
			$options = get_option( 'wpmtst_view_options' );

			$container_class_list = array( 'strong-view-id-' . $this->atts['view'] );
			$container_class_list = array_merge( $container_class_list, $this->get_template_css_class() );

			if ( is_rtl() ) {
				$container_class_list[] = 'rtl';
			}

			if ( $this->atts['class'] ) {
				$container_class_list[] = $this->atts['class'];
			}

			$container_data_list = array(
				'count' => $this->post_count,
			);

			$content_class_list = array();

			$post_class_list = array( 'wpmtst-testimonial testimonial' );

			if ( 'excerpt' === $this->atts['content'] ) {
				$post_class_list[] = 'excerpt';
			}

			/**
			 * Slideshow
			 */
			$settings = $this->atts['slideshow_settings'];

			$container_class_list[] = 'slider-container';

			if ( 'show_multiple' === $settings['type'] ) {
				$container_class_list[] = 'carousel';
			}

			$container_class_list[] = 'slider-mode-' . $settings['effect'];

			if ( $settings['adapt_height'] ) {
				$container_class_list[] = 'slider-adaptive';
			} elseif ( $settings['stretch'] ) {
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
				if ( 'sides' === $control ) {
					if ( 'show_single' === $settings['type'] ) {
						$container_class_list[] = $nav_methods['controls'][ $control ]['class'];
					} else {
						$container_class_list[] = $nav_methods['controls'][ $control ]['class'] . '-outside';
					}
				}
			}

			if ( 'none' !== $control ) {
				if ( isset( $nav_styles['controls'][ $control_style ]['class'] ) && $nav_styles['controls'][ $control_style ]['class'] ) {
					$container_class_list[] = $nav_styles['controls'][ $control_style ]['class'];
				}
			}

			// Pager
			if ( isset( $nav_methods['pager'][ $pager ]['class'] ) && $nav_methods['pager'][ $pager ]['class'] ) {
				$container_class_list[] = $nav_methods['pager'][ $pager ]['class'];
			}

			if ( 'none' !== $pager ) {
				if ( isset( $nav_styles['pager'][ $pager_style ]['class'] ) && $nav_styles['pager'][ $pager_style ]['class'] ) {
					$container_class_list[] = $nav_styles['pager'][ $pager_style ]['class'];
				}
			}

			// Position
			// TODO Simplify logic.
			if ( 'none' !== $pager || ( 'none' !== $control && 'sides' !== $control ) ) {
				if ( 'show_multiple' === $settings['type'] ) {
					$settings['nav_position'] = 'outside';
				}
				$container_class_list[] = 'nav-position-' . $settings['nav_position'];
			}

			$container_data_list['slider-var'] = $this->slideshow_signature();
			$container_data_list['state']      = 'idle';

			$content_class_list[] = 'wpmslider-content';

			$post_class_list[] = 't-slide';

			/**
			 * Filter classes.
			 */
			$this->atts['container_data']  = apply_filters( 'wpmtst_view_container_data', $container_data_list, $this->atts );
			$this->atts['container_class'] = implode( ' ', apply_filters( 'wpmtst_view_container_class', array_filter( $container_class_list ), $this->atts ) );
			$this->atts['content_class']   = implode( ' ', apply_filters( 'wpmtst_view_content_class', array_filter( $content_class_list ), $this->atts ) );
			$this->atts['post_class']      = implode( ' ', apply_filters( 'wpmtst_view_post_class', array_filter( $post_class_list ), $this->atts ) );

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

			$settings          = $this->atts['slideshow_settings'];
			$not_full_controls = ( 'none' !== $settings['controls_type'] || 'full' !== $settings['controls_type'] );

			/*
			 * Controls with or without Pagination
			 */
			if ( isset( $settings['controls_type'] ) && 'none' !== $settings['controls_type'] ) {

				$controls_type = $settings['controls_type'];
				if ( 'sides' === $controls_type && 'show_multiple' === $settings['type'] ) {
					$controls_type .= '-outside';
				}

				$filename = 'slider-controls-' . $controls_type . '-' . $settings['controls_style'];

				if ( 'full' !== $settings['controls_type'] ) {
					if ( isset( $settings['pager_style'] ) && 'none' !== $settings['pager_type'] ) {
						$filename .= '-pager-' . $settings['pager_style'];
					}
				}

				if ( file_exists( WPMTST_PUBLIC . "css/$filename.css" ) ) {
					wp_register_style( "wpmtst-$filename", WPMTST_PUBLIC_URL . "css/$filename.css", array(), $this->plugin_version );
					WPMST()->render->add_style( "wpmtst-$filename" );
				}
			} elseif ( $not_full_controls ) {

				/*
				 * Pagination only
				 */
				if ( isset( $settings['pager_type'] ) && 'none' !== $settings['pager_type'] ) {

					//TODO Adapt for multiple pager types (only one right now).
					$filename = 'slider-pager-' . $settings['pager_style'];

					if ( file_exists( WPMTST_PUBLIC . "css/$filename.css" ) ) {
						wp_register_style( "wpmtst-$filename", WPMTST_PUBLIC_URL . "css/$filename.css", array(), $this->plugin_version );
						WPMST()->render->add_style( "wpmtst-$filename" );
					}
				}
			}

			WPMST()->render->add_script( 'wpmtst-slider' );
			WPMST()->render->add_script_var( 'wpmtst-slider', $this->slideshow_signature(), $this->slideshow_args() );
			WPMST()->render->add_script( 'wpmtst-controller' );
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
			$options        = get_option( 'wpmtst_options' );
			$view_options   = apply_filters( 'wpmtst_view_options', get_option( 'wpmtst_view_options' ) );
			$compat_options = get_option( 'wpmtst_compat_options' );

			/**
			 * Compatibility with lazy loading and use of imagesLoaded.
			 *
			 * @since 2.31.0 As user-configurable.
			 */
			$compat  = array();
			$enabled = false;
			$pairs   = array();

			// Presets
			// Flatsome theme
			if ( class_exists( 'FL_LazyLoad_Images' ) && get_theme_mod( 'lazy_load_images' ) ) {
				$enabled = true;
				$pairs[] = array(
					'start'  => 'lazy-load',
					'finish' => '',
				);
			}

			// User settings
			if ( $compat_options['lazyload']['enabled'] ) {
				$enabled = true;
				foreach ( $compat_options['lazyload']['classes'] as $key => $pair ) {
					$pairs[] = $pair;
				}
			}

			// Bring together the presets and user settings.
			$compat['lazyload'] = array(
				'active'  => $enabled,
				'classes' => $pairs,
			);

			// Convert breakpoint variable names
			// TODO Refactor to make this unnecessary.
			$new_breakpoints = array();

			// Fallback
			$new_breakpoints['single'] = array(
				'maxSlides'   => $this->atts['slideshow_settings']['show_single']['max_slides'],
				'moveSlides'  => $this->atts['slideshow_settings']['show_single']['move_slides'],
				'slideMargin' => $this->atts['slideshow_settings']['show_single']['margin'],
			);

			$breakpoints = $this->atts['slideshow_settings']['breakpoints'];
			foreach ( $breakpoints as $key => $breakpoint ) {
				$new_breakpoints['multiple'][ $key ] = array(
					'width'       => $breakpoints[ $key ]['width'],
					'maxSlides'   => $breakpoints[ $key ]['max_slides'],
					'moveSlides'  => $breakpoints[ $key ]['move_slides'],
					'slideMargin' => $breakpoints[ $key ]['margin'],
				);
			}

			$args = array(
				'mode'                => isset( $this->atts['slideshow_settings']['effect'] ) ? $this->atts['slideshow_settings']['effect'] : 'fade',
				'speed'               => isset( $this->atts['slideshow_settings']['speed'] ) ? $this->atts['slideshow_settings']['speed'] * 1000 : 1000,
				'pause'               => isset( $this->atts['slideshow_settings']['pause'] ) ? $this->atts['slideshow_settings']['pause'] * 1000 : 8000,
				'autoHover'           => ( isset( $this->atts['slideshow_settings']['auto_hover'] ) && $this->atts['slideshow_settings']['auto_hover'] ) ? 1 : 0,
				'autoStart'           => ( isset( $this->atts['slideshow_settings']['auto_start'] ) && $this->atts['slideshow_settings']['auto_start'] ) ? 1 : 0,
				'infiniteLoop'        => ( isset( $this->atts['slideshow_settings']['continuous_sliding'] ) && $this->atts['slideshow_settings']['continuous_sliding'] ) ? 1 : 0,
				'stopAutoOnClick'     => ( isset( $this->atts['slideshow_settings']['stop_auto_on_click'] ) && $this->atts['slideshow_settings']['stop_auto_on_click'] ) ? 1 : 0,
				'adaptiveHeight'      => ( isset( $this->atts['slideshow_settings']['adapt_height'] ) && $this->atts['slideshow_settings']['adapt_height'] ) ? 1 : 0,
				'adaptiveHeightSpeed' => isset( $this->atts['slideshow_settings']['adapt_height_speed'] ) ? $this->atts['slideshow_settings']['adapt_height_speed'] * 1000 : 500,
				'controls'            => 0,
				'autoControls'        => 0,
				'pager'               => 0,
				'slideCount'          => $this->post_count,
				'debug'               => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && apply_filters( 'debug_strong_slider', true ),
				'compat'              => $compat,
				'touchEnabled'        => $options['touch_enabled'],
				'type'                => isset( $this->atts['slideshow_settings']['type'] ) ? $this->atts['slideshow_settings']['type'] : 'show_single',
				'breakpoints'         => $new_breakpoints,
			);

			if ( ! $this->atts['slideshow_settings']['adapt_height'] ) {
				$args['stretch'] = $this->atts['slideshow_settings']['stretch'] ? 1 : 0;
			}

			/**
			 * Controls
			 */
			$options         = $view_options['slideshow_nav_method']['controls'];
			$control_setting = $this->atts['slideshow_settings']['controls_type'];
			if ( ! $control_setting ) {
				$control_setting = 'none';
			}
			if ( isset( $options[ $control_setting ] ) && isset( $options[ $control_setting ]['args'] ) ) {
				$args['controls'] = 1;
				$args             = array_merge( $args, $options[ $control_setting ]['args'] );
			}

			if ( 'none' !== $control_setting ) {
				$options = $view_options['slideshow_nav_style']['controls'];
				$setting = $this->atts['slideshow_settings']['controls_style'];
				if ( ! $setting ) {
					$setting = 'none';
				}

				/**
				 * Quick fix for ticket 12014 to translate slider text controls.
				 * TODO Refactor; see fix-i18n branch in translations.strong.test.
				 *
				 * @since 2.32.1
				 */
				if ( 'text' === $setting ) {
					$options['text']['args'] = array(
						'startText' => esc_html_x( 'Play', 'slideshow control', 'strong-testimonials' ),
						'stopText'  => esc_html_x( 'Pause', 'slideshow control', 'strong-testimonials' ),
						'prevText'  => esc_html_x( 'Previous', 'slideshow_control', 'strong-testimonials' ),
						'nextText'  => esc_html_x( 'Next', 'slideshow_control', 'strong-testimonials' ),
					);
				}

				if ( isset( $options[ $setting ] ) && isset( $options[ $setting ]['args'] ) ) {
					$args = array_merge( $args, $options[ $setting ]['args'] );
				}
			}

			/**
			 * Pager
			 */
			$options       = $view_options['slideshow_nav_method']['pager'];
			$pager_setting = $this->atts['slideshow_settings']['pager_type'];
			if ( ! $pager_setting ) {
				$pager_setting = 'none';
			}
			if ( isset( $options[ $pager_setting ] ) && isset( $options[ $pager_setting ]['args'] ) ) {
				$args = array_merge( $args, $options[ $pager_setting ]['args'] );
			}

			if ( 'none' !== $pager_setting ) {
				$options = $view_options['slideshow_nav_style']['pager'];
				$setting = $this->atts['slideshow_settings']['pager_style'];
				if ( ! $setting ) {
					$setting = 'none';
				}
				if ( isset( $options[ $setting ] ) && isset( $options[ $setting ]['args'] ) ) {
					$args['pager'] = 1;
					$args          = array_merge( $args, $options[ $setting ]['args'] );
				}
			}

			$args['nextUrl'] = __( 'next-slide', 'strong-testimonials' );
			$args['prevUrl'] = __( 'previous-slide', 'strong-testimonials' );

			return array( 'config' => apply_filters( 'wpmtst_slider_args', $args, $this->atts ) );
		}

		/**
	 * Lazy Load
	 *
	 * @since 2.40.4
	 */
		public function has_lazyload() {
			if ( ! function_exists( 'wp_lazy_loading_enabled' ) || ! apply_filters( 'wp_lazy_loading_enabled', true, 'img', 'strong_testimonials_has_lazyload' ) ) {
				$options = get_option( 'wpmtst_options' );
				if ( isset( $options['lazyload'] ) && $options['lazyload'] ) {
					WPMST()->render->add_style( 'wpmtst-lazyload-css' );
					WPMST()->render->add_script( 'wpmtst-lozad' );
					WPMST()->render->add_script( 'wpmtst-lozad-load' );
				}
			}
		}

		/**
		 * Overwrites inherited method preventing pagination for slider type.
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function query_pagination( $args ) {
			return $args;
		}
	}

endif;
