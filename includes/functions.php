<?php
/**
 * Strong Testimonials - Functions
 */

 
/*
 * Truncate post content
 */
function wpmtst_truncate( $content, $limit ) {
	if ( strlen( $content ) > $limit ) {
		// Find first space after char_limit (e.g. 200).
		// If not found then char_limit is in the middle of the
		// last word (e.g. string length = 203) so no need to truncate.
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
 * Get category
 */
function wpmtst_get_terms( $category ) {
	if ( $category && 'all' != $category ) {
		$term = get_term_by( 'id', $category, 'wpm-testimonial-category' );
		$term_taxonomy = $term->taxonomy;
		$term_slug     = $term->slug;
	}
	else {
		$term_taxonomy = '';
		$term_slug     = '';
	}
	return array( 'taxo' => $term_taxonomy, 'term' => $term_slug );
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

	// Load jQuery Cycle2 plugin (http://jquery.malsup.com/cycle2/) only if
	// either Cycle or Cycle 2 is not already enqueued by a theme or another
	// plugin. Both versions use same function name so we can't load both
	// but either version will work for our purposes.
	// http://jquery.malsup.com/cycle2/faq/
	
	// ------------------------------------------------------------------------
	// This WordPress function checks by *handle* but handles can be different
	// so `wp_script_is` misses it. (Seems to be for use only within a plugin.)
	// http://codex.wordpress.org/Function_Reference/wp_script_is
	// ------------------------------------------------------------------------
	// $list = 'enqueued';
	// if ( ! wp_script_is( 'jquery.cycle2.min.js', $list ) ) { }
	
	// ---------------------------------------------------
	// This custom function checks by *file name* instead:
	// ---------------------------------------------------
	if ( ! wpmtst_is_queued( array( 'jquery.cycle.all.min.js', 'jquery.cycle.all.js' ) )
			&& ! wpmtst_is_queued( array( 'jquery.cycle2.min.js', 'jquery.cycle2.js' ) ) ) {
			
		// ----------------------------------------------------------------------
		// Conflict with Page Builder plugin (and maybe others)
		// ----------------------------------------------------------------------
		// Page Builder preloads widgets before `wp_enqueue_scripts` hook.
		// With a widget in a panel, this custom hook is getting called before
		// `wp_enqueue_scripts` so this plugin's styles and scripts have not
		// been registered yet, and therefore cannot be enqueued or localized.
		
		// Solution 1:
		// Enqueue completely here, not registered first. <-- DOING THIS (since 1.9)
		// Solution 2:
		// Move these conditional scripts to separate actions with later priority. <-- too many functions

		wp_enqueue_script( 'wpmtst-cycle-plugin', WPMTST_DIR . 'js/jquery.cycle2.min.js', array( 'jquery' ) );
		
	}
	
	// Load Cycle script and populate its variable.
	$args = array (
			'fx'      => $effect,
			'speed'   => $speed * 1000, 
			'timeout' => $timeout * 1000, 
			'pause'   => $pause,
	);
	wp_enqueue_script( 'wpmtst-cycle-script', WPMTST_DIR . 'js/wpmtst-cycle.js', array ( 'jquery' ), false, true );
	wp_localize_script( 'wpmtst-cycle-script', $var, $args );
	
}
// custom hook
add_action( 'wpmtst_cycle_hook', 'wpmtst_cycle_check', 10, 5 );
