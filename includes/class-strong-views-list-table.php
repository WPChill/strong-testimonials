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
			'name'      => 'Name',
			'mode'      => 'Mode',
			'template'  => 'Template',
			'shortcode' => 'Shortcode',
		);

		return $columns;
	}

	public function get_hidden_columns() {
		return array();
	}

	public function get_sortable_columns() {
		return array(
			'name'      => array( 'name', false ),
			'mode'      => array( 'mode', false ),
			'template'  => array( 'template', false ),
			'shortcode' => array( 'shortcode', false ),
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
		switch ( $column_name ) {
			case 'name':
				return $item[ $column_name ];
			case 'mode':
				return $item['data'][ $column_name ];
			case 'template':
				return $this->find_template( array( 'template' => $item['data'][ $column_name ] ) );
			case 'shortcode':
				return "[testimonial_view id={$item['id']}]";
			default:
				return print_r( $item, true );
		}
	}

	public function find_template( $atts = '' ) {
		global $strong_templates;
		$name = $strong_templates->get_template_attr( $atts, 'name', false );
		return $name ? $name : '<span class="error"><span class="dashicons dashicons-warning"></span> not found</span>';
	}

}
