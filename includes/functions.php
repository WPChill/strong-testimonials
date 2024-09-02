<?php
/**
 * Functions
 */

function wpmtst_support_url() {
	return esc_url( '#' );
}

/**
 * Return default translation from po/mo files if no active translation plugin.
 *
 * @since 2.23.2
 * @param $string
 *
 * @return string
 */
function wpmtst_l10n_default( $l10n_string ) {
	return $l10n_string;
}
add_filter( 'wpmtst_l10n', 'wpmtst_l10n_default' );

/**
 * Append custom fields to post object.
 * Add thumbnail if included in field group.
 *
 * @param $post
 *
 * @return mixed
 */
function wpmtst_get_post( $post ) {
	$custom = get_post_custom( $post->ID );
	$fields = wpmtst_get_custom_fields();

	foreach ( $fields as $key => $field ) {
		$name = $field['name'];

		if ( 'featured_image' === $name ) {
			$post->thumbnail_id = get_post_thumbnail_id( $post->ID );
		} else {
			if ( isset( $custom[ $name ] ) ) {
				$post->$name = $custom[ $name ][0];
			} else {
				$post->$name = '';
			}
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
	if ( ! preg_match( '~^(?:f|ht)tps?://~i', $url ) ) {
		$url = 'https://' . $url;
	}

	return $url;
}

/**
 * Check whether a common script is already registered by file name
 * instead of handle.
 *
 * === Used in older versions to check for Cycle script. ===
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
	if ( ! $wp_scripts ) {
		return false;
	}

	$script_handle = '';

	foreach ( $wp_scripts->registered as $handle => $script ) {
		if ( in_array( basename( $script->src ), $filenames, true ) ) {
			$script_handle = $handle;
			break;
		}
	}

	return $script_handle;
}

if ( ! function_exists( 'get_page_by_slug' ) ) {
	/**
	 * Get page ID by slug.
	 *
	 * Thanks http://wordpress.stackexchange.com/a/102845/32076
	 * Does not require parent slug.
	 *
	 * @since 1.11.0
	 */
	function get_page_by_slug( $page_slug, $output = OBJECT, $post_type = 'page' ) {
		global $wpdb;
		$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) );
		if ( $page ) {
			return get_post( $page, $output );
		} else {
			return null;
		}
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
		$s = str_replace( "\n", '', $s );

		// remove all <p>
		$s = str_replace( '<p>', '', $s );

		// remove <br>
		$s = str_replace( array( '<br />', '<br/>', '<br>' ), '', $s );

		// remove </p>
		$s = str_replace( '</p>', '', $s );

		return $s;
	}
}

/**
 * Sort array based on 'order' element.
 *
 * @since 1.13
 */
function wpmtst_uasort( $a, $b ) {
	if ( $a['order'] === $b['order'] ) {
		return 0;
	}
	return ( $a['order'] < $b['order'] ) ? -1 : 1;
}

function wpmtst_get_custom_form_count() {
	$forms = get_option( 'wpmtst_custom_forms' );
	return count( $forms );
}

function wpmtst_get_form_fields( $form_id = 1 ) {
	$forms = get_option( 'wpmtst_custom_forms' );
	if ( isset( $forms[ $form_id ] ) ) {
		$form = $forms[ $form_id ];
	} else {
		$form = $forms[1];
	}
	$fields = $form['fields'];

	return $fields;
}

/**
 * Get only custom fields from all field groups.
 *
 * Used in post editor.
 *
 * @return array
 */
function wpmtst_get_custom_fields() {
	$all_fields = array();
	$forms      = get_option( 'wpmtst_custom_forms' );

	if ( ! $forms ) {
		return $all_fields;
	}
	// merge remaining form fields.
	foreach ( $forms as $form ) {

		$custom_fields = array();
		if ( isset( $form['fields'] ) ) {
			$fields = $form['fields'];
			foreach ( $fields as $field ) {
				if ( 'post' !== $field['record_type'] ) {
					$custom_fields[ $field['name'] ] = $field;
				}
			}
		}
		$all_fields = array_merge( $all_fields, $custom_fields );
	}

	return $all_fields;
}

/**
 * Get all fields from all field groups.
 *
 * Used for admin list columns.
 *
 * @return array
 */
