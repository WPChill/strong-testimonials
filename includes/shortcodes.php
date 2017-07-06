<?php
/**
 * Shortcode functions.
 *
 * @package Strong_Testimonials
 */

/**
 * testimonial_view shortcode
 *
 * @param      $atts
 * @param null $content
 *
 * @return mixed|string
 */
function wpmtst_strong_view_shortcode( $atts, $content = null ) {
	$out = shortcode_atts(
		WPMST()->get_view_defaults(),
		normalize_empty_atts( $atts ),
		'testimonial_view'
	);

	return wpmtst_render_view( $out );
}
add_shortcode( 'testimonial_view', 'wpmtst_strong_view_shortcode' );

/**
 * testimonial_view attribute filter
 *
 * @since 1.21.0
 * @param $out
 * @param $pairs
 * @param $atts
 *
 * @return array
 */
function wpmtst_strong_view_shortcode_filter( $out, $pairs, $atts ) {
	return WPMST()->parse_view( $out, $pairs, $atts );
}
add_filter( 'shortcode_atts_testimonial_view', 'wpmtst_strong_view_shortcode_filter', 10, 3 );

/**
 * Render the View.
 *
 * @param $out
 *
 * @return mixed|string
 */
function wpmtst_render_view( $out ) {
	// Did we find this view?
	if ( isset( $out['view_not_found'] ) && $out['view_not_found'] ) {
		return '<p style="color:red">' . sprintf( __( 'Strong Testimonials error: View %s not found' ), $out['view'] ) . '</p>';
	}

	if ( $out['form'] ) {
		$view = new Strong_View_Form( $out );
	} elseif ( $out['slideshow'] ) {
		$view = new Strong_View_Slideshow( $out );
	} else {
    	$view = new Strong_View_Display( $out );
	}
	$view->build();

	return $view->output();
}

/**
 * Normalize empty shortcode attributes.
 *
 * Turns atts into tags - brilliant!
 * Thanks http://wordpress.stackexchange.com/a/123073/32076
 */
if ( ! function_exists( 'normalize_empty_atts' ) ) {
	function normalize_empty_atts( $atts ) {
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
			return $atts;
		}
	}
}


/**
 * Remove whitespace between tags. Helps prevent double wpautop in plugins
 * like Posts For Pages and Custom Content Shortcode.
 *
 * @param $html
 * @since 2.3
 *
 * @return mixed
 */
function wpmtst_strong_view_html( $html ) {
	$options = get_option( 'wpmtst_options' );
	if ( $options['remove_whitespace'] ) {
		$html = preg_replace( '~>\s+<~', '><', $html );
	}

	return $html;
}
add_filter( 'strong_view_html', 'wpmtst_strong_view_html' );


/**
 * A shortcode to display the number of testimonials.
 *
 * For all: [testimonial_count]
 * For a specific category (by slug): [testimonial_count category="abc"]
 *
 * @param      $atts
 * @param null $content
 *
 * @since 2.19.0
 *
 * @return int
 */
function wpmtst_testimonial_count( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'category' => '',
		),
		$atts
	);

	$args = array(
		'posts_per_page'           => -1,
		'post_type'                => 'wpm-testimonial',
		'post_status'              => 'publish',
		'wpm-testimonial-category' => $atts['category'],
		'suppress_filters'         => true,
	);
	$posts_array = get_posts( $args );

	return count( $posts_array );
}
add_shortcode( 'testimonial_count', 'wpmtst_testimonial_count' );


/**
 * A simple shortcode for testing.
 *
 * @return string
 */
function wpmtst_hello() {
    return 'Hello :) &bull;&nbsp;';
}
add_shortcode ( 'wpmtst_hello', 'wpmtst_hello' );
