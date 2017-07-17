<?php
/**
 * Functions
 *
 * @package Strong_Testimonials
 */

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

		if ( 'featured_image' == $name ) {
			$post->thumbnail_id = get_post_thumbnail_id( $post->ID );
		}
		else {
			if ( isset( $custom[ $name ] ) )
				$post->$name = $custom[ $name ][0];
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
	$forms = get_option( 'wpmtst_custom_forms' );
	$all_fields = array();

	// use default group as base
	$fields = $forms[1]['fields'];

	// replace key with field name
	foreach ( $fields as $field ) {
		//if ( 'custom' == $field['record_type'] ) {
		if ( 'post' != $field['record_type'] ) {
			$all_fields[ $field['name'] ] = $field;
		}
	}

	// merge remaining form fields
	foreach ( $forms as $form ) {
		$custom_fields = array();
		$fields = $form['fields'];
		foreach ( $fields as $field ) {
			//if ( 'custom' == $field['record_type'] ) {
			if ( 'post' != $field['record_type'] ) {
				$custom_fields[ $field['name'] ] = $field;
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
	$forms = get_option( 'wpmtst_custom_forms' );
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
		$fields = $form['fields'];
		foreach ( $fields as $field ) {
			$custom_fields[ $field['name'] ] = $field;
		}
		$all_fields = array_merge( $all_fields, $custom_fields );
	}

	return $all_fields;
}

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
 * @param string $size
 *
 * @return array|bool|mixed
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

	/**
	 * Catch possibility of missing standard sizes.
	 * @since 2.2.5
	 */
	if ( $get_intermediate_image_sizes ) {
		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {

			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool)get_option( $_size . '_crop' );
			}
			elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop']
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
	$sizes['full']   = array( 'label' => 'original size uploaded', 'width' => 0, 'height' => 0 );
	$sizes['custom'] = array( 'label' => 'custom size', 'width' => 0, 'height' => 0 );

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
	if ( $a['width'] == $b['width'] ) {
		return 0;
	}
	return ($a['width'] < $b['width']) ? -1 : 1;
}

/**
 * Return a list of categories after removing any orderby filters.
 *
 * @since 2.2.3 If WPML is active, will find corresponding term ID in current language.
 *
 * @return array|int|WP_Error
 */
function wpmtst_get_category_list() {
	$category_list = get_terms( 'wpm-testimonial-category', array(
		'hide_empty' => false,
		'order_by'   => 'name',
		'pad_counts' => true,
	) );

	return $category_list;
}

/**
 * @return array|mixed|null|object
 */
function wpmtst_get_views() {
	global $wpdb;
	$wpdb->show_errors();
	$table_name = $wpdb->prefix . 'strong_views';
	$results = $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM $table_name WHERE name != %s ORDER BY id ASC", '_default'
	), ARRAY_A );
	$wpdb->hide_errors();

	if ( $wpdb->last_error ) {
		$message = sprintf(
			wp_kses(
				__( 'An error occurred: <code>%s</code>. Please <a href="%s" target="_blank">open a support ticket</a>.', 'strong-testimonials' ),
				array( 'code' => array(), 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ) )
			),
			$wpdb->last_error,
			esc_url( 'https://support.strongplugins.com/tickets/submit-ticket/' )
		);
		wp_die( sprintf( '<div class="error strong-view-error"><p>%s</p></div>', $message ) );
	}

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
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", (int) $id ), ARRAY_A );

	return $row;
}

/**
 * Save a View.
 *
 * @param        $view
 * @param string $action
 *
 * @return bool|false|int
 */
