<?php
/**
 * View class.
 *
 * @since 2.3
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

	public function __construct( $atts = array() ) {

		add_filter( 'wpmtst_view_atts', array( $this, 'atts' ) );

		$this->atts = apply_filters( 'wpmtst_view_atts', $atts );

		//$this->build();

	}

	/**
	 * Like a shortcode attribute filter.
	 *
	 * @param $atts
	 * @return mixed
	 */
	public function atts( $atts ) {
		if ( 'custom' == $this->atts['thumbnail_size'] ) {
			$this->atts['thumbnail_size'] = array( $this->atts['thumbnail_width'], $this->atts['thumbnail_height'] );
		}

		return $atts;
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
		$this->query = $this->build_query();
		$this->build_classes();
		wp_reset_postdata();
	}

	/**
	 * Build the view.
	 */
	public function build() {

		global $strong_templates;

		do_action( 'wpmtst_view_build_before', $this );

		$this->query = $this->build_query();

		/**
		 * Build CSS classes. This must happen after the query.
		 */
		$this->build_classes();

		/**
		 * Add filters here.
		 */
		add_filter( 'get_avatar', 'wpmtst_get_avatar', 10, 3 );

		/**
		 * Locate template.
		 */
		$this->template_file = $strong_templates->get_template_attr( $this->atts, 'template' );

		/**
		 * Allow add-ons to hijack the output generation.
		 */
		$query = $this->query;
		if ( has_filter( 'wpmtst_render_view_template' ) ) {
			$html = apply_filters( 'wpmtst_render_view_template', '', $this );
		}
		else {
			ob_start();
			/** @noinspection PhpIncludeInspection */
			include( $this->template_file );
			$html = ob_get_contents();
			ob_end_clean();
		}

		/**
		 * Remove filters here.
		 */
		remove_filter( 'get_avatar', 'wpmtst_get_avatar' );

		/**
		 * Hook to enqueue scripts.
		 */
		do_action( 'wpmtst_view_rendered', $this->atts );

		wp_reset_postdata();

		$this->html = apply_filters( 'strong_view_html', $html, $this );

	}

	/**
	 * Build class list based on view attributes.
	 */
	public function build_classes() {
		$content_class_list = array();
		$post_class_list    = array( 'testimonial' );

		// excerpt overrides length
		if ( $this->atts['excerpt'] ) {
			$post_class_list[] = 'excerpt';
		}
		elseif ( $this->atts['length'] ) {
			$post_class_list[] = 'truncated';
		}

		/**
		 * Slideshow
		 */
		if ( $this->atts['slideshow'] ) {

			// add slideshow signature
			$args = array(
				'fx'      => 'fade',
				'speed'   => $this->atts['effect_for'] * 1000,
				'timeout' => $this->atts['show_for'] * 1000,
				'pause'   => $this->atts['no_pause'] ? 0 : 1
			);
			$content_class_list[] = 'strong_cycle';
			$content_class_list[] = 'strong_cycle_' . hash( 'md5', serialize( $args ) );
			$post_class_list[]    = 't-slide';

		}
		else {

			if ( $this->atts['per_page']
				&& $this->post_count > $this->atts['per_page']
				&& 'masonry' != $this->atts['layout'] )
			{
				$content_class_list[] = 'strong-paginated';
			}

			// layouts
			$content_class_list[] = 'strong-' . ( $this->atts['layout'] ? $this->atts['layout'] : 'normal' );
			$content_class_list[] = 'columns-' . ( $this->atts['layout'] ? $this->atts['column_count'] : '1' );

		}

		/**
		 * Filter classes and store updated atts.
		 */
		$this->atts['content_class'] = join( ' ', apply_filters( 'wpmtst_view_content_class', $content_class_list ) );
		$this->atts['post_class']    = join( ' ', apply_filters( 'wpmtst_view_post_class', $post_class_list ) );
		WPMST()->set_atts( $this->atts );

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
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);

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

		return $query;
	}

}

endif;