function wpmtst_get_all_fields() {
	$forms      = get_option( 'wpmtst_custom_forms' );
	$all_fields = array();

	/**
	 * Use first custom form as the base because if we use 'default'
	 * and a field has 'admin_table' enabled in 'default'
	 * but not in any custom form, the column will still be shown.
	 */
	$fields = $forms[1]['fields'];

	// replace key with field name
	foreach ( $fields as $field ) {
		$all_fields[ $field['name'] ] = $field;
	}

	// merge remaining form fields
	foreach ( $forms as $form ) {
		$custom_fields = array();
		$fields        = $form['fields'];
		foreach ( $fields as $field ) {
			$custom_fields[ $field['name'] ] = $field;
		}
		$all_fields = array_merge( $all_fields, $custom_fields );
	}

	return $all_fields;
}

/**
 * Get all rating fields
 *
 * @return array
 */
function wpmtst_get_all_rating_fields() {

	$all_fields = wpmtst_get_all_fields();

	$rating_fields = array();

	foreach ( $all_fields as $key => $field ) :
		if ( 'rating' !== $field['input_type'] ) {
			continue;
		}
		$rating_fields[] = $field;
	endforeach;

	return $rating_fields;
}

/**
 * Get the built-in fields.
 *
 * @since 2.29.0
 */
function wpmtst_get_builtin_fields() {
	$builtin_fields = array(
		'post_date'   => array(
			'name'        => 'post_date',
			'label'       => 'Post Date',
			'input_type'  => 'date',
			'type'        => 'date',
			'record_type' => 'builtin',
		),
		'submit_date' => array(
			'name'        => 'submit_date',
			'label'       => 'Submit Date',
			'input_type'  => 'date',
			'type'        => 'date',
			'record_type' => 'builtin',
		),
		'category'    => array(
			'name'        => 'category',
			'label'       => 'Category',
			'input_type'  => 'category',
			'type'        => 'category',
			'record_type' => 'builtin',
		),
	);

	$options = get_option( 'wpmtst_options' );
	if ( isset( $options['include_platform'] ) && true === $options['include_platform'] ) {
		$builtin_fields[] = array(
			'name'        => 'platform',
			'label'       => 'Platform',
			'input_type'  => 'platform',
			'type'        => 'platform',
			'record_type' => 'builtin',
		);
	}

	return $builtin_fields;
}

