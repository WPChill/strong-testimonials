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
