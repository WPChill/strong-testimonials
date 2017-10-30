<?php
/**
 * Class Strong_Testimonials_Updater
 *
 * @since 2.28.0
 */
class Strong_Testimonials_Updater {

	/**
	 * The version before updating.
	 *
	 * @var string
	 */
	public static $old_version;

	/**
	 * Strong_Testimonials_Updater constructor.
	 */
	public function __construct() {
	}

	/**
	 * Static initializer.
	 */
	public static function init() {
		self::$old_version = get_option( 'wpmtst_plugin_version', false );
	}

	/**
	 * Return admin role.
	 *
	 * @since 2.27.0
	 *
	 * @return bool|null|WP_Role
	 */
	public static function get_admins() {
		return get_role( 'administrator' );
	}

	/**
	 * Add custom capabilities.
	 *
	 * @since 2.27.1
	 */
	public static function add_caps() {
		if ( $admins = self::get_admins() ) {
			$admins->add_cap( 'strong_testimonials_views' );
			$admins->add_cap( 'strong_testimonials_fields' );
			$admins->add_cap( 'strong_testimonials_options' );
			$admins->add_cap( 'strong_testimonials_about' );
		}
	}

	/**
	 * Remove custom capabilities.
	 *
	 * @since 2.27.1
	 */
	public static function remove_caps() {
		if ( $admins = self::get_admins() ) {
			$admins->remove_cap( 'strong_testimonials_views' );
			$admins->remove_cap( 'strong_testimonials_fields' );
			$admins->remove_cap( 'strong_testimonials_options' );
			$admins->remove_cap( 'strong_testimonials_about' );
		}
	}

	/**
	 * Update tables.
	 *
	 * @since 1.21.0 Checking for new table version.
	 */
	public static function update_db_check() {
		if ( get_option( 'wpmtst_db_version' ) != WPMST()->get_db_version() ) {
			self::update_tables();
		}
	}

	/**
	 * Add tables for Views.
	 *
	 * @since 1.21.0
	 */
	public static function update_tables() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'strong_views';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL,
			value text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		// TODO Error handling
		$result = dbDelta( $sql );

