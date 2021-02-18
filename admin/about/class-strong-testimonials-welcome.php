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
			array( $this, 'output' )
		);
	}

	public function output() {
		?>
		<div id="wpmtst-welcome">

			<div class="container">

				<div class="hero features">

					<div class="mascot">
						<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/mascot-2.svg" alt="<?php esc_attr_e( 'Strong Testimonials Mascot', 'strong-testimonials' ); ?>">
					</div>

					<div class="block">
						<h1><?php esc_html_e( 'Welcome to Strong Testimonials', 'strong-testimonials' ); ?></h1>
						<h6><?php esc_html_e( 'Thank you for choosing Strong Testimonials - Build trust and credibility with your products.', 'strong-testimonials' ); ?></h6>
					</div>

					<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/banner.png" alt="<?php esc_attr_e( 'Watch how to', 'strong-testimonials' ); ?>" class="video-thumbnail">

					<div class="block">

						<div class="feature-list clear">

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/7.svg">
								<h5><?php esc_html_e( 'Increase Conversions', 'strong-testimonials' ); ?></h5>
								<p><?php esc_html_e( 'Make customers 63% more likely to purchase with testimonials that drive sales.', 'strong-testimonials' ); ?></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/8.svg">
								<h5><?php esc_html_e( 'Collect Testimonials', 'strong-testimonials' ); ?></h5>
								<p><?php esc_html_e( 'Easily collect testimonials from customers by creating forms.', 'strong-testimonials' ); ?></p>
							</div>

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/5.svg">
								<h5><?php esc_html_e( 'Multiple Layouts', 'strong-testimonials' ); ?></h5>
								<p><?php esc_html_e( 'Choose from four unique layouts for your testimonials.', 'strong-testimonials' ); ?></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/14.svg">
								<h5><?php esc_html_e( 'SEO-friendly', 'strong-testimonials' ); ?></h5>
								<p><?php esc_html_e( 'SEO-friendly testimonials that your customers and Search Engines can understand.', 'strong-testimonials' ); ?></p>
							</div>

						</div>

						<div class="button-wrap clear">
							<div class="left">
								<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpm-testimonial' ) ); ?>" class="wpmtst-btn wpmtst-btn-block wpmtst-btn-lg wpmtst-btn-purple">
									<?php esc_html_e( 'Start Adding Testimonials', 'strong-testimonials' ); ?>
								</a>
							</div>
							<div class="right">
								<a href="https://strongtestimonials.com/docs?utm_source=welcome_banner&utm_medium=readdocs&utm_campaign=welcome_banner"
									class="wpmtst-btn wpmtst-btn-block wpmtst-btn-lg" target="_blank">
									<?php esc_html_e( 'Read the Docs', 'strong-testimonials' ); ?>
								</a>
							</div>
						</div>

					</div>

				</div><!-- hero -->

				<div class="features">

					<div class="block">

						<h1><?php esc_html_e( 'Strong Testimonials Extensions', 'strong-testimonials' ); ?></h1>
						<h6><?php esc_html_e( 'Sales copy grabs attention - testimonials drive sales.', 'strong-testimonials' ); ?></h6>

						<div class="feature-list clear">

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/1.svg">
								<h5><?php esc_html_e( 'Pro Templates', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Create beautiful testimonial designs with a number of predesigned and easy-to-use premium templates.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/pro-templates?utm_source=welcome_banner&utm_medium=pro-templates&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/infinitescroll.svg">
								<h5><?php esc_html_e( 'Infinite Scroll', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Change properties of the testimonial post type: labels, permalink structure, admin options and post editor features.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/infinite-scroll/?utm_source=welcome_banner&utm_medium=infinite-scroll&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/12.svg">
								<h5><?php esc_html_e( 'Testimonial Assignment', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Assign testimonials to custom post types for easy management and filtering.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/assignment?utm_source=welcome_banner&utm_medium=assignment&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/11.svg">
								<h5><?php esc_html_e( 'Custom Properties', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Change properties of the testimonial post type: labels, permalink structure, admin options and post editor features.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/properties?utm_source=welcome_banner&utm_medium=properties&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/6.svg">
								<h5><?php esc_html_e( 'Advanced View Settings', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Customize your testimonials beyond star ratings, reorder fields and more.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/advanced-views?utm_source=welcome_banner&utm_medium=advanced-views&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/9.svg">
								<h5><?php esc_html_e( 'Multiple Submission Forms', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Easily collect testimonials from customers by creating and customizing multiple forms at once.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/multiple-forms?utm_source=welcome_banner&utm_medium=multiple-forms&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/10.svg">
								<h5><?php esc_html_e( 'Custom Form Fields', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Enhance your submission forms to both collect and display additional information.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/custom-fields?utm_source=welcome_banner&utm_medium=custom-fields&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/14.svg">
								<h5><?php esc_html_e( 'SEO-friendly Testimonials', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Take full advantage of your testimonials with our Schema.org Markup extension.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/review-markup?utm_source=welcome_banner&utm_medium=review-markup&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/13.svg">
								<h5><?php esc_html_e( 'Spam Control', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Protect your testimonial submission forms from spam and other types of automated abuse.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/captcha?utm_source=welcome_banner&utm_medium=captcha&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/15.svg">
								<h5><?php esc_html_e( 'Testimonial Importer', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Import reviews from 3rd party sites like: Facebook, Google, Yelp, Zomato & WooCommerce', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/importer?utm_source=welcome_banner&utm_medium=importer&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/email.svg">
								<h5><?php esc_html_e( 'Enhanced Emails', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'We added the option to send an email to the customer upon new testimonial submission. Also, to send an email to the customer when the testimonial is approved.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/enhanced-emails/?utm_source=welcome_banner&utm_medium=enhanced-emails&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/filter.svg">
								<h5><?php esc_html_e( 'Filters', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Now you can use categories to group your testimonials and have your clients read reviews grouped by service/product type.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/filters/?utm_source=welcome_banner&utm_medium=filters&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block first">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/rolemanagement.svg">
								<h5><?php esc_html_e( 'Role Management', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'We’re giving power back to the users and admins can decide which user roles are worthy of adding, editing, or removing testimonials.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/extensions/role-management/?utm_source=welcome_banner&utm_medium=role-management&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

							<div class="feature-block last">
								<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/features/mailchimp.svg">
								<h5><?php esc_html_e( 'Mailchimp integration', 'strong-testimonials' ); ?><div class="pro-label">PRO</div></h5>
								<p><?php esc_html_e( 'Now you can subscribe your customers to a Mailchimp list.', 'strong-testimonials' ); ?><br/><a target="_blank" href="https://strongtestimonials.com/pricing/?utm_source=welcome_banner&utm_medium=upgradenow&utm_campaign=welcome_banner"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a></p>
							</div>

						</div><!-- feature-list -->

					</div>

				</div><!-- features -->

			
				<div class="upgrade">
					<div class="block">
						<a href="https://strongtestimonials.com/pricing/?utm_source=welcome_banner&utm_medium=upgradenow&utm_campaign=welcome_banner" target="_blank"
							class="wpmtst-btn wpmtst-btn-lg wpmtst-btn-orange">
							<?php esc_html_e( 'Upgrade Now', 'strong-testimonials' ); ?>
						</a>

					</div>
				</div><!--/.upgrade-->


				<div class="testimonials">

					<div class="block clear">
					<h1>Happy users of the Strong Testimonials - premium version</h1>
						<div class="testimonial-block left">
							<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/testimonial-image-1.jpg">
							<p><?php esc_html_e( 'Strong Testimonials is my new, go-to resource for creating pages with multiple staff bios or testimonials. It’s extremely easy to use, update, and customize, and that makes it an invaluable asset. Highly recommend!', 'strong-testimonials' ); ?>
							<div style="background-image: url(<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/star.svg)" class="testimonial-stars"></div>
							<p><strong><?php esc_html_e( 'Ryan Haught' ); ?></strong><br/><?php esc_html_e( 'Digital Marketer at Heaven’s Family' ); ?></p>
						</div>

						<div class="testimonial-block right">
							<img src="<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/testimonial-image-2.jpeg">
							<p><?php esc_html_e( 'I have used various testimonials plugins. What I get here for free is just amazing. The support is great. And updates fresh. Looking at the ability to get reviews indexed by Google is more than enough reason to get an upgrade.', 'strong-testimonials' ); ?>
							<div style="background-image: url(<?php echo esc_attr( WPMTST_ADMIN_URL ); ?>/img/star.svg)" class="testimonial-stars"></div>
							<p><strong><?php esc_html_e( 'Johan Horak' ); ?></strong><br/><?php esc_html_e( 'Marketing at CapeHolidays' ); ?></p>
						</div>

					</div>

				</div><!-- testimonials -->

				<div class="footer">

					<div class="block clear">

						<div class="button-wrap clear">
							<div class="left">
								<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpm-testimonial' ) ); ?>"
									class="wpmtst-btn wpmtst-btn-block wpmtst-btn-lg wpmtst-btn-purple">
									<?php esc_html_e( 'Start Adding Testimonials', 'strong-testimonials' ); ?>
								</a>
							</div>
							<div class="right">
								<a href="https://strongtestimonials.com/pricing/?utm_source=welcome_banner&utm_medium=upgradenow&utm_campaign=welcome_banner" target="_blank"
									class="wpmtst-btn wpmtst-btn-block wpmtst-btn-lg wpmtst-btn-purple">
									<?php esc_html_e( 'Upgrade now', 'strong-testimonials' ); ?>
								</a>
							</div>
						</div>

					</div>

				</div><!-- footer -->

			</div><!-- container -->

		</div><!-- wpmtst welcome -->
		<?php
	}


}

new Strong_Testimonials_Welcome();
