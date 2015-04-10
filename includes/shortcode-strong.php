<?php
/**
 * Strong shortcode functions.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */


/**
 * Template filter.
 *
 * Returns plugin's default template if template not found in theme.
 *
 * @since 1.11.0
 */
function wpmtst_loop_template_filter( $template ) {
	// Need to check option here for using built-in theme, e.g. "2-column", "rounded", etc.
	if ( ! $template )
		return WPMTST_TPL . 'default/testimonials.php';
	else
		return $template;
}
add_filter( 'testimonials_template', 'wpmtst_loop_template_filter', 99 );


/**
 * Do not texturize [strong].
 *
 * @since 1.11.5
 */
function wpmtst_no_texturize_shortcodes( $shortcodes ) {
	$shortcodes[] = 'strong';
	return $shortcodes;
}
add_filter( 'no_texturize_shortcodes', 'wpmtst_no_texturize_shortcodes' );


/**
 * Strong shortcode.
 *
 * @since 1.11.0
 */
function wpmtst_strong_shortcode( $atts, $content = null, $parent_tag ) {
	global $child_shortcode_tags;
	// $content contains only child shortcodes like [client]
	extract( shortcode_atts(
		array(
				// modes
				'form' => '',
				'slideshow' => '',
				'read_more' => '',
				
				// main attributes
				'category' => '',
				'class' => '',
				'template' => '',
				
				// display loop attributes
				'count' => -1,
				'per_page' => '',
				'nav' => 'after',
				'id' => '',
				'menu_order' => '',  // @since 1.16
				'random' => '',
				'newest' => '',
				'oldest' => '',
				'title'  => '',
				'thumbnail' => '',
				'excerpt' => '',
				'length' => '',
				'more_post' => '',
				'more_text' => _x( 'Read more', 'link', 'strong-testimonials' ),
				
				// slideshow attributes
				'show_for' => '8',
				'effect_for' => '1.5',
				// 'pause' => true,
				'no_pause' => 'false',
				
				// read more link attributes
				// 'class' => '',
				'page' => '',
		),
		normalize_empty_atts( $atts ), 
		$parent_tag
	) );

	$options = get_option( 'wpmtst_options' );
	
	$shortcode_content = reverse_wpautop( $content );
	$show_client       = has_child_shortcode( $shortcode_content, 'client', $parent_tag );
	
	$container_class_list = '';
	$content_class_list   = '';
	
	// ==========
	// MODE: FORM
	// ==========
	if ( $form ) {
		return wpmtst_form_shortcode( $atts );  // move this to include
	}
	
	// ====================
	// MODE: READ MORE LINK
	// ====================
	if ( $read_more ) {
		// page ID or slug?
		if ( ! intval( $page ) )
			$page = get_page_by_slug( $page );
			
		if ( ! $content )
			$content = _x( 'Read more', 'link', 'strong-testimonials' );
			
		return '<div class="' . $class . '"><a href="' . get_permalink( $page ) . '">' . $content . '</a></div>';
	}
	
	
	// =======================
	// MODE: DISPLAY (default)
	// =======================
	
	// ------------------------------
	// extract comma-separated values
	// ------------------------------
	$categories = explode( ',', $category );
	$ids = explode( ',', $id );
	if ( $class )
		$container_class_list .= ' ' . str_replace( ',', ' ', $class );
	
	// ------------------------
	// assemble query arguments
	// ------------------------
	$args = array(
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
	);
	
	// id overrides category
	if ( $id ) {
		$args['post__in'] = $ids;
	}
	elseif ( $category ) {
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
	}
	else {
		$args['orderby'] = 'post_date';
		if ( $newest )
			$args['order'] = 'DESC';
		else 
			$args['order'] = 'ASC';
	}

	// query
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
	 * @since 1.16.1
	 */
	if ( $count > 0 ) {
		$count = min( $count, count( $query->posts ) );
		$query->posts = array_slice( $query->posts, 0, $count );
		$query->post_count = $count;
	}
	
	$post_count = $query->post_count;
	
	// ===================
	// SUB-MODE: SLIDESHOW
	// ===================
	if ( $slideshow ) {
		// add shortcode signature
		$content_class_list .= ' strong_cycle strong_cycle_' . hash( 'md5', serialize( $atts ) );
	}
	else {
		// pagination
		if ( $per_page && $post_count > $per_page ) {
			$content_class_list .= ' strong-paginated';
		}
	}

	// ------------------------------
	// individual testimonial classes
	// ------------------------------
	$post_class_list = "testimonial";
	
	// excerpt overrides length
	if ( $excerpt )
		$post_class_list .= ' excerpt';
	elseif ( $length ) 
		$post_class_list .= ' truncated';
		
	if ( $slideshow )
		$post_class_list .= ' t-slide';
		
	// -------------
	// load template
	// -------------
	// search order:
	// 1. Custom template (via shortcode parameter) in child theme
	// 2. Custom template (via shortcode parameter) in parent theme
	// 3. Default template in child theme
	// 4. Default template in parent theme
	// 5. Default template in plugin `templates` directory (via filter)
	
	$template_file = '';
	
	if ( $template )
		$template_file = get_query_template( "testimonials-{$template}" );
		
	if ( ! $template || ! $template_file )
		$template_file = get_query_template( "testimonials" );
	
	ob_start();
	include( $template_file );
	$html = ob_get_contents();
	ob_end_clean();
	
	wp_reset_postdata();
	
	$html = apply_filters( 'strong_html', $html );
	return $html;
}
add_shortcode( 'strong', 'wpmtst_strong_shortcode' );


