<?php

/**
 * Handles the testimonial widget
 */

namespace ElementorStrongTestimonials\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Strong_Testimonials_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'strong_testimonials_elementor_views';
	}

	public function get_title() {
		return esc_html__( 'Strong Testimonials', 'strong-testimonials' );
	}

	public function get_icon() {
		return 'eicon-editor-quote';
	}

	public function get_cattegories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'strong-testimonials' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'strong_testimonials_views_select',
			array(
				'label'   => esc_html__( 'Select/Search View', 'strong-testimonials' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => get_formatted_views(),
				'default' => 'none',
			)
		);

		$this->end_controls_section();
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'strong-testimonials' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'strong_testimonials_views_select',
			array(
				'label'   => esc_html__( 'Select/Search View', 'strong-testimonials' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => get_formatted_views(),
				'default' => 'none',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings = $this->get_settings_for_display();
		$view_id  = esc_html( $settings['strong_testimonials_views_select'] );
		if ( 'none' !== $view_id ) {
			echo "[testimonial_view id={$view_id}]";  // phpcs:ignore $view_id OK
		}
	}
}
