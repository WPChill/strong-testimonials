<?php

/**
 * Class Strong_Testimonials_Defaults
 *
 * @since 2.28.0
 */
class Strong_Testimonials_Defaults {

	/**
	 * Strong_Testimonials_Defaults constructor.
	 */
	public function __construct() { }

	/**
	 * Settings
	 *
	 * @since 1.13.0 reorder
	 * @since 2.2.11 scrolltop
	 * @since 2.3.0  remove whitespace
	 * @since 2.6.0  embed width
	 * @since 2.6.2  slideshow z-index
	 * @since 2.10.0 pending indicator
	 * @since 2.21.0 load Font Awesome
	 * @since 2.22.5 nofollow
	 * @since 2.27.0 no_lazyload
	 *
	 * @return array
	 */
	public static function get_options() {
		$default_options = array(
			'embed_width'             => '',
			'nofollow'                => true,
            'noopener'                => true,
            'noreferrer'              => true,
            'disable_rewrite'         => false,
			'pending_indicator'       => true,
			'remove_whitespace'       => true,
			//@todo : delete commented line. For the moment let it be
			//'reorder'                 => false,
			'support_comments'        => false,
			'support_custom_fields'   => false,
			'single_testimonial_slug' => 'testimonial',
			'scrolltop'               => true,
			'scrolltop_offset'        => 80,
			'lazyload'                => false,
            'no_lazyload_plugin'      => true,
			'touch_enabled'           => true,
            'disable_upsells'         => false,
            'track_data'              => false,
		);

		return $default_options;
	}

	/**
	 * Custom field base.
	 *
	 * @since 2.28.0 Use 'action' to register a callback.
	 *
	 * @return array
	 */
	public static function get_field_base() {
		return apply_filters( 'wpmtst_field_base', array(
			'name'                    => '',
			'name_mutable'            => 1,
			'label'                   => '',
			'show_label'              => 1,
			'input_type'              => '',
			'action_input'            => '',
			'action_output'           => '',
			'text'                    => '',
			'show_text_option'        => 0,
			'required'                => 0,
			'show_required_option'    => 1,
			'default_form_value'      => '',
			'default_display_value'   => '',
			'show_default_options'    => 1,
			'error'                   => esc_html__( 'This field is required.', 'strong-testimonials' ),
			'placeholder'             => '',
			'show_placeholder_option' => 1,
			'before'                  => '',
			'after'                   => '',
			'admin_table'             => 0,
			'admin_table_option'      => 1,
			'show_admin_table_option' => 1,
			'shortcode_on_form'       => '',
			'shortcode_on_display'    => '',
			'show_shortcode_options'  => 0,
            'show_length_option'      => 0,
            'max_length'              => ''
		) );
	}

