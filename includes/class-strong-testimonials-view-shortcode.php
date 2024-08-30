<?php
/**
 * Class Strong_Testimonials_View_Shortcode
 *
 * @since 2.28.0
 */

class Strong_Testimonials_View_Shortcode {

	/**
	 * @var string
	 */
	public $shortcode = 'testimonial_view';

	public function __construct() {
		add_shortcode( $this->shortcode, array( $this, 'testimonial_view_shortcode' ) );
		add_filter( 'shortcode_atts_' . $this->shortcode, array( $this, 'testimonial_view_filter' ), 10, 3 );

		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'no_texturize_shortcodes', array( $this, 'no_texturize_shortcodes' ) );

		add_filter( 'strong_view_html', array( $this, 'remove_whitespace' ) );
		add_filter( 'strong_view_form_html', array( $this, 'remove_whitespace' ) );
	}

	public function get_shortcode() {
		return $this->shortcode;
	}

	/**
	 * Our primary shortcode.
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return mixed|string
	 */
	public function testimonial_view_shortcode( $atts, $content = null ) {
		$out = shortcode_atts(  // phpcs:ignore sanitized in render_view 
			array(),
			$atts,
			$this->shortcode
		);

		return $this->render_view( $out );
	}

	/**
	 * Shortcode attribute filter
	 *
	 * @since 1.21.0
	 *
	 * @param array $out The output array of shortcode attributes.
	 * @param array $pairs The supported attributes and their defaults.
	 * @param array $atts The user defined shortcode attributes.
	 *
	 * @return array
	 */
	public function testimonial_view_filter( $out, $pairs, $atts ) {
		return WPMST()->render->parse_view( $out, $pairs, $atts );
	}

	/**
	 * Render the View.
	 *
	 * @param $out
	 *
	 * @return mixed|string
	 */
	public function render_view( $out ) {
		// Did we find this view?
		if ( isset( $out['view_not_found'] ) && $out['view_not_found'] ) {
			if ( current_user_can( 'strong_testimonials_views' ) ) {
				ob_start();
				?>
				<p style="color: #CD0000;">
					<?php
						// translators: %s is the placeholder for the testimonial view name or identifier.
						printf( esc_html__( 'Testimonial view %s not found.', 'strong-testimonials' ), esc_attr( $out['view'] ) );
					?>
					<br>
					<span style="color: #777; font-size: 0.9em;"><?php esc_html_e( '(Only administrators see this message.)', 'strong-testimonials' ); ?></span>
				</p>
				<?php
				return ob_get_clean();
			}
		}

		switch ( $out['mode'] ) {
			case 'form':
				$view = new Strong_View_Form( $out );
				if ( isset( $_GET['success'] ) && isset( $_GET['formid'] ) && (int) $out['form_id'] === (int) $_GET['formid'] ) {
					$view->success();
				} else {
					$view->build();
				}
				break;
			case 'slideshow':
				$view = new Strong_View_Slideshow( $out );
				$view->build();
				break;
			default:
				$view = new Strong_View_Display( $out );
				$view->build();
		}

		return $view->output();
	}

	/**
	 * Remove whitespace between tags. Helps prevent double wpautop in plugins
	 * like Posts For Pages and Custom Content Shortcode.
	 *
	 * @param $html
	 *
	 * @since 2.3
	 *
	 * @return mixed
	 */
	public function remove_whitespace( $html ) {
		$options = get_option( 'wpmtst_options' );
		if ( $options['remove_whitespace'] ) {
			return wpmtst_strip_whitespace( $html );
		}

		return $html;
	}

	/**
	 * Do not texturize shortcode.
	 *
	 * @since 1.11.5
	 *
	 * @param $shortcodes
	 *
	 * @return array
	 */
	public function no_texturize_shortcodes( $shortcodes ) {
		$shortcodes[] = $this->shortcode;

		return $shortcodes;
	}
}
