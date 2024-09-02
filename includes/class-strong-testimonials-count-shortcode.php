<?php
/**
 * Class Strong_Testimonials_Count_Shortcode
 *
 * @since 2.28.0
 */

class Strong_Testimonials_Count_Shortcode {

	/**
	 * @var string
	 */
	public $shortcode = 'testimonial_count';

	public function __construct() {
		add_shortcode( $this->shortcode, array( $this, 'testimonial_count_shortcode' ) );
	}

	/**
	 * A shortcode to display the number of testimonials.
	 *
	 * For all: [testimonial_count]
	 * For a specific category (by slug): [testimonial_count category="abc"]
	 * Unformatted: [testimonial_count unformatted]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @since 2.19.0
	 * @since 2.30.0 unformatted attribute
	 *
	 * @return int
	 */
	public function testimonial_count_shortcode( $atts, $content = null ) {
		$pairs = array(
			'category'    => '',
			'unformatted' => 0,
		);
		$pairs = apply_filters( "wpmtst_shortcode_defaults__{$this->shortcode}", $pairs );

		$atts = shortcode_atts( $pairs, normalize_empty_atts( $atts ), $this->shortcode );

		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => 'wpm-testimonial',
			'post_status'      => 'publish',
			'suppress_filters' => true,
		);

		if ( $atts['category'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'wpm-testimonial-category',
					'field'    => 'slug',
					'terms'    => $atts['category'],
				),
			);

		}

		$args        = apply_filters( 'wpmtst_query_args', $args, $atts );
		$posts_array = get_posts( $args );
		$count       = count( $posts_array );

		if ( $atts['unformatted'] ) {
			return $count;
		}

		return number_format_i18n( $count );
	}
}