	/**
	 * Custom fields.
	 *
	 * @return array
	 */
	public static function get_fields() {
		$field_base  = self::get_field_base();
		$field_types = array();

		/*
		 * Assemble post field types
		 */
		$field_types['post'] = array(
			'post_title'     => array(
				'input_type'              => 'text',
				'option_label'            => esc_html__( 'Testimonial Title', 'strong-testimonials' ),
				'map'                     => 'post_title',
				'show_default_options'    => 0,
				'admin_table'             => 1,
				'admin_table_option'      => 0,
				'show_admin_table_option' => 0,
				'name_mutable'            => 0,
                                'show_length_option'      => 1
			),
			'post_content'   => array(
				'input_type'              => 'textarea',
				'option_label'            => esc_html__( 'Testimonial Content', 'strong-testimonials' ),
				'map'                     => 'post_content',
				'required'                => 1,
				'show_default_options'    => 0,
				'core'                    => 0,
				'admin_table'             => 0,
				'show_admin_table_option' => 0,
				'name_mutable'            => 0,
                                'show_length_option'      => 1
			),
			'featured_image' => array(
				'input_type'              => 'file',
				'option_label'            => esc_html__( 'Featured Image', 'strong-testimonials' ),
				'map'                     => 'featured_image',
				'show_default_options'    => 0,
				'show_placeholder_option' => 0,
				'admin_table'             => 0,
				'name_mutable'            => 0,
			)
		);
		foreach ( $field_types['post'] as $key => $array ) {
			$field_types['post'][ $key ] = array_merge( $field_base, $array );
		}

		/*
		 * Assemble custom field types
		 */
		$field_types['custom'] = array(
			'text'     => array(
				'input_type'   => 'text',
				'option_label' => esc_html__( 'text', 'strong-testimonials' ),
			),
			'email'    => array(
				'input_type'           => 'email',
				'option_label'         => esc_html__( 'email', 'strong-testimonials' ),
				'show_default_options' => 0,
			),
			'url'      => array(
				'input_type'           => 'url',
				'option_label'         => esc_html__( 'URL', 'strong-testimonials' ),
				'show_default_options' => 0,
			),
			'checkbox' => array(
				'input_type'              => 'checkbox',
				'option_label'            => esc_html__( 'checkbox', 'strong-testimonials' ),
				'show_text_option'        => 1,
				'show_placeholder_option' => 0,
			),
		);
		foreach ( $field_types['custom'] as $key => $array ) {
			$field_types['custom'][ $key ] = array_merge( $field_base, $array );
		}

		/*
		 * Assemble special field types (FKA Optional)
		 *
		 * @since 1.18
		 * @since 2.2.2 Fix bug caused by localizing 'categories'
		 */
		$field_types['optional'] = array(
			'category-selector'  => array(
				'input_type'              => 'category-selector',
				'option_label'            => esc_html__( 'category selector', 'strong-testimonials' ),
				'show_default_options'    => 0,
				'show_placeholder_option' => 0,
				'show_admin_table_option' => 0,
				'name_mutable'            => 0,
			),
			'category-checklist' => array(
				'input_type'              => 'category-checklist',
				'option_label'            => esc_html__( 'category checklist', 'strong-testimonials' ),
				'show_default_options'    => 0,
				'show_placeholder_option' => 0,
				'show_admin_table_option' => 0,
				'name_mutable'            => 0,
			),
			'shortcode'          => array(
				'input_type'              => 'shortcode',
				'option_label'            => esc_html__( 'shortcode', 'strong-testimonials' ),
				'show_label'              => 0,
				'required'                => 0,
				'show_required_option'    => 0,
				'show_default_options'    => 0,
				'show_placeholder_option' => 0,
				'show_admin_table_option' => 0,
				'show_shortcode_options'  => 1,
			),
			'rating'             => array(
				'input_type'              => 'rating',
				'option_label'            => esc_html__( 'star rating', 'strong-testimonials' ),
				'show_placeholder_option' => 0,
				'admin_table'             => 1,
				'admin_table_option'      => 1,
				'show_admin_table_option' => 1,
			),
		);

		/*
		 * Merge each one onto base field
		 */
		foreach ( $field_types['optional'] as $key => $array ) {
			$field_types['optional'][ $key ] = array_merge( $field_base, $array );
		}

		/*
		 * Assemble all fields
		 */
		$default_fields = array(
			'field_base'  => $field_base,
			'field_types' => $field_types,
		);

		return apply_filters( 'wpmtst_default_fields', $default_fields );
	}

