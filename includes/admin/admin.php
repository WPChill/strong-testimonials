<?php
/**
 * Strong Testimonials - Admin functions
 */


/**
 * Init
 */
function wpmtst_admin_init() {

	// Check WordPress version
	wpmtst_version_check();

	// Check for new options in plugin activation/update
	wpmtst_default_settings();

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
 * Prevent other post ordering plugins, in admin_init hook.
 *
 * @since 1.16.0
 */
function wpmtst_deny_plugins_init() {

	/**
	 * Intuitive Custom Post Order
	 */
	if ( is_plugin_active( 'intuitive-custom-post-order/intuitive-custom-post-order.php' ) ) {
		$options = get_option( 'hicpo_options' );
		$update = false;

		if ( isset( $options['objects'] ) && is_array( $options['objects'] ) ) {
			if ( in_array( 'wpm-testimonial', $options['objects'] ) ) {
				$options['objects'] = array_diff( $options['objects'], array( 'wpm-testimonial' ) );
				$update = true;
			}
		}

		if ( isset( $options['tags'] ) && is_array( $options['tags'] ) ) {
			if ( in_array( 'wpm-testimonial-category', $options['tags'] ) ) {
				$options['tags'] = array_diff( $options['tags'], array( 'wpm-testimonial-category' ) );
				$update = true;
			}
		}

		if ( $update )
			update_option( 'hicpo_options', $options );
	}

	/**
	 * Simple Custom Post Order
	 */
	if ( is_plugin_active( 'simple-custom-post-order/simple-custom-post-order.php' ) ) {
		$options = get_option( 'scporder_options' );
		$update = false;

		if ( isset( $options['objects'] ) && is_array( $options['objects'] ) ) {
			if ( in_array( 'wpm-testimonial', $options['objects'] ) ) {
				$options['objects'] = array_diff( $options['objects'], array( 'wpm-testimonial' ) );
				$update = true;
			}
		}

		if ( isset( $options['tags'] ) && is_array( $options['tags'] ) ) {
			if ( in_array( 'wpm-testimonial-category', $options['tags'] ) ) {
				$options['tags'] = array_diff( $options['tags'], array( 'wpm-testimonial-category' ) );
				$update = true;
			}
		}

		if ( $update )
			update_option( 'scporder_options', $options );
	}

}
add_action( 'admin_init', 'wpmtst_deny_plugins_init', 200 );


/**
 * Plugin and theme compatibility in admin.
 *
 * @since 2.4.0
 */
function wpmtst_compat__admin_init() {
	$theme = wp_get_theme();

	/* ------------------------------------------------------------
	 * Theme Name: Mercury
	 * Theme URI: http://themes.themegoods2.com/mercury
	 * Description: Premium Template for Photography Portfolio
	 * Version: 1.7.5
	 * Author: Peerapong Pulpipatnan
	 * Author URI: http://themeforest.net/user/peerapong
	 * ------------------------------------------------------------
	 * Mercury enqueues its scripts and styles poorly.
	 * 1. on the `admin_init` hook
	 * 2. UNconditionally
	 */
	if ( 'Mercury' == $theme->get( 'Name' ) && 'http://themes.themegoods2.com/mercury' == $theme->get( 'ThemeURI' )	) {

		/** Screen information is not available yet. */
		//$screen = get_current_screen();
		//if ( $screen && 'wpm-testimonial' == $screen->post_type ) {

		if ( false !== strpos( $_SERVER['QUERY_STRING'], 'post_type=wpm-testimonial' ) ) {
			if ( function_exists( 'pp_add_init' ) ) {
				remove_action( 'admin_init', 'pp_add_init' );
			}
		}

	}
}
add_action( 'admin_init', 'wpmtst_compat__admin_init', 1 );


/**
 * Prevent other post ordering plugins, in admin_menu hook.
 *
 * @since 1.16.0
 */
function wpmtst_deny_plugins_menu() {

	/**
	 * Post Types Order
	 */
	if ( is_plugin_active( 'post-types-order/post-types-order.php' ) ) {
		remove_submenu_page( 'edit.php?post_type=wpm-testimonial', 'order-post-types-wpm-testimonial' );
	}

}
add_action( 'admin_menu', 'wpmtst_deny_plugins_menu', 200 );


/**
 * Admin scripts.
 *
 * @param $hook
 */
function wpmtst_admin_scripts( $hook ) {

	$plugin_version = get_option( 'wpmtst_plugin_version' );

	$hooks_to_style = array(
		'wpm-testimonial_page_testimonial-views',
		'wpm-testimonial_page_testimonial-fields',
		'wpm-testimonial_page_testimonial-settings',
		'wpm-testimonial_page_testimonial-guide',
		'settings_page_strong-testimonials-welcome',
	);

	$screen = get_current_screen();
	if ( $screen && 'wpm-testimonial' == $screen->post_type ) {
		$hooks_to_style = array_merge( $hooks_to_style, array( 'edit.php', 'edit-tags.php', 'post.php', 'post-new.php' ) );
	}

	// Page Builder compat
	if ( in_array( $hook, $hooks_to_style ) || defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		wp_enqueue_style( 'wpmtst-admin-style', WPMTST_URL . 'css/admin/admin.css', array(), $plugin_version );
	}

	$hooks_to_script = array(
		'wpm-testimonial_page_testimonial-views',
		'wpm-testimonial_page_testimonial-fields',
		'wpm-testimonial_page_testimonial-settings',
		'wpm-testimonial_page_testimonial-guide',
	);

	if ( $screen && 'wpm-testimonial' == $screen->post_type ) {
		$hooks_to_script = array_merge( $hooks_to_script, array( 'edit.php' ) );
	}

	if ( in_array( $hook, $hooks_to_script ) || defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		wp_enqueue_script( 'wpmtst-admin-script', WPMTST_URL . 'js/wpmtst-admin.js', array( 'jquery' ), $plugin_version );
		wp_localize_script( 'wpmtst-admin-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	// Page Builder compat
	if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		//TODO  is loading validate necessary? if so, language file too?
		wp_enqueue_script( 'wpmtst-validation-plugin', WPMTST_URL . 'js/validate/jquery.validate.min.js', array( 'jquery' ), $plugin_version );
	}

	// Extra
	switch ( $hook ) {

		// The Fields Editor
		case 'wpm-testimonial_page_testimonial-fields':
			wp_enqueue_style( 'wpmtst-admin-fields-style', WPMTST_URL . 'css/admin/fields.css', array(), $plugin_version );
			wp_enqueue_script( 'wpmtst-admin-fields-script', WPMTST_URL . 'js/wpmtst-admin-fields.js', array( 'jquery', 'jquery-ui-sortable' ), $plugin_version );
			wp_localize_script( 'wpmtst-admin-fields-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			break;

		// The View Editor
		case 'wpm-testimonial_page_testimonial-views':
			wp_enqueue_style( 'wpmtst-admin-views-style', WPMTST_URL . 'css/admin/views.css', array(), $plugin_version );
			wp_enqueue_script( 'wpmtst-admin-views-script', WPMTST_URL . 'js/wpmtst-views.js',
					array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker', 'jquery-masonry' ), $plugin_version );
			wp_localize_script( 'wpmtst-admin-views-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_style( 'wp-color-picker' );

			/**
			 * Category filter in View editor.
			 *
			 * JavaScript adapted under GPL-2.0+ license
			 * from Post Category Filter plugin by Javier Villanueva (http://www.jahvi.com)
			 *
			 * @since 2.2.0
			 */
			wp_register_script( 'wpmtst-view-category-filter-script', WPMTST_URL . 'js/wpmtst-view-category-filter.js', array( 'jquery' ), $plugin_version, true );

			break;

		// The Guide
		case 'wpm-testimonial_page_testimonial-guide':
			wp_enqueue_style( 'wpmtst-admin-guide-style', WPMTST_URL . 'css/admin/guide.css', array(), $plugin_version );
			break;

		default:
	}

	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '4.4.0' );

}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_scripts' );

/**
 * Known theme and plugin conflicts.
 *
 * @param $hook
 */
function wpmtst_admin_dequeue_scripts( $hook ) {

	if ( wp_style_is( 'CPTStyleSheets' ) )
		wp_dequeue_style( 'CPTStyleSheets' );

	$screen = get_current_screen();

	$hooks_to_script = array(
		'wpm-testimonial_page_testimonial-views',
		'wpm-testimonial_page_testimonial-fields',
		'wpm-testimonial_page_testimonial-settings',
		'wpm-testimonial_page_testimonial-guide',
	);

	if ( $screen && 'wpm-testimonial' == $screen->post_type )
		$hooks_to_script = array_merge( $hooks_to_script, array( 'edit.php' ) );

	/**
	 * Block RT Themes and their overzealous JavaScript on our admin pages.
	 * @since 2.2.12.1
	 */
	if ( in_array( $hook, $hooks_to_script ) ) {
		if ( class_exists( 'RTThemeAdmin' ) && wp_script_is( 'admin-scripts' ) )
			wp_dequeue_script( 'admin-scripts' );
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
	wp_enqueue_style( 'wpmtst-admin-style-wpml', WPMTST_URL . 'css/admin/wpml.css', array(), $plugin_version );
}
add_action( 'load-wpml-string-translation/menu/string-translation.php', 'wpmtst_admin_scripts_wpml' );
add_action( 'load-edit-tags.php', 'wpmtst_admin_scripts_wpml' );

/**
 * Polylang conditional loading
 *
 * @since 1.21.0
 */
function wpmtst_admin_polylang() {
	if ( ! defined( 'POLYLANG_VERSION' ) )
		return;

	$plugin_version = get_option( 'wpmtst_plugin_version' );
	wp_enqueue_style( 'wpmtst-admin-style-polylang', WPMTST_URL . 'css/admin/polylang.css', array(), $plugin_version );

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
	global $post;
	$post   = wpmtst_get_post( $post );
	$fields = wpmtst_get_custom_fields();
	?>
	<table class="options">
		<tr>
			<td colspan="2">
				<?php _ex( 'To add a photo or logo, use the Featured Image option.', 'post editor', 'strong-testimonials' ); ?>&nbsp;<div class="dashicons dashicons-arrow-right-alt"></div>
			</td>
		</tr>
		<?php foreach ( $fields as $key => $field ) : ?>
		<tr>
			<th>
				<label for="<?php echo $field['name']; ?>">
					<?php echo apply_filters( 'wpmtst_l10n', $field['label'], wpmtst_get_l10n_context( 'form-fields' ), $field['name'] . ' : label' ); ?>
				</label>
			</th>
			<td>
				<?php echo sprintf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" size="">', $field['input_type'], $field['name'], $post->{$field['name']} ); ?>
				<?php if ( 'url' == $field['input_type'] ) : ?>
					&nbsp;&nbsp;<label><input type="checkbox" name="custom[nofollow]" <?php checked( $post->nofollow, 'on' ); ?>> <code>rel="nofollow"</code></label>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php
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

		case 'post_id':
			echo $post->ID;
			break;

		case 'post_content':
			echo substr( $post->post_content, 0, 100 ) . '&hellip;';
			break;

		case 'post_excerpt':
			echo $post->post_excerpt;
			break;

		case 'thumbnail':
			echo get_the_post_thumbnail( $post->ID, array( 75, 75 ) );
			break;

		case 'category':
			$categories = get_the_terms( 0, 'wpm-testimonial-category' );
			if ( $categories && ! is_wp_error( $categories ) ) {
				$list = array();
				foreach ( $categories as $cat ) {
					$list[] = $cat->name;
				}
				echo join( ", ", $list );
			}
			break;

		case 'handle':
			if ( current_user_can( 'edit_post', $post->ID ) && ! wpmtst_is_column_sorted() && ! wpmtst_is_viewing_trash() ) {
				echo '<div class="handle"><div class="help"></div><div class="help-in-motion"></div></div>';
			}
			break;

		default:
			// custom field?
			$custom  = get_post_custom();
			if ( isset( $custom[$column] ) )
				echo $custom[$column][0];

	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_custom_columns' );


/**
 * Filter the published time of the post.
 *
 * This filter is documented in wp-admin/includes/class-wp-posts-list-table.php.
 *
 * @since 1.16.0
 */
function wpmtst_post_date_column_time( $t_time, $post = null, $column_name = 'date', $mode = 'list' ) {
	if ( $post && 'wpm-testimonial' == $post->post_type && 'date' == $column_name ) {
		$t_time = get_post_time( __( 'Y/m/d g:i:s A' ), true, $post );
	}
	return $t_time;
}
add_filter( 'post_date_column_time', 'wpmtst_post_date_column_time', 10, 4 );


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
 */
function wpmtst_pre_get_posts( $query ) {
	// Only in main WP query AND if an orderby query variable is designated.
	if ( is_admin()
		&& $query->is_main_query()
		&& 'wpm-testimonial' == $query->get( 'post_type' )
		&& ( $orderby = $query->get( 'orderby' ) )
	) {
		if ( 'client_name' == $orderby ) {
			$query->set( 'meta_key', 'client_name' );
			$query->set( 'orderby', 'meta_value' );
		}
	}
}
add_action( 'pre_get_posts', 'wpmtst_pre_get_posts', 10 );


/**
 * Save custom fields
 */
function wpmtst_save_details() {
	// check Custom Post Type
	if ( ! isset( $_POST['post_type'] ) || 'wpm-testimonial' != $_POST['post_type'] )
		return;

	global $post;

	if ( isset( $_POST['custom'] ) ) {

		// {missing 'nofollow'} = {unchecked checkbox} = 'off'
		if ( ! array_key_exists( 'nofollow', $_POST['custom'] ) )
			$_POST['custom']['nofollow'] = 'off';

		foreach ( $_POST['custom'] as $key => $value ) {
			// empty values replace existing values
			update_post_meta( $post->ID, $key, $value );
		}

	}
}
// add_action( 'save_post_wpm-testimonial', 'wpmtst_save_details' ); // WP 3.7+
add_action( 'save_post', 'wpmtst_save_details' );


/**
 * Check WordPress version
 */
function wpmtst_version_check() {
	global $wp_version;
	$plugin_info = get_plugin_data( __FILE__, false );
	$require_wp = "3.5";  // minimum Wordpress version
	$plugin = plugin_basename( __FILE__ );

	if ( version_compare( $wp_version, $require_wp, '<' ) ) {
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
			$message = '<h2>';
			/* translators: %s is the name of the plugin. */
			$message .= sprintf( _x( 'Unable to load %s', 'installation', 'strong-testimonials' ), $plugin_info['Name'] );
			$message .= '</h2>';
			/* translators: %s is a WordPress version number. */
			$message .= '<p>' . sprintf( _x( 'This plugin requires <strong>WordPress %s</strong> or higher so it has been deactivated.', 'installation', 'strong-testimonials' ), $require_wp ) . '<p>';
			$message .= '<p>' . _x( 'Please upgrade WordPress and try again.', 'installation', 'strong-testimonials' ) . '<p>';
			$message .= '<p>' . sprintf( _x( 'Back to the WordPress <a href="%s">Plugins page</a>', 'installation', 'strong-testimonials' ), get_admin_url( null, 'plugins.php' ) ) . '<p>';
			wp_die( $message );
		}
	}
}


/**
 * [Add Recipient] Ajax receiver
 */
function wpmtst_add_recipient_function() {
	$key = $_REQUEST['key'];
	$form_options = get_option( 'wpmtst_form_options' );
	$recipient = $form_options['default_recipient'];
	include WPMTST_INC . 'admin/settings/recipient.php';
	die();
}
add_action( 'wp_ajax_wpmtst_add_recipient', 'wpmtst_add_recipient_function' );


/**
 * Admin notices
 *
 * @since 1.18.4
 */
function wpmtst_admin_notices() {
	if ( $notices = get_option( 'wpmtst_admin_notices' ) ) {
		foreach ( $notices as $notice ) {
			echo "<div class='wpmtst updated notice is-dismissible'><p>$notice</p></div>";
		}
		?>
		<script>
		jQuery(document).ready(function($) {
			$(".wrap").on("click", ".notice-dismiss", function() {
				$.get(ajaxurl,{'action':'wpmtst_dismiss_notice'},function(response){})
			}).on("click", ".notice-dismiss-text", function() {
				$(this).closest(".notice").find(".notice-dismiss").click();
			});
		});
		</script>
	<?php
	}
}
add_action( 'admin_notices', 'wpmtst_admin_notices' );


/**
 * Dismiss admin notices
 *
 * @since 1.18.4
 */
function wpmtst_dismiss_notice() {
	if ( isset( $_REQUEST['action'] ) && 'wpmtst_dismiss_notice' == $_REQUEST['action'] ) {
		delete_option( 'wpmtst_admin_notices' );
		die;
	}
}
add_action( 'wp_ajax_wpmtst_dismiss_notice', 'wpmtst_dismiss_notice' );


function wpmtst_countdown() {
	$datetime1 = new DateTime();
	$datetime2 = new DateTime('2016-02-02');
	$interval = $datetime1->diff($datetime2);
	if ( $interval->invert )
		echo 'in the next update';
	else
		echo $interval->format('in %a days');
	die();
}
add_action( 'wp_ajax_wpmtst_countdown', 'wpmtst_countdown' );


function wpmtst_get_background_preset_colors() {
	$preset = WPMST()->get_background_presets( $_REQUEST['key'] );
	echo json_encode( $preset );
	die();
}
add_action( 'wp_ajax_wpmtst_get_background_preset_colors', 'wpmtst_get_background_preset_colors' );