function wpmtst_save_view( $view, $action = 'edit' ) {
	global $wpdb;

	if ( ! $view ) return false;

	$table_name = $wpdb->prefix . 'strong_views';
	$serialized = serialize( $view['data'] );

	if ( 'add' == $action || 'duplicate' == $action ) {
		$sql = "INSERT INTO {$table_name} (name, value) VALUES (%s, %s)";
		$sql = $wpdb->prepare( $sql, $view['name'], $serialized );
		$wpdb->query( $sql );
		$view['id'] = $wpdb->insert_id;
		$return  = $view['id'];
	}
	else {
		$sql = "UPDATE {$table_name} SET name = %s, value = %s WHERE id = %d";
		$sql = $wpdb->prepare( $sql, $view['name'], $serialized, intval( $view['id'] ) );
		$wpdb->query( $sql );
		$return = $wpdb->last_error ? 0 : 1;
	}

	do_action( 'wpmtst_view_saved', $view );

	return $return;
}


/**
 * Do stuff after a View is saved.
 *
 * @since 2.11.17
 * @param $view
 */
function wpmtst_on_save_view( $view ) {

	// WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		wpmtst_readmore_wpml(
			array(
				'id'             => $view['id'],
				'more_post_text' => $view['data']['more_post_text'],
				'more_page_text' => $view['data']['more_page_text'],
			)
		);
	}

	// Polylang
	if ( defined( 'POLYLANG_VERSION' ) ) {
		wpmtst_readmore_polylang(
			array(
				'id'             => $view['id'],
				'more_post_text' => $view['data']['more_post_text'],
				'more_page_text' => $view['data']['more_page_text'],
			)
		);
	}

}
add_action( 'wpmtst_view_saved', 'wpmtst_on_save_view' );


/**
 * @param $field
 *
 * @return mixed
 */
function wpmtst_get_field_label( $field ) {
	if ( ! isset( $field['field'] ) ) return '';

	$custom_fields = wpmtst_get_custom_fields();
	foreach ( $custom_fields as $key => $custom_field ) {
		if ( $custom_field['name'] == $field['field'] ) {
			return $custom_field['label'];
		}
	}

	return ucwords( str_replace( '_', ' ', $field['field'] ) );
}

/**
 * @param $field
 *
 * @return mixed
 */
function wpmtst_get_field_default_display_value( $field ) {
	if ( isset( $field['field'] ) ) {
		$custom_fields = wpmtst_get_custom_fields();
		foreach ( $custom_fields as $key => $custom_field ) {
			if ( $custom_field['name'] == $field['field'] ) {
				return $custom_field['default_display_value'];
			}
		}
	}

	return '';
}

/**
 * @param $field
 *
 * @return mixed
 */
