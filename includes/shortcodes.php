<?php
/**
 * Shortcode functions.
 *
 * @package Strong_Testimonials
 */

/**
 * testimonial_view shortcode
 * Merely a wrapper for the [strong] shortcode until verion 2.0
 *
 * @param      $atts
 * @param null $content
 *
 * @return mixed|string|void
 */
function wpmtst_strong_view_shortcode( $atts, $content = null ) {
	$out = shortcode_atts(
		WPMST()->get_view_defaults(),
		normalize_empty_atts( $atts ), 'testimonial_view'
	);

	return wpmtst_render_view( $out );
}
add_shortcode( 'testimonial_view', 'wpmtst_strong_view_shortcode' );

/**
 * testimonial_view attribute filter
 *
 * @since 1.21.0
 * @param $out
 * @param $pairs
 * @param $atts
 *
 * @return array
 */
function wpmtst_strong_view_shortcode_filter( $out, $pairs, $atts ) {
	return WPMST()->parse_view( $out, $pairs, $atts );
}
add_filter( 'shortcode_atts_testimonial_view', 'wpmtst_strong_view_shortcode_filter', 10, 3 );

function wpmtst_render_view( $out ) {
	// Did we find this view?
	if ( isset( $out['view_not_found'] ) && $out['view_not_found'] ) {
		return '<p style="color:red">' . __( sprintf( 'Strong Testimonials error: View %s not found', $out['view'] ) ) . '</p>';
	}

	// Container class is shared by display and form in templates.
	$options = get_option( 'wpmtst_options' );
	$out['container_class'] = 'strong-view-id-' . $out['view'];

	if ( $out['class'] ) {
		$out['container_class'] .= ' ' . str_replace( ',', ' ', $out['class'] );
	}
	if ( is_rtl() ) {
		$out['container_class'] .= ' rtl';
	}
	WPMST()->set_atts( $out );

	/**
	 * MODE: FORM
	 */
	if ( $out['form'] )
		return wpmtst_form_view( $out );

	/**
	 * MODE: DISPLAY (default)
	 */
	return wpmtst_display_view( $out );
}

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

	$categories = apply_filters( 'wpmtst_l10n_cats', explode( ',', $category ) );
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
	 * Add new values to shortcode atts
	 */
	if ( 'custom' == $thumbnail_size ) {
		$atts['thumbnail_size'] = array( $thumbnail_width, $thumbnail_height );
	}
	$atts['content_class'] = $content_class_list;
	$atts['post_class']    = $post_class_list;
	WPMST()->set_atts( $atts );

	/**
	 * Add filters here.
	 */
	add_filter( 'get_avatar', 'wpmtst_get_avatar', 10, 3 );

	/**
	 * Load template
	 */
	$template_file = $strong_templates->get_template_attr( $atts, 'template' );
	ob_start();
	/** @noinspection PhpIncludeInspection */
	include( $template_file );
	$html = ob_get_contents();
	ob_end_clean();

	/**
	 * Remove filters here.
	 */
	remove_filter( 'get_avatar', 'wpmtst_get_avatar' );

	do_action( 'wpmtst_view_rendered', $atts );

	wp_reset_postdata();
	$html = apply_filters( 'strong_view_html', $html );

	return $html;
}

/**
 * The form.
 *
 * @param $atts
 *
 * @return mixed|string|void
 */
function wpmtst_form_view( $atts ) {
	global $strong_templates;

	if ( isset( $_GET['success'] ) ) {
		return '<div class="testimonial-success">' . wpmtst_get_form_message( 'submission-success' ) . '</div>';
	}

	extract( normalize_empty_atts( $atts ) );

	$fields = wpmtst_get_form_fields( $form_id );

	$form_values = array( 'category' => $category );
	foreach ( $fields as $key => $field ) {
		$form_values[ $field['name'] ] = '';
	}
	$previous_values = WPMST()->get_form_values();
	if ( $previous_values ) {
		$form_values = array_merge( $form_values, $previous_values );
	}
	WPMST()->set_form_values( $form_values );

	/**
	 * Add filters here.
	 */

	/**
	 * Load template
	 */
	$template_file = $strong_templates->get_template_attr( $atts, 'template' );
	ob_start();
	/** @noinspection PhpIncludeInspection */
	include $template_file;
	$html = ob_get_contents();
	ob_end_clean();

	/**
	 * Remove filters here.
	 */

	do_action( 'wpmtst_form_rendered', $atts );

	$html = apply_filters( 'strong_view_html', $html );

	return $html;
}

/**
 * Normalize empty shortcode attributes.
 *
 * Turns atts into tags - brilliant!
 * Thanks http://wordpress.stackexchange.com/a/123073/32076
 */
if ( ! function_exists( 'normalize_empty_atts' ) ) {
	function normalize_empty_atts( $atts ) {
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
			return $atts;
		}
	}
}

/**
 * Honeypot preprocessor
 */
function wpmtst_honeypot_before() {
	if ( isset( $_POST['wpmtst_if_visitor'] ) && ! empty( $_POST['wpmtst_if_visitor'] ) ) {
		do_action( 'honeypot_before_spam_testimonial', $_POST );
		$form_options = get_option( 'wpmtst_form_options' );
		$messages     = $form_options['messages'];
		die( apply_filters( 'wpmtst_l10n', $messages['submission-error']['text'], 'strong-testimonials-form-messages', $messages['submission-error']['description'] ) );
	}
	return;
}

/**
 * Honeypot preprocessor
 */
function wpmtst_honeypot_after() {
	if ( ! isset ( $_POST['wpmtst_after'] ) ) {
		do_action( 'honeypot_after_spam_testimonial', $_POST );
		$form_options = get_option( 'wpmtst_form_options' );
		$messages     = $form_options['messages'];
		die( apply_filters( 'wpmtst_l10n', $messages['submission-error']['text'], 'strong-testimonials-form-messages', $messages['submission-error']['description'] ) );
	}
	return;
}

/**
 * Honeypot
 */
function wpmtst_honeypot_before_script() {
	?>
	<script type="text/javascript">jQuery('#wpmtst_if_visitor').val('');</script>
	<?php
}

/**
 * Honeypot
 */
function wpmtst_honeypot_after_script() {
	?>
	<script type='text/javascript'>
		//<![CDATA[
		( function( $ ) {
			'use strict';
			var forms = "#wpmtst-submission-form";
			$( forms ).submit( function() {
				$( "<input>" ).attr( "type", "hidden" )
					.attr( "name", "wpmtst_after" )
					.attr( "value", "1" )
					.appendTo( forms );
				return true;
			});
		})( jQuery );
		//]]>
	</script>
	<?php
}
