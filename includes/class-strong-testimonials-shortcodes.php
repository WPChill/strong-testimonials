<?php
/**
 * Class Strong_Testimonials_Shortcodes
 *
 * @since 2.28.0
 */
class Strong_Testimonials_Shortcodes {

	/**
	 * Our primary shortcode.
	 */
	const SHORTCODE = 'testimonial_view';

	/**
	 * Strong_Testimonials_Shortcodes constructor.
	 */
	public function __construct() {}

	/**
	 * Initialize.
	 */
	public static function initialize() {
		add_shortcode( self::SHORTCODE, array( __CLASS__, 'testimonial_view_shortcode' ) );
		add_filter( 'shortcode_atts_' . self::SHORTCODE, array( __CLASS__, 'testimonial_view_filter' ), 10, 3 );

		add_shortcode( 'testimonial_count', array( __CLASS__, 'testimonial_count' ) );

		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'no_texturize_shortcodes', array( __CLASS__, 'no_texturize_shortcodes' ) );

		add_filter( 'strong_view_html', array( __CLASS__, 'strong_view_html' ) );
		add_filter( 'strong_view_form_html', array( __CLASS__, 'strong_view_html' ) );
	}

	/**
	 * Our primary shortcode.
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return mixed|string
	 */
	public static function testimonial_view_shortcode( $atts, $content = null ) {
		$out = shortcode_atts(
			WPMST()->render->get_view_defaults(),   // $pairs
			$atts,
			self::SHORTCODE
		);

		return self::render_view( $out );
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
	public static function testimonial_view_filter( $out, $pairs, $atts ) {
		return WPMST()->render->parse_view( self::normalize_empty_atts( $out ), $pairs, $atts );
	}

	/**
	 * Render the View.
	 *
	 * @param $out
	 *
	 * @return mixed|string
	 */
	public static function render_view( $out ) {
		// Did we find this view?
		if ( isset( $out['view_not_found'] ) && $out['view_not_found'] ) {
			return '<p style="color:red">' . sprintf( __( 'Strong Testimonials error: View %s not found' ), $out['view'] ) . '</p>';
		}

		if ( $out['form'] ) {
			$view = new Strong_View_Form( $out );
		} elseif ( $out['slideshow'] ) {
			$view = new Strong_View_Slideshow( $out );
		} else {
			$view = new Strong_View_Display( $out );
		}
		$view->build();

		return $view->output();
	}

	/**
	 * Normalize empty shortcode attributes.
	 *
	 * Turns atts into tags - brilliant!
	 * Thanks http://wordpress.stackexchange.com/a/123073/32076
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	public static function normalize_empty_atts( $atts ) {
		if ( !empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
		}

		return $atts;
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
	public static function no_texturize_shortcodes( $shortcodes ) {
		$shortcodes[] = self::SHORTCODE;

		return $shortcodes;
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
	public static function strong_view_html( $html ) {
		$options = get_option( 'wpmtst_options' );
		if ( $options['remove_whitespace'] ) {
			$html = preg_replace( '~>\s+<~', '><', $html );
		}

		return $html;
	}

	/**
	 * A shortcode to display the number of testimonials.
	 *
	 * For all: [testimonial_count]
	 * For a specific category (by slug): [testimonial_count category="abc"]
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @since 2.19.0
	 *
	 * @return int
	 */
	public static function testimonial_count( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'category' => '',
			),
			$atts
		);

		$args = array(
			'posts_per_page'           => -1,
			'post_type'                => 'wpm-testimonial',
			'post_status'              => 'publish',
			'wpm-testimonial-category' => $atts['category'],
			'suppress_filters'         => true,
		);
		$posts_array = get_posts( $args );

		return count( $posts_array );
	}

}

Strong_Testimonials_Shortcodes::initialize();
