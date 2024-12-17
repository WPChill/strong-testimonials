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
	private static $old_version = '3.0.0';

	/**
	 * Log steps during update process.
	 *
	 * @var array
	 */
	private static $new_log;

	/**
	 * Strong_Testimonials_Updater constructor.
	 */
	public function __construct() {
	}

	/**
	 * Add a log entry.
	 *
	 * @param        $name
	 * @param string $entry
	 * @param string $variable
	 */
	private static function log( $name, $entry = '', $variable = '' ) {
		if ( $name ) {
			$x = $name;
			if ( $entry ) {
				$x .= ' : ' . $entry;
				if ( $variable ) {
					$x .= ' = ';
					if ( is_array( $variable ) || is_object( $variable ) ) {
						// log the text
						self::$new_log[] = $x;
						// then log the variable
						self::$new_log[] = $variable;
					} else {
						self::$new_log[] = $x . $variable;
					}
				} else {
					self::$new_log[] = $x;
				}
			} else {
				self::$new_log[] = $x;
			}
		}
	}

	/**
	 * Plugin activation and update.
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
		if ( get_transient( 'wpmtst_update_in_progress' ) ) {
			return;
		}

		set_transient( 'wpmtst_update_in_progress', 1, 10 );

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
		update_option( 'wpmtst_fields', self::update_fields(), 'no' );

		/**
		 * Forms.
		 */
		update_option( 'wpmtst_base_forms', self::update_base_forms(), 'no' );
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
		update_option( 'wpmtst_view_default', self::update_default_view(), 'no' );

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
						self::convert_noopener();
						self::convert_noreferrer();
		}

		/**
		 * Legacy stuff.
		 */
		if ( ! isset( $history['2.28_new_update_process'] ) ) {
			// Upgrade from version 1.x
			delete_option( 'wpmtst_cycle' );

			// L10n context no longer used.
			delete_option( 'wpmtst_l10n_contexts' );

			// Remove older attempts at admin notices.
			delete_option( 'wpmtst_news_flag' );

			self::update_history_log( '2.28_new_update_process' );
		}

		/**
		 * Fix add-ons.
		 */
		self::update_addons();

		/**
		 * Update the plugin version.
		 */
		update_option( 'wpmtst_plugin_version', WPMTST_VERSION );

		/**
		 * Update log.
		 */
		self::update_log();

		/**
		 * Update admin notices.
		 */
		self::update_admin_notices();

		delete_transient( 'wpmtst_update_in_progress' );
	}

	/**
	 * Fix add-on file names.
	 *
	 * @since 2.30.9
	 */
	public static function update_addons() {
		$addons = get_option( 'wpmtst_addons' );
		if ( $addons ) {
			foreach ( $addons as $addon => $data ) {
				if ( isset( $addons[ $addon ]['file'] ) ) {
					$addons[ $addon ]['file'] = plugin_basename( basename( $data['file'], '.php' ) . '/' . basename( $data['file'] ) );
				}
			}
			update_option( 'wpmtst_addons', $addons );
		}
	}

	/**
	 * Update the log in options table.
	 */
	public static function update_log() {
		$log                            = get_option( 'wpmtst_update_log', array() );
		$log[ current_time( 'mysql' ) ] = self::$new_log;
		update_option( 'wpmtst_update_log', $log );
	}

	public static function update_admin_notices() {
		wpmtst_add_admin_notice( 'feedback-notice', true );
		wpmtst_add_admin_notice( 'upsell-notice', true );
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
		$admins = self::get_admins();
		if ( $admins ) {
			$admins->add_cap( 'strong_testimonials_views' );
			$admins->add_cap( 'strong_testimonials_fields' );
			$admins->add_cap( 'strong_testimonials_options' );
			$admins->add_cap( 'strong_testimonials_about' );
		} else {
			self::log( __FUNCTION__, 'failed' );
		}
	}

	/**
	 * Remove custom capabilities.
	 *
	 * Was part of uninstall process but cannot be run from static class.
	 *
	 * @todo  Move to Leave No Trace.
	 *
	 * @since 2.27.1
	 */
	public static function remove_caps() {
		$admins = self::get_admins();
		if ( $admins ) {
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
		if ( get_option( 'wpmtst_db_version' ) !== WPMST()->get_db_version() ) {
			wpmtst_update_tables();
			self::log( __FUNCTION__, 'tables updated' );
		}
	}

	/**
	 * Update history log.
	 *
	 * @param $event
	 */
	public static function update_history_log( $event ) {
		$history           = get_option( 'wpmtst_history', array() );
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
		if ( 0 === $options['embed_width'] ) {
			$options['embed_width'] = '';
		}

		/**
		 * Remove email logging.
		 *
		 * @since 2.28.4
		 */
		if ( isset( $options['email_log_level'] ) ) {
			unset( $options['email_log_level'] );
		}

		// Merge in new options
		$options = array_merge( Strong_Testimonials_Defaults::get_options(), $options );

		return $options;
	}

	/**
	 * Default custom fields.
	 *
	 * @since 2.31.0 There is a rare bug/conflict where the default fields are incomplete.
	 *               Overwrite existing fields on every update to auto-repair.
	 *
	 * @return array
	 */
	public static function update_fields() {
		return Strong_Testimonials_Defaults::get_fields();
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
		}

		foreach ( $custom_forms as $form_id => $form_properties ) {
			foreach ( $form_properties['fields'] as $key => $form_field ) {

				/*
				 * Convert categories to category-selector.
				 * @since 2.17.0
				 */
				if ( 'categories' === $form_field['input_type'] ) {
					$custom_forms[ $form_id ]['fields'][ $key ]['input_type'] = 'category-selector';
				}

				/*
				 * Unset `show_default_options` for rating field. Going from 0 to 1.
				 * @since 2.21.0
				 */
				if ( 'rating' === $form_field['input_type'] ) {
					unset( $form_field['show_default_options'] );
				}

				/*
				 * Add `show_required_option` to shortcode field. Initial value is false.
				 * @since 2.22.0
				 */
				if ( 'shortcode' === $form_field['input_type'] ) {
					$form_field['show_required_option'] = false;
				}

				/*
				 * Add `show_default_options` to checkbox field.
				 *
				 * @since 2.27.0
				 */
				if ( 'checkbox' === $form_field['input_type'] ) {
					$form_field['show_default_options'] = 1;
				}

				/*
				 * Merge in new default.
				 * Custom fields are in display order (not associative) so we must find them by `input_type`.
				 * @since 2.21.0 Using default fields instead of default form as source
				 */
				$new_default = array();
				$fields      = get_option( 'wpmtst_fields', array() );

				foreach ( $fields['field_types'] as $field_type_group_key => $field_type_group ) {
					foreach ( $field_type_group as $field_type_key => $field_type_field ) {
						if ( $field_type_field['input_type'] === $form_field['input_type'] ) {
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
		}

		$options = get_option( 'wpmtst_options', array() );
		$history = get_option( 'wpmtst_history', array() );

		/**
		 * Move existing options.
		 */
		if ( isset( $options['admin_notify'] ) ) {
			$form_options['admin_notify'] = $options['admin_notify'];
			unset( $options['admin_notify'] );

			$form_options['admin_email'] = $options['admin_email'];
			unset( $options['admin_email'] );

			update_option( 'wpmtst_options', $options );
		}

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

			unset( $form_options['admin_name'] );
			unset( $form_options['admin_site_email'] );
			unset( $form_options['admin_email'] );
		}

		/**
		 * Add default required-notice setting
		 *
		 * @since 2.24.1
		 */
		if ( ! isset( $form_options['messages']['required-field']['enabled'] ) ) {
			$form_options['messages']['required-field']['enabled'] = 1;
		}

		/**
		 * Delete form options
		 *
		 * @since 2.38
		 */
		unset( $form_options['captcha'] );
		unset( $form_options['messages']['captcha'] );

		/**
		 * Merge in new options.
		 */
		$defaults = Strong_Testimonials_Defaults::get_form_options();

		$form_options = array_merge( $defaults, $form_options );

		// Merge nested arrays individually. Don't use array_merge_recursive.

		$form_options['default_recipient'] = array_merge( $defaults['default_recipient'], $form_options['default_recipient'] );
		foreach ( $defaults['messages'] as $key => $message ) {
			if ( isset( $form_options['messages'][ $key ] ) ) {
				$form_options['messages'][ $key ] = array_merge( $message, $form_options['messages'][ $key ] );
			}
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
		$defaults = Strong_Testimonials_Defaults::get_compat_options();

		// Merge nested arrays individually. Don't use array_merge_recursive.

		if ( isset( $options['controller'] ) ) {
			$options['ajax'] = array_merge( $defaults['ajax'], $options['ajax'] );
		} else {
			$options['ajax'] = $defaults['ajax'];
		}

		/**
		 * Controller
		 *
		 * @since 2.31.0
		 */
		if ( isset( $options['controller'] ) ) {
			$options['controller'] = array_merge( $defaults['controller'], $options['controller'] );
		} else {
			$options['controller'] = $defaults['controller'];
		}

		/**
		 * Lazy load
		 *
		 * @since 2.31.0
		 */
		if ( isset( $options['lazyload'] ) ) {
			// first level only: enabled, classes (array)
			$options['lazyload'] = array_merge( $defaults['lazyload'], $options['lazyload'] );
		} else {
			$options['lazyload'] = $defaults['lazyload'];
		}

		$options = array_merge( $defaults, $options );

		return $options;
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
	 *
	 * @uses wpmtst_save_view
	 */
	public static function update_views() {
		$views = wpmtst_get_views();

		if ( ! $views ) {
			return;
		}

		$default_view = wpmtst_get_view_default();
		$history      = get_option( 'wpmtst_history', array() );

		foreach ( $views as $key => $view ) {

			$view_data = unserialize( $view['value'] );
			if ( ! is_array( $view_data ) ) {
				self::log( __FUNCTION__, 'view ' . $view['id'] . ' data is not an array' );
				continue;
			}

			/**
			 * For version 2.28.
			 */
			if ( ! isset( $history['2.28_new_update_process'] ) ) {
				/**
				 * Compat mode no longer needed.
				 *
				 * @since 2.22.0
				 */
				unset( $view_data['compat'] );

				$view_data = self::convert_template_name( $view_data );
				$view_data = self::convert_background_color( $view_data );
				$view_data = self::convert_form_ajax( $view_data );
				$view_data = self::convert_layout( $view_data );
				$view_data = self::convert_word_count( $view_data );
				$view_data = self::convert_excerpt_length( $view_data );
				$view_data = self::convert_more_text( $view_data );
				$view_data = self::convert_modern_title( $view_data );
				$view_data = self::convert_slideshow( $view_data );
				$view_data = self::convert_title_link( $view_data );
				$view_data = self::convert_pagination_type( $view_data );
			}

			/**
			 * For version 2.30.
			 */
			if ( ! isset( $history['2.30_new_template_structure'] ) ) {
				$view_data = self::convert_template_structure( $view_data );
				$view_data = self::convert_count( $view_data );
				if ( isset( $view_data['background']['example-font-color'] ) ) {
					unset( $view_data['background']['example-font-color'] );
				}

				self::update_history_log( '2.30_new_template_structure' );
			}

			/**
			 * Add carousel breakpoints.
			 *
			 * @since 2.32.2
			 */
			if ( ! isset( $history['2.32.2_carousel_breakpoints'] ) ) {
				$view['data'] = self::add_carousel_breakpoints( $view_data );
				self::update_history_log( '2.32.2_carousel_breakpoints' );
			}

			/**
			 * Merge in new default values.
			 * Merge nested arrays individually. Don't use array_merge_recursive.
			 */
			$view['data'] = array_merge( $default_view, $view_data );

			/**
			 * Background defaults.
			 */
			$view['data']['background'] = array_merge( $default_view['background'], $view_data['background'] );

			/**
			 * Pagination defaults.
			 * Attempt to repair bug from 2.28.2
			 *
			 * @since 2.28.3
			 */
			if ( isset( $view_data['pagination_settings'] ) ) {
				$view['data']['pagination_settings'] = array_merge( $default_view['pagination_settings'], $view_data['pagination_settings'] );

				if ( ! isset( $view['data']['pagination_settings']['end_size'] ) || ! $view['data']['pagination_settings']['end_size'] ) {
					$view['data']['pagination_settings']['end_size'] = 1;
				}
				if ( ! isset( $view['data']['pagination_settings']['mid_size'] ) || ! $view['data']['pagination_settings']['mid_size'] ) {
					$view['data']['pagination_settings']['mid_size'] = 2;
				}
				if ( ! isset( $view['data']['pagination_settings']['per_page'] ) || ! $view['data']['pagination_settings']['per_page'] ) {
					$view['data']['pagination_settings']['per_page'] = 5;
				}
			} else {
				$view['data']['pagination_settings'] = $default_view['pagination_settings'];
			}

			/**
			 * Slideshow defaults.
			 */
			if ( isset( $view_data['slideshow_settings'] ) ) {
				$view['data']['slideshow_settings'] = array_merge( $default_view['slideshow_settings'], $view_data['slideshow_settings'] );
			} else {
				$view['data']['slideshow_settings'] = $default_view['slideshow_settings'];
			}
			ksort( $view['data']['slideshow_settings'] );

			/**
			 * Save it.
			 */
			wpmtst_save_view( $view );

		} // foreach $view
	}

	/**
	 * Add carousel breakpoints.
	 *
	 * @param array $view_data
	 * @since 2.32.2
	 *
	 * @return array
	 */
	public static function add_carousel_breakpoints( $view_data ) {
		if ( ! isset( $view_data['slideshow_settings']['max_slides'] ) ) {
			return $view_data;
		}

		if ( 1 === absint( $view_data['slideshow_settings']['max_slides'] ) ) {

			// Convert to single
			$view_data['slideshow_settings']['type'] = 'show_single';

			$view_data['slideshow_settings']['show_single'] = array(
				'max_slides'  => $view_data['slideshow_settings']['max_slides'],
				'move_slides' => $view_data['slideshow_settings']['move_slides'],
				'margin'      => 1,
			);

		} else {

			// Convert to multiple
			$view_data['slideshow_settings']['type'] = 'show_multiple';

			$view_data['slideshow_settings']['breakpoints']['desktop'] = array(
				'max_slides'  => $view_data['slideshow_settings']['max_slides'],
				'move_slides' => $view_data['slideshow_settings']['move_slides'],
				'margin'      => 1,
			);

		}

		// Remove old values
		unset(
			$view_data['slideshow_settings']['max_slides'],
			$view_data['slideshow_settings']['move_slides'],
			$view_data['slideshow_settings']['margin']
		);

		return $view_data;
	}

	/**
	 * Convert template naming structure.
	 *
	 * @since 2.30.0
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_template_structure( $view_data ) {
		/*
		Array
		(
			[0] => default:content
			[1] => default-dark:form
			[2] => default-dark:content
			[3] => default:form
			[4] => image-right:content
			[5] => no-quotes:content
			[6] => large:widget
			[7] => modern:content
			[8] => simple:content
			[9] => simple:form
			[10] => unstyled:content
			[11] => unstyled:form
			[12] => default:widget
			[13] => image-right:widget
		)
		*/
		switch ( $view_data['template'] ) {
			case 'default:content':
				$view_data['template'] = 'default';
				break;
			case 'default-dark:form':
				$view_data['template']                                    = 'default-form';
				$view_data['template_settings'][ $view_data['template'] ] = array( 'theme' => 'dark' );
				break;
			case 'default-dark:content':
				$view_data['template']                                    = 'default';
				$view_data['template_settings'][ $view_data['template'] ] = array( 'theme' => 'dark' );
				break;
			case 'default:form':
				$view_data['template'] = 'default-form';
				break;
			case 'image-right:content':
				$view_data['template']                                    = 'default';
				$view_data['template_settings'][ $view_data['template'] ] = array( 'image_position' => 'right' );
				break;
			case 'no-quotes:content':
				$view_data['template']                                    = 'default';
				$view_data['template_settings'][ $view_data['template'] ] = array( 'quotes' => 'off' );
				break;
			case 'large:widget':
				$view_data['template'] = 'bold';
				break;
			case 'modern:content':
				$view_data['template'] = 'modern';
				break;
			case 'simple:content':
				$view_data['template'] = 'simple';
				break;
			case 'simple:form':
				$view_data['template'] = 'simple-form';
				break;
			case 'unstyled:content':
				$view_data['template'] = 'unstyled';
				break;
			case 'unstyled:form':
				$view_data['template'] = 'unstyled-form';
				break;
			case 'default:widget':
				$view_data['template'] = 'small-widget';
				break;
			case 'image-right:widget':
				$view_data['template']                                    = 'small-widget';
				$view_data['template_settings'][ $view_data['template'] ] = array( 'image_position' => 'right' );
				break;
			default:
				// Keep existing value; it's probably a custom template.
		}

		return $view_data;
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
			if ( 'form' === $view_data['mode'] ) {
				$type = 'form';
			} else {
				$type = 'content';
			}

			$view_data['template'] = "default:$type";
		} else {
			// Convert name; e.g. 'simple/testimonials.php'
			if ( 'widget/testimonials.php' === $view_data['template'] ) {
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
			$default_view        = Strong_Testimonials_Defaults::get_default_view();
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
		if ( isset( $view_data['slideshow_settings'] ) ) {
			return $view_data;
		}

		if ( 'scrollHorz' === $view_data['effect'] ) {
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

		return $view_data;
	}

	/**
	 * Convert 'all' to 'count'.
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_count( $view_data ) {
		if ( isset( $view_data['all'] ) ) {
			if ( $view_data['all'] ) {
				$view_data['count'] = -1;
			}
			unset( $view_data['all'] );
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

		return $view_data;
	}

	/**
	 * Prevent incompatible layouts.
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_layout( $view_data ) {
		if ( isset( $view_data['pagination'] ) && $view_data['pagination'] ) {
			if ( isset( $view_data['layout'] ) && 'masonry' === $view_data['layout'] ) {
				$view_data['layout'] = '';
			}
		}

		return $view_data;
	}

	/**
	 * Move word_count to excerpt_length for versions 2.10.0 to 2.11.3.
	 *
	 * @since 2.11.4
	 *
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
		if ( 'modern:content' === $view_data['template'] ) {
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
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public static function convert_title_link( $view_data ) {
		if ( ! isset( $view_data['title_link'] ) ) {
			$view_data['title_link'] = 'none';
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

			if ( 'on' === $nofollow ) {
				$new_value = 'yes';
			} elseif ( 1 === $nofollow ) {
				$new_value = 'yes';
			} elseif ( 'off' === $nofollow ) {
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
	 * Convert noopener from (on|off) to (1|0).
	 *
	 * @since 2.41.0
	 */
	public static function convert_noopener() {
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
			$noopener  = get_post_meta( $post->ID, 'noopener', true );
			$new_value = 'default';

			if ( 'on' === $noopener ) {
				$new_value = 'yes';
			} elseif ( 1 === $noopener ) {
				$new_value = 'yes';
			} elseif ( 'off' === $noopener ) {
				$new_value = 'no';
			} elseif ( 0 === $noopener ) {
				$new_value = 'no';
			} elseif ( is_bool( $noopener ) ) {
				if ( $noopener ) {
					$new_value = 'yes';
				} else {
					$new_value = 'default';
				}
			}

			update_post_meta( $post->ID, 'noopener', $new_value );
		}
	}

		/**
	 * Convert noreferrer from (on|off) to (1|0).
	 *
	 * @since 2.41.0
	 */
	public static function convert_noreferrer() {
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
			$noreferrer = get_post_meta( $post->ID, 'noreferrer', true );
			$new_value  = 'default';

			if ( 'on' === $noreferrer ) {
				$new_value = 'yes';
			} elseif ( 1 === $noreferrer ) {
				$new_value = 'yes';
			} elseif ( 'off' === $noreferrer ) {
				$new_value = 'no';
			} elseif ( 0 === $noreferrer ) {
				$new_value = 'no';
			} elseif ( is_bool( $noreferrer ) ) {
				if ( $noreferrer ) {
					$new_value = 'yes';
				} else {
					$new_value = 'default';
				}
			}

			update_post_meta( $post->ID, 'noreferrer', $new_value );
		}
	}

	/**
	 * Convert pagination settings.
	 *
	 * @since 2.28.0
	 *
	 * @param $view_data
	 *
	 * @return mixed
	 */
	public static function convert_pagination_type( $view_data ) {
		if ( isset( $view_data['pagination_type'] ) ) {
			$view_data['pagination_settings']['type'] = $view_data['pagination_type'];
			unset( $view_data['pagination_type'] );
		}

		if ( isset( $view_data['nav'] ) ) {
			$view_data['pagination_settings']['nav'] = $view_data['nav'];
			unset( $view_data['nav'] );
		}

		if ( isset( $view_data['per_page'] ) ) {
			$view_data['pagination_settings']['per_page'] = $view_data['per_page'];
			unset( $view_data['per_page'] );
		}

		return $view_data;
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

		$wordstring = implode( '', $allwords );

		return round( strlen( $wordstring ) / count( $allwords ) );
	}
}

new Strong_Testimonials_Updater();
