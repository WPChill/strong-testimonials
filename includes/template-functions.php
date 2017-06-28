<?php
/**
 * Template Functions
 *
 * @package Strong_Testimonials
 */

/**
 * Template function for showing a View.
 *
 * @since 1.25.0
 *
 * @param null $id
 */
function strong_testimonials_view( $id = null ) {
	if ( ! $id ) return;

	$out   = WPMST()->get_view_defaults();
	$pairs = array();
	$atts  = array( 'id' => $id );
	$out   = WPMST()->parse_view( $out, $pairs, $atts );

	echo wpmtst_render_view( $out );
}

/**
 * @param string $before
 * @param string $after
 */
function wpmtst_the_title( $before = '', $after = '' ) {
	if ( WPMST()->atts( 'title' ) && get_the_title() ) {
		echo $before . get_the_title() . $after;
	}
}


/**
 * Display the testimonial content.
 * Used by the plugin and as a template function.
 *
 * @since 1.24.0
 * @since 2.4.0 Run content through core WordPress filters only, instead of all filters added to the_excerpt()
 *              or the_content() in order to to be compatible with NextGEN Gallery and to prevent other plugins
 *              from unconditionally adding content like share buttons, etc.
 * @since 2.11.5 Run specific filters on `wpmtst_the_content` hook.
 * @since 2.20.0 For automatic excerpts, run `wpautop` after truncating.
 *               Add `wp_make_content_images_responsive`.
 */
