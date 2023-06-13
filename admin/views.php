<?php
/**
 * View admin functions.
 *
 * @since 1.21.0
 */


/**
 * View list page.
 *
 * @since 1.21.0
 */
function wpmtst_views_admin() {
	if ( ! current_user_can( 'strong_testimonials_views' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'strong-testimonials' ) );
	}

	$tags = array(
		'a' => array(
			'href'   => array(),
			'target' => array(),
		),
	);

	?>
	<div class="wrap">

		<?php
		if ( isset( $_REQUEST['result'] ) ) {

			$result = filter_input( INPUT_GET, 'result', FILTER_SANITIZE_STRING );

			$result_messages = array(
				'cancelled'         => esc_html__( 'Changes cancelled.', 'strong-testimonials' ),
				'defaults-restored' => esc_html__( 'Defaults restored.', 'strong-testimonials' ),
				'view-saved'        => esc_html__( 'View saved.', 'strong-testimonials' ),
				'view-deleted'      => esc_html__( 'View deleted.', 'strong-testimonials' ),
			);

			if ( in_array( $result, array_keys( $result_messages ) ) ) {
				printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $result_messages[ $result ] ) );
			}

		}

		if ( isset( $_REQUEST['error'] ) ) {

			echo '<h1>' . esc_html__( 'Edit View', 'strong-testimonials' ) . '</h1>';

			wp_die( sprintf( '<div class="notice notice-error"><p>%s</p></div>', esc_html__( 'An error occurred.', 'strong-testimonials' ) ) );

		}

		if ( isset( $_REQUEST['action'] ) ) {
						global $view;
                        $view = Strong_Testimonials_Helper::get_view();
                        $sections = new Strong_Testimonials_Helper();
                        $sections->render_form();

		} else {

                        /**
                         * View list
                         */
                    
			// Fetch views after heading and before intro in case we need to display any database errors.
			$views = wpmtst_get_views();
			$views_table = new Strong_Views_List_Table();
                        
                        // Get links for filtering
                        $filters = $views_table->prepare_filters(wpmtst_unserialize_views( $views ));
			?>
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'Views', 'strong-testimonials' ); ?>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views&action=add' ) ); ?>" class="add-new-h2"><?php esc_html_e( 'Add New', 'strong-testimonials' ); ?></a>
				<a href="#tab-panel-wpmtst-help-views" class="add-new-h2 open-help-tab"><?php esc_html_e( 'Help', 'strong-testimonials' ); ?></a>
			</h1>
                        <hr class="wp-header-end">
                        <h2 class="screen-reader-text"><?php esc_html_e( 'Filter view list', 'strong-testimonials' ); ?></h2>
                        <ul class="subsubsub">
                            <li class="all"><a <?php echo (!isset($_GET['mode']) || $_GET['mode'] == 'all' ? 'class="current"' : '') ?> href="<?php echo esc_url( add_query_arg( array('post_type' => 'wpm-testimonial', 'page' => 'testimonial-views', 'mode' => 'all' ), admin_url('edit.php') ) ) ?>"><?php esc_html_e( 'All', 'strong-testimonials' ); ?><?php printf( wp_kses_post( __( ' <span class="count">(%s)</span>', 'strong-testimonials' ) ), count($views) ); ?></a> |</li>
                            <?php foreach ($filters as $mode => $items): ?>
                            <li class="<?php echo esc_attr( $mode ) ?>"><a <?php echo (isset($_GET['mode']) && $_GET['mode'] == $mode ? 'class="current"' : '') ?> href="<?php echo esc_url( add_query_arg( array('post_type' => 'wpm-testimonial', 'page' => 'testimonial-views', 'mode' => $mode ), admin_url('edit.php') ) ) ?>"><?php echo esc_html( ucfirst($mode) ) ?><?php printf( wp_kses_post( __( ' <span class="count">(%s)</span>', 'strong-testimonials' )  ), count($items) ); ?></a> |</li>
                            <?php endforeach; ?>
                        </ul>
			<?php
			// Add button to clear sort value.
			if ( isset( $_GET['orderby'] ) ) {
                ?>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-bottom: 4px;">
                    <input type="hidden" name="action" value="clear-view-sort">
                    <input type="submit" value="clear sort" class="button">
                </form>
                <?php
			}

            // Display the table
            $views_table->prepare_list(wpmtst_unserialize_views( $views ) );
			$views_table->display();

		}
		?>
	</div><!-- .wrap -->
	<?php
}


