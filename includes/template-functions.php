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
 */
function wpmtst_the_content() {
	if ( WPMST()->atts( 'truncated' ) ) {

		// Force automatic excerpt (bypass manual excerpt). Based on wp_trim_excerpt.

		$content = get_the_content();

		$content = strip_shortcodes( $content );

		$content = $GLOBALS['wp_embed']->autoembed( $content );
		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );

		//wpmtst_add_content_filters();

		$excerpt_more   = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
		$excerpt_length = WPMST()->atts( 'use_default_length' ) ? 55 : WPMST()->atts( 'excerpt_length' );
		$content        = wp_trim_words( $content, $excerpt_length, $excerpt_more );

	}
	elseif ( WPMST()->atts( 'excerpt' ) ) {

		// Based on the_excerpt.

		$use_default_length = WPMST()->atts( 'use_default_length' );

		if ( ! $use_default_length ) {
			add_filter( 'excerpt_length', 'wpmtst_excerpt_length', 20 );
		}

		$content = get_the_excerpt();

		if ( ! $use_default_length ) {
			remove_filter( 'excerpt_length', 'wpmtst_excerpt_length', 20 );
		}

		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );

		 //TODO add content filters?
	}
	else {

		// Based on the_content.

		$content = get_the_content( apply_filters( 'wpmtst_more_link_text', null ) );

		$content = $GLOBALS['wp_embed']->autoembed( $content );
		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );

		//wpmtst_add_content_filters();

	}

	echo apply_filters( 'wpmtst_the_content', $content );
}


/**
 * Add compatible filters from other plugins.
 *
 * @since 2.11.5
 * @since 2.11.9 disabled
 */
function wpmtst_add_content_filters() {
	/**
	 * CM Tooltip Glossary
	 * This would only be necessary in widget text because CMTG only filters page/post content.
	 * @link https://wordpress.org/support/topic/exclude-shortcode-not-working
	 * @todo Add an option for this.
	 */
	//if ( class_exists( 'CMTooltipGlossaryFrontend' ) ) {
	//	if ( $p = has_filter( 'the_content', array( 'CMTooltipGlossaryFrontend', 'cmtt_glossary_parse' ) ) ) {
	//		add_filter( 'wpmtst_the_content', array( 'CMTooltipGlossaryFrontend', 'cmtt_glossary_parse' ), $p );
	//	}
	//	if ( $p = has_filter( 'the_content', array( 'CMTooltipGlossaryFrontend', 'cmtt_glossary_addBacklink' ) ) ) {
	//		add_filter( 'wpmtst_the_content', array( 'CMTooltipGlossaryFrontend', 'cmtt_glossary_addBacklink' ), $p );
	//	}
	//}
}

/**
 * Read More link to the post or a page.
 *
 * @deprecated 2.10.0
 */
function wpmtst_read_more() {}

/**
 * Add "Read more" to *manual* excerpts after content and custom fields.
 *
 * Using the wp_trim_excerpt filter as a trigger instead of checking content/excerpt fields manually.
 *
 * @since 2.11.4
 *
 * @param $text
 * @param $raw_excerpt
 *
 * @return mixed
 */
function wpmtst_trim_excerpt( $text, $raw_excerpt ) {
	if ( 'wpm-testimonial' == get_post_type() && WPMST()->atts( 'excerpt' ) && WPMST()->atts( 'more_full_post' ) ) {
		add_action( 'wpmtst_after_testimonial', 'wpmtst_excerpt_more_full_post' );
	}

	return $text;
}
add_filter( 'wp_trim_excerpt', 'wpmtst_trim_excerpt', 10, 2 );

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
		if ( $excerpt_length = WPMST()->atts( 'excerpt_length' ) ) {
			$words = $excerpt_length;
		}
	}

	return $words;
}

/**
 * Modify the excerpt "Read more" link.
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
add_filter( 'excerpt_more', 'wpmtst_excerpt_more', 20 );


function wpmtst_excerpt_more_full_post() {
	echo apply_filters( 'wpmtst_manual_excerpt_read_more', wpmtst_get_excerpt_more_link() );
}


function wpmtst_get_excerpt_more_post() {
	if ( WPMST()->atts( 'excerpt' ) && WPMST()->atts( 'more_full_post' ) )
		return ( WPMST()->atts( 'more_post_ellipsis' ) ? ' &hellip;' : '' );
	else
		return ( WPMST()->atts( 'more_post_ellipsis' ) ? ' &hellip; ' : ' ' ) . wpmtst_get_excerpt_more_link();
}


function wpmtst_get_excerpt_more_link() {
	$link = sprintf( '<a href="%1$s" class="readmore">%2$s</a>',
		esc_url( get_permalink() ),
		sprintf( '%s<span class="screen-reader-text"> "%s"</span>',
			apply_filters( 'wpmtst_l10n',
				WPMST()->atts( 'more_post_text' ),
				'strong-testimonials-read-more',
				sprintf( __( 'View %s : Read more (testimonial)', 'strong-testimonials' ), WPMST()->atts( 'view' ) )
			),
			get_the_title() )
	);

	return $link;
}

/**
 * Assemble link to designated "Read more" page.

 * @since 2.10.0
 *
 * @return string
 */
