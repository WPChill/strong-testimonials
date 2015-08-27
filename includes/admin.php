<?php
/**
 * Strong Testimonials - Admin functions
 */


/*
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


/*
 * Admin scripts.
 */
function wpmtst_admin_scripts( $hook ) {

	$hooks_to_style = array( 
			'wpm-testimonial_page_settings',
			'wpm-testimonial_page_fields',
			'wpm-testimonial_page_shortcodes',
			'wpm-testimonial_page_guide',
			'wpm-testimonial_page_news',
			'widgets.php',
	);
	
	$screen = get_current_screen();
	if ( $screen && 'wpm-testimonial' == $screen->post_type ) {
		$hooks_to_style[] = 'edit.php';
		$hooks_to_style[] = 'edit-tags.php';
		$hooks_to_style[] = 'post.php';
		$hooks_to_style[] = 'post-new.php';
	}
	
	// Page Builder compat
	if ( in_array( $hook, $hooks_to_style ) || defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		wp_enqueue_style( 'wpmtst-admin-style', WPMTST_URL . 'css/wpmtst-admin.css' );
	}	
	
	if ( 'wpm-testimonial_page_fields' == $hook ) {
		wp_enqueue_style( 'wpmtst-admin-fields-style', WPMTST_URL . 'css/wpmtst-admin-fields.css' );
	}

	if ( 'wpm-testimonial_page_guide' == $hook ) {
		wp_enqueue_style( 'wpmtst-admin-guide-style', WPMTST_URL . 'css/wpmtst-admin-guide.css' );
	}

	$hooks_to_script = array( 
			'wpm-testimonial_page_settings',
			'wpm-testimonial_page_fields',
			'wpm-testimonial_page_shortcodes',
			'widgets.php',
	);
			
	// Page Builder compat
	if ( in_array( $hook, $hooks_to_script ) || defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		// @todo  is loading validate this necessary? if so, language file too?
		wp_enqueue_script( 'wpmtst-validation-plugin', WPMTST_URL . 'js/validate/jquery.validate.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'wpmtst-admin-script', WPMTST_URL . 'js/wpmtst-admin.js', array( 'jquery' ) );
		wp_localize_script( 'wpmtst-admin-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}
	
}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_scripts' );


function wpmtst_admin_dequeue_scripts() {
	if ( wp_style_is( 'CPTStyleSheets' ) )
		wp_dequeue_style( 'CPTStyleSheets' );
}
add_action( 'admin_enqueue_scripts', 'wpmtst_admin_dequeue_scripts', 500 );


/*
 * Add meta box to the post editor screen and place above Custom Fields
 */
function wpmtst_add_meta_boxes() {
	add_meta_box( 'details', _x( 'Client Details', 'post editor', 'strong-testimonials' ), 'wpmtst_meta_options', 'wpm-testimonial', 'normal', 'core' );
}
add_action( 'add_meta_boxes_wpm-testimonial', 'wpmtst_add_meta_boxes' );

function wpmtst_reorder_meta_boxes() {
	global $wp_meta_boxes;

	if ( ! isset( $wp_meta_boxes['wpm-testimonial'] ) )
		return;

	if ( ! isset( $wp_meta_boxes['wpm-testimonial']['normal'] ) )
		return;
	
	if ( ! isset( $wp_meta_boxes['wpm-testimonial']['normal']['core'] ) )
		return;
		
	$core = $wp_meta_boxes['wpm-testimonial']['normal']['core'];
	$newcore = array();
	if ( $core['postexcerpt'] )
		$newcore['postexcerpt'] = $core['postexcerpt'];
	if ( $core['details'] )
		$newcore['details'] = $core['details'];
	if ( $core['postcustom'] )
		$newcore['postcustom'] = $core['postcustom'];
	if ( $core['slugdiv'] )
		$newcore['slugdiv'] = $core['slugdiv'];
		
	if ( $newcore ) 
		$wp_meta_boxes['wpm-testimonial']['normal']['core'] = $newcore;
}
add_action( 'do_meta_boxes', 'wpmtst_reorder_meta_boxes' );


/*
 * Add custom fields to the testimonial editor
 */
function wpmtst_meta_options() {
	global $post;
	$post = wpmtst_get_post( $post );
	$options = get_option( 'wpmtst_options' );
	$fields = get_option( 'wpmtst_fields' );
	$field_groups = $fields['field_groups'];
	?>
	<table class="options">
		<tr>
			<td colspan="2"><?php _ex( 'To add a client\'s photo, use the Featured Image option.', 'post editor', 'strong-testimonials' ); ?>&nbsp;<div class="dashicons dashicons-arrow-right-alt"></div></td>
		</tr>
		<?php foreach ( $field_groups[ $fields['current_field_group'] ]['fields'] as $key => $field ) : ?>
		<?php if ( 'custom' == $field['record_type'] ) : ?>
		<tr>
			<th><label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label></td>
			<td>
				<?php echo sprintf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" size="" />', $field['input_type'], $field['name'], $post->$field['name'] ); ?>
				<?php
				/*
				 * Add rel="nofollow" to outbound links.
				 *
				 * @since 1.11.0
				 */
				 ?>
				<?php if ( 'url' == $field['input_type'] ) : ?>
					&nbsp;&nbsp;<label><input type="checkbox" name="custom[nofollow]" <?php checked( $post->nofollow, 'on' ); ?> /> <code>rel="nofollow"</code></label>
				<?php endif; ?>
			</td>
		</tr>
		<?php endif; ?>
		<?php endforeach; ?>
	</table>
	<?php
}


/**
 * Add custom columns to the admin screen
 */
function wpmtst_edit_columns( $columns ) {
	$options = get_option( 'wpmtst_options' );
	$fields  = get_option( 'wpmtst_fields' );
	$fields  = $fields['field_groups'][ $fields['current_field_group'] ]['fields'];
	
	$columns = array( 'cb' => '<input type="checkbox" />'	);
	
	/**
	 * Menu order
	 *
	 * @since 1.16.0
	 */
	if ( $options['reorder'] && !wpmtst_is_column_sorted() && !wpmtst_is_viewing_trash() ) {
		$columns['order'] = __( 'Order', 'strong-testimonials' );
	}
	
	$columns['title'] = _x( 'Title', 'testimonial', 'strong-testimonials' );
	
	$columns['post_excerpt'] = __( 'Excerpt', 'strong-testimonials' );
	
	foreach ( $fields as $key => $field ) {
		if ( $field['admin_table'] ) {
			if ( 'featured_image' == $field['name'] )
				$columns['thumbnail'] = __( 'Thumbnail', 'strong-testimonials' );
			elseif ( 'post_title' == $field['name'] )
				continue; // is set above
			else
				$columns[ $field['name'] ] = $field['label'];
		}
	}
	
	$columns['category']  = __( 'Category', 'strong-testimonials' );
	
	$columns['shortcode'] = __( 'Shortcode', 'strong-testimonials' );
	
	$columns['date']      = __( 'Date', 'strong-testimonials' );

	return $columns;
}
add_filter( 'manage_edit-wpm-testimonial_columns', 'wpmtst_edit_columns' );


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


/*
 * Show custom values
 */
function wpmtst_custom_columns( $column ) {
	global $post;
	$custom  = get_post_custom();
	$options = get_option( 'wpmtst_options' );

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
			echo $post->post_thumbnail;
			break;
		
		case 'shortcode':
			echo '[strong id="' . $post->ID . '" ]';
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
		
		/**
		 * Menu order.
		 *
		 * @since 1.16.0
		 */
		case 'order':
			if ( current_user_can( 'edit_post', $post->ID ) 
					&& $options['reorder'] 
					&& !wpmtst_is_column_sorted() 
					&& !wpmtst_is_viewing_trash() ) {
						
				echo '<div class="handle">';
				echo '<div class="menu-order">' . $post->menu_order . '</div>';
				// echo '<div class="help" style="display: none;">reorder</div>';
				echo '<div class="help">reorder</div>';
				echo '<div class="help-in-motion">drag and drop</div>';
				// echo '<div class="dashicons dashicons-menu"></div>';
				echo '</div>';
			}
			break;
			
		default:
			// custom field?
			if ( isset( $custom[$column] ) )
				echo $custom[$column][0];
		
	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_custom_columns' );


/*
 * Add thumbnail column to admin list
 */
function wpmtst_add_thumbnail_column( $columns ) {
	$columns['thumbnail'] = __( 'Thumbnail', 'strong-testimonials' );
	return $columns;
}
add_filter( 'manage_wpm-testimonial_posts_columns', 'wpmtst_add_thumbnail_column' );


/*
 * Show thumbnail in admin list
 */
function wpmtst_add_thumbnail_value( $column_name, $post_id ) {
	if ( 'thumbnail' == $column_name ) {
		$width  = (int) 75;
		$height = (int) 75;

		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		$attachments = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );

		if ( $thumbnail_id ) {
			$thumb = wp_get_attachment_image( $thumbnail_id, array( $width, $height ), true );
		}
		elseif ( $attachments ) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array( $width, $height ), true );
			}
		}

		if ( isset( $thumb ) && $thumb )
			echo $thumb;
		else
			echo __( 'None', 'strong-testimonials' );
	}
}
add_action( 'manage_wpm-testimonial_posts_custom_column', 'wpmtst_add_thumbnail_value', 10, 2 );


