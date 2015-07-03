<?php
/**
 * Strong Testimonials - View widget
 * 
 * @since 1.21.0
 */

class Strong_Testimonials_View_Widget extends WP_Widget {

	function __construct() {

		parent::__construct( 
			'strong-testimonials-view-widget', 
			_x( 'Strong Testimonials', 'widget label', 'strong-testimonials' ),
			array(
				'classname'   => 'strong-testimonials-view-widget',
				'description' => _x( 'Add one of your testimonial views.', 'widget description', 'strong-testimonials' )
			),
			array(
				'id_base' => 'strong-testimonials-view-widget',
			) 
		);

		$this->defaults = array(
			'title' => _x( 'Testimonials', 'widget title', 'strong-testimonials' ),
			'view'  => '0',
		);

	}

	function widget( $args, $instance ) {
		$data  = array_merge( $args, $instance );
		$title = apply_filters( 'widget_title', empty( $data['title'] ) ? '' : $data['title'], $instance, $this->id_base );

		echo $data['before_widget'];

		if ( ! empty( $title ) )
			echo $data['before_title'] . $title . $data['after_title'];

		/**
		 * This is the equivalent of:  echo do_shortcode( '[testimonial_view id="' . $instance['view'] . '"]' );
		 * Catch undefined view to avoid error in the Customizer.
		 */
		if ( isset( $data['view'] ) && $data['view'] )
			echo wpmtst_strong_view_shortcode( $instance, null );
		
		echo $data['after_widget'];
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$views = wpmtst_get_views();
		?>
		<div class="wpmtst-widget-form">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">
					<?php _ex( 'Title:', 'widget setting', 'strong-testimonials' ); ?>
				</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
				       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'view' ); ?>">
					<?php _ex( 'View:', 'widget setting', 'strong-testimonials' ); ?>
				</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'view' ); ?>"
				        name="<?php echo $this->get_field_name( 'view' ); ?>" autocomplete="off">
					<option value=""><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php
					foreach ( $views as $view ) {
						printf( '<option value="%s" %s>%s</option>', $view['id'], selected( $view['id'], $instance['view'] ), $view['name'] );
					}
					?>
				</select>
			</p>
		</div>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$new_instance['title'] = sanitize_text_field( $new_instance['title'] );
		return array_merge( $this->defaults, $new_instance );
	}

}


/**
 * Load widget
 */
function wpmtst_load_view_widget() {
	register_widget( 'Strong_Testimonials_View_Widget' );
}
add_action( 'widgets_init', 'wpmtst_load_view_widget' );
