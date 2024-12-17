<?php
/**
 * Class Strong_Testimonials_Addons
 *
 */
class Strong_Testimonials_Lite_Vs_PRO_Page {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'inline_script_for_redirection' ) );

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

			$links = array_merge( array( '<a target="_blank" class="wpmtst-lite-vs-pro" href="https://strongtestimonials.com/pricing/?utm_source=strong-testimonials-lite&utm_medium=plugin_settings&utm_campaign=upsell">' . esc_html__( 'Upgrade to PRO!', 'strong-testimonials' ) . '</a>' ), $links );
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

	public function admin_enqueue_scripts() {
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
			'menu_slug'  => '#st-lite-vs-pro',
			'function'   => array( $this, 'render_page' ),
		);
	}

	public function render_page() {
		return;
	}

	public function inline_script_for_redirection() {
		?>
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				const link = document.querySelector('a[href*="edit.php?post_type=wpm-testimonial&page=#st-lite-vs-pro"]');
				if (link) {
					link.addEventListener('click', function(event) {
						event.preventDefault();
						
						window.open(
						'https://strongtestimonials.com/free-vs-pro/?utm_source=st-lite&utm_medium=link&utm_campaign=upsell&utm_term=lite-vs-pro',
						'_blank'
					);
					});
				}
			});
		</script>
		<?php
	}
}
