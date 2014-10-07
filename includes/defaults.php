<?php
/**
 * Strong Testimonials - Default options
 *
 * Populates default_options and default_options.
 *
 * @since 1.8
 */

 
// --------
// Settings
// --------

$default_options = array(
		'per_page'          => '5',
		'admin_notify'      => 0,
		'admin_email'       => '',
		'captcha'           => '',
		'honeypot_before'   => 0,
		'honeypot_after'    => 1,
		'load_page_style'   => 1,
		'load_widget_style' => 1,
		'load_form_style'   => 1,
);

$default_cycle = array(
		'category'    => 'all',
		'order'       => 'recent',
		'all'         => 0,
		'limit'       => 3,
		'title'       => 1,
		'content'     => 'entire',
		'char-limit'  => 200,
		'images'      => 1,
		'client'      => 0,
		'more'        => 0,
		'more-page'   => '',
		'effect'      => 'fade',
		'speed'       => 1.5,
		'timeout'     => 8,
		'pause'       => 1,
);

// ---------
// Templates
// ---------

$default_options['default_template'] = '[wpmtst-text field="client_name" class="name"]' . PHP_EOL
	. '[wpmtst-link url="company_website" text="company_name" new_tab class="company"]';

$default_options['client_section'] = $default_options['default_template'];

// ------
// Fields
// ------

// common field properties
$field_base = array(
		'name' => '',
		'label' => '',
		'input_type' => '',
		'required' => 0,
		'error' => __( 'This field is required.', 'strong-testimonials' ),
		'placeholder' => '',
		'before' => '',
		'after' => '',
		'admin_table' => 0,
		'admin_table_option' => 1,
);

$field_types = array();
$field_types['post'] = array(
		'post_title' => array(
				'input_type' => 'text',
				'option_label' => __( 'Testimonial Title', 'strong-testimonials' ),
				'map' => 'post_title',
				'admin_table' => 1,
				'admin_table_option' => 0,
		),
		'post_content' => array(
				'input_type' => 'textarea',
				'option_label' => __( 'Testimonial Content', 'strong-testimonials' ),
				'map' => 'post_content',
				'required' => 1,
				'core' => 1,
				'admin_table' => 0,
		),
		'featured_image' => array(
				'input_type' => 'file',
				'option_label' => __( 'Featured Image', 'strong-testimonials' ),
				'map' => 'featured_image',
				'admin_table' => 0,
		),
);
foreach ( $field_types['post'] as $key => $array ) {
	$field_types['post'][$key] = array_merge( $field_base, $array );
}

$field_types['custom'] = array(
		'text' => array(
				'input_type' => 'text',
				'option_label' => 'text',
		),
		'email' => array(
				'input_type' => 'text',
				'option_label' => 'email (text)',
		),
		'url' => array(
				'input_type' => 'text',
				'option_label' => 'url (text)',
		)
);
foreach ( $field_types['custom'] as $key => $array ) {
	$field_types['custom'][$key] = array_merge( $field_base, $array );
}

$field_groups = array(
		'default' => array(
				'name'   => 'default',
				'label'  => __( 'Default Field Group', 'strong-testimonials' ),
				'fields' => array(
						// ------
						// CUSTOM
						// ------
						0 => array(
								'record_type' => 'custom',
								'name' => 'client_name',
								'label' => __( 'Full Name', 'strong-testimonials' ),
								'input_type' => 'text',
								'required' => 1,
								'after' => __( 'What is your full name?', 'strong-testimonials' ),
								'admin_table' => 1,
						),
						1 => array(
								'record_type' => 'custom',
								'name' => 'email',
								'label' => __( 'Email', 'strong-testimonials' ),
								'input_type' => 'email',
								'required' => 1,
								'after' => __( 'What is your email address?', 'strong-testimonials' ),
						),
						3 => array(
								'record_type' => 'custom',
								'name' => 'company_name',
								'label' => __( 'Company Name', 'strong-testimonials' ),
								'input_type' => 'text',
								'after' => __( 'What is your company name?', 'strong-testimonials' ),
						),
						4 => array(
								'record_type' => 'custom',
								'name' => 'company_website',
								'label' => __( 'Company Website', 'strong-testimonials' ),
								'input_type' => 'url',
								'after' => __( 'Does your company have a website?', 'strong-testimonials' ),
						),
						// ----
						// POST
						// ----
						5 => array(
								'record_type' => 'post',
								'name' => 'post_title',
								'label' => __( 'Heading', 'strong-testimonials' ),
								'input_type' => 'text',
								'required' => 0,
								'after' => __( 'A headline for your testimonial.', 'strong-testimonials' ),
						),
						6 => array(
								'record_type' => 'post',
								'name' => 'post_content',
								'label' => __( 'Testimonial', 'strong-testimonials' ),
								'input_type' => 'textarea',
								'required' => 1,
								'after' => __( 'What do you think about us?', 'strong-testimonials' ),
						),
						7 => array(
								'record_type' => 'post',
								'name' => 'featured_image',
								'label' => __( 'Photo', 'strong-testimonials' ),
								'input_type' => 'file',
								'after' => __( 'Would you like to include a photo?', 'strong-testimonials' ),
								'admin_table' => 1,
						),
				)
		)
);
foreach ( $field_groups['default']['fields'] as $key => $array ) {
	if ( 'post' == $array['record_type'] )
		$field_groups['default']['fields'][$key] = array_merge( $field_types['post'][$array['name']], $array );
	else
		$field_groups['default']['fields'][$key] = array_merge( $field_types['custom'][$array['input_type']], $array );
}

// Copy default field group to custom field group.
$field_groups['custom'] = array(
		'name'   => 'custom',
		'label'  => __( 'Custom Field Group', 'strong-testimonials' ),
		'fields' => $field_groups['default']['fields'],
);

// Assemble default field settings.
$default_fields['field_base'] = $field_base;
$default_fields['field_types'] = $field_types;
$default_fields['field_groups'] = $field_groups;
$default_fields['current_field_group'] = 'custom';