/**
 * Process form POST after editing.
 *
 * Thanks http://stackoverflow.com/a/20003981/51600
 *
 * @since 1.21.0
 */
function wpmtst_view_edit_form() {

	$goback = wp_get_referer();

	if ( empty( $_POST ) || ! check_admin_referer( 'view_form_submit', 'view_form_nonce' ) ) {
		$goback = add_query_arg( 'result', 'error', $goback );
		wp_redirect( $goback );
		exit;
	}

	$view_id    = isset( $_POST['view']['id'] ) ? absint( filter_var( wp_unslash( $_POST['view']['id'] ), FILTER_SANITIZE_NUMBER_INT ) ) : 0;

	$view_name  = isset( $_POST['view']['name'] ) ? wpmtst_validate_view_name( sanitize_text_field( wp_unslash( $_POST['view']['name'] ) ), $view_id ) : 'new';

	if ( isset( $_POST['reset'] ) ) {

		// Undo changes
		$goback = add_query_arg( 'result', 'cancelled', $goback );

	} elseif ( isset( $_POST['restore-defaults'] ) ) {

		// Restore defaults
		$default_view = wpmtst_get_view_default();

		$view = array(
			'id'   => $view_id,
			'name' => $view_name,
			'data' => $default_view
		);
		$success = wpmtst_save_view( $view ); // num_rows

		if ( $success ) {
			$goback = add_query_arg( 'result', 'defaults-restored', $goback );
		} else {
			$goback = add_query_arg( 'result', 'error', $goback );
		}

	} elseif ( isset( $_POST['submit-form'] ) ) {

		// Sanitize & validate
		$view = array(
			'id'   => $view_id,
			'name' => $view_name,
			'data' => isset( $_POST['view']['data'] ) ? wpmtst_sanitize_view( stripslashes_deep( $_POST['view']['data'] ) ) : array(), // phpcs:ignore sanitized by wpmtst_sanitize_view
		);
		$success = wpmtst_save_view( $view ); // num_rows

		if ( $success ) {
			$goback = add_query_arg( 'result', 'view-saved', $goback );
		} else {
			$goback = add_query_arg( 'result', 'error', $goback );
		}

	} else {

		$goback = add_query_arg( 'result', 'error', $goback );

	}

	wp_redirect( $goback );
	exit;

}
add_action( 'admin_post_view_edit_form', 'wpmtst_view_edit_form' );


/**
 * Process form POST after adding.
 *
 * @since 1.21.0
 */
