<?php
/**
 * Child shortcodes. A fork of WordPress shortcodes API.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */

/**
 * Container for storing shortcode tags and their hook to call for the shortcode
 *
 * @since 1.11.0
 *
 * @name $child_shortcode_tags
 * @var array
 * @global array $child_shortcode_tags
 */
$child_shortcode_tags = array();
$current_child_shortcodes = array();

/**
 * Add hook for shortcode tag.
 *
 * @since 1.11.0
 *
 * @uses $child_shortcode_tags
 *
 * @param string $tag Shortcode tag to be searched in post content.
 * @param callable $func Hook to run when shortcode is found.
 */
function add_child_shortcode($global_tag, $tag, $func) {
	global $child_shortcode_tags;

	if ( is_callable($func) )
		$child_shortcode_tags[$global_tag][$tag] = $func;
}

/**
 * Removes hook for shortcode.
 *
 * @since 1.11.0
 *
 * @uses $child_shortcode_tags
 *
 * @param string $tag shortcode tag to remove hook for.
 */
function remove_child_shortcode($global_tag, $tag) {
	global $child_shortcode_tags;

	unset($child_shortcode_tags[$global_tag][$tag]);
}

/**
 * Clear all shortcodes.
 *
 * @since 1.11.0
 *
 * @uses $child_shortcode_tags
 */
function remove_all_child_shortcodes($global_tag) {
	global $child_shortcode_tags;

	$child_shortcode_tags[$global_tag] = array();
}

/**
 * Whether a registered shortcode exists named $tag
 *
 * @since 1.11.0
 *
 * @global array $child_shortcode_tags
 * @param string $tag
 * @return boolean
 */
function child_shortcode_exists( $global_tag, $tag ) {
	global $child_shortcode_tags;
	return array_key_exists( $tag, $child_shortcode_tags[$global_tag] );
}

/**
 * Whether the passed content contains the specified shortcode
 *
 * @since 1.11.0
 *
 * @global array $child_shortcode_tags
 * @param string $tag
 * @return boolean
 */
