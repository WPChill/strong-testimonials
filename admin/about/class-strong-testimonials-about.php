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
			'page_title' => __( 'About' ),
			'menu_title' => __( 'About' ),
			'capability' => 'strong_testimonials_about',
			'menu_slug'  => 'about-strong-testimonials',
			'function'   => array( $this, 'about_page' ),
		);
	}

	/**
	 * Print the About page.
	 */
	public function about_page() {
		$major_minor = strtok( WPMTST_VERSION, '.' ) . '.' . strtok( '.' );
		$active_tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : 'how-to';
		$url         = admin_url( 'edit.php?post_type=wpm-testimonial&page=about-strong-testimonials' );
		?>
		<div class="wrap about-wrap">

			<?php /* translators: %s is the plugin version number */ ?>
			<h1><?php printf( esc_html__( 'Welcome to Strong Testimonials %s', 'strong-testimonials' ), esc_html( $major_minor ) ); ?></h1>

			<p class="about-text">
				<?php esc_html_e( 'Thank you for updating to the latest version!' ); ?>
				<?php /* translators: %s is the plugin version number */ ?>
			</p>

			<div class="wp-badge strong-testimonials"><?php printf( esc_html__( 'Version %s' ), esc_html( $major_minor ) ); ?></div>

			<h2 class="nav-tab-wrapper wp-clearfix">

				<a href="<?php echo esc_url( add_query_arg( 'tab', 'how-to', $url ) ); ?>" class="nav-tab <?php echo $active_tab == 'how-to' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'How To', 'strong-testimonials' ); ?></a>

				<a href="<?php echo esc_url( add_query_arg( 'tab', 'privacy', $url ) ); ?>" class="nav-tab <?php echo $active_tab == 'privacy' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Privacy' ); ?></a>

			</h2>


			<?php
			switch ( $active_tab ) {
				case 'privacy':
					include WPMTST_ADMIN . 'about/privacy.php';
					break;
				default:
					include WPMTST_ADMIN . 'about/how-to.php';
					break;
			}

			include WPMTST_ADMIN . 'about/links.php';
			include WPMTST_ADMIN . 'about/addons.php';
			?>

		</div>
		<?php
	}


}

new Strong_Testimonials_About();
