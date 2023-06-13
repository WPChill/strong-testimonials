<?php

/**
 * Class Strong_Testimonials_Post_Editor
 *
 * @since 2.28.0
 */
class Strong_Testimonials_Post_Editor {

	/**
	 * Strong_Testimonials_Post_Editor constructor.
	 */
	public function __construct() {
	}

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
		add_action( 'add_meta_boxes_wpm-testimonial', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_wpm-testimonial', array( __CLASS__, 'save_details' ) );
		add_action( 'wp_ajax_wpmtst_edit_rating', array( __CLASS__, 'edit_rating' ) );
		add_filter( 'wp_insert_post_data', array( __CLASS__, 'prevent_shortcode' ), 10, 2 );
	}

	/**
	 * Add meta box to the post editor screen.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'details',
			esc_html_x( 'Client Details', 'post editor', 'strong-testimonials' ),
			array( __CLASS__, 'meta_options' ),
			'wpm-testimonial',
			'normal',
			'high'
		);
	}

	/**
	 * Add custom fields to the testimonial editor.
	 */
	public static function meta_options() {
		global $post, $pagenow;
		$post   = wpmtst_get_post( $post );
		$fields = wpmtst_get_custom_fields();
		$is_new = ( 'post-new.php' == $pagenow );
                wp_nonce_field ( plugin_basename(__FILE__), 'wpmtst_metabox_nonce');
		?>
		<?php do_action( 'wpmtst_before_client_fields_table' ); ?>
        <table class="options">
            <tr>
                <td colspan="2">
                    <p><?php esc_html_x( 'To add a photo or logo, use the Featured Image option.', 'post editor', 'strong-testimonials' ); ?></p>
                </td>
            </tr>
			<?php
			do_action( 'wpmtst_before_client_fields' );
			foreach ( $fields as $key => $field ) {
				// TODO Use field property to bypass instead
				// short-circuit
				if ( 'category' == strtok( $field['input_type'], '-' ) ) {
					continue;
				}
				?>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( $field['name'] ); ?>">
							<?php echo wp_kses_post( apply_filters( 'wpmtst_l10n', $field['label'], 'strong-testimonials-form-fields', $field['name'] . ' : label' ) ); ?>
                        </label>
                    </th>
                    <td>
                        <div class="<?php echo esc_attr( $field['input_type'] ); ?>">
							<?php self::meta_option( $field, $post, $is_new ); ?>
                        </div>
                    </td>
                </tr>
				<?php
			}
			do_action( 'wpmtst_after_client_fields' );
			?>
        </table>
		<?php
		do_action( 'wpmtst_after_client_fields_table' );
	}

