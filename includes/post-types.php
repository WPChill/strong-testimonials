<?php
/**
 * Register Post Type and Taxonomy
 *
 * @since 1.4.0
 * @since 1.8.0   $args['hierarchical'] => false
 * @since 1.15.10 $args['exclude_from_search'] => false
 * @since 2.4.0   Move 'custom-fields' to an option.
 *                Added 'wpmtst_testimonial_supports' filter.
 *                Added 'wpmtst_exclude_from_search' filter.
 */
function wpmtst_register_cpt() {

	$testimonial_labels = array(
		'name'               => _x( 'Testimonials', 'post type general name', 'strong-testimonials' ),
		'menu_name'          => apply_filters( 'wpmtst_testimonials_menu_name', _x( 'Testimonials', 'admin menu name', 'strong-testimonials' ) ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name', 'strong-testimonials' ),
		'add_new'            => _x( 'Add New', 'post type', 'strong-testimonials' ),
		'add_new_item'       => __( 'Add New Testimonial', 'strong-testimonials' ),
		'edit_item'          => __( 'Edit Testimonial', 'strong-testimonials' ),
		'new_item'           => __( 'New Testimonial', 'strong-testimonials' ),
		'all_items'          => __( 'All Testimonials', 'strong-testimonials' ),
		'view_item'          => __( 'View Testimonial', 'strong-testimonials' ),
		'search_items'       => __( 'Search Testimonials', 'strong-testimonials' ),
		'not_found'          => __( 'Nothing Found', 'strong-testimonials' ),
		'not_found_in_trash' => __( 'Nothing found in Trash', 'strong-testimonials' ),
		'parent_item_colon'  => ''
	);

	$supports = apply_filters( 'wpmtst_testimonial_supports', array(
		'title',
		'excerpt',
		'editor',
		'thumbnail',
		'page-attributes',
	) );

	$testimonial_args = array(
		'labels'              => $testimonial_labels,
		'singular_label'      => _x( 'testimonial', 'post type singular label', 'strong-testimonials' ),
		'public'              => true,
		'show_ui'             => true,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'rewrite'             => array( 'slug' => _x( 'testimonial', 'slug', 'strong-testimonials' ) ), // @since 1.8
		'menu_icon'           => 'dashicons-editor-quote',
		'menu_position'       => 20,
		'exclude_from_search' => apply_filters( 'wpmtst_exclude_from_search', false ),
		'supports'            => $supports,
		'taxonomies'          => array( 'wpm-testimonial-category' ),
	);

	register_post_type( 'wpm-testimonial', $testimonial_args );

	/**
	 * Additional permastructure.
	 * This will override other CPTs with same slug.
	 */
	// add_permastruct( 'wpm-testimonial', "review/%wpm-testimonial%", array( 'slug' => __( 'review', 'strong-testimonials' ) ) );


	$categories_labels = array(
		'name'               => __( 'Testimonial Categories', 'strong-testimonials' ),
		'singular_name'      => __( 'Testimonial Category', 'strong-testimonials' ),
		'menu_name'          => __( 'Categories' ),
		'all_items'          => __( 'All categories' ),
	);

	register_taxonomy( 'wpm-testimonial-category', array( 'wpm-testimonial' ), array(
		'hierarchical' => true,
		'labels'       => $categories_labels,
		'rewrite'      => array(
			'slug' => 'view',
		)
	) );

}
add_action( 'init', 'wpmtst_register_cpt', 5 );


/**
 * Modify testimonial features.
 *
 * @since 2.4.0
 * @param $supports
 *
 * @return array
 */
function wpmtst_testimonial_supports( $supports ) {
	$options = get_option( 'wpmtst_options' );

	if ( isset( $options['support_custom_fields'] ) && $options['support_custom_fields'] )
		$supports[] = 'custom-fields';

	if ( isset( $options['support_comments'] ) && $options['support_comments'] )
		$supports[] = 'comments';

	return $supports;
}
add_filter( 'wpmtst_testimonial_supports', 'wpmtst_testimonial_supports' );


/**
 * Add testimonial-specific messages.
 *
 * @param $messages
 * @since 2.12.0
 * @todo Action Hook
 *
 * @return mixed
 */
function wpmtst_updated_messages( $messages ) {
	global $post;

	$preview_url = get_preview_post_link( $post );

	$permalink = get_permalink( $post->ID );
	if ( ! $permalink ) {
		$permalink = '';
	}

	// TODO Use WordPress translations as a basis for adding these to existing translation files.
	// Preview post link.
	$preview_post_link_html = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>',
		esc_url( $preview_url ),
		__( 'Preview testimonial', 'strong-testimonials' )
	);

	// View post link.
	$view_post_link_html = sprintf( ' <a href="%1$s">%2$s</a>',
		esc_url( $permalink ),
		__( 'View testimonial', 'strong-testimonials' )
	);

	// Scheduled post preview link.
	$scheduled_post_link_html = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>',
		esc_url( $permalink ),
		__( 'Preview testimonial', 'strong-testimonials' )
	);

	/* translators: Publish box date format, see https://secure.php.net/date */
	$scheduled_date = date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_date ) );

	$messages['wpm-testimonial'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Testimonial updated.', 'strong-testimonials' ) . $view_post_link_html,
		2 => __( 'Custom field updated.', 'strong-testimonials' ),
		3 => __( 'Custom field deleted.', 'strong-testimonials' ),
		4 => __( 'Testimonial updated.', 'strong-testimonials' ),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __( 'Testimonial restored to revision from %s.', 'strong-testimonials' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __( 'Testimonial published.', 'strong-testimonials' ) . $view_post_link_html,
		7 => __( 'Testimonial saved.', 'strong-testimonials' ),
		8 => __( 'Testimonial submitted.', 'strong-testimonials' ) . $preview_post_link_html,
		9 => sprintf( __( 'Testimonial scheduled for: %s.', 'strong-testimonials' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_post_link_html,
		10 => __( 'Testimonial draft updated.', 'strong-testimonials' ) . $preview_post_link_html,
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'wpmtst_updated_messages' );