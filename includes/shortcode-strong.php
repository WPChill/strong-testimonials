<?php
/**
 * Strong shortcode functions.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */

/**
 * Strong shortcode.
 * Simply a router for various modes.
 *
 * @since 1.11.0
 *
 * @param array  $atts
 * @param null   $content     Contains only child shortcodes like [client].
 * @param string $parent_tag  Used for child shortcodes.
 *
 * @return mixed|string|void
 */
function wpmtst_strong_shortcode( $atts, $content = null, $parent_tag ) {
	/**
	 * SEQUENCE
	 * 1. normalize_empty_atts( $atts ) turns 'attribute' into 'attribute=true'
	 * 2. shortcode_atts merges normalized attributes onto default pairs
	 * 3. filter is applied (where view is fetched)
	 * 4. process common attributes
	 * 5. store in main object for use in template functions
	 */
	$out = shortcode_atts(
		WPMST()->get_view_defaults(),
		normalize_empty_atts( $atts ),
		$parent_tag
	);
	$out['content']    = $content;
	$out['parent_tag'] = $parent_tag;

	// container_class is shared by display and form in both original and new default templates
	$options = get_option( 'wpmtst_options' );
	if ( $out['class'] ) {
		$out['container_class'] .= ' ' . str_replace( ',', ' ', $out['class'] );
	}
	if ( is_rtl() && $options['load_rtl_style'] ) {
		$out['container_class'] .= ' rtl';
	}
	WPMST()->set_atts( $out );

	/**
	 * MODE: FORM
	 */
	if ( $out['form'] )
		return wpmtst_form_shortcode( $out );

	/**
	 * MODE: READ MORE LINK
	 * Will be removed in 2.0
	 */
	if ( $out['read_more'] )
		return wpmtst_readmore_shortcode( $out, $content );

	/**
	 * MODE: DISPLAY (default)
	 */
	return wpmtst_display_view( $out );
}
add_shortcode( wpmtst_get_shortcode(), 'wpmtst_strong_shortcode' );

/**
 * Attribute filter for strong shortcode.
 *
 * This filter was used by [strong view] to get the view and merge it onto the
 * shortcode defaults, but views are now parsed through a different shortcode.
 *
 * @since 1.11.0
 *
 * @param array $out The output array of shortcode attributes.
 * @param array $pairs The supported attributes and their defaults.
 * @param array $atts The user defined shortcode attributes.
 *
 * @return array
 */
function wpmtst_strong_shortcode_filter( $out, $pairs, $atts ) {
	return $out;
}
add_filter( 'shortcode_atts_' . wpmtst_get_shortcode(), 'wpmtst_strong_shortcode_filter', 10, 3 );

/**
 * Strong view - display mode
 *
 * @param $atts
 *
 * @return mixed|string|void
 */
