<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Strong_Testimonials_Privacy
 *
 * @since 2.31.4
 */
class Strong_Testimonials_Privacy {

	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * @since 2.31.4
	 */
	public function add_hooks() {
		add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'register_exporter' ) );
		add_filter( 'wp_privacy_personal_data_erasers', array( $this, 'register_eraser' ) );
	}

	/**
	 * @since 2.31.4
	 * @return string
	 */
	public function get_friendly_name() {
		return __( 'Strong Testimonials Plugin', 'strong-testimonials' );
	}

	/**
	 * @param     $email_address
	 * @param int $page
	 *
	 * @since 2.31.4
	 * @return array
	 */
	public function exporter( $email_address, $page = 1 ) {
		$number = 100; // Limit us to avoid timing out
		$page   = (int) $page;

		$export_items = array();

		$args = array(
			'post_type'        => 'wpm-testimonial',
			'post_status'      => 'publish',
			'posts_per_page'   => $number,
			'offset'           => $number * ( $page - 1 ),
			'suppress_filters' => true,
		);

		$testimonials = get_posts( $args );

		foreach ( (array) $testimonials as $testimonial ) {
			$post_meta = get_post_meta( $testimonial->ID );

			$item_id     = "testimonial-{$testimonial->ID}";
			$group_id    = 'testimonials';
			$group_label = __( 'Testimonials', 'strong-testimonials' );

			$data = array();

			foreach ( $post_meta as $key => $value ) {
				$found = array_search( $email_address, $value );
				if ( false !== $found ) {
					$data[] = array(
						'name'  => $key,
						'value' => $value[ $found ],
					);
				}
			}

			if ( $data ) {
				$export_items[] = array(
					'group_id'    => $group_id,
					'group_label' => $group_label,
					'item_id'     => $item_id,
					'data'        => $data,
				);
			}
		}

		// Tell core if we have more comments to work on still
		$done = count( $testimonials ) < $number;

		return array(
			'data' => $export_items,
			'done' => $done,
		);
	}

	/**
	 * @param     $email_address
	 * @param int $page
	 *
	 * @since 2.31.4
	 * @return array
	 */
	public function eraser( $email_address, $page = 1 ) {
		$number = 100; // Limit us to avoid timing out
		$page   = (int) $page;

		$items_removed = false;

		$args = array(
			'post_type'        => 'wpm-testimonial',
			'post_status'      => 'publish',
			'posts_per_page'   => $number,
			'offset'           => $number * ( $page - 1 ),
			'suppress_filters' => true,
		);

		$testimonials = get_posts( $args );

		foreach ( (array) $testimonials as $testimonial ) {
			$post_meta = get_post_meta( $testimonial->ID );

			foreach ( $post_meta as $key => $value ) {
				if ( in_array( $email_address, $value ) ) {
					delete_post_meta( $testimonial->ID, $key, $email_address );
					$items_removed = true;
				}
			}
		}

		// Tell core if we have more comments to work on still
		$done = count( $testimonials ) < $number;

		return array(
			'items_removed'  => $items_removed,
			'items_retained' => false, // always false in this example
			'messages'       => array(), // no messages in this example
			'done'           => $done,
		);
	}

	/**
	 * @param array $exporters
	 *
	 * @since 2.31.4
	 * @return array
	 */
	public function register_exporter( $exporters ) {
		$exporters['strong-testimonials'] = array(
			'exporter_friendly_name' => $this->get_friendly_name(),
			'callback'               => array( $this, 'exporter' ),
		);

		return $exporters;
	}

	/**
	 * @param array $erasers
	 * @since 2.31.4
	 * @return array
	 */
	public function register_eraser( $erasers ) {
		$erasers['strong-testimonials'] = array(
			'eraser_friendly_name' => $this->get_friendly_name(),
			'callback'             => array( $this, 'eraser' ),
		);

		return $erasers;
	}

}