/**
 * Attribute filter for strong shortcode.
 *
 * @since 1.11.0
 */
function wpmtst_strong_shortcode_filter( $out, $pairs, $atts ) {
	return $out;
}
add_filter( 'shortcode_atts_strong', 'wpmtst_strong_shortcode_filter', 10, 3 );


/*===========================================================================*/


/**
 * Child shortcode for the client section.
 *
 * Just a wrapper for client child shortcodes. No attributes yet.
 *
 * @since 1.11.0
 */
function wpmtst_strong_client_shortcode( $atts, $content = null, $tag ) {
	return do_child_shortcode( 'strong', $content );
}
add_child_shortcode( 'strong', 'client', 'wpmtst_strong_client_shortcode' );


/**
 * Attribute filter for client child shortcode.
 *
 * @since 1.11.0
 */
function wpmtst_client_shortcode_filter( $out, $pairs, $atts, $post ) {
	return $out;
}
add_filter( 'child_shortcode_atts_client', 'wpmtst_client_shortcode_filter', 10, 3 );


/*===========================================================================*/


/**
 * Child shortcode for a custom field.
 *
 * [field name="client_name" class="name"]
 * [field name="company_name" url="company_website" class="name" new_tab]
 * No child shortcodes.
 *
 * @since 1.11.0
 */
function wpmtst_strong_field_shortcode( $atts, $content = null, $tag ) {
	extract( child_shortcode_atts(
		array(
				'name'     => '',	// custom field
				'url'      => '', // custom field
				'class'    => '', // CSS
				'new_tab'  => false,
				// 'nofollow' => false   // approach: no global + local enable (via filter)
		),
		normalize_empty_atts( $atts ),
		$tag
	) );

	if ( $url ) {
		if ( '' == $name ) {
			$name = preg_replace( '(^https?://)', '', $url );
		}
		$name = "<a href=\"$url\"" . link_new_tab( $new_tab, false ) . link_nofollow( $nofollow, false ) . ">$name</a>";
	}
	
	/*
	 * Bug fix: Return blank string.
	 *
	 * @since 1.12.0
	 */
	if ( '' == $name )
		return;
	else
		return '<div class="' . $class . '">' . $name . '</div>';
}
add_child_shortcode( 'strong', 'field', 'wpmtst_strong_field_shortcode' );


/**
 * Attribute filter for [field] child shortcode.
 *
 * @since 1.11.0
 * @param array $out   The output array of shortcode attributes.
 * @param array $pairs The array of accepted parameters and their defaults.
 * @param array $atts  The input array of shortcode attributes.
 */
function wpmtst_field_shortcode_filter( $out, $pairs, $atts ) {
	global $post;
	if ( $post )
		return wpmtst_atts_to_values( $out, $atts, $post );
	else
		return $out;
}
add_filter( 'child_shortcode_atts_field', 'wpmtst_field_shortcode_filter', 10, 3 );


/*===========================================================================*/


/**
 * Replace attribute values with $post values
 * but don't overwrite other attributes like "class".
 *
 * from : [url] => company_website
 *   to : [url] => http://example.com -OR- (empty string)
 *
 * @since 1.11.0
 * @param array $out   The output array of shortcode attributes.
 * @param array $atts  The input array of shortcode attributes.
 * @param array $post  The testimonial post.
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
	}

	// for fields *not* listed in shortcode attributes:
	// approach: no global + local enable
	$out['nofollow'] = ( 'on' == $post->nofollow );
	return $out;
}