function wpmtst_get_field_shortcode_on_display( $field ) {
	if ( isset( $field['field'] ) ) {
		$custom_fields = wpmtst_get_custom_fields();
		foreach ( $custom_fields as $key => $custom_field ) {
			if ( $custom_field['name'] == $field['field'] ) {
				return $custom_field['shortcode_on_display'];
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
	$custom_fields = wpmtst_get_custom_fields();
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
	$width = (int) $options['embed_width'];
	if ( $width ) {
		$dimensions = array(
			'width'  => $width,
			'height' => min( ceil( $width * 1.5 ), 1000 )
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
	if ( 'wpm-testimonial' == $postarr['post_type'] )
		return false;

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

	if ( 'wpm-testimonial' == $post->post_type ) {
		echo '<div class="wpmtst-pub-section">';
		echo '<span id="submit-timestamp">&nbsp;';
		$submit_date = get_post_meta( $post->ID, 'submit_date', true );
		if ( $submit_date ) {
			echo 'Submitted on: <strong>' . date_i18n( __( 'M j, Y @ H:i' ), strtotime( $submit_date ) ) . '</strong>';
		} else {
			echo 'No submit date';
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
	return apply_filters( 'wpmtst_default_template_background', array(
		'color'              => '',
		'type'               => '',
		'preset'             => '',
		'gradient1'          => '',
		'gradient2'          => '',
		'example-font-color' => 'dark',
	) );
}


/**
 * @param null $preset
 *
 * TODO Move to options and add a filter.
 * @return array|bool
 */
function wpmtst_get_background_presets( $preset = null ) {
	$presets = array(
		'light-gray-gradient' => array(
			'label'  => __( 'light gray gradient', 'strong-testimonials' ),
			'color'  => '#FBFBFB',
			'color2' => '#EDEDED',
		),
		'light-blue-gradient' => array(
			'label'  => __( 'light blue gradient', 'strong-testimonials' ),
			'color'  => '#E7EFFE',
			'color2' => '#B8CFFB',
		),
		'sky-blue-gradient' => array(
			'label'  => __( 'sky blue gradient', 'strong-testimonials' ),
			'color'  => '#E9F6FB',
			'color2' => '#C8E9F6',
		),
		'light-latte-gradient' => array(
			'label'  => __( 'light latte gradient', 'strong-testimonials' ),
			'color'  => '#F8F3EC',
			'color2' => '#E0C8AB',
		),
		'light-green-mist-gradient' => array(
			'label'  => __( 'light green mist gradient', 'strong-testimonials' ),
			'color'  => '#F2FBE9',
			'color2' => '#E0F7CC',
		),
		'light-plum-gradient' => array(
			'label'  => __( 'light plum gradient', 'strong-testimonials' ),
			'color'  => '#F7EEF7',
			'color2' => '#E9D0E9',
		),
	);

	ksort( $presets );

	if ( !$preset )
		return $presets;

	if ( isset( $presets[ $preset] ) )
		return $presets[ $preset ];

	return false;
}


/**
 * Return the form success message.
 *
 * @since 2.18.0
 *
 * @return mixed
 */
function wpmtst_get_success_message() {
	$message = wpautop( do_shortcode( wpmtst_get_form_message( 'submission-success' ) ) );
	$message = sprintf( '<div class="%s">%s</div>', 'testimonial-success', $message );

	return apply_filters( 'wpmtst_form_success_message', $message );
}


/**
 * Does callback exist?
 *
 * @param $callback
 * @since 2.18.0
 *
 * @return bool
 */
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
		ob_start();
		?>
		<div class="testimonial-client normal">
			<?php wpmtst_single_template_client(); ?>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		$content .= $html;
	}

	return $content;
}
add_filter( 'the_content', 'wpmtst_single_template_add_content' );


/**
 * Find the view for the single template.
 *
 * @return bool|array
 */
function wpmtst_find_single_template_view() {
	$views = wpmtst_get_views();
	/*
	 * [id] => 1
     * [name] => TEST
     * [value] => {serialized_array}
	 */

	foreach ( $views as $view ) {
		$view_data = maybe_unserialize( $view['value'] );
		if ( isset( $view_data['mode'] ) && 'single_template' == $view_data['mode'] ) {
			return $view_data;
		}
	}

	return false;
}


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
	if ( ! $plugin )
		return false;

	$plugins = array(
		'wpml'     => 'sitepress-multilingual-cms/sitepress.php',
		'polylang' => 'polylang/polylang.php'
	);
	if ( isset( $plugins[ $plugin ] ) ) {
		$plugin = $plugins[ $plugin ];
	}

	if ( in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) )
	    return true;

	if ( ! is_multisite() )
		return false;

	$plugins = get_site_option( 'active_sitewide_plugins');
	if ( isset( $plugins[ $plugin ] ) )
		return true;

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
	/* LONGHAND
	if ( isset( $input['reorder'] ) ) {
		if ( 'on' == $input['reorder'] ) { // checked checkbox
			$new_input['reorder'] = 1;
		} else { // hidden input
			$new_input['reorder'] = $input['reorder']; // 0 or 1
		}
	} else { // unchecked checkbox
		$new_input['reorder'] = 0;
	}
	*/
	return ( isset( $input[ $key ] ) ? ( 'on' == $input[ $key ] ? 1 : $input[ $key ] ) : 0 );
}