function wpmtst_get_image_sizes( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes                        = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	/**
	 * Catch possibility of missing standard sizes.
	 * @since 2.2.5
	 */
	if ( $get_intermediate_image_sizes ) {
		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {

			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ), true ) ) {

				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);

			}
		}

		// Sort by width
		uasort( $sizes, 'wpmtst_compare_width' );

		// Add option labels
		foreach ( $sizes as $key => $dimensions ) {
			$sizes[ $key ]['label'] = sprintf( '%s - %d x %d', $key, $dimensions['width'], $dimensions['height'] );
		}
	}

	// Add extra options
	$sizes['full']   = array(
		'label'  => 'original size uploaded',
		'width'  => 0,
		'height' => 0,
	);
	$sizes['custom'] = array(
		'label'  => 'custom size',
		'width'  => 0,
		'height' => 0,
	);

	// Get only one size if found
	if ( $size ) {
		if ( isset( $sizes[ $size ] ) ) {
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
	if ( $a['width'] === $b['width'] ) {
		return 0;
	}
	return ( $a['width'] < $b['width'] ) ? -1 : 1;
}

/**
 * @return int
 */
function wpmtst_get_cat_count() {
	return count( get_terms( 'wpm-testimonial-category', array( 'hide_empty' => false ) ) );
}

/**
 * Return a list of categories after removing any orderby filters.
 *
 * @since 2.2.3 If WPML is active, will find corresponding term ID in current language.
 *
 * @param int $cat_parent
 *
 * @return array|int|WP_Error
 */
function wpmtst_get_cats( $cat_parent = 0 ) {
	return get_terms(
		'wpm-testimonial-category',
		array(
			'hide_empty' => false,
			'parent'     => $cat_parent,
		)
	);
}

/**
 * @param $value
 * @param int $cat_parent
 * @param int $level
 */
function wpmtst_nested_cats( $value, $cat_parent = 0, $level = 0 ) {
	$cats = wpmtst_get_cats( $cat_parent );
	if ( $cats ) {
		foreach ( $cats as $cat ) {
			$selected = in_array( $cat->term_id, $value, true ) ? ' selected' : '';
			printf( '<option value="%s"%s>%s%s</option>', esc_attr( $cat->term_id ), esc_attr( $selected ), esc_html( str_repeat( '&nbsp;&nbsp;&nbsp;', $level ) ), esc_html( $cat->name ) );
			wpmtst_nested_cats( $value, $cat->term_id, $level + 1 );
		}
	}
}

function wpmtst_sort_array_by_name( $a, $b ) {
	if ( $a['name'] === $b['name'] ) {
		return 0;
	}

	return ( $a['name'] < $b['name'] ) ? -1 : 1;
}

/**
 * Allow disabling of client-side form validation via filter.
 *
 * @since 1.21.0
 * @deprecated since 2.18.1
 */
function wpmtst_using_form_validation_script() {
	return true;
}

/**
 * Set iframe width of embedded videos.
 *
 * @since 2.6.0
 * @param $dimensions
 * @param $url
 *
 * @return array
 */
function wpmtst_embed_size( $dimensions, $url ) {
	$options = get_option( 'wpmtst_options' );
	$width   = (int) $options['embed_width'];
	if ( $width ) {
		$dimensions = array(
			'width'  => $width,
			'height' => min( ceil( $width * 1.5 ), 1000 ),
		);
	}

	return $dimensions;
}

/**
 * Allow empty posts.
 *
 * @since 2.6.0
 * @param $maybe_empty
 * @param $postarr
 *
 * @return bool
 */
function wpmtst_insert_post_empty_content( $maybe_empty, $postarr ) {
	if ( 'wpm-testimonial' === $postarr['post_type'] ) {
		return false;
	}

	return $maybe_empty;
}
add_filter( 'wp_insert_post_empty_content', 'wpmtst_insert_post_empty_content', 10, 2 );

/**
 * Display submit_date in Publish meta box under Published date.
 *
 * @param $post @since WordPress 4.4
 * @since 2.12.0
 */
function wpmtst_post_submitbox_misc_actions( $post ) {
	if ( ! $post ) {
		global $post;
	}

	if ( 'wpm-testimonial' === $post->post_type ) {
		echo '<div class="wpmtst-pub-section">';
		echo '<span id="submit-timestamp">&nbsp;';
		$submit_date = get_post_meta( $post->ID, 'submit_date', true );
		if ( $submit_date ) {
			echo 'Submitted on: <strong>' . wp_kses_post( date_i18n( 'M j, Y @ H:i', strtotime( $submit_date ) ) ) . '</strong>';
		} else {
			esc_html_e( 'No submit date', 'strong-testimonials' );
		}
		echo '</span>';
		echo '</div>';
	}
}
add_action( 'post_submitbox_misc_actions', 'wpmtst_post_submitbox_misc_actions' );

/**
 * @return mixed
 */
function wpmtst_get_background_defaults() {
	return apply_filters(
		'wpmtst_default_template_background',
		array(
			'color'     => '',
			'type'      => '',
			'preset'    => '',
			'gradient1' => '',
			'gradient2' => '',
		)
	);
}

/**
 * @param null $preset
 *
 * TODO Move to options and add a filter.
 * @return array|bool
 */
function wpmtst_get_background_presets( $preset = null ) {
	$presets = array(
		'light-blue-gradient'       => array(
			'label'  => esc_html__( 'light blue gradient', 'strong-testimonials' ),
			'color'  => '#E7EFFE',
			'color2' => '#B8CFFB',
		),
		'light-gray-gradient'       => array(
			'label'  => esc_html__( 'light gray gradient', 'strong-testimonials' ),
			'color'  => '#FBFBFB',
			'color2' => '#EDEDED',
		),
		'light-green-mist-gradient' => array(
			'label'  => esc_html__( 'light green mist gradient', 'strong-testimonials' ),
			'color'  => '#F2FBE9',
			'color2' => '#E0F7CC',
		),
		'light-latte-gradient'      => array(
			'label'  => esc_html__( 'light latte gradient', 'strong-testimonials' ),
			'color'  => '#F8F3EC',
			'color2' => '#E0C8AB',
		),
		'light-plum-gradient'       => array(
			'label'  => esc_html__( 'light plum gradient', 'strong-testimonials' ),
			'color'  => '#F7EEF7',
			'color2' => '#E9D0E9',
		),
		'sky-blue-gradient'         => array(
			'label'  => esc_html__( 'sky blue gradient', 'strong-testimonials' ),
			'color'  => '#E9F6FB',
			'color2' => '#C8E9F6',
		),
	);

	if ( $preset ) {
		if ( isset( $presets[ $preset ] ) ) {
			return $presets[ $preset ];
		} else {
			return wpmtst_get_background_defaults();
		}
	}

	return $presets;
}

/**
 * Return the form success message.
 *
 * @since 2.18.0
 *
 * @return mixed
 */
function wpmtst_get_success_message( $atts = false ) {
	$message = wpautop( do_shortcode( wpmtst_get_form_message( 'submission-success' ) ) );
	$message = sprintf( '<div class="%s">%s</div>', 'wpmtst-testimonial-success', $message );

	return apply_filters( 'wpmtst_form_success_message', $message, $atts );
}

/**
 * Does callback exist?
 *
 * @param $callback
 * @since 2.18.0
 *
 * @return bool
 */
// TODO Move to Utils class
function wpmtst_callback_exists( $callback ) {
	if ( is_array( $callback ) ) {
		$exists = method_exists( $callback[0], $callback[1] );
	} else {
		$exists = function_exists( $callback );
	}

	return $exists;
}

/**
 * Check for Divi Builder plugin.
 *
 * Its plugin version constant is inaccurate so get the version from the file header.
 *
 * @since 2.22.0
 *
 * @return bool
 */
function wpmtst_divi_builder_active() {
	$active = false;
	if ( wpmtst_is_plugin_active( 'divi-builder/divi-builder.php' ) ) {
		$plugin = get_file_data( WP_PLUGIN_DIR . '/divi-builder/divi-builder.php', array( 'version' => 'Version' ) );
		if ( isset( $plugin['version'] ) && version_compare( $plugin['version'], '2' ) > 0 ) {
			$active = true;
		}
	}

	return $active;
}

/**
 * Append custom fields to testimonial content in theme's single post template.
 *
 * @param $content
 * @since 2.22.0
 *
 * @return string
 */
function wpmtst_single_template_add_content( $content ) {
	if ( is_singular( 'wpm-testimonial' ) || is_tax( 'wpm-testimonial-category' ) ) {
		$content .= wpmtst_single_template_client();
	}

	return $content;
}
add_filter( 'the_content', 'wpmtst_single_template_add_content' );

/**
 * Frequent plugin checks.
 *
 * A combination of an array of frequent plugin names, and core's is_plugin_active functions
 * which are not available in front-end without loading plugin.php which is uncecessary.
 *
 * @param $plugin
 *
 * @return bool
 */
function wpmtst_is_plugin_active( $plugin = '' ) {
	if ( ! $plugin ) {
		return false;
	}

	$plugins = array(
		'wpml'                           => 'sitepress-multilingual-cms/sitepress.php',
		'polylang'                       => 'polylang/polylang.php',
		'lazy-loading-responsive-images' => 'lazy-loading-responsive-images/lazy-load-responsive-images.php',
	);
	if ( isset( $plugins[ $plugin ] ) ) {
		$plugin = $plugins[ $plugin ];
	}

	if ( in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ) {
		return true;
	}

	if ( ! is_multisite() ) {
		return false;
	}

	$plugins = get_site_option( 'active_sitewide_plugins' );
	if ( isset( $plugins[ $plugin ] ) ) {
		return true;
	}

	return false;
}

/**
 * Sanitize a textarea from user input. Based on sanitize_text_field.
 *
 * Check for invalid UTF-8,
 * Convert single < characters to entity,
 * strip all tags,
 * strip octets.
 *
 * @since 2.11.8
 *
 * @param string $text
 *
 * @return string
 */
function wpmtst_sanitize_textarea( $text ) {
	$filtered = wp_check_invalid_utf8( $text );

	if ( strpos( $filtered, '<' ) !== false ) {
		$filtered = wp_pre_kses_less_than( $filtered );
		// This will NOT strip extra whitespace.
		$filtered = wp_strip_all_tags( $filtered, false );
	}

	while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) {
		$filtered = str_replace( $match[0], '', $filtered );
	}

	/**
	 * Filter a sanitized textarea string.
	 *
	 * @param string $filtered The sanitized string.
	 * @param string $str The string prior to being sanitized.
	 */
	return apply_filters( 'wpmtst_sanitize_textarea', $filtered, $text );
}

