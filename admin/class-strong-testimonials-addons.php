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
		add_action( 'wpmtst_settings_tabs', array( $this, 'register_tab' ), 1, 2 );
		add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_settings_page' ) );
	}

	private function check_for_addons() {
		$data = get_transient( 'strong_testimonials_all_extensions' );

		if ( false !== $data ) {
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
				if ( 'strong-testimonials-pro' === $addon['slug'] ) {
					continue;
				}
				$image = ( '' !== $addon['image'] ) ? $addon['image'] : WPMTST_ASSETS_IMG . '/logo.png';
				echo '<div class="wpmtst-addon">';
				echo '<div class="wpmtst-addon-box">';
				echo '<img src="' . esc_attr( $image ) . '">';
				echo '<div class="wpmtst-addon-content">';
				echo '<h3>' . esc_html( $addon['name'] ) . '</h3>';
				echo '<div class="wpmtst-addon-description">' . wp_kses_post( $addon['description'] ) . '</div>';
				echo '</div>';
				echo '</div>';
				echo '<div class="wpmtst-addon-actions">';
				echo wp_kses_post( apply_filters( 'wpmtst_addon_button_action', '<a href="' . esc_url( WPMTST_STORE_UPGRADE_URL . '?utm_source=st-lite&utm_campaign=upsell&utm_medium=' . esc_attr( $addon['slug'] ) ) . '" target="_blank" class="button primary-button">' . esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade now', 'strong-testimonials' ) ) ) . '</a>', $addon ) );
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
				'testimonials'    => array(
					'name'     => esc_html_x( 'Testimonials', 'post type general name', 'strong-testimonials' ),
					'url'      => admin_url( 'edit.php?post_type=wpm-testimonial' ),
					'priority' => '1',
				),
				'suggest_feature' => array(
					'name'     => esc_html__( 'Suggest a feature', 'strong-testimonials' ),
					'icon'     => 'dashicons-external',
					'url'      => 'https://docs.google.com/forms/d/e/1FAIpQLScch0AchtnzxJsSrjUcW9ypcr1fZ9r-vyk3emEp8Sv47brb2g/viewform',
					'target'   => '_blank',
					'priority' => '10',
				),
			);

			if ( current_user_can( 'install_plugins' ) ) {
				$tabs['extensions'] = array(
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

		do_action( 'wpmtst_reload_extensions' );

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
	 * Register settings tab.
	 *
	 * @param $active_tab
	 * @param $url
	 */
	public function register_tab( $active_tab, $url ) {
		echo apply_filters(
			'wpmtst_license_tab',
			sprintf(
				'<a href="%1$s" class="nav-tab %2$s">%3$s %4$s</a>',
				esc_url( add_query_arg( 'tab', 'license', $url ) ),
				esc_attr( 'license' === $active_tab ? 'nav-tab-active' : '' ),
				esc_html__( 'License', 'strong-testimonials' ),
				'<span class="wpmtst-upsell-badge">PRO</span>'
			),
			$active_tab,
			$url
		);
	}

	/**
	 * Register settings page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function register_settings_page( $pages ) {
		$pages['license'] = array( $this, 'render_license_upsell_content' );

		return $pages;
	}

	/**
	 * Render the license field.
	 *
	 * @return void
	 */
	public function render_license_upsell_content() {
		?>
		<div class="wpmtst-alert">
			<h2> Strong Testimonials - PRO </h2>
			<p><?php esc_html_e( 'Manage license activation and deactivation, and install extensions seamlessly on-the-go.', 'strong-testimonials' ); ?></p>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( WPMTST_STORE_UPGRADE_URL . '?utm_medium=license-tab' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade Now', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}
}

new Strong_Testimonials_Addons();
