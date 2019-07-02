<?php
/**
 * Class Strong_Testimonials_Upsell
 *
 * @since 2.38
 */
class Strong_Testimonials_Upsell {

	public $store_upgrade_url;

	public function __construct() {
		$this->set_store_upgrade_url();

		add_action( 'admin_notices', array( $this, 'add_general_upsell_notice' ), 11 );
		add_action( 'wpmtst_after_form_type_selection', array( $this, 'add_upsells_1' ) );
		add_action( 'wpmtst_before_fields_settings', array( $this, 'add_upsells_2' ) );
		add_action( 'wpmtst_view_editor_after_groups', array( $this, 'add_upsells_3' ) );
		add_action( 'wpmtst_view_editor_after_group_select', array( $this, 'add_upsells_4' ) );
		add_action( 'wpmtst_fields_before_fields_editor_preview', array( $this, 'add_upsells_5' ) );
		add_action( 'wpmtst_after_form_settings', array( $this, 'add_upsells_6' ) );
		add_action( 'wpmtst_views_after_template_list', array( $this, 'add_upsells_7' ) );
	}

	public function set_store_upgrade_url() {

		$this->store_upgrade_url = WPMTST_STORE_UPGRADE_URL . '?utm_source=st-lite&utm_campaign=upsell';

		//append license key
		$license = trim( get_option( 'strong_testimonials_license_key' ) );
		if ( $license ) {
			$this->store_upgrade_url .= '&license=' . $license;
		}

	}

	public function add_general_upsell_notice() {
		$screen = get_current_screen();
		if ( $screen->id !== 'edit-wpm-testimonial' && $screen->id !== 'wpm-testimonial_page_testimonial-views' ) {
			return;
		}

		$notices = get_option( 'wpmtst_admin_notices', array() );
		?>

		<div class="notice wpmtst-notice-wrap">

			<?php if ( array_key_exists( 'feedback-notice', $notices ) ) : ?>
				<div class="wpmtst-notice wpmtst-notice--feedback" data-key="feedback-notice" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpmtst-admin' ) ); ?>">
					<div class="wpmtst-notice--feedback__bg"></div>
					<h2><?php esc_html_e( 'Feature Request', 'strong-testimonials' ); ?></h2>
					<p><?php esc_html_e( 'Do you enjoy using Strong Testimonials? Please take a minute to suggest a feature or tell us what you think.', 'strong-testimonials' ); ?></p>
					<a class="button" target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLScch0AchtnzxJsSrjUcW9ypcr1fZ9r-vyk3emEp8Sv47brb2g/viewform"><?php esc_html_e( 'Submit Feedback', 'strong-testimonials' ); ?></a>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'strong-testimonials' ); ?></span></button>
				</div><!-- wpmtst-notice--feedback -->
			<?php endif; ?>

			<?php if ( array_key_exists( 'upsell-notice', $notices ) && ! wpmtst_extensions_installed() ) : ?>
				<div class="wpmtst-notice wpmtst-notice--upsell" data-key="upsell-notice" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpmtst-admin' ) ); ?>">
					<div class="wpmtst-notice--upsell__bg"></div>
					<h2><?php esc_html_e( 'Upgrade to PRO', 'strong-testimonials' ); ?></h2>
					<p>
						<?php esc_html_e( 'Build trust and credibility with your products.', 'strong-testimonials' ); ?><br/>
						<?php esc_html_e( 'Do more with Strong Testimonials extensions.', 'strong-testimonials' ); ?>
					</p>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=' . $screen->id . '-notice-upsell' ); ?>"><?php esc_html_e( 'View pricing', 'strong-testimonials' ); ?></a>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'strong-testimonials' ); ?></span></button>
				</div><!-- wpmtst-notice--upsell -->
			<?php endif; ?>

		</div><!-- wpmtst-notice-wrap -->
		<?php
	}

