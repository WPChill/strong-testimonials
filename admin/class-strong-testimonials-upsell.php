<?php
/**
 * Class Strong_Testimonials_Upsell
 *
 * @since 2.38
 */
class Strong_Testimonials_Upsell {

	/**
	 * Holds the upsells object
	 *
	 * @var bool
	 */
	private $wpchill_upsells = false;

	public $store_upgrade_url;

	public function __construct() {
		$this->set_offer();
		$this->set_store_upgrade_url();
		$options = get_option( 'wpmtst_options' );

		if ( isset( $options['disable_upsells'] ) && $options['disable_upsells'] ) {
			return;
		}

		add_action( 'wpmtst_admin_after_settings_form', array( $this, 'general_upsell' ) );

		if ( class_exists( 'Strong_Testimonials_WPChill_Upsells' ) ) {

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_filter( 'wpmtst_submenu_pages', array( $this, 'add_submenu' ) );

			// Initialize WPChill upsell class
			$args = apply_filters(
				'upsells_args',
				array(
					'shop_url' => 'https://strongtestimonials.com/',
					'slug'     => 'strong-testimonials',
				)
			);

			$wpchill_upsell = Strong_Testimonials_WPChill_Upsells::get_instance( $args );

			// output wpchill lite vs pro page
			add_action( 'st_lite_vs_premium_page', array( $wpchill_upsell, 'lite_vs_premium' ), 30, 1 );
			add_filter( 'st_uninstall_transients', array( $wpchill_upsell, 'smart_upsells_transients' ), 15 );

			$this->wpchill_upsells = $wpchill_upsell;
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-role-management' ) ) {
			add_action( 'wpmtst_settings_tabs', array( $this, 'register_role_manager' ), 4, 2 );
			add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_rm_settings_page' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_role_upsell' ), 20 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-country-selector' ) ) {
			add_action( 'wpmtst_after_form_type_selection', array( $this, 'output_country_selector_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_country_selector_upsell' ), 95 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-custom-fields' ) ) {
			add_action( 'wpmtst_after_form_type_selection', array( $this, 'output_custom_fields_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_custom_fields_upsell' ), 90 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-multiple-forms' ) ) {
			add_action( 'wpmtst_before_fields_settings', array( $this, 'output_multiple_form_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_multiple_form_upsell' ), 30 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-review-markup' ) ) {
			add_action( 'wpmtst_view_editor_after_groups', array( $this, 'output_review_markup_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_review_markup_upsell' ), 15 );
			add_action( 'wpmtst_settings_tabs', array( $this, 'register_review_markup' ), 4, 2 );
			add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_review_markup_settings_page' ) );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-advanced-views' ) ) {
			add_action( 'wpmtst_view_editor_after_group_select', array( $this, 'output_advanced_views_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_advanced_views_upsell' ), 35 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-captcha' ) ) {
			add_action( 'wpmtst_fields_before_fields_editor_preview', array( $this, 'output_captcha_editor_upsell' ) );
			add_action( 'wpmtst_after_form_settings', array( $this, 'output_captcha_form_settings_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_captcha_upsell' ), 40 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-pro-templates' ) ) {
			add_action( 'wpmtst_views_after_template_list', array( $this, 'output_pro_templates_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_pro_templates_upsell' ), 20 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-emails' ) ) {
			add_action( 'wpmtst_after_mail_notification_settings', array( $this, 'output_enhanced_emails_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_enhanced_emails_upsell' ), 45 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-infinite-scroll' ) ) {
			add_action( 'wpmtst_view_editor_pagination_row_end', array( $this, 'output_infinite_scroll_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_infinite_scroll_upsell' ), 50 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-filters' ) ) {
			add_action( 'wpmtst_after_style_view_section', array( $this, 'output_filters_upsell' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_filters_upsell' ), 15 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-pro' ) ) {
			add_action( 'wpmtst_settings_tabs', array( $this, 'register_st_pro_tab' ), 4, 2 );
			add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_st_pro_page' ) );
			add_filter( 'wpmtst_general_upsell_items', array( $this, 'add_pro_upsell' ), 10 );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-assignment' ) ) {
			add_action( 'wpmtst_settings_tabs', array( $this, 'register_assigment_tab' ), 4, 2 );
			add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_assigment_settings_page' ) );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-properties' ) ) {
			add_action( 'wpmtst_settings_tabs', array( $this, 'register_properties_tab' ), 4, 2 );
			add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_properties_page' ) );
		}

		if ( $this->is_upgradable_addon( 'strong-testimonials-mailchimp' ) ) {
			add_action( 'wpmtst_after_form_settings', array( $this, 'output_mailchip_form_settings_upsell' ) );
		}
	}

	public function is_upgradable_addon( $addon ) {

		if ( $this->wpchill_upsells && $this->wpchill_upsells->is_upgradable_addon( $addon ) ) {
			return true;
		}

		return false;
	}

	public function add_meta_boxes() {

		if ( $this->is_upgradable_addon( 'strong-testimonials-imports' ) ) {

			// remove "submitdiv" metabox so we can add it back in desired order.
			$post_type = 'wpm-testimonial';
			remove_meta_box( 'post_submit_meta_box', $post_type, 'side' );
			add_meta_box( 'submitdiv', __( 'Publish', 'strong-testimonials' ), 'post_submit_meta_box', $post_type, 'side', 'high' );

			add_meta_box(
				'wpmtst-importer-upsell',      // Unique ID
				esc_html__( 'Import', 'strong-testimonials' ),    // Title
				array( $this, 'output_importer_upsell' ),   // Callback function
				'wpm-testimonial',         // Admin page (or post type)
				'side',         // Context
				'high'         // Priority
			);
		}
	}

	public function set_store_upgrade_url() {

		$this->store_upgrade_url = WPMTST_STORE_UPGRADE_URL . '?utm_source=st-lite&utm_campaign=upsell';

		//append license key
		$license = trim( get_option( 'strong_testimonials_license_key' ) );
		if ( $license ) {
			$this->store_upgrade_url .= '&license=' . $license;
		}
	}

	public function output_importer_upsell() {
		?>
		<div class="wpmtst-alert">
			<h2><?php esc_html_e( 'Automatically pull in & display new reviews as your customers leave their feedback on external platforms', 'strong-testimonials' ); ?></h2>
			<p><?php esc_html_e( 'Upgrade today and get the ability to import testimonials from:', 'strong-testimonials' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'Facebook', 'strong-testimonials' ); ?></li>
				<li><?php esc_html_e( 'Google', 'strong-testimonials' ); ?></li>
				<li><?php esc_html_e( 'Yelp', 'strong-testimonials' ); ?></li>
				<li><?php esc_html_e( 'Zomato', 'strong-testimonials' ); ?></li>
				<li><?php esc_html_e( 'WooCommerce', 'strong-testimonials' ); ?></li>
				<li><?php esc_html_e( 'and more...', 'strong-testimonials' ); ?></li>
			</ul>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=importer-metabox' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade Now', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function general_upsell() {

		$general_upsells = apply_filters( 'wpmtst_general_upsell_items', array() );

		if ( ! empty( $general_upsells ) ) {

			?>

		<div class="wpmtst-settings-upsell">
			<div class="wpmtst-alert">
				<h3><?php esc_html_e( 'Upgrade now', 'strong-testimonials' ); ?></h3>
				<ul>
					<?php foreach ( $general_upsells as $general_upsell ) { ?>
						<li>
							<span>
								<?php echo wp_kses_post( $general_upsell ); ?>
							</span>
						</li>
					<?php } ?>
				</ul>

				<a href="<?php echo esc_url( WPMTST_STORE_URL . '/pricing?utm_source=st-lite&utm_campaign=upsell&utm_medium=general-settings-upsell' ); ?>"
					target="_blank" class="button button-primary button-hero"
					style="width:100%;display:block;margin-top:20px;text-align:center;"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade now', 'strong-testimonials' ) ) ); ?></a>

			</div>
		</div>

			<?php
		}
	}

	// Role Manager upsell
	public function register_role_manager( $active_tab, $url ) {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		printf(
			'<a href="%s" class="nav-tab %s">%s%s</a>',
			esc_url( add_query_arg( 'tab', 'access', $url ) ),
			esc_attr( 'access' === $tab ? 'nav-tab-active' : '' ),
			esc_html_x( 'Role Management', 'adjective', 'strong-testimonials' ),
			'<span class="wpmtst-upsell-badge">PRO</span>'
		);
	}

	public function register_rm_settings_page( $pages ) {
		$pages['access'] = array( $this, 'output_role_manager_page' );
		return $pages;
	}

	public function output_role_manager_page() {
		?>
		<div class="wpmtst-alert">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonial extension page.
				esc_html__( 'Control who approves testimonials or who has access to the plugins’ settings panel with %s extension. Get total granular control over who has access to your testimonials.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/role-management?utm_source=st-lite&utm_campaign=upsell&utm_medium=role-management-tab-upsell' ),
					esc_html__( 'Role Management', 'strong-testimonials' )
				)
			);
			?>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=role-management-tab-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_role_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonial extension page.
			esc_html__( 'Control who approves testimonials or who has access to the plugins’ settings panel with %s extension. Get total granular control over who has access to your testimonials.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/role-management?utm_source=st-lite&utm_campaign=upsell&utm_medium=role-management-general-upsell' ),
				esc_html__( 'Role Management', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Country Selector
	*/
	public function output_country_selector_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top: 10px">
			<?php esc_html_e( 'Want to know where are your customers located?', 'strong-testimonials' ); ?>
			<br/>
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonial extension page.
				esc_html__( 'Install the %s extension', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/country-selector?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-country-selector-upsell' ),
					esc_html__( 'Strong Testimonials: Country Selector', 'strong-testimonials' )
				)
			);
			?>
			<p>

				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=fields-country-selector-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_country_selector_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Show where your customers are located with the %s extension. ', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/country-selector?utm_source=st-lite&utm_campaign=upsell&utm_medium=country-selector-general-upsell' ),
				esc_html__( 'Country Selector', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Custom fields
	*/
	public function output_custom_fields_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top: 10px">
			<?php esc_html_e( 'Know your customers by having access to more advanced custom fields.', 'strong-testimonials' ); ?>
			<br/>
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Install the %s extension', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/custom-fields?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-custom-fields-upsell' ),
					esc_html__( 'Strong Testimonials: Custom Fields', 'strong-testimonials' )
				)
			);
			?>
			<p>

				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=fields-custom-fields-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_custom_fields_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Get to know your customers by installing our %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/custom-fields?utm_source=st-lite&utm_campaign=upsell&utm_medium=custom-fields-general-upsell' ),
				esc_html__( 'Custom Fields', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	* Multiple forms
	*/
	public function output_multiple_form_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top: 10px">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Create multiple submission forms by installing the %s extension.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/multiple-forms?utm_source=st-lite&utm_campaign=upsell&utm_medium=fields-multiple-forms-upsell' ),
					esc_html__( 'Strong Testimonials: Multiple Forms', 'strong-testimonials' )
				)
			);
			?>
			<p>

				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=fields-multiple-forms-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_multiple_form_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Create multiple submission forms by installing the %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/multiple-forms?utm_source=st-lite&utm_campaign=upsell&utm_medium=multiple-forms-general-upsell' ),
				esc_html__( 'Multiple Forms', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	* Review Markup
	*/
	public function register_review_markup( $active_tab, $url ) {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		printf(
			'<a href="%s" class="nav-tab %s">%s%s</a>',
			esc_url( add_query_arg( 'tab', 'review-markup', $url ) ),
			esc_attr( 'review-markup' === $tab ? 'nav-tab-active' : '' ),
			esc_html_x( 'Review Markup', 'adjective', 'strong-testimonials' ),
			'<span class="wpmtst-upsell-badge">PRO</span>'
		);
	}
	public function register_review_markup_settings_page( $pages ) {
		$pages['review-markup'] = array( $this, 'output_review_markup_upsell' );
		return $pages;
	}
	public function output_review_markup_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top: 10px">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Add SEO-friendly & Schema.org compliant Testimonials with our %s extension.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/review-markup?utm_source=st-lite&utm_campaign=upsell&utm_medium=views-review-markup-upsell' ),
					esc_html__( 'Strong Testimonials: Review Markup', 'strong-testimonials' )
				)
			);
			?>
				<ul>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'With this extensions, search engines will display star ratings in search results for your site.', 'strong-testimonials' ); ?></li>
				</ul>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=views-review-markup-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_review_markup_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Add SEO-friendly & Schema.org compliant Testimonials with our %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/review-markup?utm_source=st-lite&utm_campaign=upsell&utm_medium=review-markup-general-upsell' ),
				esc_html__( 'Review Markup', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Advanced Views
	*/
	public function output_advanced_views_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top: 1.5rem">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'With the %s extension you can:', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/advanced-views?utm_source=st-lite&utm_campaign=upsell&utm_medium=views-advanced-views-upsell' ),
					esc_html__( 'Strong Testimonials: Advanced Views', 'strong-testimonials' )
				)
			);

			?>
			<ul>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'filter & display testimonials based on their rating or on a pre-defined condition.', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'easily define the display order of your testimonial fields. Re-order the name, image, url and testimonial content fields through drag & drop.', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'edit, in real time, the way your testimonials will look on your site. Stop losing clients because of poor design.', 'strong-testimonials' ); ?></li>

			</ul>
			<p>

				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=views-advanced-views-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_advanced_views_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Start filtering, changing the order, or even editing your testimonials in real-time with the %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/advanced-views?utm_source=st-lite&utm_campaign=upsell&utm_medium=advanced-views-general-upsell' ),
				esc_html__( 'Advanced Views', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Captcha extensio
	*/
	public function output_captcha_editor_upsell() {
		?>
		<div class="wpmtst-alert">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Protect your form against spam with the %s extension.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/captcha?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ),
					esc_html__( 'Strong Testimonials: Captcha', 'strong-testimonials' )
				)
			);
			?>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=form-settings-captcha-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function output_captcha_form_settings_upsell() {
		?>
		<hr>

		<h3><?php esc_html_e( 'Form Spam Control', 'strong-testimonials' ); ?></h3>

		<div class="wpmtst-alert">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Protect your form against spam. Add Google reCAPTCHA or honeypot anti-spam with the %s extension.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/captcha?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ),
					esc_html__( 'Strong Testimonials: Captcha', 'strong-testimonials' )
				)
			);
			?>

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
			<p>
				<a class="button button-primary" target="_blank"
					href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=form-settings-captcha-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_captcha_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Protect your form against spam. Add Google ReCaptcha or honeypot anti-spam with the %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/captcha?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-captcha-general-upsell' ),
				esc_html__( 'Captcha', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	PRO Templates
	*/
	public function output_pro_templates_upsell() {
		?>
		<div class="wpmtst-alert">
			<?php
			echo wp_kses_post( sprintf( __( 'With the %1$sStrong Testimonials: PRO Templates%2$s you can impress your potential clients with profesionally designed, pixel-perfect templates that increase your chances of standing out and landing more clients.', 'strong-testimonials' ), '<a href="' . WPMTST_STORE_URL . '/extensions/pro-templates/" target="_blank">', '</a>' ) );
			?>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=views-pro-templates-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_pro_templates_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Get access to professionally designed testimonial templates with the %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/pro-templates?utm_source=st-lite&utm_campaign=upsell&utm_medium=pro-templates-general-upsell' ),
				esc_html__( 'Pro Templates', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Enhanced Emails
	*/
	public function output_enhanced_emails_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top: 10px">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Use the %s extension to:', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/enhanced-emails?utm_source=st-lite&utm_campaign=upsell&utm_medium=enhanced-emails-upsell' ),
					esc_html__( 'Strong Testimonials: Enhanced Emails', 'strong-testimonials' )
				)
			);
			?>
				<ul>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'send a thank you email to your client once his testimonial\'s approved', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'increase brand loyalty by showing you really care about your clients', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'keep your clients engaged and increase your chances of selling more', 'strong-testimonials' ); ?></li>
				</ul>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=enhanced-emails-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_enhanced_emails_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Send a thank-you email to your clients once their testimonial is approved using %s extension. This way, you increase brand loyalty and grow your chances of seeling more. ', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/enhanced-emails?utm_source=st-lite&utm_campaign=upsell&utm_medium=enhanced-emails-general-upsell' ),
				esc_html__( 'Enhanced Emails', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Inifinite Scroll
	*/
	public function output_infinite_scroll_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top: 10px">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'With the %s extension you can:', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/infinite-scroll?utm_source=st-lite&utm_campaign=upsell&utm_medium=infinite-scroll-upsell' ),
					esc_html__( 'Strong Testimonials: Infinite Scroll', 'strong-testimonials' )
				)
			);
			?>
				<ul>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'display a fixed number of testimonials on first view and have more of them load when the user starts scrolling', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'reduce your page\'s initial load time, making your site faster in the process and not driving clients away because of a slow loading website', 'strong-testimonials' ); ?></li>
				</ul>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=infinite-scroll-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	public function add_infinite_scroll_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Reduce your page’s initial load time - display a fixed number of testimonials on the first view and have more loading when you scroll down with %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/infinite-scroll?utm_source=st-lite&utm_campaign=upsell&utm_medium=infinite-scroll-general-upsell' ),
				esc_html__( 'Infinite Scroll', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Filters
	*/
	public function output_filters_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top:1.5rem;">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Use the %s extensions to:', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/filters?utm_source=st-lite&utm_campaign=upsell&utm_medium=views-filters-upsell' ),
					esc_html__( 'Strong Testimonials: Filters', 'strong-testimonials' )
				)
			);
			?>
				<ul>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'create category-like filters for your testimonials', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'group testimonials by associated product or service', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'help potential clients appreciate the great work you do by showcasing reviews from other clients', 'strong-testimonials' ); ?></li>
				</ul>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=filters-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}
	public function add_filters_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( 'Add category-like filters for testimonials, group testimonials by associated product/service, and help potential clients appreciate the great work you do by showcasing reviews from other clients with %s extension.', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/filters?utm_source=st-lite&utm_campaign=upsell&utm_medium=filters-general-upsell' ),
				esc_html__( 'Filters', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Assigments
	*/
	public function register_assigment_tab( $active_tab, $url ) {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		printf(
			'<a href="%s" class="nav-tab %s">%s%s</a>',
			esc_url( add_query_arg( 'tab', 'assignment', $url ) ),
			esc_attr( 'assignment' === $tab ? 'nav-tab-active' : '' ),
			esc_html_x( 'Assignment', 'adjective', 'strong-testimonials' ),
			'<span class="wpmtst-upsell-badge">PRO</span>'
		);
	}
	public function register_assigment_settings_page( $pages ) {
		$pages['assignment'] = array( $this, 'output_assigment_upsell' );
		return $pages;
	}
	public function output_assigment_upsell() {
		?>

		<div class="wpmtst-alert" style="margin-top:1.5rem;">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'The %s extension is perfect if you want to easily assign testimonials to certain custom post types.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/assignment?utm_source=st-lite&utm_campaign=upsell&utm_medium=settings-tab-assigment-upsell' ),
					esc_html__( 'Strong Testimonials: Assignment', 'strong-testimonials' )
				)
			);
			?>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=assigment-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>

		<?php
	}

	/*
	Strong Testimonials PRO
	*/
	public function register_st_pro_tab( $active_tab, $url ) {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		printf(
			'<a href="%s" class="nav-tab %s">%s%s</a>',
			esc_url( add_query_arg( 'tab', 'single_testiomonial_template', $url ) ),
			esc_attr( 'single_testiomonial_template' === $tab ? 'nav-tab-active' : '' ),
			esc_html_x( 'Single Testimonial Template', 'adjective', 'strong-testimonials' ),
			'<span class="wpmtst-upsell-badge">PRO</span>'
		);
	}
	public function register_st_pro_page( $pages ) {
		$pages['single_testiomonial_template'] = array( $this, 'output_st_pro_upsell' );
		return $pages;
	}
	public function output_st_pro_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top:1.5rem;">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'With %s you can:', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/strong-testimonials-pro?utm_source=st-lite&utm_campaign=upsell&utm_medium=setting-tab-st-pro-upsell' ),
					esc_html__( 'Strong Testimonials PRO', 'strong-testimonials' )
				)
			);
			?>
				<ul>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'Display a default image when no image has been provided for the testimonial;', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'Use author initials as the testimonial image;', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'Choose the HTML tag you’d like to use for your testimonial titles;', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'Choose the Single Testimonial Template settings;', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'Prefill testimonial forms from $_GET parameters;', 'strong-testimonials' ); ?></li>
				<li class="wpmtst-upsell-checkmark"><?php esc_html_e( 'Show testimonial form only for logged-in users.', 'strong-testimonials' ); ?></li>
				</ul>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=setting-tab-st-pro-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}
	public function add_pro_upsell( $upsells ) {
		$upsell = sprintf(
			// translators: %s is a link to a Strong Testimonials extension page.
			esc_html__( '%s provides you with a lot of new functionalities in one plugin, such as choosing the HTML tag you’d like to display for your testimonial titles or prefilling the forms from $_GET parameters. Moreover, if no image is provided for your testimonial, you can display the author’s initials or a default picture. Get started with Strong Testimonials Pro today!', 'strong-testimonials' ),
			sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( WPMTST_STORE_URL . '/extensions/strong-testimonials-pro?utm_source=st-lite&utm_campaign=upsell&utm_medium=st-pro-general-upsell' ),
				esc_html__( 'Strong Testimonials PRO', 'strong-testimonials' )
			)
		);

		$upsells[] = $upsell;
		return $upsells;
	}

	/*
	Properties
	*/
	public function register_properties_tab( $active_tab, $url ) {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		printf(
			'<a href="%s" class="nav-tab %s">%s%s</a>',
			esc_url( add_query_arg( 'tab', 'properties', $url ) ),
			esc_attr( 'properties' === $tab ? 'nav-tab-active' : '' ),
			esc_html_x( 'Properties', 'adjective', 'strong-testimonials' ),
			'<span class="wpmtst-upsell-badge">PRO</span>'
		);
	}

	public function register_properties_page( $pages ) {
		$pages['properties'] = array( $this, 'output_properties_upsell' );
		return $pages;
	}

	public function output_properties_upsell() {
		?>
		<div class="wpmtst-alert" style="margin-top:1.5rem;">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'Easily customize default testimonial attributes such as labels, permalink structure, icons and more with the %s extension.', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/properties?utm_source=st-lite&utm_campaign=upsell&utm_medium=settings-tab-properties-upsell' ),
					esc_html__( 'Strong Testimonials: Properties', 'strong-testimonials' )
				)
			);
			?>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=properties-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Add submenu page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function add_submenu( $pages ) {
		$packages = $this->wpchill_upsells->get_packages();
		if ( ! isset( $packages['current_package'] ) ) {
			return $pages;
		}
		// Add the lite vs pro page only if the user has not purchased the agency package.
		if ( false === strpos( $packages['current_package']['slug'], 'business' ) && false === strpos( $packages['current_package']['slug'], 'agency' ) ) {
			$pages[92] = $this->get_submenu();
		}

		return $pages;
	}

	/**
	 * Return submenu page parameters.
	 *
	 * @return array
	 */
	public function get_submenu() {
		$packages = $this->wpchill_upsells->get_packages()['current_package'];

		return array(
			'page_title' => esc_html__( 'Upgrade', 'strong-testimonials' ),
			'menu_title' => esc_html__( 'Upgrade', 'strong-testimonials' ),
			'capability' => 'strong_testimonials_options',
			'menu_slug'  => 'strong-testimonials-upsells',
			'function'   => array( $this, 'upsells_page' ),
		);
	}

	/**
	 * Print the Addons page.
	 */
	public function upsells_page() {
		wp_enqueue_style( 'wpmtst-admin-upsells-style' );

		echo '<div class="wpmst wrap lite-vs-pro-section">';

		do_action( 'st_lite_vs_premium_page' );

		echo '</div>';
	}

	/**
	 * Adds the filters and actions to add modula offers display by month
	 *
	 * @since 3.1.10
	 */
	private function set_offer() {
		// $month = gmdate( 'm' );

		// if ( '11' === $month ) {
		// 	add_filter( 'wpmtst_upsells_button_text', array( $this, 'bf_buttons' ), 15 );
		// 	add_action( 'admin_print_styles', array( $this, 'footer_bf_styles' ), 999 );
		// }
		// if ( '12' === $month ) {
		// 	add_filter( 'wpmtst_upsells_button_text', array( $this, 'xmas_buttons' ), 15 );
		// 	add_action( 'admin_print_styles', array( $this, 'footer_xmas_styles' ), 999 );
		// }
	}

	/**
	 * Replaces upsells button with Black Friday text buttons
	 *
	 * @since 3.1.10
	 */
	public function bf_buttons( $text ) {
		return __( '40% OFF for Black Friday', 'strong-testimonials' );
	}

	/**
	 * Replaces upsells button with Christmas text buttons
	 *
	 * @since 3.1.10
	 */
	public function xmas_buttons( $text ) {
		return __( '25% OFF for Christmas', 'strong-testimonials' );
	}

	/**
	 * Echoes Black Friday script to footer
	 *
	 * @since 3.1.10
	 */
	public function footer_bf_styles() {

		$css = '<style>
		#wpbody-content .wpmtst-alert {
			color: #fff;
			background-color: #000;
		}
		#wpbody-content .wpmtst-alert h3,
		#wpbody-content .wpmtst-alert h2,
		#wpbody-content .wpmtst-alert table label{
			color: #fff;
		}
		
		#wpbody-content .wpmtst-alert > a,
		#wpbody-content .wpmtst-alert li span a {
			color: #f8003e;
		}
		#wpbody-content .wpmtst-alert .button.button-primary{
			background-color: #f8003e;
			border: none;
			color: #fff;
			font-weight: 600;
		}
		#wpbody-content .wpmtst-alert .button.button-primary:hover {
			background-color: red;
			border: none;
			color: #fff;
			font-weight: 600;
		}

		</style>';
		echo $css;
	}

	/**
	 * Echoes Christmas style to footer
	 *
	 * @since 3.1.10
	 */
	public function footer_xmas_styles() {

		$css = '<style>
		#wpbody-content .wpmtst-alert::before{
			content: "";
			position: absolute;
			width: 100%;
			height: 50px;
			background-image: url(' . WPMTST_ADMIN_URL . 'img/upsells/x-mas.jpg' . ');
			background-position-x: 15px;
			left: 0;
			top: 0;
			background-size: contain;
			z-index: 0;
		}

		#wpbody-content .wpmtst-alert .button.button-primary {
			background-color: #f8003e;
			border: none;
			color: #fff;
			font-weight: 600;
		}
		#wpbody-content .wpmtst-alert .button.button-primary:hover {
			background-color: red;
			border: none;
			color: #fff;
			font-weight: 600;
		}
		#wpbody-content .wpmtst-alert{
			margin-top: 10px;
			position: relative;
			padding-top: 60px;
			background-color: #fff;
		}
		#wpbody-content .inside .wpmtst-alert{ 
			margin-top: unset;
		}
		</style>';
		echo $css;
	}

	public function output_mailchip_form_settings_upsell() {
		?>
		<hr>

		<h3><?php esc_html_e( 'Mailchimp', 'strong-testimonials' ); ?></h3>

		<div class="wpmtst-alert">
			<?php
			printf(
				// translators: %s is a link to a Strong Testimonials extension page.
				esc_html__( 'With this extension you can automatically subscribe your users to a MailChimp email list. Follow up with a targeted message or a coupon to thank them for leaving a good review. Unlock even more marketing & automation potential. ', 'strong-testimonials' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( WPMTST_STORE_URL . '/extensions/mailchimp?utm_source=st-lite&utm_campaign=upsell&utm_medium=form-settings-upsell' ),
					esc_html__( 'Strong Testimonials: Captcha', 'strong-testimonials' )
				)
			);
			?>
			<p>
				<a class="button button-primary" target="_blank"
					href="<?php echo esc_url( $this->store_upgrade_url . '&utm_medium=form-settings-captcha-upsell' ); ?>"><?php echo esc_html( apply_filters( 'wpmtst_upsells_button_text', __( 'Upgrade', 'strong-testimonials' ) ) ); ?></a>
			</p>
		</div>
		<?php
	}
}


new Strong_Testimonials_Upsell();
