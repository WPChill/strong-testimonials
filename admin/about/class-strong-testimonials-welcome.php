<?php

class Strong_Testimonials_Welcome {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register' ) );
		add_action( 'admin_head', array( $this, 'hide_menu' ) );
	}

	public function hide_menu() {
		remove_submenu_page( 'index.php', 'wpmtst-getting-started' );
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
     * @since 2.51.8
     * Display About page
     */
    public function about_page() {

        // WPChill Welcome Class
        require_once WPMTST_DIR . 'includes/admin/about/class-wpchill-welcome.php';
        $welcome = WPChill_Welcome::get_instance();
        ?>
		<div id="wpchill-welcome">

			<div class="container">

				<div class="hero features">

					<div class="mascot">
					<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/mascot-2.svg" alt="<?php esc_attr_e( 'Strong Testimonials Mascot', 'strong-testimonials' ); ?>">
					</div>

					<div class="block">
                        <?php $welcome->display_heading( 'Thank you for installing Strong Testimonials' ); ?>
						<?php $welcome->display_subheading( 'You\'re just a few steps away from adding and displaying your first testimonial on your website with the easiest to use Wordpress review and testimonial plugin on the market.' ); ?>
                    </div>
					
                    <div class="button-wrap-single">
                        <?php $welcome->display_button( 'Read our step-by-step guide to get started', 'https://strongtestimonials.com/docs/add-testimonials-to-your-website/', true ); ?>
                    </div>

					<?php $welcome->display_empty_space(); ?>
					
					<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/banner.png" alt="<?php esc_attr_e( 'Watch how to', 'strong-testimonials' ); ?>" class="video-thumbnail">
                    
					<div class="block">
                        <?php $welcome->layout_start( 2, 'feature-list clear' ); ?>
                        <?php $welcome->display_extension( 'Multiple views', 'Take advantage of our three testimonial views - display, slider, and form, and use them to apply custom settings to multiple testimonials.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/multiple-views.png", true ); ?>
                        <?php $welcome->display_extension( 'Predesigned templates', 'Choose a built-in template, layout, background, and font color to showcase your testimonials in a visually-appealing way.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/predesigned-templates.png", true ); ?>
                        <?php $welcome->display_extension( 'Testimonial Importer', 'Keep all your testimonials in one single place by importing reviews from third-party websites/plugins, such as: Google, Facebook, Yelp, Zomato, Woocommerce.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/testimonial-importer.png", true ); ?>
                        <?php $welcome->display_extension( 'Testimonial forms', 'Collect testimonials from customers via different types of forms along with their data - name, email, company name, company website, featured image, star rating.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/testimonial-forms.png", true ); ?>
                        <?php $welcome->display_extension( 'Country Selector', ' Easily add a form field to allow your customers to submit testimonials with their country information.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/country-selector.png", true ); ?>
                        <?php $welcome->display_extension( 'Spam protection', ' Add spam control to your forms with our Captcha extension.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/spam-protection.png", true ); ?>
                        <?php $welcome->display_extension( 'Email integration', 'Unlock more marketing and automation potential by sending users a targeted message or a coupon to thank them for leaving a good review and subscribing to a MailChimp email list',  esc_attr( WPMTST_ADMIN_URL). "/img/features/email-integration.png", true ); ?>
                        <?php $welcome->display_extension( 'SEO-friendly markup', 'Include rating markup to encourage search engines to display rich snippets – whether or not you use a star rating field on your testimonials.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/seo-friendly-markup.png", true ); ?>
                        <?php $welcome->display_extension( 'Pending testimonials', 'Manually verify and approve your incoming testimonials, or make use of the auto-approve feature to save time.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/pending-testimonials.png", true ); ?>
                        <?php $welcome->display_extension( 'Testimonial assignment', 'Assign testimonials to certain custom post types.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/testimonial-assignment.png", true ); ?>
                        <?php $welcome->display_extension( 'Testimonial category filters', 'Increase workflow and user experience by filtering testimonials by category, product/service, or star rating.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/testimonial-category-filters.png", true ); ?>
                        <?php $welcome->display_extension( 'Properties ', 'Change properties of the testimonial post type: labels, permalink structure, admin options and post editor features.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/properties.png", true ); ?>
                        <?php $welcome->display_extension( 'User role management', 'As, an admin, easily decide which user roles are worthy of adding, editing, or removing testimonials.',  esc_attr( WPMTST_ADMIN_URL). "/img/features/user-role-management.png", true ); ?>
						<?php $welcome->layout_end(); ?>

						<div class="testimonials">
                                <div class="block clear">
                                    <?php $welcome->display_heading( 'Happy users of Strong Testimonials' ); ?>
                                
                                    <?php $welcome->display_testimonial( 'Strong Testimonials is my new, go-to resource for creating pages with multiple staff bios or testimonials. It’s extremely easy to use, update, and customize, and that makes it an invaluable asset. Highly recommend!.', esc_attr( WPMTST_ADMIN_URL ) . '/img/testimonial-image-1.jpg', 'Ryan Haught', 'Digital Marketer at Heaven’s Family'); ?>
                                    <?php $welcome->display_testimonial( 'I have used various testimonials plugins. What I get here for free is just amazing. The support is great. And updates fresh. Looking at the ability to get reviews indexed by Google is more than enough reason to get an upgrade.', esc_attr( WPMTST_ADMIN_URL ) . '/img/testimonial-image-2.jpeg', 'Johan Horak', 'Marketing at CapeHolidays'); ?>
                                </div>
						</div><!-- testimonials -->
						<div class="button-wrap clear">
							<div class="left">
                                <?php $welcome->display_button( 'Start Adding Testimonials', esc_url( admin_url( 'edit.php?post_type=wpm-testimonial' ) ), true ); ?>
							</div>
							<div class="right">
                                <?php $welcome->display_button( 'Upgrade Now', 'https://strongtestimonials.com/pricing/?utm_source=welcome_banner&utm_medium=upgradenow&utm_campaign=welcome_banner', true ); ?>
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
