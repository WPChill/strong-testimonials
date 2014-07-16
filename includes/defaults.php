<?php
/**
 * Strong Testimonials - Default options
 * Version: 1.7
 *
 * Populates default_options and default_fields.
 */

// --------
// Settings
// --------

$default_options = array(
		'per_page'      => '5',
		'admin_notify'  => 0,
		'admin_email'   => '',
		'captcha'       => '',
);

$default_options['cycle'] = array(
		'cycle-order'   => 'recent',
		'cycle-effect'  => 'fade',
		'cycle-speed'   => 1.5,
		'cycle-timeout' => 8,
		'cycle-pause'   => 1,
);

// ---------
// Templates
// ---------

$default_options['default_template'] = '[wpmtst-text field="client_name" class="name"]' . PHP_EOL
	.'[wpmtst-link url="company_website" text="company_name" target="_blank" class="company"]';

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
		'error' => 'This field is required.',
		'placeholder' => '',
		'before' => '',
		'after' => '',
		'admin_table' => 0,
		'admin_table_option' => 1,
		'template' => 1,
);

$field_types = array();

$field_types['post'] = array(
		'post_title' => array(
				'input_type' => 'text',
				'option_label' => 'Testimonial Title',
				'map' => 'post_title',
				'admin_table' => 1,
				'admin_table_option' => 0,
		),
		'post_content' => array(
				'input_type' => 'textarea',
				'option_label' => 'Testimonial Content',
				'map' => 'post_content',
				'required' => 1,
				'core' => 1,
				'admin_table' => 0,
		),
		'featured_image' => array(
				'input_type' => 'file',
				'option_label' => 'Featured Image',
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
				'template' => 0,
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
				'label'  => 'Default Field Group',
				'fields' => array(
						// ------
						// CUSTOM
						// ------
						0 => array(
								'record_type' => 'custom',
								'name' => 'client_name',
								'label' => 'Full Name',
								'input_type' => 'text',
								'required' => 1,
								'after' => 'What is your full name?',
								'admin_table' => 1,
						),
						1 => array(
								'record_type' => 'custom',
								'name' => 'email',
								'label' => 'Email',
								'input_type' => 'email',
								'required' => 1,
								'after' => 'What is your email address?',
						),
						3 => array(
								'record_type' => 'custom',
								'name' => 'company_name',
								'label' => 'Company Name',
								'input_type' => 'text',
								'after' => 'What is your company name?',
						),
						4 => array(
								'record_type' => 'custom',
								'name' => 'company_website',
								'label' => 'Company Website',
								'input_type' => 'url',
								'after' => 'Does your company have a website?',
						),
						// ----
						// POST
						// ----
						5 => array(
								'record_type' => 'post',
								'name' => 'post_title',
								'label' => 'Heading',
								'input_type' => 'text',
								'required' => 0,
								'after' => 'A headline for your testimonial.',
						),
						6 => array(
								'record_type' => 'post',
								'name' => 'post_content',
								'label' => 'Testimonial',
								'input_type' => 'textarea',
								'required' => 1,
								'after' => 'What do you think about us?',
						),
						7 => array(
								'record_type' => 'post',
								'name' => 'featured_image',
								'label' => 'Photo',
								'input_type' => 'file',
								'after' => 'Would you like to include a photo?',
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
		'label'  => 'Custom Field Group',
		'fields' => $field_groups['default']['fields'],
);

// Assemble default field settings.
$default_fields = array(
		'field_base' => $field_base,
		'field_types' => $field_types,
		'field_groups' => $field_groups,
		'current_field_group' => 'custom',
);
unset( $field_base, $field_types, $field_groups );


// ----------------
// Display Template
// ----------------

// section key = section name
// field label = fetched from custom fields array
$default_sections = array(
		'header' => array(
				// 'name' => 'header',
				'wrapper_class' => '',
				'fields' => array(
						0 => array(
								'name' => 'post_title',
								// 'label' => 'Title',
								'type' => 'h3',
								'class' => 'heading',
								// 'record_type' => 'post',
						),
				),
		),
		'content' => array(
				// 'name' => 'content',
				'wrapper_class' => '',
				'fields' => array(
						0 => array(
								'name' => 'featured_image',
								// 'label' => 'Featured Image',
								'type' => 'featured_image',
								'class' => 'photo',
								// 'record_type' => 'post',
						),
						1 => array(
								'name' => 'post_content',
								// 'label' => 'Testimonial',
								'type' => 'post_content',
								'class' => 'content',
								// 'record_type' => 'post',
								'locked' => 1,
						),
				)
		),
		'footer' => array(
				// 'name' => 'footer',
				'wrapper_class' => 'client',
				'fields' => array(
						0 => array(
								'name' => 'client_name',
								// 'label' => 'Client Name',
								'type' => 'text',
								'class' => 'name',
								// 'record_type' => 'custom',
						),
						/*
						1 => array(
								'name' => 'company_website',
								// 'label' => 'Website',
								'type' => 'link',
								'class' => 'company',
								// 'text' => 'company_name',
								'record_type' => 'custom',
						),
						2 => array(
								'name' => 'company_name',
								// 'label' => 'Company Name',
								'type' => 'link-text',
								'for' => 'company_website',
								'record_type' => 'custom',
						),
						*/
				),
		),
);


$default_templates = array(
		'templates' => array(
				'default' => array(
						'name'   => 'default',
						'label'  => 'Default Template',
						'sections' => $default_sections,
						'wrapper_class' => 'inner',
				),
				'custom' => array(
						'name'   => 'custom',
						'label'  => 'Custom Template',
						'sections' => $default_sections,
						'wrapper_class' => 'inner',
				)
		),
		'current_template' => 'custom',
);
unset( $default_sections );
