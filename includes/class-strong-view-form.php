<?php
/**
 * View Form Mode class.
 *
 * @since 2.16.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_View_Form' ) ) :

class Strong_View_Form extends Strong_View {

	/**
	 * Strong_View_Form constructor.
	 *
	 * @param array $atts
	 */
	public function __construct( $atts = array() ) {
		parent::__construct();
		$this->atts = apply_filters( 'wpmtst_view_atts', $atts );
	}

	/**
	 * Process the view.
	 *
	 * Used by main class to load the scripts and styles for this View.
	 */
	public function process() {
		$this->build_classes();
		$this->find_stylesheet();
		$this->load_dependent_scripts();
		$this->load_extra_stylesheets();
		$this->load_special();
	}

	/**
	 * Build the view.
	 */
	public function build() {

		do_action( 'wpmtst_view_build_before', $this );

		if ( isset( $_GET['success'] ) ) {

			$this->find_stylesheet();
			$this->on_form_success();
			do_action( 'wpmtst_form_success', $this->atts );

			$this->html = apply_filters( 'wpmtst_form_success_message', '<div class="testimonial-success">' . wpmtst_get_form_message( 'submission-success' ) . '</div>' );

			return;
		}

		$this->build_classes();
		$this->find_stylesheet();
		$this->load_dependent_scripts();
		$this->load_extra_stylesheets();
		$this->load_special();

		$fields      = wpmtst_get_form_fields( $this->atts['form_id'] );
		$form_values = array( 'category' => $this->atts['category'] );

		foreach ( $fields as $key => $field ) {
			$form_values[ $field['name'] ] = '';
		}

		$previous_values = WPMST()->get_form_values();
		if ( $previous_values ) {
			$form_values = array_merge( $form_values, $previous_values );
		}

		WPMST()->set_form_values( $form_values );

		/**
		 * Add filters here.
		 */

		/**
		 * Locate template.
		 */
		$this->template_file = WPMST()->templates->get_template_attr( $this->atts, 'template' );

		/**
		 * Allow add-ons to hijack the output generation.
		 */
		if ( has_filter( 'wpmtst_render_view_template' ) ) {
			$html = apply_filters( 'wpmtst_render_view_template', '', $this );
		}
		else {
			ob_start();
			/** @noinspection PhpIncludeInspection */
			include( $this->template_file );
			$html = ob_get_clean();
		}
		// TODO apply content filters

		/**
		 * Remove filters here.
		 */

		do_action( 'wpmtst_form_rendered', $this->atts );

		$this->html = apply_filters( 'strong_view_form_html', $html, $this );

	}

	/**
	 * Build class list based on view attributes.
	 */
	public function build_classes() {

		$container_class_list = array(
			'strong-view-id-' . $this->atts['view'],
			$this->get_template_css_class(),
		);

		if ( is_rtl() ) {
			$container_class_list[] = 'rtl';
		}

		if ( $this->atts['class'] ) {
			$container_class_list[] = $this->atts['class'];
		}

		$container_data_list = array();

		/**
		 * Filter classes.
		 */
		$this->atts['container_data']  = apply_filters( 'wpmtst_view_container_data', $container_data_list );
		$this->atts['container_class'] = join( ' ', apply_filters( 'wpmtst_view_container_class', $container_class_list ) );

		/**
		 * Store updated atts.
		 */
		WPMST()->set_atts( $this->atts );

	}

	/**
	 * Load extra scripts and styles.
	 */
	public function load_special() {
		WPMST()->add_style( 'wpmtst-rating-form' );

		$form_options = get_option( 'wpmtst_form_options' );
		$args         = array(
			'scrollTop' => $form_options['scrolltop_error'],
			'offset'    => $form_options['scrolltop_error_offset'],
		);

		WPMST()->add_script( 'wpmtst-form' );
		WPMST()->add_script_var( 'wpmtst-form', 'formError', $args );

		if ( wpmtst_using_form_validation_script() ) {
			if ( wp_script_is( 'wpmtst-validation-lang', 'registered' ) ) {
				WPMST()->add_script( 'wpmtst-validation-lang' );
			}

			$args = array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'ajaxSubmit' => isset( $this->atts['form_ajax'] ) && $this->atts['form_ajax'] ? 1 : 0,
			);
			WPMST()->add_script( 'wpmtst-form-validation' );
			WPMST()->add_script_var( 'wpmtst-form-validation', 'form_ajax_object', $args );
		}

		if ( $form_options['honeypot_before'] ) {
			add_action( 'wp_footer', 'wpmtst_honeypot_before_script' );
		}

		if ( $form_options['honeypot_after'] ) {
			add_action( 'wp_footer', 'wpmtst_honeypot_after_script' );
		}
	}

	public function on_form_success() {
		$form_options = get_option( 'wpmtst_form_options' );
		$args         = array(
			'scrollTop' => $form_options['scrolltop_success'],
			'offset'    => $form_options['scrolltop_success_offset'],
		);
		WPMST()->add_script( 'wpmtst-form-success' );
		WPMST()->add_script_var( 'wpmtst-form-success', 'formSuccess', $args );
	}
}

endif;
