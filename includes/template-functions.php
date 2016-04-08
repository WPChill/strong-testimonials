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
 *
 * @param null $length
 *
 * @since 1.24.0
 * @since 2.4.0 Run content through selected filters only, instead
 *              of all filters added to the_excerpt() or the_content().
 *
 * @todo Use native auto-excerpt and trim_words instead.
 */
function wpmtst_the_content( $length = null ) {
	if ( $length ) {
		$excerpt = false;
	}
	else {
		$excerpt = WPMST()->atts( 'excerpt' );
		$length  = WPMST()->atts( 'length' );
	}

	// In View settings, {excerpt} overrides {length} overrides {full content}.

	if ( $excerpt ) {

		$content = get_the_excerpt();
		$content = apply_filters( 'the_excerpt', $content );

	}
	else {

		if ( $length )
			$content = wpmtst_get_field( 'truncated', array ( 'char_limit' => $length ) );
		else
			$content = get_the_content();

		// Applying all content filters breaks POS NextGEN Gallery.
		// So need to find a way to select which additional filters, if any, to apply.
		// For instance, All In One Rich Snippets.

		//$content = apply_filters( 'the_content', $content );

		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );

	}

	echo $content;
}

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
 * @return string
 */
function wpmtst_thumbnail_img( $img, $post_id ) {
	if ( WPMST()->atts( 'lightbox' ) ) {
		$url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
		if ( $url ) {
			$img = '<a href="' . $url . '">' . $img . '</a>';
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
 * Global Colorbox settings.
 *
 * @param $settings
 *
 * @return mixed
 */
function wpmtst_colorbox_settings( $settings ) {
	$settings['returnFocus'] = false;
	$settings['rel'] = 'nofollow';
	return $settings;
}
//add_filter( 'simple_colorbox_settings', 'wpmtst_colorbox_settings' );

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

function wpmtst_the_client() {
	$atts = WPMST()->atts();
	if ( isset( $atts['client_section'] ) ) {
		echo wpmtst_client_section( $atts['client_section'] );
	}
}

/**
 * Client section
 *
 * @since 1.21.0
 * @param array $client_section An array of client fields.
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
				// TODO is this necessary? check after testing upgrade process
				if ( ! isset( $field['link_text'] ) ) {
					$field['link_text'] = 'field';
				}

				/**
				 * Get link text and an alternate in case the URL is empty;
				 * e.g. display the domain if no company name given
				 * but don't display 'LinkedIn' if no URL given.
				 */
				$text_if_no_url = '';
				switch ( $field['link_text'] ) {
					case 'custom' :
						$text = $field['link_text_custom'];
						break;
					case 'label' :
						$text = $field['label'];
						break;
					default :
						$text = get_post_meta( $post->ID, $field['field'], true );
						$text_if_no_url = $text;
				}

				$url = get_post_meta( $post->ID, $field['url'], true );
				if ( $url ) {

					$new_tab = isset( $field['new_tab'] ) ? $field['new_tab'] : false;

					// TODO Make this a global plugin option.
					$nofollow = get_post_meta( $post->ID, 'nofollow', true );

					// if field empty, use domain instead
					if ( '' == $text ) {
						$text = preg_replace( '(^https?://)', '', $url );
					}

					$output = sprintf( '<a href="%s"%s%s>%s</a>', $url, link_new_tab( $new_tab, false ), link_nofollow( $nofollow, false ), $text );

				} else {

					// if no URL, show the alternate (usually the field)
					$output = $text_if_no_url;

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

		if ( $output ) {
			$html .= '<div class="' . $field['class'] . '">' . $output . '</div>';
		}
	}
	return $html;
}

/**
 * Read More link to the post or a page.
 */
function wpmtst_read_more() {
	$atts = WPMST()->atts( array( 'more_post', 'more_page', 'more_text' ) );

	if ( $atts['more_post'] ) {
		$permalink = get_permalink();
	}
	elseif ( $atts['more_page'] ) {
		$permalink = wpmtst_get_permalink( $atts['more_page'] );
	}
	else {
		$permalink = false;
	}

	if ( $permalink ) {
		echo '<div class="readmore"><a href="' . $permalink . '">' . $atts['more_text'] . '</a></div>';
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