/*
 * Add columns to the testimonials categories screen
 */
function wpmtst_manage_categories( $columns ) {
	$new_columns = array(
			'cb'        => '<input type="checkbox" />',
			'ID'        => __( 'ID', 'strong-testimonials' ),
			'name'      => _x( 'Name', 'noun', 'strong-testimonials' ),
			'slug'      => __( 'Slug', 'strong-testimonials' ),
			'shortcode' => __( 'Shortcode', 'strong-testimonials' ),
			'posts'     => __( 'Posts', 'strong-testimonials' )
	);
	return $new_columns;
}
add_filter( 'manage_edit-wpm-testimonial-category_columns', 'wpmtst_manage_categories');


/*
 * Show custom column
 */
function wpmtst_manage_columns( $out, $column_name, $id ) {
	if ( 'shortcode' == $column_name )
		$output = '[strong category="' . $id . '" ]';
	elseif ( 'ID' == $column_name )
		$output = $id;
	else
		$output = '';

	return $output;
}
add_filter( 'manage_wpm-testimonial-category_custom_column', 'wpmtst_manage_columns', 10, 3 );


/*
 * Make columns sortable.
 *
 * @since 1.12.0
 */
function wpmtst_manage_sortable_columns( $columns ) {
	$columns['client_name'] = 'client_name';
	$columns['date'] = 'date';
	return $columns;
}
add_filter( 'manage_edit-wpm-testimonial_sortable_columns', 'wpmtst_manage_sortable_columns' );


