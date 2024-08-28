<?php
/**
 * Content functions.
 */

/**
 * Based on the_content().
 *
 * @param null $more_link_text
 * @param bool $strip_teaser
 *
 * @return string
 */
function wpmtst_the_content_filtered( $more_link_text = null, $strip_teaser = false ) {
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
		return esc_html__( 'There is no excerpt because this is a protected post.', 'strong-testimonials' );
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
 * Based on wp_trim_excerpt(). On wpmtst_get_the_excerpt hook.
 *
 * @since 2.26.0
 * @param string $excerpt The manual excerpt.
 *
 * @return string
 */
function wpmtst_trim_excerpt( $excerpt = '' ) {
	$raw_excerpt = $excerpt;

	/**
	 * Filter hybrid value here to allow individual overrides.
	 */
	$hybrid = apply_filters( 'wpmtst_is_hybrid_content', false );

		$text           = wpmtst_get_the_prepared_text();
		$excerpt_length = 0;
		$excerpt_more   = '';

		// Create excerpt if post has no manual excerpt.
	if ( empty( $excerpt ) ) {
		$excerpt_length = apply_filters( 'excerpt_length', 55 );
		$excerpt_more   = apply_filters( 'excerpt_more', ' [&hellip;]' );
	}

		$excerpt = wpmtst_trim_words( $text, $excerpt_length, $excerpt_more, $hybrid, $excerpt );

	/**
	 * Filters the trimmed excerpt string.
	 *
	 * @param string $text        The trimmed text.
	 * @param string $raw_excerpt The text prior to trimming.
	 */
	return apply_filters( 'wpmtst_trim_excerpt', $excerpt, $raw_excerpt );
}

/**
 * Prepare the post content.
 *
 * @param bool $hybrid
 * @since 2.33.0
 *
 * @return string
 */
function wpmtst_get_the_prepared_text( $hybrid = false ) {
	$text = get_the_content( '' );

	if ( function_exists( 'et_core_is_builder_used_on_current_request' ) && et_core_is_builder_used_on_current_request() ) {
		$text = wp_strip_all_tags( et_strip_shortcodes( $text ), true );
	} elseif ( ! $hybrid ) {
		$text = strip_shortcodes( $text );
	}

	$text = apply_filters( 'wpmtst_the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );

	return $text;
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

	$link_text = sprintf(
		'%s<span class="screen-reader-text"> "%s"</span>',
		apply_filters( 'wpmtst_read_more_post_link_text', esc_html( WPMST()->atts( 'more_post_text' ) ), WPMST()->atts() ),
		get_the_title()
	);

	$link_class = apply_filters( 'wpmtst_read_more_post_link_class', 'readmore' );

	if ( apply_filters( 'wpmtst_is_hybrid_content', false ) ) {
		// no href
		$link = sprintf(
			'<a aria-expanded="false" aria-controls="more-%1$d" class="%2s readmore-toggle"><span class="readmore-text" data-more-text="%4$s" data-less-text="%5$s">%3$s</span></a>',
			get_the_ID(), // 1
			$link_class,  // 2
			$link_text,   // 3
			esc_attr( WPMST()->atts( 'more_post_text' ) ), // 4
			esc_attr( WPMST()->atts( 'less_post_text' ) ) // 5
		);
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
 * @param bool $hybrid
 *
 * @return string
 */
function wpmtst_trim_words( $text, $num_words = 55, $more = null, $hybrid = false, $excerpt = '' ) {
	if ( null === $more ) {
		$more = __( '&hellip;', 'strong-testimonials' );
	}

	if ( WPMST()->atts( 'html_content' ) || ! empty( $excerpt ) ) {
		$full_text = strip_tags( $text, '<p><br><img><b><strong><i><em><ul><ol><li><del><a><sup>' );
	} else {
		$full_text = strip_tags( $text );
	}

	$text = strip_tags( $text );

	/*
	 * translators: If your word count is based on single characters (e.g. East Asian characters),
	 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
	 * Do not translate into your own language.
	 */
	if ( ( strpos( esc_html_x( 'words', 'Word count type. Do not translate!', 'strong-testimonials' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) || apply_filters( 'wpmtst_excerpt_by_characters_count', false ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep         = '';
	} else {
		$offset      = $hybrid ? 0 : $num_words + 1;
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $offset, PREG_SPLIT_NO_EMPTY );
		$sep         = ' ';
	}

	if ( count( $words_array ) > $num_words ) {
		if ( $hybrid ) {
			$text = wpmtst_assemble_hybrid( $words_array, $num_words, $sep, $more, $full_text, $excerpt );
		} else {
			$text = wpmtst_assemble_excerpt( $words_array, $sep, $more, $excerpt );
		}
	} else {
		$text = implode( $sep, $words_array );
	}

	return $text;
}

/**
 * Assemble excerpt from trimmed array.
 *
 * @param $words_array
 * @param $sep
 * @param $more
 * @since 2.33.0
 *
 * @return string
 */
function wpmtst_assemble_excerpt( $words_array, $sep, $more, $excerpt = '' ) {
	if ( ! empty( $excerpt ) ) {
		return $excerpt;
	}
	array_pop( $words_array );
	$text = implode( $sep, $words_array );

	return $text . $more;
}

/**
 * Assemble excerpt + rest of content in hidden span.
 *
 * @param $words_array
 * @param $num_words
 * @param $sep
 * @param $more
 * @since 2.33.0
 *
 * @return string
 */
function wpmtst_assemble_hybrid( $words_array, $num_words, $sep, $more, $full_text, $excerpt = '' ) {
	$ellipsis = wpmtst_ellipsis();
	if ( $ellipsis ) {
		$ellipsis = '<div class="ellipsis" style="display:inline;">' . $ellipsis . ' </div>';
		/* ! This space is important:                        ^       */
	}
	if ( ! empty( $excerpt ) ) {
		$first_half = $excerpt;
	} else {
		$first_half = implode( $sep, array_slice( $words_array, 0, $num_words ) );
	}

	$wrap_open_class = '';

	if ( WPMST()->atts( 'html_content' ) || ! empty( $excerpt ) ) {
		$wrap_open_class = 'all-html';
	}

	$wrap_open_excerpt  = '<div class="readmore-excerpt animated ' . esc_attr( $wrap_open_class ) . '"> ';
	$wrap_open          = '<div class="readmore-content animated"  id="more-' . esc_attr( get_the_ID() ) . '" hidden> ';
	$wrap_close         = ' </div>';
	$wrap_close_excerpt = ' </div>';
	$first_half         = '<div style="display:inline;">' . wp_kses_post( $first_half ) . '</div>';

	return $wrap_open_excerpt . $first_half . $ellipsis . ' ' . $wrap_close_excerpt . $wrap_open . ' ' . $full_text . $wrap_close . $more;
	/* ! This space is important:                                        ^                                                  */
}
