<?php

class Strong_Testimonials_Welcome {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register' ) );
		add_action( 'admin_head', array( $this, 'hide_menu' ) );
		add_action( 'wpmtst_after_update_setup', array( $this, 'wpmtst_on_activation' ), 15 );
	}

	public function hide_menu() {
		remove_submenu_page( 'index.php', 'wpmtst-getting-started' );
	}

	/**
	 * Add activation hook. Need to be this way so that the About page can be created and accessed
	 *
	 * @param $first_install
	 * @since 2.51.9
	 */
	public function wpmtst_on_activation( $first_install ) {

		if ( $first_install ) {
			add_action( 'activated_plugin', array( $this, 'redirect_on_activation' ) );
		}
	}

	/**
	 * Redirect to About page when activated
	 *
	 * @param $plugin
	 * @since 2.51.9
	 */
	public function redirect_on_activation( $plugin ) {

		if ( WPMTST_PLUGIN === $plugin ) {
			wp_safe_redirect( admin_url( 'index.php?page=wpmtst-getting-started' ) );
			exit;
		}
	}
	public function register() {

		add_dashboard_page(
			esc_html__( 'Welcome to Strong Testimonials', 'strong-testimonials' ),
			esc_html__( 'Welcome to Strong Testimonials', 'strong-testimonials' ),
			'manage_options',
			'wpmtst-getting-started',
			array( $this, 'about_page' )
		);
	}

	/**
	 * @since 2.51.9
	 * Display About page
	 */
	public function about_page() {

		// WPChill Welcome Class
		require_once WPMTST_DIR . 'includes/submodules/banner/class-wpchill-welcome.php';

		$welcome = WPChill_Welcome::get_instance();
		?>
		<div id="wpchill-welcome">

			<div class="container">

				<div class="hero features">

					<div class="mascot">
					<img src="<?php echo esc_url( WPMTST_ADMIN_URL ); ?>/img/mascot-2.svg" alt="<?php esc_attr_e( 'Strong Testimonials Mascot', 'strong-testimonials' ); ?>">
					</div>

					<div class="block">
						<?php $welcome->display_heading( esc_html__( 'Thank you for installing Strong Testimonials', 'strong-testimonials' ) ); ?>
						<?php $welcome->display_subheading( esc_html__( 'You\'re just a few steps away from adding and displaying your first testimonial on your website with the easiest to use WordPress review and testimonial plugin on the market.', 'strong-testimonials' ) ); ?>
					</div>
					<div class="wpchill-text-center">
						<div class="button-wrap-single">
							<?php $welcome->display_button( esc_html__( 'Read our step-by-step guide to get started', 'strong-testimonials' ), 'https://strongtestimonials.com/docs/add-testimonials-to-your-website/','wpmtst-btn wpmtst-btn-block wpmtst-btn-lg', true ); ?>
						</div>
					</div>

					<?php $welcome->display_empty_space(); ?>

					<img src="<?php echo esc_url( WPMTST_ADMIN_URL ); ?>/img/banner.png" alt="<?php esc_attr_e( 'Watch how to', 'strong-testimonials' ); ?>" class="video-thumbnail">

					<?php $welcome->horizontal_delimiter(); ?>

					<div class="block">
						<?php $welcome->display_heading( esc_html__( 'Features&Add-ons', 'strong-testimonials' ) ); ?>
						<?php $welcome->layout_start( 2, 'feature-list clear' ); ?>
						<?php $welcome->display_extension( esc_html__( 'Multiple views', 'strong-testimonials' ), esc_html__( 'Take advantage of our three testimonial views - display, slider, and form, and use them to apply custom settings to multiple testimonials.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/multiple-views.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Predesigned templates', 'strong-testimonials' ), esc_html__( 'Choose a built-in template, layout, background, and font color to showcase your testimonials in a visually-appealing way.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/predesigned-templates.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Testimonial Importer', 'strong-testimonials' ), esc_html__( 'Keep all your testimonials in one single place by importing reviews from third-party websites/plugins, such as: Google, Facebook, Yelp, Zomato, Woocommerce.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/testimonial-importer.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Testimonial forms', 'strong-testimonials' ), esc_html__( 'Collect testimonials from customers via different types of forms along with their data - name, email, company name, company website, featured image, star rating.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/testimonial-forms.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'User role management', 'strong-testimonials' ), esc_html__( 'As, an admin, easily decide which user roles are worthy of adding, editing, or removing testimonials.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/user-role-management.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Spam protection', 'strong-testimonials' ), esc_html__( 'Add spam control to your forms with our Captcha extension.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/spam-protection.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Email integration', 'strong-testimonials' ), esc_html__( 'Unlock more marketing and automation potential by sending users a targeted message or a coupon to thank them for leaving a good review and subscribing to a MailChimp email list', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/email-integration.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'SEO-friendly markup', 'strong-testimonials' ), esc_html__( 'Include rating markup to encourage search engines to display rich snippets - whether or not you use a star rating field on your testimonials.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/seo-friendly-markup.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Pending testimonials', 'strong-testimonials' ), esc_html__( 'Manually verify and approve your incoming testimonials, or make use of the auto-approve feature to save time.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/pending-testimonials.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Testimonial assignment', 'strong-testimonials' ), esc_html__( 'Assign testimonials to certain custom post types.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/testimonial-assignment.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Testimonial category filters', 'strong-testimonials' ), esc_html__( 'Increase workflow and user experience by filtering testimonials by category, product/service, or star rating.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/testimonial-category-filters.png', true ); ?>
						<?php $welcome->display_extension( esc_html__( 'Properties ', 'strong-testimonials' ), esc_html__( 'Change properties of the testimonial post type: labels, permalink structure, admin options and post editor features.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/features/properties.png', true ); ?>					
						<?php $welcome->layout_end(); ?>
						<div class="wpchill-text-center">
							<div class="button-wrap-single clear">
									<div class="right">
									<?php $welcome->display_button( esc_html__( 'Upgrade Now', 'strong-testimonials' ), 'https://strongtestimonials.com/pricing/?utm_source=welcome_banner&utm_medium=upgradenow&utm_campaign=welcome_banner&utm_content=first_button','wpmtst-btn wpmtst-btn-block wpmtst-btn-lg', true, '#E76F51' ); ?>
									</div>
							</div>
						</div>
					</div>

					<?php $welcome->horizontal_delimiter(); ?>

					<div class="block">
						<div class="testimonials">
								<div class="clear">
									<?php $welcome->display_heading( esc_html__( 'Happy users of Strong Testimonials', 'strong-testimonials' ) ); ?>

									<?php $welcome->display_testimonial( esc_html__( 'Strong Testimonials is my new, go-to resource for creating pages with multiple staff bios or testimonials. It\'s extremely easy to use, update, and customize, and that makes it an invaluable asset. Highly recommend!.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/testimonial-image-1.jpg', 'Ryan Haught', 'Digital Marketer at Heaven\'s Family' ); ?>
									<?php $welcome->display_testimonial( esc_html__( 'I have used various testimonials plugins. What I get here for free is just amazing. The support is great. And updates fresh. Looking at the ability to get reviews indexed by Google is more than enough reason to get an upgrade.', 'strong-testimonials' ), esc_url( WPMTST_ADMIN_URL ) . '/img/testimonial-image-2.jpeg', 'Johan Horak', 'Marketing at CapeHolidays' ); ?>
								</div>
						</div><!-- testimonials -->
						<div class="button-wrap clear">
							<div class="left">
								<?php $welcome->display_button( esc_html__( 'Start Adding Testimonials', 'strong-testimonials' ), esc_url( admin_url( 'edit.php?post_type=wpm-testimonial' ) ),'wpmtst-btn wpmtst-btn-block wpmtst-btn-lg', true ); ?>
							</div>
							<div class="right">
								<?php $welcome->display_button( esc_html__( 'Upgrade Now', 'strong-testimonials' ), 'https://strongtestimonials.com/pricing/?utm_source=welcome_banner&utm_medium=upgradenow&utm_campaign=welcome_banner&utm_content=second_button','wpmtst-btn wpmtst-btn-block wpmtst-btn-lg', true, '#E76F51' ); ?>
							</div>
						</div>
					</div>
				</div><!-- hero -->
			</div><!-- container -->
		</div><!-- wpchill welcome -->
		<?php
	}
}

new Strong_Testimonials_Welcome();
