<?php
/**
 * Strong Testimonials - Admin functions
 */


/**
 * Init
 */
function wpmtst_admin_init() {

	// Store plugin data from file header
	WPMST()->set_plugin_data();

	// Check WordPress version
	wpmtst_version_check();

	// Check for new options in plugin activation/update
	wpmtst_upgrade();

	// Remove ad banner from Captcha by BestWebSoft plugin
	remove_action( 'admin_notices', 'cptch_plugin_banner' );

	/**
	 * Custom action hooks
	 *
	 * @since 1.18.4
	 */
	if ( isset( $_REQUEST['action'] ) && '' != $_REQUEST['action'] ) {
		do_action( 'wpmtst_' . $_REQUEST['action'] );
	}

}
add_action( 'admin_init', 'wpmtst_admin_init' );


/**
 * Register admin scripts.
 */
function wpmtst_admin_register() {

	$plugin_version = get_option( 'wpmtst_plugin_version' );

	wp_register_style( 'wpmtst-font-awesome',
        WPMTST_PUBLIC_URL . 'fonts/font-awesome-4.6.3/css/font-awesome.min.css',
        array(),
        '4.6.3' );

	wp_register_script( 'wpmtst-help',
        WPMTST_ADMIN_URL . 'js/help.js',
        array( 'jquery' ),
        $plugin_version,
        true );

	wp_register_script( 'wpmtst-admin-script',
        WPMTST_ADMIN_URL . 'js/admin.js',
        array( 'jquery', 'underscore' ),
        $plugin_version,
        true );

	wp_register_style( 'wpmtst-admin-style',
        WPMTST_ADMIN_URL . 'css/admin.css',
        array( 'wpmtst-font-awesome' ),
        $plugin_version );

	wp_register_style( 'wpmtst-post-editor',
        WPMTST_ADMIN_URL . 'css/post-editor.css',
        array( 'wpmtst-font-awesome' ),
        $plugin_version );

	// for Page Builder?
	wp_register_script( 'wpmtst-validation-plugin',
        WPMTST_PUBLIC_URL . 'js/lib/validate/jquery.validate.min.js',
        array( 'jquery' ),
        $plugin_version );

	/**
     * Fields
     */
	wp_register_style( 'wpmtst-admin-fields-style',
        WPMTST_ADMIN_URL . 'css/fields.css',
        array(),
        $plugin_version );

	wp_register_style( 'wpmtst-admin-form-preview',
        WPMTST_ADMIN_URL . 'css/form-preview.css',
        array(),
        $plugin_version );

	wp_register_script( 'wpmtst-admin-fields-script',
        WPMTST_ADMIN_URL . 'js/admin-fields.js',
        array( 'jquery', 'jquery-ui-sortable', 'wpmtst-help' ),
        $plugin_version,
        true );

	$params = array(
		'ajax_nonce' => wp_create_nonce( 'wpmtst-admin' ),
        'newField'   => _x( 'New Field', 'Field editor: The default label for new fields', 'strong-testimonials' ),
        'inUse'      => _x( '(in use)', 'Fields editor: To indicate when a field type can only be used once.', 'strong-testimonials' ),
        'noneFound'  => _x( '(none found)', 'Fields editor: To indicate when no categories have been found.', 'strong-testimonials' ),
	);
	wp_localize_script( 'wpmtst-admin-fields-script', 'wpmtstAdmin', $params );

	/**
     * Ratings
     */
    wp_register_style( 'wpmtst-rating-display',
        WPMTST_PUBLIC_URL . 'css/rating-display.css',
        array( 'wpmtst-font-awesome' ),
        $plugin_version );

	wp_register_style( 'wpmtst-rating-form',
        WPMTST_PUBLIC_URL . 'css/rating-form.css',
        array( 'wpmtst-font-awesome' ),
        $plugin_version );

	wp_register_script( 'wpmtst-rating-script',
        WPMTST_ADMIN_URL . 'js/rating-edit.js',
        array( 'jquery' ),
        $plugin_version,
        true );

	/**
     * Views
     */
	wp_register_style( 'wpmtst-admin-views-style',
        WPMTST_ADMIN_URL . 'css/views.css',
        array(),
        $plugin_version );

	wp_register_script( 'wpmtst-admin-views-script',
        WPMTST_ADMIN_URL . 'js/views.js',
		array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-masonry', 'wpmtst-help' ),
        $plugin_version,
        true );

	/**
	 * Category filter in View editor.
	 *
	 * JavaScript adapted under GPL-2.0+ license
	 * from Post Category Filter plugin by Javier Villanueva (http://www.jahvi.com)
	 *
	 * @since 2.2.0
	 */
	wp_register_script( 'wpmtst-view-category-filter-script',
        WPMTST_ADMIN_URL . 'js/view-category-filter.js',
        array( 'jquery' ),
        $plugin_version,
        true );

	wp_register_style( 'wpmtst-admin-guide-style',
        WPMTST_ADMIN_URL . 'css/guide.css',
        array(),
        $plugin_version );

	/**
	 * Add-on licenses
	 *
	 * @since 2.18
	 */
	wp_register_script( 'wpmtst-addons-script',
        WPMTST_ADMIN_URL . 'js/addon-licenses.js',
        array( 'jquery' ),
        $plugin_version,
        true );

	$params = array(
		'ajax_nonce'     => wp_create_nonce( 'wpmtst-admin' ),
		'requiredField'  => __( 'This field is required.', 'strong-testimonials' ),
		'errorMessage'   => __( 'An error occurred, please try again.', 'strong-testimonials' ),
		'restoreDefault' => __( 'Restore the default settings?', 'strong-testimonials' ),
	);
	wp_localize_script( 'wpmtst-addons-script', 'strongAddonAdmin', $params );

	/**
	 * Are You Sure? for dirty forms
	 *
	 * @since 2.18
	 */
	wp_register_script( 'wpmtst-ays-script',
        WPMTST_ADMIN_URL . 'js/lib/are-you-sure/jquery.are-you-sure.js',
        array( 'jquery' ),
        $plugin_version,
        true );
}
add_action( 'admin_init', 'wpmtst_admin_register' );