function wpmtst_view_add_form() {

	$goback = wp_get_referer();

	if ( empty( $_POST ) || ! check_admin_referer( 'view_form_submit', 'view_form_nonce' ) ) {
		$goback = add_query_arg( 'result', 'error', $goback );
		wp_redirect( $goback );
		exit;
	}

	$view_id   = 0;
	$view_name = isset( $_POST['view']['name'] ) ? wpmtst_validate_view_name( sanitize_text_field( wp_unslash( $_POST['view']['name'] ) ), $view_id ) : "Testimonial View $view_id";

	if ( isset( $_POST['restore-defaults'] ) ) {

		// Restore defaults
		$default_view = wpmtst_get_view_default();

		$view = array(
			'id'   => $view_id,
			'name' => $view_name,
			'data' => $default_view,
		);
		$success = wpmtst_save_view( $view, 'add' ); // view ID

		$query_arg = 'defaults-restored';

	} elseif ( isset( $_POST['submit-form'] ) ) {

		// Sanitize & validate
		$view = array(
			'id'   => 0,
			'name' => $view_name,
			'data' => isset( $_POST['view']['data'] ) ? wpmtst_sanitize_view( stripslashes_deep( $_POST['view']['data'] ) ) : array(), // phpcs:ignore sanitized by wpmtst_sanitize_view
		);
		$success = wpmtst_save_view( $view, 'add' ); // view ID

		$query_arg = 'view-saved';

	} else {

		$success = false;
		$query_arg = 'error';

	}

	if ( $success ) {
		$goback = add_query_arg( array( 'action' => 'edit', 'id' => $success, 'result' => $query_arg ), $goback );
	} else {
		$goback = add_query_arg( 'result', 'error', $goback );
	}

	wp_redirect( $goback );
	exit;

}
add_action( 'admin_post_view_add_form', 'wpmtst_view_add_form' );
add_action( 'admin_post_view_duplicate_form', 'wpmtst_view_add_form' );


/**
 * --------------
 * VIEW FUNCTIONS
 * --------------
 */

/**
 * Fetch pages, bypass filters.
 *
 * @since 2.10.0
 *
 * @return array|null|object
 */
function wpmtst_get_pages() {
	global $wpdb;
	$query = "SELECT * FROM $wpdb->posts WHERE post_type = 'page' AND post_status = 'publish' ORDER BY post_title ASC";

	$pages = $wpdb->get_results( $query );

	return $pages;
}


/**
 * Fetch pages, bypass filters.
 *
 * @since 2.10.0
 *
 * @return array|null|object
 */
function wpmtst_get_posts() {
	global $wpdb;
	$query = "SELECT * FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_title ASC";

	$posts = $wpdb->get_results( $query );

	return $posts;
}

/**
 * Filter the custom fields.
 * Until WordPress abandons PHP 5.2
 *
 * @since 2.17.0 Remove [category] from custom because it's included in [optional].
 * @since 2.23.0 Remove checkboxes.
 *
 * @param $field
 *
 * @return bool
 */
function wpmtst_array_filter__custom_fields( $field ) {
	if ( 'category' == strtok( $field['input_type'], '-' ) ) {
		return false;
	}
//	if ( 'checkbox' == $field['input_type'] ) {
//		return false;
//	}

	return true;
}


/**
 * Show a single client field's inputs.
 *
 * @since 1.21.0
 *
 * @param $key
 * @param $field
 * @param bool $adding
 */
