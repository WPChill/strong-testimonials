<?php
/**
 * Strong Testimonials - Shims
 */
 
 
/*
 * Shim: has_shortcode < WP 3.6
 */
if ( ! function_exists( 'has_shortcode' ) ) {
	function has_shortcode( $content, $tag ) {
		if ( false === strpos( $content, '[' ) ) {
			return false;
		}

		if ( shortcode_exists( $tag ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
			if ( empty( $matches ) )
				return false;

			foreach ( $matches as $shortcode ) {
				if ( $tag === $shortcode[2] )
					return true;
			}
		}
		return false;
	}
}


/*
 * Shim: shortcode_exists < WP 3.6
 */
if( ! function_exists( 'shortcode_exists' ) ) {
	function shortcode_exists( $tag ) {
		global $shortcode_tags;
		return array_key_exists( $tag, $shortcode_tags );
	}
}
