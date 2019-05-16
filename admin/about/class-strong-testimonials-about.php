<?php
/**
 * Class Strong_Testimonials_About
 *
 * @since 2.27.0
 */
class Strong_Testimonials_About {

	/**
	 * Strong_Testimonials_About constructor.
	 */
	public function __construct() {
        $this->add_actions();
    }

	/**
	 * Add actions and filters.
	 */
	public function add_actions() {
		add_filter( 'wpmtst_submenu_pages', array( $this, 'add_submenu' ) );
	}

	/**
     * Add submenu page.
     *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function add_submenu( $pages ) {
		$pages[90] = $this->get_submenu();
		return $pages;
	}

	/**
	 * Return submenu page parameters.
	 *
	 * @return array
	 */
	public function get_submenu() {
		return array(
			'page_title' => __( 'About', 'strong-testimonials' ),
			'menu_title' => __( 'About', 'strong-testimonials' ),
			'capability' => 'strong_testimonials_about',
			'menu_slug'  => 'about-strong-testimonials',
			'function'   => array( $this, 'about_page' ),
		);
	}

	/**
	 * Print the About page.
	 */
	public function about_page() {
		$active_tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : 'how-to';
		$url         = admin_url( 'edit.php?post_type=wpm-testimonial&page=about-strong-testimonials' );
		?>
		<div class="wrap about-wrap">

			<img class="wpmst-mascot" src="<?php echo esc_url( WPMTST_ADMIN_URL ); ?>/img/mascot.png" />

			<?php /* translators: %s is the plugin version number */ ?>
			<h1><?php printf( __( 'Welcome to Strong Testimonials %s', 'strong-testimonials' ), WPMTST_VERSION ); ?></h1>

			<p class="about-text">
				<?php esc_html_e( 'Thank you for updating to the latest version!', 'strong-testimonials' ); ?>
				<?php /* translators: %s is the plugin version number */ ?>
            </p>
			<br/>

			<h2 class="nav-tab-wrapper wp-clearfix">

				<a href="<?php echo add_query_arg( 'tab', 'how-to', $url ); ?>" class="nav-tab <?php echo $active_tab == 'how-to' ? 'nav-tab-active' : ''; ?>"><?php _e( 'How To', 'strong-testimonials' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'privacy', $url ) ); ?>" class="nav-tab <?php echo $active_tab == 'privacy' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Privacy', 'strong-testimonials' ); ?></a>

			</h2>


			<?php
			switch( $active_tab ) {
				case 'privacy':
					include WPMTST_ADMIN . 'about/privacy.php';
					break;
				default:
					include WPMTST_ADMIN . 'about/how-to.php';
					break;
			}

			include WPMTST_ADMIN . 'about/upsell.php';
			include WPMTST_ADMIN . 'about/links.php';
			include WPMTST_ADMIN . 'about/addons.php';
			?>

		</div>
		<?php
	}


}

new Strong_Testimonials_About();
