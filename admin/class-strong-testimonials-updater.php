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
	private $old_version;

	/**
	 * Log steps during update process.
	 *
	 * @var array
	 */
	private $new_log;

	/**
	 * Strong_Testimonials_Updater constructor.
	 */
	public function __construct() {
		$this->new_log     = array();
		$this->old_version = get_option( 'wpmtst_plugin_version', false );
		if ( $this->old_version ) {
			$this->log( __CLASS__, 'old: ' . $this->old_version . ' --> new: ' . WPMTST_VERSION );
		}
		else {
			$this->log( __CLASS__, 'NEW INSTALL', WPMTST_VERSION );
		}
	}

	/**
	 * Add a log entry.
	 *
	 * @param        $name
	 * @param string $entry
	 * @param string $var
	 */
	private function log( $name, $entry = '', $var = '' ) {
		if ( $name ) {
			$x = $name;
			if ( $entry ) {
				$x .= ' : ' . $entry;
				if ( $var ) {
					$x .= ' = ';
					if ( is_array( $var ) || is_object( $var ) ) {
						// log the text
						$this->new_log[] = $x;
						// then log the variable
						$this->new_log[] = $var;
					}
					else {
						$this->new_log[] = $x . $var;
					}
				}
				else {
					$this->new_log[] = $x;
				}
			}
			else {
				$this->new_log[] = $x;
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
	public function update() {
		if ( $this->old_version == WPMTST_VERSION ) {
			return;
		}

		if ( get_transient( 'wpmtst_update_in_progress' ) ) {
			return;
		}

		set_transient( 'wpmtst_update_in_progress', 1, 10 );

		// Redirect to About page afterwards. On new install or (de)activation only.
		if ( false === $this->old_version ) {
			add_option( 'wpmtst_do_activation_redirect', true );
		}

		/**
		 * Add custom capablities.
		 *
		 * @since 2.27.1
		 */
		$this->add_caps();

		/**
		 * Check DB version.
		 */
		$this->update_db_check();

		/**
		 * Let's start updating.
		 */
		$history = get_option( 'wpmtst_history', array() );

		/**
		 * Options.
		 */
		update_option( 'wpmtst_options', $this->update_options() );

		/**
		 * Custom fields.
		 */
		update_option( 'wpmtst_fields', $this->update_fields() );

		/**
		 * Forms.
		 */
		update_option( 'wpmtst_base_forms', $this->update_base_forms() );
		update_option( 'wpmtst_custom_forms', $this->update_custom_forms() );
		update_option( 'wpmtst_form_options', $this->update_form_options() );

		/**
		 * Compatibility options.
		 *
		 * @since 2.28.0
		 */
		update_option( 'wpmtst_compat_options', $this->update_compat_options() );

		/**
		 * Overwrite default view options.
		 *
		 * @since 2.15.0
		 */
		update_option( 'wpmtst_view_options', $this->update_view_options() );

		/**
		 * Overwrite default view settings.
		 *
		 * @since 2.15.0
		 */
		update_option( 'wpmtst_view_default', $this->update_default_view() );

		/**
		 * Update views.
		 */
		$this->update_views();

		/**
		 * Convert nofollow
		 */
		if ( ! isset( $history['2.23.0_convert_nofollow'] ) ) {
			$this->convert_nofollow();
			$this->update_history_log( '2.23.0_convert_nofollow' );
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
			delete_option( 'wpmtst_admin_notices' );
			delete_option( 'wpmtst_news_flag' );

			$this->update_history_log( '2.28_new_update_process' );
		}

		/**
		 * Update the plugin version.
		 */
		update_option( 'wpmtst_plugin_version', WPMTST_VERSION );

		/**
		 * Update log.
		 */
		$this->log( __FUNCTION__, 'complete' );
		$this->update_log();

		delete_transient( 'wpmtst_update_in_progress' );
	}

	/**
	 * Update the log in options table.
	 */
	public function update_log() {
		$log                            = get_option( 'wpmtst_update_log', array() );
		$log[ current_time( 'mysql' ) ] = $this->new_log;
		update_option( 'wpmtst_update_log', $log );
	}

	/**
	 * Return admin role.
	 *
	 * @since 2.27.0
	 *
	 * @return bool|null|WP_Role
	 */
	public function get_admins() {
		return get_role( 'administrator' );
	}

	/**
	 * Add custom capabilities.
	 *
	 * @since 2.27.1
	 */
	public function add_caps() {
		$admins = $this->get_admins();
		if ( $admins ) {
			$admins->add_cap( 'strong_testimonials_views' );
			$admins->add_cap( 'strong_testimonials_fields' );
			$admins->add_cap( 'strong_testimonials_options' );
			$admins->add_cap( 'strong_testimonials_about' );
			$this->log( __FUNCTION__, 'success' );
		}
		else {
			$this->log( __FUNCTION__, 'failed' );
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
	public function remove_caps() {
		if ( $admins = $this->get_admins() ) {
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
	public function update_db_check() {
		if ( get_option( 'wpmtst_db_version' ) != WPMST()->get_db_version() ) {
			$this->update_tables();
		}
		else {
			$this->log( __FUNCTION__, 'tables up to date' );
		}
	}

	/**
	 * Add tables for Views.
	 *
	 * @since 1.21.0
	 */
	public function update_tables() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$this->log( __FUNCTION__, 'start' );

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'strong_views';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL,
			value text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		$wpdb->show_errors();
		$result = dbDelta( $sql );
		$wpdb->hide_errors();

		if ( $wpdb->last_error ) {
			deactivate_plugins( 'strong-testimonials/strong-testimonials.php' );
			$message = '<p><span style="color: #CD0000;">';
			$message .= __( 'An error occurred.', 'strong-testimonials' ) . '</span>&nbsp;';
			$message .= __( 'The plugin has been deactivated.', 'strong-testimonials' ) . '&nbsp;';
			$message .= sprintf( __( 'Please <a href="%s" target="_blank">open a support ticket</a>.', 'strong-testimonials' ), esc_url( 'https://support.strongplugins.com/new-ticket/' ) ) . '</p>';
			$message .= '<p>' . sprintf( __( '<a href="%s">Go back to Dashboard</a>', 'strong-testimonials' ), esc_url( admin_url() ) ) . '</p>';

			$this->log( __FUNCTION__, 'error', $wpdb->last_error );
			$this->update_log();

			wp_die( sprintf( '<div class="error strong-view-error">%s</div>', $message ) );
		}

		update_option( 'wpmtst_db_version', WPMST()->get_db_version() );

		$this->log( __FUNCTION__, 'dbDelta', $result ? $result : 'no change' );
	}

	/**
	 * Update history log.
	 *
	 * @param $event
	 */
	public function update_history_log( $event ) {
		$history           = get_option( 'wpmtst_history', array() );
		$history[ $event ] = current_time( 'mysql' );
		$success           = update_option( 'wpmtst_history', $history );
		$this->log( __FUNCTION__, $event, $success ? 'record added' : 'record exists' );
	}

	/**
	 * Update options.
	 *
	 * @return array
	 */
	public function update_options() {
		$options = get_option( 'wpmtst_options' );
		if ( ! $options ) {
			$this->log( __FUNCTION__, 'default' );
			return Strong_Testimonials_Defaults::get_options();
		}

		/**
		 * Remove version 1 options
		 */
		if ( version_compare( '2.0', $this->old_version ) ) {

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

		$this->log( __FUNCTION__, 'done' );

		return $options;
	}

	/**
	 * Custom fields
	 *
	 * @return array
	 */
	public function update_fields() {
		$fields = get_option( 'wpmtst_fields', array() );
		if ( ! $fields ) {
			$this->log( __FUNCTION__, 'default' );
			return Strong_Testimonials_Defaults::get_fields();
		}

		/**
		 * Updating from 1.x
		 *
		 * Copy current custom fields to the new default custom form which will be added in the next step.
		 *
		 * @since 2.0.1
		 * @since 2.17 Added version check.
		 */
		if ( version_compare( '2.0', $this->old_version ) ) {
			if ( isset( $fields['field_groups'] ) ) {
				$default_custom_forms[1]['fields'] = $fields['field_groups']['custom']['fields'];
				unset( $fields['field_groups'] );
			}
			if ( isset( $fields['current_field_group'] ) ) {
				unset( $fields['current_field_group'] );
			}
		}

		$this->log( __FUNCTION__, 'done' );

		return $fields;
	}

	/**
	 * Base forms.
	 *
	 * @return array
	 */
	public function update_base_forms() {
		$this->log( __FUNCTION__, 'default' );
		return Strong_Testimonials_Defaults::get_base_forms();
	}

	/**
	 * Custom forms.
	 *
	 * @return array
	 */
	public function update_custom_forms() {
		$custom_forms = get_option( 'wpmtst_custom_forms' );
		if ( ! $custom_forms ) {
			$this->log( __FUNCTION__, 'default' );
			return Strong_Testimonials_Defaults::get_custom_forms();
		}

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
				$fields      = get_option( 'wpmtst_fields', array() );

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

		$this->log( __FUNCTION__, 'convert forms', 'done' );

		return $custom_forms;
	}

	/**
	 * Form options.
	 *
	 * @return array
	 */
	public function update_form_options() {
		$form_options = get_option( 'wpmtst_form_options' );
		if ( ! $form_options ) {
			$this->log( __FUNCTION__, 'default' );
			return Strong_Testimonials_Defaults::get_form_options();
		}

		$options = get_option( 'wpmtst_options', array() );

		/**
		 * Move existing options.
		 */
		if ( isset( $options['admin_notify'] ) ) {
			$form_options['admin_notify'] = $options['admin_notify'];
			unset( $options['admin_notify'] );

			$form_options['admin_email'] = $options['admin_email'];
			unset( $options['admin_email'] );

			$form_options['captcha'] = $options['captcha'];
			unset( $options['captcha'] );

			$form_options['honeypot_before'] = $options['honeypot_before'];
			unset( $options['honeypot_before'] );

			$form_options['honeypot_after'] = $options['honeypot_after'];
			unset( $options['honeypot_after'] );

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

		// Merge in new options
		$form_options = array_merge( Strong_Testimonials_Defaults::get_form_options(), $form_options );

		$this->log( __FUNCTION__, 'done' );

		return $form_options;
	}

	/**
	 * Compatibility options.
	 *
	 * @since 2.28.0
	 *
	 * @return array
	 */
	public function update_compat_options() {
		$options = get_option( 'wpmtst_compat_options' );
		if ( ! $options ) {
			$this->log( __FUNCTION__, 'default' );
			return Strong_Testimonials_Defaults::get_compat_options();
		}

		// Merge in new options.
		$defaults = Strong_Testimonials_Defaults::get_compat_options();
		// Merge nested arrays individually. Don't use array_merge_recursive.
		$options['ajax'] = array_merge( $defaults['ajax'], $options['ajax'] );
		$options         = array_merge( $defaults, $options );

		$this->log( __FUNCTION__, 'done' );

		return $options;
	}

	/**
	 * View options.
	 *
	 * @return array
	 */
	public function update_view_options() {
		$this->log( __FUNCTION__, 'default' );
		return Strong_Testimonials_Defaults::get_view_options();
	}

	/**
	 * Default view.
	 *
	 * @return array
	 */
	public function update_default_view() {
		$this->log( __FUNCTION__, 'default' );
		return apply_filters( 'wpmtst_view_default', Strong_Testimonials_Defaults::get_default_view() );
	}

	/**
	 * Update views.
	 */
	public function update_views() {
		$views = wpmtst_get_views();

		if ( ! $views ) {
			$this->log( __FUNCTION__, 'no views found' );
			return;
		}

		$default_view = get_option( 'wpmtst_view_default' );
		$history      = get_option( 'wpmtst_history', array() );

		foreach ( $views as $key => $view ) {

			$view_data = unserialize( $view['value'] );
			if ( ! is_array( $view_data ) ) {
				$this->log( __FUNCTION__, 'view ' . $view['id'] . ' data is not an array' );
				continue;
			}

			if ( isset( $history['2.28_new_update_process'] ) ) {
				$this->log( __FUNCTION__, 'already been thru 2.28 update process' );
			}
			else {
				/**
				 * Compat mode no longer needed.
				 *
				 * @since 2.22.0
				 */
				unset( $view_data['compat'] );

				$view_data = $this->convert_template_name( $view_data );
				$view_data = $this->convert_count( $view_data );
				$view_data = $this->convert_background_color( $view_data );
				$view_data = $this->convert_form_ajax( $view_data );
				$view_data = $this->convert_layout( $view_data );
				$view_data = $this->convert_word_count( $view_data );
				$view_data = $this->convert_excerpt_length( $view_data );
				$view_data = $this->convert_more_text( $view_data );
				$view_data = $this->convert_modern_title( $view_data );
				$view_data = $this->convert_slideshow( $view_data );
				$view_data = $this->convert_title_link( $view_data );
				$view_data = $this->convert_pagination_type( $view_data );
			}

			// Merge in new default values.
			$view['data'] = array_merge( $default_view, $view_data );

			// Merge nested arrays individually. Don't use array_merge_recursive.

			$view['data']['background'] = array_merge( $default_view['background'], $view_data['background'] );

			if ( isset( $view_data['pagination_settings'] ) ) {
				$view['data']['pagination_settings'] = array_merge( $default_view['pagination_settings'], $view_data['pagination_settings'] );

				/**
				 * Attempt to repair bug from 2.28.2
				 *
				 * @since 2.28.3
				 */
				if ( ! isset( $view['data']['pagination_settings']['end_size'] ) || ! $view['data']['pagination_settings']['end_size'] ) {
					$view['data']['pagination_settings']['end_size'] = 1;
					$this->log( __FUNCTION__, 'fix end_size' );
				}
				if ( ! isset( $view['data']['pagination_settings']['mid_size'] ) || ! $view['data']['pagination_settings']['mid_size'] ) {
					$view['data']['pagination_settings']['mid_size'] = 2;
					$this->log( __FUNCTION__, 'fix mid_size' );
				}
				if ( ! isset( $view['data']['pagination_settings']['per_page'] ) || ! $view['data']['pagination_settings']['per_page'] ) {
					$view['data']['pagination_settings']['per_page'] = 5;
					$this->log( __FUNCTION__, 'fix per_page' );
				}

			}
			else {
				$view['data']['pagination_settings'] = $default_view['pagination_settings'];
			}

			if ( isset( $view_data['slideshow_settings'] ) ) {
				$view['data']['slideshow_settings'] = array_merge( $default_view['slideshow_settings'], $view_data['slideshow_settings'] );
			}
			else {
				$view['data']['slideshow_settings'] = $default_view['slideshow_settings'];
			}
			ksort( $view['data']['slideshow_settings'] );

			$success = wpmtst_save_view( $view );

		} // foreach $view

		$this->log( __FUNCTION__, 'done' );
	}

	/**
	 * Update template naming structure.
	 *
	 * @param $view_data
	 *
	 * @return array
	 */
	public function convert_template_name( $view_data ) {
		// Change default template from empty to 'default:{type}'
		if ( ! $view_data['template'] ) {
			if ( 'form' == $view_data['mode'] ) {
				$type = 'form';
			}
			else {
				$type = 'content';
			}

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
	public function convert_excerpt_length( $view_data ) {
		if ( ! isset( $view_data['excerpt_length'] ) || ! $view_data['excerpt_length'] ) {
			$default_view        = Strong_Testimonials_Defaults::get_default_view();
			$average_word_length = $this->get_average_word_length();

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
			$this->log( __FUNCTION__, 'done' );
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
	public function convert_more_text( $view_data ) {
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
			$this->log( __FUNCTION__, 'done' );
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
	public function convert_slideshow( $view_data ) {
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

			$this->log( __FUNCTION__, 'done' );
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
	public function convert_count( $view_data ) {
		if ( - 1 == $view_data['count'] ) {
			$view_data['count'] = 1;
			$view_data['all']   = 1;
			$this->log( __FUNCTION__, 'done' );
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
	public function convert_background_color( $view_data ) {
		if ( ! is_array( $view_data['background'] ) ) {
			$view_data['background'] = array(
				'color' => $view_data['background'],
				'type'  => 'single',
			);
			$this->log( __FUNCTION__, 'done' );
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
	public function convert_form_ajax( $view_data ) {
		if ( isset( $view_data['form-ajax'] ) ) {
			$view_data['form_ajax'] = $view_data['form-ajax'];
			unset( $view_data['form-ajax'] );
			$this->log( __FUNCTION__, 'done' );
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
	public function convert_layout( $view_data ) {
		if ( isset( $view_data['pagination'] ) && $view_data['pagination'] ) {
			if ( isset( $view_data['layout'] ) && 'masonry' == $view_data['layout'] ) {
				$view_data['layout'] = '';
				$this->log( __FUNCTION__, 'done' );
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
	public function convert_word_count( $view_data ) {
		if ( isset( $view_data['word_count'] ) ) {
			$view_data['excerpt_length'] = $view_data['word_count'];
			unset( $view_data['word_count'] );
			$this->log( __FUNCTION__, 'done' );
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
	public function convert_modern_title( $view_data ) {
		if ( 'modern:content' == $view_data['template'] ) {
			if ( ! isset( $history['2.12.4_convert_modern_template'] ) ) {
				$view_data['title'] = 0;
				$this->update_history_log( '2.12.4_convert_modern_template' );
				$this->log( __FUNCTION__, 'done' );
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
	public function convert_title_link( $view_data ) {
		if ( ! isset( $view_data['title_link'] ) ) {
			$view_data['title_link'] = 0;
			$this->log( __FUNCTION__, 'done' );
		}

		return $view_data;
	}

	/**
	 * Convert nofollow from (on|off) to (1|0).
	 *
	 * @since 2.23.0
	 */
	public function convert_nofollow() {
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
			}
			elseif ( 1 === $nofollow ) {
				$new_value = 'yes';
			}
			elseif ( 'off' == $nofollow ) {
				$new_value = 'no';
			}
			elseif ( 0 === $nofollow ) {
				$new_value = 'no';
			}
			elseif ( is_bool( $nofollow ) ) {
				if ( $nofollow ) {
					$new_value = 'yes';
				}
				else {
					$new_value = 'default';
				}
			}

			update_post_meta( $post->ID, 'nofollow', $new_value );
		}
		$this->log( __FUNCTION__, 'done' );
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
	public function convert_pagination_type( $view_data ) {
		if ( isset( $view_data['pagination_type'] ) ) {
			$view_data['pagination_settings']['type'] = $view_data['pagination_type'];
			unset( $view_data['pagination_type'] );
			$this->log( __FUNCTION__, 'type', $view_data['pagination_settings']['type'] );
		}

		if ( isset( $view_data['nav'] ) ) {
			$view_data['pagination_settings']['nav'] = $view_data['nav'];
			unset( $view_data['nav'] );
			$this->log( __FUNCTION__, 'nav', $view_data['pagination_settings']['nav'] );
		}

		if ( isset( $view_data['per_page'] ) ) {
			$view_data['pagination_settings']['per_page'] = $view_data['per_page'];
			unset( $view_data['per_page'] );
			$this->log( __FUNCTION__, 'per_page', $view_data['pagination_settings']['per_page'] );
		}

		return $view_data;
	}

	/**
	 * Convert length to excerpt_length.
	 *
	 * @since 2.10.0
	 */
	public function get_average_word_length() {
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

}
