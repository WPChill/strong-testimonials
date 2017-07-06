<?php
/**
 * View Display Mode class.
 *
 * @since 2.16.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
		$this->has_stars();
		$this->has_pagination();
		$this->has_layouts();
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
		$this->has_stars();
		$this->has_pagination();
		$this->has_layouts();

		$this->load_dependent_scripts();
		$this->load_extra_stylesheets();

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
		$ids = explode( ',', $this->atts['id'] );

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

		if ( $this->atts['pagination'] && 'masonry' != $this->atts['layout'] ) {
			$content_class_list[] = 'strong-paginated';
			$content_class_list[] = $this->pager_signature();
		}

		// layouts
		$content_class_list[] = 'strong-' . ( $this->atts['layout'] ? $this->atts['layout'] : 'normal' );
		$content_class_list[] = 'columns-' . ( $this->atts['layout'] ? $this->atts['column_count'] : '1' );

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
	 * Layouts
	 *
	 * @since 2.16.0 In Strong_View class.
	 */
	public function has_layouts() {

		if ( 'masonry' == $this->atts['layout'] ) {

			WPMST()->add_script( 'wpmtst-masonry-script' );

			if ( apply_filters( 'wpmtst_load_masonry_style', true ) ) {
				WPMST()->add_style( 'wpmtst-masonry-style' );
			}

		} elseif ( 'columns' == $this->atts['layout'] ) {

			if ( apply_filters( 'wpmtst_load_columns_style', true ) ) {
				WPMST()->add_style( 'wpmtst-columns-style' );
			}

		} elseif ( 'grid' == $this->atts['layout'] ) {

			WPMST()->add_script( 'wpmtst-grid-script' );

			if ( apply_filters( 'wpmtst_load_grid_style', true ) ) {
				WPMST()->add_style( 'wpmtst-grid-style' );
			}
		}

	}

	/**
	 * Pagination
	 *
	 * @since 2.16.0 In Strong_View class.
	 */
	public function has_pagination() {
		if ( $this->atts['pagination'] && 'simple' == $this->atts['pagination_type'] ) {
			$sig  = $this->pager_signature();
			$args = $this->pager_args();
			WPMST()->add_script( 'wpmtst-pager-script' );
			WPMST()->add_script_var( 'wpmtst-pager-script', $sig, $args );
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

		$nav = $this->atts['nav'];
		if ( false !== strpos( $this->atts['nav'], 'before' ) && false !== strpos( $this->atts['nav'], 'after' ) ) {
			$nav = 'both';
		}

		$args = array(
			'pageSize'      => $this->atts['per_page'],
			'currentPage'   => 1,
			'pagerLocation' => $nav,
			'scrollTop'     => $options['scrolltop'],
			'offset'        => $options['scrolltop_offset'],
		);

		return apply_filters( 'wpmtst_view_pagination', $args, $this->atts['view'] );
	}

}

endif;