	/**
	 * Input type for a single custom field.
	 *
	 * @since 2.23.0
	 *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	public static function meta_option( $field, $post, $is_new ) {
		// Check for callback first.
		if ( isset( $field['action_input'] ) && $field['action_input'] ) {
			self::meta_option__action( $field, $post, $is_new );
		}
		// Check field type.
		else {
			switch ( $field['input_type'] ) {
				case 'rating' :
					self::meta_option__rating( $field, $post, $is_new );
					break;
				case 'url' :
					self::meta_option__url( $field, $post, $is_new );
					break;
				case 'checkbox' :
					self::meta_option__checkbox( $field, $post, $is_new );
					break;
				case 'shortcode' :
					self::meta_option__shortcode( $field, $post, $is_new );
					break;
				case 'textarea' :
					self::meta_option__textarea( $field, $post, $is_new );
					break;
				default :
					self::meta_option__text( $field, $post, $is_new );
			}
		}
	}

	/**
     * Custom action callback.
     *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	private static function meta_option__action( $field, $post, $is_new ) {
		if ( isset( $field['action_input'] ) && $field['action_input'] ) {
			do_action( $field['action_input'], $field, $post->{$field['name']} );
		}
	}

	/**
	 * Text input.
	 *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	private static function meta_option__text( $field, $post, $is_new ) {
		printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s">', esc_attr( $field['input_type'] ), esc_attr( $field['name'] ), esc_attr( $post->{$field['name']} ) );
	}

	/**
	 * Textarea.
	 *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	private static function meta_option__textarea( $field, $post, $is_new ) {
		printf(
			'<textarea id="%1$s" name="custom[%1$s]" class="custom-input">%2$s</textarea>',
			esc_attr( $field['name'] ),
			wp_kses_post( $post->{$field['name']} )
		);
	}

	/**
	 * URL input.
	 *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	private static function meta_option__url( $field, $post, $is_new ) {
		?>
        <div class="input-url">
			<?php printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" size="">', esc_attr( $field['input_type'] ), esc_html( $field['name'] ), esc_attr( $post->{$field['name']} ) ); ?>
        </div>
        <div class="input-links">
            <div class="input-nofollow">
                <label for="custom_nofollow"><code>rel="nofollow"</code></label>
                <select id="custom_nofollow" name="custom[nofollow]">
                    <option value="default" <?php selected( $post->nofollow, 'default' ); ?>><?php esc_html_e( 'default', 'strong-testimonials' ); ?></option>
                    <option value="yes" <?php selected( $post->nofollow, 'yes' ); ?>><?php esc_html_e( 'yes', 'strong-testimonials' ); ?></option>
                    <option value="no" <?php selected( $post->nofollow, 'no' ); ?>><?php esc_html_e( 'no', 'strong-testimonials' ); ?></option>
                </select>
            </div>
            <div class="input-noopener">
                <label for="custom_noopener"><code>rel="noopener"</code></label>
                <select id="custom_noopener" name="custom[noopener]">
                    <option value="default" <?php selected( $post->noopener, 'default' ); ?>><?php esc_html_e( 'default', 'strong-testimonials' ); ?></option>
                    <option value="yes" <?php selected( $post->noopener, 'yes' ); ?>><?php esc_html_e( 'yes', 'strong-testimonials' ); ?></option>
                    <option value="no" <?php selected( $post->noopener, 'no' ); ?>><?php esc_html_e( 'no', 'strong-testimonials' ); ?></option>
                </select>
            </div>
            <div class="input-noreferrer">
                <label for="custom_noreferrer"><code>rel="noreferrer"</code></label>
                <select id="custom_noopener" name="custom[noreferrer]">
                    <option value="default" <?php selected( $post->noreferrer, 'default' ); ?>><?php esc_html_e( 'default', 'strong-testimonials' ); ?></option>
                    <option value="yes" <?php selected( $post->noreferrer, 'yes' ); ?>><?php esc_html_e( 'yes', 'strong-testimonials' ); ?></option>
                    <option value="no" <?php selected( $post->noreferrer, 'no' ); ?>><?php esc_html_e( 'no', 'strong-testimonials' ); ?></option>
                </select>
            </div>
		<?php
	}

	/**
	 * Checkbox input.
	 *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	private static function meta_option__checkbox( $field, $post, $is_new ) {
		printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" %4$s>', esc_attr( $field['input_type'] ), esc_attr( $field['name'] ), 1, checked( $post->{$field['name']}, 1, false ) );
	}

	/**
	 * Rating input.
	 *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	private static function meta_option__rating( $field, $post, $is_new ) {
		$rating = get_post_meta( $post->ID, $field['name'], true );
		if ( ! $rating || $is_new ) {
			$rating = 0;
		}
		?>
        <div class="edit-rating-box hide-if-no-js" data-field="<?php echo esc_attr( $field['name'] ); ?>">

			<?php wp_nonce_field( 'editrating', "edit-{$field['name']}-nonce", false ); ?>
            <input type="hidden" class="current-rating" value="<?php echo esc_attr( $rating ); ?>">

            <!-- form -->
            <div class="rating-form" style="<?php echo ( $is_new ) ? 'display: inline-block;' : 'display: none;'; ?>">
                    <span class="inner">
                        <?php wpmtst_star_rating_form( $field, $rating, 'in-metabox', true, 'custom' ); ?>
                    </span>
				<?php if ( ! $is_new ) : ?>
					<span class="edit-rating-buttons-2">
							<button type="button" class="zero button-link"><?php esc_html_e( 'Zero', 'strong-testimonials' ); ?></button>&nbsp;
							<button type="button" class="save button button-small"><?php esc_html_e( 'OK', 'strong-testimonials' ); ?></button>&nbsp;
							<button type="button" class="cancel button-link"><?php esc_html_e( 'Cancel', 'strong-testimonials' ); ?></button>
						</span>
				<?php endif; ?>
            </div>

            <!-- display -->
            <div class="rating-display" style="<?php echo $is_new ? 'display: none;' : 'display: inline-block;'; ?>">
                    <span class="inner">
                        <?php wpmtst_star_rating_display( $rating, 'in-metabox' ); ?>
                    </span>

				<?php if ( ! $is_new ) : ?>
					<span class="edit-rating-buttons-1">
						<button type="button" id="" class="edit-rating button button-small hide-if-no-js" aria-label="Edit rating"><?php esc_html_e( 'Edit', 'strong-testimonials' ); ?></button>
					</span>
				<?php endif; ?>
            </div>

            <span class="edit-rating-success"></span>

        </div>
		<?php
	}

	/**
	 * Shortcode for custom input.
	 *
	 * @param $field
	 * @param $post
	 * @param $is_new
	 */
	public static function meta_option__shortcode( $field, $post, $is_new ) {
	    $shortcode = str_replace( array( '[', ']' ), array( '', '' ), $field['shortcode_on_display'] );
	    if ( shortcode_exists( $shortcode ) ) {
		    echo do_shortcode( $field['shortcode_on_display'] );
	    } else {
	        echo '<div class="custom-input not-found">' . sprintf( esc_html__( 'shortcode %s not found', 'strong-testimonials' ), '<code>' . esc_html($field['shortcode_on_display']) . '</code>' ) . '</div>';
	    }
	}

