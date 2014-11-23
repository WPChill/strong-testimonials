<?php
/**
 * Cycle shortcode settings form
 *
 * @package Strong_Testimonials
 */
?>
<div class="update-nag"><?php _e( 'This shortcode will be deprecated soon. Please migrate to the <code>[strong]</code> shortcode.', 'strong-testimonials' ); ?></div>

<table class="form-table" cellpadding="0" cellspacing="0">

<!-- category -->
<tr valign="top">
	<th scope="row">
		<label for="cycle-category">
			<?php
			/* translators: This is on the Cycle Shortcode settings screen. */
			_e( 'Category', 'strong-testimonials' );
			?>
		</label>
	</th>
	<td>
		<select id="cycle-category" name="wpmtst_cycle[category]" autocomplete="off">
			<option value="all" <?php selected( 'all', $cycle['category'] ); ?>>
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_e( 'All categories', 'strong-testimonials' );
				?>
			</option>
			<?php
			foreach ( $category_list as $category ) {
				$data['categories'][$category->term_id] = $category->name . ' (' . $category->count . ')';
				echo '<option value="' . $category->term_id . '"' . selected( $category->term_id, $cycle['category'] ) . '>';
				echo $category->name . ' (' . $category->count . ')';
				echo '</option>';
			}
			?>
		</select>
	</td>
</tr>

<!-- order -->
<tr valign="top">
	<th scope="row">
		<label for="cycle-order">
			<?php
			/* translators: This is on the Cycle Shortcode settings screen. */
			_ex( 'Order', 'noun', 'strong-testimonials' );
			?>
		</label>
	</th>
	<td>
		<select id="cycle-order" name="wpmtst_cycle[order]" autocomplete="off">
			<?php
			foreach ( $order_list as $order => $order_label ) {
				echo '<option value="' . $order . '"' . selected( $order, $cycle['order'] ) . '>' . $order_label . '</option>';
			}
			?>
		</select>
	</td>
</tr>

<!-- limit -->
<tr valign="top">
	<th scope="row">
		<?php
		/* translators: This is on the Cycle Shortcode settings screen. */
		_ex( 'How many?', 'quantity', 'strong-testimonials' );
		?>
	</th>
	<td>
	
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[all]" <?php checked( 1, $cycle['all'] ); ?> value="1" />
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_e( 'All', 'strong-testimonials' );
				?>
			</label>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[all]" <?php checked( 0, $cycle['all'] ); ?> value="0" /><input type="number" min="1" id="cycle-limit" name="wpmtst_cycle[limit]" value="<?php echo $cycle['limit']; ?>" size="5" />
			</label>
		</div>
		
	</td>
</tr>

<!-- start: show parts -->
<tr valign="top">
	<th scope="row" class="parent">
		<?php
			/* translators: This is on the Cycle Shortcode settings screen. */
			_ex( 'Show', 'verb', 'strong-testimonials' );
		?>
	</th>
	<!-- title -->
	<td class="">
		<input type="checkbox" id="cycle-title" name="wpmtst_cycle[title]" <?php checked( $cycle['title'] ); ?> class="checkbox" />
		<label for="cycle-title">
			<?php
			/* translators: This is on the Cycle Shortcode settings screen. Refers to your custom fields.*/
			_ex( 'Title <em>(if included in Fields)</em>', 'the testimonial title', 'strong-testimonials' );
			?>
			</label>
	</td>
</tr>

<tr valign="top">
	<th scope="row" class="child parent">
	</th>
	<!-- content -->
	<td class="child">
	
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[content]" <?php checked( 'excerpt', $cycle['content'] ); ?> value="excerpt" />
				<?php 
				/* translators: This is on the Cycle Shortcode settings screen. */
				_ex( 'Excerpt', 'the testimonial excerpt', 'strong-testimonials' ); 
				?>
				<p class="description">
					<?php
					/* translators: This is on the Cycle Shortcode settings screen. */
					_e( 'Excerpts are hand-crafted summaries of your testimonial.', 'strong-testimonials' ); 
					?>
					<br />
					<?php
					/* translators: This is on the Cycle Shortcode settings screen. */
					_e( 'You may need to enable them in the post editor like in this <a id="toggle-screen-options" href="#">screenshot</a>.', 'strong-testimonials' );
					?>
					<div class="screenshot" id="screenshot-screen-options">
						<div style="background: url(<?php echo WPMTST_DIR; ?>/images/screen-options.png); height: 241px; width: 730px;"></div>
					</div>
				</p>
			</label>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[content]" <?php checked( 'truncated', $cycle['content'] ); ?> value="truncated" />
				<?php
				$input = '<input type="number" min="10" max="995" step="5" id="cycle-char-limit" name="wpmtst_cycle[char_limit]" value="' . $cycle['char_limit'] . '" size="3" />';
				/* translators: This is on the Cycle Shortcode settings screen. %s is a number input field. */
				printf( _x( 'Content up to %s characters', 'display setting', 'strong-testimonials' ), $input );
				?>
			</label>
			<p class="description">
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_e( 'Will break on a space and add an ellipsis.', 'strong-testimonials' );
				?>
			</p>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[content]" <?php checked( 'entire', $cycle['content'] ); ?> value="entire" />
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_ex( 'Entire content', 'display setting', 'strong-testimonials' );
				?>
			</label>
		</div>
		
	</td>