function wpmtst_view_field_inputs( $key, $field, $adding = false, $source = 'view[data]') {
	$custom_fields = array_filter( wpmtst_get_custom_fields(), 'wpmtst_array_filter__custom_fields' );

	$builtin_fields = wpmtst_get_builtin_fields();

	$all_fields = array(
		__( 'custom', 'strong-testimonials' )  => $custom_fields,
		__( 'built-in', 'strong-testimonials' ) => $builtin_fields
	);

	$allowed = array( 'custom', 'optional', 'builtin' );

	// TODO Move this to view defaults option.
	$types = apply_filters( 'wpmtst_view_field_inputs_types', array( 
		'text'      => esc_html__( 'text', 'strong-testimonials' ),
		'link'      => esc_html__( 'link with another field', 'strong-testimonials' ),  // the original link type
		'link2'     => esc_html__( 'link (must be URL type)', 'strong-testimonials' ),  // @since 1.24.0
		'date'      => esc_html__( 'date', 'strong-testimonials' ),
		'category'  => esc_html__( 'category', 'strong-testimonials' ),
		'rating'    => esc_html__( 'rating', 'strong-testimonials' ),
		'platform'  => esc_html__( 'platform', 'strong-testimonials' ),
		'shortcode' => esc_html__( 'shortcode', 'strong-testimonials' ),
		'checkbox'  => esc_html__('checkbox', 'strong-testimonials'),
		'video'     => esc_html__('video', 'strong-testimonials'),
		'video_record' => esc_html__('video_record', 'strong-testimonials')
	), $key, $field );

	if ( isset( $custom_fields[ $field['field'] ] ) ) {
            $field_label = $custom_fields[ $field['field'] ]['label'];
	} else {
	    $field_label = ucwords( str_replace( '_', ' ', $field['field'] ) );
	}

	/**
	 * Catch and highlight fields not found in custom fields; i.e. it has been deleted.
	 *
     * @since 2.17.0
	 */
	$all_field_names = array_merge( array_keys( $custom_fields), array( 'post_date', 'submit_date', 'category', 'platform' ) );
	$label_class = '';
	if ( ! $adding && ! in_array( $field['field'], $all_field_names ) ) {
	    // $field_label .= ' < ERROR - not found >';
	    // $label_class = 'error';
		return FALSE;
	}
	?>
	<div id="field-<?php echo esc_attr( $key ); ?>" class="field2">

		<div class="field3" data-key="<?php echo esc_attr( $key ); ?>">

			<div class="link" title="<?php esc_html_e( 'click to open or close', 'strong-testimonials' ); ?>">

				<a href="#" class="field-description <?php echo esc_attr( $label_class ); ?>"><?php echo esc_html( $field_label ); ?></a>

				<div class="controls2 left">
					<span class="handle ui-sortable-handle icon-wrap"
						  title="<?php  esc_html_e( 'drag and drop to reorder', 'strong-testimonials' ); ?>"></span>
					<span class="delete icon-wrap"
						  title="<?php  esc_html_e( 'remove this field', 'strong-testimonials' ); ?>"></span>
				</div>

				<div class="controls2 right">
					<span class="toggle icon-wrap"
						  title="<?php  esc_html_e( 'click to open or close', 'strong-testimonials' ); ?>"></span>
				</div>

			</div>

			<div class="field-properties" style="display: none;">

                <!-- FIELD NAME -->
                <div class="field-property field-name">
                    <label for="client_section_<?php echo esc_attr( $key ); ?>_field">
                        <?php esc_html_e( 'Name', 'strong-testimonials' ); ?>
                    </label>
                    <select id="client_section_<?php echo esc_attr( $key ); ?>_field" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][field]" class="first-field">
                        <option value="">&mdash; <?php esc_html_e( 'select a field', 'strong-testimonials' ); ?> &mdash;</option>

                        <?php foreach ( $all_fields as $group_name => $group ) : ?>
                        <optgroup label="<?php echo esc_attr( $group_name ); ?>">

                        <?php foreach ( $group as $key2 => $field2 ) : ?>
                        <?php if ( in_array( $field2['record_type'], $allowed ) && 'email' != $field2['input_type'] ) : ?>
                        <option value="<?php echo esc_attr( $field2['name'] ); ?>" data-type="<?php echo esc_attr( $field2['input_type'] ); ?>"
                            <?php selected( $field2['name'], $field['field'] ); ?>><?php esc_html_e( $field2['name'] ); ?></option>
                        <?php endif; ?>
                        <?php endforeach; ?>

                        </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FIELD TYPE -->
                <div class="field-property field-type field-dep" <?php if ( $adding || in_array($field['type'], array('checkbox', 'video', 'video_record') ) || $field['field'] == 'video_file' ) echo ' style="display: none;"'; ?>>
                    <label for="client_section_<?php echo esc_attr( $key ); ?>_type">
                        <?php esc_html_e( 'Display Type', 'strong-testimonials' ); ?>
                    </label>
                    <select id="client_section_<?php echo esc_attr( $key ); ?>_type" name="<?php echo esc_attr( $source )?>[client_section][<?php echo esc_attr( $key ); ?>][type]" <?php echo ($field['type'] == 'checkbox' ? 'readonly' : '') ?>>
                        <?php foreach ( $types as $type => $type_label ) : ?>
                        <option value="<?php echo esc_attr( $type ); ?>" <?php selected( $type, $field['type'] ); ?> <?php echo (in_array($type, array('checkbox', 'video', 'video_record')) || $field['field'] == 'video_file' ? 'style="display:none"' : '') ?>><?php esc_html_e( $type_label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FIELD META -->
                
                <div class="field-property-box field-meta field-dep" <?php if ( $adding ) echo ' style="display: none;"'; ?>>
                    <?php
                    if ( 'link' == $field['type'] || 'link2' == $field['type'] ) {
                        wpmtst_view_field_link( $key, $field['field'], $field['type'], $field, false, $source );
                    }

                    if ( 'date' == $field['type'] ) {
                        wpmtst_view_field_date( $key, $field, false, $source );
                    }
                    
                    if ( 'checkbox' == $field['type'] ) {
                        wpmtst_view_field_checkbox( $key, $field, false, $source );
                    }
                    ?>
                </div>

                <!-- FIELD BEFORE -->
                <div class="field-property field-before field-dep" <?php if ( $adding ) echo ' style="display: none;"'; ?>>
                    <label for="client_section_<?php echo esc_attr( $key ); ?>_before">
                        <?php esc_html_e( 'Before', 'strong-testimonials' ); ?>
                    </label>
                    <input id="client_section_<?php echo esc_attr( $key ); ?>_before" type="text" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][before]" value="<?php echo isset( $field['before'] ) ? esc_attr($field['before']) : ''; ?>">
                </div>

                <!-- FIELD CSS CLASS -->
                <div class="field-property field-css field-dep" <?php if ( $adding ) echo ' style="display: none;"'; ?>>
                    <label for="client_section_<?php echo esc_attr( $key ); ?>_class">
                        <?php esc_html_e( 'CSS Class', 'strong-testimonials' ); ?>
                    </label>
                    <input id="client_section_<?php echo esc_attr( $key ); ?>_class" type="text" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][class]" value="<?php echo esc_attr( $field['class' ] ); ?>">
                </div>

            </div>

		</div>

	</div>
	<?php
}