/**
 * Store values as 1 or 0 (never blank).
 *
 * Checked checkbox value is "on" but unchecked checkboxes are _not_ submitted.
 *
 * @param $input
 * @param $key string  Must be explicit. Do not simply loop through an input array.
 *
 * @return int
 */
function wpmtst_sanitize_checkbox( $input, $key ) {
	if ( isset( $input[ $key ] ) ) {
		if ( 'on' === $input[ $key ] ) {   // checked checkbox
			return true;
		} else {   // hidden input
			return $input[ $key ] ? true : false;   // 0 or 1
		}
	} else {   // unchecked checkbox
		return false;
	}
}

/**
 * Trims a entire array recursively.
 *
 * @since 2.26.6
 *
 * @props      Jonas John
 * @version     0.2
 * @link        http://www.jonasjohn.de/snippets/php/trim-array.htm
 * @param       $input array|string
 *
 * @return array|string
 */
function wpmtst_trim_array( $input ) {
	if ( ! is_array( $input ) ) {
		return trim( $input );
	}

	return array_map( 'wpmtst_trim_array', $input );
}

if ( ! function_exists( 'normalize_empty_atts' ) ) {
	/**
	 * Normalize empty shortcode attributes.
	 *
	 * Turns atts into tags - brilliant!
	 * Thanks http://wordpress.stackexchange.com/a/123073/32076
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	function normalize_empty_atts( $atts ) {
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
		}

		return $atts;
	}
}

// @todo : check in addons to see if function is called somewhere, else delete it
if ( ! function_exists( 'wpmtst_round_to_half' ) ) {
	/**
	 * Round to the nearest half.
	 *
	 * @param $value
	 *
	 * @since 2.31.0
	 * @return float|int
	 */
	function wpmtst_round_to_half( $value ) {
		if ( is_string( $value ) ) {
			$value = (float) str_replace( ',', '.', $value );
		}
		return round( (float) $value * 2 ) / 2;
	}
}


