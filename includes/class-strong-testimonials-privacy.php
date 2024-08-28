<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Strong_Testimonials_Privacy
 *
 * @since 2.41.0
 */
class Strong_Testimonials_Privacy {

	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * @since 2.41.0
	 */
	public function add_hooks() {
		add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'register_exporter' ) );
		add_filter( 'wp_privacy_personal_data_erasers', array( $this, 'register_eraser' ) );
		add_action( 'admin_init', array( $this, 'wpmtst_register_privacy_policy_template' ) );
	}

	/**
	 * @since 2.41.0
	 * @return string
	 */
	public function get_friendly_name() {
		return esc_html__( 'Strong Testimonials Plugin', 'strong-testimonials' );
	}

	/**
	 * @param     $email_address
	 * @param int $page
	 *
	 * @since 2.41.0
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
			$group_label = esc_html__( 'Testimonials', 'strong-testimonials' );

			$data = array();

			foreach ( $post_meta as $key => $value ) {
				$found = array_search( $email_address, $value, true );
				if ( false !== $found ) {
					$data = array(
						array(
							'name'  => 'Email',
							'value' => $value[ $found ],
						),
						array(
							'name'  => 'Client Name',
							'value' => $post_meta['client_name'][0],
						),
						array(
							'name'  => 'Company Name',
							'value' => $post_meta['company_name'][0],
						),
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
	 * @since 2.41.0
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
				if ( in_array( $email_address, $value, true ) ) {
					//delete_post_meta( $testimonial->ID, $key, $email_address );
					wp_delete_post( $testimonial->ID );
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
	 * @since 2.41.0
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
	 * @since 2.41.0
	 * @return array
	 */
	public function register_eraser( $erasers ) {
		$erasers['strong-testimonials'] = array(
			'eraser_friendly_name' => $this->get_friendly_name(),
			'callback'             => array( $this, 'eraser' ),
		);

		return $erasers;
	}

	public function wpmtst_register_privacy_policy_template() {

		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
				return;
		}

		$content = wp_kses_post(
			apply_filters(
				'wpmtst_privacy_policy_content',
				__(
					'
            We collect certain pieces of information about you when you fill one of our testimonial forms. This includes your full name, e-mail address, photo, company name, and website.           
            By agreeing to these terms, you also allow us to::
            - Send a confirmation e-mail, to let you know your testimonial was received and approved;
            - Send important account/ product/ service information;
            - Set up and administer your account, provide technical/customer support, and verify your identity.',
					'strong-testimonials'
				)
			)
		);

		wp_add_privacy_policy_content( 'Strong Testimonial Privacy Policy', wpautop( $content ) );
	}
}