</tr>

<tr valign="top">
	<th scope="row" class="child parent">
	</th>
	<!-- featured images -->
	<td class="child">
		<input type="checkbox" id="cycle-images" name="wpmtst_cycle[images]" <?php checked( $cycle['images'] ); ?> class="checkbox" />
		<label for="cycle-images">
			<?php
			/* translators: This is on the Cycle Shortcode settings screen. Refers to your custom fields.*/
			_e( 'Featured Images <em>(if included in Fields)</em>', 'strong-testimonials' );
			?>
		</label>
	</td>
</tr>

<tr valign="top">
	<th scope="row" class="child">
	</th>
	<!-- client_info -->
	<td class="child">
		<input type="checkbox" id="cycle-client" name="wpmtst_cycle[client]" <?php checked( $cycle['client'] ); ?> class="checkbox" />
		<label for="cycle-client">
			<?php
			/* translators: This is on the Cycle Shortcode settings screen. Refers to your custom fields.*/
			_e( 'Client Information <em>(if included in Fields)</em>', 'strong-testimonials' );
			?>
		</label>
	</td>
</tr>
<!-- end: show parts -->

<!-- read more -->
<tr valign="top">
	<th scope="row">
		<?php
		/* translators: This is on the Cycle Shortcode settings screen. */
		_e( '"Read More" link', 'strong-testimonials' );
		?>
	</th>
	<td>
	
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[more]" <?php checked( 0, $cycle['more'] ); ?> value="0" />
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_ex( 'None', 'no "Read more" link', 'strong-testimonials' );
				?>
			</label>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[more]" <?php checked( 1, $cycle['more'] ); ?> value="1" />
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_ex( 'Link to the testimonial', 'the "Read more" link', 'strong-testimonials' );
				?>
			</label>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[more]" <?php checked( 2, $cycle['more'] ); ?> value="2" />
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_ex( 'Link to', 'the "Read more" link', 'strong-testimonials' );
				?>&nbsp;
			</label>
			<select id="cycle-more-page" name="wpmtst_cycle[more_page]" autocomplete="off">
				<option value="">
					<?php
					/* translators: This is on the Cycle Shortcode settings screen. */
					_ex( '— Select a page —', 'the "Read more" link', 'strong-testimonials' );
					?>
				</option>
				<?php foreach ( $pages_list as $pages ) : ?>
					<option value="<?php echo $pages->ID; ?>" <?php selected( $cycle['more_page'], $pages->ID ); ?>><?php echo $pages->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		
	</td>
</tr>

<!-- effect -->
<tr valign="top">
	<th scope="row">
		<?php
		/* translators: This is on the Cycle Shortcode settings screen. */
		_ex( 'Transition', 'slideshow effect', 'strong-testimonials' );
		?>
	</th>
 <td>
		
		<div class="row"><!-- timeout -->
			<div class="alpha">
				<label for="cycle-timeout">
					<?php
					/* translators: This is on the Cycle Shortcode settings screen. */
					_ex( 'Show each for', 'slideshow setting', 'strong-testimonials' );
					?>
				</label>
			</div>
			<div>
				<input type="text" id="cycle-timeout" name="wpmtst_cycle[timeout]" value="<?php echo $cycle['timeout']; ?>" size="3" />
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_ex( 'seconds', 'time setting', 'strong-testimonials' );
				?>
			</div>
		</div>
		
		<!-- effect -->
		<input type="hidden" name="wpmtst_cycle[effect]" value="fade" />
		
		<div class="row"><!-- duration -->
			<div class="alpha">
				<label for="cycle-speed">
					<?php
					/* translators: This is on the Cycle Shortcode settings screen. */
					_ex( 'Effect duration', 'slideshow setting', 'strong-testimonials' );
					?>
				</label>
			</div>
			<div>
				<input type="text" id="cycle-speed" name="wpmtst_cycle[speed]" value="<?php echo $cycle['speed']; ?>" size="3" />
				<?php
				/* translators: This is on the Cycle Shortcode settings screen. */
				_ex( 'seconds', 'time setting', 'strong-testimonials' );
				?>
			</div>
		</div>

		<div class="row"><!-- pause -->
			<div>
				<input type="checkbox" id="cycle-pause" name="wpmtst_cycle[pause]" <?php checked( $cycle['pause'] ); ?>  class="checkbox" />
				<label for="cycle-pause">
					<?php
					/* translators: This is on the Cycle Shortcode settings screen. */
					_ex( 'Pause on hover', 'slideshow setting', 'strong-testimonials' );
					?>
				</label>
			</div>
		</div>
	</td>
</tr>

</table>
