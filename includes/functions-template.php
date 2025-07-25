<?php
/**
 * Template Functions
 */

/**
 * Template function for showing a View.
 *
 * @since 1.25.0
 *
 * @param null $id
 */
function strong_testimonials_view( $id = null ) {
	if ( ! $id ) {
		return;
	}

	$out   = array();
	$pairs = array();
	$atts  = array( 'id' => $id );
	$out   = WPMST()->render->prerender( $atts );
	$out   = WPMST()->render->parse_view( $out, $pairs, $atts );

	echo WPMST()->shortcode->render_view( $out );
}

/**
 * Print the current post title with optional markup.
 *
 * @since 2.26.0 Add optional link to post.
 *
 * @param string $before
 * @param string $after
 */

function wpmtst_the_title( $tag = '', $wrapper_class = '' ) {
	$title   = get_the_title();
	$options = get_option( 'wpmtst_options' );

	$tag = apply_filters( 'wpmtst_the_title_tag', $tag );
	if ( ! empty( $tag ) ) {
		$before = '<' . $tag . ' class="' . $wrapper_class . '">';
		$after  = '</' . $tag . '>';
	}
	if ( WPMST()->atts( 'title' ) && $title ) {
		if ( 'none' !== WPMST()->atts( 'title_link' ) && '0' !== WPMST()->atts( 'title_link' ) ) {
			if ( ( ! isset( $options['disable_rewrite'] ) || false === $options['disable_rewrite'] ) && ( 'wpmtst_testimonial' === WPMST()->atts( 'title_link' ) || '1' === WPMST()->atts( 'title_link' ) ) ) {
				$before .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
				$after   = '</a>' . $after;
			} else {
				$id           = get_the_ID();
				$url_field    = WPMST()->atts( 'title_link' );
				$external_url = get_post_meta( $id, $url_field, true );

				if ( '' !== $external_url && ! is_array( $external_url ) ) {
					$before .= '<a href="' . esc_url( $external_url ) . '" rel="bookmark" target="_blank">';
					$after   = '</a>' . $after;
				}
			}
		}
	}
	$before = apply_filters( 'wpmtst_the_title_before', $before );
	$after  = apply_filters( 'wpmtst_the_title_after', $after );

	if ( WPMST()->atts( 'title' ) !== 'hidden' ) {
		the_title( $before, $after );
	}
}

/**
 * Display the testimonial content.
 *
 * @since 1.24.0
 *
 * @since 2.4.0  Run content through core WordPress filters only, instead of all filters
 *               added to the_excerpt() or the_content() in order to to be compatible with
 *               NextGEN Gallery and to prevent other plugins from unconditionally adding
 *               content like share buttons, etc.
 *
 * @since 2.11.5 Run specific filters on `wpmtst_the_content` hook.
 *
 * @since 2.20.0 For automatic excerpts, run `wpautop` after truncating.
 *               Add `wp_make_content_images_responsive`.
 *
 * @since 2.26.0 Using content filters instead of direct function calls.
 *               Using custom get_*() functions to allow filter selectivity.
 */
function wpmtst_the_content() {
	/**
	 * Use this hook to remove specific content filters.
	 *
	 * @since 2.26.0
	 */
	do_action( 'wpmtst_before_content_filters' );

	echo apply_filters( 'wpmtst_get_the_content', '' );

	/**
	 * Restore content filters that were removed.
	 *
	 * @since 2.26.0
	 */
	do_action( 'wpmtst_after_content_filters' );
}

/**
 * Like the_excerpt().
 *
 * @since 2.33.0
 */
function wpmtst_the_excerpt() {
	echo wpmtst_the_excerpt_filtered();
}

/**
 * The ellipsis on read-more's.
 *
 * @since 2.33.0
 */
function wpmtst_ellipsis() {
	if ( apply_filters( 'wpmtst_use_ellipsis', true ) ) {
		return apply_filters( 'wpmtst_ellipsis', __( '&hellip;', 'strong-testimonials' ) );
	}

	return '';
}

function wpmtst_prepend_ellipsis( $more ) {
	return wpmtst_ellipsis() . ' ' . $more;
}

/**
 * Assemble link to secondary "Read more" page.
 *
 * @since 2.10.0
 */
