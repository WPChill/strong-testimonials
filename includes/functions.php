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
		if ( $space_pos ) {
			$content = substr( $content, 0, $space_pos ) . '&hellip;';
		}
	}

	return $content;
}

/**
 * Append custom fields to post object.
 * Add thumbnail if included in field group. (v1.8)
 */
function wpmtst_get_post( $post ) {
	//$custom = get_post_custom( $post->ID );
	//$fields = get_option( 'wpmtst_fields' );
	//$field_groups = $fields['field_groups'];
	$fields = wpmtst_get_form_fields();

	// Only add on fields from current field group.
	//foreach ( $field_groups[ $fields['current_field_group'] ]['fields'] as $key => $field ) {
	foreach ( $fields as $key => $field ) {
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

/**
 * Helper: Format URL
 *
 * @param $url
 * @return string
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

		// remove <br>
		$s = str_replace(array("<br />", "<br/>", "<br>"), "", $s);

		// remove </p>
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

/**
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

function wpmtst_get_form_fields( $form_name = 'custom' ) {
	$forms = get_option( 'wpmtst_forms' );
	$form = $forms[ $form_name ];
	$fields = $form['fields'];
	return $fields;
}

/**
 * Get custom fields.
 *
 * @since 1.21.0
 * @return array
 */
//function wpmtst_get_custom_fields() {
//	$field_options       = get_option( 'wpmtst_fields' );
//	$field_groups        = $field_options['field_groups'];
//	$current_field_group = $field_options['current_field_group'];
//	$field_group         = $field_groups[$current_field_group];
//	$custom_fields       = $field_group['fields'];
//	return $custom_fields;
//}

/**
 * @return array
 */
//function wpmtst_get_custom_field_list() {
//	$field_options = get_option( 'wpmtst_fields' );
//	$field_groups = $field_options['field_groups'];
//	$current_field_group = $field_options['current_field_group'];
//	$fields = $field_groups[$current_field_group]['fields'];
//	$fields_array = array();
//	foreach ( $fields as $field ) {
//		if ( ! in_array( $field['name'], array( 'post_title', 'post_content', 'featured_image' ) ) ) {
//			$fields_array[] = $field['name'];
//		}
//	}
//	return $fields_array;
//}

/**
 * Strip close comment and close php tags from file headers used by WP.
 *
 * @since 1.21.0
 * @param string $str Header comment to clean up.
 * @return string
 */
function wpmtst_cleanup_header_comment( $str ) {
	return trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $str));
}

/**
 * Get defined images sizes.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
 * @since 1.21.0
 */
/*
	wpmtst_get_image_sizes = Array
	(
			[widget-thumbnail] => Array
					(
							[width] => 75
							[height] => 75
							[crop] =>
							[label] => widget-thumbnail - 75 x 75
					)
			[thumbnail] => Array
					(
							[width] => 150
							[height] => 150
							[crop] => 1
							[label] => thumbnail - 150 x 150
					)
			[medium] => Array
					(
							[width] => 300
							[height] => 300
							[crop] =>
							[label] => medium - 300 x 300
					)
			[post-thumbnail] => Array
					(
							[width] => 825
							[height] => 510
							[crop] => 1
							[label] => post-thumbnail - 825 x 510
					)
			[large] => Array
					(
							[width] => 1024
							[height] => 1024
							[crop] =>
							[label] => large - 1024 x 1024
					)
			[full] => Array
					(
							[label] => original size uploaded
							[width] => 0
							[height] => 0
					)
			[custom] => Array
					(
							[label] => enter dimensions:
							[width] => 0
							[height] => 0
					)
	)
*/
function wpmtst_get_image_sizes( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach( $get_intermediate_image_sizes as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

			$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
		}
		elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[ $_size ] = array(
					'width' => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
			);

		}

	}

	// Sort by width
	uasort( $sizes, 'wpmtst_compare_width' );

	// Add option labels
	foreach ( $sizes as $key => $dimensions ) {
		$sizes[ $key ]['label'] = sprintf( '%s - %d x %d', $key, $dimensions['width'], $dimensions['height'] );
	}

	// Add extra options
	$sizes['full']   = array( 'label' => 'original size uploaded', 'width' => 0, 'height' => 0 );
	$sizes['custom'] = array( 'label' => 'custom size', 'width' => 0, 'height' => 0 );

	// Get only one size if found
	if ( $size ) {
		if( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}

	return $sizes;
}

/**
 * @param $a
 * @param $b
 *
 * @return int
 */
function wpmtst_compare_width( $a, $b ) {
	if ( $a['width'] == $b['width'] ) {
		return 0;
	}
	return ($a['width'] < $b['width']) ? -1 : 1;
}

/**
 * @return array|int|WP_Error
 */