	/**
	 * Default forms.
	 *
	 * @return array
	 */
	public static function get_base_forms() {
		$default_fields = self::get_fields();

		// Assemble field groups.
		$forms = array(
			'default' => array(
				'name'     => 'default',
				'label'    => esc_html__( 'Default Form', 'strong-testimonials' ),
				'readonly' => 1,
				'fields'   => array(
					// ------
					// CUSTOM
					// ------
					0 => array(
						'record_type' => 'custom',
						'name'        => 'client_name',
						'label'       => esc_html__( 'Full Name', 'strong-testimonials' ),
						'input_type'  => 'text',
						'required'    => 1,
						'after'       => esc_html__( 'What is your full name?', 'strong-testimonials' ),
						'admin_table' => 1,
					),
					1 => array(
						'record_type' => 'custom',
						'name'        => 'email',
						'label'       => esc_html__( 'Email', 'strong-testimonials' ),
						'input_type'  => 'email',
						'required'    => 1,
						'after'       => esc_html__( 'What is your email address?', 'strong-testimonials' ),
					),
					3 => array(
						'record_type' => 'custom',
						'name'        => 'company_name',
						'label'       => esc_html__( 'Company Name', 'strong-testimonials' ),
						'input_type'  => 'text',
						'after'       => esc_html__( 'What is your company name?', 'strong-testimonials' ),
					),
					4 => array(
						'record_type' => 'custom',
						'name'        => 'company_website',
						'label'       => esc_html__( 'Company Website', 'strong-testimonials' ),
						'input_type'  => 'url',
						'after'       => esc_html__( 'Does your company have a website?', 'strong-testimonials' ),
					),
					// ----
					// POST
					// ----
					5 => array(
						'record_type' => 'post',
						'name'        => 'post_title',
						'label'       => esc_html__( 'Heading', 'strong-testimonials' ),
						'input_type'  => 'text',
						'required'    => 0,
						'after'       => esc_html__( 'A headline for your testimonial.', 'strong-testimonials' ),
                                                'max_length'  => ''
					),
					6 => array(
						'record_type' => 'post',
						'name'        => 'post_content',
						'label'       => esc_html__( 'Testimonial', 'strong-testimonials' ),
						'input_type'  => 'textarea',
						'required'    => 1,
						'after'       => esc_html__( 'What do you think about us?', 'strong-testimonials' ),
                                                'max_length'  => ''
					),
					7 => array(
						'record_type' => 'post',
						'name'        => 'featured_image',
						'label'       => esc_html__( 'Photo', 'strong-testimonials' ),
						'input_type'  => 'file',
						'after'       => esc_html__( 'Would you like to include a photo?', 'strong-testimonials' ),
						'admin_table' => 1,
					),
                                    	8 => array(
						'record_type' => 'optional',
						'name'        => 'star_rating',
						'label'       => esc_html__( 'Star rating', 'strong-testimonials' ),
						'input_type'  => 'rating',
                                                'required'    => 0,
						'after'       => esc_html__( 'Would you like to include star rating?', 'strong-testimonials' )
					),
				),
			),
		);

		$forms['minimal'] = array(
			'name'     => 'minimal',
			'label'    => esc_html__( 'Minimal Form', 'strong-testimonials' ),
			'readonly' => 1,
			'fields'   => array(
				// ------
				// CUSTOM
				// ------
				0 => array(
					'record_type' => 'custom',
					'name'        => 'client_name',
					'label'       => esc_html__( 'Name', 'strong-testimonials' ),
					'input_type'  => 'text',
					'required'    => 1,
					'after'       => '',
					'admin_table' => 1,
				),
				1 => array(
					'record_type' => 'custom',
					'name'        => 'email',
					'label'       => esc_html__( 'Email', 'strong-testimonials' ),
					'input_type'  => 'email',
					'required'    => 1,
					'after'       => '',
				),
				// ----
				// POST
				// ----
				2 => array(
					'record_type' => 'post',
					'name'        => 'post_content',
					'label'       => esc_html__( 'Testimonial', 'strong-testimonials' ),
					'input_type'  => 'textarea',
					'required'    => 1,
					'after'       => '',
				),
			),
		);

		foreach ( $forms as $form_name => $form ) {
			foreach ( $form['fields'] as $key => $array ) {
				if ( 'post' == $array['record_type'] ) {
					$forms[ $form_name ]['fields'][ $key ] = array_merge( $default_fields['field_types']['post'][ $array['name'] ], $array );
				} elseif ( 'custom' == $array['record_type']) {
					$forms[ $form_name ]['fields'][ $key ] = array_merge( $default_fields['field_types']['custom'][ $array['input_type'] ], $array );
				} else {
                                        $forms[ $form_name ]['fields'][ $key ] = array_merge( $default_fields['field_types']['optional'][ $array['input_type'] ], $array );
                                }
			}
		}

		return $forms;
	}

	/**
	 * Custom forms.
	 *
	 * @return array
	 */
	public static function get_custom_forms() {

		$base_forms = self::get_base_forms();

		// Copy default fields to custom fields.
		$forms[1] = array(
			'name'     => 'custom',
			'label'    => esc_html__( 'Custom Form', 'strong-testimonials' ),
			'readonly' => 0,
			'fields'   => $base_forms['default']['fields'],
		);

		return apply_filters( 'wpmtst_update_custom_form', $forms );
	}