function wpmtst_read_more_page() {
	$atts = WPMST()->atts();

	if ( $atts['more_page'] && $atts['more_page_id'] ) {
		$permalink = '';
		if ( is_numeric( $atts['more_page_id'] ) ) {
			$permalink = wpmtst_get_permalink( $atts['more_page_id'] );
		}
		$permalink = apply_filters( 'wpmtst_readmore_page_link', $permalink, $atts );

		if ( $permalink ) {
			$default_view = wpmtst_get_view_default();

			if ( isset( $atts['more_page_text'] ) && $atts['more_page_text'] ) {
				$link_text = $atts['more_page_text'];
			} else {
				$link_text = $default_view['more_page_text'];
			}

			$link_text = apply_filters( 'wpmtst_read_more_page_link_text', $link_text, $atts );

			if ( 'wpmtst_after_testimonial' === $atts['more_page_hook'] ) {
				$classname = 'readmore';
			} else {
				$classname = 'readmore-page';
			}
			$classname = apply_filters( 'wpmtst_read_more_page_class', $classname );
			echo apply_filters( 'wpmtst_read_more_page_output', sprintf( '<div class="%s"><a href="%s">%s</a></div>', esc_attr( $classname ), esc_url( $permalink ), wp_kses_post( $link_text ) ) );
		}
	}
}

/**
 * L10n filter on read-more-page link.
 *
 * @since 2.23.0 As separate function.
 *
 * @param $text
 * @param $atts
 *
 * @return string
 */
function wpmtst_read_more_page_link_text_l10n( $text, $atts ) {
	return apply_filters(
		'wpmtst_l10n',
		$text,
		'strong-testimonials-read-more',
		sprintf( 'View %s : Read more (page or post)', $atts['view'] )
	);
}
add_filter( 'wpmtst_read_more_page_link_text', 'wpmtst_read_more_page_link_text_l10n', 10, 2 );

/**
 * L10n filter on read-more-post link.
 *
 * @since 2.29.0
 *
 * @param $text
 * @param $atts
 *
 * @return string
 */
function wpmtst_read_more_post_link_text_l10n( $text, $atts ) {
	return apply_filters(
		'wpmtst_l10n',
		$text,
		'strong-testimonials-read-more',
		sprintf( 'View %s : Read more (testimonial)', $atts['view'] )
	);
}
add_filter( 'wpmtst_read_more_post_link_text', 'wpmtst_read_more_post_link_text_l10n', 10, 2 );

/**
 * Get permalink by ID or slug.
 *
 * @since 1.25.0
 *
 * @param $page_id
 *
 * @return false|string
 */
function wpmtst_get_permalink( $page_id ) {
	if ( ! is_numeric( $page_id ) ) {
		$page    = get_page_by_path( $page_id );
		$page_id = $page->ID;
	}

	return get_permalink( $page_id );
}

/**
 * Prevent page scroll when clicking the More link.
 *
 * @since 2.10.0
 *
 * @param $link
 *
 * @return mixed
 */
function wpmtst_remove_more_link_scroll( $link ) {
	if ( 'wpm-testimonial' === get_post_type() ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
	}

	return $link;
}
add_filter( 'the_content_more_link', 'wpmtst_remove_more_link_scroll' );

/**
 * Display the thumbnail.
 *
 * TODO WP 4.2+ has better filters.
 *
 * @param null   $size
 * @param string $before
 * @param string $after
 */
function wpmtst_the_thumbnail( $size = null, $before = '<div class="wpmtst-testimonial-image testimonial-image">', $after = '</div>' ) {
	if ( ! WPMST()->atts( 'thumbnail' ) ) {
		return;
	}

	$img = wpmtst_get_thumbnail( $size );
	if ( $img ) {
		echo $before . $img . $after;
	}
}

/**
 * Print the date.
 *
 * @param string $format
 * @param string $wrapper_class
 */
function wpmtst_the_date( $format = '', $wrapper_class = '' ) {
	global $post;
	if ( ! $post ) {
		return;
	}

	if ( ! $format ) {
		$format = get_option( 'date_format' );
	}

	$the_date = apply_filters( 'wpmtst_the_date', mysql2date( $format, $post->post_date ), $format, $post );
	echo '<div class="' . esc_attr( $wrapper_class ) . '">' . esc_attr( $the_date ) . '</div>';
}

/**
 * Display the client section.
 *
 * @since 1.21.0
 */
function wpmtst_the_client() {
	$atts = WPMST()->atts();
	if ( isset( $atts['client_section'] ) ) {
		echo wpmtst_client_section( $atts['client_section'] );
	}
}

/**
 * Assemble the client section.
 *
 * @since 1.21.0
 *
 * @param array $client_section An array of client fields.
 *
 * @return mixed
 */
