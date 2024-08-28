<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Strong_Testimonials_Beaver_Block extends FLBuilderModule {
	public function __construct() {
		parent::__construct(
			array(
				'name'            => esc_html__( 'Strong Testimonials', 'strong-testimonials' ),
				'description'     => esc_html__( 'A block for Strong Testimonials Views', 'strong-testimonials' ),
				'category'        => esc_html__( 'Strong Testimonials', 'strong-testimonials' ),
				'icon'            => 'format-image.svg',
				'dir'             => WPMTST_DIR . 'includes/strong-testimonials-beaver-block/',
				'url'             => WPMTST_URL . 'includes/strong-testimonials-beaver-block/',
				'partial_refresh' => true,
			)
		);
	}
}

FLBuilder::register_module(
	'Strong_Testimonials_Beaver_Block',
	array(
		'strong_testimonials' => array(
			'title'    => esc_html__( 'Strong Testimonials', 'strong-testimonials' ),
			'sections' => array(
				'strong_testimonials_view_section' => array(
					'title'  => esc_html__( 'Select the Strong Testimonials View you want', 'strong-testimonials' ),
					'fields' => array(
						'strong_testimonials_view_select' => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Select Strong Testimonials View', 'strong-testimonials' ),
							'default' => 'none',
							'options' => get_formatted_views(),
						),
					),
				),
			),
		),
	)
);
