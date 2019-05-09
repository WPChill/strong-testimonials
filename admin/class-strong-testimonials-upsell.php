<?php
/**
 * Class Strong_Testimonials_Upsell
 *
 * @since 2.38
 */
class Strong_Testimonials_Upsell {

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'add_general_upsell_notice' ), 11 );
		add_action( 'wpmtst_after_form_type_selection', array( $this, 'add_upsells_1' ) );
		add_action( 'wpmtst_before_fields_settings', array( $this, 'add_upsells_2' ) );
		add_action( 'wpmtst_view_editor_after_groups', array( $this, 'add_upsells_3' ) );
		add_action( 'wpmtst_view_editor_after_group_select', array( $this, 'add_upsells_4' ) );
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
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
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
					<a class="button button-primary" target="_blank" href="https://strongtestimonials.com/pricing"><?php esc_html_e( 'View pricing', 'strong-testimonials' ); ?></a>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
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
				<?php esc_html_e( 'Install the Strong Testimonials Country Selector extension.', 'strong-testimonials' ); ?>
				<a class="button button-primary wpmtst-alert__upgrade-btn" target="_blank" href="https://strongtestimonials.com/pricing"><?php esc_html_e( 'Upgrade to PRO', 'strong-testimonials' ); ?></a>
			</div>
			<?php
		endif;

		if ( ! defined( 'WPMTST_CUSTOM_FIELDS_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 10px">
				<?php esc_html_e( 'Know your customers by having access to more advanced custom fields.', 'strong-testimonials' ); ?>
				<br/>
				<?php esc_html_e( 'Install the Strong Testimonials Custom Fields extension.', 'strong-testimonials' ); ?>
				<a class="button button-primary wpmtst-alert__upgrade-btn" target="_blank" href="https://strongtestimonials.com/pricing"><?php esc_html_e( 'Upgrade to PRO', 'strong-testimonials' ); ?></a>
			</div>
			<?php
		endif;
	}

	public function add_upsells_2() {
		if ( ! defined( 'WPMTST_MULTIPLE_FORMS_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 10px">
				<?php esc_html_e( 'Create multiple submission forms by installing the Strong Testimonials Multiple Forms extension.', 'strong-testimonials' ); ?>
				<a class="button button-primary wpmtst-alert__upgrade-btn" target="_blank" href="https://strongtestimonials.com/pricing"><?php esc_html_e( 'Upgrade to PRO', 'strong-testimonials' ); ?></a>
			</div>
			<?php
		endif;
	}

	public function add_upsells_3() {
		if ( ! defined( 'WPMTST_REVIEW_MARKUP_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 10px">
				<?php esc_html_e( 'Add SEO-friendly Testimonials with our Schema.org Review Markup extension.', 'strong-testimonials' ); ?>
				<a class="button button-primary wpmtst-alert__upgrade-btn" target="_blank" href="https://strongtestimonials.com/pricing"><?php esc_html_e( 'Upgrade to PRO', 'strong-testimonials' ); ?></a>
			</div>
			<?php
		endif;
	}

	public function add_upsells_4() {
		if ( ! defined( 'WPMTST_ADVANCED_VIEWS_VERSION' ) ) :
			?>
			<div class="wpmtst-alert" style="margin-top: 1.5rem">
				<?php esc_html_e( 'Display testimonials based on their rating by installing the Strong Testimonials Advanced Views extension.', 'strong-testimonials' ); ?>
				<a class="button button-primary wpmtst-alert__upgrade-btn" target="_blank" href="https://strongtestimonials.com/pricing"><?php esc_html_e( 'Upgrade to PRO', 'strong-testimonials' ); ?></a>
			</div>
			<?php
		endif;
	}



}

new Strong_Testimonials_Upsell();
