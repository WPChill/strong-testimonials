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
				'description' => _x( 'Strong Testimonials widget.', 'description', 'strong-testimonials' )
		);

		$control_ops = array(
				'id_base' => 'wpmtst-widget',
		);

		$this->WP_Widget( 'wpmtst-widget', _x( 'Testimonials', 'widget title', 'strong-testimonials' ), $widget_ops, $control_ops );

		$this->defaults = array(
				'title'         => _x( 'Testimonials', 'widget title', 'strong-testimonials' ),
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
				// New option name will break existing widgets. Wait until 2.0.
				// 'content'       => 'truncated',  // excerpt, truncated, entire
				'char-switch'   => 1,  // 1 = truncated, 2 = entire, 3 = excerpt
				'char-limit'    => 200,
				'show-title'    => 1,
				'images'        => 0,
				'client'        => 1,
				'more'          => 0,  // 0 = none, 1 = testimonial, 2 = page
				'more_page'     => ''
		);

	}

	// -------
	// display
	// -------
	function widget( $args, $instance ) {
		$var = 'tcycle_' . str_replace( '-', '_', $args['widget_id'] );
		
		$options = get_option( 'wpmtst_options' );
		if ( $options['load_widget_style'] ) {
			// Enqueue completely here to be compatible with Page Builder.
			wp_enqueue_style( 'wpmtst-widget-style', WPMTST_DIR . 'css/wpmtst-widget.css' );
		}
			
		// custom action hook: load slider with widget parameters
		do_action(
			'wpmtst_cycle_hook',
			$instance['cycle-effect'],
			$instance['cycle-speed'],
			$instance['cycle-timeout'],
			$instance['cycle-pause'],
			$var
		);
		
		$classes = array( 'wpmtst-widget-container' );
		
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

			array_push( $classes, 'tcycle', $var );
			
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

		$args = array(
				'post_type'      => 'wpm-testimonial',
				'post_status'    => 'publish',
				'posts_per_page' => $num,
				'orderby'        => $orderby,
				'order'          => $order,
		);

		if ( 'all' != $data['category'] ) {
			$args['tax_query'] = array(
					array(
							'taxonomy' => 'wpm-testimonial-category',
							'field'    => 'term_id',
							'terms'    => $data['category'],
							'include_children' => false
					)
			);
		}
	
		$wp_query = new WP_Query();
		$results = $wp_query->query( $args );

		// start HTML output

		$format = '<div class="readmore"><a href="%s">' . _x( 'Read more', 'link', 'strong-testimonials' ) .'</a></div>';

		echo $data['before_widget'];

		if ( ! empty( $title ) )
			echo $data['before_title'] . $title . $data['after_title'];

		echo '<div class="' . join( ' ', $classes ) . '">';

		foreach ( $results as $post ) {
			$post = wpmtst_get_post( $post );

			echo '<div class="testimonial-widget t-slide">';

			if ( $data['show-title'] && $post->post_title )
				echo '<h5>' . $post->post_title . '</h5>';

			/*
			 * Content: excerpt, truncated, or entire
			 *
			 * @since 1.8.2
			 */
			if ( 3 == $char_switch ) {
				$content = do_shortcode( $post->post_excerpt );
			}
			else {
				// process shortcodes then trim on word boundary
				$content = wpautop( do_shortcode( $post->post_content ) );
				if ( 1 == $char_switch )
					$content = wpmtst_truncate( $content, $char_limit );
			}
			
			echo '<div class="content">';
			
			if ( $data['images'] && $post->thumbnail_id )
				echo '<div class="photo">' . get_the_post_thumbnail( $post->ID, array( 75, 75 ) ) . '</div>';
			
			echo $content;
			
			echo '</div>'; // content
			
			if ( $data['client'] )
				echo '<div class="client">' . do_shortcode( wpmtst_client_info( $post ) ) . '</div>';
			
			if ( 1 == $data['more'] )
				echo sprintf( $format, get_permalink( $post ) );
			
			echo '</div>'; // testimonial-widget
		}

		echo '</div><!-- wpmtst-widget-container -->';

		if ( 2 == $data['more'] )
			echo sprintf( $format, get_permalink( $data['more_page'] ) );

		echo $data['after_widget'];
	}

	// ----
	// form
	// ----
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$order_list = array(
				'rand'   => _x( 'Random', 'display order', 'strong-testimonials' ),
				'recent' => _x( 'Newest first', 'display order', 'strong-testimonials' ),
				'oldest' => _x( 'Oldest first', 'display order', 'strong-testimonials' ),
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
		<div class="wpmtst-widget-form">

			<!-- title -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'title' ); ?>">
					<?php
					/* translators: This appears in widget settings. */
					_ex( 'Title', 'widget setting', 'strong-testimonials' );
					?>:
				</label>
				<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="omega" />
			</p>

			<!-- category -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'category' ); ?>">
					<?php
					/* translators: This appears in widget settings. */
					_e( 'Category', 'strong-testimonials' );
					?>:
				</label>
				<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="omega" autocomplete="off">
					<option value="all">
						<?php
						/* translators: This appears in widget settings. */
						_ex( 'Show all', 'categories', 'strong-testimonials' );
						?>
					</option>
					<?php
					foreach ( $category_list as $category ) {
						$data['categories'][$category->term_id] = $category->name . ' (' . $category->count . ')';
						echo '<option value="' . $category->term_id . '"' . selected( $category->term_id, $instance['category'] ) . '>';
						echo $category->name . ' (' . $category->count . ')';
						echo '</option>';
					}
					?>
				</select>
			</p>

			<!-- order -->
			<p>
				<label class="alpha" for="<?php echo $this->get_field_id( 'order' ); ?>">
					<?php
					/* translators: This appears in widget settings. */
					_ex( 'Order', 'noun', 'strong-testimonials' );
					?>:
				</label>
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
							<?php
							/* translators: This appears in widget settings. */
							_ex( 'Cycle Mode', 'display type', 'strong-testimonials' );
							?>
						</label>
					</li>
					<li class="radio-tab <?php if ( 'static' == $instance['mode'] ) { echo ' radio-current'; } ?>">
						<label for="<?php echo $this->get_field_id( 'mode-static' ); ?>">
							<input  id="<?php echo $this->get_field_id( 'mode-static' ); ?>" type="radio" name="<?php echo $this->get_field_name( 'mode' ); ?>" value="static" class="wpmtst-mode-setting" <?php checked( $instance['mode'], 'static' ); ?> />
							<?php
							/* translators: This appears in widget settings. */
							_ex( 'Static Mode', 'display type', 'strong-testimonials' );
							?>
						</label>
					</li>
				</ul>

				<!-- cycle mode -->
				<div class="wpmtst-mode-cycle"<?php if ( 'static' == $instance['mode'] ) { echo ' style="display: none;"'; } ?>>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-limit' ); ?>">
								<?php
								/* translators: This appears in widget settings. */
								_ex( 'Show', 'verb', 'strong-testimonials' );
								?>:
							</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-limit' ); ?>" name="<?php echo $this->get_field_name( 'cycle-limit' ); ?>" value="<?php echo $instance['cycle-limit']; ?>" size="3" <?php if ( $instance['cycle-all'] ) { echo ' readonly="readonly"'; } ?> />
						</div>
						<div class="divider">
							<input type="checkbox" id="<?php echo $this->get_field_id( 'cycle-all' ); ?>" name="<?php echo $this->get_field_name( 'cycle-all' ); ?>" <?php checked( $instance['cycle-all'] ); ?> class="checkbox" />
							<label for="<?php echo $this->get_field_id( 'cycle-all' ); ?>">
								<?php
								/* translators: This appears in widget settings. */
								_e( 'All', 'strong-testimonials' );
								?>
							</label>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>">
								<?php
								/* translators: This appears in widget settings. */
								_ex( 'Show each for', 'slideshow setting', 'strong-testimonials' );
								?>:
							</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-timeout' ); ?>" name="<?php echo $this->get_field_name( 'cycle-timeout' ); ?>" value="<?php echo $instance['cycle-timeout']; ?>" size="3" />
							<?php
							/* translators: This appears in widget settings. */
							_ex( 'seconds', 'time setting', 'strong-testimonials' );
							?>
						</div>
					</div>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'cycle-speed' ); ?>">
								<?php
								/* translators: This appears in widget settings. */
								_ex( 'Effect duration', 'slideshow setting', 'strong-testimonials' );
								?>:
							</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'cycle-speed' ); ?>" name="<?php echo $this->get_field_name( 'cycle-speed' ); ?>" value="<?php echo $instance['cycle-speed']; ?>" size="3" />
							<?php
							/* translators: This appears in widget settings. */
							_ex( 'seconds', 'time setting', 'strong-testimonials' );
							?>
						</div>
					</div>

					<div class="row tall">
						<div>
							<input type="checkbox" id="<?php echo $this->get_field_id( 'cycle-pause' ); ?>" name="<?php echo $this->get_field_name( 'cycle-pause' ); ?>" <?php checked( $instance['cycle-pause'] ); ?>  class="checkbox" />
							<label for="<?php echo $this->get_field_id( 'cycle-pause' ); ?>">
								<?php
								/* translators: This appears in widget settings. */
								_ex( 'Pause on hover', 'slideshow setting', 'strong-testimonials' );
								?>
							</label>
						</div>
					</div>

				</div>

				<!-- static mode -->
				<div class="wpmtst-mode-static"<?php if ( 'cycle' == $instance['mode'] ) { echo ' style="display: none;"'; } ?>>

					<div class="row">
						<div class="alpha">
							<label for="<?php echo $this->get_field_id( 'static-limit' ); ?>">
								<?php
								/* translators: This appears in widget settings. */
								_ex( 'Show', 'verb', 'strong-testimonials' );
								?>:
							</label>
						</div>
						<div>
							<input type="text" id="<?php echo $this->get_field_id( 'static-limit' ); ?>" name="<?php echo $this->get_field_name( 'static-limit' ); ?>" value="<?php echo $instance['static-limit']; ?>" size="3" />
						</div>
					</div>


				</div>

			</div><!-- wpmtst-mode -->

			<!-- content: excerpt, character limit, or entire -->
			<div class="wpmtst-inner-box">
				<p><b><?php _ex( 'Content', 'noun', 'strong-testimonials' ); ?></b></p>
			
				<p>
					<input type="radio" id="<?php echo $this->get_field_id( 'char-switch' ); ?>-3" name="<?php echo $this->get_field_name( 'char-switch' ); ?>" value="3" <?php checked( $instance['char-switch'], 3 ); ?>  class="radio" />
					<label for="<?php echo $this->get_field_id( 'char-switch' ); ?>-3">
						<?php
						/* translators: This appears in widget settings. */
						_e( 'Excerpt', 'strong-testimonials' );
						?>
					</label>
					<span class="widget-help dashicons dashicons-editor-help">
						<span class="help">
							<?php
							/* translators: This appears in widget settings. */
							_e( 'Excerpts are hand-crafted summaries of your testimonial. You may need to enable them in the post editor.', 'strong-testimonials' );
							?>
						</span>
					</span>
				</p>

				<p>
					<input type="radio" id="<?php echo $this->get_field_id( 'char-switch' ); ?>-1" name="<?php echo $this->get_field_name( 'char-switch' ); ?>" value="1" <?php checked( $instance['char-switch'], 1 ); ?>  class="radio" />
					<label for="<?php echo $this->get_field_id( 'char-switch' ); ?>-1">
						<?php
						/* translators: This appears in widget settings. */
						_e( 'Character limit', 'strong-testimonials' );
						?>:&nbsp;
					</label>
					<!-- char limit field -->
					<input type="text" id="<?php echo $this->get_field_id( 'char-limit' ); ?>" name="<?php echo $this->get_field_name( 'char-limit' ); ?>" value="<?php echo $instance['char-limit']; ?>" size="3" <?php if ( 1 != $instance['char-switch'] ) { echo ' readonly="readonly"'; } ?> />
					<span class="widget-help pushdown dashicons dashicons-editor-help">
						<span class="help">
							<?php
							/* translators: This appears in widget settings. */
							_e( 'Will break on a space and add an ellipsis.', 'strong-testimonials' );
							?>
						</span>
					</span>
				</p>
				
				<p>
					<input type="radio" id="<?php echo $this->get_field_id( 'char-switch' ); ?>-2" name="<?php echo $this->get_field_name( 'char-switch' ); ?>" value="2" <?php checked( $instance['char-switch'], 2 ); ?>  class="radio" />
					<label for="<?php echo $this->get_field_id( 'char-switch' ); ?>-2">
						<?php
						/* translators: This appears in widget settings. */
						_e( 'Entire content', 'strong-testimonials' );
						?>
					</label>
				</p>
				
			</div>
			
			<div class="wpmtst-inner-box">
				<p>
					<?php
					/* translators: This appears in widget settings. */
					_ex( 'Show parts', 'display custom fields', 'strong-testimonials' );
					?>
				</p>
			
			<!-- title -->
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'show-title' ); ?>" name="<?php echo $this->get_field_name( 'show-title' ); ?>" <?php checked( $instance['show-title'] ); ?> class="checkbox" />
				<label for="<?php echo $this->get_field_id('show-title'); ?>">
					<?php
					/* translators: This appears in widget settings. */
					_e( 'Title', 'strong-testimonials' );
					?>
				</label>
				<span class="widget-help dashicons dashicons-editor-help">
					<span class="help">
						<?php
						/* translators: This appears in widget settings. Refers to your custom fields.*/
						_ex( 'if included in Fields', 'tooltip', 'strong-testimonials' );
						?>
					</span>
				</span>
			</p>

			<!-- featured images -->
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'images' ); ?>" name="<?php echo $this->get_field_name( 'images' ); ?>" <?php checked( $instance['images'] ); ?> class="checkbox" />
				<label for="<?php echo $this->get_field_id('images'); ?>">
					<?php
					/* translators: This appears in widget settings. */
					_ex( 'Featured Images', 'thumbnail', 'strong-testimonials' );
					?>
				</label>
				<span class="widget-help dashicons dashicons-editor-help">
					<span class="help">
						<?php
						/* translators: This appears in widget settings. Refers to your custom fields.*/
						_ex( 'if included in Fields', 'tooltip', 'strong-testimonials' );
						?>
						</span>
				</span>
			</p>
			
			<!-- client info -->
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'client' ); ?>" name="<?php echo $this->get_field_name( 'client' ); ?>" <?php checked( $instance['client'] ); ?> class="checkbox" />
				<label for="<?php echo $this->get_field_id('images'); ?>">
					<?php
						/* translators: This appears in widget settings. */
						_e( 'Client Info', 'strong-testimonials' );
						?>
				</label>
				<span class="widget-help dashicons dashicons-editor-help">
					<span class="help">
						<?php
						/* translators: This appears in widget settings. */
						_ex( 'if included in Fields', 'tooltip', 'strong-testimonials' );
						?>
						</span>
				</span>
			</p>
			
			</div>

			<!-- read more link -->
			<div class="wpmtst-inner-box">
				<p>
					<?php
					/* translators: This appears in widget settings. */
					_e( '"Read More" link', 'strong-testimonials' );
					?>
				</p>
			
				<p>
					<input type="radio" id="<?php echo $this->get_field_id( 'more' ); ?>-0" name="<?php echo $this->get_field_name( 'more' ); ?>" value="0" <?php checked( 0, $instance['more'] ); ?> class="radio" />
					<label for="<?php echo $this->get_field_id( 'more' ); ?>-0">
						<?php
						/* translators: This appears in widget settings. */
						_ex( 'None', 'no "Read more" link', 'strong-testimonials' );
						?>
					</label>
				</p>
				<p>
					<input type="radio" id="<?php echo $this->get_field_id( 'more' ); ?>-1" name="<?php echo $this->get_field_name( 'more' ); ?>" value="1" <?php checked( 1, $instance['more'] ); ?> class="radio" />
					<label for="<?php echo $this->get_field_id( 'more' ); ?>-1">
						<?php
						/* translators: This appears in widget settings. */
						_ex( 'Link to the testimonial', 'the "Read more" link', 'strong-testimonials' );
						?>
					</label>
				</p>
				<p>
					<input type="radio" id="<?php echo $this->get_field_id( 'more' ); ?>-2" name="<?php echo $this->get_field_name( 'more' ); ?>" value="2" <?php checked( 2, $instance['more'] ); ?> class="radio" />
					<label for="<?php echo $this->get_field_id( 'more' ); ?>-2">
						<?php
						/* translators: This appears in widget settings. */
						_ex( 'Link to', 'the "Read more" link', 'strong-testimonials' );
						?>
					</label>
					<select id="<?php echo $this->get_field_id( 'more_page' ); ?>" name="<?php echo $this->get_field_name( 'more_page' ); ?>" class="" autocomplete="off">
						<option value="*">
							<?php
							/* translators: This appears in widget settings. */
							_ex( '— Select a page —', 'the "Read more" link', 'strong-testimonials' );
							?>
						</option>
						<?php foreach ( $pages_list as $pages ) : ?>
						<option value="<?php echo $pages->ID; ?>" <?php selected( $instance['more_page'], $pages->ID ); ?>>
							<?php echo $pages->post_title; ?>
						</option>
						<?php endforeach; ?>
					</select>
				</p>
			</div>

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

		// $instance['cycle-effect'] = strip_tags( $new_instance['cycle-effect'] );
		$instance['cycle-effect'] = 'fade';

		if ( ! $new_instance['cycle-speed'] ) {
			$instance['cycle-speed'] = $defaults['cycle-speed'];
		}
		else {
			$instance['cycle-speed'] = (float) strip_tags( $new_instance['cycle-speed'] );
		}

		$instance['cycle-pause'] = isset( $new_instance['cycle-pause'] ) ? 1 : 0;

		$instance['static-limit'] = (int) strip_tags( $new_instance['static-limit'] );

		// previous: checkbox (on/off)
		// $instance['char-switch'] = isset( $new_instance['char-switch'] ) ? 1 : 0;
		// new: radio (1,2,3)
		$instance['char-switch'] = $new_instance['char-switch'];
		$instance['char-limit']  = (int) strip_tags( $new_instance['char-limit'] );

		if ( 1 == $instance['char-switch'] && ! $instance['char-limit'] ) {
			// if limit selected and value cleared out then restore default value
			$instance['char-limit'] = $defaults['char-limit'];
		}

		$instance['show-title'] = isset( $new_instance['show-title'] ) ? 1 : 0;
		$instance['images']     = isset( $new_instance['images'] ) ? 1 : 0;
		$instance['client']     = isset( $new_instance['client'] ) ? 1 : 0;

		// previous: checkbox (on/off)
		// $instance['more']      = isset( $new_instance['more'] ) ? 1 : 0;
		// new: radio (0,1,2)
		$instance['more']      = $new_instance['more'];
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
