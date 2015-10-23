<?php

/**
 * Admin List Table
 *
 * @version 0.1.0
 */

class Strong_Views_List_Table extends Strong_Testimonials_List_Table {

	public function prepare_list( $data = array() ) {
		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}

	public function get_columns() {
		$columns = array(
			//'id'   => 'View ID',
			'name' => 'Name',
			'mode' => 'Mode',
			'template' => 'Template',
			'shortcode' => 'Shortcode',
		);

		return $columns;
	}

	public function get_hidden_columns() {
		return array();
	}

	public function get_sortable_columns() {
		return array(
			// 'post_author' => array( 'post_author', false ),
		);
	}

	public function column_name( $item ) {
		$screen = get_current_screen();
		$url = $screen->parent_file;
		$edit_link = $url . '&page=views&action=edit&id=' . $item['id'];
		echo '<strong><a href="' . $edit_link . '">' . $item['name'] . '</a></strong>';

		$delete_link = 'admin.php?action=delete-strong-view&id=' . $item['id'];

		$actions = array();
		$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
		$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( $delete_link, 'delete-strong-view_' . $item['id'] ) . "' onclick=\"if ( confirm( '" . esc_js( sprintf( __( "You are about to delete this view '%s'\n  'Cancel' to stop, 'OK' to delete." ), $item['name'] ) ) . "' ) ) { return true;}return false;\">" . __( 'Delete' ) . "</a>";
		echo $this->row_actions( $actions );
	}

	public function column_default( $item, $column_name ) {
		$this->find_template();
		switch ( $column_name ) {
			//case 'id':
			//	return $item[ $column_name ];
			case 'name':
				return $item[ $column_name ];
			case 'mode':
				return $item['data'][ $column_name ];
			case 'template':
				return $this->find_template( $item['data'][ $column_name ] );
			case 'shortcode':
				return "[testimonial_view id={$item['id']}]";
			default:
				return print_r( $item, true );
		}
	}
	
	public function find_template( $template = '' ) {
		if ( ! $template ) {
			return 'plugin > Default';
		}
		
		$template_file = '';

		if ( '.php' != substr( $template, - 4 ) ) {

			/**
			 * If not full filename, use native function to search
			 * in child/parent theme first and allow filtering.
			 */

			$search_array = array();
			if ( $template ) {
				$search_array[] = "testimonials-{$template}.php";
			}
			$search_array[] = 'testimonials.php';

			$template_file = get_query_template( 'testimonials', $search_array );

		} else {

			/**
			 * If full file name, search in plugin.
			 * File name includes path relative to plugin's template directory.
			 */

			// To include add-on templates:
			$paths = apply_filters( 'wpmtst_template_paths', array( WPMTST_TPL ) );

			foreach ( $paths as $path ) {
				if ( file_exists( $path . $template ) ) {
					$template_file = $path . $template;
					break;
				}
			}

		}

		if ( ! $template_file ) {
			return 'plugin > Default';
		}
		
		$template_file = str_replace( array( WPMTST_TPL, trailingslashit( get_stylesheet_directory() ) ), array( '', '' ), $template_file );

		$theme_templates  = wpmtst_get_theme_templates( 'testimonials' ) + wpmtst_get_theme_templates( 'testimonial-form' );
		$plugin_templates = wpmtst_get_plugin_templates( 'testimonials' ) + wpmtst_get_plugin_templates( 'testimonial-form' );
		
		// Get template name. Search theme first.
		$name = array_search( $template_file, $theme_templates );
		$location = 'theme';
		if ( ! $name ) {
			$name = array_search( $template_file, $plugin_templates );
			$location = 'plugin';
		}

		return "$location > $name";
	}

}
