<?php

/**
 * Validate a View's name.
 *
 * @since 2.11.14
 *
 * @param $name
 * @param $view_id
 *
 * @return string
 */
function wpmtst_validate_view_name( $name, $view_id ){
	if ( '' == $name ) {
		$name = "Testimonial View $view_id";
	}
	else {
		$name = sanitize_text_field( stripslashes( $name ) );
	}
	return $name;
}


/**
 * Sanitize and validate a View.
 * TODO break down into separate validators
 *
 * @since 1.21.0
 * @since 2.5.7 Strip CSS from CSS Class Names field.
 * @since 2.10.0 Provide both more_post and more_page.
 * @since 2.11.0 More slideshow options: effect, slideshow_nav, stretch
 * @since 2.11.4 more_full_post for manual excerpts
 * @since 2.11.5 more_page_hook
 *
 * @param $input
 *
 * @return array
 */
function wpmtst_sanitize_view( $input ) {
	ksort( $input );

	$default_view = apply_filters( 'wpmtst_view_default', get_option( 'wpmtst_view_default' ) );

	$data         = array();
	$data['mode'] = sanitize_text_field( $input['mode'] );

	// Compatibility
	$data['compat'] = ( 'compat_on' == $input['compat'] ? 1 : 0 );

	/**
	 * Single testimonial
	 */
	// Clear single ID if "multiple" selected
	if ( 'multiple' == $input['select'] ) {
		$data['id'] = 0;  // must be zero not empty or false
	}
	else {
		// Check the "ID or slug" field first
		if ( ! $input['post_id'] ) {
			$data['id'] = (int) sanitize_text_field( $input['id'] );
		}
		else {
			// is post ID?
			$id = (int) $input['post_id'];
			if ( $id ) {
				if ( ! get_posts( array( 'p' => $id, 'post_type' => 'wpm-testimonial', 'post_status' => 'publish' ) ) ) {
					$id = null;
				}
			}
			else {
				// is post slug?
				$target = get_posts( array(
					'name'        => $input['post_id'],
					'post_type'   => 'wpm-testimonial',
					'post_status' => 'publish'
				) );
				if ( $target ) {
					$id = $target[0]->ID;
				}
			}

			$data['id']      = $id;
			$data['post_id'] = '';
		}
	}

	$data['form_ajax'] = isset( $input['form_ajax'] ) ? 1 : 0;

	// Template
	if ( 'form' == $data['mode'] )
		$data['template'] = isset( $input['form-template'] ) ? sanitize_text_field( $input['form-template'] ) : '';
	else
		$data['template'] = isset( $input['template'] ) ? sanitize_text_field( $input['template'] ) : '';

	// Category
	if ( 'form' == $data['mode'] ) {

		if ( isset( $input['category-form'] ) ) {
			$data['category'] = sanitize_text_field( implode( ',', $input['category-form'] ) );
		}
		else {
			$data['category'] = '';
		}

	}
	else {

		if ( 'allcats' == $input['category_all'] ) {
			$data['category'] = 'all';
		}
		elseif ( ! isset( $input['category'] ) ) {
			$data['category'] = 'all';
		}
		elseif ( 'somecats' == $input['category_all'] && ! isset( $input['category'] ) ) {
			$data['category'] = 'all';
		}
		else {
			$data['category'] = sanitize_text_field( implode( ',', $input['category'] ) );
		}

	}

	$data['order'] = sanitize_text_field( $input['order'] );

	$data['all']   = sanitize_text_field( $input['all'] );
	$data['count'] = (int) sanitize_text_field( $input['count'] );

	$data['pagination'] = isset( $input['pagination'] ) ? 1 : 0;
	$data['per_page']   = (int) sanitize_text_field( $input['per_page'] );
	$data['nav']        = str_replace( ' ', '', sanitize_text_field( $input['nav'] ) );

	$data['title']              = isset( $input['title'] ) ? 1 : 0;
	$data['content']            = sanitize_text_field( $input['content'] );
	$data['word_count']         = isset( $input['word_count'] ) ? (int) sanitize_text_field( $input['word_count'] ) : 0;
	$data['excerpt_length']     = (int) sanitize_text_field( $input['excerpt_length'] );
	$data['use_default_length'] = sanitize_text_field( $input['use_default_length'] );

	// Read more --> post
	$data['more_full_post']          = $input['more_full_post'];
	if ( 'truncated' == $data['content'] || 'excerpt' == $data['content'] ) {
		$data['more_post'] = 1;
	} else {
		$data['more_post'] = 0;
	}
	$data['more_post_ellipsis']      = isset( $input['more_post_ellipsis'] ) ? 1 : 0;
	$data['more_post_text']          = sanitize_text_field( $input['more_post_text'] );
	$data['use_default_more']        = $input['use_default_more'];

	// Read more --> page
	if ( isset( $input['more_page'] ) && $input['more_page'] ) {

		// Check the "ID or slug" field first
		if ( $input['more_page_id2'] ) {

			// is post ID?
			$id = sanitize_text_field( $input['more_page_id2'] );
			if ( is_numeric( $id ) ) {
				if ( ! get_posts( array( 'p' => $id, 'post_type' => array( 'page', 'post' ), 'post_status' => 'publish' ) ) ) {
					$id = null;
				}
			} else {
				// is post slug?
				$target = get_posts( array( 'name' => $id, 'post_type' => array( 'page', 'post' ), 'post_status' => 'publish' ) );
				if ( $target ) {
					$id = $target[0]->ID;
				}
			}

			if ( $id ) {
				$data['more_page_id'] = $id;
				unset( $data['more_page_id2'] );
			}

		} else {

			if ( $input['more_page_id'] ) {
				$data['more_page_id'] = (int) sanitize_text_field( $input['more_page_id'] );
			}

		}

		// Only enable more_page if a page was selected by either method.
		if ( isset( $data['more_page_id'] ) && $data['more_page_id'] ) {
			$data['more_page'] = 1;
		}
	}
	if ( ! $input['more_page_text'] ) {
		$data['more_page_text'] = $default_view['more_page_text'];
	} else {
		$data['more_page_text'] = sanitize_text_field( $input['more_page_text'] );
	}
	$data['more_page_hook'] = sanitize_text_field( $input['more_page_hook'] );

	// Thumbnail
	$data['thumbnail']        = isset( $input['thumbnail'] ) ? 1 : 0;
	$data['thumbnail_size']   = sanitize_text_field( $input['thumbnail_size'] );
	$data['thumbnail_width']  = sanitize_text_field( $input['thumbnail_width'] );
	$data['thumbnail_height'] = sanitize_text_field( $input['thumbnail_height'] );
	$data['lightbox']         = isset( $input['lightbox'] ) ? 1 : 0;
	$data['gravatar']         = sanitize_text_field( $input['gravatar'] );

	/**
	 * CSS Class Names
	 * This field is being confused with custom CSS rules like `.testimonial { border: none; }`
	 * so strip periods and anything between and including curly braces.
	 */
	$data['class'] = sanitize_text_field( trim( preg_replace( '/\{.*?\}|\./', '', $input['class'] ) ) );

	// Background
	$data['background'] = WPMST()->get_background_defaults();
	if ( !isset( $input['background']['type'] ) || 'none' == $input['background']['type'] ) {
		$data['background']['type'] = '';
	}
	else {
		$data['background']['type'] = sanitize_text_field( $input['background']['type'] );
	}
	$data['background']['color']     = sanitize_text_field( $input['background']['color'] );
	$data['background']['gradient1'] = sanitize_text_field( $input['background']['gradient1'] );
	$data['background']['gradient2'] = sanitize_text_field( $input['background']['gradient2'] );
	$data['background']['preset']    = sanitize_text_field( $input['background']['preset'] );
	$data['background']['example-font-color'] = sanitize_text_field( $input['background']['example-font-color'] );

	// Layout input may have been disabled by selecting the widget template so no value is posted.
	if ( ! isset( $input['layout'] ) ) {
		$data['layout'] = '';
	}
	else {
		// pagination and Masonry are incompatible
		$data['layout'] = sanitize_text_field( $input['layout'] );
		if ( isset( $input['pagination'] ) && 'masonry' == $data['layout'] ) {
			$data['layout'] = '';
		}
	}

	$data['column_count'] = sanitize_text_field( $input['column_count'] );

	// Slideshow
	$data['show_for']          = floatval( sanitize_text_field( $input['show_for'] ) );
	$data['effect']            = sanitize_text_field( $input['effect'] );
	$data['effect_for']        = floatval( sanitize_text_field( $input['effect_for'] ) );
	$data['no_pause']          = isset( $input['no_pause'] ) ? 0 : 1;
	if ( 'none' == $input['slideshow_nav'] ) {
		$data['slideshow_nav'] = '';
	} else {
		$data['slideshow_nav'] = sanitize_text_field( $input['slideshow_nav'] );
	}
	$data['stretch']           = isset( $input['stretch'] ) ? 1 : 0;

	// Custom fields
	if ( isset( $input['client_section'] ) ) {
		foreach ( $input['client_section'] as $key => $field ) {
			if ( empty( $field['field'] ) ) {
				break;
			}

			$data['client_section'][ $key ]['field'] = sanitize_text_field( $field['field'] );
			$data['client_section'][ $key ]['type']  = sanitize_text_field( $field['type'] );
			$data['client_section'][ $key ]['class'] = sanitize_text_field( $field['class'] );

			switch ( $field['type'] ) {
				case 'link':
				case 'link2':
					/**
					 * If no URL, change field type to 'text'. This happens when a URL field
					 * (e.g. company_name) is removed from Custom Fields.
					 * @since 2.10.0
					 */
					if ( ! isset( $field['url'] ) ) {
						$data['client_section'][ $key ]['type'] = 'text';
						unset( $data['client_section'][ $key ]['link_text'] );
						unset( $data['client_section'][ $key ]['link_text_custom'] );
						unset( $data['client_section'][ $key ]['new_tab'] );
					}
					else {
						$data['client_section'][ $key ]['url'] = sanitize_text_field( $field['url'] );

						$data['client_section'][ $key ]['link_text'] = isset( $field['link_text'] ) ? sanitize_text_field( $field['link_text'] ) : '';

						$data['client_section'][ $key ]['link_text_custom'] = isset( $field['link_text_custom'] ) ? sanitize_text_field( $field['link_text_custom'] ) : '';

						$data['client_section'][ $key ]['new_tab'] = isset( $field['new_tab'] ) ? 1 : 0;
					}
					break;
				case 'date':
					$format = isset( $field['format'] ) ? sanitize_text_field( $field['format'] ) : '';
					$data['client_section'][ $key ]['format'] = $format;
					break;
				default:
			}

		}
	}
	else {
		$data['client_section'] = null;
	}

	// Multiple Forms add-on
	if ( isset( $input['form_id'] ) ) {
		$data['form_id'] = $input['form_id'];
	}
	else {
		$data['form_id'] = $input['_form_id'];
	}

	$data = apply_filters( 'wpmtst_sanitized_view', $data, $input );
	ksort( $data );

	return $data;
}
