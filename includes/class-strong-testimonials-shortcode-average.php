<?php
/**
 * Class Strong_Testimonials_Average_Shortcode
 *
 * @since 2.31.0
 */

class Strong_Testimonials_Average_Shortcode {

	/**
	 * @var string
	 */
	public $shortcode = 'testimonial_average_rating';

	public function __construct() {
		add_shortcode( $this->shortcode, array( $this, 'testimonial_average_rating_shortcode' ) );
	}

	/**
	 * Return average rating.
	 *
	 * @param $atts
	 * @param null $content
	 * @since 2.31.0
	 * @return string
	 */
	public function testimonial_average_rating_shortcode( $atts, $content = null ) {
		$pairs = array(
            // parts
            'average'   => '',
            'count'     => '',
            'stars'     => '',
            // style
            'block'     => '',
            'centered'  => '',
            // HTML
            'element'   => 'div', // span
            'class'     => '', // on wrapper
            // filters
            'category'  => '',
            // rounded
            'rounded' => '',
            // field
            'field' => '',
            // decimals
            'decimals' => 1
		);
		$pairs = apply_filters( "wpmtst_shortcode_defaults__{$this->shortcode}", $pairs );

		$atts = shortcode_atts( $pairs, normalize_empty_atts( $atts ), $this->shortcode );

		// default parts
		if ( ! $content ) {
			$content = '{title} {stars} {summary}';
		}

		// set parts
		preg_match_all( "#{(.*?)}#", $content, $parts );
		/*
		 * Example:
		 *
		 * Array
		 * (
		 *     [0] => Array
		 *         (
		 *             [0] => {title}
		 *             [1] => {stars}
		 *             [2] => {summary}
		 *         )
		 *
		 *     [1] => Array
		 *         (
		 *             [0] => title
		 *             [1] => stars
		 *             [2] => summary
		 *         )
		 * )
		 */
		$tag_list = $parts[0];
		$tag_keys = $parts[1];
		$parts    = array_fill_keys( $tag_keys, '' );

		// get posts
		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => 'wpm-testimonial',
			'post_status'      => 'publish',
			'suppress_filters' => true,
		);

