<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$view_id = $settings->strong_testimonials_view_select;
if ( 'none' !== $view_id ) {
	echo do_shortcode( "[testimonial_view id='{$view_id}']" );

} else {
	echo esc_html__( 'No view was selected', 'strong-testimonials' );
}
