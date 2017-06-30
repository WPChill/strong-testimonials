<?php
/**
 * Mail class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Strong_Mail' ) ) :

class Strong_Mail {

	public function __construct() {

		add_action( 'wp_loaded', array( $this, 'process_mail_queue' ), 20 );

	}

	/**
	 * Process mail queue
	 *
	 * @since 2.8.0
	 */
	public function process_mail_queue() {
		$current_queue = get_transient( 'wpmtst_mail_queue' );
		if ( ! $current_queue )
			return;

		add_action( 'wp_mail_failed', array( $this, 'catch_mail_failed' ) );
		foreach ( $current_queue as $email ) {
			$this->send_mail( $email );
		}
		remove_action( 'wp_mail_failed', array( $this, 'catch_mail_failed' ) );

		delete_transient( 'wpmtst_mail_queue' );
	}


	public function catch_mail_failed( $error ) {
		WPMST()->debug->log( $error );
	}


	public function send_mail( $email ) {
		$mail_sent = wp_mail( $email['to'], $email['subject'], $email['message'], $email['headers'] );

		// Log email action
		//TODO Deeper integration with Mandrill
		$options = get_option( 'wpmtst_options' );
		if ( isset( $options['email_log_level'] ) && $options['email_log_level'] ) {

			// for both levels, log failure only
			// for level 2, log both success and failure
			if ( ! $mail_sent || 2 == $options['email_log_level'] ) {
				$log_entry = array(
					'response' => $mail_sent ? 'mail successful' : 'mail failed',
					'to'       => $email['to'],
					'subject'  => $email['subject'],
					'message'  => $email['message'],
					'headers'  => $email['headers'],
				);
				WPMST()->debug->log( $log_entry, 'mail', __FUNCTION__ );
			}

		}

	}

	/**
	 * Enqueue mail.
	 *
	 * @since 2.8.0
	 * @param $email
	 */
	public function enqueue_mail( $email ) {
		$current_queue = get_transient( 'wpmtst_mail_queue' );
		if ( $current_queue ) {
			delete_transient( 'wpmtst_mail_queue' );
		} else {
			$current_queue = array();
		}

		$current_queue[] = $email;
		set_transient( 'wpmtst_mail_queue', $current_queue, DAY_IN_SECONDS );
	}

}

endif;
