<?php
/**
 * View Display Mode class.
 *
 * @since 2.16.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Strong_View_Display' ) ) :

	class Strong_View_Display extends Strong_View {

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
			parent::__construct( $atts );
			add_filter( 'wpmtst_build_query', array( $this, 'query_pagination' ) );
				add_filter( 'wpmtst_build_query', array( $this, 'query_infinitescroll' ) );
			add_action( 'wpmtst_view_processed', array( $this, 'reset_view' ) );
		}

		/**
		 * Reset stuff after view is processed or rendered.
		 *
		 * @since 2.31.0
		 */
		public function reset_view() {
			wp_reset_postdata();
		}

		/**
		 * Adjust query for standard pagination.
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		public function query_pagination( $args ) {
			if ( isset( $this->atts['pagination'] ) && isset( $this->atts['pagination_settings'] ) && isset( $this->atts['pagination_settings']['type'] ) ) {
				if ( $this->atts['pagination'] && 'standard' === $this->atts['pagination_settings']['type'] ) {
					// Limit is not compatible with standard pagination.
					$this->atts['count']    = -1;
					$args['posts_per_page'] = $this->atts['pagination_settings']['per_page'];
					$args['paged']          = wpmtst_get_paged();
				}
			}

			return $args;
		}

		/**
		 * Adjust query for infinite scroll pagination.
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		public function query_infinitescroll( $args ) {

			if ( isset( $this->atts['pagination'] ) && isset( $this->atts['pagination_settings'] ) && isset( $this->atts['pagination_settings']['type'] ) && isset( $this->atts['mode'] ) ) {
				if ( $this->atts['pagination'] && in_array( $this->atts['pagination_settings']['type'], array( 'infinitescroll', 'loadmore' ), true ) && 'slideshow' !== $this->atts['mode'] ) {
					// Limit is not compatible with standard pagination.
					$this->atts['count']    = -1;
					$args['posts_per_page'] = $this->atts['pagination_settings']['per_page'];
					$args['paged']          = wpmtst_get_paged();
				}
			}

			return $args;
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
			$this->has_stars();
			$this->has_pagination();
			$this->has_layouts();
			$this->has_readmore();

			$this->load_extra_stylesheets();

			// If we can preprocess, we can add the inline style in <head>.
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
			$this->has_stars();
			$this->has_pagination();
			$this->has_layouts();
				$this->has_readmore();
				$this->has_lazyload();

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
			// Standard pagination
			if ( isset( $this->atts['pagination'] ) && $this->atts['pagination'] && 'standard' === $this->atts['pagination_settings']['type'] ) {
				if ( false !== strpos( $this->atts['pagination_settings']['nav'], 'before' ) ) {
					add_action( 'wpmtst_view_header', 'wpmtst_standard_pagination' );
				}
				if ( false !== strpos( $this->atts['pagination_settings']['nav'], 'after' ) ) {
					add_action( 'wpmtst_view_footer', 'wpmtst_standard_pagination' );
				}
			}

			// Read more page
			if ( isset( $this->atts['more_page_hook'] ) ) {
				add_action( $this->atts['more_page_hook'], 'wpmtst_read_more_page' );
			}

			/**
			 * Locate template.
			 */
			$this->template_file = apply_filters( 'wpmtst_view_template_file_display', WPMST()->templates->get_template_attr( $this->atts, 'template' ) );

			/**
			 * Allow add-ons to hijack the output generation.
			 */
			$query = $this->query;
			$atts  = $this->atts;
			$html  = '';

			if ( ! $this->found_posts ) {

				if ( current_user_can( 'strong_testimonials_views' ) && 'infinitescroll' !== $this->atts['pagination_settings']['type'] ) {
					$html = $this->nothing_found();
				}
			} elseif ( has_filter( 'wpmtst_render_view_template' ) ) {

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
			if ( isset( $this->atts['more_page_hook'] ) ) {
				remove_action( $this->atts['more_page_hook'], 'wpmtst_read_more_page' );
			}
			remove_action( 'wpmtst_view_header', 'wpmtst_standard_pagination' );
			remove_action( 'wpmtst_view_footer', 'wpmtst_standard_pagination' );

			/**
			 * Hook to enqueue scripts.
			 */
			do_action( 'wpmtst_view_rendered', $this->atts );

			do_action( 'wpmtst_view_processed' );

			$this->html = apply_filters( 'strong_view_html', $html, $this );
		}

		/**
		 * Build our query.
		 */
		public function build_query() {
			$ids = isset( $this->atts['id'] ) ? explode( ',', $this->atts['id'] ) : false;

			$args = array(
				'post_type'      => 'wpm-testimonial',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'paged'          => null,
			);
			$args = apply_filters( 'wpmtst_build_query', $args );

			// id's override category
			if ( isset( $this->atts['id'] ) && $this->atts['id'] ) {
				$args['post__in'] = $ids;
			} elseif ( isset( $this->atts['category'] ) && $this->atts['category'] ) {
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
			// TODO improve for allowable custom order
			if ( isset( $this->atts['order'] ) && 'menu_order' === $this->atts['order'] ) {
				$args['orderby'] = 'menu_order';
				$args['order']   = 'ASC';
			} else {
				$args['orderby'] = 'post_date';
				if ( isset( $this->atts['order'] ) && 'newest' === $this->atts['order'] ) {
					$args['order'] = 'DESC';
				} else {
					$args['order'] = 'ASC';
				}
			}

			// For Post Types Order plugin
			$args['ignore_custom_sort'] = true;

			if ( isset( $this->atts['pagination'] ) && isset( $this->atts['pagination_settings'] ) && isset( $this->atts['pagination_settings']['type'] ) ) {
				if ( $this->atts['pagination'] && in_array( $this->atts['pagination_settings']['type'], array( 'infinitescroll', 'loadmore' ), true ) ) {
					if ( empty( $this->atts['paged'] ) ) {
						$this->atts['paged'] = 1;
					}
					$args['paged'] = $this->atts['paged'];
				}
			}

			$query = new WP_Query( apply_filters( 'wpmtst_query_args', $args, $this->atts ) );
			/**
			 * Shuffle array in PHP instead of SQL.
			 *
			 * @since 1.16
			 */
			if ( isset( $this->atts['order'] ) && 'random' === $this->atts['order'] ) {
					$options = get_option( 'wpmtst_compat_options' );
				if ( isset( $options['random_js'] ) && $options['random_js'] ) {
					WPMST()->render->add_script( 'wpmtst-random' );
				} else {
					shuffle( $query->posts );
				}
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
			if ( isset( $this->atts['count'] ) && $this->atts['count'] > 0 ) {
				$count              = min( $this->atts['count'], count( $query->posts ) );
				$query->posts       = array_slice( $query->posts, 0, $count );
				$query->post_count  = $count;
				$query->found_posts = $count;
			}

			$this->post_count  = $query->post_count;
			$this->found_posts = $query->found_posts;
			$this->query       = $query;
			WPMST()->set_query( $query );
		}

		/**
		 * Build class list based on view attributes.
		 *
		 * This must happen after the query.
		 * TODO DRY
		 */
		public function build_classes() {
			$container_class_list = isset( $this->atts['view'] ) ? array( 'strong-view-id-' . $this->atts['view'] ) : array();
			$container_class_list = array_merge( $container_class_list, $this->get_template_css_class() );

			if ( is_rtl() ) {
				$container_class_list[] = 'rtl';
			}

			if ( isset( $this->atts['class'] ) && $this->atts['class'] ) {
				$container_class_list[] = $this->atts['class'];
			}

			$container_data_list = array(
				'count' => $this->post_count,
			);

			$content_class_list = array();

			$post_class_list = array( 'wpmtst-testimonial testimonial' );

			if ( isset( $this->atts['content'] ) && 'excerpt' === $this->atts['content'] ) {
				$post_class_list[] = 'excerpt';
			}

			if ( $this->is_paginated() && ! $this->is_masonry() ) {
				$content_class_list[]             = 'strong-paginated';
				$container_class_list[]           = 'strong-pager';
				$container_data_list['pager-var'] = $this->pager_signature();
				$container_data_list['state']     = 'idle';
			}

			if ( $this->is_masonry() ) {
				$container_data_list['state'] = 'idle';
			}

			if ( $this->is_hybrid() ) {
				$container_class_list[] = 'more-in-place';
			}

			// layouts
			$content_class_list[] = 'strong-' . ( isset( $this->atts['layout'] ) && $this->atts['layout'] ? $this->atts['layout'] : 'normal' );
			$content_class_list[] = 'columns-' . ( isset( $this->atts['layout'] ) && $this->atts['layout'] ? $this->atts['column_count'] : '1' );

			/**
			 * Filter classes.
			 */
			$this->atts['container_data']  = apply_filters( 'wpmtst_view_container_data', $container_data_list, $this->atts );
			$this->atts['container_class'] = implode( ' ', apply_filters( 'wpmtst_view_container_class', $container_class_list, $this->atts ) );
			$this->atts['content_class']   = implode( ' ', apply_filters( 'wpmtst_view_content_class', $content_class_list, $this->atts ) );
			$this->atts['post_class']      = implode( ' ', apply_filters( 'wpmtst_view_post_class', $post_class_list, $this->atts ) );

			/**
			 * Store updated atts.
			 */
			WPMST()->set_atts( $this->atts );
		}

		/**
		 * Return true if using simple pagination (JavaScript).
		 *
		 * @since 2.28.0
		 *
		 * @return bool
		 */
		public function is_paginated() {
			if ( isset( $this->atts['pagination'] ) && isset( $this->atts['pagination_settings'] ) && isset( $this->atts['pagination_settings']['type'] ) ) {
				return ( $this->atts['pagination'] && 'simple' === $this->atts['pagination_settings']['type'] );
			}
			return false;
		}

		/**
		 * Return true if using infinitescroll pagination (JavaScript).
		 *
		 * @since 2.28.0
		 *
		 * @return bool
		 */
		public function is_infinitescroll() {
			if ( isset( $this->atts['pagination'] ) && isset( $this->atts['pagination_settings'] ) && isset( $this->atts['pagination_settings']['type'] ) ) {
				return ( $this->atts['pagination'] && 'infinitescroll' === $this->atts['pagination_settings']['type'] );
			}
			return false;
		}

		/**
		 * Return true if using load more button pagination (JavaScript).
		 *
		 * @since 2.28.0
		 *
		 * @return bool
		 */
		public function is_loadmore() {
			if ( isset( $this->atts['pagination'] ) && isset( $this->atts['pagination_settings'] ) && isset( $this->atts['pagination_settings']['type'] ) ) {
				return ( $this->atts['pagination'] && 'loadmore' === $this->atts['pagination_settings']['type'] );
			}
			return false;
		}

		/**
		 * Return true if using Masonry.
		 *
		 * @since 2.28.0
		 *
		 * @return bool
		 */
		public function is_masonry() {
			return ( isset( $this->atts['layout'] ) && 'masonry' === $this->atts['layout'] );
		}

		/**
		 * Return true if read-more in place.
		 *
		 * @since 2.33.0
		 *
		 * @return bool
		 */
		public function is_hybrid() {
			return ( isset( $this->atts['more_post_in_place'] ) && $this->atts['more_post_in_place'] );
		}

		/**
		 * Pagination
		 *
		 * @since 2.16.0 In Strong_View class.
		 */
		public function has_pagination() {
			if ( $this->is_paginated() ) {
				WPMST()->render->add_script( 'wpmtst-pager' );
				WPMST()->render->add_script_var( 'wpmtst-pager', $this->pager_signature(), $this->pager_args() );
				WPMST()->render->add_script( 'wpmtst-controller' );
			}
		}

		/**
		 * Create unique pager signature.
		 *
		 * @since 2.13.2
		 * @since 2.22.3 In this class.
		 *
		 * @return string
		 */
		public function pager_signature() {
			return 'strong_pager_id_' . $this->atts['view'];
		}

		/**
		 * Assemble pager settings.
		 *
		 * @since 2.13.2
		 * @since 2.22.3 In this class.
		 *
		 * @return array
		 */
		public function pager_args() {
			$options = get_option( 'wpmtst_options' );

			$nav = $this->atts['pagination_settings']['nav'];
			if ( false !== strpos( $nav, 'before' ) && false !== strpos( $nav, 'after' ) ) {
				$nav = 'both';
			}

			// Remember: top level is converted to strings!
			$args = array(
				'config' => array(
					'pageSize'      => $this->atts['pagination_settings']['per_page'],
					'currentPage'   => 1,
					'pagerLocation' => $nav,
					'scrollTop'     => $options['scrolltop'],
					'offset'        => $options['scrolltop_offset'],
					'imagesLoaded'  => true,
				),
			);

			return apply_filters( 'wpmtst_view_pagination', $args, $this->atts['view'] );
		}

		/**
		 * Layouts
		 *
		 * @since 2.16.0 In Strong_View class.
		 */
		public function has_layouts() {
			if ( $this->is_masonry() ) {

				WPMST()->render->add_script( 'jquery-masonry' );
				WPMST()->render->add_script( 'imagesloaded' );

				if ( apply_filters( 'wpmtst_load_masonry_style', true ) ) {
					WPMST()->render->add_style( 'wpmtst-masonry-style' );
				}
			} elseif ( isset( $this->atts['layout'] ) && 'columns' === $this->atts['layout'] ) {

				if ( apply_filters( 'wpmtst_load_columns_style', true ) ) {
					WPMST()->render->add_style( 'wpmtst-columns-style' );
				}
			} elseif ( isset( $this->atts['layout'] ) && 'grid' === $this->atts['layout'] ) {

				if ( apply_filters( 'wpmtst_load_grid_style', true ) ) {
					WPMST()->render->add_style( 'wpmtst-grid-style' );
				}
			}

			WPMST()->render->add_script( 'wpmtst-controller' );
		}

		/**
		 * Read more in place
		 *
		 * @since 2.33.0
		 */
		public function has_readmore() {
			if ( $this->is_hybrid() ) {
				WPMST()->render->add_style( 'wpmtst-animate' );
				WPMST()->render->add_script( 'wpmtst-readmore' );
			}
		}

		/**
	 * Lazy Load
	 *
	 * @since 2.40.4
	 */
		public function has_lazyload() {
			if ( ! function_exists( 'wp_lazy_loading_enabled' ) || apply_filters( 'wp_lazy_loading_enabled', true, 'img', 'strong_testimonials_has_lazyload' ) ) {
				$options = get_option( 'wpmtst_options' );
				if ( isset( $options['lazyload'] ) && $options['lazyload'] ) {
					WPMST()->render->add_style( 'wpmtst-lazyload-css' );
					WPMST()->render->add_script( 'wpmtst-lozad' );
					WPMST()->render->add_script( 'wpmtst-lozad-load' );
				}
			}
		}
	}

endif;
