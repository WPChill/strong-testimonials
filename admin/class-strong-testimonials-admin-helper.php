<?php


class WPMTST_Admin_Helpers {

	/**
	 * Holds the class object.
	 *
	 * @since 3.0.3
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * WPMTST_Admin_Helpers constructor.
	 *
	 * @since 3.0.3
	 */
	function __construct() {

		$this->load_hooks();

		if ( is_admin() ) {
			$this->load_admin_hooks();
		}
	}


	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return object The WPMTST_Admin_Helpers object.
	 *
	 * @since 3.0.3
	 */
	public static function get_instance() {

		if ( !isset( self::$instance ) && !( self::$instance instanceof WPMTST_Admin_Helpers ) ) {
			self::$instance = new WPMTST_Admin_Helpers();
		}

		return self::$instance;

	}

	/**
	 * Load our public hooks
	 *
	 * @since 3.0.3
	 */
	public function load_hooks(){

	}

	/**
	 * Load our admin hooks
	 *
	 * @since 3.0.3
	 */
	public function load_admin_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'register_style' ) );
		add_action( 'in_admin_header', array( $this, 'page_header' ) );
		add_filter( 'wpmtst_page_header', array( $this, 'page_header_locations' ) );
	}

	/**
	 * Display the ST Admin Page Header
	 *
	 * @param bool $extra_class
	 *
	 * @since 3.0.3
	 */
	public static function page_header($extra_class = '') {

		// Only display the header on pages that belong to ST.
		if ( ! apply_filters( 'wpmtst_page_header', false ) ) {
			return;
		}

        wp_enqueue_style( 'wpmtst-header-style' );

		?>
		<div class="wpchill-page-header <?php echo ( $extra_class ) ? esc_attr( $extra_class ) : ''; ?>">
			<div class="wpchill-header-logo">
				<img src="<?php echo esc_url( WPMTST_ADMIN_URL . 'img/logo strong text.png' ); ?>" class="wpchill-logo">
			</div>
			<div class="wpchill-status-bar">
			</div>
			<div class="wpchill-header-links">
				<a href="<?php echo esc_url( admin_url('index.php?page=wpmtst-getting-started') ); ?>"
				   class="button button-secondary"><span
							class="dashicons dashicons-admin-plugins"></span><?php esc_html_e( 'About', 'strong-testimonials' ); ?>
				</a>
				<a href="https://strongtestimonials.com/docs/" target="_blank" id="get-help"
				   class="button button-secondary"><span
							class="dashicons dashicons-external"></span><?php esc_html_e( 'Documentation', 'strong-testimonials' ); ?>
				</a>
				<a class="button button-secondary"
				   href="https://strongtestimonials.com/contact-us/" target="_blank"><span
							class="dashicons dashicons-email-alt"></span><?php echo esc_html__( 'Contact us for support!', 'strong-testimonials' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Set the ST header locations
	 *
	 * @param $return
	 *
	 * @return bool|mixed
	 *
	 * @since 3.0.3
	 */
	public function page_header_locations( $return ) {

		$current_screen = get_current_screen();

		if ( 'wpm-testimonial' === $current_screen->post_type ) {
			return true;
		}

		return $return;
	}

	/**
	 * Tab navigation display
	 *
	 * @param $tabs
	 * @param $active_tab
	 *
	 * @since 3.0.3
	 */
	public static function wpmtst_tab_navigation( $tabs, $active_tab ) {

		if ( $tabs ) {

			$i = count( $tabs );
			$j = 1;

			foreach ( $tabs as $tab_id => $tab ) {

				$last_tab = ( $i == $j ) ? ' last_tab' : '';
				$active   = ( $active_tab == $tab_id ? ' nav-tab-active' : '' );
				$j ++;

				if ( isset( $tab['url'] ) ) {
					// For Extensions and Gallery list tabs
					$url = $tab['url'];
				} else {
					// For Settings tabs
					$url = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-' . $tab_id );
				}

				echo '<a href="' . esc_url( $url ) . '" class="nav-tab' . esc_attr( $active ) . esc_attr( $last_tab ) . '" ' . ( isset( $tab['target'] ) ? 'target="' . esc_attr( $tab['target'] ) . '"' : '' ) . '>';

				if ( isset( $tab['icon'] ) ) {
					echo '<span class="dashicons ' . esc_attr( $tab['icon'] ) . '"></span>';
				}

				// For Extensions and Gallery list tabs
				if ( isset( $tab['name'] ) ) {
					echo esc_html( $tab['name'] );
				}

				// For Settings tabs
				if ( isset( $tab['label'] ) ) {
					echo esc_html( $tab['label'] );
				}

				if ( isset( $tab['badge'] ) ) {
					echo '<span class="wpmsts-badge">' . esc_html( $tab['badge'] ) . '</span>';
				}

				echo '</a>';
			}
		}
	}

	/**
	 * Register style
	 *
	 * @return void
	 * @since 3.0.3
	 */
	public function register_style() {
		wp_register_style( 'wpmtst-header-style', WPMTST_ADMIN_URL . 'css/header.css', array(), WPMTST_VERSION );

	}

}

$wpmtst_admin_helpers = WPMTST_Admin_Helpers::get_instance();