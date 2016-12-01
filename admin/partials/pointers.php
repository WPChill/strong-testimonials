<?php
/**
 * @param $hook_suffix
 */
function wpmtst_pointer_load( $hook_suffix ) {
	
	// Get pointers for this screen
	$screen = get_current_screen();
	$pointers = apply_filters( 'wpmtst_admin_pointers-' . $screen->id, array() );
	$pointers = apply_filters( 'wpmtst_admin_pointers-' . $screen->post_type, $pointers );
	
	if ( ! $pointers || ! is_array( $pointers ) )
		return;

	// Get dismissed pointers
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$valid_pointers = array();

	// Check pointers and remove dismissed ones.
	foreach ( $pointers as $pointer_id => $pointer ) {

		// Sanity check
		if ( in_array( $pointer_id, $dismissed ) || empty( $pointer ) || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
			continue;

		$pointer['pointer_id'] = $pointer_id;

		// Add the pointer to $valid_pointers array
		$valid_pointers['pointers'][] =  $pointer;
	}
	
	// No valid pointers? Stop here.
	if ( empty( $valid_pointers ) )
		return;

	wp_enqueue_style( 'wp-pointer' );
	wp_enqueue_script( 'wpmtst-pointer', WPMTST_ADMIN_URL . 'js/pointers.js', array( 'wp-pointer' ) );
	wp_localize_script( 'wpmtst-pointer', 'wpmtstPointer', $valid_pointers );
}
add_action( 'admin_enqueue_scripts', 'wpmtst_pointer_load', 1000 );

/**
 * @param $p
 * @return mixed
 */
function wpmtst_register_pointers( $p ) {
	$p['wpmtst_lightbox'] = array(
		'target'  => '#view-lightbox',
		'options' => array(
			'content'  => sprintf( '<h3>%s</h3> %s',
				sprintf( __( 'New in version %s', 'strong-testimonials' ), '1.21' ),
				sprintf( '<p>%s</p>', __( 'Works with multiple lightbox plugins like <a href="https://wordpress.org/plugins/simple-colorbox/" target="_blank">Simple Colorbox</a> and <a href="https://wordpress.org/plugins/simple-lightbox/" target="_blank">Simple Lightbox</a>.', 'strong-testimonials' ) )
			),
			'position' => array( 'edge' => 'top', 'align' => 'left' )
		)
	);
	
	$p['wpmtst_view_linktext'] = array(
		'target'  => '#custom-field-list2',
		'options' => array(
			'content'  => sprintf( '<h3>%s</h3> %s',
				sprintf( __( 'New in version %s', 'strong-testimonials' ), '1.24' ),
				sprintf( '<p>%s</p>', __( 'New link type: Create a link using a field and its label. Great for social media profiles.', 'strong-testimonials' ) )
			),
			'position' => array( 'edge' => 'right', 'align' => 'middle' )
		)
	);
	
	return $p;
}
add_filter( 'wpmtst_admin_pointers-wpm-testimonial', 'wpmtst_register_pointers' );
