<?php
/**
 * Shortcode functions.
 *
 * @package Strong_Testimonials
 */

/**
 * testimonial_view shortcode
 * Merely a wrapper for the [strong] shortcode until verion 2.0
 *
 * @param      $atts
 * @param null $content
 *
 * @return mixed|string|void
 */
function wpmtst_strong_view_shortcode( $atts, $content = null ) {
	$out = shortcode_atts(
		WPMST()->get_view_defaults(),
		normalize_empty_atts( $atts ), 'testimonial_view'
	);
	$out['content'] = $content;

	// container_class is shared by display and form in both original and new default templates
	$options = get_option( 'wpmtst_options' );
	$out['container_class'] = 'strong-view-id-' . $out['view'];

	if ( $out['class'] ) {
		$out['container_class'] .= ' ' . str_replace( ',', ' ', $out['class'] );
	}
	if ( is_rtl() ) {
		$out['container_class'] .= ' rtl';
	}
	WPMST()->set_atts( $out );

	/**
	 * MODE: FORM
	 */
	if ( $out['form'] )
		return wpmtst_form_shortcode( $out );

	/**
	 * MODE: DISPLAY (default)
	 */
	return wpmtst_display_view( $out );
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
 * read_more shortcode
 *
 * @since 1.21.0
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function wpmtst_read_more_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'page'  => '',
			'class' => '',
		),
		normalize_empty_atts( $atts ), 'read_more'
	);
	return wpmtst_readmore_shortcode( $atts, $content );
}
add_shortcode( 'read_more', 'wpmtst_read_more_shortcode' );

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
