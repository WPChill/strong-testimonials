<?php
/**
 * Featured image functions.
 */

/**
 * @param null $size
 *
 * @return mixed|string
 */
function wpmtst_get_thumbnail( $size = null ) {
	if ( ! WPMST()->atts( 'thumbnail' ) && ! is_admin() ) {
		return '';
	}

	// let arg override view setting
	$size = ( null === $size ) ? WPMST()->atts( 'thumbnail_size' ) : $size ;
	if ( 'custom' == $size ) {
		$size = array( WPMST()->atts( 'thumbnail_width' ), WPMST()->atts( 'thumbnail_height' ) );
	}

	$id = get_the_ID();
	$img  = '';

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
                $img = apply_filters( 'wpmtst_thumbnail_default_img', $img, $id, $size );
	}

	return apply_filters( 'wpmtst_thumbnail_img', $img, $id, $size );
}

/**
 * Filter the thumbnail image.
 * Used to add link for a lightbox. Will not affect avatars.
 *
 * @param $img
 * @param $post_id
 *
 * @since 1.23.0
 * @since 2.9.4 classes and filter
 * @since 2.30.0  lightbox_class
 *
 * @return string
 */
function wpmtst_thumbnail_img( $img, $post_id, $size ) {
	if ( WPMST()->atts( 'lightbox' ) ) {
		$url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
		if ( $url ) {
			$class_array = array( WPMST()->atts( 'lightbox_class' ) );
			$classes     = implode( ' ', array_unique( apply_filters( 'wpmtst_thumbnail_link_class', $class_array ) ) );
			$img         = sprintf( '<a class="%s" href="%s">%s</a>', $classes, esc_url( $url ), $img );
		}
	}
	return $img;
}
add_filter( 'wpmtst_thumbnail_img', 'wpmtst_thumbnail_img', 10, 3 );


/**
 * Exclude testimonial thumbnails from Lazy Loading Responsive Images plugin.
 *
 * @param $attr
 * @param $attachment
 * @param $size
 *
 * @since 2.27.0
 *
 * @return array
 */
function wpmtst_exclude_from_lazyload( $attr, $attachment, $size ) {
        $options = get_option( 'wpmtst_options' );

        if ( isset( $options['no_lazyload_plugin'] ) && $options['no_lazyload_plugin'] ) {
                if ( 'wpm-testimonial' == get_post_type( $attachment->post_parent ) ) {
                        $attr['data-no-lazyload'] = 1;
                }
        }
    
        return $attr;
}
/**
 * Add filter if Lazy Loading Responsive Images plugin is active.
 *
 * @since 2.27.0
 */
function wpmtst_lazyload_check() {
	if ( wpmtst_is_plugin_active( 'lazy-loading-responsive-images' ) ) {
		add_filter( 'wp_get_attachment_image_attributes', 'wpmtst_exclude_from_lazyload', 10, 3 );
	}
}
add_action( 'init', 'wpmtst_lazyload_check' );

/**
 * Enable lazy load
 *
 * @param $attr
 * @param $attachment
 * @param $size
 *
 * @since 2.27.0
 *
 * @return array
 */
function wpmtst_add_lazyload( $attr, $attachment, $size ) {
	if( !function_exists( 'wp_lazy_loading_enabled' ) || !apply_filters( 'wp_lazy_loading_enabled', true, 'img', 'strong_testimonials_has_lazyload' ) ) {
		$options = get_option( 'wpmtst_options' );
			
		if ( isset( $options['lazyload'] ) && $options['lazyload']) {
			if ( 'wpm-testimonial' == get_post_type( $attachment->post_parent ) && !is_admin() ) {
				$attr['class'] .= ' lazy-load';
							$attr['data-src'] = $attr['src'];
							$attr['data-srcset'] = $attr['srcset'];
							unset($attr['src']);
							unset($attr['srcset']);
			}
		}
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'wpmtst_add_lazyload', 10, 3 );

/**
 * Filter the gravatar size.
 *
 * @param array|string $size
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


function wpmtst_thumbnail_img_platform( $img, $post_id, $size ) {
	if ( $img ) {
		return $img;
	}

	$platform = get_post_meta( $post_id, 'platform', true );
	if( ! $platform ) {
		return $img;
	}

	$img = apply_filters( 'wpmtst_thumbnail_img_platform_' . $platform, $img, $post_id, $size );

	return $img;
}
add_filter( 'wpmtst_thumbnail_img', 'wpmtst_thumbnail_img_platform', 10, 3 );


function wpmtst_thumbnail_img_platform_general( $img, $post_id, $size ) {

	$platform_user_photo = get_post_meta( $post_id, 'platform_user_photo', true );
	if ( ! $platform_user_photo ) {
		return $img;
	}

 	//calculate width & height based on size
	if ( is_array( $size ) ) {
		$width  = $size[0];
		$height = $size[1];
	} else {
		$sizes  = wpmtst_get_image_sizes( $size );
		$width  = $sizes['width'];
		$height = $sizes['height'];
	}

	return sprintf( '<img src="%s" %s %s/>', $platform_user_photo, $width ? "width='${width}'" : '', $height ? "height='${height}'" : '' );
}
add_filter( 'wpmtst_thumbnail_img_platform_facebook', 'wpmtst_thumbnail_img_platform_general', 10, 3 );
add_filter( 'wpmtst_thumbnail_img_platform_google', 'wpmtst_thumbnail_img_platform_general', 10, 3 );
add_filter( 'wpmtst_thumbnail_img_platform_yelp', 'wpmtst_thumbnail_img_platform_general', 10, 3 );
add_filter( 'wpmtst_thumbnail_img_platform_zomato', 'wpmtst_thumbnail_img_platform_general', 10, 3 );


function wpmtst_thumbnail_img_platform_woocommerce( $img, $post_id, $size ) {

	$options = get_option( 'wpmtst_importer_options' );
	if ( $options['email_field'] === '' ) {
		return $img;
	}

	$email = get_post_meta( $post_id, $options['email_field'], true );
	if ( ! $email ) {
		return $img;
	}

	//calculate width & height based on size
	if ( is_array( $size ) ) {
		$width  = $size[0];
		$height = $size[1];
	} else {
		$sizes  = wpmtst_get_image_sizes( $size );
		$width  = $sizes['width'];
		$height = $sizes['height'];
	}

	return sprintf( '<img src="%s" %s %s/>', get_avatar_url( $email ), $width ? "width='${width}'" : '', $height ? "height='${height}'" : '' );
}
add_filter( 'wpmtst_thumbnail_img_platform_woocommerce', 'wpmtst_thumbnail_img_platform_woocommerce', 10, 3 );