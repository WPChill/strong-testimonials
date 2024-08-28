<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Strong_Testimonials_Beaver {

	/**
	 * Strong_Testimonials_Beaver constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'include_beaver_block' ) );
	}

	/**
	 * Include ST Beaver Block
	 */
	public function include_beaver_block() {
		if ( class_exists( 'FLBuilder' ) ) {
			require_once WPMTST_DIR . 'includes/strong-testimonials-beaver-block/class-strong-testimonials-beaver-block.php';
		}
	}
}

$strong_beaver = new Strong_Testimonials_Beaver();
