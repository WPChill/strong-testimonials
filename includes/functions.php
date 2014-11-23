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
	if ( strlen( $content ) > $limit ) {
		$space_pos = strpos( $content, ' ', $limit );
		if ( $space_pos )
			$content = substr( $content, 0, $space_pos ) . ' . . . ';
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
	if ( ! preg_match( "~^(?:f|ht)tps?://~i", $url ) )
		$url = 'http://' . $url;

	return $url;
}


/*
 * Check whether a script is registered by file name instead of handle.
 *
 * @param array $filenames possible versions of one script, e.g. plugin.js, plugin-min.js, plugin-1.2.js
 * @return bool
 */
function wpmtst_is_queued( $filenames ) {
	global $wp_scripts;
	// Bail if called too early.
	if ( ! $wp_scripts ) return false;
		
	$registered = false;
	foreach ( $wp_scripts->registered as $handle => $script ) {
		if ( in_array( basename( $script->src ), $filenames ) ) {
			$registered = true;
			break;
		}
	}
	if ( $registered ) {
		if ( in_array( $handle, $wp_scripts->queue ) ) {
			return true;
		}
	}
	return false;
}


/*
 * Custom hook action to conditionally load Cycle script
 */
function wpmtst_cycle_check( $effect, $speed, $timeout, $pause, $var ) {
	/*
	 * Load jQuery Cycle2 plugin (http://jquery.malsup.com/cycle2/) only if
	 * either Cycle or Cycle 2 is not already enqueued by a theme or another
	 * plugin. Both versions use same function name so we can't load both
	 * but either version will work for our purposes.
	 * http://jquery.malsup.com/cycle2/faq/
	 *
	 * ------------------------------------------------------------------------
	 * This WordPress function checks by *handle* but handles can be different
	 * so `wp_script_is` misses it. (Seems to be for use only within a plugin.)
	 * http://codex.wordpress.org/Function_Reference/wp_script_is
	 * ------------------------------------------------------------------------
	 */
	// $list = 'enqueued';
	// if ( ! wp_script_is( 'jquery.cycle2.min.js', $list ) ) { }
	
	/*
	 * ---------------------------------------------------
	 * This custom function checks by *file name* instead:
	 * ---------------------------------------------------
	 */
	if ( ! wpmtst_is_queued( array( 'jquery.cycle.all.min.js', 'jquery.cycle.all.js' ) )
			&& ! wpmtst_is_queued( array( 'jquery.cycle2.min.js', 'jquery.cycle2.js' ) ) ) {
			
		/*
		 * ----------------------------------------------------------------------
		 * Conflict with Page Builder plugin (and maybe others)
		 * ----------------------------------------------------------------------
		 * Page Builder preloads widgets before `wp_enqueue_scripts` hook.
		 * With a widget in a panel, this custom hook is getting called before
		 * `wp_enqueue_scripts` so this plugin's styles and scripts have not
		 * been registered yet, and therefore cannot be enqueued or localized.
		 *
		 * Solution: Enqueue completely here, not registered first.
		 * @since 1.9.0
		 */
		wp_enqueue_script( 'wpmtst-cycle-plugin', WPMTST_DIR . 'js/jquery.cycle2.min.js', array( 'jquery' ) );
	}
	
	// Load Cycle script and populate its variable.
	$args = array (
			'fx'      => $effect,
			'speed'   => $speed * 1000, 
			'timeout' => $timeout * 1000, 
			'pause'   => $pause,
	);
	// Load in footer:
	wp_enqueue_script( 'wpmtst-cycle-script', WPMTST_DIR . 'js/wpmtst-cycle.js', array ( 'jquery' ), false, true );
	// Load in header:
	//wp_enqueue_script( 'wpmtst-cycle-script', WPMTST_DIR . 'js/wpmtst-cycle.js', array ( 'jquery' ), false );
	wp_localize_script( 'wpmtst-cycle-script', $var, $args );
	
}
// custom hook
add_action( 'wpmtst_cycle_hook', 'wpmtst_cycle_check', 10, 5 );


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
 * @since 1.12.1
 */
function wpmtst_uasort( $a, $b ) {
	if ( $a['order'] == $b['order'] ) {
		return 0;
	}
	return ( $a['order'] < $b['order'] ) ? -1 : 1;
}