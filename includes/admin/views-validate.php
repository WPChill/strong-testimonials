<?php
/**
 * Sanitize and validate a View.
 * TODO break down into separate validators
 *
 * @since 1.21.0
 * @since 2.5.7 Strip CSS from CSS Class Names field.
 * @since 2.10.0 Provide both more_post and more_page.
 * @since 2.11.0 More slideshow options: effect, slideshow_nav, stretch
 *
 * @param $input
 *
 * @return array
 */
function wpmtst_sanitize_view( $input ) {
	ksort( $input );

	$default_view = apply_filters( 'wpmtst_view_default', get_option( 'wpmtst_view_default' ) );

	$view_data         = array();
	$view_data['mode'] = sanitize_text_field( $input['mode'] );

	// Compatibility
	$view_data['compat'] = ( 'compat_on' == $input['compat'] ? 1 : 0 );

	// Read more --> post
	$view_data['more_post']          = isset( $input['more_post'] ) ? 1 : 0;
	$view_data['more_post_ellipsis'] = isset( $input['more_post_ellipsis'] ) ? 1 : 0;
	$view_data['more_post_text']     = sanitize_text_field( $input['more_post_text'] );
	$view_data['use_default_more']   = $input['use_default_more'];

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
				$view_data['more_page_id'] = $id;
				unset( $view_data['more_page_id2'] );
			}

		} else {

			if ( $input['more_page_id'] ) {
				$view_data['more_page_id'] = (int) sanitize_text_field( $input['more_page_id'] );
			}

		}

		// Only enable more_page if a page was selected by either method.
		if ( isset( $view_data['more_page_id'] ) && $view_data['more_page_id'] ) {
			$view_data['more_page'] = 1;
		}
	}
	if ( !$input['more_page_text'] ) {
		$view_data['more_page_text'] = $default_view['more_page_text'];
	} else {
		$view_data['more_page_text'] = sanitize_text_field( $input['more_page_text'] );
	}

	/**
	 * Single testimonial
	 */
	// Clear single ID if "multiple" selected
	if ( 'multiple' == $input['select'] ) {
		$view_data['id'] = 0;  // must be zero not empty or false
		//$view_data['post_id'] = '';
	}
	else {
		// Check the "ID or slug" field first
		if ( !$input['post_id'] ) {
			$view_data['id'] = (int) sanitize_text_field( $input['id'] );
		} else {
			// is post ID?
			$id = (int) $input['post_id'];
			if ( $id ) {
				if ( ! get_posts( array( 'p' => $id, 'post_type' => 'wpm-testimonial', 'post_status' => 'publish' ) ) ) {
					$id = null;
				}
			} else {
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

			$view_data['id']      = $id;
			$view_data['post_id'] = '';
		}
	}

	$view_data['form_ajax'] = isset( $input['form_ajax'] ) ? 1 : 0;

	// Template
	if ( 'form' == $view_data['mode'] )
		$view_data['template'] = isset( $input['form-template'] ) ? sanitize_text_field( $input['form-template'] ) : '';
	else
		$view_data['template']   = isset( $input['template'] ) ? sanitize_text_field( $input['template'] ) : '';

	// Category
	if ( 'form' == $view_data['mode'] ) {

		if ( isset( $input['category-form'] ) ) {
			$view_data['category'] = sanitize_text_field( implode( ',', $input['category-form'] ) );
		}
		else {
			$view_data['category'] = '';
		}

	}
	else {

		if ( 'allcats' == $input['category_all'] ) {
			$view_data['category'] = 'all';
		}
		elseif ( !isset( $input['category'] ) ) {
			$view_data['category'] = 'all';
		}
		elseif ( 'somecats' == $input['category_all'] && !isset( $input['category'] ) ) {
			$view_data['category'] = 'all';
		}
		else {
			$view_data['category'] = sanitize_text_field( implode( ',', $input['category'] ) );
		}

	}

	$view_data['order'] = sanitize_text_field( $input['order'] );

	$view_data['all']   = sanitize_text_field( $input['all'] );
	$view_data['count'] = (int) sanitize_text_field( $input['count'] );

	$view_data['pagination'] = isset( $input['pagination'] ) ? 1 : 0;
	$view_data['per_page']   = (int) sanitize_text_field( $input['per_page'] );
	$view_data['nav']        = str_replace( ' ', '', sanitize_text_field( $input['nav'] ) );

	$view_data['title']          = isset( $input['title'] ) ? 1 : 0;
	$view_data['content']        = sanitize_text_field( $input['content'] );
	$view_data['word_count']     = isset( $input['word_count'] ) ? (int) sanitize_text_field( $input['word_count'] ) : 0;
	$view_data['excerpt_length'] = (int) sanitize_text_field( $input['excerpt_length'] );
	$view_data['use_default_length'] = sanitize_text_field( $input['use_default_length'] );

	$view_data['thumbnail']        = isset( $input['thumbnail'] ) ? 1 : 0;
	$view_data['thumbnail_size']   = sanitize_text_field( $input['thumbnail_size'] );
	$view_data['thumbnail_width']  = sanitize_text_field( $input['thumbnail_width'] );
	$view_data['thumbnail_height'] = sanitize_text_field( $input['thumbnail_height'] );
	$view_data['lightbox']         = isset( $input['lightbox'] ) ? 1 : 0;
	$view_data['gravatar']         = sanitize_text_field( $input['gravatar'] );

	/**
	 * CSS Class Names
	 * This field is being confused with custom CSS rules like `.testimonial { border: none; }`
	 * so strip periods and anything between and including curly braces.
	 */
	$view_data['class'] = sanitize_text_field( trim( preg_replace( '/\{.*?\}|\./', '', $input['class'] ) ) );

	// Background
	$view_data['background'] = WPMST()->get_background_defaults();
	if ( !isset( $input['background']['type'] ) || 'none' == $input['background']['type'] ) {
		$view_data['background']['type'] = '';
	}
	else {
		$view_data['background']['type'] = sanitize_text_field( $input['background']['type'] );
	}
	$view_data['background']['color']     = sanitize_text_field( $input['background']['color'] );
	$view_data['background']['gradient1'] = sanitize_text_field( $input['background']['gradient1'] );
	$view_data['background']['gradient2'] = sanitize_text_field( $input['background']['gradient2'] );
	$view_data['background']['preset']    = sanitize_text_field( $input['background']['preset'] );
	$view_data['background']['example-font-color'] = sanitize_text_field( $input['background']['example-font-color'] );

	// Layout input may have been disabled by selecting the widget template so no value is posted.
	if ( ! isset( $input['layout'] ) ) {
		$view_data['layout'] = '';
	}
	else {
		// pagination and Masonry are incompatible
		$view_data['layout'] = sanitize_text_field( $input['layout'] );
		if ( isset( $input['pagination'] ) && 'masonry' == $view_data['layout'] ) {
			$view_data['layout'] = '';
		}
	}

	$view_data['column_count'] = sanitize_text_field( $input['column_count'] );

	// Slideshow
	$view_data['show_for']          = floatval( sanitize_text_field( $input['show_for'] ) );
	$view_data['effect']            = sanitize_text_field( $input['effect'] );
	$view_data['effect_for']        = floatval( sanitize_text_field( $input['effect_for'] ) );
	$view_data['no_pause']          = isset( $input['no_pause'] ) ? 0 : 1;
	if ( 'none' == $input['slideshow_nav'] ) {
		$view_data['slideshow_nav'] = '';
	} else {
		$view_data['slideshow_nav'] = sanitize_text_field( $input['slideshow_nav'] );
	}
	$view_data['stretch']           = isset( $input['stretch'] ) ? 1 : 0;

	// Custom fields
	if ( isset( $input['client_section'] ) ) {
		foreach ( $input['client_section'] as $key => $field ) {
			if ( empty( $field['field'] ) ) {
				break;
			}

			$view_data['client_section'][ $key ]['field'] = sanitize_text_field( $field['field'] );
			$view_data['client_section'][ $key ]['type']  = sanitize_text_field( $field['type'] );
			$view_data['client_section'][ $key ]['class'] = sanitize_text_field( $field['class'] );

			switch ( $field['type'] ) {
				case 'link':
				case 'link2':
					/**
					 * If no URL, change field type to 'text'. This happens when a URL field
					 * (e.g. company_name) is removed from Custom Fields.
					 * @since 2.10.0
					 */
					if ( ! isset( $field['url'] ) ) {
						$view_data['client_section'][ $key ]['type'] = 'text';
						unset( $view_data['client_section'][ $key ]['link_text'] );
						unset( $view_data['client_section'][ $key ]['link_text_custom'] );
						unset( $view_data['client_section'][ $key ]['new_tab'] );
					}
					else {
						$view_data['client_section'][ $key ]['url'] = sanitize_text_field( $field['url'] );

						$view_data['client_section'][ $key ]['link_text'] = isset( $field['link_text'] ) ? sanitize_text_field( $field['link_text'] ) : '';

						$view_data['client_section'][ $key ]['link_text_custom'] = isset( $field['link_text_custom'] ) ? sanitize_text_field( $field['link_text_custom'] ) : '';

						$view_data['client_section'][ $key ]['new_tab'] = isset( $field['new_tab'] ) ? 1 : 0;
					}
					break;
				case 'date':
					$format = isset( $field['format'] ) ? sanitize_text_field( $field['format'] ) : '';
					$view_data['client_section'][ $key ]['format'] = $format;
					break;
				default:
			}

		}
	}
	else {
		$view_data['client_section'] = null;
	}

	// Multiple Forms add-on
	if ( isset( $input['form_id'] ) ) {
		$view_data['form_id'] = $input['form_id'];
	}
	else {
		$view_data['form_id'] = $input['_form_id'];
	}

	$view_data = apply_filters( 'wpmtst_sanitized_view', $view_data, $input );
	ksort( $view_data );

	return $view_data;
}
