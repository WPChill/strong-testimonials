<?php
/**
 * Featured image functions.
 *
 * @package Strong_Testimonials
 */

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
