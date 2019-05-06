<?php
/**
 * Class Strong_Testimonials_Addons
 *
 * @since 2.38
 */
class Strong_Testimonials_Addons {

	private $addons;
	public $upgrade_url = 'https://strongtestimonials.com/pricing/?utm_source=st-lite&utm_campaign=upsell';

	public function __construct() {
		$this->addons = $this->check_for_addons();
		$this->add_actions();
	}

	private function check_for_addons() {

		$addons = array(
			array(
				'image'       => '',
				'name'        => esc_html__( 'Strong Testimonials Custom Fields', 'strong-testimonials' ),
				'description' => esc_html__( 'Enhance your testimonials and submission forms with the Custom Fields extension to both collect and display additional information.', 'strong-testimonials' ),
				'slug'        => 'strong-testimonials-custom-fields',
			),
			array(
				'image'       => '',
				'name'        => esc_html__( 'Strong Testimonials Country Selector', 'strong-testimonials' ),
				'description' => esc_html__( 'Allow customers to select their country when submitting testimonials.', 'strong-testimonials' ),
				'slug'        => 'strong-testimonials-country-selector',
			),
			array(
				'image'       => '',
				'name'        => esc_html__( 'Strong Testimonials Assignment', 'strong-testimonials' ),
				'description' => esc_html__( 'Assign testimonials to custom post types.', 'strong-testimonials' ),
				'slug'        => 'strong-testimonials-assignment',
			),
			array(
				'image'       => '',
				'name'        => esc_html__( 'Strong Testimonials Multiple Forms', 'strong-testimonials' ),
				'description' => esc_html__( 'Easily collect testimonials from customers by creating and customizing multiple forms at once.', 'strong-testimonials' ),
				'slug'        => 'strong-testimonials-multiple-forms',
			),
			array(
				'image'       => '',
				'name'        => esc_html__( 'Strong Testimonials Review Markup', 'strong-testimonials' ),
				'description' => esc_html__( 'SEO-friendly Testimonials. Take full advantage of your testimonials with our Schema.org Markup extension.', 'strong-testimonials' ),
				'slug'        => 'strong-testimonials-review-markup',
			),
			array(
				'image'       => '',
				'name'        => esc_html__( 'Strong Testimonials Custom Properties', 'strong-testimonials' ),
				'description' => esc_html__( 'Change properties of the testimonial post type: labels, permalink structure, admin options and post editor features.', 'strong-testimonials' ),
				'slug'        => 'strong-testimonials-properties',
			),
			array(
				'image'       => '',
				'name'        => esc_html__( 'Strong Testimonials Advanced Views', 'strong-testimonials' ),
				'description' => esc_html__( 'Customize testimonials beyond star ratings with the Advanced Views extension.', 'strong-testimonials' ),
				'slug'        => 'strong-testimonials-advanced-views',
			),
		);

		return apply_filters( 'wpmtst_addons', $addons );

	}

	public function render_addons() {

		wp_enqueue_style( 'wpmtst-admin-style' );

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
				echo apply_filters( 'wpmtst_addon_button_action', '<a href="' . esc_url( $this->upgrade_url ) . '&utm_medium=' . esc_attr( $addon['slug'] ) . '" target="_blank" class="button primary-button">' . esc_html__( 'Upgrade to PRO', 'strong-testimonials' ) . '</a>', $addon );
				echo '</div>';
				echo '</div>';
			}
		}

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
			'page_title' => __( 'Extensions', 'strong-testimonials' ),
			'menu_title' => __( 'Extensions', 'strong-testimonials' ),
			'capability' => 'strong_testimonials_options',
			'menu_slug'  => 'strong-testimonials-addons',
			'function'   => array( $this, 'addons_page' ),
		);
	}


	/**
	 * Print the Addons page.
	 */
	public function addons_page() {
		?>
		<div class="wrap about-wrap">
			<div class="wpmtst-addons-container">
				<?php $this->render_addons(); ?>
			</div>
		</div>
		<?php
	}



}

new Strong_Testimonials_Addons();
