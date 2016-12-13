<?php
/**
 * Plugin activation and upgrade.
 *
 * @package Strong_Testimonials
 */

function wpmtst_upgrade() {

	$old_plugin_version = get_option( 'wpmtst_plugin_version' );
	$plugin_data        = WPMST()->get_plugin_data();
	$plugin_version     = $plugin_data['Version'];

	if ( $old_plugin_version == $plugin_version ) return;

	wpmtst_update_db_check();

	delete_option( 'wpmtst_cycle' );

	/**
	 * -1- DEFAULTS
	 */
	include_once WPMTST_INC . 'defaults.php';
	$default_options       = wpmtst_get_default_options();
	$default_fields        = wpmtst_get_default_fields();
	$default_base_forms    = wpmtst_get_default_base_forms();
	$default_custom_forms  = wpmtst_get_default_custom_forms();
	$default_form_options  = wpmtst_get_default_form_options();
	$default_view_options  = wpmtst_get_default_view_options();
	$default_view          = apply_filters( 'wpmtst_view_default', wpmtst_get_default_view() );
	$default_l10n_contexts = wpmtst_get_default_l10n_contexts();

	$history = wpmtst_get_update_history();

	/**
	 * -2- GET OPTIONS
	 */
	$options = get_option( 'wpmtst_options' );
	if ( ! $options ) {
		// -2A- NEW ACTIVATION
		update_option( 'wpmtst_options', $default_options );
	}
	else {
		// -2B- UPDATE

		// Remove version 1 options
		if ( version_compare( '2.0', $old_plugin_version ) ) {

			if ( isset( $options['captcha'] ) )
				unset( $options['captcha'] );

			if ( isset( $options['plugin_version'] ) )
				unset( $options['plugin_version'] );

			if ( isset( $options['per_page'] ) )
				unset( $options['per_page'] );

			if ( isset( $options['load_page_style'] ) )
				unset( $options['load_page_style'] );

			if ( isset( $options['load_widget_style'] ) )
				unset( $options['load_widget_style'] );

			if ( isset( $options['load_form_style'] ) )
				unset( $options['load_form_style'] );

			if ( isset( $options['load_rtl_style'] ) )
				unset( $options['load_rtl_style'] );

			if ( isset( $options['shortcode'] ) )
				unset( $options['shortcode'] );

			if ( isset( $options['default_template'] ) )
				unset( $options['default_template'] );

			if ( isset( $options['client_section'] ) )
				unset( $options['client_section'] );

		}

		/**
		 * Remove slideshow z-index (Cycle)
		 * @since 2.15.0
		 */
		if ( isset( $options['slideshow_zindex'] ) ) {
			unset( $options['slideshow_zindex'] );
		}

		// Merge in new options
		$options = array_merge( $default_options, $options );
		update_option( 'wpmtst_options', $options );
	}

	/**
	 * -3- GET FIELDS
	 */
	$fields = get_option( 'wpmtst_fields', array() );
	if ( ! $fields ) {
		// -3A- NEW ACTIVATION
		update_option( 'wpmtst_fields', $default_fields );
	}
	else {
		// -3B- UPDATE

		/**
		 * Updating from 1.x
		 *
		 * Copy current custom fields to the new default custom form which will be added in the next step.
		 *
		 * @since 2.0.1
		 * @since 2.17 Added version check.
		 */
		if ( version_compare( '2.0', $old_plugin_version ) ) {
			if ( isset( $fields['field_groups'] ) ) {
				$default_custom_forms[1]['fields'] = $fields['field_groups']['custom']['fields'];
				unset( $fields['field_groups'] );
			}
			if ( isset( $fields['current_field_group'] ) ) {
				unset( $fields['current_field_group'] );
			}
		}

		update_option( 'wpmtst_fields', $default_fields );
	}

	/**
	 * -4- GET FORMS
	 */
	update_option( 'wpmtst_base_forms', $default_base_forms );

	$custom_forms = get_option( 'wpmtst_custom_forms' );
	if ( ! $custom_forms ) {
		update_option( 'wpmtst_custom_forms', $default_custom_forms );
	}
	else {
		foreach ( $custom_forms as $form_id => $group_array ) {
			foreach ( $group_array['fields'] as $key => $new_field ) {

				/**
				 * Convert categories to category-selector.
				 *
				 * @since 2.17.0
				 */
				if ( 'categories' == $new_field['input_type'] ) {
					$custom_forms[ $form_id ]['fields'][ $key ]['input_type'] = 'category-selector';
				}

				/**
				 * Merge in new default.
				 *
				 * Custom fields are in display order (not associative) so we must find them by name.
				 */
				$updated_default = false;
				foreach ( $default_base_forms['default']['fields'] as $a_field ) {
					if ( $a_field['name'] == $new_field['name'] ) {
						$updated_default = $a_field;
						break;
					}
				}
				if ( $updated_default ) {
					$custom_forms[ $form_id ]['fields'][ $key ] = array_merge( $updated_default, $new_field );
				}

			}
		}
		update_option( 'wpmtst_custom_forms', $custom_forms );

		// WPML
		wpmtst_form_fields_wpml( $custom_forms[1]['fields'] );
	}


	/**
	 * -5- GET FORM OPTIONS
	 *
	 * @since 1.13
	 */
	$form_options = get_option( 'wpmtst_form_options', array() );
	if ( ! $form_options ) {
		// -5A- NEW ACTIVATION
		$form_options = $default_form_options;

		// -5B- MOVE EXISTING OPTIONS
		if ( isset( $options['admin_notify'] ) ) {
			$form_options['admin_notify']    = $options['admin_notify'];
			$form_options['admin_email']     = $options['admin_email'];
			$form_options['captcha']         = $options['captcha'];
			$form_options['honeypot_before'] = $options['honeypot_before'];
			$form_options['honeypot_after']  = $options['honeypot_after'];

			unset( $options['admin_notify'] );
			unset( $options['admin_email'] );
			unset( $options['captcha'] );
			unset( $options['honeypot_before'] );
			unset( $options['honeypot_after'] );
			update_option( 'wpmtst_options', $options );
		}

		update_option( 'wpmtst_form_options', $form_options );
	}
	else {
		// -5C- UPDATE
		/**
		 * Update single email recipient to multiple.
		 *
		 * @since 1.18
		 */
		if ( !isset( $form_options['recipients'] ) ) {
			$form_options['recipients'] = array(
				array(
					'admin_name'       => isset( $form_options['admin_name'] ) ? $form_options['admin_name'] : '',
					'admin_site_email' => isset( $form_options['admin_site_email'] ) ? $form_options['admin_site_email'] : 1,
					'admin_email'      => isset( $form_options['admin_email'] ) ? $form_options['admin_email'] : '',
					'primary'          => 1,  // cannot be deleted
				)
			);
		}

		unset( $form_options['admin_name'] );
		unset( $form_options['admin_site_email'] );
		unset( $form_options['admin_email'] );

		// Merge in new options
		$form_options = array_merge( $default_form_options, $form_options );
		update_option( 'wpmtst_form_options', $form_options );
	}
	// WPML
	wpmtst_form_messages_wpml( $form_options['messages'] );
	wpmtst_form_options_wpml( $form_options );


	/**
	 * -6- VIEW OPTIONS
	 *
	 * Overwrite default view options.
	 * @since 2.15.0
	 */
	update_option( 'wpmtst_view_options', $default_view_options );


	/**
	 * -7- VIEWS
	 */

	/**
	 * -7A-
	 * Overwrite default view settings.
	 * @since 2.15.0
	 */
	update_option( 'wpmtst_view_default', $default_view );

	/**
	 * -7B-
	 * Update each view.
	 */
	$views = wpmtst_get_views();

	if ( $views ) {

		// Only used in upgrading to version 2.10.0
		$average_word_length = false;

		foreach ( $views as $key => $view ) {

			$view_data = unserialize( $view['value'] );
			if ( ! is_array( $view_data ) )
				continue;

			// Change default template from empty to 'default:{type}'
			if ( ! $view_data['template'] ) {
				if ( 'form' == $view_data['mode'] )
					$type = 'form';
				else
					$type = 'content'; // list or slideshow

				$view_data['template'] = "default:$type";
			}
			else {
				// Convert name; e.g. 'simple/testimonials.php'
				if ( 'widget/testimonials.php' == $view_data['template'] ) {
					$view_data['template'] = 'default:widget';
				}
				else {
					$view_data['template'] = str_replace( '/', ':', $view_data['template'] );
					$view_data['template'] = str_replace( 'testimonials.php', 'content', $view_data['template'] );
					$view_data['template'] = str_replace( 'testimonial-form.php', 'form', $view_data['template'] );
				}
			}

			// Convert count value of -1 to 'all'
			if ( -1 == $view_data['count'] ) {
				$view_data['count'] = 1;
				$view_data['all']   = 1;
			}

			// Convert background color
			if ( !is_array( $view_data['background'] ) ) {
				$view_data['background'] = array(
					'color' => $view_data['background'],
					'type'  => 'single',
				);
			}

			// Convert 'form-ajax' (hyphen) to 'form_ajax' (underscore)
			if ( isset( $view_data['form-ajax'] ) ) {
				$view_data['form_ajax'] = $view_data['form-ajax'];
				unset( $view_data['form-ajax'] );
			}

			if ( isset( $view_data['pagination'] ) && $view_data['pagination'] ) {
				if ( isset( $view_data['layout'] ) && 'masonry' == $view_data['layout'] ) {
					$view_data['layout'] = '';
				}
			}

			/**
			 * Move word_count to excerpt_length for versions 2.10.0 to 2.11.3.
			 *
			 * @since 2.11.4
			 */
			if ( isset( $view_data['word_count'] ) ) {
				$view_data['excerpt_length'] = $view_data['word_count'];
				unset ( $view_data['word_count'] );
			}

			/**
			 * Convert length (characters).
			 *
			 * @since 2.10.0 word_count (deprecated)
			 * @since 2.11.4 excerpt_length
			 */
			if ( ! isset( $view_data['excerpt_length'] ) || ! $view_data['excerpt_length'] ) {
				if ( ! $average_word_length ) {
					$average_word_length = wpmtst_get_average_word_length();
				}

				if ( isset( $view_data['length'] ) && $view_data['length'] ) {
					$word_count                  = round( $view_data['length'] / $average_word_length );
					$word_count                  = $word_count < 5 ? 5 : $word_count;
					$word_count                  = $word_count > 300 ? 300 : $word_count;
					$view_data['excerpt_length'] = $word_count;
				}
				else {
					$view_data['excerpt_length'] = $default_view['excerpt_length'];
				}

				unset( $view_data['length'] );
			}

			/**
			 * Convert more_text to post or page.
			 * @since 2.10.0
			 */
			if ( isset( $view_data['more_text'] ) ) {
				if ( isset( $view_data['more_page'] ) && $view_data['more_page'] > 1 ) {
					// convert more_page to toggle and move page id to more_page_id
					$view_data['more_page_id']   = $view_data['more_page'];
					$view_data['more_page']      = 1;
					$view_data['more_page_text'] = $view_data['more_text'];
				}
				elseif ( isset( $view_data['more_post'] ) && $view_data['more_post'] ) {
					$view_data['more_post_text'] = $view_data['more_text'];
				}
				unset( $view_data['more_text'] );
			}

			/**
			 * Disable title on Modern template because new version of template has the title.
			 * Only if updating from version earlier than 2.12.4.
			 */
			if ( 'modern:content' == $view_data['template'] ) {
				if ( ! isset( $history['2.12.4_convert_modern_template'] ) ) {
					$view_data['title'] = 0;
				}
			}

			/**
			 * Convert slideshow settings.
			 * @since 2.15.0
			 */
			if ( 'slideshow' == $view_data['mode'] ) {
				$view_data['slideshow'] = 1;
			}
			if ( ! isset( $view_data['slideshow_settings'] ) ) {

				if ( 'scrollHorz' == $view_data['effect'] ) {
					$view_data['effect'] = 'horizontal';
				}

				$view_data['slideshow_settings'] = array(
					'effect'             => $view_data['effect'],
					'speed'              => $view_data['effect_for'],
					'pause'              => $view_data['show_for'],
					'auto_hover'         => ! $view_data['no_pause'],
					'adapt_height'       => false,
					'adapt_height_speed' => .5,
					'stretch'            => isset( $view_data['stretch'] ) ? 1 : 0,
				);

				unset(
					$view_data['effect'],
					$view_data['effect_for'],
					$view_data['no_pause'],
					$view_data['show_for'],
					$view_data['stretch']
				);

				if ( isset( $view_data['slideshow_nav'] ) ) {
					switch ( $view_data['slideshow_nav'] ) {
						case 'simple':
							$view_data['slideshow_settings']['controls_type']  = 'none';
							$view_data['slideshow_settings']['controls_style'] = 'buttons';
							$view_data['slideshow_settings']['pager_type']     = 'full';
							$view_data['slideshow_settings']['pager_style']    = 'buttons';
							$view_data['slideshow_settings']['nav_position']   = 'inside';
							break;
						case 'buttons1':
							$view_data['slideshow_settings']['controls_type']  = 'sides';
							$view_data['slideshow_settings']['controls_style'] = 'buttons';
							$view_data['slideshow_settings']['pager_type']     = 'none';
							$view_data['slideshow_settings']['pager_style']    = 'buttons';
							$view_data['slideshow_settings']['nav_position']   = 'inside';
							break;
						case 'buttons2':
							$view_data['slideshow_settings']['controls_type']  = 'simple';
							$view_data['slideshow_settings']['controls_style'] = 'buttons2';
							$view_data['slideshow_settings']['pager_type']     = 'none';
							$view_data['slideshow_settings']['pager_style']    = 'buttons';
							$view_data['slideshow_settings']['nav_position']   = 'inside';
							break;
						case 'indexed':
							$view_data['slideshow_settings']['controls_type']  = 'none';
							$view_data['slideshow_settings']['controls_style'] = 'buttons';
							$view_data['slideshow_settings']['pager_type']     = 'full';
							$view_data['slideshow_settings']['pager_style']    = 'text';
							$view_data['slideshow_settings']['nav_position']   = 'inside';
							break;
						default:
							// none
					}
					unset( $view_data['slideshow_nav'] );
				}

			}

			// Merge in new default values.
			$view['data'] = array_merge( $default_view, $view_data );

			// Merge nested arrays individually. Don't use array_merge_recursive.
			$view['data']['background']         = array_merge( $default_view['background'], $view_data['background'] );
			$view['data']['slideshow_settings'] = array_merge( $default_view['slideshow_settings'], $view_data['slideshow_settings'] );
			ksort( $view['data']['slideshow_settings'] );

			wpmtst_save_view( $view );

		} // foreach $view

	}


	/**
	 * -8- GET L10N CONTEXTS
	 */
	$contexts = get_option( 'wpmtst_l10n_contexts' );
	if ( ! $contexts ) {
		// -8A- NEW ACTIVATION
		update_option( 'wpmtst_l10n_contexts', $default_l10n_contexts );
	} else {
		// -8B- UPDATE
		// Merge in new options
		$contexts = array_merge( $default_l10n_contexts, $contexts );
		update_option( 'wpmtst_l10n_contexts', $contexts );
	}

	/**
	 * After all is said and done, update history log.
	 *
	 * @since 2.12.4
	 */
	if ( ! isset( $history['2.12.4_convert_modern_template'] ) ) {
		$history['2.12.4_convert_modern_template'] = current_time( 'mysql' );
		update_option( 'wpmtst_history', $history );
	}


	/**
	 * Update the plugin version.
	 */
	update_option( 'wpmtst_plugin_version', $plugin_version );

	/**
	 * Remove older attempts at admin notices.
	 */
	delete_option( 'wpmtst_admin_notices' );
	delete_option( 'wpmtst_news_flag' );

	/**
	 * Our welcome page
	 */
	//$old_parts       = explode( '.', $old_plugin_version );
	//$new_parts       = explode( '.', $plugin_version );
	//$old_major_minor = implode( '.', array( $old_parts[0], isset( $old_parts[1] ) ? $old_parts[1] : '0' ) );
	//$new_major_minor = implode( '.', array( $new_parts[0], isset( $new_parts[1] ) ? $new_parts[1] : '0' ) );

	//if ( version_compare( $new_major_minor, $old_major_minor ) ) {
		//set_transient( 'wpmtst_welcome_screen_activation_redirect', true, 30 );
	//}

	/**
	 * Delete old install log.
	 *
	 * @since 2.4.0
	 */
	if ( file_exists( WP_CONTENT_DIR  . '/install.log' ) ) {
		unlink( WP_CONTENT_DIR  . '/install.log' );
	}

	/**
	 * Flush rewrite rules.
	 *
	 * In case a theme or plugin skips this and it has a "testimonial" post type.
	 *
	 * @since 2.11.17
	 */
	add_action( 'shutdown', 'flush_rewrite_rules' );

}


/**
 * Convert length to excerpt_length.
 *
 * @since 2.10.0
 */
function wpmtst_get_average_word_length() {

	$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'wpm-testimonial',
		'post_status'      => 'publish',
		'suppress_filters' => true
	);
	$posts = get_posts( $args );
	if ( ! $posts )
		return 5;

	$allwords = array();

	foreach ( $posts as $post ) {
		$words = explode( ' ', $post->post_content );
		if ( count( $words ) > 5 ) {
			$allwords = $allwords + $words;
		}
	}

	$wordstring = join( '', $allwords );

	return round( strlen( $wordstring ) / count( $allwords) );
}