function wpmtst_client_section( $client_section ) {

	$html = '';

	foreach ( $client_section as $field ) {
		$html .= wpmtst_the_custom_field( $field );
	}

	return $html;
}

function wpmtst_the_custom_field( $field ) {
	global $post;

	$options       = get_option( 'wpmtst_options' );
	$custom_fields = wpmtst_get_custom_fields();

	$output     = '';
	$field_name = $field['field'];

	if ( isset( $custom_fields[ $field_name ] ) ) {
		$field['prop'] = $custom_fields[ $field_name ];
	} else {
		$field['prop'] = array();
	}

	// Check for callback first.
	if ( isset( $field['prop']['action_output'] ) && $field['prop']['action_output'] ) {
		$value  = get_post_meta( $post->ID, $field_name, true );
		$output = apply_filters( $field['prop']['action_output'], $field, $value );
	} else {
		switch ( $field['type'] ) {
			case 'link':
			case 'link2':
				// use default if missing
				if ( ! isset( $field['link_text'] ) ) {
					$field['link_text'] = 'value';
				}

				/**
				 * Get link text and an alternate in case the URL is empty;
				 * e.g. display the domain if no company name given
				 * but don't display 'LinkedIn' if no URL given.
				 */
				switch ( $field['link_text'] ) {
					case 'custom':
						$text   = $field['link_text_custom'];
						$output = '';
						break;
					case 'label':
						$text   = $field['prop']['label'];
						$output = '';
						break;
					default: // value
						$text = get_post_meta( $post->ID, $field_name, true );
						// if no URL (next condition), show the alternate (the field)
						$output = $text;
				}

				if ( $field['url'] ) {
					$url = get_post_meta( $post->ID, $field['url'], true );
					if ( $url ) {
						if ( isset( $field['new_tab'] ) && $field['new_tab'] ) {
							$newtab = ' target="_blank"';
						} else {
							$newtab = '';
						}

						// TODO Abstract this global fallback technique.
						$is_nofollow = get_post_meta( $post->ID, 'nofollow', true );
						if ( 'default' === $is_nofollow || '' === $is_nofollow ) {
							// convert default to (yes|no)
							$is_nofollow = $options['nofollow'] ? 'yes' : 'no';
						}
						if ( 'yes' === $is_nofollow ) {
							$nofollow = 'nofollow';
						} else {
							$nofollow = '';
						}

						$is_noopener = get_post_meta( $post->ID, 'noopener', true );
						if ( 'default' === $is_noopener || '' === $is_noopener ) {
							// convert default to (yes|no)
							$is_noopener = $options['noopener'] ? 'yes' : 'no';
						}
						if ( 'yes' === $is_noopener ) {
							$noopener = 'noopener';
						} else {
							$noopener = '';
						}

						$is_noreferrer = get_post_meta( $post->ID, 'noreferrer', true );
						if ( 'default' === $is_noreferrer || '' === $is_noreferrer ) {
							// convert default to (yes|no)
							$is_noreferrer = $options['noreferrer'] ? 'yes' : 'no';
						}
						if ( 'yes' === $is_noreferrer ) {
							$noreferrer = 'noreferrer';
						} else {
							$noreferrer = '';
						}

						if ( ! empty( $noopener ) || ! empty( $nofollow ) || ! empty( $noreferrer ) ) {
							$rel = sprintf( ' rel="%s %s %s"', esc_attr( $nofollow ), esc_attr( $noopener ), esc_attr( $noreferrer ) );
						} else {
							$rel = '';
						}

						// if field empty, use domain instead
						if ( ! $text || is_array( $text ) ) {
							$text = preg_replace( '(^https?://)', '', $url );
						}
						$output = sprintf( '<a href="%s"%s%s>%s</a>', esc_url( $url ), $newtab, $rel, wp_kses_post( $text ) );
					}
				}
				break;

			case 'date':
				$format = isset( $field['format'] ) && $field['format'] ? $field['format'] : get_option( 'date_format' );

				// Fall back to post_date if submit_date missing.
				$the_date = get_post_meta( $post->ID, $field_name, true );
				$the_date = $the_date ? $the_date : $post->post_date;
				$the_date = mysql2date( $format, $the_date );

				if ( get_option( 'date_format' ) !== $format ) {
					// Requires PHP 5.3+
					if ( version_compare( PHP_VERSION, '5.3' ) >= 0 ) {
						$new_date = DateTime::createFromFormat( get_option( 'date_format' ), $the_date );
						if ( $new_date ) {
							$the_date = $new_date->format( $format );
						}
					}
				}

				$output = apply_filters( 'wpmtst_the_date', $the_date, $format, $post );
				break;

			case 'category':
				// 1. Get all terms for testimonial
				$categories = get_the_terms( $post->ID, 'wpm-testimonial-category' );
				// 2, Check for errors
				if ( $categories && ! is_wp_error( $categories ) ) {
					$list = array();
					// 3. Check if should display parent cats, child cats, or both.
					if ( isset( $field['category_show'] ) && 'both' !== $field['category_show'] ) {
						if ( 'parent' === $field['category_show'] ) {
							foreach ( $categories as $cat ) {
								// 3.1 Include only categories that don't have parents
								if ( 0 === $cat->parent ) {
									$list[] = $cat->name;
								}
							}
							$output = implode( ', ', $list );
						} elseif ( 'child' === $field['category_show'] ) {
							foreach ( $categories as $cat ) {
								// 3.2 Include only categories that have parents
								if ( 0 !== $cat->parent ) {
									$list[] = $cat->name;
								}
							}
							$output = implode( ', ', $list );
						}
					} else {
						foreach ( $categories as $cat ) {
							// 3.3 Include all categories
							$list[] = $cat->name;
						}
						$output = implode( ', ', $list );
					}
				} else {
					$output = '';
				}
				break;

			case 'shortcode':
				if ( isset( $field['prop']['shortcode_on_display'] ) && $field['prop']['shortcode_on_display'] ) {
					$output = do_shortcode( $field['prop']['shortcode_on_display'] );
				}
				break;

			case 'rating':
				$output = get_post_meta( $post->ID, $field_name, true );
				// Check default value
				if ( '' === $output && isset( $field['prop']['default_display_value'] ) && $field['prop']['default_display_value'] ) {
					$output = $field['prop']['default_display_value'];
				}
				// Convert number to stars
				if ( $output ) {
					$output = wpmtst_star_rating_display( $output, 'in-view', false );
				}
				break;

			case 'platform':
				$platform = get_post_meta( $post->ID, $field_name, true );

				if ( $platform ) {
					$output = wpmtst_platform_display( $platform );
				}

				break;

			case 'checkbox':
					// we output the checkbox value from view
					$output = '';
				if ( isset( $field['custom_label'] ) && ! empty( $field['custom_label'] ) ) {
							$output = sprintf( '%s: ', esc_attr( $field['custom_label'] ) );
				}
				if ( get_post_meta( $post->ID, $field_name, true ) ) {
								$output .= esc_attr( $field['checked_value_custom'] );
				} else {
					$output .= esc_attr( $field['unchecked_value'] );
				}
				break;

			default:
				// text field
				$output = get_post_meta( $post->ID, $field_name, true );
				$output = wp_kses_post( $output );
				if ( '' === $output && isset( $field['prop']['default_display_value'] ) && $field['prop']['default_display_value'] ) {
					$output = $field['prop']['default_display_value'];
				}
		}
	}

	if ( is_array( $output ) ) {
		return '';
	}

	if ( $output ) {
		if ( isset( $field['before'] ) && $field['before'] ) {
			$output = '<span class="wpmtst-testimonial-field-before testimonial-field-before">' . $field['before'] . '</span>' . $output;
		}
		$output = '<div class="wpmtst-testimonial-field testimonial-field ' . esc_attr( $field['class'] ) . '">' . $output . '</div>';
	}

	return $output;
}

