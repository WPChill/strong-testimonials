<?php
/**
 * Default options.
 *
 * @since 1.8.0
 * @package Strong_Testimonials
 */

/**
 * Settings
 *
 * @since 1.13.0 reorder
 * @since 2.2.11 scrolltop
 * @since 2.3.0  remove whitespace
 * @since 2.4.0  email log level for troubleshooting
 * @since 2.6.0  embed width
 * @since 2.6.2  slideshow z-index
 * @since 2.10.0 pending indicator
 * @since 2.21.0 load Font Awesome
 * @since 2.22.5 nofollow
 *
 * @return array
 */
function wpmtst_get_default_options() {
	$default_options = array(
		'debug_log'             => 0,
		'email_log_level'       => 1,
		'embed_width'           => '',
		'load_font_awesome'     => 1,
		'nofollow'              => 0,
		'pending_indicator'     => true,
		'remove_whitespace'     => true,
		'reorder'               => false,
		'support_comments'      => false,
		'support_custom_fields' => false,
		'scrolltop'             => true,
		'scrolltop_offset'      => 40,
	);

	return $default_options;
}

/**
 * Fields
 *
 * @return array
 */
function wpmtst_get_default_fields() {
	// common field properties
	$field_base = array(
		'name'                    => '',
		'name_mutable'            => 1,
		'label'                   => '',
		'show_label'              => 1,
		'input_type'              => '',

		'text'                    => '',
		'show_text_option'        => 0,

		'required'                => 0,
		'show_required_option'    => 1,

		'default_form_value'      => '',
		'default_display_value'   => '',
		'show_default_options'    => 1,

		'error'                   => __( 'This field is required.', 'strong-testimonials' ),

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
	);

	// Assemble field type groups.
	$field_types = array();

	// Post
	$field_types['post'] = array(
		'post_title'     => array(
			'input_type'              => 'text',
			'option_label'            => __( 'Testimonial Title', 'strong-testimonials' ),
			'map'                     => 'post_title',
			'show_default_options'    => 0,
			'admin_table'             => 1,
			'admin_table_option'      => 0,
			'show_admin_table_option' => 0,
			'name_mutable'            => 0,
		),
		'post_content'   => array(
			'input_type'              => 'textarea',
			'option_label'            => __( 'Testimonial Content', 'strong-testimonials' ),
			'map'                     => 'post_content',
			'required'                => 1,
			'show_default_options'    => 0,
			'core'                    => 0,
			'admin_table'             => 0,
			'show_admin_table_option' => 0,
			'name_mutable'            => 0,
		),
		'featured_image' => array(
			'input_type'              => 'file',
			'option_label'            => __( 'Featured Image', 'strong-testimonials' ),
			'map'                     => 'featured_image',
			'show_default_options'    => 0,
			'show_placeholder_option' => 0,
			'admin_table'             => 0,
			'name_mutable'            => 0,
		),
	);
	foreach ( $field_types['post'] as $key => $array ) {
		$field_types['post'][ $key ] = array_merge( $field_base, $array );
	}

	// Custom
	$field_types['custom'] = array(
		'text'  => array(
			'input_type'   => 'text',
			'option_label' => __( 'text', 'strong-testimonials' ),
		),
		'email' => array(
			'input_type'           => 'email',
			'option_label'         => __( 'email', 'strong-testimonials' ),
			'show_default_options' => 0,
		),
		'url'   => array(
			'input_type'           => 'url',
			'option_label'         => __( 'URL', 'strong-testimonials' ),
			'show_default_options' => 0,
		),
		'checkbox' => array(
			'input_type'           => 'checkbox',
			'option_label'         => __( 'checkbox', 'strong-testimonials' ),
			'show_text_option'     => 1,
			'show_default_options' => 0,
			'show_placeholder_option' => 0,
		),
	);
	foreach ( $field_types['custom'] as $key => $array ) {
		$field_types['custom'][ $key ] = array_merge( $field_base, $array );
	}

	/**
	 * Special field types (FKA Optional)
	 *
	 * @since 1.18
	 * @since 2.2.2 Fix bug caused by localizing 'categories'
	 */
	$field_types['optional'] = array(
		'category-selector' => array(
			'input_type'              => 'category-selector',
			'option_label'            => __( 'category selector', 'strong-testimonials' ),
			'show_default_options'    => 0,
			'show_placeholder_option' => 0,
			'show_admin_table_option' => 0,
			'name_mutable'            => 0,
		),
		'category-checklist' => array(
			'input_type'              => 'category-checklist',
			'option_label'            => __( 'category checklist', 'strong-testimonials' ),
			'show_default_options'    => 0,
			'show_placeholder_option' => 0,
			'show_admin_table_option' => 0,
			'name_mutable'            => 0,
		),
		'shortcode' => array(
			'input_type'              => 'shortcode',
			'option_label'            => __( 'shortcode', 'strong-testimonials' ),
			'show_label'              => 0,
			'required'                => 0,
			'show_required_option'    => 0,
			'show_default_options'    => 0,
			'show_placeholder_option' => 0,
			'show_admin_table_option' => 0,
			'show_shortcode_options'  => 1,
		),
		'rating' => array(
			'input_type'              => 'rating',
			'option_label'            => __( 'star rating', 'strong-testimonials' ),
			//'show_default_options'    => 0,
			'show_placeholder_option' => 0,
			'admin_table'             => 1,
			'admin_table_option'      => 1,
			'show_admin_table_option' => 1,
		)
	);

	// merge each one onto base field
	foreach ( $field_types['optional'] as $key => $array ) {
		$field_types['optional'][ $key ] = array_merge( $field_base, $array );
	}

	// Assemble default field settings.
	$default_fields['field_base']  = $field_base;
	$default_fields['field_types'] = $field_types;

	return $default_fields;
}

