<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 */
if ( ! class_exists( 'Strong_Testimonials_WPChill_Upsells' ) ) {

	class Strong_Testimonials_WPChill_Upsells {
		/**
		 * Holds the class object.
		 *
		 * @since 2.5.2
		 *
		 * @var object
		 */
		public static $instance;

		/**
		 * The server from which we get our info
		 *
		 * @since 2.5.2
		 *
		 * @var string
		 */
		private $shop_url = '';

		/**
		 * Plugin slug
		 *
		 * @since 2.5.2
		 *
		 * @var string
		 */
		private $slug = '';

		/**
		 * Our packages
		 *
		 * @since 2.5.2
		 *
		 * @var string
		 */
		private $packages = array();

		/**
		 * License Status
		 *
		 * @since 2.5.2
		 *
		 * @var string
		 */
		private $license = false;

		/**
		 * URL endpoints
		 *
		 * @since 2.5.2
		 *
		 * @var array
		 */
		private $endpoints = array(
			'checkout' => 'checkout',
			'pricing'  => 'pricing',
			'base'     => 'wp-json/wpchill/v1/',
		);

		private $upsell_extensions = array();

		/**
		 * Primary class constructor.
		 *
		 * $args : array
		 *
		 * @since 2.5.2
		 */
		public function __construct( $args ) {

			// Check for 'strong_testimonials_license_key' option and delete 'upgradable_packages' transient
			add_action( 'updated_option', array( $this, 'delete_upgradable_packages_transients' ), 15 );

			$this->license = self::check_for_license();

			if ( ! isset( $args['slug'] ) ) {
				return;
			}

			if ( ! isset( $args['shop_url'] ) ) {
				return;
			}

			$this->slug     = $args['slug'];
			$this->shop_url = $args['shop_url'];

			if ( isset( $args['endpoints'] ) && ! empty( $args['endpoints'] ) ) {
				$this->endpoints = wp_parse_args( $args['endpoints'], $this->endpoints );
			}

			$this->fetch_packages();
		}

		/**
		 * Lets check for license
		 *
		 * @return bool
		*
		* @since 2.5.2
		* @since 2.5.6 moved here from class-modula-pro-upsells.php.
		*/
		public static function check_for_license() {

			$license_status = get_option( 'strong_testimonials_license_status' );

			// If the option is false it means it is empty
			if ( ! $license_status ) {
				return null;
			}

			// There is no license or license is not valid anymore, so we get all packages
			if ( 'valid' !== $license_status->license ) {
				return false;
			}

			return true;
		}

		/**
		 * Returns the singleton instance of the class.
		 *
		 * @return object The Strong_Testimonials_WPChill_Upsells object.
		 * @since 2.5.2
		 *
		 */
		public static function get_instance( $args ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Strong_Testimonials_WPChill_Upsells ) ) {
				self::$instance = new Strong_Testimonials_WPChill_Upsells( $args );
			}

			return self::$instance;
		}

		/**
		 * Get transient names based on plugin slug
		 *
		 * @since 1.0.0
		 */
		public function get_transient( $name ) {
			return $this->slug . '_' . $name;
		}

		/**
		 * Get REST API route
		 *
		 * @since 1.0.0
		 */
		public function get_route( $name ) {
			return trailingslashit( $this->shop_url ) . trailingslashit( $this->endpoints['base'] ) . $name;
		}

		/**
		 * Fetch all packages
		 *
		 * @since 2.5.2
		 */
		public function fetch_packages() {

			$rest_calls = array(
				'packages' => 'all_packages',
				'route'    => 'get-packages',
			);

			if ( $this->license ) {
				$rest_calls['packages'] = 'upgradable_packages';

				// Transient doesn't exist so we make the call
				$url         = preg_replace( '/\?.*/', '', get_bloginfo( 'url' ) );
				$license_key = get_option( 'strong_testimonials_license_key' );
				$query_var   = 'get-upgrade?license=' . $license_key . '&url=' . $url;

				$rest_calls['route'] = $query_var;
			}

			// Lets get the transient
			$packages_transient = get_transient( $this->get_transient( $rest_calls['packages'] ) );

			//If the transient exists then we will not make another call to the main server
			if ( $packages_transient && ! empty( $packages_transient ) ) {
				$this->packages          = $packages_transient;
				$this->upsell_extensions = $this->get_extensions_upsell( $this->packages );

				return;
			}

			$query_var = $rest_calls['route'];

			// Transient doesn't exist so we make the call
			$response = wp_remote_get( $this->get_route( $query_var ) );

			if ( ! is_wp_error( $response ) ) {

				// Decode the data that we got.
				$data = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( ! empty( $data ) && is_array( $data ) ) {
					$this->packages          = $data;
					$this->upsell_extensions = $this->get_extensions_upsell( $this->packages );
					set_transient( $this->get_transient( $rest_calls['packages'] ), $this->packages, '86400' );
				}
			}
		}

		/**
		 * Get list of extensions for upsell
		 *
		 * @return array
		 */
		public function get_extensions_upsell( $packages ) {

			$upsells = array();

			if ( isset( $packages['upsell_packages'] ) ) {

				foreach ( $packages['upsell_packages'] as $package ) {

					if ( isset( $package['extensions'] ) ) {
						$upsells = array_merge( $upsells, $package['extensions'] );
					}
				}

				// Unset the addons we currently have in our current package if any
				if ( isset( $packages['current_package'] ) && ! empty( $packages['current_package'] ) ) {

					foreach ( $packages['current_package']['extensions'] as $key => $addon ) {

						// Search and unset
						if ( isset( $upsells[ $key ] ) ) {

							unset( $upsells[ $key ] );
						}
					}
				}
			}

			return $upsells;
		}


		/**
		 * Check to see if addon is in our upgrade list
		 *
		 * @param $addon
		 *
		 * @return bool
		 *
		 * @since 2.5.2
		 */
		public function is_upgradable_addon( $addon = false ) {

			if ( ! $addon ) {
				return false;
			}

			if ( ! empty( $this->upsell_extensions ) ) {

				if ( isset( $this->upsell_extensions[ $addon ] ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Get our packages
		 *
		 * @return string
		 *
		 * @since 2.5.2
		 */
		public function get_packages() {

			return $this->packages;
		}

		/**
		 * Sort packages based on price
		 *
		 * @return int
		 * @since 2.5.2
		 *
		 */
		public static function sort_data_by_price( $a, $b ) {

			if ( ! isset( $a['upgrade_price'], $b['upgrade_price'] ) ) {
				return - 1;
			}
			if ( (int) $a['upgrade_price'] === (int) $b['upgrade_price'] ) {
				return 0;
			}

			return $a['upgrade_price'] > $b['upgrade_price'] ? - 1 : 1;
		}

		/**
		 * The Current Package vs Upgrade Package page
		 *
		 * @param $pro_features
		 *
		 * @since 2.5.2
		 */
		public function lite_vs_premium( $pro_features ) {

			$upsell_packages = array();
			$addons          = array();

			$lite_plan['strong-testimonials-lite'] = array(
				'name' => esc_html__( 'Strong Testimonials - LITE', 'strong-testimonials' ),
			);

			$packages = $this->get_packages();

			if ( isset( $packages['current_package'] ) && ! empty( $packages['current_package'] ) ) {

				// Add current plas as LITE plan
				$lite_plan[ $packages['current_package']['slug'] ] = $packages['current_package'];
				// Unset the LITE plan as there is not need for comparison
				unset( $lite_plan['strong-testimonials-lite'] );
				// No PRO Features needed in comparison as they all have them
				$pro_features = false;
			}

			if ( isset( $packages['upsell_packages'] ) ) {
				$upsell_packages = $packages['upsell_packages'];
			}

			// Lets sort them by price, higher is better
			uasort( $upsell_packages, array( 'Strong_Testimonials_WPChill_Upsells', 'sort_data_by_price' ) );

			// Get our extensions from the heighest paid package as it has all of them
			// Also we need to reverse the addons so that they appear in a cascade
			if ( isset( array_values( $upsell_packages )[0]['extensions'] ) ) {

				$addons = array_reverse( array_values( $upsell_packages )[0]['extensions'] );

				// Unset the PRO from extensions
				unset( $addons['strong-testimonials'] );
			}

			// Unset lifetime packages if any.
			foreach ( $upsell_packages as $key => $package ) {
				if ( false !== strpos( strtolower( $key ), 'lifetime' ) ) {
					unset( $upsell_packages[ $key ] );
				}
			}

			$all_packages = array_merge( $upsell_packages, $lite_plan );

			// Make the size of the element based on number of addons
			if ( count( $upsell_packages ) > 0 ) {
				echo '<style>.wpchill-pricing-package {width:' . ( intval( 100 / ( count( $upsell_packages ) + 2 ) ) - 1 ) . '%}.wpchill-plans-table.table-header .wpchill-pricing-package:last-child:before{content:"' . esc_html__( 'Current package', 'strong-testimonials' ) . '";}</style>';
			}
			?>

			<div class="wpchill-plans-table table-header">
				<div class="wpchill-pricing-package wpchill-empty">
					<!--This is an empty div so that we can have an empty corner-->
				</div>
				<?php
				// Let's do the sites and priority rows
				$sites    = '';
				$priority = '';
				foreach ( $all_packages as $slug => $package ) {

					if ( ! empty( $package['extra_features'] ) ) {
						foreach ( $package['extra_features'] as $key => $value ) {
							if ( 'sites' === $key ) {
								$sites .= '<div class="wpchill-pricing-package">' . $value . '</div>';
							} else {
								$priority .= '<div class="wpchill-pricing-package">' . $value . '</div>';
							}
						}
					} else {
						$sites    .= '<div class="wpchill-pricing-package">-</div>';
						$priority .= '<div class="wpchill-pricing-package">w.org Forums</div>';
					}

					?>
					<div class="wpchill-pricing-package wpchill-title wpchill-<?php echo esc_attr( $slug ); ?>">
						<!--Usually the names are "Plugin name - Package" so we make the explode -->
						<p class="wpchill-name"><strong><?php echo esc_html( isset( explode( '-', $package['name'] )[1] ) ? explode( '-', $package['name'] )[1] : $package['name'] ); ?></strong></p>
						<?php

						// Lets display the price and other info about our packages
						if ( isset( $package['upgrade_price'] ) && 'strong-testimonials-lite' !== $slug ) {
							$price        = number_format( $package['upgrade_price'], 2 );
							$price_parts  = explode( '.', $price );
							$normal_price = false;

							if ( isset( $package['normal_price'] ) ) {
								$normal_price       = number_format( $package['normal_price'], 2 );
								$normal_price_parts = explode( '.', $normal_price );
							}

							$checkout_page = trailingslashit( $this->shop_url ) . $this->endpoints['checkout'];

							$url = add_query_arg(
								array(
									'edd_action'   => 'add_to_cart',
									'download_id'  => $package['id'],
									'utm_source'   => 'upsell',
									'utm_medium'   => 'litevspro',
									'utm_campaign' => $slug,
								),
								$checkout_page
							);

							$buy_button = apply_filters(
								'wpchill-st-upsells-buy-button', // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
								array(
									'url'   => $url,
									'label' => esc_html__( 'Upgrade', 'strong-testimonials' ),
								),
								$slug,
								$package,
								$this
							);

							?>
							<p class="description"><?php echo ( isset( $package['excerpt'] ) ) ? esc_html( $package['excerpt'] ) : ' '; ?></p>
							<div class="wpchill-price">
							<?php if ( $normal_price ) { ?>
								<p class="old-price"><?php echo '$<strong>' . esc_html( $normal_price_parts[0] ) . '</strong><sup>.' . esc_html( $normal_price_parts[1] ) . '</sup>'; ?></p>
							<?php } ?>

							<p><?php echo '<sup>$</sup><strong>' . esc_html( $price_parts[0] ) . '</strong><sup>.' . esc_html( $price_parts[1] ) . '</sup>'; ?></p>

							</div>
							<a href="<?php echo esc_url( $buy_button['url'] ); ?>" target="_blank" class="button button-primary button-hero">
								<?php echo esc_html( $buy_button['label'] ); ?>
							</a>
						<?php } ?>

					</div>
				<?php } ?>
			</div>

			<div class="wpchill-plans-table">
				<div class="wpchill-pricing-package feature-name">
					<?php echo esc_html__( 'Sites', 'strong-testimonials' ); ?>
				</div>
				<?php echo wp_kses_post( $sites ); ?>
			</div>

			<div class="wpchill-plans-table">
				<div class="wpchill-pricing-package feature-name">
					<?php echo esc_html__( 'Support', 'strong-testimonials' ); ?>
				</div>
				<?php echo wp_kses_post( $priority ); ?>
			</div>
			<?php

			// Now lets loop through each addon described in LITE version of the plugin
			foreach ( $addons as $key => $addon ) {
				?>
				<div class="wpchill-plans-table">
					<div class="wpchill-pricing-package feature-name">
						<?php echo esc_html( $addon['name'] ); ?>
						<?php
						if ( isset( $addon['description'] ) ) {
							?>
							<div class="tab-header-tooltip-container wpmtst-tooltip"><span>[?]</span>
								<div class="tab-header-description wpmtst-tooltip-content"><?php echo wp_kses_post( $addon['description'] ); ?></div>
							</div>
							<?php
						}
						?>
					</div>

					<?php
					// Need to check if each package if the addon is contained
					foreach ( $all_packages as $slug => $upsell ) {
						?>

						<div class="wpchill-pricing-package">
							<?php
							if ( isset( $upsell['extensions'][ $key ] ) ) {
								echo '<span class="dashicons dashicons-saved"></span>';
							} else {
								echo '<span class="dashicons dashicons-no-alt"></span>';
							}
							?>
						</div>
					<?php } ?>
				</div>
				<?php
			}

			// Pro features are features that are present in the PRO version of the plugin
			// And not in extensions / addons
			if ( ! empty( $pro_features ) ) {
				foreach ( $pro_features as $pro_feature ) {
					?>
					<div class="wpchill-plans-table">
						<div class="wpchill-pricing-package feature-name">
							<?php echo esc_html( $pro_feature['title'] ); ?>
							<?php
							// Tooltip the description if any
							if ( isset( $pro_feature['description'] ) ) {
								?>
								<div class="tab-header-tooltip-container wpmtst-tooltip"><span>[?]</span>
									<div class="tab-header-description wpmtst-tooltip-content"><?php echo wp_kses_post( $pro_feature['description'] ); ?></div>
								</div>
								<?php
							}
							?>
						</div>

						<?php
						// Now let's go through our packages
						foreach ( $all_packages as $slug => $upsell ) {
							?>
							<div class="wpchill-pricing-package">
								<?php
								// We need the LITE version because if there is no license key / current package the LITE will be a package also
								if ( 'strong-testimonials-lite' !== $slug ) {
									echo '<span class="dashicons dashicons-saved"></span>';
								} else {
									echo '<span class="dashicons dashicons-no-alt"></span>';
								}
								?>
							</div>
						<?php } ?>
					</div>
					<?php
				}
			}
		}

		/**
		 * Add the smart upsells transients to deletion
		 *
		 * @param $transients
		 *
		 * @return mixed
		 *
		 * @since 2.5.3
		 */
		public function smart_upsells_transients( $transients ) {

			$transients[] = $this->get_transient( 'all_packages' );

			return $transients;
		}

		/**
		 * Deletes the packages transients on license update
		 *
		 * @param $option_name
		 *
		 * @return void
		 *
		 * @since 3.0.3
		 */
		public function delete_upgradable_packages_transients( $option_name ) {
			if ( 'strong_testimonials_license_key' === $option_name ) {
				delete_transient( 'strong-testimonials_upgradable_packages' );
			}
		}
	}
}