	public function add_upsells_1() {

		if ( ! defined( 'WPMTST_COUNTRY_SELECTOR_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 10px">
				<?php esc_html_e( 'Want to know where are your customers located?', 'strong-testimonials' ); ?>
				<br/>
				<?php
				printf(
					esc_html__( 'Install the %s extension', 'strong-testimonials' ),
					sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( WPMTST_STORE_URL . '/extensions/country-selector?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-country-selector-upsell' ),
						esc_html__( 'Strong Testimonials: Country Selector', 'strong-testimonials' )
					)
				);
				?>
				<p>
					<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/country-selector?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-country-selector-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=fields-country-selector-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<?php
		endif;

		if ( ! defined( 'WPMTST_CUSTOM_FIELDS_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 10px">
				<?php esc_html_e( 'Know your customers by having access to more advanced custom fields.', 'strong-testimonials' ); ?>
				<br/>
				<?php
				printf(
					esc_html__( 'Install the %s extension', 'strong-testimonials' ),
					sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( WPMTST_STORE_URL . '/extensions/custom-fields?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-custom-fields-upsell' ),
						esc_html__( 'Strong Testimonials: Custom Fields', 'strong-testimonials' )
					)
				);
				?>
				<p>
					<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/custom-fields?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-custom-fields-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=fields-custom-fields-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<?php
		endif;
	}

	public function add_upsells_2() {
		if ( ! defined( 'WPMTST_MULTIPLE_FORMS_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 10px">
				<?php
				printf(
					esc_html__( 'Create multiple submission forms by installing the %s extension.', 'strong-testimonials' ),
					sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( WPMTST_STORE_URL . '/extensions/multiple-forms?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-multiple-forms-upsell' ),
						esc_html__( 'Strong Testimonials: Multiple Forms', 'strong-testimonials' )
					)
				);
				?>
				<p>
					<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/multiple-forms?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-multiple-forms-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=fields-multiple-forms-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<?php
		endif;
	}

	public function add_upsells_3() {
		if ( ! defined( 'WPMTST_REVIEW_MARKUP_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 10px">
				<?php
				printf(
					esc_html__( 'Add SEO-friendly Testimonials with our %s extension.', 'strong-testimonials' ),
					sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( WPMTST_STORE_URL . '/extensions/review-markup?utm_source=st-lite&utm_campaign=upsell&utm_medium=views-review-markup-upsell' ),
						esc_html__( 'Strong Testimonials: Review Markup', 'strong-testimonials' )
					)
				);
				?>
				<p>
					<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/review-markup?utm_source=st-lite&utm_campaign=upsell&utm_medium=views-review-markup-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=views-review-markup-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<?php
		endif;
	}

	public function add_upsells_4() {
		if ( ! defined( 'WPMTST_ADVANCED_VIEWS_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 1.5rem">
				<?php
				printf(
					esc_html__( 'Display testimonials based on their rating by installing the %s extension.', 'strong-testimonials' ),
					sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( WPMTST_STORE_URL . '/extensions/advanced-views?utm_source=st-lite&utm_campaign=upsell&utm_medium=views-advanced-views-upsell' ),
						esc_html__( 'Strong Testimonials: Advanced Views', 'strong-testimonials' )
					)
				);
				?>
				<p>
					<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/advanced-views?utm_source=st-lite&utm_campaign=upsell&utm_medium=views-advanced-views-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=views-advanced-views-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<?php
		endif;
	}

	public function add_upsells_5() {
		if ( ! defined( 'WPMTST_CAPTCHA_VERSION' ) ) :
			?>
			<div class="wpmtst-alert">
				<?php
				printf(
					esc_html__( 'Protect your form against spam with the %s extension.', 'strong-testimonials' ),
					sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( WPMTST_STORE_URL . '/extensions/captcha?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ),
						esc_html__( 'Strong Testimonials: Captcha', 'strong-testimonials' )
					)
				);
				?>
				<p>
					<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/captcha?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=form-settings-captcha-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<?php
		endif;
	}

	public function add_upsells_6() {
		if ( defined( 'WPMTST_CAPTCHA_VERSION' ) ) {
			return;
		}
		?>
		<hr>

		<h3><?php esc_html_e( 'Form Spam Control', 'strong-testimonials' ); ?></h3>

		<div class="wpmtst-alert">
			<?php
			printf(
				esc_html__( 'Protect your form against spam. Add Google recaptcha or honeypots with the %s extension.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/captcha?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ),
					esc_html__( 'Strong Testimonials: Captcha', 'strong-testimonials' )
				)
			);
			?>
			<p>
				<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/captcha?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=form-settings-captcha-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
			</p>
		</div>

		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr>
				<th scope="row">
					<label>
						<?php esc_html_e( 'Honeypot', 'strong-testimonials' ); ?>
					</label>
				</th>
				<td>
					<p>
						<?php esc_html_e( 'These methods for trapping spambots are both time-tested and widely used. May be used simultaneously for more protection.', 'strong-testimonials' ); ?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label>
						<a name="captcha-section"></a><?php esc_html_e( 'Captcha', 'strong-testimonials' ); ?>
					</label>
				</th>
				<td>
					<?php esc_html_e( 'Google reCAPTCHA prompts visitors to check a box to prove that they’re not a robot before they submit the form.', 'strong-testimonials' ); ?>
					<br/>
					<?php esc_html_e( 'In some cases, they’re prompted to complete another task, like identify a string of letters.', 'strong-testimonials' ); ?>
					<br/>
					<?php esc_html_e( 'This method makes it difficult for spambots to complete form submissions.', 'strong-testimonials' ); ?>
				</td>
			</tr>
		</table>
		<?php
	}

	public function add_upsells_7() {
		if ( ! defined( 'WPMTST_ADVANCED_VIEWS_VERSION' ) ) :
			?>
			<div class="wpmtst-alert">
				<?php
				printf(
					esc_html__( 'Easily define the display order of your testimonial fields with the %s extension.', 'strong-testimonials' ),
					sprintf(
						'<a href="%s" target="_blank">%s</a>',
						esc_url( WPMTST_STORE_URL . '/extensions/advanced-views?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ),
						esc_html__( 'Strong Testimonials: Advanced Views', 'strong-testimonials' )
					)
				);
				?>
				<p>
					<a class="button" target="_blank" href="<?php echo esc_url( WPMTST_STORE_URL . '/extensions/advanced-views?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ); ?>"><?php esc_html_e( 'Learn More', 'strong-testimonials' ); ?></a>
					<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=views-advanced-views-upsell' ); ?>"><?php esc_html_e( 'Upgrade', 'strong-testimonials' ); ?></a>
				</p>
			</div>
			<?php
		endif;
	}





}

new Strong_Testimonials_Upsell();