	/**
	 * Form options.
	 *
	 * @return array
	 */
	public static function get_form_options() {
		/**
		 * Messages
		 *
		 * @since 1.13
		 */
		$default_messages = array(
			'required-field'     => array(
				'order'       => 1,
				/* translators: Settings > Form > Messages tab */
				'description' => esc_html_x( 'Required', 'setting description', 'strong-testimonials' ),
				'text'        => esc_html_x( 'Required', 'Default message for required notice at top of form.', 'strong-testimonials' ),
				'enabled'     => 1,
			),
			'form-submit-button' => array(
				'order'       => 2,
				/* translators: Settings > Form > Messages tab */
				'description' => esc_html_x( 'Submit Button', 'description', 'strong-testimonials' ),
				/* translators: Default label for the Submit button on testimonial form. */
				'text'        => esc_html_x( 'Add Testimonial', 'the Submit button', 'strong-testimonials' ),
			),
			'submission-error'   => array(
				'order'       => 3,
				/* translators: Settings > Form > Messages tab */
				'description' => esc_html_x( 'Submission Error', 'description', 'strong-testimonials' ),
				/* translators: Default message for submission form error. */
				'text'        => esc_html_x( 'There was a problem processing your testimonial.', 'error message', 'strong-testimonials' ),
			),
			'submission-success' => array(
				'order'       => 4,
				/* translators: Settings > Form > Messages tab */
				'description' => esc_html_x( 'Submission Success', 'description', 'strong-testimonials' ),
				/* translators: Default message for submission form success message. */
				'text'        => esc_html_x( 'Thank you! Your testimonial is waiting to be approved.', 'success message', 'strong-testimonials' ),
			),
		);

		uasort( $default_messages, 'wpmtst_uasort' );

		$default_form_options = array(
			'post_status'              => 'pending',
			'admin_notify'             => false,
                        'customer-notify'          => false,
                        'approved-notify'          => false,
                        'sender_name_for_customer' => false,
                        'sender_customer_email'    => false,
                        'sender_site_customer_email' => true,
                        'sender_name_for_customer_approval' => false,
                        'sender_site_customer_approval_email'    => true,
                        'sender_approval_email' => false,
			'mail_queue'               => false,
			'sender_name'              => get_bloginfo( 'name' ),
			'sender_site_email'        => true,
			'sender_email'             => '',
			'recipients'               => array(
				array(
					'admin_name'       => '',
					'admin_email'      => '',
					'admin_site_email' => true,
					'primary'          => true,  // cannot be deleted
				),
			),
			'default_recipient'        => array(
				'admin_name'  => '',
				'admin_email' => '',
			),
			/* translators: Default subject line for new testimonial notification email. */
			'email_subject'                      => esc_html__( 'New testimonial for %BLOGNAME%', 'strong-testimonials' ),
                        'customer_approval_email_subject'    => esc_html__( 'Testimonial for %BLOGNAME%', 'strong-testimonials' ),
                        'customer_email_subject'             => esc_html__( 'Testimonial for %BLOGNAME%', 'strong-testimonials' ),
			/* translators: Default message for new testimonial notification email. */
			'email_message'                      => esc_html__( 'New testimonial submission for %BLOGNAME%. This is awaiting action from the website administrator.', 'strong-testimonials' ),
                        'customer_approval_email_message'    => esc_html__( 'Your testimonial was published for %BLOGNAME%. Thank you!', 'strong-testimonials' ),
                        'customer_email_message'             => esc_html__( 'Your testimonial was received  for %BLOGNAME% and awaiting approval from the website administrator. Thank you!', 'strong-testimonials' ),
			'messages'                 => $default_messages,
			'scrolltop_success'        => true,
			'scrolltop_success_offset' => 80,
			'scrolltop_error'          => true,
			'scrolltop_error_offset'   => 80,
			'success_action'           => 'message', // message | id | url
			'success_redirect_id'      => '',
			'success_redirect_url'     => '',
                        'members_only'             => false,
                        'members_only_message'     => esc_html__( 'You need to be logged in to access this form.', 'strong-testimonials' ),
                        'mailchimp'                => false,
                        'mailchimp_message'        => esc_html__( 'Subscribe to our newsletter.', 'strong-testimonials' ),
                        'mailchimp_list'           => ''
		);

		return apply_filters( 'wpmtst_default_form_options', $default_form_options );
	}

