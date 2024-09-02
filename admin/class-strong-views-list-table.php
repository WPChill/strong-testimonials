<?php
/**
 * Admin List Table
 *
 * @version 0.2.1
 */

class Strong_Views_List_Table extends Strong_Testimonials_List_Table {

	public $stickies;

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since 0.2.1
	 * @access public
	 */
	public function no_items() {
		esc_html_e( 'No views found.', 'strong-testimonials' );
	}

	public function prepare_list( $data = array() ) {
		$this->stickies = get_option( 'wpmtst_sticky_views', array() );

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Sort the list
		if ( isset( $_GET['orderby'] ) ) {
			usort( $data, array( &$this, 'usort_reorder' ) );
		}
		$data = $this->move_sticky( $data );
		$data = apply_filters( 'wpmtst_list_views', $data );
		if ( isset( $_GET['mode'] ) && ! empty( $_GET['mode'] ) && 'all' !== $_GET['mode'] ) {
			$data = $this->filter_data( sanitize_text_field( wp_unslash( $_GET['mode'] ) ), $data );
		}
		if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
			$data = $this->search_data( sanitize_text_field( wp_unslash( $_GET['s'] ) ), $data );
		}
		$this->items = $data;
	}

	public function prepare_filters( $data = array() ) {
		$links = array();
		foreach ( $data as $item ) {
			$value                     = unserialize( $item['value'] );
			$links[ $value['mode'] ][] = $item;
		}
		return $links;
	}

	public function filter_data( $mode, $data = array() ) {
		$items = array();
		foreach ( $data as $item ) {
			if ( $mode === $item['data']['mode'] ) {
				$items[] = $item;
			}
		}
		return $items;
	}

	public function search_data( $search, $data = array() ) {
		$items = array();
		foreach ( $data as $item ) {
			if ( strtolower( $search ) === strtolower( $item['name'] ) ) {
				$items[] = $item;
			}
		}
		return $items;
	}

	/**
	 * Move sticky views to the top
	 *
	 * @param $data
	 * @since 0.2.0
	 * @return array
	 */
	public function move_sticky( $data ) {
		$sticky_views = array();
		$views        = array();
		foreach ( $data as $view ) {
			if ( in_array( $view['id'], $this->stickies, true ) ) {
				$sticky_views[] = $view;
			} else {
				$views[] = $view;
			}
		}

		return array_merge( $sticky_views, $views );
	}

	public function get_columns() {
		$columns = array(
			'id'        => esc_html__( 'ID', 'strong-testimonials' ),
			//'sticky'    => __( 'Sticky', 'strong-testimonials' ),
			'sticky'    => '',
			'name'      => esc_html__( 'Name', 'strong-testimonials' ),
			'mode'      => esc_html__( 'Mode', 'strong-testimonials' ),
			'template'  => esc_html__( 'Template', 'strong-testimonials' ),
			'shortcode' => esc_html__( 'Shortcode', 'strong-testimonials' ),
		);

		return $columns;
	}

	public function get_hidden_columns() {
		return array();
	}

	public function get_sortable_columns() {
		return array(
			'id'   => array( 'id', false ),
			'name' => array( 'name', false ),
		);
	}

	public function usort_reorder( $a, $b ) {
		// If no sort, default to name
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'name';

		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'asc';

		// Determine sort order
		if ( 'id' === $orderby ) {
			$result = $this->cmp( intval( $a[ $orderby ] ), intval( $b[ $orderby ] ) );
		} else {
			$result = strcasecmp( $a[ $orderby ], $b[ $orderby ] );
		}

		// Send final sort direction to usort
		return ( 'asc' === $order ) ? $result : -$result;
	}

	public function cmp( $a, $b ) {
		if ( (int) $a === (int) $b ) {
			return 0;
		}

		return ( $a < $b ) ? -1 : 1;
	}

	public function column_name( $item ) {
		$screen = get_current_screen();
		$url    = $screen->parent_file;

		// Edit link
		$edit_link = esc_url( $url . '&page=testimonial-views&action=edit&id=' . $item['id'] );
		echo '<a class="row-title" href="' . esc_url( $edit_link ) . '">' . esc_html( $item['name'] ) . '</a>';

		// Duplicate link
		// @since 2.1.0
		$duplicate_link = esc_url( $url . '&page=testimonial-views&action=duplicate&id=' . $item['id'] );

		// Delete link
		$delete_link = 'admin.php?action=delete-strong-view&id=' . $item['id'];

		// Assemble links

		$actions              = array();
		$actions['edit']      = '<a href="' . esc_url( $edit_link ) . '">' . esc_html__( 'Edit', 'strong-testimonials' ) . '</a>';
		$actions['duplicate'] = '<a href="' . esc_url( $duplicate_link ) . '">' . esc_html__( 'Duplicate', 'strong-testimonials' ) . '</a>';
		// translators: %s is the name of the view that is going to be deleted.
		$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( $delete_link, 'delete-strong-view_' . $item['id'] ) . "' onclick=\"if ( confirm( '" . esc_js( sprintf( __( 'Delete "%s"?', 'strong-testimonials' ), $item['name'] ) ) . "' ) ) { return true;} return false;\">" . esc_html__( 'Delete', 'strong-testimonials' ) . '</a>';

		$actions = apply_filters( 'wpmtst_views_actions', $actions, $item );

		echo wp_kses_post( $this->row_actions( $actions ) );
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
				$text = $item['id'];
				break;
			case 'sticky':
				$stuck = $this->is_stuck( $item['id'] ) ? 'stuck' : '';
				$text  = '<a href="#" class="stickit ' . $stuck . '" title="' . esc_html__( 'stick to top of list', 'strong-testimonials' ) . '"></>';
				break;
			case 'name':
				$text = $item['name'];
				break;
			case 'mode':
				$mode         = $item['data']['mode'];
				$text         = $mode;
				$view_options = apply_filters( 'wpmtst_view_options', get_option( 'wpmtst_view_options' ) );
				if ( isset( $view_options['mode'][ $mode ]['label'] ) ) {
					$text = $view_options['mode'][ $mode ]['label'];
				}
				break;
			case 'template':
				if ( 'single_template' === $item['data']['mode'] ) {
					$text = esc_html__( 'theme single post template', 'strong-testimonials' );
				} else {
					$text = $this->find_template( array( 'template' => $item['data']['template'] ) );
				}
				break;
			case 'shortcode':
				if ( 'single_template' === $item['data']['mode'] ) {
					$text = '';
				} else {
					$text = '[testimonial_view id="' . $item['id'] . '"]';
				}
				break;
			default:
				$text = print_r( $item, true );
		}

		return apply_filters( "wpmtst_view_list_column_$column_name", $text, $item );
	}

	public function is_stuck( $id ) {
		$stickies = get_option( 'wpmtst_sticky_views', array() );
		return ( $stickies && in_array( $id, $stickies, true ) );
	}

	public function find_template( $atts = '' ) {
		$name = WPMST()->templates->get_template_config( $atts, 'name', false );
		return $name ? $name : '<span class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'not found', 'strong-testimonials' ) . '</span>';
	}

	/**
	 * Display the table
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function display() {
			$singular = $this->_args['singular'];
			// Disabling the table nav options to regain some real estate.
			//$this->display_tablenav( 'top' );
			$s = '';
		if ( isset( $_GET['s'] ) ) {
			$s = sanitize_text_field( wp_unslash( $_GET['s'] ) );
		}
		?>
			<form id="posts-filter" method="get">
				<p class="search-box">
					<label class="screen-reader-text" for="post-search-input"><?php esc_html_e( 'Search', 'strong-testimonials' ); ?></label>
					<input type="search" id="post-search-input" name="s" value="<?php echo esc_attr( $s ); ?>">
					<input type="submit" id="search-submit" class="button" value="<?php esc_html_e( 'Search', 'strong-testimonials' ); ?>">
					<input type="hidden" name="post_type" class="post_type_page" value="wpm-testimonial">
					<input type="hidden" name="page" value="testimonial-views">
				</p>
					<table class="wp-list-table <?php echo esc_attr( implode( ' ', $this->get_table_classes() ) ); ?>">
						<thead>
						<tr>
							<?php $this->print_column_headers(); ?>
						</tr>
						</thead>

						<tbody id="the-list"
						<?php
						if ( $singular ) {
							echo " data-wp-lists='list:" . esc_attr( $singular ) . "'";
						}
						?>
						>
						<?php $this->display_rows_or_placeholder(); ?>
						</tbody>

						<tfoot>
						<tr>
							<?php $this->print_column_headers( false ); ?>
						</tr>
						</tfoot>

					</table>
					<?php
					//$this->display_tablenav( 'bottom' );
					?>
			</form>
		<?php
	}
}
