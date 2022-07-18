<?php
/**
 * Class Strong_Testimonials_Addons
 *
 */
class Strong_Testimonials_Lite_vs_PRO_page {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'wpmtst_submenu_pages', array( $this, 'add_submenu' ) );

		// Upgrade to PRO plugin action link
		add_filter( 'plugin_action_links_' . WPMTST_PLUGIN, array( $this, 'filter_action_links' ), 60 );
	}
	
	/**
	 * Add the Upgrade to PRO plugin action link
	 *
	 * @param $links
	 *
	 * @return array
	 *
	 * @since 2.51.7
	 */
	public function filter_action_links( $links ) {

		if ( apply_filters( 'st_plugins_upgrade_pro', true ) ) {

			$links = array_merge( array ( '<a target="_blank" class="wpmtst-lite-vs-pro" href="https://strongtestimonials.com/pricing/?utm_source=strong-testimonials-lite&utm_medium=plugin_settings&utm_campaign=upsell">' . esc_html__( 'Upgrade to PRO!', 'strong-testimonials' ) . '</a>' ), $links );
		}
        return $links;
    }
	/**
	 * Add submenu page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function add_submenu( $pages ) {
		$pages[99] = $this->get_submenu();
		return $pages;
	}

	public function admin_enqueue_scripts(){
		wp_enqueue_style( 'wpmtst-lite-vs-pro' );
	}

	/**
	 * Return submenu page parameters.
	 *
	 * @return array
	 */
	public function get_submenu() {
		return array(
			'page_title' => esc_html__( 'Lite vs Pro', 'strong-testimonials' ),
			'menu_title' => esc_html__( 'Lite vs Pro', 'strong-testimonials' ),
			'capability' => 'strong_testimonials_options',
			'menu_slug'  => 'strong-testimonials-lite-vs-pro',
			'function'   => array( $this, 'render_page' ),
		);
	}

	public function render_page(){

		$addons = new Strong_Testimonials_Addons();
		?>
		<div class="wrap wpmtst-lite-vs-premium">
			<hr class="wp-header-end" />
			<h1><?php echo esc_html__( 'LITE vs PRO', 'strong-testimonials' ); ?> </h1>	
			<div class="free-vs-premium">
				<!--  Table header -->
				<div class="wpchill-plans-table table-header">
					<div class="wpchill-pricing-package wpchill-empty">
						<!--This is an empty div so that we can have an empty corner-->
					</div>
					<div class="wpchill-pricing-package wpchill-title">
						<p class="wpchill-name"><strong>PRO</strong></p>
					</div>
					<div class="wpchill-pricing-package wpchill-title wpchill-wpmtst-lite">
						<p class="wpchill-name"><strong>LITE</strong></p>
					</div>
				</div>
				<!--  Table content -->

				<?php
				foreach ( $addons->get_addons() as $pro ) {
					?>
					<div class="wpchill-plans-table">
					<div class="wpchill-pricing-package feature-name">
						<h3><?php echo esc_html( $pro['name']); ?></h3>
						<p class="tab-header-description wpmtst-tooltip-content">
							<?php echo esc_html( $pro['description'] ); ?>
						</p>
					</div>
					<div class="wpchill-pricing-package">
						<span class="dashicons dashicons-saved"></span>
					</div>
					<div class="wpchill-pricing-package">
						<span class="dashicons dashicons-no-alt"></span>
					</div>
				</div>
					<?php
				}
				?>
				<!-- Support -->
				<div class="wpchill-plans-table">
					<div class="wpchill-pricing-package feature-name">
						<h3><?php esc_html_e( 'Support', 'strong-testimonials' ); ?></h3>
					</div>
					<div class="wpchill-pricing-package">Priority</div>
					<div class="wpchill-pricing-package"><a href="https://wordpress.org/support/plugin/strong-testimonials/"
							target="_blank">wp.org</a>
					</div>
				</div>
				<!--  Table footer -->
				<div class="wpchill-plans-table tabled-footer">
					<div class="wpchill-pricing-package wpchill-empty">
						<!--This is an empty div so that we can have an empty corner-->
					</div>
					<div class="wpchill-pricing-package wpchill-title wpchill-wpmtst-grid-gallery-business">

						<a href="https://strongtestimonials.com/pricing/?utm_source=strong-testimonials&utm_medium=lite-vs-pro&utm_campaign=upsell" target="_blank"
							class="button button-primary button-hero "><span class="dashicons dashicons-cart"></span>
							<?php esc_html_e( 'Upgrade now!', 'strong-testimonials' ); ?> </a>

					</div>
					<div class="wpchill-pricing-package wpchill-title wpchill-wpmtst-lite">


					</div>
				</div>
			</div>
		</div>
	<?php
	}
}
new Strong_Testimonials_Lite_vs_PRO_page();