	/**
	 * Default view options.
	 *
	 * @since 1.21.0
	 *
	 * @return array
	 */
	public static function get_view_options() {
		$default_view_options = array(

			'mode' => array(
				'display'         => array(
					'name'        => 'display',
					'label'       => esc_html__( 'Display', 'strong-testimonials' ),
					'description' => esc_html__( 'Display your testimonials in a list or a grid.', 'strong-testimonials' ),
				),
				'slideshow'       => array(
					'name'        => 'slideshow',
					'label'       => esc_html__( 'Slideshow', 'strong-testimonials' ),
					'description' => esc_html__( 'Create a slideshow of your testimonials.', 'strong-testimonials' ),
				),
				'form'            => array(
					'name'        => 'form',
					'label'       => esc_html__( 'Form', 'strong-testimonials' ),
					'description' => esc_html__( 'Display a testimonial submission form.', 'strong-testimonials' ),
				),
				'single_template' => array(
					'name'        => 'single_template',
					'label'       => esc_html__( 'Single Template', 'strong-testimonials' ),
					'description' => esc_html__( 'When viewing the testimonial using a theme\'s single post template.', 'strong-testimonials' ),
				),
			),

			'order' => array(
				'random'     => esc_html_x( 'random', 'display order', 'strong-testimonials' ),
				'menu_order' => esc_html_x( 'menu order', 'display order', 'strong-testimonials' ),
				'newest'     => esc_html_x( 'newest first', 'display order', 'strong-testimonials' ),
				'oldest'     => esc_html_x( 'oldest first', 'display order', 'strong-testimonials' ),
			),

			'slideshow_effect' => array(
				'none'       => esc_html_x( 'no transition effect', 'slideshow transition option', 'strong-testimonials' ),
				'fade'       => esc_html_x( 'fade', 'slideshow transition option', 'strong-testimonials' ),
				'horizontal' => esc_html_x( 'scroll horizontally', 'slideshow transition option', 'strong-testimonials' ),
				'vertical'   => esc_html_x( 'scroll vertically', 'slideshow transition option', 'strong-testimonials' ),
			),

			'slideshow_height' => array(
				'dynamic' => esc_html_x( 'Adjust height for each slide', 'slideshow option', 'strong-testimonials' ),
				'static'  => esc_html_x( 'Set height to match the tallest slide', 'slideshow option', 'strong-testimonials' ),
			),

			'slideshow_nav_method' => array(
				'controls' => array(
					'none'   => array(
						'label' => esc_html_x( 'none', 'slideshow controls option', 'strong-testimonials' ),
						'args'  => array(  // base args; style will add more args
						                   'controls'     => 0,
						                   'pager'        => 0,
						                   'autoControls' => 0,
						),
					),
					'full'   => array(
						'label'              => esc_html_x( 'Bottom: previous / play-pause / next', 'slideshow controls option', 'strong-testimonials' ),
						'class'              => 'controls-type-full',
						'add_position_class' => 1,
						'args'               => array(
							'pager'               => 0,
							'autoControls'        => 1,
							'autoControlsCombine' => 1,
							'fullSetButtons'      => 1,
							'fullSetText'         => 1,
						),
					),
					'simple' => array(
						'label'              => esc_html_x( 'Bottom: previous / next', 'slideshow controls option', 'strong-testimonials' ),
						'class'              => 'controls-type-simple',
						'add_position_class' => 1,
						'args'               => array(
							'controls'     => 1,
							'autoControls' => 0,
						),
					),
					'sides'  => array(
						'label'              => esc_html_x( 'Sides: previous / next', 'slideshow controls option', 'strong-testimonials' ),
						'class'              => 'controls-type-sides',
						'add_position_class' => 0,
						'args'               => array(
							'controls'     => 1,
							'autoControls' => 0,
							'prevText'     => '',
							'nextText'     => '',
						),
					),
				),
				'pager'    => array(
					'none' => array(
						'label' => esc_html_x( 'none', 'slideshow navigation option', 'strong-testimonials' ),
						'args'  => array(),
					),
					'full' => array(
						'label' => esc_html_x( 'full', 'slideshow navigation option', 'strong-testimonials' ),
						//'class' => 'controls-pager-full',
						'class' => 'pager-type-full',
						'args'  => array(
							'pager' => 1,
						),
					),
				),
			),

			'slideshow_nav_style'    => array(
				'controls' => array(
					'buttons'  => array(
						'label' => esc_html_x( 'buttons 1', 'slideshow navigation option', 'strong-testimonials' ),
						'class' => 'controls-style-buttons',
						'args'  => array(
							'startText' => '',
							'stopText'  => '',
							'prevText'  => '',
							'nextText'  => '',
						),
					),
					'buttons2' => array(
						'label' => esc_html_x( 'buttons 2', 'slideshow navigation option', 'strong-testimonials' ),
						'class' => 'controls-style-buttons2',
						'args'  => array(
							'startText' => '',
							'stopText'  => '',
							'prevText'  => '',
							'nextText'  => '',
						),
					),
					'buttons3' => array(
						'label' => esc_html_x( 'buttons 3', 'slideshow navigation option', 'strong-testimonials' ),
						'class' => 'controls-style-buttons3',
						'args'  => array(
							'startText' => '',
							'stopText'  => '',
							'prevText'  => '',
							'nextText'  => '',
						),
					),
					'text'     => array(
						'label' => esc_html_x( 'text', 'slideshow navigation option', 'strong-testimonials' ),
						'class' => 'controls-style-text',
						'args'  => array(
							'startText' => esc_html_x( 'Play', 'slideshow control', 'strong-testimonials' ),
							'stopText'  => esc_html_x( 'Pause', 'slideshow control', 'strong-testimonials' ),
							'prevText'  => esc_html_x( 'Previous', 'slideshow_control', 'strong-testimonials' ),
							'nextText'  => esc_html_x( 'Next', 'slideshow_control', 'strong-testimonials' ),
						),
					),
				),
				'pager'    => array(
					'buttons' => array(
						'label' => esc_html_x( 'buttons', 'slideshow navigation option', 'strong-testimonials' ),
						'class' => 'pager-style-buttons',
						'args'  => array(
							'buildPager'     => 'icons',
							'simpleSetPager' => 1,
						),
					),
					'text'    => array(
						'label' => esc_html_x( 'text', 'slideshow navigation option', 'strong-testimonials' ),
						'class' => 'pager-style-text',
						'args'  => array(
							'buildPager'    => null,
							'simpleSetText' => 1,
						),
					),
				),
			),

			// Position is shared by Controls and Pagination.
			'slideshow_nav_position' => array(
				'inside'  => esc_html_x( 'inside', 'slideshow navigation option', 'strong-testimonials' ),
				'outside' => esc_html_x( 'outside', 'slideshow navigation option', 'strong-testimonials' ),
			),
		);

		return $default_view_options;
	}

