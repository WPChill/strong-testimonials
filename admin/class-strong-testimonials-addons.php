<?php
/**
 * Class Strong_Testimonials_Addons
 *
 * @since 2.38
 */
class Strong_Testimonials_Addons {

	private $addons = array();

	public function __construct() {
		add_filter( 'wpmtst_submenu_pages', array( $this, 'add_submenu' ) );

		// Add ajax action to reload extensions
		add_action( 'wp_ajax_wpmtst_reload_extensions', array( $this, 'reload_extensions' ), 20 );

		add_filter( 'wpmtst_addon_button_action', array( $this, 'output_download_link' ), 5 );
	}

	private function check_for_addons() {

		if ( false !== ( $data = get_transient( 'strong_testimonials_all_extensions' ) ) ) {
			return $data;
		}

		$addons = array();

		$url = apply_filters( 'strong_testimonials_addon_server_url', WPMTST_STORE_URL . '/wp-json/mt/v1/get-all-extensions' );

		// Get data from the remote URL.
		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {

			// Decode the data that we got.
			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! empty( $data ) && is_array( $data ) ) {
				$addons = $data;
				// Store the data for a week.
				set_transient( 'strong_testimonials_all_extensions', $data, 7 * DAY_IN_SECONDS );
			}
		}

		return apply_filters( 'wpmtst_addons', $addons );
	}

	public function render_addons() {

		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );

		if ( ! empty( $this->addons ) ) {
			foreach ( $this->addons as $addon ) {
				$image = ( '' != $addon['image'] ) ? $addon['image'] : WPMTST_ASSETS_IMG . '/logo.png';
				echo '<div class="wpmtst-addon">';
				echo '<div class="wpmtst-addon-box">';
				echo '<img src="' . esc_attr( $image ) . '">';
				echo '<div class="wpmtst-addon-content">';
				echo '<h3>' . esc_html( $addon['name'] ) . '</h3>';
				echo '<div class="wpmtst-addon-description">' . wp_kses_post( $addon['description'] ) . '</div>';
				echo '</div>';
				echo '</div>';
				echo '<div class="wpmtst-addon-actions">';
				echo wp_kses_post( apply_filters( 'wpmtst_addon_button_action', '<a href="' . esc_url( WPMTST_STORE_UPGRADE_URL . '?utm_source=st-lite&utm_campaign=upsell&utm_medium=' . esc_attr( $addon['slug'] ) ) . '" target="_blank" class="button primary-button">' . esc_html__( 'Upgrade now', 'strong-testimonials' ) . '</a>', $addon ) );
				echo '</div>';
				echo '</div>';
			}
		}

	}

	/**
	 * Add submenu page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function add_submenu( $pages ) {
		$pages[91] = $this->get_submenu();
		return $pages;
	}

	/**
	 * Return submenu page parameters.
	 *
	 * @return array
	 */
	public function get_submenu() {
		return array(
			'page_title' => esc_html__( 'Extensions', 'strong-testimonials' ),
			'menu_title' => esc_html__( 'Extensions', 'strong-testimonials' ),
			'capability' => 'strong_testimonials_options',
			'menu_slug'  => 'strong-testimonials-addons',
			'function'   => array( $this, 'addons_page' ),
		);
	}


	/**
	 * Print the Addons page.
	 */
	
	public function addons_page() { 

		$this->addons = $this->check_for_addons();
		?>

		<div class="wrap">
			<h1 style="display: inline-block;"><?php esc_html_e( 'Extensions', 'strong-testimonials' ); ?></h1>

			<a id="wpmtst-reload-extensions" class="button button-primary" style="margin: 10px 0 0 30px;" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpmtst-reload-extensions' ) ); ?>"><?php esc_html_e( 'Reload Extensions', 'strong-testimonials' ); ?></a>

			<?php $this->display_extension_tab(); ?>
			<?php $this->render_license(); ?>
			<div class="wpmtst-addons-container">
				<?php $this->render_addons(); ?>
			</div>
		</div>
		<?php
	}

	public function display_extension_tab() {
		?>
		<h2 class="nav-tab-wrapper">
			<?php
			$tabs = array(
					'testimonials'       => array(
							'name'     => esc_html_x( 'Testimonials', 'post type general name', 'strong-testimonials' ),
							'url'      => admin_url( 'edit.php?post_type=wpm-testimonial' ),
							'priority' => '1'
					),
					'suggest_feature' => array(
							'name'     => esc_html__( 'Suggest a feature', 'strong-testimonials' ),
							'icon'     => 'dashicons-external',
							'url'      => 'https://docs.google.com/forms/d/e/1FAIpQLScch0AchtnzxJsSrjUcW9ypcr1fZ9r-vyk3emEp8Sv47brb2g/viewform',
							'target'   => '_blank',
							'priority' => '10'
					),
			);

			if ( current_user_can( 'install_plugins' ) ) {
				$tabs[ 'extensions' ] = array(
						'name'     => esc_html__( 'Extensions', 'strong-testimonials' ),
						'url'      => admin_url( 'edit.php?post_type=wpm-testimonial&page=strong-testimonials-addons' ),
						'priority' => '5',
				);
			}

			$tabs = apply_filters( 'wpmtst_add_edit_tabs', $tabs );

			uasort( $tabs, array( 'Strong_Testimonials_Helper', 'sort_data_by_priority' ) );

			$active_tab = 'extensions';
			if ( isset( $_GET['tab'] ) && isset( $tabs[ $_GET['tab'] ] ) ) {
				$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
			}

			WPMTST_Admin_Helpers::wpmtst_tab_navigation( $tabs, $active_tab );
			?>
		</h2>
		<br/>
		<?php
	}

	public function reload_extensions() {
		// Run a security check first.
		check_admin_referer( 'wpmtst-reload-extensions', 'nonce' );

		delete_transient( 'strong_testimonials_all_extensions' );

		die;
	}

	/**
	 * Retrieves ST addons
	 *
	 * @return Array
	 *
	 * @since 2.51.7
	 */
	public function get_addons() {
		return $this->check_for_addons();
	}

	/**
	 * Render the license field.
	 *
	 * @return void
	 */
	public function render_license() {

		$license = get_option( 'strong_testimonials_license_key' );
		$email   = get_option( 'strong_testimonials_email' );
		$status  = get_option( 'strong_testimonials_license_status', false );

		$messages = array(
			'no-license'       => esc_html__( 'Enter your license key', 'strong-testimonials-assignment' ),
			'activate-license' => esc_html__( 'Activate your license key', 'strong-testimonials-assignment' ),
			'all-good'         => __( 'Your license is active until <strong>%s</strong>', 'strong-testimonials-assignment' ),
			'lifetime'         => __( 'You have a lifetime license.', 'strong-testimonials-assignment' ),
			'expired'          => esc_html__( 'Your license has expired', 'strong-testimonials-assignment' ),
		);

		if ( '' === $license ) {
			//$license_message = $messages['no-license'];
			$license_message = '';
		} elseif ( '' !== $license && $status === false ) {
			//$license_message = $messages['activate-license'];
			$license_message = '';
		} elseif ( $status->license === 'expired' ) {
			$license_message = $messages['expired'];
		} elseif ( '' !== $license && $status !== false && isset( $status->license ) && $status->license == 'valid' ) {

			$date_format = get_option( 'date_format' );

			if ( 'lifetime' == $status->expires ) {
				$license_message = $messages['lifetime'];
			} else {
				$license_expire = date( $date_format, strtotime( $status->expires ) );
				$curr_time      = time();
				// weeks till expiration
				$weeks = (int) ( ( strtotime( $status->expires ) - $curr_time ) / ( 7 * 24 * 60 * 60 ) );

				// set license status based on colors
				if ( 4 >= $weeks ) {
					$l_stat = 'red';
				} else {
					$l_stat = 'green';
				}

				$license_message = sprintf( '<p class="%s">' . $messages['all-good'] . '</p>', $l_stat, $license_expire );

				if ( 'green' != $l_stat ) {
					$license_message .= sprintf( __( 'You have %s week(s) untill your license will expire.', 'strong-testimonials-assignment' ), $weeks );
				}

			}
		}

		?>
		<div class="row">
			<?php do_action( 'wpmtst_license_errors' ); ?>
			<?php
			$valid_license = false;
			if ( false !== $license && $status && 'valid' === $status->license ) {
				$valid_license = true;
			}
			?>

			<div class="wpmtst-master-license">
				<div>
					<label for="strong_testimonials_email"><?php esc_html_e( 'Email', 'strong-testimonials' ); ?></label>
					<input type="email" id="strong_testimonials_email" name="strong_testimonials_email"
					       value="<?php echo esc_attr( $email ); ?>">
					<label for="st-master-license"><?php esc_html_e( 'License key', 'strong-testimonials' ); ?></label>
					<input type="password" id="strong_testimonials_license_key" name="strong_testimonials_license_key"
					       value="<?php echo esc_attr( $license ); ?>">
					<input type="hidden"
					       value="<?php echo esc_attr( wp_create_nonce( 'strong_testimonials_license_nonce' ) ); ?>"/>
					<button class="button button-primary" id="st-master-license-btn"
					        data-action="<?php echo ( ! $valid_license ) ? 'activate' : 'deactivate'; ?>"><?php ( ! $valid_license ) ? esc_html_e( 'Activate', 'strong-testimonials' ) : esc_html_e( 'Deactivate', 'strong-testimonials' ); ?></button>

					&nbsp;<a href="#" target="_blank" id="st-forgot-license"
					         data-nonce="<?php echo esc_attr( wp_create_nonce( 'strong_testimonials_license_nonce' ) ); ?>"><?php esc_html_e( 'Forgot your license?', 'strong-testimonials' ); ?></a>
				</div>
				<label class="description strong-testimonials-license-label"
				       for="strong_testimonials_license_key">
					<?php
					echo wp_kses_post( $license_message );
					?>
				</label>
			</div>
		</div>
		<?php
	}

	/**
	 * Output the download link.
	 *
	 * @param string $link The link.
	 *
	 * @return string
	 * @since 3.1.4
	 */
	public function output_download_link( $link ) {
		$license = get_option( 'strong_testimonials_license_key', false );
		$status  = get_option( 'strong_testimonials_license_status', false );
		if ( ! $license || $status && 'valid' !== $status->license ) {
			return $link;
		}

		return '<a href="' . WPMTST_STORE_URL . '/account/" class="button button-primary" target="_blank">' . __( 'Download extension', 'strong-testimonials' ) . '</a>';
	}
}

new Strong_Testimonials_Addons();