if ( ! function_exists( 'wpmtst_strip_whitespace' ) ) {
	/**
	 * Remove whitespace from HTML output.
	 *
	 * @param $html
	 *
	 * @return string
	 */
	function wpmtst_strip_whitespace( $html ) {
		return preg_replace( '~>\s+<~', '><', trim( $html ) );
	}
}

if ( ! function_exists( 'wpmtst_current_url' ) ) {
	/**
	 * Assemble and return the current URL.
	 *
	 * @since 2.31.0
	 * @return string
	 */
	function wpmtst_current_url() {
		global $wp;

		return home_url( add_query_arg( array(), $wp->request ) );
	}
}
if ( ! function_exists( 'get_formatted_views' ) ) {

	function get_formatted_views() {
		$views = wpmtst_get_views();

		$view_array = array( 'none' => esc_html__( 'None', 'strong-testimonials' ) );
		foreach ( $views as $view ) {
			$view_array[ $view['id'] ] = esc_html( $view['name'] );
		}
		return $view_array;
	}
}

/**
 * Get stars svg
 *
 * @return string
 * @since 3.1.9
 */
function wpmtst_get_star_svg( $type = 'star_solid' ) {
	$star = array();

	$star['star_solid']   = '<svg class="star_solid" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>';
	$star['star_regular'] = '<svg class="star_regular" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"></path></svg>';
	$star['star_half']    = '<svg class="star_half" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M508.55 171.51L362.18 150.2 296.77 17.81C290.89 5.98 279.42 0 267.95 0c-11.4 0-22.79 5.9-28.69 17.81l-65.43 132.38-146.38 21.29c-26.25 3.8-36.77 36.09-17.74 54.59l105.89 103-25.06 145.48C86.98 495.33 103.57 512 122.15 512c4.93 0 10-1.17 14.87-3.75l130.95-68.68 130.94 68.7c4.86 2.55 9.92 3.71 14.83 3.71 18.6 0 35.22-16.61 31.66-37.4l-25.03-145.49 105.91-102.98c19.04-18.5 8.52-50.8-17.73-54.6zm-121.74 123.2l-18.12 17.62 4.28 24.88 19.52 113.45-102.13-53.59-22.38-11.74.03-317.19 51.03 103.29 11.18 22.63 25.01 3.64 114.23 16.63-82.65 80.38z"></path></svg>';

	if ( isset( $star[ $type ] ) ) {
		return $star[ $type ];
	}

	return '';
}