function wpmtst_get_category_list() {

	// Disable Taxonomy Terms Order plugin by NSP-Code
	if ( is_plugin_active( 'taxonomy-terms-order/taxonomy-terms-order.php' ) ) {
		$NSP_TTO_f1 = function_exists( 'TO_get_terms_orderby' );
		$NSP_TTO_f2 = function_exists( 'TO_applyorderfilter' );
	}
	else {
		$NSP_TTO_f1 = false;
		$NSP_TTO_f2 = false;
	}

	if ( $NSP_TTO_f1 ) {
		remove_filter( 'get_terms_orderby', 'TO_get_terms_orderby', 1 );
	}
	if ( $NSP_TTO_f2 ) {
		remove_filter( 'get_terms_orderby', 'TO_applyorderfilter', 10 );
	}

	$category_list = get_terms( 'wpm-testimonial-category', array(
		'hide_empty' => false,
		'order_by'   => 'name',
		'pad_counts' => true,
	) );

	// Re-enable Taxonomy Terms Order plugin by NSP-Code
	if ( $NSP_TTO_f1 ) {
		add_filter( 'get_terms_orderby', 'TO_get_terms_orderby', 1, 2 );
	}
	if ( $NSP_TTO_f2 ) {
		add_filter( 'get_terms_orderby', 'TO_applyorderfilter', 10, 2 );
	}

	return $category_list;
}

/**
 * @return array
 */
function wpmtst_get_category_ids() {
	$category_ids = array();
	$category_list = wpmtst_get_category_list();
	foreach ( $category_list as $cat ) {
		$category_ids[] = $cat->term_id;
	}

	return $category_ids;
}

/**
 * @return array|mixed|null|object
 */
function wpmtst_get_views() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'strong_views';
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $table_name . ' WHERE name != %s ORDER BY 1', '_default' ), ARRAY_A );

	return $results;
}

/**
 * @param $views
 *
 * @return mixed
 */
function wpmtst_unserialize_views( $views ) {
	foreach( $views as $key => $view ) {
		$views[$key]['data'] = unserialize( $view['value'] );
	}

	return $views;
}

/**
 * @param $id
 *
 * @return array|mixed|null|object|void
 */
function wpmtst_get_view( $id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'strong_views';

	if ( is_numeric( $id ) )
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ), ARRAY_A );
	else
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE name = %s", $id ), ARRAY_A );

	return $row;
}

/**
 * @param        $view
 * @param string $action
 *
 * @return bool|false|int
 */
function wpmtst_save_view( $view, $action = 'edit' ) {
	$view = (array) $view;
	if ( ! $view )
		return false;

	global $wpdb;
	$table_name = $wpdb->prefix . 'strong_views';
	$serialized = serialize( $view['data'] );
	if ( 'add' == $action ) {
		$sql = "INSERT INTO {$table_name} (name, value) VALUES (%s, %s)";
		$sql = $wpdb->prepare( $sql, $view['name'], $serialized );
		$num_rows = $wpdb->query( $sql );
		$last_id = $wpdb->insert_id;
		return $last_id;
	}
	else {
		$sql = "UPDATE {$table_name} SET name = %s, value = %s WHERE id = %d";
		$sql = $wpdb->prepare( $sql, $view['name'], $serialized, intval( $view['id'] ) );
		$num_rows = $wpdb->query( $sql );
		return $num_rows;
	}
}

/**
 * @param $field
 *
 * @return mixed
 */
function wpmtst_get_field_label( $field ) {
	//$custom_fields = wpmtst_get_custom_fields();
	$custom_fields = wpmtst_get_form_fields();
	if ( isset( $field['field'] ) ) {
		foreach ( $custom_fields as $key => $custom_field ) {
			if ( $custom_field['name'] == $field['field'] ) {
				return $custom_field['label'];
			}
		}
	}

	return '';
}

/**
 * @param string $field_name
 *
 * @return mixed
 */
function wpmtst_get_field_by_name( $field_name = '' ) {
	//$custom_fields = wpmtst_get_custom_fields();
	$custom_fields = wpmtst_get_form_fields();
	foreach ( $custom_fields as $key => $custom_field ) {
		if ( $custom_field['name'] == $field_name ) {
			return $custom_field;
		}
	}

	return '';
}

function wpmtst_sort_array_by_name( $a, $b ) {
	if ( $a['name'] == $b['name'] )
		return 0;

	return ( $a['name'] < $b['name'] ) ? -1 : 1;
}

/**
 * Allow disabling of client-side form validation via filter.
 *
 * @since 1.21.0
 */
function wpmtst_using_form_validation_script() {
	return apply_filters( 'wpmtst_field_required_tag', true ) && apply_filters( 'wpmtst_form_validation_script', true );
}
