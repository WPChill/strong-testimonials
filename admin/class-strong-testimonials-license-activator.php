<?php

if ( ! class_exists( 'Strong_Testimonials_Master_License_Activator' ) ) {

	class Strong_Testimonials_Master_License_Activator {

		/**
		 * The name of the plugin.
		 *
		 * @var string
		 */
		private $main_item_name = 'Strong Testimonials';

		/**
		 * The ID of the add-on present in all the license keys.
		 *
		 * @var int
		 */
		private $main_item_id   = 13054; // The id of the ST PRO addon as it appears in every license key.

		/**
		 * The single instance of the class.
		 *
		 * @var Strong_Testimonials_Master_License_Activator
		 */
		public static $instance = null;

		/**
		 * The license key.
		 *
		 * @var null
		 */
		public $license = null;

		/**
		 * License status
		 *
		 * @var mixed
		 */
		public $status = false;

		/**
		 * Strong_Testimonials_Master_License_Activator constructor.
		 */
		public function __construct() {

			add_action( 'admin_init', array( $this, 'register_license_option' ) );
			// Don't need this for the moment, as we do it AJAX style. @todo: Delete commented lines in the future.
			//add_action( 'admin_init', array( $this, 'activate_license' ) );
			//add_action( 'admin_init', array( $this, 'deactivate_license' ) );
			add_action( 'wpmtst_license_errors', array( $this, 'admin_notices' ) );
			add_action( 'wp_ajax_wpmtst_license_action', array( $this, 'ajax_license_action' ) );
			add_action( 'wp_ajax_wpmtst_forgot_license', array( $this, 'ajax_forgot_license' ) );
			// retrieve the license from the database.
			$this->license = trim( get_option( 'strong_testimonials_license_key', false ) );
			$this->status  = get_option( 'strong_testimonials_license_status', false );
		}

		/**
		 * Get the instance of the class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Strong_Testimonials_Master_License_Activator ) ) {
				self::$instance = new Strong_Testimonials_Master_License_Activator();
			}

			return self::$instance;
		}

		/**
		 * License activation function.
		 *
		 * @return void
		 */
		public function activate_license() {

			// listen for our activate button to be clicked.
			if ( isset( $_POST['strong_testimonials_license_activate'] ) ) {

				// run a quick security check.
				if ( ! check_admin_referer( 'strong_testimonials_license_nonce', 'strong_testimonials_license_nonce' ) ) {
					return;
				}
				$extensions = $this->get_installed_extensions();
				$this->force_license_activation( true, $extensions );
			}
		}

		/**
		 * Deactivate license
		 *
		 * @return void
		 */
		public function deactivate_license() {

			// listen for our deactivate button to be clicked.
			if ( isset( $_POST['strong_testimonials_license_deactivate'] ) ) {
				// run a quick security check.
				if ( ! check_admin_referer( 'strong_testimonials_license_nonce', 'strong_testimonials_license_nonce' ) ) {
					return; // get out if we didn't click the Activate button.
				}
				$extensions = $this->get_installed_extensions();
				$this->force_license_deactivation( true, $extensions );
				wp_redirect( admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=licenses' ) );
				exit();
			}
		}

		/**
		 * Force license activation.
		 *
		 * @param bool   $regular_action Is this a regular activation or a forced one.
		 * @param array  $extensions     List of extensions to activate.
		 * @param string $action_status  The action status to send to the API.
		 *
		 * @return void
		 */
		public function force_license_activation( $regular_action = false, $extensions = array(), $action_status = 'activate' ) {

			// AJAX or regular action, license must be set.
			if ( isset( $_POST['license'] ) ) {
				$license = sanitize_text_field( $_POST['license'] );
			} else {
				$license = $this->license;
			}

			if ( ! $license ) {
				if ( ! $regular_action ) {
					return;
				} else {
					exit;
				}
			}

			// data to send in our API request.
			$api_params = array(
				'edd_action'      => 'activate_license',
				'license'         => $license,
				'item_id'         => $this->main_item_id,
				'url'             => home_url(),
				'extensions'      => implode( ',', $extensions ),
				'action_status'   => $action_status,
			);

			// Call the custom API.
			$response = wp_remote_post(
				WPMTST_STORE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				// If it's not a regular action return as it's most probably plugin deactivation.
				if ( ! $regular_action ) {
					return;
				}
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'strong-testimonials-pro' );
				}
			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				if ( false === $license_data->success ) {
					switch ( $license_data->error ) {
						case 'expired':
							$message = sprintf(
								__( 'Your license key expired on %s.', 'strong-testimonials-pro' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;
						case 'disabled':
						case 'revoked':
							$message = __( 'Your license key has been disabled.', 'strong-testimonials-pro' );
							break;
						case 'missing':
							$message = __( 'Invalid license.', 'strong-testimonials-pro' );
							break;
						case 'invalid':
						case 'site_inactive':
							$message = __( 'Your license is not active for this URL.', 'strong-testimonials-pro' );
							break;
						case 'item_name_mismatch':
							$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'strong-testimonials-pro' ), $this->main_item_name );
							break;
						case 'no_activations_left':
							$message = __( 'Your license key has reached its activation limit.', 'strong-testimonials-pro' );
							break;
						default:
							$message = __( 'An error occurred, please try again.', 'strong-testimonials-pro' );
							break;
					}
				}
			}

			if ( ! $regular_action ) {
				return;
			}

			// Check if anything passed on a message constituting a failure.
			if ( ! empty( $message ) ) {
				wp_send_json_error( array( 'message' => $message ) );
			}

			// $license_data->license will be either "valid" or "invalid"
			update_option( 'strong_testimonials_license_status', $license_data );
			wp_send_json_success(
				array(
					'message' => __( 'License activated.', 'strong-testimonials' )
				)
			);
			exit;
		}

		/**
		 * Force license deactivation
		 *
		 * @param bool   $regular_action Is this a regular deactivation or a forced one.
		 * @param array  $extensions     List of extensions to deactivate.
		 * @param string $action_status  The action status to send to the API.
		 *
		 * @return void
		 */
		public function force_license_deactivation( $regular_action = false, $extensions = array(), $action_status = 'deactivate' ) {

			// AJAX or regular action, license must be set.
			if ( isset( $_POST['license'] ) ) {
				$license = sanitize_text_field( $_POST['license'] );
			} else {
				$license = $this->license;
			}

			if ( ! $license ) {
				if ( ! $regular_action ) {
					return;
				} else {
					exit;
				}
			}

			// data to send in our API request.
			$api_params = array(
				'edd_action'      => 'deactivate_license',
				'license'         => $this->license,
				'item_id'         => $this->main_item_id,
				'url'             => home_url(),
				'extensions'      => implode( ',', $extensions ),
				'action_status'   => $action_status,
			);

			// Call the custom API.
			$response = wp_remote_post(
				WPMTST_STORE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				// If it's not a regular action it means it's most probably on plugin deactivation.
				if ( ! $regular_action ) {
					return;
				}
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'strong-testimonials-pro' );
				}

				wp_send_json_error( array( 'success' => false, 'message' => $message ) );
				exit;
			}

			// decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' ) {
				delete_option( 'strong_testimonials_license_status' );
			}

			if ( $regular_action ) {
				wp_send_json_success(
					array(
						'success' => true,
						'message' => __( 'License deactivated.', 'strong-testimonials' )
					)
				);
				exit;
			}
		}

		/**
		 * Register the license option.
		 *
		 * @return void
		 */
		public function register_license_option() {
			// creates our settings in the options table.
			register_setting( 'strong_testimonials_license_key', 'strong_testimonials_license_key', array(
				$this,
				'sanitize_license'
			) );
		}

		/**
		 * Sanitize the license key.
		 *
		 * @param string $new License value.
		 *
		 * @return string
		 */
		public function sanitize_license( $new ) {
			$old = get_option( 'strong_testimonials_license_key' );
			if ( $old && $old != $new ) {
				delete_option( 'strong_testimonials_license_status' ); // new license has been entered, so must reactivate
				delete_transient( 'strong_testimonials_licensed_extensions' );
			}

			return $new;
		}

		/**
		 * Admin notices for errors.
		 *
		 * @return void
		 */
		public function admin_notices() {
			if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
				switch ( $_GET['sl_activation'] ) {
					case 'false':
						$message = urldecode( $_GET['message'] );
						?>
						<div class="error">
							<p><?php echo esc_html( $message ); ?></p>
						</div>
						<?php
						break;
					case 'true':
					default:
						// Developers can put a custom success message here for when activation is successful if they way.
						break;
				}
			}
		}

		/**
		 * Retrieve installed extensions
		 *
		 * @return array
		 */
		public function get_installed_extensions() {
			// Get all installed extensions.
			$plugins    = get_option( 'active_plugins' );
			$extensions = array();
			if ( ! empty( $plugins ) ) {
				foreach ( $plugins as $plugin ) {
					// Search only for Strong Testimonials extensions.
					if ( false !== strpos( $plugin, 'strong-testimonials-' ) ) {
						$extensions[] = basename( $plugin, '.php' );
					}
				}
			}

			return $extensions;
		}

		/**
		 * AJAX activate-deactivate license
		 *
		 * @return void
		 */
		public function ajax_license_action() {
			// run a quick security check.
			if ( ! isset( $_POST['nonce'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Nonce not set', 'strong-testimonials' )
					)
				);
			}

			check_admin_referer( 'strong_testimonials_license_nonce', 'nonce' );

			if ( ! isset( $_POST['click_action'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Action not set', 'strong-testimonials' )
					)
				);
			}

			if ( ! isset( $_POST['license'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'License not set', 'strong-testimonials' )
					)
				);
			}

			$action = sanitize_text_field( $_POST['click_action'] );

			if ( 'activate' === $action ) {
				update_option( 'strong_testimonials_license_key', sanitize_text_field( $_POST['license'] ) );
				if ( isset( $_POST['email'] ) ) {
					update_option( 'strong_testimonials_email', sanitize_text_field( $_POST['email'] ) );
				}
				$extensions = $this->get_installed_extensions();
				$this->force_license_activation( true, $extensions );
			} else {
				$extensions = $this->get_installed_extensions();
				$this->force_license_deactivation( true, $extensions );
			}

			$activated_message   = __( 'License successfully activated', 'strong-testimonials' );
			$deactivated_message = __( 'License successfully deactivated', 'strong-testimonials' );

			wp_send_json_success(
				array(
					'success' => true,
					'message' => ( 'activate' === $action ) ? $activated_message : $deactivated_message,
				)
			);
			exit;
		}

		/**
		 * Forgot license functionality.
		 *
		 * @return void
		 */
		public function ajax_forgot_license() {

			// run a quick security check.
			if ( ! isset( $_POST['nonce'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Nonce not set', 'strong-testimonials' )
					)
				);
			}

			if ( ! isset( $_POST['email'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Email not set', 'strong-testimonials' )
					)
				);
			}

			check_admin_referer( 'strong_testimonials_license_nonce', 'nonce' );
			$email = sanitize_email( wp_unslash( $_POST['email'] ) );

			// data to send in our API request.
			$api_params = array(
				'edd_action' => 'forgot_license',
				'url'        => home_url(),
				'email'      => $email

			);

			// Call the custom API.
			$response = wp_remote_post(
				WPMTST_STORE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				// If it's not a regular action it means it's most probably on plugin deactivation.
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'strong-testimonials-pro' );
				}
				wp_send_json_error( array( 'message' => $message ) );
			}
			$json_response = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( $json_response['success'] ) {
				wp_send_json_success( array( 'message' => $json_response['message'] ) );
			}
		}
	}

	Strong_Testimonials_Master_License_Activator::get_instance();
}