	/**
	 * Default view configuration.
	 *
	 * @since 1.21.0
	 *
	 * @return array
	 */
	public static function get_default_view() {
		$default_view = array(
			'background'          => array(
				'color'              => '',
				'type'               => '',
				'preset'             => '',
				'gradient1'          => '',
				'gradient2'          => '',
			),
			'category'            => 'all',
			'class'               => '',
			'client_section'      => array(
				0 => array(
					'field'  => 'client_name',
					'type'   => 'text',
					'before' => '',
					'class'  => 'testimonial-name',
				),
				1 => array(
					'field'   => 'company_name',
					'type'    => 'link',
					'before'  => '',
					'url'     => 'company_website',
					'class'   => 'testimonial-company',
					'new_tab' => true,
				),
			),
			'column_count'        => 2,
			'container_class'     => '',
			'container_data'      => '',
			'content'             => 'entire',
			'count'               => -1,
			'divi_builder'        => 0,
			'excerpt_length'      => 55,
			'font-color'         => array(
				'color' => '',
				'type'  => '',
			),
			'form_ajax'           => 0,
			'form_id'             => 1,
			'gravatar'            => 'no',
                        'initials_font_size'  => '42',
                        'initials_font_color' => '#000000',
                        'initials_bg_color'   => '#ffffff',
			'id'                  => '',
			'layout'              => '',
			'lightbox'            => '',
			'lightbox_class'      => '',
			'mode'                => 'display',
			'more_full_post'      => false,
			'more_post'           => true,
			'more_post_ellipsis'  => true,
			'more_post_text'      => esc_html_x( 'Read more', 'link', 'strong-testimonials' ),
			'more_post_in_place'  => false,
			'less_post'           => false,
			'less_post_text'      => esc_html__( 'Show less', 'strong-testimonials' ),
			'more_page'           => false,
			'more_page_hook'      => 'wpmtst_view_footer',
			'more_page_id'        => 0,
			'more_page_text'      => esc_html_x( 'Read more testimonials', 'link', 'strong-testimonials' ),
			'note'                => '',
			'order'               => 'oldest',
			'page'                => '',
			'pagination'          => false,
			'pagination_settings' => array(
				'type'               => 'simple',
				'nav'                => 'after',
				'per_page'           => 5,
				'show_all'           => 0,
				'end_size'           => 1,
				'mid_size'           => 2,
				'prev_next'          => 1,
				'prev_text'          => esc_html__( '&laquo; Previous', 'strong-testimonials' ),
				'next_text'          => esc_html__( 'Next &raquo;', 'strong-testimonials' ),
				'before_page_number' => '',
				'after_page_number'  => '',
			),
			'slideshow_settings'  => array(
				'type'        => 'show_single', // or show_multiple
				'show_single'   => array(
					'max_slides'  => 1,
					'move_slides' => 1,
					'margin'      => 1,
				),
				'breakpoints' => array(
					'desktop' => array(
						'description' => 'Desktop',
						'width'       => 1200,
						'max_slides'  => 2,
						'move_slides' => 1,
						'margin'      => 20,
					),
					'large'   => array(
						'description' => 'Large',
						'width'       => 1024,
						'max_slides'  => 2,
						'move_slides' => 1,
						'margin'      => 20,
					),
					'medium'  => array(
						'description' => 'Medium',
						'width'       => 640,
						'max_slides'  => 1,
						'move_slides' => 1,
						'margin'      => 10,
					),
					'small'   => array(
						'description' => 'Small',
						'width'       => 480,
						'max_slides'  => 1,
						'move_slides' => 1,
						'margin'      => 1,
					),
				),
				'effect'             => 'fade',
				'speed'              => 1,
				'pause'              => 8,
				'auto_start'         => true,
				'continuous_sliding' => false,
				'auto_hover'         => true,
				'adapt_height'       => true,
				'adapt_height_speed' => .5,
				'stretch'            => 0,
				'stop_auto_on_click' => true,
				'controls_type'      => 'none',
				'controls_style'     => 'buttons',
				'pager_type'         => 'none',
				'pager_style'        => 'buttons',
				'nav_position'       => 'inside',
			),
			'template'            => 'default',
			'template_settings'   => array(),
			'thumbnail'           => true,
			'thumbnail_size'      => 'thumbnail',
			'thumbnail_height'    => null,
			'thumbnail_width'     => null,
			'title'               => true,
			'title_link'          => 'none',
			'use_default_length'  => true,
			'use_default_more'    => false,
			'view'                => '',
		);
		ksort( $default_view );

		return $default_view;
	}

	/**
	 * Compatibility options.
	 *
	 * @since 2.28.0
	 * @since 2.31.0 controller
	 * @since 2.31.0 lazyload
	 * @return array
	 */
	public static function get_compat_options() {
		$options = array(
			'page_loading' => '', // (blank) | general | advanced
			'prerender'    => 'current', // current | all | none
			'ajax'         => array(
				'method'          => '', // (blank) | universal | observer | event | script
				'universal_timer' => 0.5,
				'observer_timer'  => 0.5,
				'container_id'    => 'page',    // = what we listen to  (try page > content > primary)
				'addednode_id'    => 'content', // = what we listen for
				'event'           => '',
				'script'          => '',
			),
			'controller' => array(
				'initialize_on' => 'documentReady', // or windowLoad
			),
			'lazyload' => array(
				'enabled' => '',
				'classes' => array( // may be multiple pairs
					array(
						'start'  => '',
						'finish' => '',
					)
				),
			),
            'random_js' => false
		);

		return $options;
	}

}
