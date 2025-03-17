<?php

class Strong_Testimonials_Review {

	private $value;
	private $messages;
	private $link = 'https://wordpress.org/support/plugin/%s/reviews/#new-post';
	private $slug = 'strong-testimonials';

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		if ( ! is_admin() ) {
			return;
		}

		$this->messages = array(
			'notice'  => esc_html__( "Hi there! Stoked to see you're using Strong Testimonials for a few days now - hope you like it! And if you do, please consider rating it. It would mean the world to us.  Keep on rocking!", 'strong-testimonials' ),
			'rate'    => esc_html__( 'Rate the plugin', 'strong-testimonials' ),
			'rated'   => esc_html__( 'Remind me later', 'strong-testimonials' ),
			'no_rate' => __( 'Don\'t show again', 'strong-testimonials' ),
		);

		if ( isset( $args['messages'] ) ) {
			$this->messages = wp_parse_args( $args['messages'], $this->messages );
		}

		$this->value = $this->value();

		if ( $this->check() ) {
			add_action( 'admin_notices', array( $this, 'five_star_wp_rate_notice' ) );
			add_action( 'wp_ajax_strong-testimonials_review', array( $this, 'ajax' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'ajax_script' ) );
		}

		add_filter( 'st_uninstall_db_options', array( $this, 'uninstall_options' ) );
	}

	private function check() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return( time() > $this->value );
	}

	private function value() {

		$value = get_option( 'strong-testimonials-rate-time', false );
		if ( $value ) {
			return $value;
		}

		$value = time() + DAY_IN_SECONDS;
		update_option( 'strong-testimonials-rate-time', $value );

		return $value;
	}

	public function five_star_wp_rate_notice() {

		$url            = sprintf( $this->link, $this->slug );
		$url            = apply_filters( 'wpmtst_review_link', $url );
		$this->messages = apply_filters( 'wpmtst_review_messages', $this->messages );

		$notice = array(
			'title'       => 'Rate Us',
			'message'     => sprintf( esc_html( $this->messages['notice'] ), esc_html( $this->value ) ),
			'status'      => 'success',
			'dismissible' => false,
			'timestamp'   => false,
			'actions'     => array(
				array(
					'label'    => esc_html( $this->messages['rated'] ),
					'id'       => 'strong-testimonials-later',
					'class'    => 'strong-testimonials-review-button',
					'dismiss'  => true,
					'callback' => 'handleStButtonClick',
				),
				array(
					'label'    => esc_html( $this->messages['no_rate'] ),
					'id'       => 'strong-testimonials-no-rate',
					'class'    => 'strong-testimonials-review-button',
					'dismiss'  => true,
					'callback' => 'handleStButtonClick',
				),
				array(
					'label'    => esc_html( $this->messages['rate'] ),
					'id'       => 'strong-testimonials-rate',
					'url'      => esc_url( $url ),
					'class'    => 'strong-testimonials-review-button',
					'variant'  => 'primary',
					'target'   => '_BLANK',
					'dismiss'  => true,
					'callback' => 'handleStButtonClick',
				),
			),
			'source'      => array(
				'slug' => 'strong-testimonials',
				'name' => 'Strong Testimonials',
			),
		);

		WPChill_Notifications::add_notification( 'wpmtst-five-star-rate', $notice );
	}

	public function ajax() {

		check_ajax_referer( 'strong-testimonials-review', 'security' );

		if ( ! isset( $_POST['check'] ) ) {
			wp_die( 'ok' );
		}

		$time = get_option( 'strong-testimonials-rate-time' );

		if ( 'strong-testimonials-rate' === $_POST['check'] || 'strong-testimonials-no-rate' === $_POST['check'] ) {
			$time = time() + YEAR_IN_SECONDS * 5;
		} else {
			$time = time() + WEEK_IN_SECONDS;
		}

		update_option( 'strong-testimonials-rate-time', $time );
		wp_die( 'ok' );
	}

	public function enqueue() {
		wp_enqueue_script( 'jquery' );
	}

	public function ajax_script() {

		$ajax_nonce = wp_create_nonce( 'strong-testimonials-review' );

		?>
		<script type="text/javascript">

		function handleStButtonClick( element ) {
			console.error('clicky');
			var data = {
				action: 'strong-testimonials_review',
				security: '<?php echo esc_js( $ajax_nonce ); ?>',
				check: element.url ? 'strong-testimonials-rate' : element.id
			};

			jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', data );
		}
		</script>
		<?php
	}

	/**
	 * @param $options
	 *
	 * @return mixed
	 *
	 * @since 2.51.6
	 */
	public function uninstall_options( $options ) {

		$options[] = 'strong-testimonials-rate-time';

		return $options;
	}
}

new Strong_Testimonials_Review();