/**
 * Default forms.
 *
 * @return array
 */
function wpmtst_get_default_base_forms() {
	$default_fields = wpmtst_get_default_fields();

	// Assemble field groups.
	$forms = array(
		'default' => array(
			'name'   => 'default',
			'label'  => __( 'Default Form', 'strong-testimonials' ),
			'readonly' => 1,
			'fields' => array(
				// ------
				// CUSTOM
				// ------
				0 => array(
					'record_type' => 'custom',
					'name'        => 'client_name',
					'label'       => __( 'Full Name', 'strong-testimonials' ),
					'input_type'  => 'text',
					'required'    => 1,
					'after'       => __( 'What is your full name?', 'strong-testimonials' ),
					'admin_table' => 1,
				),
				1 => array(
					'record_type' => 'custom',
					'name'        => 'email',
					'label'       => __( 'Email', 'strong-testimonials' ),
					'input_type'  => 'email',
					'required'    => 1,
					'after'       => __( 'What is your email address?', 'strong-testimonials' ),
				),
				3 => array(
					'record_type' => 'custom',
					'name'        => 'company_name',
					'label'       => __( 'Company Name', 'strong-testimonials' ),
					'input_type'  => 'text',
					'after'       => __( 'What is your company name?', 'strong-testimonials' ),
				),
				4 => array(
					'record_type' => 'custom',
					'name'        => 'company_website',
					'label'       => __( 'Company Website', 'strong-testimonials' ),
					'input_type'  => 'url',
					'after'       => __( 'Does your company have a website?', 'strong-testimonials' ),
				),
				// ----
				// POST
				// ----
				5 => array(
					'record_type' => 'post',
					'name'        => 'post_title',
					'label'       => __( 'Heading', 'strong-testimonials' ),
					'input_type'  => 'text',
					'required'    => 0,
					'after'       => __( 'A headline for your testimonial.', 'strong-testimonials' ),
				),
				6 => array(
					'record_type' => 'post',
					'name'        => 'post_content',
					'label'       => __( 'Testimonial', 'strong-testimonials' ),
					'input_type'  => 'textarea',
					'required'    => 1,
					'after'       => __( 'What do you think about us?', 'strong-testimonials' ),
				),
				7 => array(
					'record_type' => 'post',
					'name'        => 'featured_image',
					'label'       => __( 'Photo', 'strong-testimonials' ),
					'input_type'  => 'file',
					'after'       => __( 'Would you like to include a photo?', 'strong-testimonials' ),
					'admin_table' => 1,
				),
			),
		)
	);

	$forms['minimal'] = array(
		'name'   => 'minimal',
		'label'  => __( 'Minimal Form', 'strong-testimonials' ),
		'readonly' => 1,
		'fields' => array(
			// ------
			// CUSTOM
			// ------
			0 => array(
				'record_type' => 'custom',
				'name'        => 'client_name',
				'label'       => __( 'Name', 'strong-testimonials-multiple-forms' ),
				'input_type'  => 'text',
				'required'    => 1,
				'after'       => '',
				'admin_table' => 1,
			),
			1 => array(
				'record_type' => 'custom',
				'name'        => 'email',
				'label'       => __( 'Email', 'strong-testimonials-multiple-forms' ),
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
				'label'       => __( 'Testimonial', 'strong-testimonials-multiple-forms' ),
				'input_type'  => 'textarea',
				'required'    => 1,
				'after'       => '',
			),
		),
	);

	foreach ( $forms as $form_name => $form ) {
		foreach ( $form['fields'] as $key => $array ) {
			if ( 'post' == $array['record_type'] ) {
				$forms[$form_name]['fields'][ $key ] = array_merge( $default_fields['field_types']['post'][ $array['name'] ], $array );
			}
			else {
				$forms[$form_name]['fields'][ $key ] = array_merge( $default_fields['field_types']['custom'][ $array['input_type'] ], $array );
			}
		}
	}

	return $forms;
}