function wpmtst_the_content() {

	if ( WPMST()->atts( 'truncated' ) ) {

	    // Force automatic excerpt. Based on wp_trim_excerpt.

		$content = get_the_content();

		$content = strip_shortcodes( $content );

		$content = $GLOBALS['wp_embed']->autoembed( $content );
		$content = wptexturize( $content );

		add_filter( 'excerpt_more', 'wpmtst_excerpt_more', 20 );
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
		remove_filter( 'excerpt_more', 'wpmtst_excerpt_more', 20 );

		$excerpt_length = WPMST()->atts( 'use_default_length' ) ? 55 : WPMST()->atts( 'excerpt_length' );

		// wp_trim_words will remove line breaks. So no paragraphs.
		$content        = wp_trim_words( $content, $excerpt_length, $excerpt_more );

		// Run wpautop just to wrap entire string in <p> for consistent style.
		$content = wpautop( $content );

		$content = wp_make_content_images_responsive( $content );

		$content = convert_smilies( $content );

	} elseif ( WPMST()->atts( 'excerpt' ) ) {

		// Based on the_excerpt.

		$use_default_length = WPMST()->atts( 'use_default_length' );

		if ( ! $use_default_length ) {
			add_filter( 'excerpt_length', 'wpmtst_excerpt_length', 20 );
		}

		add_filter( 'excerpt_more', 'wpmtst_excerpt_more', 20 );
		$content = get_the_excerpt();
		remove_filter( 'excerpt_more', 'wpmtst_excerpt_more', 20 );

		if ( ! $use_default_length ) {
			remove_filter( 'excerpt_length', 'wpmtst_excerpt_length', 20 );
		}

		$content = wptexturize( $content );

        if ( WPMST()->atts( 'more_full_post' ) ) {
			$excerpt_more = wpmtst_excerpt_more_full_post();
			$content      .= $excerpt_more;
		}

		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
		$content = convert_smilies( $content );

	} else {

		// Based on the_content.

		$content = get_the_content( apply_filters( 'wpmtst_more_link_text', null ) );

		$content = $GLOBALS['wp_embed']->autoembed( $content );
		$content = wptexturize( $content );

		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
		$content = wp_make_content_images_responsive( $content );
		$content = convert_smilies( $content );

	}

	echo apply_filters( 'wpmtst_the_content', $content );
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
	if ( 'wpm-testimonial' == get_post_type() ) {
		$excerpt_length = WPMST()->atts( 'excerpt_length' );
		if ( $excerpt_length ) {
			$words = $excerpt_length;
		}
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
	if ( 'wpm-testimonial' == get_post_type() ) {
		if ( WPMST()->atts( 'more_post' ) && ! WPMST()->atts( 'use_default_more' ) ) {
			return wpmtst_get_excerpt_more_post();
		}
	}

	return $more;
}


/**
 * Return "Read more" for automatic excerpts.
 *
 * @return string
 */
function wpmtst_get_excerpt_more_post() {
    $dots = WPMST()->atts( 'more_post_ellipsis' ) ? ' &hellip;' : '';
    // This is where the "for both automatic and manual excerpts" happens
	if ( WPMST()->atts( 'excerpt' ) && WPMST()->atts( 'more_full_post' ) ) {
		return $dots;
	} else {
		return $dots . ' ' . wpmtst_get_excerpt_more_link();
	}
}


/**
 * Return "Read more" for manual excerpts.
 *
 * @return string
 */
function wpmtst_excerpt_more_full_post() {
    $link = apply_filters( 'wpmtst_manual_excerpt_read_more', wpmtst_get_excerpt_more_link() );
	return '<div class="testimonial-readmore">' . $link . '</div>';
}


/**
 * Construct the "Read more" link (both automatic and manual).
 *
 * @return string
 */
function wpmtst_get_excerpt_more_link() {
	$link = sprintf( '<a href="%1$s" class="readmore">%2$s</a>',
		esc_url( get_permalink() ),
		sprintf( '%s<span class="screen-reader-text"> "%s"</span>',
			apply_filters( 'wpmtst_read_more_page_link_text', WPMST()->atts( 'more_post_text' ), WPMST()->atts() ), get_the_title() ) );

	return $link;
}

/**
 * Assemble link to secondary "Read more" page.

 * @since 2.10.0
 */
function wpmtst_read_more_page() {
	$atts = WPMST()->atts();

	if ( $atts['more_page'] && $atts['more_page_id'] ) {

		$permalink = '';
		if ( is_numeric( $atts['more_page_id'] ) ) {
			$permalink = wpmtst_get_permalink( $atts['more_page_id'] );
		}
		$permalink = apply_filters( 'wpmtst_readmore_page_link', $permalink, $atts );

		if ( $permalink ) {
			$default_view = apply_filters( 'wpmtst_view_default', get_option( 'wpmtst_view_default' ) );

			if ( isset( $atts['more_page_text'] ) && $atts['more_page_text'] ) {
				$link_text = $atts['more_page_text'];
			} else {
				$link_text = $default_view['more_page_text'];
			}

			$link_text = apply_filters( 'wpmtst_read_more_page_link_text', $link_text, $atts );

			if ( 'wpmtst_after_testimonial' == $atts['more_page_hook'] ) {
				$classname = 'readmore';
			} else {
				$classname = 'readmore-page';
			}
			$classname = apply_filters( 'wpmtst_read_more_page_class', $classname );
			echo sprintf( '<div class="%s"><a href="%s">%s</a></div>', $classname, esc_url( $permalink ), $link_text );
		}

	}
}

/**
 * Localization filter.
 *
 * @since 2.23.0 As separate function.
 * @param $text
 * @param $atts
 *
 * @return string
 */
function wpmtst_read_more_page_link_text_l10n( $text, $atts ) {
	return apply_filters( 'wpmtst_l10n', $text, 'strong-testimonials-read-more', sprintf( 'View %s : Read more (page or post)', $atts['view'] ) );

}
add_filter( 'wpmtst_read_more_page_link_text', 'wpmtst_read_more_page_link_text_l10n', 10, 2 );

/**
 * Get permalink by ID or slug.
 *
 * @since 1.25.0
 * @param $page_id
 *
 * @return false|string
 */
function wpmtst_get_permalink( $page_id ) {
	if ( ! is_numeric( $page_id ) ) {
		$page = get_page_by_path( $page_id );
		$page_id = $page->ID;
	}
	return get_permalink( $page_id );
}

/**
 * Prevent page scroll when clicking the More link.
 *
 * @since 2.10.0
 * @param $link
 *
 * @return mixed
 */
function wpmtst_remove_more_link_scroll( $link ) {
	if ( 'wpm-testimonial' == get_post_type() )
		$link = preg_replace( '|#more-[0-9]+|', '', $link );

	return $link;
}
add_filter( 'the_content_more_link', 'wpmtst_remove_more_link_scroll' );


/**
 * Display the thumbnail.
 *
 * TODO WP 4.2+ has better filters.
 *
 * @param null $size
 * @param string $before
 * @param string $after
 */
function wpmtst_the_thumbnail( $size = null, $before = '<div class="testimonial-image">', $after = '</div>' ) {
	if ( ! WPMST()->atts( 'thumbnail' ) )
		return;

	$img = wpmtst_get_thumbnail( $size );
	if ( $img ) {
		echo $before . $img . $after;
	}
}

/**
 * @param null $size
 *
 * @return mixed|string
 */
function wpmtst_get_thumbnail( $size = null ) {
	if ( ! WPMST()->atts( 'thumbnail' ) )
		return '';

	// let arg override view setting
	$size = ( null === $size ) ? WPMST()->atts( 'thumbnail_size' ) : $size ;
	if ( 'custom' == $size ) {
		$size = array( WPMST()->atts( 'thumbnail_width' ), WPMST()->atts( 'thumbnail_height' ) );
	}

	$id   = get_the_ID();
	$img  = '';

	// check for a featured image
	if ( has_post_thumbnail( $id ) ) {

		// show featured image
		$img = get_the_post_thumbnail( $id, $size );

	} else {

		// no featured image, now what?

        $dimensions = apply_filters( 'wpmtst_gravatar_size', $size );

		if ( 'yes' == WPMST()->atts( 'gravatar' ) ) {
			// view > gravatar > show gravatar (use default, if not found)

			$img = get_avatar( wpmtst_get_field( 'email' ), apply_filters( 'wpmtst_gravatar_size', $size ) );
            //$img = get_avatar( wpmtst_get_field( 'email' ), $dimensions['width'], '', '', $dimensions );

		} elseif ( 'if' == WPMST()->atts( 'gravatar' ) ) {
			// view > gravatar > show gravatar only if found (and has email)

			if ( wpmtst_get_field( 'email' ) ) {
				// get_avatar will return false if not found (via filter)
				$img = get_avatar( wpmtst_get_field( 'email' ), apply_filters( 'wpmtst_gravatar_size', $size ) );
				//$img = get_avatar( wpmtst_get_field( 'email' ), $dimensions['width'], '', '', $dimensions );
			}
		}

	}

	return apply_filters( 'wpmtst_thumbnail_img', $img, $id );
}

/**
 * Filter the thumbnail image.
 * Used to add link for a lightbox. Will not affect avatars.
 *
 * @param $img
 * @param $post_id
 * @since 1.23.0
 * @since 2.9.4 classes and filter
 *
 * @return string
 */
function wpmtst_thumbnail_img( $img, $post_id ) {
	if ( WPMST()->atts( 'lightbox' ) ) {
		$url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
		if ( $url ) {
			$classes = join( ' ', array_unique( apply_filters( 'wpmtst_thumbnail_link_class', array() ) ) );
			$img = '<a class="' . $classes . '" href="' . $url . '">' . $img . '</a>';
			/**
			 * Adjust settings for Simple Colorbox plugin.
			 * TODO do the same for other lightbox plugins
			 */
			if ( defined( 'SIMPLECOLORBOX_VERSION' ) ) {
                wp_enqueue_script( 'wpmtst-colorbox' );
			}
		}
	}
	return $img;
}
add_filter( 'wpmtst_thumbnail_img', 'wpmtst_thumbnail_img', 10, 2 );

/**
 * Filter thumbnail link classes.
 *
 * @since 2.9.4
 * @param $classes
 *
 * @return array
 */
function wpmtst_thumbnail_link_class( $classes ) {
	if ( ! is_array( $classes ) )
		$classes = preg_split( '#\s+#', $classes );

	// FooBox (both free and pro versions)
	if ( defined( 'FOOBOXFREE_VERSION' ) || class_exists( 'fooboxV2' ) )
		$classes[] = 'foobox';

	return $classes;
}
add_filter( 'wpmtst_thumbnail_link_class', 'wpmtst_thumbnail_link_class' );

/**
 * Filter the gravatar size.
 *
 * @param array $size
 * @since 1.23.0
 * @return mixed
 */
function wpmtst_gravatar_size_filter( $size = array( 150, 150 ) ) {
	// avatars are square so get the width of the requested size
	if ( is_array( $size ) ) {
		// if dimension array
		$gravatar_size = $size[0];
	} else {
		// if named size
		$image_sizes   = wpmtst_get_image_sizes();
		$gravatar_size = $image_sizes[$size]['width'];
		//$gravatar_size = array( 'width' => $image_sizes[$size]['width'], 'height' => $image_sizes[$size]['height'] );
	}
	return $gravatar_size;
}
add_filter( 'wpmtst_gravatar_size', 'wpmtst_gravatar_size_filter' );

/**
 * Checks to see if the specified email address has a Gravatar image.
 *
 * Thanks Tom McFarlin https://tommcfarlin.com/check-if-a-user-has-a-gravatar/
 * @param $email_address string The email of the address of the user to check
 * @return bool Whether or not the user has a gravatar
 * @since 1.23.0
 */
function wpmtst_has_gravatar( $email_address ) {
	// Build the Gravatar URL by hashing the email address
	$url = 'http://www.gravatar.com/avatar/' . md5( strtolower( trim ( $email_address ) ) ) . '?d=404';

	// Now check the headers...
	$headers = @get_headers( $url );

	// If 200 is found, the user has a Gravatar; otherwise, they don't.
	return preg_match( '|200|', $headers[0] ) ? true : false;
}

/**
 * Before assembling avatar HTML.
 *
 * @param $url
 * @param $id_or_email
 * @param $args
 *
 * @return bool
 */
function wpmtst_get_avatar( $url, $id_or_email, $args ) {
	if ( 'if' == WPMST()->atts( 'gravatar' ) && ! wpmtst_has_gravatar( $id_or_email ) )
		return false;

	return $url;
}

/**
 * Print the date.
 *
 * @param string $format
 * @param string $class
 */
function wpmtst_the_date( $format = '', $class = '' ) {
	global $post;
	if ( ! $post )
		return;

	if ( ! $format )
		$format = get_option( 'date_format' );

	$the_date = apply_filters( 'wpmtst_the_date', mysql2date( $format, $post->post_date ), $format, $post );
	echo '<div class="' . $class . '">' . $the_date . '</div>';
}

/**
 * Display the client section.
 *
 * @since 1.21.0
 */
function wpmtst_the_client() {
	$atts = WPMST()->atts();
	if ( isset( $atts['client_section'] ) ) {
		echo wpmtst_client_section( $atts['client_section'] );
	}
}

/**
 * Assemble the client section.
 *
 * @since 1.21.0
 *
 * @param array $client_section An array of client fields.
 * Array
 * (
 * 	[0] => Array
 * 	(
 * 		[field] => client_name
 * 		[type] => text
 * 		[class] => testimonial-name
 * 	)
 *
 * 	[1] => Array
 * 	(
 * 		[field] => company_name
 * 		[type] => link
 * 		[class] => testimonial-company
 * 		[url] => company_website
 * 		[link_text] => value
 * 		[link_text_custom] =>
 * 		[new_tab] => 1
 * 	)
 * )
 *
 * @return mixed
 */
function wpmtst_client_section( $client_section ) {
	global $post;

	$options = get_option( 'wpmtst_options' );
	$html = $output = '';

	foreach ( $client_section as $field ) {

		// Get field meta.
		$field['field_label'] = wpmtst_get_field_label( $field );
		if ( $default_display_value = wpmtst_get_field_default_display_value( $field ) ) {
			$field['default_display_value'] = $default_display_value;
		}
		if ( $shortcode_on_display = wpmtst_get_field_shortcode_on_display( $field ) ) {
			$field['shortcode_on_display'] = $shortcode_on_display;
		}

		switch ( $field['type'] ) {

			case 'link' :
			case 'link2' :
				// use default if missing
				if ( ! isset( $field['link_text'] ) )
					$field['link_text'] = 'value';

				/**
				 * Get link text and an alternate in case the URL is empty;
				 * e.g. display the domain if no company name given
				 * but don't display 'LinkedIn' if no URL given.
				 */
				switch ( $field['link_text'] ) {
					case 'custom' :
						$text = $field['link_text_custom'];
						$output = '';
						break;
					case 'label' :
						$text = $field['field_label'];
						$output = '';
						break;
					default : // value
						$text = get_post_meta( $post->ID, $field['field'], true );
						// if no URL (next condition), show the alternate (the field)
						$output = $text;
				}

				if ( $field['url'] ) {

					$url = get_post_meta( $post->ID, $field['url'], true );
					if ( $url ) {
						if ( isset( $field['new_tab'] ) && $field['new_tab'] ) {
						    $newtab = ' target="_blank"';
						} else {
						    $newtab = '';
						}

						// TODO Abstract this global fallback technique.
						$is_nofollow = get_post_meta( $post->ID, 'nofollow', true );
						if ( 'default' == $is_nofollow || '' == $is_nofollow ) {
						    // convert default to (yes|no)
						    $is_nofollow = $options['nofollow'] ? 'yes' : 'no';
						}
						if ( 'yes' == $is_nofollow ) {
							$nofollow = ' rel="nofollow"';
						} else {
							$nofollow = '';
						}

						// if field empty, use domain instead
						if ( ! $text || is_array( $text ) ) {
							$text = preg_replace( '(^https?://)', '', $url );
						}

						$output = sprintf( '<a href="%s"%s%s>%s</a>', $url, $newtab, $nofollow, $text );
					}

				}
				break;

			case 'date' :
				$format = isset( $field['format'] ) && $field['format'] ? $field['format'] : get_option( 'date_format' );

				// Fall back to post_date if submit_date missing.
				$fallback = $post->post_date;
				$the_date = get_post_meta( $post->ID, $field['field'], true );
				if ( ! $the_date ) {
					$the_date = $fallback;
				}

				$the_date = mysql2date( $format, $the_date );

				if ( get_option( 'date_format' ) != $format ) {
					// Requires PHP 5.3+
					if ( version_compare( PHP_VERSION, '5.3' ) >= 0 ) {
						$new_date = DateTime::createFromFormat( get_option( 'date_format' ), $the_date );
						if ( $new_date ) {
							$the_date = $new_date->format( $format );
						}
					}
				}

				$output = apply_filters( 'wpmtst_the_date', $the_date, $format, $post );
				break;

			case 'category' :
				$categories = get_the_terms( $post->ID, 'wpm-testimonial-category' );
				if ( $categories && ! is_wp_error( $categories ) ) {
					$list = array();
					foreach ( $categories as $cat ) {
						$list[] = $cat->name;
					}
					$output = join( ", ", $list );
				} else {
				    $output = '';
				}
				break;

			case 'shortcode' :
				$output = do_shortcode( get_post_meta( $post->ID, $field['field'], true ) );
				if ( isset( $field['shortcode_on_display'] ) && $field['shortcode_on_display'] ) {
					$output = do_shortcode( $field['shortcode_on_display'] );
				}
				break;

			case 'rating' :
				$output = get_post_meta( $post->ID, $field['field'], true );
				// Check default value
				if ( '' == $output && isset( $field['default_display_value'] ) && $field['default_display_value'] ) {
					$output = $field['default_display_value'];
				}
				// Convert number to stars
				if ( $output ) {
					$output = wpmtst_star_rating_display( $output, 'in-view', false );
				}
				break;

			default :
				// text field
				$output = get_post_meta( $post->ID, $field['field'], true );
				if ( '' == $output && isset( $field['default_display_value'] ) && $field['default_display_value'] ) {
					$output = $field['default_display_value'];
				}

		}

		if ( $output ) {
			if ( isset( $field['before'] ) && $field['before'] ) {
				$output = '<span class="testimonial-field-before">' . $field['before'] . '</span>' . $output;
			}
			$html .= '<div class="' . $field['class'] . '">' . $output . '</div>';
		}
	}

	return $html;
}

function wpmtst_container_class() {
	echo apply_filters( 'wpmtst_container_class', WPMST()->atts( 'container_class' ) );
}

function wpmtst_container_data() {
	$data_array = apply_filters( 'wpmtst_container_data', WPMST()->atts( 'container_data' ) );
	if ( $data_array ) {
		$data = '';
		foreach ( $data_array as $attr => $value ) {
			$data .= " data-$attr=$value";
		}
		echo $data;
	}
}

function wpmtst_content_class() {
	echo apply_filters( 'wpmtst_content_class', WPMST()->atts( 'content_class' ) );
}

function wpmtst_post_class( $args = null ) {
	echo apply_filters( 'wpmtst_post_class', WPMST()->atts( 'post_class' ) . ' post-' . get_the_ID(), $args );
}

/**
 * Echo custom field.
 *
 * @since 1.11.0
 * @param null  $field
 * @param array $args
 */
function wpmtst_field( $field = null, $args = array() ) {
	echo wpmtst_get_field( $field, $args );
}

/**
 * Fetch custom field.
 *
 * Thanks to Matthew Harris.
 * @link https://github.com/cdillon/strong-testimonials/issues/2
 * @param $field
 * @param array $args
 * @since 1.15.7
 *
 * @return mixed|string
 */
function wpmtst_get_field( $field, $args = array() ) {
	if ( ! $field ) return '';

	global $post;

	switch( $field ) {

		// Apply a character limit to post content.
		case 'truncated' :
			$html = wpmtst_truncate( $post->post_content, $args['char_limit'] );
			break;

		// Get the custom field.
		default :
			$html = get_post_meta( $post->ID, $field, true );

	}
	return $html;
}

/**
 * Custom pagination. Pluggable.
 *
 * Thanks http://callmenick.com/post/custom-wordpress-loop-with-pagination
 *
 * @param string $numpages
 * @param string $pagerange
 */
if ( ! function_exists( 'wpmtst_standard_pagination' ) ) :
function wpmtst_standard_pagination() {

	$query = WPMST()->get_query();
	$paged = wpmtst_get_paged();

	$numpages = $query->max_num_pages;
	if ( ! $numpages ) {
		$numpages = 1;
	}

	$pagination_args = array(
		'base'               => get_pagenum_link( 1 ) . '%_%',
		'format'             => 'page/%#%',
		'total'              => $numpages,
		'current'            => $paged,
		'show_all'           => false,
		'end_size'           => 1,
		'mid_size'           => 2,
		'prev_next'          => false,
		//'prev_text'          => __( '&laquo;' ),
		//'next_text'          => __( '&raquo;' ),
		'type'               => 'list',
		'add_args'           => false,
		'add_fragment'       => '', // a string to append to each link
		'before_page_number' => '',
		'after_page_number'  => '',
	);

	$paginate_links = paginate_links( apply_filters( 'wpmtst_pagination_args', $pagination_args ) );

	if ( $paginate_links ) {
		echo "<nav class='standard-pagination'>";
		//echo "<span class='page-numbers page-num'>Page " . $paged . " of " . $numpages . "</span> ";
		echo $paginate_links;
		echo "</nav>";
	}
}
endif;


function wpmtst_get_paged() {
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	}
	elseif ( get_query_var( 'page' ) ) { // static front page
		$paged = get_query_var( 'page' );
	}
	else {
		$paged = 1;
	}

	return $paged;
}

/*
// alternate pagination style
function wpmtst_new_pagination_2() {
	$query = WPMST()->get_query();
	if ($query->max_num_pages > 1) { ?>
		<nav class="prev-next-posts">
			<div class="prev-posts-link">
				<?php echo get_next_posts_link( 'Older Entries', $query->max_num_pages ); // display older posts link ?>
			</div>
			<div class="next-posts-link">
				<?php echo get_previous_posts_link( 'Newer Entries' ); // display newer posts link ?>
			</div>
		</nav>
	<?php }
}
*/

/**
 * Single testimonial custom fields. Pluggable.
 *
 * @since 2.22.0
 */
if ( ! function_exists( 'wpmtst_single_template_client' ) ) :

function wpmtst_single_template_client() {
    $view = wpmtst_find_single_template_view();
    if ( $view && isset( $view['client_section'] ) ) {
	    foreach ( $view['client_section'] as $field ) {
		    if ( 'rating' == $field['type'] ) {
			    wp_enqueue_style( 'wpmtst-rating-display' );
			    break;
		    }
	    }
	    echo wpmtst_client_section( $view['client_section'] );
    }
}

endif;
