<?php
/**
 * Class Strong_Testimonials_Admin_Ajax
 */
class Strong_Testimonials_Admin_Ajax {

	/**
	 * Strong_Testimonials_Admin_Ajax constructor.
	 */
	public function __construct(){}

	/**
	 * Initialize.
	 */
	public static function init() {
		self::add_actions();
	}

	/**
	 * Add actions and filters.
	 */
	public static function add_actions() {
		add_action( 'wp_ajax_wpmtst_edit_rating', array( __CLASS__, 'edit_rating' ) );
		add_action( 'wp_ajax_wpmtst_add_recipient', array( __CLASS__, 'add_recipient' ) );
		add_action( 'wp_ajax_wpmtst_get_background_preset_colors', array( __CLASS__, 'get_background_preset_colors' ) );
	}

	/**
	 * Ajax handler to edit a rating.
	 *
	 * @since 2.12.0
	 */
	public static function edit_rating() {
		$message = '';
		$post_id = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$rating  = isset( $_POST['rating'] ) ? (int) $_POST['rating'] : 0;
		$name    = isset( $_POST['field_name'] ) ? $_POST['field_name'] : 'rating';

		check_ajax_referer( 'editrating', 'editratingnonce' );

		if ( $post_id ) {
			if ( $rating ) {
				update_post_meta( $post_id, $name, $rating );
			} else {
				delete_post_meta( $post_id, $name );
			}
			$message = 'New rating saved';
		}

		$display  = wpmtst_star_rating_display( $rating, 'in-metabox', false );
		$response = array( 'display' => $display, 'message' => $message );
		echo json_encode( $response );
		die();
	}

	/**
	 * [Add Recipient] Ajax receiver
	 */
	public static function add_recipient() {
		$key          = $_REQUEST['key'];
		$form_options = get_option( 'wpmtst_form_options' );
		$recipient    = $form_options['default_recipient'];
		include WPMTST_ADMIN . 'partials/settings/recipient.php';
		die();
	}

	/**
	 * Get background color presets in View editor.
	 */
	public static function get_background_preset_colors() {
		$preset = wpmtst_get_background_presets( $_REQUEST['key'] );
		echo json_encode( $preset );
		die();
	}

}

Strong_Testimonials_Admin_Ajax::init();