function wpmtst_read_more_page() {
	$atts = WPMST()->atts( array( 'view', 'more_page', 'more_page_id', 'more_page_text', 'more_page_hook' ) );

	if ( $atts['more_page'] && $atts['more_page_id'] ) {
		if ( $permalink = wpmtst_get_permalink( $atts['more_page_id'] ) ) {
			$view_options = get_option( 'wpmtst_view_default' );
			if ( isset( $atts['more_page_text'] ) && $atts['more_page_text'] ) {
				$link_text = $atts['more_page_text'];
			}
			else {
				$link_text = $view_options['more_page_text'];
			}
			$link_text = apply_filters( 'wpmtst_l10n',
				$atts['more_page_text'],
				'strong-testimonials-read-more',
				sprintf( __( 'View %s : Read more (page or post)', 'strong-testimonials' ), $atts['view'] )
			);

			$classname = ( 'wpmtst_after_testimonial' == $atts['more_page_hook'] ? 'readmore' : 'readmore-page' );
			$classname = apply_filters( 'wpmtst_read_more_page_class', $classname );
			echo sprintf( '<div class="%s"><a href="%s">%s</a></div>', $classname, esc_url( $permalink ), $link_text );
		}
	}
}

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
 */
function wpmtst_the_thumbnail( $size = null ) {
	if ( ! WPMST()->atts( 'thumbnail' ) )
		return;

	// let arg override view setting
	$size = ( null === $size ) ? WPMST()->atts( 'thumbnail_size' ) : $size ;
	if ( 'custom' == $size ) {
		$size = array( WPMST()->atts( 'thumbnail_width' ), WPMST()->atts( 'thumbnail_height' ) );
	}

	$id   = get_the_ID();
	$img  = false;

	// check for a featured image
	if ( has_post_thumbnail( $id ) ) {

		// show featured image
		$img = get_the_post_thumbnail( $id, $size );

	} else {

		// no featured image, now what?

		if ( 'yes' == WPMST()->atts( 'gravatar' ) ) {
			// view > gravatar > show gravatar (use default, if not found)

			$img = get_avatar( wpmtst_get_field( 'email' ), apply_filters( 'wpmtst_gravatar_size', $size ) );

		} elseif ( 'if' == WPMST()->atts( 'gravatar' ) ) {
			// view > gravatar > show gravatar only if found (and has email)

			if ( wpmtst_get_field( 'email' ) ) {
				// get_avatar will return false if not found (via filter)
				$img = get_avatar( wpmtst_get_field( 'email' ), apply_filters( 'wpmtst_gravatar_size', $size ) );
			}
		}

	}

	if ( $img ) {
		// TODO Move class to arg and filter.
		echo '<div class="testimonial-image">' . apply_filters( 'wpmtst_thumbnail_img', $img, $id ) . '</div>';
	}
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
				add_action( 'wp_footer', 'wpmtst_colorbox_manual_settings', 100 );
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
 * Colorbox settings for testimonials only.
 */
function wpmtst_colorbox_manual_settings() {
	?>
	<script>
	// de-focus and disable grouping
	jQuery(function($){
		$(".testimonial-image a").colorbox({rel:"nofollow",returnFocus:false});
	});
	</script>
	<?php
}

/**
 * @param string $format
 * @param string $class
 *
 * @return bool
 */
function wpmtst_the_date( $format = '', $class = '' ) {
	global $post;
	if ( ! $post )
		return false;

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
	$html = '';

	foreach ( $client_section as $field ) {

		// Get label.
		$field['label'] = wpmtst_get_field_label( $field );

		switch ( $field['type'] ) {

			case 'link':
			case 'link2':
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
						$text = $field['label'];
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
						$new_tab = isset( $field['new_tab'] ) ? $field['new_tab'] : false;

						// TODO Make this a global plugin option.
						$nofollow = get_post_meta( $post->ID, 'nofollow', true );

						// if field empty, use domain instead
						if ( ! $text || is_array( $text ) )
							$text = preg_replace( '(^https?://)', '', $url );

						$output = sprintf( '<a href="%s"%s%s>%s</a>', $url, link_new_tab( $new_tab, false ), link_nofollow( $nofollow, false ), $text );
					}

				}
				break;

			case 'date':
				$format   = isset( $field['format'] ) && $field['format'] ? $field['format'] : get_option( 'date_format' );

				if ( 'post_date' == $field['field'] ) {
					$the_date = mysql2date( $format, $post->post_date );
				}
				else {
					$the_date = get_post_meta( $post->ID, $field['field'], true );
					if ( get_option( 'date_format' ) != $format ) {
						// Requires PHP 5.3+
						if ( version_compare( PHP_VERSION, '5.3' ) >= 0 ) {
							$new_date = DateTime::createFromFormat( get_option( 'date_format' ), $the_date );
							if ( $new_date )
								$the_date = $new_date->format( $format );
						}
					}
				}

				$output = apply_filters( 'wpmtst_the_date', $the_date, $format, $post );
				break;

			default:
				// text field
				$output = get_post_meta( $post->ID, $field['field'], true );

		}

		if ( $output )
			$html .= '<div class="' . $field['class'] . '">' . $output . '</div>';
	}

	return $html;
}

function wpmtst_container_class() {
	echo apply_filters( 'wpmtst_container_class', WPMST()->atts( 'container_class' ) );
}

function wpmtst_content_class() {
	echo apply_filters( 'wpmtst_content_class', WPMST()->atts( 'content_class' ) );
}

function wpmtst_post_class() {
	echo apply_filters( 'wpmtst_post_class', WPMST()->atts( 'post_class' ) . ' post-' . get_the_ID() );
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