function wpmtst_display_view( $atts ) {
	global $strong_templates;
	extract( $atts );

	if ( isset( $view_not_found ) && $view_not_found )
		return '<p style="color:red">' . __( sprintf( 'Strong Testimonials error: View %s not found', $view ) ) . '</p>';

	// classes
	$content_class_list   = '';
	$post_class_list      = 'testimonial';

	// excerpt overrides length
	if ( $excerpt ) {
		$post_class_list .= ' excerpt';
	} elseif ( $length ) {
		$post_class_list .= ' truncated';
	}

	/**
	 * Build the query
	 */

	$categories = explode( ',', $category );
	$ids        = explode( ',', $id );

	$args = array(
		'post_type'      => 'wpm-testimonial',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);

	// id overrides category
	if ( $id ) {
		$args['post__in'] = $ids;
	} elseif ( $category ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'wpm-testimonial-category',
				'field'    => 'id',
				'terms'    => $categories
			)
		);
	}

	// order by
	if ( $menu_order ) {
		$args['orderby'] = 'menu_order';
		$args['order']   = 'ASC';
	} else {
		$args['orderby'] = 'post_date';
		if ( $newest ) {
			$args['order'] = 'DESC';
		} else {
			$args['order'] = 'ASC';
		}
	}

	// For Post Types Order plugin
	$args['ignore_custom_sort'] = true;

	$query = new WP_Query( $args );

	/**
	 * Shuffle array in PHP instead of SQL.
	 *
	 * @since 1.16
	 */
	if ( $random ) {
		shuffle( $query->posts );
	}

	/**
	 * Extract slice of array, which may be shuffled.
	 *
	 * Use lesser value: requested count or actual count.
	 * Thanks chestozo.
	 * @link https://github.com/cdillon/strong-testimonials/pull/5
	 *
	 * @since 1.16.1
	 */
	if ( !$all && $count > 0 ) {
		$count = min( $count, count( $query->posts ) );
		$query->posts = array_slice( $query->posts, 0, $count );
		$query->post_count = $count;
	}

	$post_count = $query->post_count;

	/**
	 * -------------------
	 * SUB-MODE: SLIDESHOW
	 * -------------------
	 * This check must be after the query due to changes in the random option.
	 */
	if ( $slideshow ) {
		// add slideshow signature
		$args = array(
			'fx'      => 'fade',
			'speed'   => $effect_for * 1000,
			'timeout' => $show_for * 1000,
			'pause'   => $no_pause ? 0 : 1
		);
		$content_class_list .= ' strong_cycle strong_cycle_' . hash( 'md5', serialize( $args ) );
		$post_class_list    .= ' t-slide';
	}
	else {
		// pagination
		if ( $per_page && $post_count > $per_page ) {
			$content_class_list .= ' strong-paginated';
		}

		// layouts
		if ( 'masonry' == $layout ) {
			$content_class_list .= ' strong-masonry columned columns-' . $column_count;
		}
		elseif ( 'grid' == $layout ) {
			$content_class_list .= ' strong-grid columned columns-' . $column_count;
		}
		elseif ( 'columns' == $layout ) {
			$content_class_list .= ' strong-columns columned columns-' . $column_count;
		}
	}

	/**
	 * [TESTIMONIAL_VIEW] or [STRONG {OPTIONS}] ?
	 */
	if ( $view ) {
		/**
		 * Add new values to shortcode atts
		 */
		if ( 'custom' == $thumbnail_size ) {
			$atts['thumbnail_size'] = array( $thumbnail_width, $thumbnail_height );
		}
		$atts['content_class'] = $content_class_list;
		$atts['post_class']    = $post_class_list;
		WPMST()->set_atts( $atts );
	}
	else {
		/**
		 * Maintain compatibility with original template
		 * which uses the parsed shortcode options.
		 */
		if ( $thumbnail_size ) {
			$dimensions = explode( ',', $thumbnail_size );
			if ( 1 == count( $dimensions ) ) {
				$thumbnail_size = $dimensions[0];
			} else {
				$thumbnail_size = $dimensions;
			}
		}
		$container_class_list = $atts['class'];
		$shortcode_content = reverse_wpautop( $content );
		$show_client = has_child_shortcode( $shortcode_content, 'client', $parent_tag );
	}

	/**
	 * Add filters here.
	 */
	add_filter( 'get_avatar', 'wpmtst_get_avatar', 10, 3 );

	/**
	 * Load template
	 */
	if ( $view ) {
		$template_file = $strong_templates->get_template_attr( $atts, 'template' );
	}
	else {
		// [strong] shortcode
		$template_file = wpmtst_find_template( 'testimonials', $template );
	}

	ob_start();
	include( $template_file );
	$html = ob_get_contents();
	ob_end_clean();

	/**
	 * Remove filters here.
	 */
	remove_filter( 'get_avatar', 'wpmtst_get_avatar' );

	if ( $view )
		do_action( 'wpmtst_view_rendered', $atts );

	wp_reset_postdata();
	$html = apply_filters( 'strong_html', $html );

	return $html;
}

/**
 * Strong shortcode - read_more mode
 *
 * @param $atts
 * @param $content
 *
 * @return string
 */
function wpmtst_readmore_shortcode( $atts, $content ) {
	if ( ! $atts['page'] )
		return '';

	// page ID or slug?
	$page    = (int) $atts['page'] ? (int) $atts['page'] : get_page_by_slug( $atts['page'] );
	$content = $content ? $content : _x( 'Read more', 'link', 'strong-testimonials' );
	return '<div class="' . $atts['class'] . '"><a href="' . get_permalink( $page ) . '">' . $content . '</a></div>';
}

/**
 * Template search.
 *
 * Use normal theme search order and allow filtering.
 * Called by shortcode and when enqueueing stylesheets.
 *
 * @since 1.21.0
 * @param string $template
 * @return string $template_file
 */
function wpmtst_find_template( $type = '', $template = '' ) {
	if ( !$type ) return false;

	$search_array = array();
	if ( $template ) {
		$search_array[] = "{$type}-{$template}.php";
	}
	$search_array[] = "{$type}.php";

	$template_file = get_query_template( $type, $search_array );

	//TODO Add filter.
	if ( ! $template_file ) {
		$template_file = WPMTST_ORIG_TPL . "{$type}.php";
	}

	return $template_file;
}

/**
 * Replace attribute values with $post values
 * but don't overwrite other attributes like "class".
 *
 * from : [url] => company_website
 *   to : [url] => http://example.com -OR- (empty string)
 *
 * @since 1.11.0
 *
 * @param array $out The output array of shortcode attributes.
 * @param array $atts The input array of shortcode attributes.
 * @param array $post The testimonial post.
 *
 * @return array
 */
function wpmtst_atts_to_values( $out, $atts, $post ) {
	// for fields listed in shortcode attributes:
	foreach ( $atts as $key => $field ) {
		if ( 'name' == $key || 'url' == $key ) {
			if ( isset( $post->$field ) )
				$out[$key] = $post->$field;
			else
				$out[$key] = '';  // @since 1.12
		}
		elseif ( 'date' == $key ) {
			$out[$key] = 'the_date';
		}
	}

	// for fields *not* listed in shortcode attributes:
	// approach: no global + local enable
	$out['nofollow'] = ( 'on' == $post->nofollow );
	return $out;
}