function wpmtst_get_default_custom_forms() {

	$base_forms = wpmtst_get_default_base_forms();

	// Copy default fields to custom fields.
	$forms[1] = array(
		'name'   => 'custom',
		'label'  => __( 'Custom Form', 'strong-testimonials' ),
		'readonly' => 0,
		'fields' => $base_forms['default']['fields'],
	);

	return $forms;
}

/**
 * Form options.
 *
 * @return array
 */
function wpmtst_get_default_form_options() {
	/**
	 * Messages
	 *
	 * @since 1.13
	 */
	$default_messages = array(
		'required-field'     => array(
			'order'       => 1,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Required Field', 'setting description', 'strong-testimonials' ),
			'text'        => _x( 'Required Field', 'Default message for required notice at top of form.', 'strong-testimonials' ),
			'enabled'     => 1,
		),
		'captcha'            => array(
			'order'       => 2,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Captcha Label', 'description', 'strong-testimonials' ),
			'text'        => _x( 'Captcha', 'Default label for Captcha field on submission form.', 'strong-testimonials' ),
		),
		'form-submit-button' => array(
			'order'       => 3,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Submit Button', 'description', 'strong-testimonials' ),
			/* translators: Default label for the Submit button on testimonial form. */
			'text'        => _x( 'Add Testimonial', 'the Submit button', 'strong-testimonials' ),
		),
		'submission-error'   => array(
			'order'       => 4,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Submission Error', 'description', 'strong-testimonials' ),
			/* translators: Default message for submission form error. */
			'text'        => _x( 'There was a problem processing your testimonial.', 'error message', 'strong-testimonials' ),
		),
		'submission-success' => array(
			'order'       => 5,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Submission Success', 'description', 'strong-testimonials' ),
			/* translators: Default message for submission form success message. */
			'text'        => _x( 'Thank you! Your testimonial is awaiting moderation.', 'success message', 'strong-testimonials' ),
		),
	);

	uasort( $default_messages, 'wpmtst_uasort' );

	$default_form_options = array(
		'post_status'              => 'pending',
		'admin_notify'             => 0,
		'mail_queue'               => 0,
		'sender_name'              => get_bloginfo( 'name' ),
		'sender_site_email'        => 1,
		'sender_email'             => '',
		'recipients'               => array(
			array(
				'admin_name'       => '',
				'admin_email'      => '',
				'admin_site_email' => 1,
				'primary'          => 1,  // cannot be deleted
			),
		),
		'default_recipient'        => array(
			'admin_name'  => '',
			'admin_email' => '',
		),
		/* translators: Default subject line for new testimonial notification email. */
		'email_subject'            => __( 'New testimonial for %BLOGNAME%', 'strong-testimonials' ),
		/* translators: Default message for new testimonial notification email. */
		'email_message'            => __( 'New testimonial submission for %BLOGNAME%. This is awaiting action from the website administrator.', 'strong-testimonials' ),
		'captcha'                  => '',
		'honeypot_before'          => 0,
		'honeypot_after'           => 0,
		'messages'                 => $default_messages,
		'scrolltop_success'        => true,
		'scrolltop_success_offset' => 40,
		'scrolltop_error'          => true,
		'scrolltop_error_offset'   => 40,
		'success_action'           => 'message', // id | url
		'success_redirect_id'      => '',
		'success_redirect_url'     => '',
	);

	return $default_form_options;
}

/**
 * Default view options.
 *
 * @since 1.21.0
 */