	/**
	 * Save custom fields.
	 *
	 * @since 2.23.2 Delete meta record when rating is zero to allow default display value.
	 */
	public static function save_details() {
		if ( ! isset( $_POST['custom'] ) || ! isset( $_POST['post_ID'] ) || !wp_verify_nonce( $_POST['wpmtst_metabox_nonce'], plugin_basename(__FILE__) ) ) {
			return;
		}

		$post_id = absint( $_POST['post_ID'] );
		$custom =  $_POST['custom']; // phpcs:ignore sanitization is done underneath ( Data Sanitizationva )

		$custom_fields = wpmtst_get_custom_fields();

		// Construct array of checkbox empty values because blank checkboxes are not POSTed.
		$checkboxes = array();
		foreach ( $custom_fields as $key => $field ) {
			if ( 'checkbox' == $field['input_type'] ) {
				$checkboxes[ $key ] = 0;
			}
		}
		if ( $checkboxes ) {
			$custom = array_merge( $checkboxes, $custom );
		}


		// Determine whether to update or delete.
		// Similar to wpmtst_ajax_edit_rating() in admin-ajax.php.
		$custom_fields['nofollow']['input_type'] = '';
		$custom_fields['noopener']['input_type'] = '';
                $custom_fields['noreferrer']['input_type'] = '';
                
		foreach ( $custom as $key => $value ) {
		    $action = 'update';
		    $sanitized_value = '';

		    if ( isset( $custom_fields[ $key ] ) ) {
				if ( 'rating' == $custom_fields[ $key ]['input_type'] && !$value ) {
					$action = 'delete';
				}
			}

			// Data Sanitizationva
			if ( isset($custom_fields[ $key ]['input_type']) && 'text' == $custom_fields[ $key ]['input_type'] ) {
				$sanitized_value = wp_filter_post_kses( $value );
			}elseif ( isset($custom_fields[ $key ]['input_type']) && 'email' == $custom_fields[ $key ]['input_type'] ) {
				$sanitized_value = sanitize_email( $value );
			}elseif ( isset($custom_fields[ $key ]['input_type']) && 'url' == $custom_fields[ $key ]['input_type'] ) {
				$sanitized_value = esc_url_raw( $value );
			}else{
				$sanitized_value = sanitize_text_field( $value );
			}

			if ( 'update' == $action ) {
				// empty values replace existing values
				update_post_meta( $post_id, $key, $sanitized_value );
			}
			else {
				// delete value; e.g. zero rating
				delete_post_meta( $post_id, $key );
			}
		}
	}

	/**
	 * Prevent use of this plugin's shortcode in a testimonial.
     *
     * @since 2.32.2
	 * @param $data
	 * @param $postarr
	 *
	 * @return mixed
	 */
	public static function prevent_shortcode( $data, $postarr ) {
	    if ( 'wpm-testimonial' == $data['post_type'] ) {
            $data['post_content'] = preg_replace( "/\[testimonial_view (.*)\]/", '', $data['post_content'] );
	    }

	    return $data;
	}

	/**
	 * Ajax handler to edit a rating.
	 *
	 * @since 2.12.0
	 */
	public static function edit_rating() {

		$message = '';
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$rating  = isset( $_POST['rating'] ) ? absint( $_POST['rating'] ) : 0;
		$name    = isset( $_POST['field_name'] ) ? sanitize_text_field( wp_unslash( $_POST['field_name'] ) ) : 'rating';

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
		wp_die();
	}

}

Strong_Testimonials_Post_Editor::init();