function wpmtst_container_class() {
	echo esc_attr( apply_filters( 'wpmtst_container_class', WPMST()->atts( 'container_class' ) ) );
}

function wpmtst_container_data() {
	$data_array = apply_filters( 'wpmtst_container_data', WPMST()->atts( 'container_data' ) );
	if ( $data_array ) {
		$data = '';
		foreach ( $data_array as $attr => $value ) {
			$data .= " data-$attr=$value";
		}
		echo esc_attr( $data );
	}
}

function wpmtst_content_class() {
	echo esc_attr( apply_filters( 'wpmtst_content_class', WPMST()->atts( 'content_class' ) ) );
}

function wpmtst_post_class( $args = null ) {
	echo esc_attr( apply_filters( 'wpmtst_post_class', WPMST()->atts( 'post_class' ) . ' post-' . get_the_ID(), $args ) );
}

/**
 * Echo custom field.
 *
 * @since 1.11.0
 *
 * @param null  $field
 * @param array $args
 */
function wpmtst_field( $field = null, $args = array() ) {
	echo wpmtst_get_field( $field, $args );
}

/**
 * Fetch custom field.
 *
 * Thanks to Matthew Harris.
 *
 * @link  https://github.com/cdillon/strong-testimonials/issues/2
 *
 * @param       $field
 * @param array $args
 *
 * @since 1.15.7
 *
 * @return mixed|string
 */
