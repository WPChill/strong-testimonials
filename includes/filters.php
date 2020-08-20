<?php
/**
 * Filters
 */

/**
 * Remove whitespace between tags. Helps prevent double wpautop in plugins
 * like Posts For Pages and Custom Content Shortcode.
 *
 * @param $html
 *
 * @since 2.3
 *
 * @return mixed
 */
function wpmtst_remove_whitespace( $html ) {
	$options = get_option( 'wpmtst_options' );
	if ( $options['remove_whitespace'] ) {
		$html = preg_replace( '~>\s+<~', '><', $html );
	}

	return $html;
}
add_filter( 'strong_view_html', 'wpmtst_remove_whitespace' );
add_filter( 'strong_view_form_html', 'wpmtst_remove_whitespace' );


/**
 * Content filters.
 *
 * @since 2.33.0 Moved to `init` action.
 */
function wpmtst_content_filters() {
        
	add_filter( 'wpmtst_the_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
	add_filter( 'wpmtst_the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
	add_filter( 'wpmtst_the_content', 'wptexturize' );
	add_filter( 'wpmtst_the_content', 'wpautop' );
	add_filter( 'wpmtst_the_content', 'shortcode_unautop' );
	add_filter( 'wpmtst_the_content', 'prepend_attachment' );
        
        if (version_compare(get_bloginfo('version'),'5.5', '>=')) {
            add_filter( 'wpmtst_the_content', 'wp_filter_content_tags' );
        } else {
            add_filter( 'wpmtst_the_content', 'wp_make_content_images_responsive' );
        }
        
	add_filter( 'wpmtst_the_content', 'do_shortcode', 11 );
	add_filter( 'wpmtst_the_content', 'convert_smilies', 20 );

	add_filter( 'wpmtst_the_excerpt', 'wptexturize' );
	add_filter( 'wpmtst_the_excerpt', 'convert_smilies' );
	add_filter( 'wpmtst_the_excerpt', 'convert_chars' );
	add_filter( 'wpmtst_the_excerpt', 'wpautop' );
	add_filter( 'wpmtst_the_excerpt', 'shortcode_unautop' );
	add_filter( 'wpmtst_the_excerpt', 'do_shortcode', 11 );
	add_filter( 'wpmtst_the_excerpt', 'convert_smilies', 20 );
}
add_action( 'init', 'wpmtst_content_filters' );

function wpmtst_add_allowed_tags( $tags ) {

	// iframe
	$tags['iframe'] = array(
		'src'             => true,
		'height'          => true,
		'width'           => true,
		'frameborder'     => true,
		'allowfullscreen' => true,
		'style'           => true,
		'data-*'          => true,
		'hidden'          => true,
	);
	// form fields - input
	$tags['input'] = array(
		'class'       => true,
		'id'          => true,
		'name'        => true,
		'value'       => true,
		'type'        => true,
		'placeholder' => true,
		'required'    => true,
		'checked'     => true,
		'title'       => true,
		'style'       => true,
		'data-*'      => true,
		'hidden'      => true,
	);
	// textarea
	$tags['textarea'] = array(
		'class'       => true,
		'id'          => true,
		'name'        => true,
		'value'       => true,
		'type'        => true,
		'placeholder' => true,
		'required'    => true,
		'style'       => true,
		'data-*'      => true,
		'hidden'      => true,
	);
	// select
	$tags['select'] = array(
		'class'  => true,
		'id'     => true,
		'name'   => true,
		'value'  => true,
		'type'   => true,
		'style'  => true,
		'data-*' => true,
		'hidden' => true,
	);
	// select options
	$tags['option']   = array(
		'selected' => true,
		'class'    => true,
		'id'       => true,
		'name'     => true,
		'value'    => true,
		'style'    => true,
		'data-*'   => true,
		'hidden'   => true,
	);
	$tags['optgroup'] = array(
		'class'  => true,
		'id'     => true,
		'name'   => true,
		'value'  => true,
		'label'  => true,
		'style'  => true,
		'data-*' => true,
		'hidden' => true,
	);
	$tags['source']   = array(
		'type' => true,
		'src'  => true,
	);

	$tags['span']['hidden'] = true;

	$tags['img']['srcset'] = true;
	$tags['img']['sizes']  = true;

	$tags['div']['data-*'] = true;

	$tags['noscript'] = array();

	$tags['style'] = array(
		'types' => true,
	);

	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'wpmtst_add_allowed_tags' );


function wpmtst_safe_style_css( $styles ) {
	$styles[] = 'display';
	return $styles;
}
add_filter( 'safe_style_css', 'wpmtst_safe_style_css' );


/**
 * Change single testimonial slug.
 */
add_filter( 'wpmtst_post_type', 'wpmtst_change_testimonial_slug' );
function wpmtst_change_testimonial_slug( $args ) {

	$options = get_option( 'wpmtst_options' );

	if ( isset( $options['single_testimonial_slug'] ) && $options['single_testimonial_slug'] != '' ) {
		$args['rewrite']['slug'] = $options['single_testimonial_slug'];
	}

	return $args;
}


/**
 * Disable custom post url
 */
add_filter( 'wpmtst_post_type', 'wpmtst_disable_permalink',999 );
function wpmtst_disable_permalink( $args ) {

    $options = get_option( 'wpmtst_options' );

    if ( isset( $options['disable_rewrite'] ) && '1' == $options['disable_rewrite'] ) {
        $args['rewrite'] = false;
        $args['public'] = false;
    }

    return $args;
}