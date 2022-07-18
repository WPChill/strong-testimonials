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
				echo apply_filters( 'wpmtst_addon_button_action', '<a href="' . esc_url( WPMTST_STORE_UPGRADE_URL . '?utm_source=st-lite&utm_campaign=upsell&utm_medium=' . esc_attr( $addon['slug'] ) ) . '" target="_blank" class="button primary-button">' . esc_html__( 'Upgrade now', 'strong-testimonials' ) . '</a>', $addon );
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
			<h1 style="margin-bottom: 20px; display: inline-block;"><?php esc_html_e( 'Extensions', 'strong-testimonials' ); ?></h1>

			<a id="wpmtst-reload-extensions" class="button button-primary" style="margin: 10px 0 0 30px;" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpmtst-reload-extensions' ) ); ?>"><?php esc_html_e( 'Reload Extensions', 'strong-testimonials' ); ?></a>

			<div class="wpmtst-addons-container">
				<?php $this->render_addons(); ?>
			</div>
		</div>
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

}

new Strong_Testimonials_Addons();
