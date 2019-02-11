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
	add_filter( 'wpmtst_the_content', 'wp_make_content_images_responsive' );
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
		'src'             => array(),
		'height'          => array(),
		'width'           => array(),
		'frameborder'     => array(),
		'allowfullscreen' => array(),
	);
	// form fields - input
	$tags['input'] = array(
		'class'       => array(),
		'id'          => array(),
		'name'        => array(),
		'value'       => array(),
		'type'        => array(),
		'placeholder' => array(),
		'required'    => array(),
		'checked'     => array(),
	);
	// textarea
	$tags['textarea'] = array(
		'class'       => array(),
		'id'          => array(),
		'name'        => array(),
		'value'       => array(),
		'type'        => array(),
		'placeholder' => array(),
		'required'    => array(),
	);
	// select
	$tags['select'] = array(
		'class' => array(),
		'id'    => array(),
		'name'  => array(),
		'value' => array(),
		'type'  => array(),
	);
	// select options
	$tags['option']   = array(
		'selected' => array(),
		'class'    => array(),
		'id'       => array(),
		'name'     => array(),
		'value'    => array(),
	);
	$tags['optgroup'] = array(
		'class' => array(),
		'id'    => array(),
		'name'  => array(),
		'value' => array(),
		'label' => array(),
	);
	$tags['noscript'] = array();
	// style
	$tags['style'] = array(
		'types' => array(),
	);

	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'wpmtst_add_allowed_tags' );