/*
 * Sort columns.
 *
 * @since 1.12.0
 */
function wpmtst_pre_get_posts( $query ) {
	// Only in main WP query AND if an orderby query variable is designated.
	if ( is_admin() 
				&& $query->is_main_query() 
				&& 'wpm-testimonial' == $query->get( 'post_type' )
				&& ( $orderby = $query->get( 'orderby' ) ) ) {
					
		if ( 'client_name' == $orderby ) {
			$query->set( 'meta_key', 'client_name' );
			$query->set( 'orderby', 'meta_value' );
		}
		
	}
}
add_action( 'pre_get_posts', 'wpmtst_pre_get_posts', 10 );


/**
 * Add order to default sort to allow manual ordering.
 *
 * @since 1.16.0
 */
function wpmtst_posts_orderby( $orderby, $query ) {
	if ( !$query->get( 'orderby' ) ) {
		global $wpdb;
		$orderby = "{$wpdb->posts}.menu_order, {$wpdb->posts}.post_date DESC";
		/*
		 * Store this in query. See notes in class-strong-testimonials-order.php.
		 */
		$query->set( 'original_orderby', $orderby );
	}
	return $orderby;
}
add_filter( 'posts_orderby', 'wpmtst_posts_orderby', 10, 2 );


/*
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


/*
 * [Add Recipient] Ajax receiver
 */
function wpmtst_add_recipient_function() {
	$key = $_REQUEST['key'];
	$form_options = get_option( 'wpmtst_form_options' );
	$recipient = $form_options['default_recipient'];
	include WPMTST_INC . 'form-form-settings-recipient.php';
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