/**
 * Show a single client link field inputs.
 *
 * @since 1.21.0
 *
 * @param $key
 * @param $field_name
 * @param $type
 * @param $field
 * @param bool|false $adding
 */
function wpmtst_view_field_link( $key, $field_name, $type, $field, $adding = false, $source = 'view[data]' ) {
	if ( $field_name ) {
		$current_field = wpmtst_get_field_by_name( $field_name );
		if ( is_array( $current_field ) ) {
			$field = array_merge( $current_field, $field );
		}
	}

	$custom_fields = wpmtst_get_custom_fields();

	// Add placeholder link_text and label to field in case we need to populate link_text
	if ( ! isset( $field['link_text'] ) ) {
		$field['link_text'] = 'field';
	}
	if ( ! isset( $field['link_text_custom'] ) ) {
		$field['link_text_custom'] = '';
	}
	$field['label'] = wpmtst_get_field_label( $field );
	?>

	<?php // the link text ?>
	<div class="flex">
		<label for="view-fieldtext<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Link Text', 'strong-testimonials' ); ?></label>
		<select id="view-fieldtext<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][link_text]" class="if selectgroup">
			<option value="value" <?php selected( $field['link_text'], 'value' ); ?>><?php esc_html_e( "this field's value", 'strong-testimonials' ); ?></option>
			<option value="label" <?php selected( $field['link_text'], 'label' ); ?>><?php esc_html_e( "this field's label", 'strong-testimonials' ); ?></option>
			<option value="custom" <?php selected( $field['link_text'], 'custom' ); ?>><?php esc_html_e( 'custom text', 'strong-testimonials' ); ?></option>
		</select>
	</div>

	<?php // the link text options ?>
	<?php // use the field label ?>
	<div class="flex then_fieldtext<?php echo esc_attr( $key ); ?> then_label then_not_value then_not_custom" style="display: none;">
		<div class="nolabel">&nbsp;</div>
		<input type="text" id="view-fieldtext<?php echo esc_attr( $key ); ?>-label" value="<?php echo esc_attr( $field['label'] ); ?>" readonly>
	</div>
	<?php // use custom text ?>
	<div class="flex then_fieldtext<?php echo esc_attr( $key ); ?> then_custom then_not_value then_not_label" style="display: none;">
		<div class="nolabel">&nbsp;</div>
		<input type="text" id="view-fieldtext<?php echo esc_attr( $key ); ?>-custom" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][link_text_custom]" value="<?php echo esc_attr( $field['link_text_custom'] ); ?>">
	</div>

	<?php // the URL ?>
	<?php if ( 'link' == $type ) : // URL = another field ?>
	<div class="flex">
		<label for="view-fieldurl<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'URL Field', 'strong-testimonials' ); ?></label>
		<select id="view-fieldurl<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][url]" class="field-type-select">
			<?php foreach ( $custom_fields as $key2 => $field2 ) : ?>
				<?php if ( 'url' == $field2['input_type'] ) : ?>
				<option value="<?php echo esc_attr( $field2['name'] ); ?>" <?php selected( $field2['name'], $field['url'] ); ?>><?php echo esc_html( $field2['name'] ); ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="flex">
		<?php // the URL options ?>
		<div class="nolabel"></div>
		<div class="new_tab">
			<input type="checkbox" id="view-fieldurl<?php echo esc_attr( $key ); ?>-newtab" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo  esc_attr( $key ); ?>][new_tab]" value="1" <?php checked( $field['new_tab'] ); ?>>
			<label for="view-fieldurl<?php echo  esc_attr( $key ); ?>-newtab">
				<?php esc_html_e( 'new tab', 'strong-testimonials' ); ?>
			</label>
		</div>

	</div>
	<?php else : // URL = this field ?>
		<input type="hidden" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][url]" value="<?php echo esc_attr( $field['name'] ); ?>">
	<?php endif; ?>

	<?php
}


