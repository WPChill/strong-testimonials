<?php
/**
 * Functions
 *
 * @package Strong_Testimonials
 */


/**
 * Truncate post content
 *
 * Find first space after char_limit (e.g. 200).
 * If not found then char_limit is in the middle of the
 * last word (e.g. string length = 203) so no need to truncate.
 */
function wpmtst_truncate( $content, $limit ) {
	/**
	 * Strip tags.
	 *
	 * @since 1.15.12
	 */
	$content = strip_tags( $content );
	
	if ( strlen( $content ) > $limit ) {
		$space_pos = strpos( $content, ' ', $limit );
		if ( $space_pos )
			$content = substr( $content, 0, $space_pos ) . '&hellip;';
	}
	
	return $content;
}


/*
 * Append custom fields to post object.
 * Add thumbnail if included in field group. (v1.8)
 */
function wpmtst_get_post( $post ) {
	$custom = get_post_custom( $post->ID );
	$fields = get_option( 'wpmtst_fields' );
	$field_groups = $fields['field_groups'];

	// Only add on fields from current field group.
	foreach ( $field_groups[ $fields['current_field_group'] ]['fields'] as $key => $field ) {
		$name = $field['name'];
		
		if ( 'featured_image' == $name )
			$post->thumbnail_id = get_post_thumbnail_id( $post->ID );
			
		if ( 'custom' == $field['record_type'] ) {
			if ( isset( $custom[$name] ) )
				$post->$name = $custom[$name][0];
			else
				$post->$name = '';
		}
	}
	return $post;
}


/*
 * Helper: Format URL
 */
function wpmtst_get_website( $url ) {
	if ( !preg_match( "~^(?:f|ht)tps?://~i", $url ) )
		$url = 'http://' . $url;

	return $url;
}


/**
 * Check whether a common script is already registered by file name
 * instead of handle.
 * 
 * Why? Plugins are loaded before themes. Our plugin includes the Cycle
 * slider. Some themes include it too. We only want to load it once.
 *
 * Load jQuery Cycle plugin (http://jquery.malsup.com/cycle/) only if
 * any version of Cycle is not already registered by a theme or another
 * plugin. Both versions of Cycle use same function name so we can't load
 * both but either version will work for our purposes.
 * http://jquery.malsup.com/cycle2/faq/
 *
 * The WordPress function `wp_script_is` checks by *handle* within a plugin
 * or theme but handles can be different so it misses it.
 *   wp_script_is( 'jquery-cycle', 'registered' )
 * http://codex.wordpress.org/Function_Reference/wp_script_is
 *
 * Jetpack's slideshow shortcode simply enqueues its own version of Cycle
 * without registering first if and when the shortcode is rendered. No way
 * to check for that. It does not seem to create a conflict now. (1.16)
 *
 * @param  array  $filenames possible versions of one script, 
 *                e.g. plugin.js, plugin-min.js, plugin-1.2.js
 * @return string
 */
function wpmtst_is_registered( $filenames ) {
	global $wp_scripts;

	// Bail if called too early.
	if ( ! $wp_scripts ) return false;
	
	$script_handle = '';
	
	foreach ( $wp_scripts->registered as $handle => $script ) {
		if ( in_array( basename( $script->src ), $filenames ) ) {
			$script_handle = $handle;
			break;
		}
	}
	
	return $script_handle;
}


/**
 * Get page ID by slug.
 *
 * Thanks http://wordpress.stackexchange.com/a/102845/32076
 * Does not require parent slug.
 *
 * @since 1.11.0
 */
if ( ! function_exists( 'get_page_by_slug' ) ) {
	function get_page_by_slug( $page_slug, $output = OBJECT, $post_type = 'page' ) { 
		global $wpdb; 
		$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) ); 
		if ( $page ) 
			return get_post($page, $output);
		else
			return null; 
	}
}


/**
 * Reverse auto-p wrap shortcodes that stand alone
 *
 * @since 1.11.0
 */
if ( ! function_exists( 'reverse_wpautop' ) ) {
	function reverse_wpautop( $s ) {
		// remove any new lines already in there
		$s = str_replace("\n", "", $s);

		// remove all <p>
		$s = str_replace("<p>", "", $s);

		// replace <br /> with \n
		$s = str_replace(array("<br />", "<br>", "<br/>"), "", $s);

		// replace </p> with \n\n
		$s = str_replace("</p>", "", $s);

		return $s;      
	}
}


/**
 * Open links in new tab.
 *
 * @since 1.11.0
 */
if ( ! function_exists( 'link_new_tab' ) ) {
	function link_new_tab( $new_tab = true, $echo = true ) {
		if ( ! $new_tab ) return;
		$t = ' target="_blank"';
		if ( $echo ) echo $t;
		else return $t;
	}
}


/**
 * Add nofollow to links.
 *
 * @since 1.11.0
 */
if ( ! function_exists( 'link_nofollow' ) ) {
	function link_nofollow( $nofollow = true, $echo = true ) {
		if ( ! $nofollow ) return;
		$t = ' rel="nofollow"';
		if ( $echo ) echo $t;
		else return $t;
	}
}


/*
 * Sort array based on 'order' element.
 *
 * @since 1.13
 */
function wpmtst_uasort( $a, $b ) {
	if ( $a['order'] == $b['order'] ) {
		return 0;
	}
	return ( $a['order'] < $b['order'] ) ? -1 : 1;
}


/**
 * Return the shortcode tag.
 * 
 * @since 1.18.4
 * 
 * @return string
 */
function wpmtst_get_shortcode() {
	$options = get_option( 'wpmtst_options' );
	if ( $options && isset( $options['shortcode'] ) && $options['shortcode'] )
		return $options['shortcode'];
	else
		return 'strong';
}