function has_child_shortcode( $content, $tag, $global_tag ) {
	if ( false === strpos( $content, '[' ) ) {
		return false;
	}

	if ( child_shortcode_exists( $global_tag, $tag ) ) {
		$pattern = get_child_shortcode_regex( $global_tag );
		preg_match_all( "/$pattern/s", $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) )
			return false;
			
/*
$matches = array (size=2)
  0 => 
    array (size=7)
      0 => string '[client][field name="client_name" class="name"][/client]' (length=56)
      1 => string '' (length=0)
      2 => string 'client' (length=6)
      3 => string '' (length=0)
      4 => string '' (length=0)
      5 => string '[field name="client_name" class="name"]' (length=39)
      6 => string '' (length=0)
  1 => 
    array (size=7)
      0 => string '[more page="all-testimonials-default" class="readmore"]Read more testimonials[/more]' (length=84)
      1 => string '' (length=0)
      2 => string 'more' (length=4)
      3 => string ' page="all-testimonials-default" class="readmore"' (length=49)
      4 => string '' (length=0)
      5 => string 'Read more testimonials' (length=22)
      6 => string '' (length=0)
*/
		foreach ( $matches as $shortcode ) {
			if ( $tag === $shortcode[2] ) {
				return true;
			} elseif ( ! empty( $shortcode[5] ) && has_child_shortcode( $shortcode[5], $global_tag, $tag ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Search content for shortcodes and filter shortcodes through their hooks.
 *
 * If there are no shortcode tags defined, then the content will be returned
 * without any filtering. This might cause issues when plugins are disabled but
 * the shortcode will still show up in the post or content.
 *
 * @since 1.11.0
 *
 * @uses $child_shortcode_tags
 * @uses get_shortcode_regex() Gets the search pattern for searching shortcodes.
 *
 * @param string $content Content to search for shortcodes
 * @return string Content with shortcodes filtered out.
 */
function do_child_shortcode($global_tag, $content) {
	global $child_shortcode_tags;
	global $current_child_shortcodes;
	$current_child_shortcodes = $child_shortcode_tags[$global_tag];

	if ( false === strpos( $content, '[' ) )
		return $content;

	if ( empty( $current_child_shortcodes ) || ! is_array( $current_child_shortcodes ) )
		return $content;

	$pattern = get_child_shortcode_regex($global_tag);
	return preg_replace_callback( "/$pattern/s", 'do_child_shortcode_tag', $content );
}

/**
 * Regular Expression callable for do_child_shortcode() for calling shortcode hook.
 * @see get_shortcode_regex for details of the match array contents.
 *
 * @since 1.11.0
 * @access private
 * @uses $child_shortcode_tags
 *
 * @param array $m Regular expression match array
 * @return mixed False on failure.
 */
function do_child_shortcode_tag( $m ) {
	global $child_shortcode_tags;
	global $current_child_shortcodes;

	// allow [[foo]] syntax for escaping a tag
	if ( $m[1] == '[' && $m[6] == ']' ) {
		return substr($m[0], 1, -1);
	}

	$tag = $m[2];
	$attr = shortcode_parse_atts( $m[3] );

	if ( isset( $m[5] ) ) {
		// enclosing tag - extra parameter
		return $m[1] . call_user_func( $current_child_shortcodes[$tag], $attr, $m[5], $tag ) . $m[6];
	} else {
		// self-closing tag
		return $m[1] . call_user_func( $current_child_shortcodes[$tag], $attr, null,  $tag ) . $m[6];
	}
}

/**
 * Retrieve the shortcode regular expression for searching.
 *
 * The regular expression combines the shortcode tags in the regular expression
 * in a regex class.
 *
 * The regular expression contains 6 different sub matches to help with parsing.
 *
 * 1 - An extra [ to allow for escaping shortcodes with double [[]]
 * 2 - The shortcode name
 * 3 - The shortcode argument list
 * 4 - The self closing /
 * 5 - The content of a shortcode when it wraps some content.
 * 6 - An extra ] to allow for escaping shortcodes with double [[]]
 *
 * @since 1.11.0
 *
 * @uses $shortcode_tags
 *
 * @return string The shortcode search regular expression
 */
function get_child_shortcode_regex($global_tag) {
	global $child_shortcode_tags;
	$tagnames = array_keys($child_shortcode_tags[$global_tag]);
	$tagregexp = join( '|', array_map('preg_quote', $tagnames) );

	// WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
	// Also, see shortcode_unautop() and shortcode.js.
	return
		  '\\['                              // Opening bracket
		. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		. "($tagregexp)"                     // 2: Shortcode name
		. '(?![\\w-])'                       // Not followed by word character or hyphen
		. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		. ')'
		. '(?:'
		.     '(\\/)'                        // 4: Self closing tag ...
		.     '\\]'                          // ... and closing bracket
		. '|'
		.     '\\]'                          // Closing bracket
		.     '(?:'
		.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.         ')'
		.         '\\[\\/\\2\\]'             // Closing shortcode tag
		.     ')?'
		. ')'
		. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
}

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 * @since 1.11.0
 *
 * @param array $pairs Entire list of supported attributes and their defaults.
 * @param array $atts User defined attributes in shortcode tag.
 * @param string $shortcode Optional. The name of the shortcode, provided for context to enable filtering
 * @return array Combined and filtered attribute list.
 */
function child_shortcode_atts( $pairs, $atts, $shortcode = '' ) {
	$atts = (array)$atts;
	$out = array();
	foreach($pairs as $name => $default) {
		if ( array_key_exists($name, $atts) )
			$out[$name] = $atts[$name];
		else
			$out[$name] = $default;
	}
	/**
	 * Filter a shortcode's default attributes.
	 *
	 * If the third parameter of the shortcode_atts() function is present then this filter is available.
	 * The third parameter, $shortcode, is the name of the shortcode.
	 *
	 * @since 1.11.0
	 *
	 * @param array $out The output array of shortcode attributes.
	 * @param array $pairs The supported attributes and their defaults.
	 * @param array $atts The user-defined shortcode attributes.
	 */
	if ( $shortcode )
		$out = apply_filters( "child_shortcode_atts_{$shortcode}", $out, $pairs, $atts );

	return $out;
}

/**
 * Remove all shortcode tags from the given content.
 *
 * @since 1.11.0
 *
 * @uses $child_shortcode_tags
 *
 * @param string $content Content to remove shortcode tags.
 * @return string Content without shortcode tags.
 */
function strip_child_shortcode( $global_tag, $content ) {
	global $child_shortcode_tags;

	if ( false === strpos( $content, '[' ) ) {
		return $content;
	}

	if (empty($child_shortcode_tags) || !is_array($child_shortcode_tags))
		return $content;

	$pattern = get_child_shortcode_regex($global_tag);

	return preg_replace_callback( "/$pattern/s", 'strip_shortcode_tag', $content );
}

// add_filter('the_content', 'do_child_shortcode', 12); // AFTER wpautop()
