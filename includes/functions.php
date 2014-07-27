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
function wpmtst_cycle_check( $effect, $speed, $timeout, $pause, $div, $var ) {
	// Load jQuery Cycle2 plugin (http://jquery.malsup.com/cycle2/) only if
	// either Cycle or Cycle 2 is not already enqueued by a theme or another
	// plugin. Both versions use same function name
	// (see http://jquery.malsup.com/cycle2/faq/) so we can't load both
	// but either version will work for our purposes.
	
	// ----------------------------------------------------------
	// This WordPress function checks by *handle* but handles can
	// be different so `wp_script_is` misses it.
	// (Seems to be intended for use within the plugin itself.)
	// http://codex.wordpress.org/Function_Reference/wp_script_is
	// ----------------------------------------------------------
	// $list = 'enqueued';
	// if ( ! wp_script_is( 'jquery.cycle2.min.js', $list ) ) {
	
	// ---------------------------------------------------
	// This custom function checks by *file name* instead:
	// ---------------------------------------------------
	if ( ! wpmtst_is_queued( array( 'jquery.cycle.all.min.js', 'jquery.cycle.all.js' ) )
			&& ! wpmtst_is_queued( array( 'jquery.cycle2.min.js', 'jquery.cycle2.js' ) ) ) {
		wp_enqueue_script( 'wpmtst-cycle-plugin' ); // Cycle2
	}
	
	// Load Cycle script and populate its variable.
	$args = array (
			'fx'      => $effect,
			'speed'   => $speed * 1000, 
			'timeout' => $timeout * 1000, 
			'pause'   => $pause,
			'div'     => $div,
	);
	wp_enqueue_script( 'wpmtst-cycle-script' );
	wp_localize_script( 'wpmtst-cycle-script', $var, $args );
}
// custom hook
add_action( 'wpmtst_cycle_hook', 'wpmtst_cycle_check', 10, 6 );