		update_option( 'wpmtst_db_version', WPMST()->get_db_version() );
	}

	/**
	 * Redirect to About page.
	 */
	public static function activation_redirect() {
		if ( get_option( 'wpmtst_do_activation_redirect', false ) ) {
			delete_option( 'wpmtst_do_activation_redirect' );
			wp_redirect( admin_url( 'edit.php?post_type=wpm-testimonial&page=about-strong-testimonials' ) );
			exit;
		}
	}

	/**
	 * Plugin activation and upgrade.
	 *
	 * ---------
	 * REMEMBER!
	 * ---------
	 * If you are changing the value of a default field property,
	 * then you need to unset that value in the current field
	 * before merging in the new default values.
	 *
	 * For example, when changing a rating field property from
	 * disabled (0) to enabled (1) in order for the property to
	 * be displayed in the form editor.
	 */
	public static function update() {
		if ( self::$old_version == WPMTST_VERSION ) {
			return;
		}

		// Redirect to About page for new installs only
		if ( false === self::$old_version ) {
			add_option( 'wpmtst_do_activation_redirect', true );
		}

		/**
		 * Add custom capablities.
		 *
		 * @since 2.27.1
		 */
		self::add_caps();

		/**
		 * Check DB version.
		 */
		self::update_db_check();

		/**
		 * Let's start updating.
		 */
		$history = get_option( 'wpmtst_history', array() );

		/**
		 * Options.
		 */
		update_option( 'wpmtst_options', self::update_options() );

		/**
		 * Custom fields.
		 */
		update_option( 'wpmtst_fields', self::update_fields() );

		/**
		 * Forms.
		 */
		update_option( 'wpmtst_base_forms', self::update_base_forms() );
		update_option( 'wpmtst_custom_forms', self::update_custom_forms() );
		update_option( 'wpmtst_form_options', self::update_form_options() );

		/**
		 * Compatibility options.
		 *
		 * @since 2.28.0
		 */
		update_option( 'wpmtst_compat_options', self::update_compat_options() );

		/**
		 * Overwrite default view options.
		 *
		 * @since 2.15.0
		 */
		update_option( 'wpmtst_view_options', self::update_view_options() );

		/**
		 * Overwrite default view settings.
		 *
		 * @since 2.15.0
		 */
		update_option( 'wpmtst_view_default', self::update_default_view() );

		/**
		 * Update views.
		 */
		self::update_views();

		/**
		 * Convert nofollow
		 */
		if ( ! isset( $history['2.23.0_convert_nofollow'] ) ) {
			self::convert_nofollow();
			self::update_history_log( '2.23.0_convert_nofollow' );
		}

		/**
		 * Legacy stuff.
		 */
		if ( ! isset( $history['2.28_new_update_process'] ) ) {
			// Upgrade from version 1.x
			delete_option( 'wpmtst_cycle' );

			// L10n context no longer used.
			delete_option( 'wpmtst_l10n_contexts' );

			//  Remove older attempts at admin notices.
			delete_option( 'wpmtst_admin_notices' );
			delete_option( 'wpmtst_news_flag' );

			self::update_history_log( '2.28_new_update_process' );
		}

		/**
		 * Update the plugin version.
		 */
		update_option( 'wpmtst_plugin_version', WPMTST_VERSION );
	}

	/**
	 * Update history log.
	 *
	 * @param $event
	 */
	public static function update_history_log( $event ) {
		$history = get_option( 'wpmtst_history', array() );
		$history[ $event ] = current_time( 'mysql' );
		update_option( 'wpmtst_history', $history );
	}

	/**
	 * Update options.
	 *
	 * @return array
	 */
	public static function update_options() {
		$options = get_option( 'wpmtst_options' );
		if ( ! $options ) {
			return Strong_Testimonials_Defaults::get_options();
		}

		/**
		 * Remove version 1 options
		 */
		if ( version_compare( '2.0', self::$old_version ) ) {

			if ( isset( $options['captcha'] ) ) {
				unset( $options['captcha'] );
			}

			if ( isset( $options['plugin_version'] ) ) {
				unset( $options['plugin_version'] );
			}

			if ( isset( $options['per_page'] ) ) {
				unset( $options['per_page'] );
			}

			if ( isset( $options['load_page_style'] ) ) {
				unset( $options['load_page_style'] );
			}

			if ( isset( $options['load_widget_style'] ) ) {
				unset( $options['load_widget_style'] );
			}

			if ( isset( $options['load_form_style'] ) ) {
				unset( $options['load_form_style'] );
			}

			if ( isset( $options['load_rtl_style'] ) ) {
				unset( $options['load_rtl_style'] );
			}

			if ( isset( $options['shortcode'] ) ) {
				unset( $options['shortcode'] );
			}

			if ( isset( $options['default_template'] ) ) {
				unset( $options['default_template'] );
			}

			if ( isset( $options['client_section'] ) ) {
				unset( $options['client_section'] );
			}

		}

		/**
		 * Remove slideshow z-index (Cycle)
		 *
		 * @since 2.15.0
		 */
		if ( isset( $options['slideshow_zindex'] ) ) {
			unset( $options['slideshow_zindex'] );
		}

		/**
		 * Replace zero embed_width with empty value.
		 *
		 * @since 2.27.0
		 */
		$options['embed_width'] = $options['embed_width'] ? (int) sanitize_text_field( $options['embed_width'] ) : '';

		// Merge in new options
		return array_merge( Strong_Testimonials_Defaults::get_options(), $options );
	}

	/**
	 * Custom fields
	 *
	 * @return array
	 */
	public static function update_fields() {
		$fields = get_option( 'wpmtst_fields', array() );
		if ( ! $fields ) {
			return Strong_Testimonials_Defaults::get_fields();
		} else {
			/**
			 * Updating from 1.x
			 *
			 * Copy current custom fields to the new default custom form which will be added in the next step.
			 *
			 * @since 2.0.1
			 * @since 2.17 Added version check.
			 */
			if ( version_compare( '2.0', self::$old_version ) ) {
				if ( isset( $fields['field_groups'] ) ) {
					$default_custom_forms[1]['fields'] = $fields['field_groups']['custom']['fields'];
					unset( $fields['field_groups'] );
				}
				if ( isset( $fields['current_field_group'] ) ) {
					unset( $fields['current_field_group'] );
				}
			}
		}

		return $fields;
	}

	/**
	 * Base forms.
	 *
	 * @return array
	 */
	public static function update_base_forms() {
		return Strong_Testimonials_Defaults::get_base_forms();
	}

	/**
	 * Custom forms.
	 *
	 * @return array
	 */
	public static function update_custom_forms() {
		$custom_forms = get_option( 'wpmtst_custom_forms' );
		if ( ! $custom_forms ) {
			return Strong_Testimonials_Defaults::get_custom_forms();
		} else {
			foreach ( $custom_forms as $form_id => $form_properties ) {
				foreach ( $form_properties['fields'] as $key => $form_field ) {

					/*
					 * Convert categories to category-selector.
					 * @since 2.17.0
					 */
					if ( 'categories' == $form_field['input_type'] ) {
						$custom_forms[ $form_id ]['fields'][ $key ]['input_type'] = 'category-selector';
					}

					/*
					 * Unset `show_default_options` for rating field. Going from 0 to 1.
					 * @since 2.21.0
					 */
					if ( 'rating' == $form_field['input_type'] ) {
						unset( $form_field['show_default_options'] );
					}

					/*
					 * Add `show_required_option` to shortcode field. Initial value is false.
					 * @since 2.22.0
					 */
					if ( 'shortcode' == $form_field['input_type'] ) {
						$form_field['show_required_option'] = false;
					}

					/*
					 * Add `show_default_options` to checkbox field.
					 *
					 * @since 2.27.0
					 */
					if ( 'checkbox' == $form_field['input_type'] ) {
						$form_field['show_default_options'] = 1;
					}

					/*
					 * Merge in new default.
					 * Custom fields are in display order (not associative) so we must find them by `input_type`.
					 * @since 2.21.0 Using default fields instead of default form as source
					 */
					$new_default = array();
					$fields = get_option( 'wpmtst_fields', array() );

					foreach ( $fields['field_types'] as $field_type_group_key => $field_type_group ) {
						foreach ( $field_type_group as $field_type_key => $field_type_field ) {
							if ( $field_type_field['input_type'] == $form_field['input_type'] ) {
								$new_default = $field_type_field;
								break;
							}
						}
					}

					if ( $new_default ) {
						$custom_forms[ $form_id ]['fields'][ $key ] = array_merge( $new_default, $form_field );
					}

				}
			}
		}

		return $custom_forms;
	}

	/**
	 * Form options.
	 *
	 * @return array
	 */
	public static function update_form_options() {
		$form_options = get_option( 'wpmtst_form_options' );
		if ( ! $form_options ) {
			return Strong_Testimonials_Defaults::get_form_options();
		} else {

			$options = get_option( 'wpmtst_options', array() );
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

			// -5C- UPDATE
			/**
			 * Update single email recipient to multiple.
			 *
			 * @since 1.18
			 */
			if ( ! isset( $form_options['recipients'] ) ) {
				$form_options['recipients'] = array(
					array(
						'admin_name'       => isset( $form_options['admin_name'] ) ? $form_options['admin_name'] : '',
						'admin_site_email' => isset( $form_options['admin_site_email'] ) ? $form_options['admin_site_email'] : 1,
						'admin_email'      => isset( $form_options['admin_email'] ) ? $form_options['admin_email'] : '',
						'primary'          => 1,  // cannot be deleted
					),
				);
			}

			unset( $form_options['admin_name'] );
			unset( $form_options['admin_site_email'] );
			unset( $form_options['admin_email'] );

			/**
			 * Add default required-notice setting
			 *
			 * @since 2.24.1
			 */
			if ( ! isset( $form_options['messages']['required-field']['enabled'] ) ) {
				$form_options['messages']['required-field']['enabled'] = 1;
			}

			// Merge in new options
			$form_options = array_merge( Strong_Testimonials_Defaults::get_form_options(), $form_options );

		}

		return $form_options;
	}

	/**
	 * Compatibility options.
	 *
	 * @since 2.28.0
	 *
	 * @return array
	 */
	public static function update_compat_options() {
		$options = get_option( 'wpmtst_compat_options' );
		if ( ! $options ) {
			return Strong_Testimonials_Defaults::get_compat_options();
		}

		// Merge in new options.
		// Merge nested arrays individually. Don't use array_merge_recursive.
		$defaults = Strong_Testimonials_Defaults::get_compat_options();
		$options['ajax'] = array_merge( $defaults['ajax'], $options['ajax'] );
		return array_merge( $defaults, $options );
	}

	/**
	 * View options.
	 *
	 * @return array
	 */
	public static function update_view_options() {
		return Strong_Testimonials_Defaults::get_view_options();
	}

	/**
	 * Default view.
	 *
	 * @return array
	 */
	public static function update_default_view() {
		return apply_filters( 'wpmtst_view_default', Strong_Testimonials_Defaults::get_default_view() );
	}

	/**
	 * Update views.
	 */
	public static function update_views() {
		// TODO Make this function accessible by add-ons; i.e. update_view( array ( {parameter} => {value} ) );
		$views = wpmtst_get_views();

		if ( ! $views ) {
			return;
		}

		$default_view = get_option( 'wpmtst_view_default' );
		$history = get_option( 'wpmtst_history', array() );

		foreach ( $views as $key => $view ) {

			$view_data = unserialize( $view['value'] );
			if ( ! is_array( $view_data ) ) {
				continue;
			}

			if ( ! isset( $history['2.28_new_update_process'] ) ) {
				/**
				 * Compat mode no longer needed.
				 *
				 * @since 2.22.0
				 */
				unset( $view_data['compat'] );

				$view_data = self::convert_template_name( $view_data );
				$view_data = self::convert_count( $view_data );
				$view_data = self::convert_background_color( $view_data );
				$view_data = self::convert_form_ajax( $view_data );
				$view_data = self::convert_word_count( $view_data );
				$view_data = self::convert_excerpt_length( $view_data );
				$view_data = self::convert_more_text( $view_data );
				$view_data = self::convert_modern_title( $view_data );
				$view_data = self::convert_slideshow( $view_data );
				$view_data = self::convert_title_link( $view_data );
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
	 * Update template naming structure.
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_template_name( $view_data ) {
		// Change default template from empty to 'default:{type}'
		if ( ! $view_data['template'] ) {
			if ( 'form' == $view_data['mode'] ) {
				$type = 'form';
			} else {
				$type = 'content';
			}

			$view_data['template'] = "default:$type";
		} else {
			// Convert name; e.g. 'simple/testimonials.php'
			if ( 'widget/testimonials.php' == $view_data['template'] ) {
				$view_data['template'] = 'default:widget';
			} else {
				$view_data['template'] = str_replace( '/', ':', $view_data['template'] );
				$view_data['template'] = str_replace( 'testimonials.php', 'content', $view_data['template'] );
				$view_data['template'] = str_replace( 'testimonial-form.php', 'form', $view_data['template'] );
			}
		}

		return $view_data;
	}

	/**
	 * Convert length (characters).
	 *
	 * @since 2.10.0 word_count (deprecated)
	 * @since 2.11.4 excerpt_length
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_excerpt_length( $view_data ) {
		if ( ! isset( $view_data['excerpt_length'] ) || ! $view_data['excerpt_length'] ) {
			$default_view = Strong_Testimonials_Defaults::get_default_view();
			$average_word_length = self::get_average_word_length();

			if ( isset( $view_data['length'] ) && $view_data['length'] ) {
				$word_count                  = round( $view_data['length'] / $average_word_length );
				$word_count                  = $word_count < 5 ? 5 : $word_count;
				$word_count                  = $word_count > 300 ? 300 : $word_count;
				$view_data['excerpt_length'] = $word_count;
			} else {
				$view_data['excerpt_length'] = $default_view['excerpt_length'];
			}

			unset( $view_data['length'] );
		}

		return $view_data;
	}

	/**
	 * Convert more_text to post or page.
	 *
	 * @since 2.10.0
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_more_text( $view_data ) {
		if ( isset( $view_data['more_text'] ) ) {
			if ( isset( $view_data['more_page'] ) && $view_data['more_page'] > 1 ) {
				// convert more_page to toggle and move page id to more_page_id
				$view_data['more_page_id']   = $view_data['more_page'];
				$view_data['more_page']      = 1;
				$view_data['more_page_text'] = $view_data['more_text'];
			} elseif ( isset( $view_data['more_post'] ) && $view_data['more_post'] ) {
				$view_data['more_post_text'] = $view_data['more_text'];
			}
			unset( $view_data['more_text'] );
		}

		return $view_data;
	}

	/**
	 * Convert slideshow settings.
	 *
	 * @since 2.15.0
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_slideshow( $view_data ) {
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

		return $view_data;
	}

	/**
	 * Convert count value of -1 to 'all'.
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_count( $view_data ) {
		if ( -1 == $view_data['count'] ) {
			$view_data['count'] = 1;
			$view_data['all']   = 1;
		}

		return $view_data;
	}

	/**
	 * Convert background color.
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_background_color( $view_data ) {
		if ( ! is_array( $view_data['background'] ) ) {
			$view_data['background'] = array(
				'color' => $view_data['background'],
				'type'  => 'single',
			);
		}

		return $view_data;
	}

	/**
	 * Convert 'form-ajax' (hyphen) to 'form_ajax' (underscore).
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_form_ajax( $view_data ) {
		if ( isset( $view_data['form-ajax'] ) ) {
			$view_data['form_ajax'] = $view_data['form-ajax'];
			unset( $view_data['form-ajax'] );
		}

		if ( isset( $view_data['pagination'] ) && $view_data['pagination'] ) {
			if ( isset( $view_data['layout'] ) && 'masonry' == $view_data['layout'] ) {
				$view_data['layout'] = '';
			}
		}

		return $view_data;
	}

	/**
	 * Move word_count to excerpt_length for versions 2.10.0 to 2.11.3.
	 *
	 * @since 2.11.4

	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_word_count( $view_data ) {
		if ( isset( $view_data['word_count'] ) ) {
			$view_data['excerpt_length'] = $view_data['word_count'];
			unset( $view_data['word_count'] );
		}

		return $view_data;
	}

	/**
	 * Disable title on Modern template because new version of template has the title.
	 * Only if updating from version earlier than 2.12.4.
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_modern_title( $view_data ) {
		if ( 'modern:content' == $view_data['template'] ) {
			if ( ! isset( $history['2.12.4_convert_modern_template'] ) ) {
				$view_data['title'] = 0;
				self::update_history_log( '2.12.4_convert_modern_template' );
			}
		}

		return $view_data;
	}

	/**
	 * Title link
	 *
	 * @since 2.26.0

	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_title_link( $view_data ) {
		if ( ! isset( $view_data['title_link'] ) ) {
			$view_data['title_link'] = 0;
		}

		return $view_data;
	}

	/**
	 * Convert nofollow from (on|off) to (1|0).
	 *
	 * @since 2.23.0
	 */
	public static function convert_nofollow() {
		$args  = array(
			'posts_per_page'   => - 1,
			'post_type'        => 'wpm-testimonial',
			'post_status'      => 'publish',
			'suppress_filters' => true,
		);
		$posts = get_posts( $args );
		if ( ! $posts ) {
			return;
		}

		/**
		 * Remove the equivocation. There is no false.
		 */
		foreach ( $posts as $post ) {
			$nofollow  = get_post_meta( $post->ID, 'nofollow', true );
			$new_value = 'default';

			if ( 'on' == $nofollow ) {
				$new_value = 'yes';
			} elseif ( 1 === $nofollow ) {
				$new_value = 'yes';
			} elseif ( 'off' == $nofollow ) {
				$new_value = 'no';
			} elseif ( 0 === $nofollow ) {
				$new_value = 'no';
			} elseif ( is_bool( $nofollow ) ) {
				if ( $nofollow ) {
					$new_value = 'yes';
				} else {
					$new_value = 'default';
				}
			}

			update_post_meta( $post->ID, 'nofollow', $new_value );
		}
	}

	/**
	 * Convert length to excerpt_length.
	 *
	 * @since 2.10.0
	 */
	public static function get_average_word_length() {
		$args  = array(
			'posts_per_page'   => - 1,
			'post_type'        => 'wpm-testimonial',
			'post_status'      => 'publish',
			'suppress_filters' => true,
		);
		$posts = get_posts( $args );
		if ( ! $posts ) {
			return 5;
		}

		$allwords = array();

		foreach ( $posts as $post ) {
			$words = explode( ' ', $post->post_content );
			if ( count( $words ) > 5 ) {
				$allwords = $allwords + $words;
			}
		}

		$wordstring = join( '', $allwords );

		return round( strlen( $wordstring ) / count( $allwords ) );
	}

	/**
	 * Unset stored version number to allow rollback and beta testing.
	 *
	 * @since 2.28.0
	 */
	public static function unset_version() {
		delete_option( 'wpmtst_plugin_version');
	}
}

Strong_Testimonials_Updater::init();