function wpmtst_get_field( $field, $args = array() ) {
	if ( ! $field ) {
		return '';
	}

	global $post;

	switch ( $field ) {

		// Apply a character limit to post content.
		case 'truncated':
			$html = wpmtst_truncate( $post->post_content, $args['char_limit'] );
			break;

		// Get the custom field.
		default:
			$html = get_post_meta( $post->ID, $field, true );
	}

	return $html;
}

if ( ! function_exists( 'wpmtst_standard_pagination' ) ) :
	/**
	 * Custom pagination. Pluggable.
	 *
	 * Thanks http://callmenick.com/post/custom-wordpress-loop-with-pagination
	 */
	function wpmtst_standard_pagination() {
		$query    = WPMST()->get_query();
		$numpages = $query->max_num_pages ? $query->max_num_pages : 1;
		$paged    = wpmtst_get_paged();
		$options  = WPMST()->atts( 'pagination_settings' );

		$pagination_args = array(
			// Required
			'total'              => $numpages,
			'current'            => $paged,
			// Options
			'show_all'           => isset( $options['show_all'] ) ? $options['show_all'] : false,
			'end_size'           => isset( $options['end_size'] ) ? $options['end_size'] : 1,
			'mid_size'           => isset( $options['mid_size'] ) ? $options['mid_size'] : 2,
			'prev_next'          => isset( $options['prev_next'] ) ? $options['prev_next'] : true,
			'prev_text'          => isset( $options['prev_text'] ) ? $options['prev_text'] : __( '&laquo; Previous', 'strong-testimonials' ),
			'next_text'          => isset( $options['next_text'] ) ? $options['next_text'] : __( 'Next &raquo;', 'strong-testimonials' ),
			'before_page_number' => isset( $options['before_page_number'] ) ? $options['before_page_number'] : '',
			'after_page_number'  => isset( $options['after_page_number'] ) ? $options['after_page_number'] : '',
		);

		$paginate_links = paginate_links( apply_filters( 'wpmtst_pagination_args', $pagination_args ) );

		if ( $paginate_links ) {
			echo "<nav class='nav-links'>";
			echo $paginate_links;
			echo '</nav>';
		}
	}
endif;

/**
 * If paged, return the current page number.
 *
 * @return int|mixed
 */
function wpmtst_get_paged() {
	if ( is_front_page() ) {
		return get_query_var( 'page' ) ? intval( get_query_var( 'page' ) ) : 1;
	}
	return get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
}

/*
// alternate pagination style
function wpmtst_new_pagination_2() {
	$query = WPMST()->get_query();
	if ($query->max_num_pages > 1) { ?>
		<nav class="prev-next-posts">
			<div class="prev-posts-link">
				<?php echo get_next_posts_link( 'Older Entries', $query->max_num_pages ); // display older posts link ?>
			</div>
			<div class="next-posts-link">
				<?php echo get_previous_posts_link( 'Newer Entries' ); // display newer posts link ?>
			</div>
		</nav>
	<?php }
}
*/

if ( ! function_exists( 'wpmtst_single_template_client' ) ) :
	/**
	 * Single testimonial custom fields. Pluggable.
	 *
	 * @since 2.22.0
	 */
	function wpmtst_single_template_client() {
		$html = '';
		$view = wpmtst_find_single_template_view();
		if ( $view && isset( $view['client_section'] ) ) {
			foreach ( $view['client_section'] as $field ) {
				if ( 'rating' === $field['type'] ) {
					wp_enqueue_style( 'wpmtst-rating-display' );
					break;
				}
			}

			$html .= '<div class="testimonial-client normal">';
			$html .= wpmtst_client_section( $view['client_section'] );
			$html .= '</div>';
		}

		return $html;
	}
endif;

function wpmtst_platform_display( $platform ) {
	ob_start();
	?>
		<img title="<?php echo esc_attr( __( 'posted on ', 'strong-testimonials' ) . $platform ); ?>" width="20" height="20" src="<?php echo esc_attr( WPMTST_ASSETS_IMG ); ?>/platform_icons/<?php echo esc_attr( $platform ); ?>.svg"/>
	<?php
	return ob_get_clean();
}