/**
 * Show a single client date field inputs.
 *
 * @since 1.21.0
 *
 * @param $key
 * @param $field
 * @param bool $adding
 */
function wpmtst_view_field_date( $key, $field, $adding = false, $source = 'view[data]'  ) {
	?>
	<div class="flex">
		<label for="view-<?php echo esc_attr( $key ); ?>-client-date-format"><span><?php esc_html_e( 'Format', 'strong-testimonials' ); ?></span></label>
		<input id="view-<?php echo esc_attr( $key ); ?>-client-date-format" type="text" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][format]" class="field-type-date" value="<?php echo isset( $field['format'] ) ? esc_attr($field['format']) : ''; ?>">
	</div>
	<div class="flex">
		<div class="nolabel">&nbsp;</div>
		<div class="help minor">
			<?php printf( '<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://codex.wordpress.org/Formatting_Date_and_Time' ),
				esc_html__( 'more about date formats', 'strong-testimonials' ) ); ?>
		</div>
	</div>
	<?php
}


/**
 * Show checked and unchecked value of checkbox.
 *
 * @since 2.40.4
 *
 * @param $key
 * @param $field
 * @param bool $adding
 */
function wpmtst_view_field_checkbox( $key, $field, $adding = false, $source = 'view[data]' ) {
        if ( ! isset( $field['label'] ) ) {
		$field['label'] = 'label';
	}
        if ( ! isset( $field['checked_value'] ) ) {
		$field['checked_value'] = 'value';
	}
        $label = '';
        $checked_value = '';
        // label
        if ( $field['label'] == 'label') {
                $label = wpmtst_get_field_label( $field );
        } else {
            if (isset($field['custom_label']) && !empty($field['custom_label'])) {
                $label = $field['custom_label'];
            }
        }
        $custom_fields = wpmtst_get_custom_fields();
        // checked value
        if ( $field['checked_value'] == 'value') {
            $checked_value = wpmtst_get_field_text( $field );
        } else {
            if (isset($field['checked_value_custom']) && !empty($field['checked_value_custom'])) {
                $checked_value = $field['checked_value_custom'];
            }
        }
        ?>
       	<div class="flex">
		<label for="client_section_<?php esc_attr( $key ); ?>_label"><?php esc_html_e( 'Label', 'strong-testimonials' ); ?></label>
		<select id="client_section_<?php echo esc_attr( $key ); ?>_label" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][label]" class="field-label-select">
			<option value="label" <?php selected( $field['label'], 'label' ); ?>><?php esc_html_e( 'Field label', 'strong-testimonials' ); ?></option>
			<option value="custom" <?php selected( $field['label'], 'custom' ); ?>><?php esc_html_e( 'Custom label', 'strong-testimonials' ); ?></option>
		</select>
	</div>
	<div class="field-property field-before field-dep">
		<label for="client_section_<?php echo esc_attr( $key ); ?>_custom_label"></label>
		<input id="client_section_<?php echo esc_attr( $key ); ?>_custom_label" class="client_section_field_label" attr-defaultValue="<?php echo esc_attr( wpmtst_get_field_label( $field ) ) ?>" type="text" name="<?php echo esc_attr( $source ) ?>[client_section][<?php echo esc_attr( $key ); ?>][custom_label]" value="<?php echo esc_attr( $label ) ?>" <?php echo ($field['label'] == 'label' ? 'readonly' : '') ?>>
	</div>
	<div class="field-property field-before field-dep">
		<label for="client_section_<?php echo esc_attr( $key ); ?>_checked_value">
			<?php esc_html_e( 'Checked Value', 'strong-testimonials' ); ?>
		</label>
	<select id="client_section_<?php echo esc_attr( $key ); ?>_checked_value" name="<?php echo esc_attr( $source ); ?>[client_section][<?php echo esc_attr( $key ); ?>][checked_value]" class="field-checked-select">
		<option value="value" <?php selected( $field['checked_value'], 'value' ); ?>><?php esc_html_e( 'Checked value', 'strong-testimonials' ); ?></option>
		<option value="custom" <?php selected( $field['checked_value'], 'custom' ); ?>><?php esc_html_e( 'Custom value', 'strong-testimonials' ); ?></option>
	</select>
	</div>
	<div class="field-property field-before field-dep">
		<label for="client_section_<?php echo esc_attr( $key ); ?>_checked_value_custom"></label>
		<input id="client_section_<?php echo esc_attr( $key ); ?>_checked_value_custom" class="client_section_field_checked_value" attr-defaultValue="<?php echo esc_attr(wpmtst_get_field_text( $field )); ?>" type="text" name="<?php echo esc_attr( $source ); ?>[client_section][<?php echo esc_attr( $key ); ?>][checked_value_custom]" value="<?php echo esc_attr( $checked_value ) ?>" <?php echo ($field['checked_value'] == 'value' ? 'readonly' : '') ?>>
	</div>
	<div class="field-property field-before field-dep">
		<label for="client_section_<?php echo esc_attr( $key ); ?>_unchecked_value">
				<?php esc_html_e( 'Unchecked Value', 'strong-testimonials' ); ?>
		</label>
		<input id="client_section_<?php echo esc_attr( $key ); ?>_unchecked_value" type="text" name="<?php echo esc_attr( $source ); ?>[client_section][<?php echo esc_attr( $key ); ?>][unchecked_value]" value="<?php echo isset( $field['unchecked_value'] ) ? esc_attr($field['unchecked_value']) : ''; ?>">
	</div>
	<?php
}