		// category
		if ( $atts['category'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'wpm-testimonial-category',
					'field'    => is_numeric( $atts['category'] ) ? 'id' : 'slug',
					'terms'    => $atts['category'],
				),
			);
		}

		$args        = apply_filters( 'wpmtst_query_args', $args, $atts );
		$posts_array = get_posts( $args );
                
		// get summary
		$summary = $this->get_summary( $posts_array, $atts['field'], $atts['decimals'] );
		/*
		 * Example:
		 *
		 * Array
         * (
         *     [review_count] => 2
         *     [rating_count] => 2
         *     [rating_sum] => 10
         *     [rating_average] => 5
         *     [rating_detail] => Array
         *         (
         *             [5] => 2
         *             [4] => 0
         *             [3] => 0
         *             [2] => 0
         *             [1] => 0
         *             [0] => 0
         *         )
         * )
		 */

		// Want to build your own HTML? Return any truthy value to short-circuit this shortcode output.
		$html = apply_filters( 'wpmtst_average_rating_pre_html', '', $atts, $summary );
		if ( $html ) {
			return $html;
		}

		// assemble classes
		$class_list = array_filter( array_merge( array( 'strong-rating-wrapper', 'average' ), explode( ' ', $atts['class'] ) ) );
		if ( $atts['block'] ) {
			$class_list[] = 'block';
		}
		if ( $atts['centered'] ) {
			$class_list[] = 'centered';
		}

		// round the rating if necessary
        if(!empty($atts['rounded'])){
            $rating_average = number_format($summary['rating_average'],0);
        } else{
            $rating_average = number_format($summary['rating_average'], absint($atts['decimals']));
        }

		// title
		if ( isset( $parts['title'] ) ) {
			$parts['title'] = sprintf( '<span class="strong-rating-title">%s</span>', esc_html__( 'Average Rating:', 'strong-testimonials' ) );
		}
		if ( isset( $parts['title2'] ) ) {
			/* translators: %s is a number */
		    $count = sprintf( _n( 'Average of %s Rating:', 'Average of %s Ratings:', $summary['rating_count'], 'strong-testimonials' ), $summary['rating_count'] );
			$parts['title2'] = sprintf( '<span class="strong-rating-title">%s</span>', $count );
		}

		// stars
		if ( isset( $parts['stars'] ) ) {
			$parts['stars'] = $this->print_stars( $rating_average);
		}

		// average
		if ( isset( $parts['average'] ) ) {
			$parts['average'] = sprintf( '<span class="strong-rating-average">%s</span>', $rating_average );
		}

		// count
		if ( isset( $parts['count'] ) ) {
			$parts['count'] = sprintf( '<span class="strong-rating-count">%s</span>', $summary['rating_count'] );
		}

		// summary phrase
		if ( isset( $parts['summary'] ) ) {

			/* translators: %s is a number */
			$average = sprintf( _n( '%s star', '%s stars', $rating_average, 'strong-testimonials' ), $rating_average );
			$count   = sprintf( _n( '(based on %s rating)', '(based on %s ratings)', $summary['rating_count'], 'strong-testimonials' ), $summary['rating_count'] );
			$parts['summary'] = sprintf( '<span class="strong-rating-summary">%s</span>', $average . ' ' . $count );

		} elseif ( isset( $parts['summary2'] ) ) {

			/* translators: %s is a number */
			$average = sprintf( _n( '%s star', '%s stars', $rating_average, 'strong-testimonials' ), $rating_average );
			$parts['summary2'] = sprintf( '<span class="strong-rating-summary">%s</span>', $average );

		}

		// replace tags
		foreach ( $tag_list as $key => $tag ) {
			$content = str_replace( $tag, $parts[ $tag_keys[ $key ] ], $content );
		}

		$allowed_elements = array( 'span', 'p', 'i', 'div', 'li', 'ul', 'ol', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		$allowed_elements = apply_filters( 'wpmtst_allowed_shortcode_elements', $allowed_elements );
		$element = in_array( $atts['element'], $allowed_elements ) ? esc_attr( $atts['element'] ) : 'div';

		// assemble it.
		$html = sprintf( '<%s class="%s">%s</%s>', esc_attr( $element ), esc_attr( implode( ' ', $class_list ) ), $content, esc_attr( $element ) );

		wp_enqueue_style( 'wpmtst-rating-display' );

		return apply_filters( 'wpmtst_average_rating_html', $html, $atts, $summary );
	}

	/**
	 * Calculate and return the average rating.
	 *
	 * @param $posts
	 * @since 1.1.0
	 * @return array|null
	 */
	public function get_summary( $posts = null, $field = '', $decimals = 1 ) {
		// Set a placeholder.
		$average = array(
			'review_count'   => null,
			'rating_count'   => null,
			'rating_sum'     => null,
			'rating_average' => null,
			'rating_detail'  => null,
		);

		if ( $posts ) {

			// initialize totals
			$review_count  = count( $posts );
			$rating_count  = 0;
			$rating_sum    = 0;
			// initial values for each rating
			$rating_detail = array_fill_keys( array( 5, 4, 3, 2, 1, 0 ), 0 );

			foreach ( $posts as $post ) {
				// get rating value
				$value = $this->get_rating_value( $post, $field );
				// add to detail array
				$rating_detail[ $value ]++;
				// add to count and sum
				if ( $value ) {
					$rating_sum += $value;
					$rating_count++;
				}
			}

			if ( $rating_count ) {

				$rating_average = number_format( $rating_sum / $rating_count, absint($decimals) );
				if ( $decimals == 1 ) {
					$rating_average = trim( $rating_average, '.0' );
				}
				$average = array(
						'review_count'   => number_format( $review_count ),
						'rating_count'   => number_format( $rating_count ),
						'rating_sum'     => number_format( $rating_sum ),
						'rating_average' => $rating_average,
						'rating_detail'  => $rating_detail,
				);
			}

		}

		return $average;
	}

	/**
	 * Return the rating value for a single post.
	 *
	 * @param $post
	 * @since 1.1.0
	 * @return int|null
	 */
	private function get_rating_value( $post, $field = '' ) {
                if (!empty($field)) {
                    if ( $field == 'all') {
                        $rating_fields = $this->find_all_rating_field();
                    } else {
                        $rating_fields = $this->find_rating_field($field);
                    }
                    
                } else {
                    $rating_fields = $this->find_first_rating_field();
                }
                if ( $rating_fields ) {  
                    $ratings = array();
                    foreach ( $rating_fields as $rating_field ) {
                        $rating = intval( get_post_meta( $post->ID, $rating_field['name'], true ) );
                        if ( ! $rating ) {
                            $rating = intval( $rating_field['default_display_value'] );
                        }
                        $ratings[] = $rating;
                    }
                    $rating = array_sum($ratings)/count($ratings);
                } else {
                    $rating = 5;
                }
                return $rating;
	}

	/**
	 * Find the first rating field.
	 *
	 * @since 1.1.0
	 * @return bool|int|string
	 */
	private function find_first_rating_field() {
		$fields = wpmtst_get_custom_fields();
		foreach ( $fields as $key => $field ) {
			if ( 'rating' == $field['input_type'] ) {
				return array($field);
			}
		}

		return false;
	}
        
        /**
	 * Find specific rating field.
	 *
	 * @return bool|int|string
	 */
        private function find_rating_field($rating_field) {
		$fields = wpmtst_get_custom_fields();
		foreach ( $fields as $key => $field ) {
			if ( 'rating' == $field['input_type'] && $rating_field == $field['name']) {
				return array($field);
			}
		}
                
		return false;
	}
        
        /**
	 * Find all rating field.
	 *
	 * @since 2.41.0
	 * @return bool|int|string
	 */
	private function find_all_rating_field() {
		$fields = wpmtst_get_custom_fields();
                $rating = array();
		foreach ( $fields as $key => $field ) {
			if ( 'rating' == $field['input_type'] ) {
				$rating[] = $field;
			}
		}
                
                if ( !empty($rating) ) {
                    return $rating;
                }

		return false;
	}

	/**
	 * Print the stars.
	 *
	 * @param float $rating Average rating.
	 * @param string $class  The container CSS class.
	 * @since 2.31.0
	 * @return string
	 */
	public function print_stars( $rating = 0.0, $class = 'strong-rating' ) {

        $is_zero = (0 == $rating) ? ' current' : '';

		$star_solid   = '<svg class="star_solid" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>';
		$star_regular = '<svg class="star_regular" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"></path></svg>';
		$star_half    = '<svg class="star_half" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M508.55 171.51L362.18 150.2 296.77 17.81C290.89 5.98 279.42 0 267.95 0c-11.4 0-22.79 5.9-28.69 17.81l-65.43 132.38-146.38 21.29c-26.25 3.8-36.77 36.09-17.74 54.59l105.89 103-25.06 145.48C86.98 495.33 103.57 512 122.15 512c4.93 0 10-1.17 14.87-3.75l130.95-68.68 130.94 68.7c4.86 2.55 9.92 3.71 14.83 3.71 18.6 0 35.22-16.61 31.66-37.4l-25.03-145.49 105.91-102.98c19.04-18.5 8.52-50.8-17.73-54.6zm-121.74 123.2l-18.12 17.62 4.28 24.88 19.52 113.45-102.13-53.59-22.38-11.74.03-317.19 51.03 103.29 11.18 22.63 25.01 3.64 114.23 16.63-82.65 80.38z"></path></svg>';
        
	
		$svg_args = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
				'id'              => true,
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'    => true,
				'fill' => true,
			),
			'style' => array( 'type' => true ),
			'span' => array(
				'style' 		=> array(),
				'class' 		=> array(),
			),
		);

		ob_start();
        ?>
        <span class="<?php echo esc_attr($class); ?>">
            <span class="star0 star<?php echo esc_attr( $is_zero ); ?>"></span>
			<?php
            if ($is_zero) {
                echo str_repeat('<span class="star" style="display: inline-block;" >'. $star_regular .'</span>', 5);
            } else {
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= round($rating)) {
						$star_icon = $star_solid;
                    }else{
						$star_icon = $star_regular;
					}

                    if ( (0.9 >= $i - $rating) && (0.1 <= $i - $rating)) {
                        $star_icon = $star_half ;
                    }
                    echo wp_kses( sprintf('<span class="star" style="display: inline-block;" >%s</span>',  $star_icon ), $svg_args) ;
                }
            }
			?>
		</span>
		<?php
		$html = apply_filters( 'wpmtst_average_rating_stars_html', ob_get_clean(), $rating );

		return $html;
	}

}
