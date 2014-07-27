<?php
/**
 * Strong Testimonials - Widget functions
 */

 
/*
 * Widget
 */
class WpmTst_Widget extends WP_Widget {

	// -----
	// setup
	// -----
	function WpmTst_Widget() {

		$widget_ops = array(
				'classname'   => 'wpmtst-widget',
				'description' => __( 'Several ways to show testimonials.', 'strong-testimonials' )
		);

		$control_ops = array(
				'id_base' => 'wpmtst-widget',
				'width'   => '280px',
		);

		$this->cycle_options = array(
				'effects' => array(
						'fade'       => __( 'Fade', 'strong-testimonials' ),
						// 'scrollHorz' => 'Scroll horizontally',
						// 'none'       => 'None',
				)
		);

		$this->WP_Widget( 'wpmtst-widget', __( 'Strong Testimonials', 'strong-testimonials' ), $widget_ops, $control_ops );

		$this->defaults = array(
				'title'         => __( 'Testimonials', 'strong-testimonials' ),
				'category'      => 'all',
				'mode'          => 'cycle',	// 'cycle' or 'static'
				'order'         => 'rand',
				'cycle-limit'   => 3,
				'cycle-all'     => 0,
				'cycle-timeout' => 8,
				'cycle-effect'  => 'fade',
				'cycle-speed'   => 1.5,
				'cycle-pause'   => 1,
				'static-limit'  => 2,
				'char-switch'   => 1,
				'char-limit'    => 200,
				'images'        => 0,
				'more'          => 0,
				'more_page'     => ''
		);

	}

	// -------
	// display
	// -------
	function widget( $args, $instance ) {
		if ( is_active_widget( false, false, $this->id_base ) ) {
		
			$options = get_option( 'wpmtst_options' );
			if ( $options['load_widget_style'] )
				wp_enqueue_style( 'wpmtst-widget-style' );
				
			// custom action hook: load slider with widget parameters
			do_action(
				'wpmtst_cycle_hook',
				$instance['cycle-effect'],
				$instance['cycle-speed'],
				$instance['cycle-timeout'],
				$instance['cycle-pause'],
				'.wpmtst-widget-container',
				'cycleWidget'
			);
			
		}

		$classes = array();
		$data = array_merge( $args, $instance );
		// Polylang filter
		$title = apply_filters( 'widget_title', empty( $data['title'] ) ? '' : $data['title'], $instance, $this->id_base );

		// build query

		if ( 'rand' == $data['order'] ) {
			$orderby = 'rand';
			$order   = '';
		}
		elseif ( 'oldest' == $data['order'] ) {
			$orderby = 'post_date';
			$order   = 'ASC';
		}
		else {
			$orderby = 'post_date';
			$order   = 'DESC';
		}

		if ( 'cycle' == $data['mode'] ) {

			$classes[] = 'tcycle';
			if ( $data['cycle-all'] )
				$num = -1;
			elseif ( ! empty( $data['cycle-limit'] ) )
				$num = $data['cycle-limit'];
			else
				$num = $this->defaults['cycle-limit'];

		}
		else {

			if ( ! empty( $data['static-limit'] ) )
				$num = $data['static-limit'];
			else
				$num = $this->defaults['static-limit'];

		}

		$char_switch = $data['char-switch'];

		if ( (int) $data['char-limit'] )
			$char_limit = $data['char-limit'];
		else
			$char_limit = $this->defaults['char-limit'];

		$terms = wpmtst_get_terms( $data['category'] );

		$args = array(
				$terms['taxo']   => $terms['term'],
				'post_type'      => 'wpm-testimonial',
				'post_status'    => 'publish',
				'posts_per_page' => $num,
				'orderby'        => $orderby,
				'order'          => $order,
		);

		$wp_query = new WP_Query();
		$results = $wp_query->query( $args );

		// start HTML output

		echo $data['before_widget'];

		if ( ! empty( $title ) )
			echo $data['before_title'] . $title . $data['after_title'];

		echo '<div class="wpmtst-widget-container ' . join( ' ', $classes ) . '">';

		foreach ( $results as $post ) {
			$post = wpmtst_get_post( $post );

			echo '<div class="testimonial-widget">';

			if ( $post->post_title )
				echo '<h5>' . $post->post_title . '</h5>';

			// process shortcodes then trim on word boundary
			$content = wpautop( do_shortcode( $post->post_content ) );
			if ( $char_switch )
				$content = wpmtst_truncate( $content, $char_limit );
			echo '<div class="content">';
			if ( $data['images'] ) {
				if ( $post->thumbnail_id )
					echo '<div class="photo">' . get_the_post_thumbnail( $post->ID, array( 75, 75 ) ) . '</div>';
			}
			echo $content;
			echo '</div>';
			echo '<div class="client">' . do_shortcode( wpmtst_client_info( $post ) ) . '</div><!-- client -->';
			echo '</div>';
		}

		echo '</div><!-- wpmtst-widget-container -->';

		if ( $data['more'] ) {
			$link = get_permalink( $data['more_page'] );
			echo '<p class="readmore"><a href="' . $link . '">' . __( 'Read More Testimonials', 'strong-testimonials' ) .'</a></p>';
		}

		echo $data['after_widget'];
	}

