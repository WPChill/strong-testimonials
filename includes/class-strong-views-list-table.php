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
		if ( isset( $_GET['orderby'] ) ) {
			usort( $data, array( &$this, 'usort_reorder' ) );
		}
		$this->items = $data;
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
			'name' => array( 'name', false ),
		);
	}

	public function usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';

		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';

		// Determine sort order
		$result = strcasecmp( $a[$orderby], $b[$orderby] );

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}

	public function column_name( $item ) {
		$screen = get_current_screen();
		$url    = $screen->parent_file;

		// Edit link
		$edit_link = $url . '&page=testimonial-views&action=edit&id=' . $item['id'];
		echo '<a class="row-title" href="' . $edit_link . '">' . $item['name'] . '</a>';

		// Duplicate link
		// @since 2.1.0
		$duplicate_link = $url . '&page=testimonial-views&action=duplicate&id=' . $item['id'];

		// Delete link
		$delete_link = 'admin.php?action=delete-strong-view&id=' . $item['id'];

		// Assemble links
		$actions = array();
		$actions['edit']      = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
		$actions['duplicate'] = '<a href="' . $duplicate_link . '">' . __( 'Duplicate' ) . '</a>';
		$actions['delete']    = "<a class='submitdelete' href='" . wp_nonce_url( $delete_link, 'delete-strong-view_' . $item['id'] ) . "' onclick=\"if ( confirm( '" . esc_js( sprintf( __( "Delete \"%s\"?" ), $item['name'] ) ) . "' ) ) { return true;} return false;\">" . __( 'Delete' ) . "</a>";

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