/**
 * Delete a view.
 *
 * @since 1.21.0
 * @param $id
 * @return false|int
 */
function wpmtst_delete_view( $id ) {
	global $wpdb;
	$num_rows_deleted = $wpdb->delete( $wpdb->prefix . 'strong_views', array( 'id' => $id ) );
	return $num_rows_deleted;
}


/**
 * Admin action hook to delete a view.
 *
 * @since 1.21.0
 */
function wpmtst_action_delete_view() {
	if ( isset( $_REQUEST['action'] ) && 'delete-strong-view' == $_REQUEST['action'] && isset( $_REQUEST['id'] ) ) {
		$id = abs( (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT ) );
		check_admin_referer( 'delete-strong-view_' . $id );
		wpmtst_delete_view( $id );
		$goback = add_query_arg( 'result', 'view-deleted', wp_get_referer() );
		wp_redirect( $goback );
		exit;
	}
}
add_action( 'admin_action_delete-strong-view', 'wpmtst_action_delete_view' );


/**
 * Category selector in Display mode in view editor.
 *
 * @param $view_cats_array
 */
function wpmtst_category_checklist( $view_cats_array ) {
	?>
	<div class="view-category-list-panel short-panel">
		<div class="fc-search-wrap">
			<input type="search" class="fc-search-field"
				   placeholder="<?php esc_attr_e( 'filter categories', 'strong-testimonials' ); ?>"/>
		</div>
		<ul class="view-category-list">
			<?php $args = array(
				'descendants_and_self' => 0,
				'selected_cats'        => $view_cats_array,
				'popular_cats'         => false,
				'walker'               => new Walker_Strong_Category_Checklist(),
				'taxonomy'             => "wpm-testimonial-category",
				'checked_ontop'        => true,
			); ?>
			<?php wp_terms_checklist( 0, $args ); ?>
		</ul>
	</div>
	<?php
}