function wpmtst_get_default_view_options() {
	$default_view_options = array(

		'mode' => array(
			'display'   => array(
				'name'        => 'display',
				'label'       => __( 'Display', 'strong-testimonials' ),
				'description' => __( 'Display your testimonials in a list or a grid.', 'strong-testimonials' ),
			),
			'slideshow' => array(
				'name'        => 'slideshow',
				'label'       => __( 'Slideshow', 'strong-testimonials' ),
				'description' => __( 'Create a slideshow of your testimonials.', 'strong-testimonials' ),
			),
			'form'      => array(
				'name'        => 'form',
				'label'       => __( 'Form', 'strong-testimonials' ),
				'description' => __( 'Display a testimonial submission form.', 'strong-testimonials' ),
			),
			'single_template' => array(
				'name'        => 'single_template',
				'label'       => __( 'Single Template', 'strong-testimonials-lucid-theme' ),
				'description' => __( 'When viewing the testimonial using a theme\'s single post template.', 'strong-testimonials' ),
			),
		),

		'order' => array(
			'random'     => _x( 'random', 'display order', 'strong-testimonials' ),
			'menu_order' => _x( 'menu order', 'display order', 'strong-testimonials' ),
			'newest'     => _x( 'newest first', 'display order', 'strong-testimonials' ),
			'oldest'     => _x( 'oldest first', 'display order', 'strong-testimonials' ),
		),

		'slideshow_effect' => array(
			'none'       => _x( 'no transition effect', 'slideshow transition option', 'strong-testimonials' ),
			'fade'       => _x( 'fade', 'slideshow transition option', 'strong-testimonials' ),
			'horizontal' => _x( 'scroll horizontally', 'slideshow transition option', 'strong-testimonials' ),
			'vertical'   => _x( 'scroll vertically', 'slideshow transition option', 'strong-testimonials' ),
		),

		'slideshow_height' => array(
			'dynamic' => _x( 'Adjust height for each slide', 'slideshow option', 'strong-testimonials' ),
			'static'  => _x( 'Set height to match the tallest slide', 'slideshow option', 'strong-testimonials' ),
		),

		/*
		 * METHODS
		 */
		'slideshow_nav_method' => array(
			'controls' => array(
				'none'   => array(
					'label' => _x( 'none', 'slideshow controls option', 'strong-testimonials' ),
					'args'  => array(  // base args; style will add more args
						'controls' => 0,
						'pager' => 0,
                        'autoControls' => 0,
					),
				),
				'full'   => array(
					'label' => _x( 'Bottom: previous / play-pause / next', 'slideshow controls option', 'strong-testimonials' ),
					'class' => 'controls-type-full',
					'add_position_class' => 1,
					'args'  => array(
						'pager' => 0,
						//'controls' => 1, // our slider.js script adds the controls
						'autoControls' => 1,
						'autoControlsCombine' => 1,
						'fullSetButtons' => 1,
						'fullSetText' => 1,
					)
				),
				'simple' => array(
					'label' => _x( 'Bottom: previous / next', 'slideshow controls option', 'strong-testimonials' ),
					'class' => 'controls-type-simple',
					'add_position_class' => 1,
					'args'  => array(
						'controls' => 1,
						'autoControls' => 0,
					)
				),
				'sides'  => array(
					'label' => _x( 'Sides: previous / next', 'slideshow controls option', 'strong-testimonials' ),
					'class' => 'controls-type-sides',
					'add_position_class' => 0,
					'args'  => array(
						'controls' => 1,
						'autoControls' => 0,
						'prevText' => '',
						'nextText' => '',
					)
				)
			),
			'pager'    => array(
				'none'   => array(
					'label' => _x( 'none', 'slideshow navigation option', 'strong-testimonials' ),
					'args'  => array(),
				),
				'full'   => array(
					'label' => _x( 'full', 'slideshow navigation option', 'strong-testimonials' ),
					//'class' => 'controls-pager-full',
					'class' => 'pager-type-full',
					'args'  => array(
						'pager' => 1,
					)
				),
			)
		),

	    /*
	     * STYLES
	     */
		'slideshow_nav_style' => array(
			'controls' => array(
				'buttons'  => array(
					'label' => _x( 'buttons 1', 'slideshow navigation option', 'strong-testimonials' ),
				    'class' => 'controls-style-buttons',
					'args'  => array(
					    'startText' => '',
					    'stopText' => '',
						'prevText' => '',
						'nextText' => '',
				    )
				),
				'buttons2' => array(
					'label' => _x( 'buttons 2', 'slideshow navigation option', 'strong-testimonials' ),
					'class' => 'controls-style-buttons2',
					'args'  => array(
					    'startText' => '',
					    'stopText' => '',
						'prevText' => '',
						'nextText' => '',
					)
				),
				'buttons3' => array(
					'label' => _x( 'buttons 3', 'slideshow navigation option', 'strong-testimonials' ),
					'class' => 'controls-style-buttons3',
					'args'  => array(
					    'startText' => '',
					    'stopText' => '',
						'prevText' => '',
						'nextText' => '',
					)
				),
				'text' => array(
					'label' => _x( 'text', 'slideshow navigation option', 'strong-testimonials' ),
					'class' => 'controls-style-text',
					'args' => array(
						'startText' => _x( 'Play', 'slideshow control', 'strong-testimonials' ),
						'stopText' => _x( 'Pause', 'slideshow control', 'strong-testimonials' ),
						'prevText' => _x( 'Previous', 'slideshow_control', 'strong-testimonials' ),
						'nextText' => _x( 'Next', 'slideshow_control', 'strong-testimonials' ),
					)
				)
			),
			'pager' => array(
				'buttons'  => array(
					'label' => _x( 'buttons', 'slideshow navigation option', 'strong-testimonials' ),
					'class' => 'pager-style-buttons',
					'args'  => array(
					    'buildPager' => 'icons',
					    'simpleSetPager' => 1,
					)
				),
				'text'     => array(
					'label' => _x( 'text', 'slideshow navigation option', 'strong-testimonials' ),
					'class' => 'pager-style-text',
					'args'  => array(
						'buildPager' => null,
					    'simpleSetText' => 1,
					)
				)
			)
		),

		/*
		 * Position is shared by Controls and Pagination.
		 */
		'slideshow_nav_position' => array(
			'inside'  => _x( 'inside', 'slideshow navigation option', 'strong-testimonials' ),
			'outside' => _x( 'outside', 'slideshow navigation option', 'strong-testimonials' ),
		),
	);
	return $default_view_options;
}