	// ----
	// form
	// ----
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$order_list = array(
				'rand'   => __( 'Random', 'strong-testimonials' ),
				'recent' => __( 'Newest first', 'strong-testimonials' ),
				'oldest' => __( 'Oldest first', 'strong-testimonials' ),
		);

		$category_list = get_terms( 'wpm-testimonial-category', array(
				'hide_empty' 	=> false,
				'order_by'		=> 'name',
				'pad_counts'	=> true
		) );

		$pages_list = get_pages( array(
				'sort_order'  => 'ASC',
				'sort_column' => 'post_title',
				'post_type'   => 'page',
				'post_status' => 'publish'
		) );

		?>
		<div class="wpmtst-widget">

			<!-- title -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'strong-testimonials' ) ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="omega" />
			</p>

			<!-- category -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category', 'strong-testimonials' ) ?>:</label>
				<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="omega" autocomplete="off">
					<option value="all"><?php _e( 'Show all', 'strong-testimonials' ) ?></option>
					<?php
					foreach ( $category_list as $category ) {
						$data['categories'][$category->term_id] = $category->name . ' (' . $category->count . ')';
						echo '<option value="' . $category->term_id . '"' . selected( $category->term_id, $instance['category'] ) . '>' . $category->name . ' (' . $category->count . ')' . '</option>';
					}
					?>
				</select>
			</p>

			<!-- order -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order', 'strong-testimonials' ) ?>:</label>
				<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="omega" autocomplete="off">
					<?php
					foreach ( $order_list as $order => $order_label ) {
						echo '<option value="' . $order . '"' . selected( $order, $instance['order'] ) . '>' . $order_label . '</option>';
					}
					?>
				</select>
			</p>

			<!-- display mode -->
			<div class="wpmtst-mode">

				<ul>
					<li class="radio-tab <?php if ( 'cycle' == $instance['mode'] ) { echo ' radio-current'; } ?>">
						<label for="<?php echo $this->get_field_id( 'mode-cycle' ); ?>">
							<input  id="<?php echo $this->get_field_id( 'mode-cycle' ); ?>" type="radio" name="<?php echo $this->get_field_name( 'mode' ); ?>" value="cycle" class="wpmtst-mode-setting" <?php checked( $instance['mode'], 'cycle' ); ?> />
							<?php _e( 'Cycle Mode', 'strong-testimonials' ) ?></label>
					</li>
					<li class="radio-tab <?php if ( 'static' == $instance['mode'] ) { echo ' radio-current'; } ?>">
						<label for="<?php echo $this->get_field_id( 'mode-static' ); ?>">
							<input  id="<?php echo $this->get_field_id( 'mode-static' ); ?>" type="radio" name="<?php echo $this->get_field_name( 'mode' ); ?>" value="static" class="wpmtst-mode-setting" <?php checked( $instance['mode'], 'static' ); ?> />
							<?php _e( 'Static Mode', 'strong-testimonials' ) ?></label>
					</li>
				</ul>

				<!-- cycle mode -->
				<div class="wpmtst-mode-cycle"<?php if ( 'static' == $instance['mode'] ) { echo ' style="display: none;"'; } ?>>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-limit' ); ?>"><?php _e( 'Number to show', 'strong-testimonials' ); ?>:</label>
						</div>
						<div>
							<input  type="text" id="<?php echo $this->get_field_id( 'cycle-limit' ); ?>" name="<?php echo $this->get_field_name( 'cycle-limit' ); ?>" value="<?php echo $instance['cycle-limit']; ?>" size="3" <?php if ( $instance['cycle-all'] ) { echo ' readonly="readonly"'; } ?> />
						</div>
						<div class="divider">
							<input  type="checkbox" id="<?php echo $this->get_field_id( 'cycle-all' ); ?>" name="<?php echo $this->get_field_name( 'cycle-all' ); ?>" <?php checked( $instance['cycle-all'], 1 ); ?> class="checkbox" />
							<label for="<?php echo $this->get_field_id( 'cycle-all' ); ?>"><?php _e( 'Show all', 'strong-testimonials' ); ?></label>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>"><?php _e( 'Show each for', 'strong-testimonials' ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>" name="<?php echo $this->get_field_name( 'cycle-timeout' ); ?>" value="<?php echo $instance['cycle-timeout']; ?>" size="3" />
							<?php _e( 'seconds', 'strong-testimonials' ); ?>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-effect' ); ?>"><?php _e( 'Transition effect', 'strong-testimonials' ); ?>:</label>
						</div>
						<div>
							<select id="<?php echo $this->get_field_id( 'cycle-effect' ); ?>" name="<?php echo $this->get_field_name( 'cycle-effect' ); ?>" autocomplete="off">
								<?php foreach ( $this->cycle_options['effects'] as $key => $label ) : ?>
								<option value="<?php echo $key; ?>" <?php selected( $instance['cycle-effect'], $key ); ?>><?php echo $label; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="help"><a href="http://wordpress.org/support/topic/settings-bug-1" target="_blank">Fade is the only effect for now</a></div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-speed' ); ?>"><?php _e( 'Effect duration', 'strong-testimonials' ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-speed' ); ?>" name="<?php echo $this->get_field_name( 'cycle-speed' ); ?>" value="<?php echo $instance['cycle-speed']; ?>" size="3" />
							<?php _e( 'seconds', 'strong-testimonials' ); ?>
						</div>
					</div>

					<div class="row tall">
						<div>
							<input type="checkbox" id="<?php echo $this->get_field_id( 'cycle-pause' ); ?>" name="<?php echo $this->get_field_name( 'cycle-pause' ); ?>" <?php checked( $instance['cycle-pause'] ); ?>  class="checkbox" />
							<label for="<?php echo $this->get_field_id( 'cycle-pause' ); ?>"><?php _e( 'Pause on hover', 'strong-testimonials' ); ?></label>
						</div>
					</div>

				</div>

				<!-- static mode -->
				<div class="wpmtst-mode-static"<?php if ( 'cycle' == $instance['mode'] ) { echo ' style="display: none;"'; } ?>>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'static-limit' ); ?>"><?php _e( 'Number to show', 'strong-testimonials' ); ?>:</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'static-limit' ); ?>" name="<?php echo $this->get_field_name( 'static-limit' ); ?>" value="<?php echo $instance['static-limit']; ?>" size="3" />
						</div>
					</div>


				</div>

			</div><!-- wpmtst-mode -->

			<!-- character limit -->
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'char-switch' ); ?>" name="<?php echo $this->get_field_name( 'char-switch' ); ?>" <?php checked( $instance['char-switch'] ); ?>  class="checkbox" />

				<label for="<?php echo $this->get_field_id( 'char-limit' ); ?>"><?php _e( 'Character limit', 'strong-testimonials' ); ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'char-limit' ); ?>" name="<?php echo $this->get_field_name( 'char-limit' ); ?>" value="<?php echo $instance['char-limit']; ?>" size="3" <?php if ( ! $instance['char-switch'] ) { echo ' readonly="readonly"'; } ?> />
				<span class="help"><?php _e( 'Will break on a space and add an ellipsis.', 'strong-testimonials' ); ?></span>
			</p>

			<!-- featured images -->
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'images' ); ?>" name="<?php echo $this->get_field_name( 'images' ); ?>" <?php checked( $instance['images'] ); ?> class="checkbox" />
				<label for="<?php echo $this->get_field_id('images'); ?>"><?php _e( 'Show Featured Images', 'strong-testimonials' ); ?></label><span class="help"><?php _e( '(if included in Field Group)', 'strong-testimonials' ); ?></span>
			</p>

			<!-- read more link -->
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'more' ); ?>" name="<?php echo $this->get_field_name( 'more' ); ?>" <?php checked( $instance['more'] ); ?> class="checkbox" />
				<label for="<?php echo $this->get_field_id( 'more' ); ?>"><?php _e( 'Show "Read More" link to this page', 'strong-testimonials' ); ?>:</label>
			</p>

			<p>
				<select id="<?php echo $this->get_field_id( 'more_page' ); ?>" name="<?php echo $this->get_field_name( 'more_page' ); ?>" class="widefat" autocomplete="off">
					<option value="*"><?php _e( 'Select page', 'strong-testimonials' ) ?></option>
					<?php foreach ( $pages_list as $pages ) : ?>
						<option value="<?php echo $pages->ID; ?>" <?php selected( $instance['more_page'], $pages->ID ); ?>><?php echo $pages->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</p>

		</div><!-- wpmtst-widget -->
		<?php
	}

	// ----
	// save
	// ----
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$defaults = $this->defaults;

		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['order']    = strip_tags( $new_instance['order'] );
		$instance['mode']     = strip_tags( $new_instance['mode'] );

		if ( ! $new_instance['cycle-limit'] ) {
			$instance['cycle-limit'] = $defaults['cycle-limit'];
		}
		else {
			$instance['cycle-limit'] = (int) strip_tags( $new_instance['cycle-limit'] );
		}

		$instance['cycle-all'] = isset( $new_instance['cycle-all'] ) ? 1 : 0;

		if ( ! $new_instance['cycle-timeout'] ) {
			$instance['cycle-timeout'] = $defaults['cycle-timeout'];
		}
		else {
			$instance['cycle-timeout'] = (float) strip_tags( $new_instance['cycle-timeout'] );
		}

		$instance['cycle-effect'] = strip_tags( $new_instance['cycle-effect'] );

		if ( ! $new_instance['cycle-speed'] ) {
			$instance['cycle-speed'] = $defaults['cycle-speed'];
		}
		else {
			$instance['cycle-speed'] = (float) strip_tags( $new_instance['cycle-speed'] );
		}

		$instance['cycle-pause'] = isset( $new_instance['cycle-pause'] ) ? 1 : 0;

		$instance['static-limit'] = (int) strip_tags( $new_instance['static-limit'] );

		$instance['char-switch'] = isset( $new_instance['char-switch'] ) ? 1 : 0;
		$instance['char-limit']  = (int) strip_tags( $new_instance['char-limit'] );

		if ( $instance['char-switch'] && ! $instance['char-limit'] ) {
			// if limit turned on and value cleared out then restore default value
			$instance['char-limit'] = $defaults['char-limit'];
		}

		$instance['images'] = isset( $new_instance['images'] ) ? 1 : 0;

		$instance['more']      = isset( $new_instance['more'] ) ? 1 : 0;
		$instance['more_page'] = strip_tags( $new_instance['more_page'] );

		return $instance;
	}

}


/*
 * Load widget
 */
function wpmtst_load_widget() {
	register_widget( 'WpmTst_Widget' );
}
add_action( 'widgets_init', 'wpmtst_load_widget' );