/**
 * Category selector in Form mode in view editor.
 *
 * @param $view_cats_array
 */
function wpmtst_form_category_checklist( $view_cats_array ) {
	?>
	<div class="view-category-list-panel short-panel">
		<div class="fc-search-wrap">
			<input type="search" class="fc-search-field"
				   placeholder="<?php esc_html_e( 'filter categories', 'strong-testimonials' ); ?>"/>
		</div>
		<ul class="view-category-list">
			<?php $args = array(
				'descendants_and_self' => 0,
				'selected_cats'        => $view_cats_array,
				'popular_cats'         => false,
				'walker'               => new Walker_Strong_Form_Category_Checklist(),
				'taxonomy'             => "wpm-testimonial-category",
				'checked_ontop'        => true,
			); ?>
			<?php wp_terms_checklist( 0, $args ); ?>
		</ul>
	</div>
	<?php
}


/**
 * Save sticky view
 *
 * @since 2.22.0
 */
function wpmtst_save_view_sticky() {
	if( !current_user_can('edit_posts') ){
		wp_die();
	}
	$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
	$stickies = get_option( 'wpmtst_sticky_views', array() );
	if ( in_array( $id, $stickies ) ) {
		$stickies = array_diff( $stickies, array( $id ) );
		$is_sticky = false;
	} else {
		$stickies[] = $id;
		$is_sticky = true;
	}
	update_option( 'wpmtst_sticky_views', $stickies );
	echo json_encode( $is_sticky );
	wp_die();
}
add_action( 'wp_ajax_wpmtst_save_view_sticky', 'wpmtst_save_view_sticky' );


/**
 * Return classes for toggling sections.
 *
 * @param $classes
 * @param $section
 *
 * @since 2.22.0
 *
 * @return string
 */
function wpmtst_view_section_filter( $classes, $section ) {
    if ( 'compat' == $section && wpmtst_divi_builder_active() ) {
        $classes = 'then_display then_form then_slideshow then_not_single_template';
	}

    return $classes;
}
add_filter( 'wpmtst_view_section', 'wpmtst_view_section_filter', 10, 2 );
