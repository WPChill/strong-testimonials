<?php
/**
 * Content functions.
 *
 * @package Strong_Testimonials
 */

/**
 * Based on the_content().
 *
 * @param null $more_link_text
 * @param bool $strip_teaser
 *
 * @return string
 */
function wpmtst_the_content_filtered( $more_link_text = null, $strip_teaser = false) {
	$content = get_the_content( $more_link_text, $strip_teaser );
	$content = apply_filters( 'wpmtst_the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	return $content;
}

/**
 * Based on the_excerpt().
 *
 * @since 2.26.0
 */
function wpmtst_the_excerpt_filtered() {
	return apply_filters( 'wpmtst_the_excerpt', wpmtst_get_the_excerpt() );
}

/**
 * Based on get_the_excerpt().
 *
 * @since 2.26.0
 * @param null $post
 *
 * @return string
 */
function wpmtst_get_the_excerpt( $post = null ) {
	$post = get_post( $post );
	if ( empty( $post ) ) {
		return '';
	}

	if ( post_password_required( $post ) ) {
		return __( 'There is no excerpt because this is a protected post.' );
	}

	/**
	 * Filters the retrieved post excerpt.
	 *
	 * @param string $post_excerpt The post excerpt.
	 * @param WP_Post $post Post object.
	 */
	return apply_filters( 'wpmtst_get_the_excerpt', $post->post_excerpt, $post );
}

/**
 * Force bypass of the manual excerpt.
 *
 * @since 2.26.0
 * @param $text
 *
 * @return string
 */
function wpmtst_bypass_excerpt( $text ) {
	return '';
}

/**
 * Based on wp_trim_excerpt().
 *
 * @since 2.26.0
 * @param string $text
 *
 * @return string
 */
function wpmtst_trim_excerpt( $text = '' ) {
	$raw_excerpt = $text;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = strip_shortcodes( $text );
		$text = apply_filters( 'wpmtst_the_content', $text );
		$text = str_replace(']]>', ']]&gt;', $text);

		/**
		 * Filters the number of words in an excerpt.
		 *
		 * @param int $number The number of words. Default 55.
		 */
		if ( WPMST()->atts( 'use_default_length' ) ) {
			$excerpt_length = apply_filters( 'excerpt_length', 55 );
		} else {
			$excerpt_length = apply_filters( 'wpmtst_excerpt_length', 55 );
		}
		/**
		 * Filters the string in the "more" link displayed after a trimmed excerpt.
		 *
		 * @param string $more_string The string shown within the more link.
		 */
		$default_more = ' [&hellip;]';
		if ( WPMST()->atts( 'use_default_more' ) ) {
			$excerpt_more = apply_filters( 'excerpt_more', $default_more );
		} else {
			$excerpt_more = apply_filters( 'wpmtst_excerpt_more', $default_more );
		}
		//$text = wpmtst_trim_words( $text, $excerpt_length, $excerpt_more, WPMST()->atts( 'more_post_in_place') );
		$text = wpmtst_trim_words( $text, $excerpt_length, $excerpt_more, true );
	}
	/**
	 * Filters the trimmed excerpt string.
	 *
	 * @param string $text        The trimmed text.
	 * @param string $raw_excerpt The text prior to trimming.
	 */
	return apply_filters( 'wpmtst_trim_excerpt', $text, $raw_excerpt );
}

/**
 * Maybe add read-more to manual excerpt.
 *
 * @since 2.26.0
 * @param $excerpt
 *
 * @return string
 */
function wpmtst_custom_excerpt_more( $excerpt ) {
	$excerpt_more = '';
	if ( has_excerpt() ) {
		if ( WPMST()->atts( 'more_full_post' ) ) {
			if ( WPMST()->atts( 'use_default_more' ) ) {
				$excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]' );
			} else {
				$excerpt_more = apply_filters( 'wpmtst_excerpt_more', ' [&hellip;]' );
			}
		}
	}

	return $excerpt . $excerpt_more;
}

/**
 * Modify the excerpt length.
 *
 * @since 2.10.0
 * @param $words
 *
 * @return int
 */
function wpmtst_excerpt_length( $words ) {
	$excerpt_length = WPMST()->atts( 'excerpt_length' );
	if ( $excerpt_length ) {
		$words = $excerpt_length;
	}

	return $words;
}

/**
 * Modify the automatic excerpt "Read more" link (via WP filter).
 *
 * @since 2.10.0
 * @param $more
 *
 * @return string
 */
function wpmtst_excerpt_more( $more ) {
	return ' ' . wpmtst_get_excerpt_more_link();
}

/**
 * Construct the "Read more" link (both automatic and manual).
 *
 * @since 2.27.0 Filters on URL and full link.
 *
 * @return string
 */
function wpmtst_get_excerpt_more_link() {
	$url = apply_filters( 'wpmtst_read_more_post_url', get_permalink(), WPMST()->atts() );

	$link_text = sprintf( '%s<span class="screen-reader-text"> "%s"</span>',
		apply_filters( 'wpmtst_read_more_post_link_text', WPMST()->atts( 'more_post_text' ), WPMST()->atts() ), get_the_title()
	);

	$link_class = apply_filters( 'wpmtst_read_more_post_link_class', 'readmore' );

	//if ( WPMST()->atts( 'more_post_in_place' ) ) {
	if ( true ) {
	    // no href
	    $link = sprintf( '<a aria-expanded="false" aria-controls="more-%d" class="%s readmore-toggle"><span class="readmore-text">%s</span></a>', get_the_ID(), $link_class, $link_text );
	} else {
		$link = sprintf( '<a href="%s" class="%s">%s</a>', esc_url( $url ), $link_class, $link_text );
	}

	return apply_filters( 'wpmtst_read_more_post_link', $link );
}

/**
 * Based on wp_trim_words().
 *
 * @param $text
 * @param int $num_words
 * @param null $more
 * @param bool $more_in_place
 *
 * @return string
 */
function wpmtst_trim_words( $text, $num_words = 55, $more = null, $more_in_place = false ) {
	if ( null === $more ) {
		$more = __( '&hellip;' );
	}

	$text = wp_strip_all_tags( $text );

	/*
	 * translators: If your word count is based on single characters (e.g. East Asian characters),
	 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
	 * Do not translate into your own language.
	 */
	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep = '';
	} else {
		if ( $more_in_place ) {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, 0, PREG_SPLIT_NO_EMPTY );
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		}
		$sep = ' ';
	}

	if ( count( $words_array ) > $num_words ) {

		if ( $more_in_place ) {

			$space = __( '&nbsp;' );

			$ellipsis = WPMST()->atts( 'more_post_ellipsis' ) ? __( '&hellip;' ) : '';
			$ellipsis = '<span class="ellipsis">' . $ellipsis . '</span>' . $space;

			$first_half  = implode( $sep, array_slice( $words_array, 0, $num_words ) );
			$second_half = implode( $sep, array_slice( $words_array, $num_words ) );

			$wrap_open  = '<span class="readmore-content animated" id="more-' . get_the_ID() . '" hidden>';
			$wrap_close = $space . '</span>';

			$text = $first_half . $ellipsis . $wrap_open . $second_half . $wrap_close . $more;

		} else {

			array_pop( $words_array );
			$text = implode( $sep, $words_array );
			$text = $text . $more;

		}

	} else {
		$text = implode( $sep, $words_array );
	}

	return $text;
}
