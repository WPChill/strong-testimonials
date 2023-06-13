<?php

/**
 * Class Strong_Testimonials_Helper
 *
 * @since 3.0.3
 */
class Strong_Testimonials_Helper {

	/**
	 * Our Class variables
	 *
	 * @since 2.51.5
	 */
	public $field;
	public $action;
	public $view_id;
	public $view_options;
	public $cat_count = false;
	public $show_section;
	public $view;
	public $view_name;
	public $view_cats_array;
	public $sections;
	public $current_mode;
	public $current_type;
	public $isSetting;

	/**
	 * Strong_Testimonials_Helper constructor.
	 *
	 * @since 2.51.5
	 */
	public function __construct() {

		$this->action       = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$this->view_id      = absint( filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT ) );
		$this->view_options = apply_filters( 'wpmtst_view_options', get_option( 'wpmtst_view_options' ) );
		$this->cat_count    = wpmtst_get_cat_count();
	}

	/**
	 * Set Strong Testimonial view
	 *
	 * @since 2.51.5
	 */
	public function set_view() {

		$this->view         = $this->get_view();
		$this->show_section = apply_filters( 'wpmtst_show_section', $this->view['mode'] );
		if ( 'edit' == $this->action ) {
			$view_array      = wpmtst_get_view( $this->view_id );
			$this->view      = unserialize( $view_array['value'] );
			$this->view_name = $view_array['name'];
		} elseif ( 'duplicate' == $this->action ) {
			$view_array      = wpmtst_get_view( $this->view_id );
			$this->view      = unserialize( $view_array['value'] );
			$this->view_id   = 0;
			$this->view_name = $view_array['name'] . ' - COPY';
		} else {
			$this->view_id   = 1;
			$this->view      = wpmtst_get_view_default();
			$this->view_name = 'new';
		}
		$this->view_cats_array = apply_filters( 'wpmtst_l10n_cats', explode( ',', $this->view['category'] ) );
		$this->sections        = $this->get_sections();
	}

	/**
	 * Get Strong Testimonial view
	 *
	 * @return array|mixed
	 *
	 * @since 2.51.5
	 */
	public static function get_view() {

		$view = wpmtst_get_view_default();
		if ( isset( $_REQUEST['action'] ) ) {
			$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$id     = absint( filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT ) );
			if ( 'edit' == $action || 'duplicate' == $action ) {
				$view_array = wpmtst_get_view( $id );
				if ( isset( $view_array['value'] ) ) {
					$view = unserialize( $view_array['value'] );
				}
			}
		}

		return $view;
	}

	/**
	 * Get Strong Testimonials sections
	 *
	 * @return mixed|void
	 *
	 * @since 2.51.5
	 */
	public function get_sections() {
		return apply_filters( 'wpmtst_view_sections', array(
				'query'  => array(
						'section_action_before' => 'wpmtst_view_editor_before_group_select',
						'section_action_after'  => 'wpmtst_view_editor_after_group_select',
						'fields_action_before'  => '',
						'fields_action_after'   => array(
								'action' => 'wpmtst_views_group_query',
								'param'  => $this->view
						),
						'classes'               => array(
								'then',
								'then_display',
								'then_not_form',
								'then_slideshow',
								'then_not_single_template'
						),
						'title'                 => esc_html__( 'Query', 'strong-testimonials' ),
						'table_classes'         => 'form-table multiple group-select',
						'subheading'            => array(
								array(
										'title'   => esc_html__( 'Option', 'strong-testimonials' ),
										'classes' => '',
										'colspan' => 1,
										'after'   => ''
								),
								array(
										'title'   => esc_html__( 'Settings', 'strong-testimonials' ),
										'classes' => '',
										'colspan' => 1,
										'after'   => ''
								),
								array(
										'title'   => esc_html__( 'or Shortcode Attribute', 'strong-testimonials' ),
										'classes' => 'divider',
										'colspan' => 2,
										'after'   => '<span class="help-links"><span class="description"><a href="#tab-panel-wpmtst-help-shortcode" class="open-help-tab">' . __( 'Help', 'strong-testimonials' ) . '</a></span></span>'
								),
								array(
										'title'   => esc_html__( 'Example', 'strong-testimonials' ),
										'classes' => '',
										'colspan' => 1,
										'after'   => ''
								)
						),
						'fields'                => array(
								'field_select'   => array(
										'label'               => esc_html_x( 'Select', 'verb', 'strong-testimonials' ),
										'type'                => 'select',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-single_or_multiple',
										'container_classes'   => 'then then_display then_slideshow then_not_form',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_category' => array(
										'label'               => esc_html__( 'Categories', 'strong-testimonials' ),
										'type'                => 'category',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-category-select',
										'container_classes'   => 'then then_display then_slideshow then_not_form',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_order'    => array(
										'label'               => esc_html_x( 'Order', 'noun', 'strong-testimonials' ),
										'type'                => 'order',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-order',
										'container_classes'   => 'then then_display then_slideshow then_not_form',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_limit'    => array(
										'label'               => esc_html__( 'Quantity', 'strong-testimonials' ),
										'type'                => 'limit',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-all',
										'container_classes'   => 'then then_display then_slideshow then_not_form',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								)
						)
				),
				'fields' => array(
						'section_action_before' => 'wpmtst_view_editor_before_group_fields',
						'section_action_after'  => '',
						'fields_action_before'  => '',
						'fields_action_after'   => '',
						'classes'               => array(
								'then',
								'then_display',
								'then_not_form',
								'then_slideshow',
								'then_single_template'
						),
						'title'                 => esc_html__( 'Fields', 'strong-testimonials' ),
						'table_classes'         => 'form-table multiple group-show',
						'fields'                => array(
								'field_title'          => array(
										'label'               => esc_html__( ' Title', 'strong-testimonials' ),
										'type'                => 'title',
										'before'              => '<input type="checkbox" id="view-title" name="view[data][title]" value="1"' . checked( $this->view['title'], true, false ) . ' class="checkbox if toggle">',
										'after'               => '',
										'class'               => 'view-title',
										'container_classes'   => 'then then_display then_not_form then_slideshow then_not_single_template',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_thumbnail'      => array(
										'label'               => esc_html__( ' Featured Image', 'strong-testimonials' ),
										'type'                => 'thumbnail',
										'before'              => '<input type="checkbox" id="view-images" class="checkbox if toggle" name="view[data][thumbnail]" value="1"' . checked( $this->view['thumbnail'], true, false ) . '>',
										'after'               => '',
										'class'               => 'view-images',
										'container_classes'   => 'then then_display then_not_form then_slideshow then_not_single_template',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_content'        => array(
										'label'               => esc_html__( ' Content', 'strong-testimonials' ),
										'type'                => 'content',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-content',
										'container_classes'   => 'then then_display then_not_form then_slideshow then_not_single_template',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_client_section' => array(
										'include'             => 'option-client-section.php',
										'label'               => esc_html__( ' Custom Fields', 'strong-testimonials' ),
										'type'                => 'client-section',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_display then_not_form then_slideshow then_single_template',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								)
						)
				),

				'extra'     => array(
						'section_action_before' => 'wpmtst_view_editor_before_group_extra',
						'section_action_after'  => '',
						'fields_action_before'  => '',
						'fields_action_after'   => '',
						'classes'               => array(
								'then',
								'then_display',
								'then_not_form',
								'then_slideshow',
								'then_not_single_template'
						),
						'title'                 => esc_html__( 'Extra', 'strong-testimonials' ),
						'table_classes'         => 'form-table multiple group-layout',
						'fields'                => array(
								'field_pagination' => array(
										'label'               => esc_html__( ' Pagination', 'strong-testimonials' ),
										'type'                => 'pagination',
										'before'              => '<input class="if toggle checkbox" id="view-pagination" name="view[data][pagination]" type="checkbox" value="1"' . checked( $this->view['pagination'], true, false ) . '/>',
										'after'               => '',
										'class'               => 'view-pagination',
										'container_classes'   => 'then then_display then_not_form then_not_slideshow then_not_single then_multiple',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_read_more'  => array(
										'include'             => 'option-read-more-page.php',
										'label'               => esc_html__( ' "Read more" link to a page or post', 'strong-testimonials' ),
										'type'                => 'read-more-page',
										'before'              => '<div class="checkbox"><input type="checkbox" id="view-more_page" class="if toggle" name="view[data][more_page]" value="1"' . checked( isset( $this->view['more_page'] ) && $this->view['more_page'], true, false ) . ' class="checkbox">',
										'after'               => '</div>',
										'class'               => 'view-more_page',
										'container_classes'   => 'then then_display then_not_form then_slideshow read-more',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
						)
				),
				'slideshow' => array(
						'section_action_before' => 'wpmtst_view_editor_before_group_slideshow',
						'section_action_after'  => '',
						'fields_action_before'  => '',
						'fields_action_after'   => '',
						'classes'               => array(
								'then',
								'then_not_display',
								'then_not_form',
								'then_slideshow',
								'then_not_single_template'
						),
						'title'                 => esc_html__( 'Slideshow', 'strong-testimonials' ),
						'table_classes'         => 'form-table multiple group-select',
						'fields'                => array(
								'field_slideshow_num'        => array(
										'label'               => esc_html__( 'Show', 'strong-testimonials' ),
										'type'                => 'slideshow-num',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_slideshow',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_slideshow_transition' => array(
										'include'             => 'option-slideshow-transition.php',
										'label'               => esc_html__( 'Transition', 'strong-testimonials' ),
										'type'                => 'slideshow-transition',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_slideshow',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_slideshow_behavior'   => array(
										'label'               => esc_html__( 'Behavior', 'strong-testimonials' ),
										'type'                => 'slideshow-behavior',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_slideshow',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_slideshow_navigation' => array(
										'label'               => esc_html__( 'Navigation', 'strong-testimonials' ),
										'type'                => 'slideshow-navigation',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-slideshow_nav',
										'container_classes'   => 'then then_slideshow',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								)
						)
				),

				'form' => array(
						'section_action_before' => 'wpmtst_view_editor_before_group_form',
						'section_action_after'  => '',
						'fields_action_before'  => '',
						'fields_action_after'   => '',
						'classes'               => array(
								'then',
								'then_not_display',
								'then_not_slideshow',
								'then_form',
								'then_not_single_template'
						),
						'title'                 => esc_html__( 'Actions', 'strong-testimonials' ),
						'table_classes'         => 'form-table multiple group-select',
						'fields'                => array(
								'field_form_category' => array(
										'label'               => esc_html__( 'Assign to a category', 'strong-testimonials' ),
										'type'                => 'form-category',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_form',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_form_ajax'     => array(
										'label'               => esc_html__( ' Submit form without reloading the page (Ajax)', 'strong-testimonials' ),
										'type'                => 'form-ajax',
										'before'              => '<input type="checkbox" id="view-form_ajax" class="checkbox if toggle" name="view[data][form_ajax]" value="1"' . checked( $this->view['form_ajax'], true, false ) . '>',
										'after'               => '',
										'class'               => 'view-form_ajax',
										'container_classes'   => 'then then_form',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
						)
				),

				'style' => array(
						'section_action_before' => 'wpmtst_view_editor_before_group_style',
						'section_action_after'  => 'wpmtst_after_style_view_section',
						'fields_action_before'  => '',
						'fields_action_after'   => array(
								'action' => 'wpmtst_view_editor_after_style_section',
								'param'  => ''
						),
						'classes'               => array(
								'then',
								'then_display',
								'then_form',
								'then_slideshow',
								'then_not_single_template'
						),
						'title'                 => esc_html__( 'Style', 'strong-testimonials' ),
						'table_classes'         => 'form-table multiple group-style',
						'fields'                => array(
								'field_template_list_display' => array(
										'label'               => esc_html__( 'Template', 'strong-testimonials' ),
										'type'                => 'template-list-display',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_display then_not_form then_slideshow',
										'id'                  => '',
										'field_action_before' => 'wpmtst_view_editor_before_template_list',
										'field_action_after'  => ''
								),
								'field_template_list_form'    => array(
										'label'               => esc_html__( 'Template', 'strong-testimonials' ),
										'type'                => 'template-list-form',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_not_display then_form then_not_slideshow',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_option_layout'         => array(
										'include'             => 'option-layout.php',
										'label'               => esc_html__( 'Layout', 'strong-testimonials' ),
										'type'                => 'layout',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'container_classes'   => 'then then_display then_not_form then_not_slideshow',
										'id'                  => '',
										'field_action_before' => 'wpmtst_view_editor_before_layout',
										'field_action_after'  => ''
								),
								'field_background'            => array(
										'label'               => esc_html__( 'Background', 'strong-testimonials' ),
										'type'                => 'background',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'id'                  => 'group-style-option-background',
										'container_classes'   => 'then then_display then_form then_slideshow',
										'field_action_before' => 'wpmtst_view_editor_before_background',
										'field_action_after'  => ''
								),
								'field_color'                 => array(
										'label'               => esc_html__( 'Font Color', 'strong-testimonials' ),
										'type'                => 'color',
										'before'              => '',
										'after'               => '',
										'class'               => '',
										'id'                  => 'group-style-option-color',
										'container_classes'   => 'then then_display then_form then_slideshow',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
								'field_classes'               => array(
										'label'               => esc_html__( 'CSS Classes', 'strong-testimonials' ),
										'type'                => 'classes',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-class',
										'id'                  => '',
										'container_classes'   => 'then then_display then_form then_slideshow',
										'field_action_before' => 'wpmtst_view_editor_before_classes',
										'field_action_after'  => ''
								),
						)
				),

				'compat' => array(
						'section_action_before' => 'wpmtst_view_editor_before_group_compat',
						'section_action_after'  => '',
						'fields_action_before'  => '',
						'fields_action_after'   => '',
						'classes'               => array( 'then' ),
						'title'                 => esc_html__( 'Compatibility', 'strong-testimonials' ),
						'table_classes'         => 'form-table multiple group-general',
						'fields'                => array(
								'field_divi_builder' => array(
										'label'               => esc_html__( 'Divi Builder', 'strong-testimonials' ),
										'type'                => 'divi',
										'before'              => '',
										'after'               => '',
										'class'               => 'view-divi_builder',
										'container_classes'   => 'then then_display then_form then_slideshow then_not_single_template',
										'id'                  => '',
										'field_action_before' => '',
										'field_action_after'  => ''
								),
						)
				),
		) );
	}

	/**
	 * Render Strong Testimonials form
	 *
	 * @since 2.51.5
	 */
	public function render_form() {

		$actions = array( 'edit', 'duplicate', 'add' );

		if ( ! in_array( $this->action, $actions ) ) {
			wp_die( esc_html__( 'Invalid request. Please try again.', 'strong-testimonials' ) );
		}

		if ( ( 'edit' == $this->action || 'duplicate' == $this->action ) && ! $this->view_id ) {
			return;
		}

		$this->set_view();
		add_thickbox();

		// @todo: these don't seem to be used anywhere
		$fields     = wpmtst_get_custom_fields();
		$all_fields = wpmtst_get_all_fields();

		/**
		 * Show category filter if necessary.
		 *
		 * @since 2.2.0
		 */
		if ( $this->cat_count > 5 ) {
			wp_enqueue_script( 'wpmtst-view-category-filter-script' );
		}

		// Select default template if necessary
		if ( ! $this->view['template'] ) {
			if ( 'form' == $this->view['mode'] ) {
				$this->view['template'] = 'default-form';
			} else {
				$this->view['template'] = 'default';
			}
		}

		// Get urls
		$url  = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views' );
		$url1 = $url . '&action=add';
		$url2 = $url . '&action=duplicate&id=' . $this->view_id;

		?>
		<h1>
			<?php 'edit' == $this->action ? esc_html_e( 'Edit View', 'strong-testimonials' ) : esc_html_e( 'Add View', 'strong-testimonials' ); ?>
			<a href="<?php echo esc_url( $url1 ); ?>"
			   class="add-new-h2"><?php esc_html_e( 'Add New', 'strong-testimonials' ); ?></a>
			<a href="<?php echo esc_url( $url ); ?>"
			   class="add-new-h2"><?php esc_html_e( 'Return To List', 'strong-testimonials' ); ?></a>
			<?php if ( 'edit' == $this->action ) : ?>
				<a href="<?php echo esc_url( $url2 ); ?>"
				   class="add-new-h2"><?php esc_html_e( 'Duplicate This View', 'strong-testimonials' ); ?></a>
			<?php endif; ?>
		</h1>

		<form id="wpmtst-views-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
			  autocomplete="off" enctype="multipart/form-data">
			<?php wp_nonce_field( 'view_form_submit', 'view_form_nonce', true, true ); ?>

			<input type="hidden" name="action" value="view_<?php echo esc_attr( $this->action ); ?>_form">
			<input type="hidden" name="view[id]" value="<?php echo esc_attr( $this->view_id ); ?>">
			<input type="hidden" name="view_original_mode" value="<?php echo esc_attr( $this->view['mode'] ); ?>">
			<input type="hidden" name="view[data][_form_id]" value="<?php echo esc_attr( $this->view['form_id'] ); ?>">

			<div class="table view-info">
				<?php $this->render_info(); ?>
			</div>

			<?php $this->render_sections(); ?>

			<p class="wpmtst-submit">
				<?php submit_button( '', 'primary', 'submit-form', false ); ?>
				<?php submit_button( esc_html__( 'Cancel Changes', 'strong-testimonials' ), 'secondary', 'reset', false ); ?>
				<?php submit_button( esc_html__( 'Restore Defaults', 'strong-testimonials' ), 'secondary', 'restore-defaults', false ); ?>
			</p>
		</form>
		<?php
	}

	/**
	 * Render Strong Testimonials view info
	 *
	 * @since 2.51.5
	 */
	private function render_info() {

		if ( 'edit' == $this->action ) {
			$shortcode = '<div class="saved">';
			$shortcode .= '<input id="view-shortcode" type="text" value="[testimonial_view id=&quot;' . esc_attr( $this->view_id ) . '&quot;]" readonly />';
			$shortcode .= '<input id="copy-shortcode" class="button small" type="button" value="' . esc_attr__( 'copy to clipboard', 'strong-testimonials' ) . '" data-copytarget="#view-shortcode" />';
			$shortcode .= '<span id="copy-message">' . esc_html__( 'copied', 'strong-testimonials' ) . '</span>';
			$shortcode .= '</div>';
		} else {
			$shortcode = '<div class="unsaved">' . esc_html_x( 'will be available after you save this', 'The shortcode for a new View.', 'strong-testimonials' ) . '</div>';
		}

		$classes = array(
				'then',
				'then_display',
				'then_form',
				'then_slideshow',
				'then_not_single_template',
				apply_filters( 'wpmtst_view_section', '', 'shortcode' ),
		); ?>

		<div class="table-row form-view-name">
			<div class="table-cell">
				<label for="view-name">
					<?php esc_html_e( 'Name', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div class="table-cell">
				<input type="text" id="view-name" class="view-name" name="view[name]"
					   value="<?php echo esc_attr( htmlspecialchars( stripslashes( $this->view_name ) ) ); ?>"
					   tabindex="1">
			</div>
		</div>

		<div class="table-row form-view-shortcode <?php echo esc_attr( implode( ' ', array_filter( $classes ) ) ); ?>">
			<div class="table-cell">
				<label for="view-shortcode"><?php esc_html_e( 'Shortcode', 'strong-testimonials' ); ?></label>
			</div>
			<div class="table-cell">
				<?php 
				if ( 'edit' == $this->action ) {
					echo '<div class="saved">';
					echo '<input id="view-shortcode" type="text" value="[testimonial_view id=&quot;' . esc_attr( $this->view_id ) . '&quot;]" readonly />';
					echo '<input id="copy-shortcode" class="button small" type="button" value="' . esc_attr__( 'copy to clipboard', 'strong-testimonials' ) . '" data-copytarget="#view-shortcode" />';
					echo '<span id="copy-message">' . esc_html__( 'copied', 'strong-testimonials' ) . '</span>';
					echo '</div>';
				} else {
					echo '<div class="unsaved">' . esc_html_x( 'will be available after you save this', 'The shortcode for a new View.', 'strong-testimonials' ) . '</div>';
				}
				?>
			</div>
		</div>

		<div id="view-mode" class="table-row mode-select">
		<div class="table-cell">
			<?php esc_html_e( 'Mode', 'strong-testimonials' ); ?>
		</div>
		<div class="table-cell">
			<div class="mode-list">
				<?php foreach ( $this->view_options['mode'] as $mode ) : ?>
					<label>
						<input id="<?php echo esc_attr( $mode['name'] ); ?>" type="radio" name="view[data][mode]"
							   value="<?php echo esc_attr( $mode['name'] ); ?>" <?php checked( $this->view['mode'], $mode['name'] ); ?>>
						<?php echo esc_html( $mode['label'] ); ?>
						<div class="mode-line"></div>
					</label>
				<?php endforeach; ?>
			</div>
			<div class="mode-description"></div>
		</div>
		</div><?php
	}

	/**
	 * Render Strong Testimonials view sections
	 *
	 * @since 2.51.5
	 */
	private function render_sections() {

		// @todo: check what `$show_section = apply_filters('wpmtst_show_section', $this->view['mode']);` does
		// @todo: seems like the same filter is used above for $this->show_sections
		$show_section = apply_filters( 'wpmtst_show_section', $this->view['mode'] );
		foreach ( $this->sections as $name => $section ) {
			if ( ! empty( $section['section_action_before'] ) ) {
				do_action( $section['section_action_before'] );
			}

			$this->render_section( $name, $section );

			if ( ! empty( $section['section_action_after'] ) ) {
				do_action( $section['section_action_after'] );
			}
		}

		do_action( 'wpmtst_view_editor_before_group_general' );
		do_action( 'wpmtst_view_editor_after_groups' );
	}

	/**
	 * Render Strong Testimonial section
	 *
	 * @param $name
	 * @param $section
	 *
	 * @since 2.51.5
	 */
	public function render_section( $name, $section ) {

		$section['classes'][] = apply_filters( 'wpmtst_view_section', '', $name ); ?>
		<div class="<?php echo esc_attr( implode( ' ', array_filter( $section['classes'] ) ) ); ?>"
			 style="display:none">
			<h3><?php echo esc_html( $section['title'] ) ?></h3>
			<table class="<?php echo esc_attr( $section['table_classes'] ) ?>">

				<?php if ( ! empty( $section['subheading'] ) ): ?>
					<tr class="subheading">
						<?php foreach ( $section['subheading'] as $subheading ): ?>
							<td class="<?php echo esc_attr( $subheading['classes'] ) ?>"
								colspan="<?php echo esc_attr( $subheading['colspan'] ) ?>">
								<?php echo esc_html( $subheading['title'] ) ?>
								<?php echo wp_kses_post( $subheading['after'] ) ?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endif;

				if ( ! empty( $section['fields'] ) ) {
					if ( ! empty( $section['fields_action_before'] ) ) {
						do_action( $section['fields_action_before']['action'], $section['fields_action_before']['param'] );
					}
					foreach ( $section['fields'] as $key => $field ) {
						$this->set_field( $field );
						if ( ! empty( $this->field['field_action_before'] ) ) {
							do_action( $field['field_action_before'] );
						} ?>
						<tr id="<?php echo esc_attr( $this->field['id'] ) ?>"
							class="<?php echo esc_attr( $this->field['container_classes'] ) ?>" style="display:none">
							<?php $this->render_field() ?>
						</tr>
						<?php
						if ( ! empty( $this->field['field_action_after'] ) ) {
							do_action( $field['field_action_after'] );
						}
					}
					if ( ! empty( $section['fields_action_after'] ) ) {
						do_action( $section['fields_action_after']['action'], $section['fields_action_after']['param'] );
					}
				}
				?>
			</table>
		</div>
		<?php
	}

	/**
	 * Set Strong Testimonial field
	 *
	 * @param $field
	 *
	 * @since 2.51.5
	 */
	public function set_field( $field ) {

		$this->field = $field;
	}

	/**
	 * Set Strong Testimonial settings field
	 *
	 * @param $field
	 *
	 * @since 2.51.5
	 */
	public function set_settings_field( $field ) {

		$this->field     = $field;
		$this->isSetting = true;
	}

	/**
	 * Render Strong Testimonial field
	 *
	 * @since 2.51.5
	 */
	public function render_field() { ?>

		<th>
			<?php echo wp_kses_post( $this->field['before'] ); ?>
			<label for="<?php echo esc_attr( $this->field['class'] ) ?>"><?php echo wp_kses_post( $this->field['label'] ); ?></label>
			<?php echo wp_kses_post( $this->field['after'] ); ?>
		</th> <?php
		switch ( $this->field['type'] ) {
			case 'select':
				$this->render_field_select();
				break;
			case 'category':
				$this->render_field_category();
				break;
			case 'order':
				$this->render_field_order();
				break;
			case 'limit':
				$this->render_field_limit();
				break;
			case 'title':
				$this->render_field_title();
				break;
			case 'thumbnail':
				$this->render_field_thumbnail();
				break;
			case 'content':
				$this->render_field_content();
				break;
			case 'client-section':
				$this->render_field_client_section();
				break;
			case 'pagination':
				$this->render_field_pagination();
				break;
			case 'read-more-page':
				$this->render_field_read_more_page();
				break;
			case 'slideshow-num':
				$this->render_field_slideshow_num();
				break;
			case 'slideshow-transition':
				$this->render_field_slideshow_transition();
				break;
			case 'slideshow-behavior':
				$this->render_field_slideshow_behavior();
				break;
			case 'slideshow-navigation':
				$this->render_field_slideshow_navigation();
				break;
			case 'form-category':
				$this->render_field_form_category();
				break;
			case 'form-ajax':
				$this->render_field_form_ajax();
				break;
			case 'template-list-display':
				$this->current_mode = 'template';
				$this->current_type = 'display';
				$this->render_field_template_list();
				break;
			case 'template-list-form':
				$this->current_mode = 'form-template';
				$this->current_type = 'form';
				$this->render_field_template_list();
				break;
			case 'layout':
				$this->render_field_layout();
				break;
			case 'background':
				$this->render_field_background();
				break;
			case 'color':
				$this->render_field_color();
				break;
			case 'classes':
				$this->render_field_classes();
				break;
			case 'divi':
				$this->render_field_divi();
				break;
			default:
				do_action( 'wpmtst_render_field', $this->field );
		}
	}

	/**
	 * Render ST select
	 *
	 * @param        $input_name
	 * @param false  $recommended
	 * @param string $title
	 *
	 * @since 2.51.5
	 */
	public function render_option_select( $input_name, $recommended = false, $title = '' ) {

		$selected = $this->field['selected'];

		if ( $this->isSetting ) {
			$selected = $this->field['selected_settings'];
		}

		if ( isset( $this->field['options'] ) && ! empty( $this->field['options'] ) ): ?>
			<td>

				<?php if ( ! empty( $title ) ): ?>
				<h4 class="title"><?php esc_html_e( $title ); ?>
					<h4>
						<?php endif; ?>
						<select id="<?php echo esc_attr( $this->field['class'] ) ?>"
								name="<?php echo esc_attr( $input_name ); ?>">
							<?php foreach ( $this->field['options'] as $option ): ?>
								<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $option, $selected ); ?>><?php esc_html_e( $option, 'strong-testimonials-review-markup' ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php if ( $recommended ): ?>
							<p class="description"><strong
										style="color: #00805e; font-style: normal;"><?php esc_html_e( 'Recommended.', 'strong-testimonials-review-markup' ) ?></strong>
								<?php if ( is_string( $recommended ) ) {
									echo esc_html( $recommended );
								} ?>
							</p>
						<?php endif; ?>

			</td>
		<?php endif;
	}

	/**
	 * Render ST textfield
	 *
	 * @param        $input_name
	 * @param false  $recommended
	 * @param string $description
	 * @param string $title
	 * @param string $placeholder
	 *
	 * @SINCE 2.51.5
	 */
	public function render_option_textfield( $input_name, $recommended = false, $description = '', $title = '', $placeholder = '' ) {

		$value = $this->field['value'];

		if ( $this->isSetting ) {
			$value = $this->field['value_settings'];
		} ?>

		<td>
			<?php if ( ! empty( $title ) ): ?>
			<h4 class="title"><?php esc_html_e( $title ); ?>
				<h4>
					<?php endif; ?>

					<div>
						<div class="has-input">
							<input class="regular-text" type="text" id="<?php echo esc_attr( $this->field['class'] ) ?>"
								   name="<?php echo esc_attr( $input_name ) ?>" value="<?php echo esc_attr( $value ) ?>"
								   data-default="<?php echo esc_attr( $this->field['default'] ) ?>"
								   placeholder="<?php echo esc_attr( $placeholder, 'strong-testimonials-review-markup' ) ?>">
						</div>
						<div class="error-message"></div>
					</div>
					<p class="description">

						<?php if ( $recommended ): ?>
							<strong style="color: #00805e; font-style: normal;"><?php esc_html_e( 'Recommended.', 'strong-testimonials-review-markup' ) ?></strong>
						<?php endif; ?>

						<?php if ( ! empty( $description ) ): ?>
							<?php esc_html_e( $description, 'strong-testimonials-review-markup' ); ?>
						<?php endif; ?>

					</p>
		</td>
		<?php
	}

	/**
	 * Render ST select
	 *
	 * @since 2.51.5
	 */
	private function render_field_select() {

		$testimonials_list = get_posts( array(
				'orderby'          => 'post_date',
				'order'            => 'ASC',
				'post_type'        => 'wpm-testimonial',
				'post_status'      => 'publish',
				'posts_per_page'   => - 1,
				'suppress_filters' => true,
		) ); ?>
		<td>
			<div class="row">
				<div class="row-inner">
					<select id="view-single_or_multiple" class="if selectper" name="view[data][select]">
						<option value="multiple" <?php echo (int) $this->view['id'] == 0 ? 'selected' : ''; ?>><?php esc_html_e( 'one or more testimonials', 'strong-testimonials' ); ?></option>
						<option value="single" <?php echo (int) $this->view['id'] >= 1 ? 'selected' : ''; ?>><?php esc_html_e( 'a specific testimonial', 'strong-testimonials' ); ?></option>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="then then_not_slideshow then_single then_not_multiple" style="display: none;">
					<div class="row-inner">
						<label>
							<select id="view-id" name="view[data][id]">
								<option value="0"><?php esc_html_e( '&mdash; select &mdash;', 'strong-testimonials' ); ?></option>
								<?php foreach ( $testimonials_list as $post ) : ?>
									<option value="<?php echo esc_attr( $post->ID ); ?>" <?php selected( $this->view['id'], $post->ID ); ?>>
										<?php echo $post->post_title ? esc_html( $post->post_title ) : esc_html__( '(untitled)', 'strong-testimonials' ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>
					</div>
					<div class="row-inner">
						<label for="view-post_id">
							<?php echo esc_html( _x( 'or enter its ID or slug', 'to select a testimonial', 'strong-testimonials' ) ); ?>
						</label>
						<input type="text" id="view-post_id" name="view[data][post_id]" size="30">
					</div>
				</div>
			</div>
		</td>

		<td class="divider">
			<p><?php echo wp_kses_post( '<code>post_ids</code>' ); ?></p>
		</td>

		<td>
			<p><?php esc_html_e( 'a comma-separated list of post ID\'s', 'strong-testimonials' ); ?></p>
		</td>

		<td>
			<p><?php echo wp_kses_post( '<code>post_ids="123,456"</code>' ); ?></p>
		</td>
		<?php
	}

	/**
	 * Render ST category field
	 *
	 * @since 2.51.5
	 */
	private function render_field_category() {

		if ( $this->cat_count ) : ?>
			<td>
				<div id="view-category" class="row">
					<div class="table inline">
						<div class="table-row">
							<div class="table-cell select-cell then_display then_slideshow then_not_form">
								<select id="view-category-select" class="if selectper" name="view[data][category_all]">
									<option value="allcats" <?php selected( $this->view['category'], 'all' ); ?>><?php esc_html_e( 'all', 'strong-testimonials' ); ?></option>
									<option value="somecats" <?php echo( 'all' != $this->view['category'] ? 'selected' : '' ); ?>><?php echo esc_html( _x( 'select', 'verb', 'strong-testimonials' ) ); ?></option>
								</select>
							</div>
							<div class="table-cell then then_not_allcats then_somecats" style="display: none;">
								<div class="table">
									<?php if ( $this->cat_count > 5 ) : ?>
										<div class="table-row">
											<div class="table-cell">
												<div class="row" style="text-align: right; padding-bottom: 5px;">
													<input type="button" class="expand-cats button"
														   value="expand list"/>
												</div>
											</div>
										</div>
									<?php endif; ?>
									<div class="table-row">
										<div class="table-cell"><?php wpmtst_category_checklist( $this->view_cats_array ); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</td>
		<?php else : ?>
			<td>
				<div id="view-category" class="row">
					<input type="hidden" name="view[data][category_all]" value="all">
					<p class="description tall"><?php esc_html_e( 'No categories found', 'strong-testimonials' ); ?></p>
				</div>
			</td>
		<?php endif; ?>

		<td class="divider">
			<p><?php echo wp_kses_post( '<code>category</code>' ); ?></p>
		</td>
		<td>
			<p><?php esc_html_e( 'a comma-separated list of category slugs or ID\'s', 'strong-testimonials' ); ?></p>
		</td>
		<td>
		<p><?php echo wp_kses_post( '<code>category="accounting"</code>' ); ?></p>
		</td><?php
	}

	/**
	 * Render ST order field
	 *
	 * @since 2.51.5
	 */
	private function render_field_order() {

		?>
		<td>
			<div class="row">
				<div class="inline">
					<select id="view-order" name="view[data][order]">
						<?php foreach ( $this->view_options['order'] as $order => $order_label ) : ?>
							<option value="<?php echo esc_attr( $order ); ?>" <?php selected( $order, $this->view['order'] ); ?>><?php echo esc_html( $order_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</td>
		<td class="divider">
			<p><?php echo wp_kses_post( '<code>order</code>' ); ?></p>
		</td>
		<td>
			<p><?php echo wp_kses_post( 'oldest | newest | random | menu_order' ); ?></p>
		</td>
		<td>
			<p><?php echo wp_kses_post( '<code>order="random"</code>' ); ?></p>
		</td> <?php
	}

	/**
	 * Render ST limit fied
	 *
	 * @since 2.51.5
	 */
	private function render_field_limit() {

		?>
		<td>
			<div class="row">
				<div class="inline">
					<select class="if select" id="view-all" name="view[data][all]">
						<option value="1" <?php selected( - 1, $this->view['count'] ); ?>>
							<?php esc_html_e( 'all', 'strong-testimonials' ); ?>
						</option>
						<option class="trip" value="0" <?php selected( $this->view['count'] > 0 ); ?>>
							<?php echo esc_html( _x( 'count', 'noun', 'strong-testimonials' ) ); ?>
						</option>
					</select>
					&nbsp;
					<label><input class="input-incremental then_all" type="number" id="view-count"
								  name="view[data][count]"
								  value="<?php echo ( - 1 == esc_attr( $this->view['count'] ) ) ? 1 : esc_attr( $this->view['count'] ); ?>"
								  min="1" size="5" style="display: none;"></label>
				</div>
			</div>
		</td>
		<td class="divider">
			<p><?php echo wp_kses_post( '<code>count</code>' ); ?></p>
		</td>
		<td></td>
		<td>
			<p><?php echo wp_kses_post( '<code>count=5</code>' ); ?></p>
		</td>
		<?php
	}

	/**
	 * Render ST title field
	 *
	 * @since 2.51.5
	 */
	private function render_field_title() {

		$custom_fields = wpmtst_get_custom_fields();
		$options       = get_option( 'wpmtst_options' );
		$url_fields    = array();

		foreach ( $custom_fields as $field ) {
			if ( 'url' == $field['input_type'] ) {
				$url_fields[] = $field;
			}
		}

		// For older versions where title_link was checkbox
		if ( '1' == $this->view['title_link'] ) {
			$this->view['title_link'] = 'wpmtst_testimonial';
		}

		if ( '0' == $this->view['title_link'] ) {
			$this->view['title_link'] = 'none';
		} ?>

		<td colspan="2">
			<div class="row">
				<div class="row-inner">
					<div class="then then_title" style="display: none;">
						<label for="view-title_link">
							<?php printf( esc_html_x( 'Link to %s', 'The name of this post type. "Testimonial" by default.', 'strong-testimonials' ), esc_html( strtolower( apply_filters( 'wpmtst_cpt_singular_name', __( 'Testimonial', 'strong-testimonials' ) ) ) ) ); ?>
						</label>
						<div class="wpmtst-tooltip"><span>[?]</span>
							<div class="wpmtst-tooltip-content"><?php echo esc_html__( '"Full testimonial" option doesn\'s work if "Disable permalinks for testimonials" from "Settings" page is enabled.', 'strong-testimonials' ); ?></div>
						</div>

						<select name="view[data][title_link]">
							<option value="none" <?php selected( 'none', $this->view['title_link'], true ); ?>><?php echo esc_html__( 'None', 'strong-testimonials' ); ?></option>
							<?php if ( ! isset( $options['disable_rewrite'] ) || '1' != $options['disable_rewrite'] ) { ?>
								<option value="wpmtst_testimonial" <?php selected( 'wpmtst_testimonial', $this->view['title_link'], true ); ?>><?php echo esc_html__( 'Full testimonial', 'strong-testimonials' ); ?></option>
							<?php } ?>

							<?php foreach ( $url_fields as $url ) { ?>
								<option value="<?php echo esc_attr( $url['name'] ); ?>" <?php selected( $url['name'], $this->view['title_link'] ); ?>><?php echo esc_html( $url['label'] ); ?></option>
							<?php } ?>

						</select>
						<?php do_action( 'wpmtst_view_editor_after_group_fields_title' ) ?>
					</div>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST thumbnail field
	 *
	 * @since 2.51.5
	 */
	private function render_field_thumbnail() {

		$image_sizes = wpmtst_get_image_sizes();
		?>
		<td colspan="2">
			<div class="then then_images" style="display: none;">
				<div class="row">
					<div class="row-inner">
						<div class="inline">
							<label for="view-thumbnail_size">Size</label>
							<select id="view-thumbnail_size" class="if select" name="view[data][thumbnail_size]">
								<?php foreach ( $image_sizes as $key => $size ) : ?>
									<option class="<?php echo( 'custom' == $key ? 'trip' : '' ) ?>"
											value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $this->view['thumbnail_size'] ); ?>><?php echo esc_html( $size['label'] ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="inline then then_thumbnail_size" style="margin-left: 1em;">
							<label for="thumbnail_width"><?php esc_html_e( 'width', 'strong-testimonials' ); ?></label>
							<input id="thumbnail_width" class="input-number-px" type="text"
								   name="view[data][thumbnail_width]"
								   value="<?php echo esc_attr( $this->view['thumbnail_width'] ); ?>"> px
							<span style="display: inline-block; color: #BBB; margin: 0 1em;">|</span>
							<label for="thumbnail_height"><?php esc_html_e( 'height', 'strong-testimonials' ); ?></label>
							<input id="thumbnail_height" class="input-number-px" type="text"
								   name="view[data][thumbnail_height]"
								   value="<?php echo esc_attr( $this->view['thumbnail_height'] ); ?>"> px
						</div>
					</div>
				</div>
				<div class="row">
					<div class="row-inner">
						<div class="inline">
							<input type="checkbox" id="view-lightbox" class="if toggle" name="view[data][lightbox]"
								   value="1" <?php checked( $this->view['lightbox'] ); ?> class="checkbox">
							<label for="view-lightbox"><?php esc_html_e( 'Open full-size image in a lightbox', 'strong-testimonials' ); ?></label>
						</div>
						<div class="inline then then_lightbox">
							<p class="description"><?php esc_html_e( 'Requires a lightbox provided by your theme or another plugin.', 'strong-testimonials' ); ?></p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="row-inner">
						<div class="inline then then_lightbox input" style="display: none;">
							<label for="view-lightbox_class"><?php esc_html_e( 'CSS class', 'strong-testimonials' ); ?></label>
							<input type="text" id="view-lightbox_class" class="medium inline"
								   name="view[data][lightbox_class]"
								   value="<?php echo esc_attr( $this->view['lightbox_class'] ); ?>">
							<p class="inline description tall"><?php esc_html_e( 'To add a class to the image link.', 'strong-testimonials' ); ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="row-inner">
						<div class="inline">
							<label for="view-gravatar"><?php esc_html_e( 'If no Featured Image', 'strong-testimonials' ); ?></label>
							<select id="view-gravatar" class="if select selectper" name="view[data][gravatar]">
								<option value="no" <?php selected( $this->view['gravatar'], 'no' ); ?>><?php esc_html_e( 'show nothing', 'strong-testimonials' ); ?></option>
								<option value="yes" <?php selected( $this->view['gravatar'], 'yes' ); ?>><?php esc_html_e( 'show Gravatar', 'strong-testimonials' ); ?></option>
								<option value="if" <?php selected( $this->view['gravatar'], 'if' ); ?>><?php esc_html_e( 'show Gravatar only if found', 'strong-testimonials' ); ?></option>
								<?php do_action( 'wpmtst_avatar_options', $this->view ) ?>
							</select>
						</div>
						<div class="inline">
							<div class="then fast then_not_no then_not_default then_not_initials then_not_wp_avatars then_yes then_if"
								 style="display: none;">
								<p class="description tall"><a
											href="<?php echo esc_url( admin_url( 'options-discussion.php' ) ); ?>"><?php esc_html_e( 'Gravatar settings', 'strong-testimonials' ); ?></a>
								</p>
							</div>
						</div>
						<?php do_action( 'after_no_featured_image', $this->view ) ?>
					</div>
				</div>
			</div><!-- .then_images -->
		</td>
		<?php
	}

	/**
	 * Render ST content field
	 *
	 * @since 2.51.5
	 */
	private function render_field_content() {

		?>
		<td colspan="2">
			<!-- Content type -->
			<div id="option-content" class="row">
				<div class="row-inner">
					<!-- select -->
					<div class="inline">
						<select id="view-content" class="if selectper min-width-1 label-not-adjacent"
								name="view[data][content]">
							<option value="entire" <?php selected( 'entire', $this->view['content'] ); ?>><?php echo esc_html( _x( 'entire content', 'display setting', 'strong-testimonials' ) ); ?></option>
							<option value="truncated" <?php selected( 'truncated', $this->view['content'] ); ?>><?php echo esc_html( _x( 'automatic excerpt', 'display setting', 'strong-testimonials' ) ); ?></option>
							<option value="excerpt" <?php selected( 'excerpt', $this->view['content'] ); ?>><?php echo esc_html( _x( 'manual excerpt', 'display setting', 'strong-testimonials' ) ); ?></option>
						</select>
					</div>
					<!-- info & screenshot -->
					<div class="inline then fast then_truncated then_not_entire then_not_excerpt"
						 style="display: none;">
						<p class="description"><?php esc_html_e( 'This will strip tags like &lt;em&gt; and &lt;strong&gt;.', 'strong-testimonials' ); ?></p>
					</div>
					<div class="inline then fast then_not_truncated then_not_entire then_excerpt"
						 style="display: none;">
						<p class="description">
							<?php printf( wp_kses_post( __( 'To create manual excerpts, you may need to enable them in the post editor like in this <a href="%s" class="thickbox">screenshot</a>.', 'strong-testimonials' ) ), esc_url( '#TB_inline?width=&height=210&inlineId=screenshot-screen-options' ) ); ?>
							<span class="screenshot" id="screenshot-screen-options" style="display: none;"><img
										src="<?php echo esc_url( WPMTST_ADMIN_URL ); ?>img/screen-options.png"
										width="600"></span>
						</p>
					</div>
				</div>
			</div>
			<!-- Excerpt length -->
			<div id="option-content-length" class="row then then_not_entire then_excerpt then_truncated"
				 style="display: none;">
				<div class="row-inner">
					<!-- info -->
					<div class="inline tight then then_excerpt then_not_truncated" style="display: none;">
						<span><?php esc_html_e( 'If no manual excerpt, create an excerpt using', 'strong-testimonials' ); ?></span>
					</div>
					<!-- default or custom? -->
					<div class="inline">
						<label>
							<select id="view-use_default_length" class="if selectgroup min-width-1"
									name="view[data][use_default_length]">
								<option value="1" <?php selected( $this->view['use_default_length'] ); ?>><?php echo esc_html( _x( 'default length', 'display setting', 'strong-testimonials' ) ); ?></option>
								<option value="0" <?php selected( ! $this->view['use_default_length'] ); ?>><?php echo esc_html( _x( 'custom length', 'display setting', 'strong-testimonials' ) ); ?></option>
							</select>
						</label>
					</div>
					<!-- 1st option: default -->
					<div class="inline then fast then_use_default_length then_1 then_not_0" style="display: none;">
						<label for="view-use_default_length" class="inline-middle"><p
									class="description tall"><?php esc_html_e( 'The default length is 55 words but your theme may override that.', 'strong-testimonials' ); ?></p>
						</label>
					</div>
					<!-- 2nd option: length -->
					<div class="inline then fast then_use_default_length then_0 then_not_1" style="display: none;">
						<label class="inline-middle"><?php printf( esc_html_x( 'the first %s words', 'the excerpt length', 'strong-testimonials' ), '<input id="view-excerpt_length" class="input-incremental" type="number" min="1" max="999" name="view[data][excerpt_length]" value="' . esc_attr( $this->view['excerpt_length'] ) . '">' ); ?></label>
					</div>
				</div>
			</div><!-- #option-content-length -->

			<!-- Read-more link -->
			<div id="option-content-read-more" class="row then then_not_entire then_excerpt then_truncated"
				 style="display: none;">
				<div class="row-inner subgroup">
					<!-- action: full post or in place -->
					<div class="row-inner">
						<div class="inline"><?php echo wp_kses_post( __( 'Add a <strong>Read more</strong> link to', 'strong-testimonials' ) ); ?></div>
						<div class="inline tight">
							<label>
								<select id="view-more_post_in_place" class="if selectgroup"
										name="view[data][more_post_in_place]">
									<option value="0" <?php selected( ! $this->view['more_post_in_place'] ); ?>><?php esc_html_e( 'the full testimonial', 'strong-testimonials' ); ?></option>
									<option value="1" <?php selected( $this->view['more_post_in_place'] ); ?>><?php esc_html_e( 'expand content in place', 'strong-testimonials' ); ?></option>
								</select>
							</label>
						</div>
					</div>
					<!-- ellipsis -->
					<div class="row-inner">
						<div class="then then_use_default_more then_0 then_not_1" style="display: none;">
							<div class="inline">
								<label>
									<select id="view-more_post_ellipsis" class="if selectgroup"
											name="view[data][more_post_ellipsis]">
										<option value="1" <?php selected( $this->view['more_post_ellipsis'] ); ?>><?php esc_html_e( 'with an ellipsis', 'strong-testimonials' ); ?></option>
										<option value="0" <?php selected( ! $this->view['more_post_ellipsis'] ); ?>><?php esc_html_e( 'without an ellipsis', 'strong-testimonials' ); ?></option>
									</select>
								</label>
							</div>
							<div class="inline then then_excerpt then_not_truncated" style="display: none;">
								<p class="description"><?php esc_html_e( 'Automatic excerpt only.', 'strong-testimonials' ); ?></p>
							</div>
						</div>
					</div>
					<!-- default or custom -->
					<div class="row-inner">
						<div class="inline tight then fast then_more_post_in_place then_1 then_not_0"
							 style="display: none;">
							<?php esc_html_e( 'with link text to read more', 'strong-testimonials' ); ?>
						</div>
						<div class="inline then fast then_more_post_in_place then_0 then_not_1" style="display: none;">
							<label>
								<select id="view-use_default_more" class="if selectgroup min-width-1"
										name="view[data][use_default_more]">
									<option value="1" <?php selected( $this->view['use_default_more'] ); ?>><?php echo esc_html( _x( 'with default link text', 'display setting', 'strong-testimonials' ) ); ?></option>
									<option value="0" <?php selected( ! $this->view['use_default_more'] ); ?>><?php echo esc_html( _x( 'with custom link text', 'display setting', 'strong-testimonials' ) ); ?></option>
								</select>
							</label>
						</div>
						<div class="inline then fast then_use_default_more then_1 then_not_0" style="display: none;">
							<p class="description"><?php esc_html_e( 'If you only see [&hellip;] without a link then use the custom link text instead.', 'strong-testimonials' ); ?></p>
						</div>
						<!-- read more -->
						<div class="inline then fast then_use_default_more then_0 then_not_1" style="display: none;">
                            <span id="option-link-text" class="inline-span">
                                <label for="view-more_post_text">
                                    <input type="text" id="view-more_post_text" name="view[data][more_post_text]"
										   value="<?php echo esc_attr( $this->view['more_post_text'] ); ?>" size="22"
										   placeholder="<?php esc_html_e( 'enter a phrase', 'strong-testimonials' ); ?>">
                                </label>
                            </span>
						</div>
					</div>
					<!-- read less -->
					<div class="row-inner then fast then_more_post_in_place then_1 then_not_0" style="display: none;">
						<div class="inline tight">
							<?php esc_html_e( 'and link text to read less', 'strong-testimonials' ); ?>
						</div>
						<div class="inline tight">
                            <span id="option-link-text-less" class="inline-span">
                                <label for="view-less_post_text">
                                    <input type="text" id="view-less_post_text" name="view[data][less_post_text]"
										   value="<?php echo esc_attr( $this->view['less_post_text'] ); ?>" size="22"
										   placeholder="<?php esc_html_e( 'enter a phrase', 'strong-testimonials' ); ?>">
                                </label>
                            </span>
							<p class="inline description"><?php esc_html_e( 'Leave blank to leave content expanded without a link.', 'strong-testimonials' ); ?></p>
						</div>
					</div>
					<!-- automatic or both -->
					<div class="row-inner then then_excerpt then_not_truncated" style="display: none;">
						<div class="inline">
							<label>
								<select id="view-more_full_post" class="if selectgroup"
										name="view[data][more_full_post]">
									<option value="0" <?php selected( $this->view['more_full_post'], 0 ); ?>><?php echo esc_html( _x( 'for automatic excerpt only', 'display setting', 'strong-testimonials' ) ); ?></option>
									<option value="1" <?php selected( $this->view['more_full_post'], 1 ); ?>><?php echo esc_html( _x( 'for both automatic and manual excerpts', 'display setting', 'strong-testimonials' ) ); ?></option>
								</select>
							</label>
						</div>
					</div>
					<div class="row-inner">
						<div class="row-inner then fast then_more_post_in_place then_1 then_not_0" style="display: none;">
							<div class="html-content-checkbox">
								<input class="checkbox" id="view-html-content" name="view[data][html_content]" value="1"
									type="checkbox" <?php checked( isset( $this->view['html_content'] ) ? $this->view['html_content'] : false ); ?>/>
								<label for="view-html-content"><?php echo wp_kses_post( __( 'Show <strong>html content</strong>.', 'strong-testimonials' ) ); ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row links then then_not_entire then_truncated then_excerpt" style="display: none;">
				<p class="description tall solo"><?php printf( esc_html__( '%s about WordPress excerpts', 'strong-testimonials' ), sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'http://buildwpyourself.com/wordpress-manual-excerpts-more-tag/' ), esc_html__( 'Learn more', 'strong-testimonials' ) ) ); ?></p>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST client field
	 *
	 * @since 2.51.5
	 */
	private function render_field_client_section() {

		?>
		<td colspan="2">
			<div id="client-section-table">
				<div id="custom-field-list2" class="fields">
					<?php
					if ( isset( $this->view['client_section'] ) ) {
						foreach ( $this->view['client_section'] as $key => $field ) {
							wpmtst_view_field_inputs( $key, $field );
						}
					}
					?>
				</div>
			</div>
			<div id="add-field-bar" class="is-below">
				<input id="add-field" type="button" name="add-field" source="view[data]"
					   value="<?php esc_html_e( 'Add Field', 'strong-testimonials' ); ?>" class="button-secondary"/>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST pagination
	 *
	 * @since 2.51.5
	 */
	private function render_field_pagination() {
		/**
		 * Attempt to repair bug from 2.28.2
		 */
		if ( ! isset( $this->view['pagination_settings']['end_size'] ) || ! $this->view['pagination_settings']['end_size'] ) {
			$this->view['pagination_settings']['end_size'] = 1;
		}
		if ( ! isset( $this->view['pagination_settings']['mid_size'] ) || ! $this->view['pagination_settings']['mid_size'] ) {
			$this->view['pagination_settings']['mid_size'] = 2;
		}
		if ( ! isset( $this->view['pagination_settings']['per_page'] ) || ! $this->view['pagination_settings']['per_page'] ) {
			$this->view['pagination_settings']['per_page'] = 5;
		}
		$links = '<span class="help-links">';
		$links .= '<a href="#tab-panel-wpmtst-help-pagination" class="open-help-tab">' . __( 'Help', 'strong-testimonials' ) . '</a>';
		$links .= '</span>';
		?>
		<td>
			<div class="row then then_pagination" style="display: none;">
				<div class="row-inner">
					<div class="inline">
						<label for="view-pagination_type">
							<select class="if selectper" id="view-pagination_type"
									name="view[data][pagination_settings][type]">
								<option value="simple" <?php selected( 'simple', $this->view['pagination_settings']['type'] ); ?>><?php esc_html_e( 'simple', 'strong-testimonials' ); ?></option>
								<option value="standard" <?php selected( 'standard', $this->view['pagination_settings']['type'] ); ?>><?php esc_html_e( 'WordPress standard', 'strong-testimonials' ); ?></option>
								<?php do_action( 'wpmtst_form_pagination_options_after', $this->view ) ?>
							</select>
						</label>
					</div>
					<div class="inline then fast then_simple then_not_standard then_not_infinitescroll then_not_loadmore"
						 style="display: none;">
						<p class="description">
							<?php esc_html_e( 'Using JavaScript. Intended for small scale.', 'strong-testimonials' ); ?>
							<?php echo wp_kses_post( $links ); ?>
						</p>
					</div>
					<div class="inline then fast then_not_simple then_standard then_not_infinitescroll then_not_loadmore"
						 style="display: none;">
						<p class="description">
							<?php esc_html_e( 'Using paged URLs: /page/2, /page/3, etc. Best for large scale.', 'strong-testimonials' ); ?>
							<?php echo wp_kses_post( $links ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="row then then_pagination" style="display: none;">
				<div class="row-inner">
					<div class="inline">
						<label for="view-per_page"><?php echo esc_html( _x( 'Per page', 'quantity', 'strong-testimonials' ) ); ?></label>
						<input class="input-incremental" id="view-per_page"
							   name="view[data][pagination_settings][per_page]" type="number" min="1" step="1"
							   value="<?php echo esc_attr( $this->view['pagination_settings']['per_page'] ); ?>"/>
					</div>
					<div class="inline then then_simple then_standard then_not_infinitescroll then_not_loadmore">
						<label for="view-nav"><?php esc_html_e( 'Navigation', 'strong-testimonials' ); ?></label>
						<select id="view-nav" name="view[data][pagination_settings][nav]">
							<option value="before" <?php selected( $this->view['pagination_settings']['nav'], 'before' ); ?>><?php esc_html_e( 'before', 'strong-testimonials' ); ?></option>
							<option value="after" <?php selected( $this->view['pagination_settings']['nav'], 'after' ); ?>><?php esc_html_e( 'after', 'strong-testimonials' ); ?></option>
							<option value="before,after" <?php selected( $this->view['pagination_settings']['nav'], 'before,after' ); ?>><?php esc_html_e( 'before & after', 'strong-testimonials' ); ?></option>
						</select>
					</div>
				</div>
				<div class="row then then_not_simple then_standard then_not_infinitescroll then_not_loadmore"
					 style="display: none;">
					<div class="row-inner">
						<div class="inline">
							<label for="view-pagination-show_all">
								<select class="if select" id="view-pagination-show_all"
										name="view[data][pagination_settings][show_all]">
									<option value="on" <?php selected( $this->view['pagination_settings']['show_all'] ); ?>><?php esc_html_e( 'Show all page numbers', 'strong-testimonials' ); ?></option>
									<option value="off"
											<?php selected( ! $this->view['pagination_settings']['show_all'] ); ?>class="trip"><?php esc_html_e( 'Show condensed page numbers', 'strong-testimonials' ); ?></option>
								</select>
							</label>
						</div>
						<div class="inline then then_show_all" style="display: none;">
							<div class="inline">
								<label for="view-pagination-end_size"><?php echo esc_html( _x( 'End size', 'quantity', 'strong-testimonials' ) ); ?></label>
								<input class="input-incremental" id="view-pagination-end_size"
									   name="view[data][pagination_settings][end_size]" type="number" min="1" step="1"
									   value="<?php echo esc_attr( $this->view['pagination_settings']['end_size'] ); ?>"/>
							</div>
							<div class="inline">
								<label for="view-pagination-mid_size"><?php echo esc_html(  _x( 'Middle size', 'quantity', 'strong-testimonials' ) ); ?></label>
								<input class="input-incremental" id="view-pagination-mid_size"
									   name="view[data][pagination_settings][mid_size]" type="number" min="1" step="1"
									   value="<?php echo esc_attr( $this->view['pagination_settings']['mid_size'] ); ?>"/>
							</div>
						</div>
					</div>
				</div>
				<div class="row then then_not_simple then_standard then_not_infinitescroll then_not_loadmore"
					 style="display: none;">
					<div class="row-inner">
						<div class="inline inline-middle">
							<input class="if toggle checkbox" id="view-pagination-prev_next"
								   name="view[data][pagination_settings][prev_next]" type="checkbox"
								   value="1" <?php checked( $this->view['pagination_settings']['prev_next'] ); ?>>
							<label for="view-pagination-prev_next"><?php esc_html_e( 'Show previous/next links', 'strong-testimonials' ); ?></label>
						</div>
						<div class="then then_prev_next inline inline-middle">
							<label for="view-pagination-prev_text"><?php esc_html_e( 'Previous text', 'strong-testimonials' ); ?></label>
							<input class="code" id="view-pagination-prev_text"
								   name="view[data][pagination_settings][prev_text]" type="text"
								   value="<?php echo esc_attr( htmlentities( $this->view['pagination_settings']['prev_text'] ) ); ?>">
						</div>
						<div class="then then_prev_next inline inline-middle">
							<label for="view-pagination-next_text"><?php esc_html_e( 'Next text', 'strong-testimonials' ); ?></label>
							<input class="code" id="view-pagination-next_text"
								   name="view[data][pagination_settings][next_text]" type="text"
								   value="<?php echo esc_attr( htmlentities( $this->view['pagination_settings']['next_text'] ) ); ?>">
						</div>
					</div>
				</div>
				<div class="row then then_not_simple then_standard then_not_infinitescroll then_not_loadmore"
					 style="display: none;">
					<div class="row-inner">
						<div class="inline">
							<label for="view-pagination-before_page_number"><?php esc_html_e( 'Before page number', 'strong-testimonials' ); ?></label>
							<input class="small-text" id="view-pagination-before_page_number"
								   name="view[data][pagination_settings][before_page_number]" type="text"
								   value="<?php echo esc_attr( $this->view['pagination_settings']['before_page_number'] ); ?>">
						</div>
						<div class="inline">
							<label for="view-pagination-after_page_number"><?php esc_html_e( 'After page number', 'strong-testimonials' ); ?></label>
							<input class="small-text" id="view-pagination-after_page_number"
								   name="view[data][pagination_settings][after_page_number]" type="text"
								   value="<?php echo esc_attr( $this->view['pagination_settings']['after_page_number'] ); ?>">
						</div>
					</div>
				</div>
			</div>
			<?php do_action( 'wpmtst_view_editor_pagination_row_end' ); ?>
		</td>
		<?php
	}

	/**
	 * Render ST read more
	 *
	 * @since 2.51.5
	 */
	private function render_field_read_more_page() {

		$custom_list = apply_filters( 'wpmtst_custom_pages_list', array(), $this->view );
		$pages_list  = apply_filters( 'wpmtst_pages_list', wpmtst_get_pages() );
		$posts_list  = apply_filters( 'wpmtst_posts_list', wpmtst_get_posts() );

		?>
		<td>
			<div class="row then then_more_page" style="display: none;">
				<!-- Select page -->
				<div class="row then then_more_page" style="display: none;">
					<div class="row-inner">
						<label>
							<select id="view-page" name="view[data][more_page_id]">
								<option value=""><?php esc_html_e( '&mdash; select &mdash;', 'strong-testimonials' ); ?></option>
								<?php
								do_action( 'wpmtst_readmore_page_list', $this->view );

								if ( $custom_list ) {
									?>
									<optgroup label="<?php esc_html_e( 'Custom', 'strong-testimonials' ); ?>">
										<?php
										foreach ( $custom_list as $page ) {
											echo wp_kses_post( $page );
										}
										?>
									</optgroup>
								<?php } ?>

								<optgroup label="<?php esc_attr_e( 'Pages', 'strong-testimonials' ); ?>">

									<?php foreach ( $pages_list as $pages ) : ?>
										<option value="<?php echo esc_attr( $pages->ID ); ?>" <?php selected( isset( $this->view['more_page_id'] ) ? $this->view['more_page_id'] : 0, $pages->ID ); ?>><?php echo esc_html( $pages->post_title ); ?></option>
									<?php endforeach; ?>

								</optgroup>

								<optgroup label="<?php esc_attr_e( 'Posts', 'strong-testimonials' ); ?>">

									<?php foreach ( $posts_list as $posts ) : ?>
										<option value="<?php echo esc_attr( $posts->ID ); ?>" <?php selected( isset( $this->view['more_page_id'] ) ? $this->view['more_page_id'] : 0, $posts->ID ); ?>><?php echo esc_html( $posts->post_title ); ?></option>
									<?php endforeach; ?>

								</optgroup>
							</select>
						</label>
						<label for="view-page_id2"><?php echo esc_html( _x( 'or enter its ID or slug', 'to select a target page', 'strong-testimonials' ) ); ?></label>
						<input type="text" id="view-page_id2"
							   name="view[data][more_page_id2]" <?php echo( isset( $this->view['more_page_id'] ) ? 'value="' . esc_attr( $this->view['more_page_id'] ) . '"' : '' ); ?>
							   size="30">
					</div>
				</div>
				<!-- Link text -->
				<div class="row">
					<div class="row-inner">
						<div class="inline">
							<label for="view-more_page_text"><?php esc_html_e( 'with link text', 'strong-testimonials' ); ?></label>
							<input type="text" id="view-more_page_text" name="view[data][more_page_text]"
								   value="<?php echo esc_attr( $this->view['more_page_text'] ); ?>" size="50">
						</div>
					</div>
				</div>
				<!-- location -->
				<div class="row">
					<div class="row-inner">
						<label>
							<select id="view-more_page_hook" name="view[data][more_page_hook]">
								<option value="wpmtst_view_footer" <?php selected( 'wpmtst_view_footer', $this->view['more_page_hook'] ); ?>><?php echo esc_html( _x( 'after the last testimonial', 'display setting', 'strong-testimonials' ) ); ?></option>
								<option value="wpmtst_after_testimonial" <?php selected( 'wpmtst_after_testimonial', $this->view['more_page_hook'] ); ?>><?php echo esc_html( _x( 'in each testimonial', 'display setting', 'strong-testimonials' ) ); ?></option>
							</select>
						</label>
					</div>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST slideshow number
	 *
	 * @since 2.51.5
	 */
	private function render_field_slideshow_num() {

		?>
		<td>
			<div class="row">
				<div class="inline inline-middle">
					<label>
						<select id="view-slider_type" name="view[data][slideshow_settings][type]"
								class="if selectgroup">
							<option value="show_single" <?php selected( $this->view['slideshow_settings']['type'], 'show_single' ); ?>><?php esc_html_e( 'single', 'strong-testimonials' ); ?></option>
							<option value="show_multiple" <?php selected( $this->view['slideshow_settings']['type'], 'show_multiple' ); ?>><?php esc_html_e( 'multiple', 'strong-testimonials' ); ?></option>
						</select>
					</label>
					<div class="option-desc singular" style="display: none;">
						<?php esc_html_e( 'slide at a time', 'strong-testimonials' ); ?>
					</div>
					<div class="option-desc plural" style="display: none;">
						<?php esc_html_e( 'slides at a time with these responsive breakpoints:', 'strong-testimonials' ); ?>
					</div>
				</div>
			</div>
		</td>
		<td>
			<div class="inline then then_slider_type then_not_show_single then_show_multiple" style="display: none;">
				<div class="row">
					<div class="inner-table is-below">
						<div class="inner-table-row bordered header">
							<div class="inner-table-cell"><?php esc_html_e( 'minimum screen width', 'strong-testimonials' ); ?></div>
							<div class="inner-table-cell"><?php esc_html_e( 'show', 'strong-testimonials' ); ?></div>
							<div class="inner-table-cell"><?php esc_html_e( 'margin', 'strong-testimonials' ); ?></div>
							<div class="inner-table-cell"><?php esc_html_e( 'move', 'strong-testimonials' ); ?></div>
						</div>
						<?php foreach ( $this->view['slideshow_settings']['breakpoints'] as $key => $breakpoint ) : ?>
							<div class="inner-table-row bordered">
								<div class="inner-table-cell">
									<label>
										<input id="view-breakpoint_<?php echo esc_attr( $key ); ?>"
											   name="view[data][slideshow_settings][breakpoints][<?php echo esc_attr( $key ); ?>][width]"
											   value="<?php echo esc_attr( $breakpoint['width'] ); ?>" type="number"
											   class="input-incremental"> px
									</label>
								</div>
								<div class="inner-table-cell">
									<label>
										<select id="view-max_slides_<?php echo esc_attr( $key ); ?>"
												name="view[data][slideshow_settings][breakpoints][<?php echo esc_attr( $key ); ?>][max_slides]"
												class="if selectgroup">
											<option value="1" <?php selected( $breakpoint['max_slides'], 1 ); ?>>1
											</option>
											<option value="2" <?php selected( $breakpoint['max_slides'], 2 ); ?>>2
											</option>
											<option value="3" <?php selected( $breakpoint['max_slides'], 3 ); ?>>3
											</option>
											<option value="4" <?php selected( $breakpoint['max_slides'], 4 ); ?>>4
											</option>
										</select>
									</label>
									<div class="option-desc singular"
										 style="display: none;"><?php esc_html_e( 'slide', 'strong-testimonials' ); ?></div>
									<div class="option-desc plural"
										 style="display: none;"><?php esc_html_e( 'slides', 'strong-testimonials' ); ?></div>
								</div>
								<div class="inner-table-cell">
									<input id="view-margin_<?php echo esc_attr( $key ); ?>"
										   name="view[data][slideshow_settings][breakpoints][<?php echo esc_attr( $key ); ?>][margin]"
										   value="<?php echo esc_attr( $breakpoint['margin'] ); ?>" type="number"
										   min="1" step="1" size="3" class="input-incremental"/> px
								</div>
								<div class="inner-table-cell">
									<label>
										<select id="view-move_slides_<?php echo esc_attr( $key ); ?>"
												name="view[data][slideshow_settings][breakpoints][<?php echo esc_attr( $key ); ?>][move_slides]"
												class="if selectgroup">
											<option value="1" <?php selected( $breakpoint['move_slides'], 1 ); ?>>1
											</option>
											<option value="2" <?php selected( $breakpoint['move_slides'], 2 ); ?>>2
											</option>
											<option value="3" <?php selected( $breakpoint['move_slides'], 3 ); ?>>3
											</option>
											<option value="4" <?php selected( $breakpoint['move_slides'], 4 ); ?>>4
											</option>
										</select>
									</label>
									<div class="option-desc singular"
										 style="display: none;"><?php esc_html_e( 'slide', 'strong-testimonials' ); ?></div>
									<div class="option-desc plural"
										 style="display: none;"><?php esc_html_e( 'slides', 'strong-testimonials' ); ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="is-below">
					<input id="restore-default-breakpoints" type="button" name="restore-default-breakpoints"
						   value="<?php esc_html_e( 'Restore Default Breakpoints', 'strong-testimonials' ); ?>"
						   class="button-secondary"/>
					<span id="restored-message"><?php esc_html_e( 'defaults restored', 'strong-testimonials' ); ?></span>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST Slideshow transition
	 *
	 * @since 2.51.5
	 */
	private function render_field_slideshow_transition() {

		?>
		<td>
			<div class="row">
				<div class="inline inline-middle">
					<label for="view-pause"><?php echo esc_html( _x( 'Show slides for', 'slideshow setting', 'strong-testimonials' ) ); ?></label>
					<input type="number" id="view-pause" class="input-incremental"
						   name="view[data][slideshow_settings][pause]" min=".1" step=".1"
						   value="<?php echo esc_attr( $this->view['slideshow_settings']['pause'] ); ?>" size="3"/>
					<?php echo esc_html( _x( 'seconds', 'time setting', 'strong-testimonials' ) ); ?>
				</div>
				<div class="inline inline-middle then then_slider_type then_show_single then_not_show_multiple fast"
					 style="display: none;">
					<label for="view-effect"><?php esc_html_e( 'then', 'strong-testimonials' ); ?></label>
					<select id="view-effect" name="view[data][slideshow_settings][effect]" class="if selectnot">
						<?php foreach ( $this->view_options['slideshow_effect'] as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"
									<?php selected( $this->view['slideshow_settings']['effect'], $key ); ?>
									<?php echo 'none' == $key ? 'class="trip"' : ''; ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="inline inline-middle then then_slider_type then_not_show_single then_show_multiple fast"
					 style="display: none;">
					<?php esc_html_e( 'then', 'strong-testimonials' ); ?><?php echo esc_html( _x( 'scroll horizontally', 'slideshow transition option', 'strong-testimonials' ) ); ?>
				</div>
				<div class="inline inline-middle then then_effect then_none">
					<label for="view-speed"><?php esc_html_e( 'for', 'strong-testimonials' ); ?></label>
					<input type="number" id="view-speed" class="input-incremental"
						   name="view[data][slideshow_settings][speed]" min=".1" step=".1"
						   value="<?php echo esc_attr( $this->view['slideshow_settings']['speed'] ); ?>" size="3"/>
					<?php echo esc_html( _x( 'seconds', 'time setting', 'strong-testimonials' ) ); ?>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST Slideshow behavior
	 *
	 * @since 2.51.5
	 */
	private function render_field_slideshow_behavior() {

		?>
		<td>
			<div class="row">
				<div class="inline inline-middle">
					<input type="checkbox" id="view-auto_start" name="view[data][slideshow_settings][auto_start]"
						   value="0" <?php checked( $this->view['slideshow_settings']['auto_start'] ); ?>
						   class="checkbox">
					<label for="view-auto_start"><?php echo esc_html( _x( 'Start automatically', 'slideshow setting', 'strong-testimonials' ) ); ?></label>
				</div>
			</div>
			<div class="row">
				<div class="inline inline-middle">
					<input type="checkbox" id="view-continuous_sliding"
						   name="view[data][slideshow_settings][continuous_sliding]"
						   value="0" <?php checked( $this->view['slideshow_settings']['continuous_sliding'] ); ?>
						   class="checkbox">
					<label for="view-continuous_sliding"><?php echo esc_html( _x( 'Continuous Sliding', 'slideshow setting', 'strong-testimonials' ) ); ?></label>
				</div>
			</div>
			<div class="row">
				<div class="inline inline-middle">
					<input type="checkbox" id="view-auto_hover" name="view[data][slideshow_settings][auto_hover]"
						   value="0" <?php checked( $this->view['slideshow_settings']['auto_hover'] ); ?>
						   class="checkbox">
					<label for="view-auto_hover"><?php echo esc_html( _x( 'Pause on hover', 'slideshow setting', 'strong-testimonials' ) ); ?></label>
				</div>
			</div>
			<div class="row">
				<div class="inline inline-middle">
					<input type="checkbox" id="view-stop_auto_on_click"
						   name="view[data][slideshow_settings][stop_auto_on_click]"
						   value="0" <?php checked( $this->view['slideshow_settings']['stop_auto_on_click'] ); ?>
						   class="checkbox">
					<label for="view-stop_auto_on_click"><?php echo esc_html( _x( 'Stop on interaction', 'slideshow setting', 'strong-testimonials' ) ); ?></label>
				</div>
				<div class="inline inline-middle">
					<p class="description"><?php esc_html_e( 'Recommended if using navigation.', 'strong-testimonials' ); ?></p>
				</div>
			</div>
			<?php
			if ( $this->view['slideshow_settings']['adapt_height'] ) {
				$height = 'dynamic';
			} else {
				$height = 'static';
			}
			?>
			<div class="row">
				<div class="row-inner">
					<div class="inline">
						<label for="view-slideshow_height">
							<select id="view-slideshow_height" name="view[data][slideshow_settings][height]"
									class="if selectgroup">
								<?php foreach ( $this->view_options['slideshow_height'] as $key => $type ) : ?>
									<option value="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"
											<?php selected( $height, $key ); ?>>
										<?php echo esc_html( $type ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>
					</div>
					<div class="inline then then_slideshow_height then_dynamic then_not_static" style="display: none;">
						<label for="view-adapt_height_speed"><?php esc_html_e( 'Duration', 'strong-testimonials' ); ?></label>
						<input type="number" id="view-adapt_height_speed" class="input-incremental"
							   name="view[data][slideshow_settings][adapt_height_speed]" min="0" step="0.1"
							   value="<?php echo esc_attr( $this->view['slideshow_settings']['adapt_height_speed'] ); ?>"
							   size="3"/>
						<?php echo esc_html( _x( 'seconds', 'time setting', 'strong-testimonials' ) ); ?>
					</div>
					<div class="inline then then_slideshow_height then_not_dynamic then_static" style="display: none;">
						<input type="checkbox" id="view-stretch" name="view[data][slideshow_settings][stretch]"
							   value="1" <?php checked( $this->view['slideshow_settings']['stretch'] ); ?>
							   class="checkbox">
						<label for="view-stretch"><?php esc_html_e( 'Stretch slides vertically', 'strong-testimonials' ); ?></label>
						<div class="inline description">
							<a href="#tab-panel-wpmtst-help-stretch"
							   class="open-help-tab"><?php esc_html_e( 'Help', 'strong-testimonials' ); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="row tall">
				<p class="description"><?php esc_html_e( 'The slideshow will pause if the browser window becomes inactive.', 'strong-testimonials' ); ?></p>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST Slideshow navigation
	 *
	 * @since 2.51.5
	 */
	private function render_field_slideshow_navigation() {

		?>
		<td>
			<div class="row">
				<div class="row-inner">
					<div class="inline">
						<label for="view-slideshow_controls_type"><?php esc_html_e( 'Controls', 'strong-testimonials' ); ?></label>
						<select id="view-slideshow_controls_type" name="view[data][slideshow_settings][controls_type]"
								class="if selectnot">

							<?php foreach ( $this->view_options['slideshow_nav_method']['controls'] as $key => $type ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"
										<?php selected( $this->view['slideshow_settings']['controls_type'], $key ); ?>
										<?php if ( 'none' == $key ) {
											echo ' class="trip"';
										} ?>>
									<?php echo esc_html( $type['label'] ); ?>
								</option>
							<?php endforeach; ?>

						</select>
					</div>
					<div class="inline then then_slideshow_controls_type" style="display: none;">
						<label for="view-slideshow_controls_style"><?php esc_html_e( 'Style', 'strong-testimonials' ); ?></label>
						<select id="view-slideshow_controls_style"
								name="view[data][slideshow_settings][controls_style]">
							<?php foreach ( $this->view_options['slideshow_nav_style']['controls'] as $key => $style ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->view['slideshow_settings']['controls_style'], $key ); ?>><?php echo esc_html( $style['label'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="row-inner then then_has-pager">
					<div class="inline">
						<label for="view-slideshow_pager_type"><?php esc_html_e( 'Pagination', 'strong-testimonials' ); ?></label>
						<select id="view-slideshow_pager_type" name="view[data][slideshow_settings][pager_type]"
								class="if selectnot">

							<?php foreach ( $this->view_options['slideshow_nav_method']['pager'] as $key => $type ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"
										<?php selected( $this->view['slideshow_settings']['pager_type'], $key ); ?>
										<?php if ( 'none' == $key ) {
											echo ' class="trip"';
										} ?>>
									<?php echo esc_html( $type['label'] ); ?>
								</option>
							<?php endforeach; ?>

						</select>
					</div>
					<div class="inline then then_slideshow_pager_type" style="display: none;">
						<label for="view-slideshow_pager_style"><?php esc_html_e( 'Style', 'strong-testimonials' ); ?></label>
						<select id="view-slideshow_pager_style" name="view[data][slideshow_settings][pager_style]"
								class="if selectnot">
							<?php foreach ( $this->view_options['slideshow_nav_style']['pager'] as $key => $style ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->view['slideshow_settings']['pager_style'], $key ); ?>><?php echo esc_html( $style['label'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="row-inner">
					<div class="then then_slider_type then_show_single then_not_show_multiple" style="display: none;">
						<div class="inline then then_has-position" style="display: none;">
							<label for="view-slideshow_nav_position"><?php esc_html_e( 'Position', 'strong-testimonials' ); ?></label>
							<select id="view-slideshow_nav_position"
									name="view[data][slideshow_settings][nav_position]">
								<?php foreach ( $this->view_options['slideshow_nav_position'] as $key => $label ) : ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->view['slideshow_settings']['nav_position'], $key ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
							<?php //esc_html_e( 'outside', 'strong-testimonials' ); ?>
							<?php esc_html_e( 'the testimonial frame', 'strong-testimonials' ); ?>
						</div>
					</div>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST form category field
	 *
	 * @since 2.51.5
	 */
	private function render_field_form_category() {

		if ( $this->cat_count ) : ?>
			<td>
				<div class="table">

					<?php if ( $this->cat_count > 5 ) : ?>
						<div class="table-row">
							<div class="table-cell">
								<div class="row" style="text-align: right; padding-bottom: 5px;">
									<input type="button" class="expand-cats button" value="expand list"/>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div class="table-row">
						<div class="table-cell">
							<?php wpmtst_form_category_checklist( $this->view_cats_array ); ?>
						</div>
					</div>
				</div>
			</td>
		<?php else : ?>
			<td>
				<p class="description tall"><?php esc_html_e( 'No categories found', 'strong-testimonials' ); ?></p>
			</td>
		<?php endif;
	}

	/**
	 * Render ST form AJAX field
	 *
	 * @since 2.51.5
	 */
	private function render_field_form_ajax() {

		?>
		<td>
			<p class="description tall"><?php echo wp_kses_post( __( 'This will override the <strong>Success Redirect</strong> setting.', 'strong-testimonials' ) ); ?></p>
		</td>
		<?php
	}

	/**
	 * Render ST template list field
	 *
	 * @since 2.51.5
	 */
	private function render_field_template_list() {

		// Assemble list of templates
		$templates      = array(
				'display' => WPMST()->templates->get_templates( 'display' ),
				'form'    => WPMST()->templates->get_templates( 'form' ),
		);
		$template_found = in_array( $this->view['template'], WPMST()->templates->get_template_keys() );

		?>
		<td colspan="2">
			<div id="view-template-list">
				<div class="radio-buttons">

					<?php if ( ! $template_found ) : ?>
						<ul class="radio-list template-list">
							<li>
								<div>
									<input class="error" type="radio"
										   id="<?php echo esc_attr( $this->view['template'] ); ?>"
										   name="view[data][<?php echo esc_attr( $this->current_mode ); ?>]"
										   value="<?php echo esc_attr( $this->view['template'] ); ?>" checked>
									<label for="<?php echo esc_attr( $this->view['template'] ); ?>"><?php echo esc_html( $this->view['template'] ); ?></label>
								</div>
								<div class="template-description">
									<p>
										<span class="dashicons dashicons-warning error"></span>&nbsp;
										<span class="error"><?php esc_html_e( 'not found', 'strong-testimonials' ); ?></span>
									</p>
								</div>
							</li>
						</ul>
					<?php endif; ?>

					<ul class="radio-list template-list">

						<?php foreach ( $templates[ $this->current_type ] as $key => $template ) : ?>
							<li>
								<div>
									<input type="radio" id="template-<?php echo esc_attr( $key ); ?>"
										   name="view[data][<?php echo esc_attr( $this->current_mode ); ?>]"
										   value="<?php echo esc_attr( $key ); ?>" <?php checked( $key, $this->view['template'] ); ?>>
									<label for="template-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $template['config']['name'] ); ?></label>
								</div>
								<div class="template-description">
									<p><?php echo( isset( $template['config']['description'] ) && $template['config']['description'] ? esc_html( $template['config']['description'] ) : esc_html__( 'no description', 'strong-testimonials' ) ) ?></p>
									<div class="options">
										<div>
											<?php if ( ! isset( $template['config']['options'] ) || ! is_array( $template['config']['options'] ) ) : ?>
												<span><?php esc_html_e( 'No options', 'strong-testimonials' ); ?></span>
											<?php else : ?>
												<?php foreach ( $template['config']['options'] as $option ) : ?>
													<div style="margin-bottom: 10px;">
														<?php
														$name = sprintf( 'view[data][template_settings][%s][%s]', esc_attr( $key ), esc_attr( $option->name ) );
														$id   = $key . '-' . $option->name;
													
														switch ( $option->type ) {
															case 'select':

																// Get default if not set
																if ( ! isset( $this->view['template_settings'][ $key ][ $option->name ] ) ) {
																	$this->view['template_settings'][ $key ][ $option->name ] = $option->default;
																}

																if ( $option->label ) {
																	printf( '<label for="%s">%s</label>', esc_attr( $id ), wp_kses_post( $option->label ) );
																}

																printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );

																foreach ( $option->values as $value ) {
																	$selected = selected( $value->value, $this->view['template_settings'][ $key ][ $option->name ], false );
																	printf( '<option value="%s" %s>%s</option>', esc_attr( $value->value ), esc_attr( $selected ), esc_html( $value->description ) );
																}

																echo '</select>';
																break;
															case 'radio':

																if ( ! isset( $this->view['template_settings'][ $key ][ $option->name ] ) ) {
																	$this->view['template_settings'][ $key ][ $option->name ] = $option->default;
																}

																foreach ( $option->values as $value ) {
																	$checked = checked( $value->value, $this->view['template_settings'][ $key ][ $option->name ], false );
																	printf( '<input type="radio" id="%s" name="%s" value="%s" %s>', esc_attr( $id ), esc_attr( $name ), esc_attr( $value->value ), esc_attr( $checked ) );
																	printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $value->description ) );
																}

																break;
															case 'colorpicker':

																if ( $option->label ) {
																	printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $option->label ) );
																}

																$value = isset( $this->view['template_settings'][ $key ][ $option->name ] ) ? $this->view['template_settings'][ $key ][ $option->name ] : $option->default;
																printf( '<input type="text" class="wp-color-picker-field" data-alpha="true" id="%s" name="%s" value="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ) );
																break;
															default:
																do_action( 'wpmtst_views_render_template_option_' . $option->type, $this->view, $key, $option );
																break;
														}
														?>
													</div>
												<?php endforeach; ?>

											<?php endif; ?>
										</div>
									</div>
									<?php do_action( 'wpmtst_views_after_template_options', $this->view, $template, $key ); ?>
								</div>
							</li>
						<?php endforeach; ?>

					</ul>
				</div>
			</div>
			<?php do_action( 'wpmtst_views_after_template_list' ); ?>
		</td>
		<?php
	}

	/**
	 * Render ST layout field
	 *
	 * @since 2.51.5
	 */
	private function render_field_layout() { ?>
		<td colspan="2">
			<div class="section-radios layout-section">
				<div class="radio-buttons">
					<ul class="radio-list layout-list">
						<li>
							<input type="radio" id="view-layout-normal" name="view[data][layout]"
								   value="" <?php checked( false, $this->view['layout'] ); ?>>
							<label for="view-layout-normal"><?php esc_html_e( 'normal', 'strong-testimonials' ); ?></label>
						</li>
						<li>
							<input type="radio" id="view-layout-masonry" name="view[data][layout]"
								   value="masonry" <?php checked( 'masonry', $this->view['layout'] ); ?>>
							<label for="view-layout-masonry"><?php esc_html_e( 'Masonry', 'strong-testimonials' ); ?> </label>
						</li>
						<li>
							<input type="radio"
								   id="view-layout-columns"
								   name="view[data][layout]"
								   value="columns" <?php checked( 'columns', $this->view['layout'] ); ?>>
							<label for="view-layout-columns">
								<?php esc_html_e( 'columns', 'strong-testimonials' ); ?>
							</label>
						</li>
						<li>
							<input type="radio" id="view-layout-grid" name="view[data][layout]"
								   value="grid" <?php checked( 'grid', $this->view['layout'] ); ?>>
							<label for="view-layout-grid"><?php esc_html_e( 'grid', 'strong-testimonials' ); ?></label>
						</li>
					</ul>
				</div>
				<div>
					<div class="radio-description" id="view-layout-info">
						<div class="layout-description view-layout-normal">
							<p><?php esc_html_e( 'A single column.', 'strong-testimonials' ); ?></p>
						</div>
						<div class="layout-description view-layout-masonry">
							<p><?php printf( wp_kses_post( __( 'A cascading, responsive grid using the jQuery plugin <a href="%s" target="_blank">Masonry</a>.', 'strong-testimonials' ) ), esc_url( 'http://masonry.desandro.com/' ) ); ?></p>
							<p><?php esc_html_e( 'The universal solution that works well regardless of testimonial lengths.', 'strong-testimonials' ); ?></p>
							<p><?php esc_html_e( 'Not compatible with pagination.', 'strong-testimonials' ); ?></p>
						</div>
						<div class="layout-description view-layout-columns">
							<p><?php printf( wp_kses_post( __( 'Using <a href="%s" target="_blank">CSS multi-column</a>. Fill from top to bottom, then over to next column.', 'strong-testimonials' ) ), esc_url( 'https://css-tricks.com/guide-responsive-friendly-css-columns/' ) ); ?></p>
							<p><?php esc_html_e( 'Works well with both long and short testimonials.', 'strong-testimonials' ); ?></p>
							<p><?php esc_html_e( 'Compatible with pagination.', 'strong-testimonials' ); ?></p>
						</div>
						<div class="layout-description view-layout-grid">
							<p><?php
								$url = 'https://scotch.io/tutorials/a-visual-guide-to-css3-flexbox-properties';
								printf( wp_kses_post( __( 'Using <a href="%s" target="_blank">CSS flexbox</a>.', 'strong-testimonials' ) ), esc_url( $url ) ); ?>
							</p>
							<p><?php esc_html_e( 'Testimonials will be equal height so this works best when they are about the same length either naturally or using excerpts.', 'strong-testimonials' ); ?></p>
							<p><?php esc_html_e( 'Compatible with pagination.', 'strong-testimonials' ); ?></p>
						</div>
					</div>
					<div class="radio-description options" id="column-count-wrapper">
						<div>
							<label for="view-column-count"><?php esc_html_e( 'Number of columns', 'strong-testimonials' ); ?></label>
							<select id="view-column-count" name="view[data][column_count]">
								<option value="2" <?php selected( $this->view['column_count'], 2 ); ?>>2</option>
								<option value="3" <?php selected( $this->view['column_count'], 3 ); ?>>3</option>
								<option value="4" <?php selected( $this->view['column_count'], 4 ); ?>>4</option>
							</select>
						</div>
					</div>
				</div>
				<div>
					<div class="layout-example view-layout-normal">
						<div class="example-container">
							<div class="box"><span>1</span></div>
							<div class="box size2"><span>2</span></div>
							<div class="box"><span>3</span></div>
							<div class="box size2"><span>4</span></div>
							<div class="box"><span>5</span></div>
						</div>
					</div>
					<div class="layout-example view-layout-masonry">
						<div class="example-container col-2">
							<div class="grid-sizer"></div>
							<div class="box"><span>1</span></div>
							<div class="box size2"><span>2</span></div>
							<div class="box"><span>3</span></div>
							<div class="box size3"><span>4</span></div>
							<div class="box"><span>5</span></div>
							<div class="box size2"><span>6</span></div>
							<div class="box"><span>7</span></div>
							<div class="box size3"><span>8</span></div>
							<div class="box"><span>9</span></div>
						</div>
					</div>
					<div class="layout-example view-layout-columns">
						<div class="example-container col-2">
							<div class="box"><span>1</span></div>
							<div class="box size2"><span>2</span></div>
							<div class="box"><span>3</span></div>
							<div class="box size3"><span>4</span></div>
							<div class="box"><span>5</span></div>
							<div class="box size2"><span>6</span></div>
							<div class="box"><span>7</span></div>
							<div class="box size3"><span>8</span></div>
							<div class="box"><span>9</span></div>
						</div>
					</div>
					<div class="layout-example view-layout-grid">
						<div class="example-container col-2">
							<div class="box"><span>1</span></div>
							<div class="box"><span>2</span></div>
							<div class="box"><span>3</span></div>
							<div class="box"><span>4</span></div>
							<div class="box"><span>5</span></div>
							<div class="box"><span>6</span></div>
							<div class="box"><span>7</span></div>
							<div class="box"><span>8</span></div>
							<div class="box"><span>9</span></div>
						</div>
					</div>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST background field
	 *
	 * @since 2.51.5
	 */
	private function render_field_background() {

		?>
		<td>
			<div class="section-radios background-section">
				<div class="radio-buttons">
					<ul class="radio-list background-list">
						<li>
							<input type="radio" id="bg-none" name="view[data][background][type]"
								   value="" <?php checked( $this->view['background']['type'], '' ); ?>>
							<label for="bg-none"><?php esc_html_e( 'inherit from theme', 'strong-testimonials' ); ?></label>
						</li>
						<li>
							<input type="radio" id="bg-single" name="view[data][background][type]"
								   value="single" <?php checked( $this->view['background']['type'], 'single' ); ?>>
							<label for="bg-single"><?php esc_html_e( 'single color', 'strong-testimonials' ); ?></label>
						</li>
						<li>
							<input type="radio" id="bg-gradient" name="view[data][background][type]"
								   value="gradient" <?php checked( $this->view['background']['type'], 'gradient' ); ?>>
							<label for="bg-gradient"><?php esc_html_e( 'gradient', 'strong-testimonials' ); ?></label>
						</li>
						<li>
							<input type="radio" id="bg-preset" name="view[data][background][type]"
								   value="preset" <?php checked( $this->view['background']['type'], 'preset' ); ?>>
							<label for="bg-preset"><?php esc_html_e( 'preset', 'strong-testimonials' ); ?></label>
						</li>
					</ul>
				</div>
				<div class="radio-description" id="view-background-info">
					<div class="background-description bg-none">
						<div class="description-inner options">
							<div>
								<?php esc_html_e( 'No options', 'strong-testimonials' ); ?>
							</div>
						</div>
					</div>
					<div class="background-description bg-single">
						<div class="description-inner options">
							<div>
								<label>
									<input type="text" id="bg-color" name="view[data][background][color]"
										   value="<?php echo esc_attr( $this->view['background']['color'] ); ?>"
										   class="wp-color-picker-field">
								</label>
							</div>
						</div>
					</div>
					<div class="background-description bg-gradient">
						<div class="description-inner options">
							<div>
								<div class="color-picker-wrap">
									<div>
										<label for="bg-gradient1"><?php esc_html_e( 'From top', 'strong-testimonials' ); ?></label>
									</div>
									<div><input type="text" id="bg-gradient1" name="view[data][background][gradient1]"
												value="<?php echo esc_attr( $this->view['background']['gradient1'] ); ?>"
												class="wp-color-picker-field gradient"></div>
								</div>
							</div>
						</div>
						<div class="description-inner options">
							<div>
								<div class="color-picker-wrap">
									<div>
										<label for="bg-gradient2"><?php esc_html_e( 'To bottom', 'strong-testimonials' ); ?></label>
									</div>
									<div><input type="text" id="bg-gradient2" name="view[data][background][gradient2]"
												value="<?php echo esc_attr( $this->view['background']['gradient2'] ); ?>"
												class="wp-color-picker-field gradient"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="background-description bg-preset">
						<div class="description-inner options">
							<div>
								<label for="view-background-preset">
									<select id="view-background-preset" name="view[data][background][preset]">
										<?php
										$presets        = wpmtst_get_background_presets();
										$current_preset = ( isset( $this->view['background']['preset'] ) && $this->view['background']['preset'] ) ? $this->view['background']['preset'] : '';
										echo '<option value="" ' . selected( $current_preset, '', false ) . '>&mdash;</option>';
										foreach ( $presets as $key => $preset ) {
											echo '<option value="' . esc_attr( $key ) . '" ' . selected( $current_preset, $key, false ) . '>' . esc_html( $preset['label'] ) . '</option>';
										}
										?>
									</select>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</td>

		<td rowspan="2" class="rowspan">
			<div id="view-color-preview" class="table-cell">
				<div class="background-preview-wrap">
					<div id="background-preview">
						Lorem ipsum dolor sit amet, accusam complectitur an eos. No vix perpetua adolescens, vix vidisse
						maiorum
						in. No erat falli scripta qui, vis ubique scripta electram ad. Vix prompta adipisci no, ad
						vidisse
						expetendis.
					</div>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST color field
	 *
	 * @since 2.51.5
	 */
	private function render_field_color() {

		?>
		<td>
			<div class="section-radios font-color-section">
				<div class="radio-buttons">
					<ul class="radio-list font-folor-list">
						<li>
							<input type="radio" id="fc-none" name="view[data][font-color][type]"
								   value="" <?php checked( $this->view['font-color']['type'], '' ); ?>>
							<label for="fc-none"><?php esc_html_e( 'inherit from theme', 'strong-testimonials' ); ?></label>
						</li>
						<li>
							<input type="radio" id="fc-custom" name="view[data][font-color][type]"
								   value="custom" <?php checked( $this->view['font-color']['type'], 'custom' ); ?>>
							<label for="fc-custom"><?php esc_html_e( 'custom', 'strong-testimonials' ); ?></label>
						</li>
					</ul>
				</div>
				<div class="radio-description" id="view-font-color-info">
					<div class="font-color-description fc-none">
						<div class="description-inner options">
							<div><?php esc_html_e( 'No options', 'strong-testimonials' ); ?></div>
						</div>
					</div>
					<div class="font-color-description fc-custom">
						<div class="description-inner options">
							<div>
								<label>
									<input type="text" id="fc-color" name="view[data][font-color][color]"
										   value="<?php echo esc_attr( $this->view['font-color']['color'] ); ?>"
										   class="wp-color-picker-field">
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</td>
		<?php
	}

	/**
	 * Render ST class field
	 *
	 * @since 2.51.5
	 */
	private function render_field_classes() {

		?>
		<td colspan="2">
			<div class="then then_display then_form then_slideshow input" style="display: none;">
				<input type="text" id="view-class" class="long inline" name="view[data][class]"
					   value="<?php echo esc_attr( $this->view['class'] ); ?>">
				<p class="inline description tall">
					<?php esc_html_e( 'For advanced users.', 'strong-testimonials' ); ?>
					<?php esc_html_e( 'Separate class names by spaces.', 'strong-testimonials' ); ?>
				</p>
			</div>
		</td>
		<?php
	}

	/**
	 * Render divi field
	 *
	 * @since 2.51.5
	 */
	private function render_field_divi() {

		?>
		<td>
			<div class="row">
				<div class="row-inner">
					<input type="checkbox" id="view-divi_builder" class="if toggle checkbox"
						   name="view[data][divi_builder]" value="1" <?php checked( $this->view['divi_builder'] ); ?>/>
					<label for="view-divi_builder"><?php esc_html_e( 'Check this if adding this view (via shortcode or widget) using the Visual Builder in <b>Divi Builder version 2</b>.', 'strong-testimonials' ); ?></label>
					<p class="description short"><?php esc_html_e( 'Not required if simply adding this view in the default editor.', 'strong-testimonials' ); ?></p>
					<p class="description short"><?php esc_html_e( 'Not required if simply adding this view in the <b>Divi theme</b> using either the default editor or Divi Builder.', 'strong-testimonials' ); ?></p>
				</div>
			</div>
		</td>
		<?php
	}

		/**
	 * Callback to sort tabs/fields on priority.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public static function sort_data_by_priority( $a, $b ) {
		if ( !isset( $a['priority'], $b['priority'] ) ) {
			return -1;
		}
		if ( $a['priority'] == $b['priority'] ) {
			return 0;
		}
		return $a['priority'] < $b['priority'] ? -1 : 1;
	}
}