/**
 * option name: wpmtst_view_default
 *
 * @since 1.21.0
 */
function wpmtst_get_default_view() {
	$default_view = array(
		'all'                => 1,
		'background'         => array(
			'color'              => '',
			'type'               => '',
			'preset'             => '',
			'gradient1'          => '',
			'gradient2'          => '',
			'example-font-color' => 'dark',
		),
		'category'           => 'all',
		'class'              => '',
		'client_section'     => array(
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
		'column_count'       => 2,
		'container_class'    => '',
		'container_data'     => '',
		'content'            => 'entire',
		'count'              => 1,
		'divi_builder'       => 0,
		'excerpt_length'     => 55,
		'form_ajax'          => 0,
		'form_id'            => 1,
		'gravatar'           => 'no',
		'id'                 => '',
		'layout'             => '',
		'lightbox'           => '',
		'mode'               => 'display',
		'more_full_post'     => false,
		'more_post'          => true,
		'more_post_ellipsis' => true,
		'more_post_text'     => _x( 'Read more', 'link', 'strong-testimonials' ),
		'more_page'          => false,
		'more_page_hook'     => 'wpmtst_view_footer',
		'more_page_id'       => 0,
		'more_page_text'     => _x( 'Read more testimonials', 'link', 'strong-testimonials' ),
		'nav'                => 'after',
		'note'               => '',
		'order'              => 'oldest',
		'page'               => '',
		'pagination'         => false,
		'pagination_type'    => 'simple',
		'per_page'           => 5,
		'slideshow'          => 0,
		'slideshow_settings' => array(
			'effect'             => 'fade',
			'speed'              => 1,
			'pause'              => 8,
			'auto_start'         => true,
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
		'template'           => 'default:content',
		'thumbnail'          => true,
		'thumbnail_size'     => 'thumbnail',
		'thumbnail_height'   => null,
		'thumbnail_width'    => null,
		'title'              => true,
		'title_link'         => false,
		'use_default_length' => true,
		'use_default_more'   => false,
	);
	ksort( $default_view );

	return $default_view;
}

/**
 * The contexts for string translation in WPML & Polylang plugins.
 *
 * @since 1.21.0
 *
 * @return array
 */
function wpmtst_get_default_l10n_contexts() {
	/* Translators: For string translation in WPML & Polylang plugins. */
	$contexts = array(
		'strong-testimonials-form-fields'   => __( 'Testimonial Form Fields', 'strong-testimonials' ),
		'strong-testimonials-form-messages' => __( 'Testimonial Form Messages', 'strong-testimonials' ),
		'strong-testimonials-notification'  => __( 'Testimonial Notification Options', 'strong-testimonials' ),
		'strong-testimonials-read-more'     => __( '"Read More" Link Text', 'strong-testimonials' ),
	);
	return $contexts;
}


/**
 * Update and conversion history.
 *
 * @since 2.12.4
 *
 * @return array|mixed|void
 */
function wpmtst_get_update_history() {
	$history = get_option( 'wpmtst_history' );
	if ( ! $history ) {
		$history = array();
	}

	return $history;
}