/**
 * Enqueue common admin scripts.
 *
 * @param $hook
 */
function wpmtst_admin_enqueue_scripts( $hook ) {

	// Page Builder compat
	if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );
		wp_enqueue_script( 'wpmtst-validation-plugin' );
	}

}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_enqueue_scripts' );


/**
 * ----------------------------------
 * START: Enqueue styles and scripts.
 * ----------------------------------
 *
 * Using separate hooks for readability, troubleshooting,
 * and future refactoring. Focus on _where_.
 *
 * @since 2.12.0
 */

/**
 * Are we on a testimonial admin screen?
 *
 * @return bool
 */
function wpmtst_is_testimonial_screen() {
	$screen = get_current_screen();
	return ( $screen && 'wpm-testimonial' == $screen->post_type );
}

/**
 * Views
 *
 * @param $hook
 */
function wpmtst_hook__admin_views( $hook ) {
    if ( 'wpm-testimonial_page_testimonial-views' == $hook ) {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );

		wp_enqueue_style( 'wpmtst-admin-views-style' );
		wp_enqueue_script( 'wpmtst-admin-views-script' );
		wp_enqueue_script( 'wpmtst-view-category-filter-script' );

		wp_enqueue_style( 'wp-color-picker' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_views' );

/**
 * Fields
 *
 * @param $hook
 */
function wpmtst_hook__admin_fields( $hook ) {
    if ( 'wpm-testimonial_page_testimonial-fields' == $hook ) {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );

		wp_enqueue_style( 'wpmtst-admin-fields-style' );
		wp_enqueue_script( 'wpmtst-admin-fields-script' );

		wp_enqueue_style( 'wpmtst-admin-form-preview' );

		wp_enqueue_style( 'wpmtst-rating-form' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_fields' );

/**
 * Settings
 *
 * @param $hook
 */
function wpmtst_hook__admin_settings( $hook ) {
    if ( 'wpm-testimonial_page_testimonial-settings' == $hook ) {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );
		wp_enqueue_script( 'wpmtst-addons-script' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_settings' );

/**
 * Guide
 *
 * @param $hook
 */
function wpmtst_hook__admin_guide( $hook ) {
    if ( 'wpm-testimonial_page_testimonial-guide' == $hook ) {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );

		wp_enqueue_style( 'wpmtst-admin-guide-style' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_guide' );

/**
 * List table
 *
 * @param $hook
 */
function wpmtst_hook__admin_load_edit( $hook ) {
	if ( 'edit.php' == $hook && wpmtst_is_testimonial_screen() ) {
		wp_enqueue_style( 'wpmtst-admin-style' );
		wp_enqueue_script( 'wpmtst-admin-script' );

		wp_enqueue_style( 'wpmtst-rating-display' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_load_edit' );

/**
 * Categories
 *
 * @param $hook
 */
function wpmtst_hook__admin_load_edit_tags( $hook ) {
	if ( 'edit-tags.php' == $hook && wpmtst_is_testimonial_screen() ) {
		wp_enqueue_style( 'wpmtst-admin-style' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_load_edit_tags' );

/**
 * Edit post
 *
 * @param $hook
 */
function wpmtst_hook__admin_load_post( $hook ) {
	if ( 'post.php' == $hook && wpmtst_is_testimonial_screen() ) {
		wp_enqueue_style( 'wpmtst-post-editor' );
		wp_enqueue_script( 'wpmtst-admin-script' );

		wp_enqueue_style( 'wpmtst-rating-display' );
		wp_enqueue_style( 'wpmtst-rating-form' );
		wp_enqueue_script( 'wpmtst-rating-script' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_load_post' );

/**
 * Add post
 *
 * @param $hook
 */
function wpmtst_hook__admin_load_post_new( $hook ) {
	if ( 'post-new.php' == $hook && wpmtst_is_testimonial_screen() ) {
		wp_enqueue_style( 'wpmtst-post-editor' );
		wp_enqueue_style( 'wpmtst-admin-script' );

		wp_enqueue_style( 'wpmtst-rating-display' );
		wp_enqueue_style( 'wpmtst-rating-form' );
		wp_enqueue_script( 'wpmtst-rating-script' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpmtst_hook__admin_load_post_new' );

/**
 * --------------------------------
 * END: Enqueue styles and scripts.
 * --------------------------------
 */


/**
 * Known theme and plugin conflicts.
 *
 * @param $hook
 */
function wpmtst_admin_dequeue_scripts( $hook ) {

	if ( wp_style_is( 'CPTStyleSheets' ) ) {
		wp_dequeue_style( 'CPTStyleSheets' );
	}

	$hooks_to_script = array(
		'wpm-testimonial_page_testimonial-views',
		'wpm-testimonial_page_testimonial-fields',
		'wpm-testimonial_page_testimonial-settings',
		'wpm-testimonial_page_testimonial-guide',
	);

	$screen = get_current_screen();
	if ( $screen && 'wpm-testimonial' == $screen->post_type ) {
		$hooks_to_script = array_merge( $hooks_to_script, array( 'edit.php' ) );
	}

	/**
	 * Block RT Themes and their overzealous JavaScript on our admin pages.
	 * @since 2.2.12.1
	 */
	if ( in_array( $hook, $hooks_to_script ) ) {
		if ( class_exists( 'RTThemeAdmin' ) && wp_script_is( 'admin-scripts' ) ) {
			wp_dequeue_script( 'admin-scripts' );
		}
	}

}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_dequeue_scripts', 500 );


/**
 * Load custom style for WPML.
 *
 * @since 1.21.0
 */
function wpmtst_admin_scripts_wpml() {
	$plugin_version = get_option( 'wpmtst_plugin_version' );
	wp_enqueue_style( 'wpmtst-admin-style-wpml', WPMTST_ADMIN_URL . 'css/wpml.css', array(), $plugin_version );
}
add_action( 'admin_head-wpml-string-translation/menu/string-translation.php', 'wpmtst_admin_scripts_wpml' );
add_action( 'admin_head-edit-tags.php', 'wpmtst_admin_scripts_wpml' );


/**
 * Polylang conditional loading
 *
 * @since 1.21.0
 */
function wpmtst_admin_polylang() {
	if ( ! defined( 'POLYLANG_VERSION' ) )
		return;

	$plugin_version = get_option( 'wpmtst_plugin_version' );
	wp_enqueue_style( 'wpmtst-admin-style-polylang', WPMTST_ADMIN_URL . 'css/polylang.css', array(), $plugin_version );

	include_once WPMTST_INC . 'defaults.php';
	$fields = wpmtst_get_all_fields();
	wpmtst_form_fields_polylang( $fields );
	$form_options = get_option( 'wpmtst_form_options', array() );
	wpmtst_form_messages_polylang( $form_options['messages'] );
	wpmtst_form_options_polylang( $form_options );
}
add_action( 'load-settings_page_mlang', 'wpmtst_admin_polylang' );


/**
 * Add meta box to the post editor screen
 */
function wpmtst_add_meta_boxes() {
	add_meta_box( 'details', _x( 'Client Details', 'post editor', 'strong-testimonials' ), 'wpmtst_meta_options', 'wpm-testimonial', 'normal', 'high' );
}
add_action( 'add_meta_boxes_wpm-testimonial', 'wpmtst_add_meta_boxes' );


/**
 * Add custom fields to the testimonial editor
 */
function wpmtst_meta_options() {
	global $post, $pagenow;
	$post   = wpmtst_get_post( $post );
	$fields = wpmtst_get_custom_fields();
	$is_new = ( 'post-new.php' == $pagenow );
	?>
    <?php do_action( 'wpmtst_before_client_fields_table' ); ?>
	<table class="options">
		<tr>
			<td colspan="2">
				<p><?php _ex( 'To add a photo or logo, use the Featured Image option.', 'post editor', 'strong-testimonials' ); ?></p>
			</td>
		</tr>
        <?php
        do_action( 'wpmtst_before_client_fields' );
		foreach ( $fields as $key => $field ) :
			// short-circuit
			if ( 'shortcode' == $field['input_type'] || 'category' == strtok( $field['input_type'], '-' ) ) {
				continue;
			}
            ?>
            <tr>
                <th>
                    <label for="<?php echo $field['name']; ?>">
                        <?php echo apply_filters( 'wpmtst_l10n', $field['label'], wpmtst_get_l10n_context( 'form-fields' ), $field['name'] . ' : label' ); ?>
                    </label>
                </th>
                <td>
                    <div class="<?php echo $field['input_type']; ?>">
                        <?php wpmtst_meta_option( $field, $post, $is_new ); ?>
                    </div>
                </td>
            </tr>
		    <?php
		endforeach;
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
function wpmtst_meta_option( $field, $post, $is_new ) {
    switch ( $field['input_type'] ) {

        case 'rating' :
            if ( $is_new ) {
                $rating = 0;
            } else {
                $rating = get_post_meta( $post->ID, $field['name'], true );
                if ( ! $rating ) {
                    $rating = 0;
                }
            }
            ?>
            <div class="edit-rating-box hide-if-no-js" data-field="<?php echo $field['name']; ?>">

                <?php wp_nonce_field( 'editrating', "edit-{$field['name']}-nonce", false ); ?>
                <input type="hidden" class="current-rating" value="<?php echo $rating; ?>">

                <!-- form -->
                <div class="rating-form" style="<?php echo ( $is_new ) ? 'display: inline-block;' : 'display: none;'; ?>">
                    <span class="inner">
                        <?php wpmtst_star_rating_form( $field, $rating, 'in-metabox', true, 'custom' ); ?>
                    </span>
                    <?php if ( ! $is_new ) : ?>
                        <span class="edit-rating-buttons-2">
                            <button type="button" class="zero button-link"><?php _e( 'Zero', 'strong-testimonials' ); ?></button>&nbsp;
                            <button type="button" class="save button button-small"><?php _e( 'OK' ); ?></button>&nbsp;
                            <button type="button" class="cancel button-link"><?php _e( 'Cancel' ); ?></button>
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
                        <button type="button" id="" class="edit-rating button button-small hide-if-no-js" aria-label="Edit rating"><?php _e( 'Edit' ); ?></button>
                    </span>
                    <?php endif; ?>
                </div>

                <span class="edit-rating-success"></span>

            </div>
            <?php
            break;

        default :
            if ( 'url' == $field['input_type'] ) {

                ?>
                <div class="input-url">
                    <?php printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" size="">',
	                    $field['input_type'], $field['name'], esc_attr( $post->{$field['name']} ) ); ?>
                </div>
                <div class="input-nofollow">
                    <label for="custom_nofollow"><code>rel="nofollow"</code></label>
                    <select id="custom_nofollow" name="custom[nofollow]">
                        <option value="default" <?php selected( $post->nofollow, 'default' ); ?>><?php _e( 'default', 'strong-testimonials' ); ?></option>
                        <option value="yes" <?php selected( $post->nofollow, 'yes' ); ?>><?php _e( 'yes', 'strong-testimonials' ); ?></option>
                        <option value="no" <?php selected( $post->nofollow, 'no' ); ?>><?php _e( 'no', 'strong-testimonials' ); ?></option>
                    </select>
                </div>
                <?php

            } else {

                if ( 'checkbox' == $field['input_type'] ) {
	                printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" %4$s>',
		                $field['input_type'], $field['name'], 1, checked( $post->{$field['name']}, 1, false ) );
                } else {
	                printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s">',
		                $field['input_type'], $field['name'], esc_attr( $post->{$field['name']} ) );
                }

            }
    }
}


/**
 * Add custom columns to the admin list.
 *
 * @param $columns
 * @since 1.4.0
 * @since 2.5.1  Added comments column.
 * @return array
 */
function wpmtst_edit_columns( $columns ) {
	$fields = wpmtst_get_all_fields();

	$comments = isset( $columns['comments'] ) ? $columns['comments'] : '';

	/*
		INCOMING COLUMNS = Array (
			[cb] => <input type="checkbox" />
			[title] => Title
			[comments] => <span class="vers comment-grey-bubble" title="Comments"><span class="screen-reader-text">Comments</span></span>
			[date] => Date
			[search_exclude] => Search Exclude   // other plugin
			[thumbnail] => Thumbnail
		)
	*/

	// 1. remove [thumbnail] (may be re-added in custom field loop) and [date]
	unset( $columns['thumbnail'], $columns['date'] );

	if ( $comments )
		unset( $columns['comments'] );

	// 2. insert [order] after [cb]
	if ( ! wpmtst_is_column_sorted()
		&& ! wpmtst_is_viewing_trash()
		&& class_exists( 'Strong_Testimonials_Order' ) )
	{
		$columns = array_merge (
			array_slice($columns, 0, 1),
			array('handle' => 'Order'),
			array_slice($columns, 1, null)
		);
	}

	// 3. insert [excerpt] after [title]
	$key           = 'title';
	$offset        = array_search( $key, array_keys( $columns ) ) + 1;
	$fields_to_add = array( 'post_excerpt' => __( 'Excerpt', 'strong-testimonials' ) );

	// 4. add custom fields
	foreach ( $fields as $key => $field ) {

		if ( $field['admin_table'] ) {

			if ( 'post_title' == $field['name'] ) {
				continue;
			}
			elseif ( 'featured_image' == $field['name'] ) {
				$fields_to_add['thumbnail'] = __( 'Thumbnail', 'strong-testimonials' );
			}
			elseif( 'rating' == $field['input_type'] ) {
				$fields_to_add[ $field['name'] ] = __( 'Rating', 'strong-testimonials' );
			}
			else {
				$fields_to_add[ $field['name'] ] = apply_filters( 'wpmtst_l10n', $field['label'], wpmtst_get_l10n_context( 'form-fields' ), $field['name'] . ' : label' );
			}

		}

	}

	// 5. add [category], [comments] and [date]
	// 'categories' is reserved by WordPress.
	if ( wpmtst_get_category_list() )
		$fields_to_add['category'] = __( 'Categories', 'strong-testimonials' );

	if ( $comments )
		$fields_to_add['comments'] = $comments;

	$fields_to_add['date'] = __( 'Date', 'strong-testimonials' );

	// Push other added columns like [search_exclude] to the end.
	$columns = array_merge (
		array_slice( $columns, 0, $offset ),
		$fields_to_add,
		array_slice( $columns, $offset, null )
	);

	return $columns;
}
add_filter( 'manage_edit-wpm-testimonial_columns', 'wpmtst_edit_columns' );


/**
 * Show custom values
 *
 * @param $column
 */
function wpmtst_custom_columns( $column ) {
	global $post;

	switch ( $column ) {

		case 'post_id' :
			echo $post->ID;
			break;

		case 'post_content' :
			echo substr( $post->post_content, 0, 100 ) . '&hellip;';
			break;

		case 'post_excerpt' :
			echo $post->post_excerpt;
			break;

		case 'thumbnail' :
			echo get_the_post_thumbnail( $post->ID, array( 75, 75 ) );
			break;

		case 'category' :
			$categories = get_the_terms( 0, 'wpm-testimonial-category' );
			if ( $categories && ! is_wp_error( $categories ) ) {
				$list = array();
				foreach ( $categories as $cat ) {
					$list[] = $cat->name;
				}
				echo join( ", ", $list );
			}
			break;

		case 'handle' :
			if ( current_user_can( 'edit_post', $post->ID ) && ! wpmtst_is_column_sorted() && ! wpmtst_is_viewing_trash() ) {
				echo '<div class="handle"><div class="help"></div><div class="help-in-motion"></div></div>';
			}
			break;

		default :
			// custom field?
			$custom = get_post_custom();
            $fields = wpmtst_get_custom_fields();

			if ( isset( $custom[ $column ] ) && $custom[ $column ][0] ) {

                if ( isset( $fields[ $column ] ) ) {

                    switch ( $fields[ $column ]['input_type'] ) {
                        case 'rating' :
                            wpmtst_star_rating_display( $custom[ $column ][0], 'in-table-list' );
                            break;
                        case 'checkbox' :
                            echo $custom[ $column ][0] ? 'yes' : 'no';
                            break;
                        default :
                            echo $custom[ $column ][0];
                    }

                }

			} else {

				if ( isset( $fields[ $column ] ) ) {

					if ( 'checkbox' == $fields[ $column ]['input_type'] ) {
						echo 'no';
					} else {
						// display nothing
					}

				}
			}

	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_custom_columns' );


/**
 * Check if a column in admin list table is sorted.
 *
 * @since 1.16.0
 */
function wpmtst_is_column_sorted() {
	return isset( $_GET['orderby'] ) || strstr( $_SERVER['REQUEST_URI'], 'action=edit' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' );
}


/**
 * Check if we are viewing the Trash.
 *
 * @since 1.16.0
 */
function wpmtst_is_viewing_trash() {
	return isset( $_GET['post_status'] ) && 'trash' == $_GET['post_status'];
}


/**
 * Add thumbnail column to admin list
 */
function wpmtst_add_thumbnail_column( $columns ) {
	$columns['thumbnail'] = __( 'Thumbnail', 'strong-testimonials' );
	return $columns;
}
add_filter( 'manage_wpm-testimonial_posts_columns', 'wpmtst_add_thumbnail_column' );


/**
 * Add columns to the testimonials categories screen
 */
function wpmtst_manage_categories( $columns ) {
	$new_columns = array(
		'cb'    => '<input type="checkbox">',
		'ID'    => __( 'ID', 'strong-testimonials' ),
		'name'  => __( 'Name', 'strong-testimonials' ),
		'slug'  => __( 'Slug', 'strong-testimonials' ),
		'posts' => __( 'Posts', 'strong-testimonials' ),
	);

	return $new_columns;
}
add_filter( 'manage_edit-wpm-testimonial-category_columns', 'wpmtst_manage_categories');


/**
 * Show custom column
 */
function wpmtst_manage_columns( $out, $column_name, $id ) {
	if ( 'ID' == $column_name )
		$output = $id;
	else
		$output = '';

	return $output;
}
add_filter( 'manage_wpm-testimonial-category_custom_column', 'wpmtst_manage_columns', 10, 3 );


/**
 * Make columns sortable.
 *
 * @param $columns
 * @since 1.12.0
 * @since 2.2.0 category
 *
 * @return mixed
 */
function wpmtst_manage_sortable_columns( $columns ) {
	$columns['client_name'] = 'client_name';
	$columns['category'] = 'categories';
	$columns['date'] = 'date';
	return $columns;
}
add_filter( 'manage_edit-wpm-testimonial_sortable_columns', 'wpmtst_manage_sortable_columns' );


/**
 * Add category filter to testimonial list table.
 *
 * @since 2.2.0
 */
function wpmtst_add_taxonomy_filters() {
	global $typenow;

	if ( $typenow == 'wpm-testimonial' ) {

		$taxonomies = array( 'wpm-testimonial-category' );

		foreach ( $taxonomies as $tax ) {
			$tax_obj = get_taxonomy( $tax );
			$args = array(
				'show_option_all'   => $tax_obj->labels->all_items,
				'show_option_none'  => '',
				'option_none_value' => '-1',
				'orderby'           => 'NAME',
				'order'             => 'ASC',
				'show_count'        => 1,
				'hide_empty'        => 1,
				'child_of'          => 0,
				'exclude'           => '',
				'echo'              => 1,
				'selected'          => isset( $_GET[ $tax ] ) ? $_GET[ $tax ] : '',
				'hierarchical'      => 1,
				'name'              => $tax,
				'id'                => $tax,
				'class'             => 'postform',
				'depth'             => 0,
				'tab_index'         => 0,
				'taxonomy'          => $tax,
				'hide_if_empty'     => true,
				'value_field'       => 'slug',
			);

			wp_dropdown_categories( $args );
		}
	}
}
add_action( 'restrict_manage_posts', 'wpmtst_add_taxonomy_filters' );


/**
 * Sort columns.
 *
 * @since 1.12.0
 * @param $query
 */
function wpmtst_pre_get_posts( $query ) {
	// Only in main WP query AND if an orderby query variable is designated.
	if ( is_admin() && $query->is_main_query() && 'wpm-testimonial' == $query->get( 'post_type' ) ) {
		if ( 'client_name' == $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', 'client_name' );
			$query->set( 'orderby', 'meta_value' );
		}
	}
}
add_action( 'pre_get_posts', 'wpmtst_pre_get_posts', 10 );


/**
 * Save custom fields
 *
 * @since 2.23.2 Delete meta record when rating is zero to allow default display value.
 */
function wpmtst_save_details() {
	// check post type
	if ( ! isset( $_POST['post_type'] ) || 'wpm-testimonial' != $_POST['post_type'] ) {
		return;
	}

	if ( isset( $_POST['custom'] ) ) {

	    $custom = $_POST['custom'];

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
        $action = 'update';
		foreach ( $custom as $key => $value ) {
            if ( isset( $custom_fields[ $key ] ) ) {
                if ( 'rating' == $custom_fields[ $key ]['input_type'] && ! $value ) {
					$action = 'delete';
				}
			}

            if ( 'update' == $action ) {
				// empty values replace existing values
				update_post_meta( $_POST['post_ID'], $key, stripslashes( $value ) );
			} else {
                // delete value; e.g. zero rating
                delete_post_meta( $_POST['post_ID'], $key );
			}
		}

	}

}

// add_action( 'save_post_wpm-testimonial', 'wpmtst_save_details' ); // WP 3.7+  Soon...
add_action( 'save_post', 'wpmtst_save_details' );


/**
 * Check WordPress version
 */
function wpmtst_version_check() {
	global $wp_version;
	$plugin_info = WPMST()->get_plugin_data();
	$require_wp_version = "3.6";

	if ( version_compare( $wp_version, $require_wp_version ) == -1 ) {
		deactivate_plugins( WPMTST_PLUGIN );
		/* translators: %s is the name of the plugin. */
		$message = '<h2>' . sprintf( _x( 'Unable to load %s', 'installation', 'strong-testimonials' ), $plugin_info['Name'] ) . '</h2>';
		/* translators: %s is a WordPress version number. */
		$message .= '<p>' . sprintf( _x( 'This plugin requires <strong>WordPress %s</strong> or higher so it has been deactivated.', 'installation', 'strong-testimonials' ), $require_wp_version ) . '</p>';
		$message .= '<p>' . _x( 'Please upgrade WordPress and try again.', 'installation', 'strong-testimonials' ) . '</p>';
		$message .= '<p>' . sprintf( _x( 'Back to the WordPress <a href="%s">Plugins page</a>', 'installation', 'strong-testimonials' ), get_admin_url( null, 'plugins.php' ) ) . '</p>';
		wp_die( $message );
	}
}


/**
 * Add pending numbers to post types on admin menu.
 * Thanks http://wordpress.stackexchange.com/a/105470/32076
 *
 * @param $menu
 *
 * @return mixed
 */
function wpmtst_pending_indicator( $menu ) {
	if ( ! current_user_can( 'edit_posts' ) )
		return $menu;

	$options = get_option( 'wpmtst_options' );
	if ( ! isset( $options['pending_indicator'] ) || ! $options['pending_indicator'] )
		return $menu;

	$types  = array( 'wpm-testimonial' );
	$status = 'pending';
	foreach ( $types as $type ) {
		$num_posts     = wp_count_posts( $type, 'readable' );
		$pending_count = 0;
		if ( ! empty( $num_posts->$status ) )
			$pending_count = $num_posts->$status;

		if ( $type == 'post' )
			$menu_str = 'edit.php';
		else
			$menu_str = 'edit.php?post_type=' . $type;

		foreach ( $menu as $menu_key => $menu_data ) {
			if ( $menu_str != $menu_data[2] )
				continue;
			$menu[ $menu_key ][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n( $pending_count ) . '</span></span>';
		}
	}

	return $menu;
}
add_filter( 'add_menu_classes', 'wpmtst_pending_indicator' );


/**
 * The [restore default] icon.
 *
 * @param $for
 *
 * @since 2.18.0
 */
function wpmtst_restore_default_icon( $for ) {
	if ( !$for ) return;
	?>
	<input type="button" class="button secondary restore-default"
		   title="<?php _e( 'restore default', 'strong-testimonials' ); ?>"
		   value="&#xf171"
		   data-for="<?php echo $for; ?>"/>
	<?php
}


/**
 * Add plugin links.
 *
 * @param        $plugin_meta
 * @param        $plugin_file
 * @param array  $plugin_data
 * @param string $status
 *
 * @return array
 */
function wpmtst_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data = array(), $status = '' ) {

    if ( $plugin_file == WPMTST_PLUGIN ) {

		$plugin_meta[] = sprintf(
		    '<a href="%s" target="_blank" title="%s" style="color: #8224e3; font-weight: 600;">%s</a>',
			'https://support.strongplugins.com/',
            __( 'For direct support requests and documentation', 'strong-testimonials' ),
            __( 'Support', 'strong-testimonials' ) );

		$plugin_meta[] = sprintf(
            '<a href="%s" target="_blank" title="%s" style="color: #8224e3; font-weight: 600;">%s</a>',
			'https://strongplugins.com/',
            __( 'Get more features with premium add-ons', 'strong-testimonials' ),
            __( 'Add-ons', 'strong-testimonials' ) );

	}

	return $plugin_meta;
}
add_filter( 'plugin_row_meta', 'wpmtst_plugin_row_meta' , 10, 4 );
