<?php
/**
 * Strong Testimonials
 * Cycle shortcode settings form
 */
?>
<table class="form-table" cellpadding="0" cellspacing="0">

<!-- category -->
<tr valign="top">
	<th scope="row">
		<label for="cycle-category"><?php _e( 'Category', 'strong-testimonials' ) ?></label>
	</th>
	<td>
		<select id="cycle-category" name="wpmtst_cycle[category]" autocomplete="off">
			<option value="all" <?php selected( 'all', $cycle['category'] ); ?>><?php _e( 'All categories', 'strong-testimonials' ) ?></option>
			<?php
			foreach ( $category_list as $category ) {
				$data['categories'][$category->term_id] = $category->name . ' (' . $category->count . ')';
				echo '<option value="' . $category->term_id . '"' . selected( $category->term_id, $cycle['category'] ) . '>' . $category->name . ' (' . $category->count . ')' . '</option>';
			}
			?>
		</select>
	</td>
</tr>

<!-- order -->
<tr valign="top">
	<th scope="row">
		<label for="cycle-order"><?php _e( 'Order', 'strong-testimonials' ); ?></label>
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
		How Many?
	</th>
	<td>
	
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[all]" <?php checked( 1, $cycle['all'] ); ?> value="1" /><?php _e( 'All', 'strong-testimonials' ); ?>
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
		Show
	</th>
	<!-- title -->
	<td class="">
		<input type="checkbox" id="cycle-title" name="wpmtst_cycle[title]" <?php checked( $cycle['title'] ); ?> class="checkbox" />
		<label for="cycle-title"><?php _e( 'Title', 'strong-testimonials' ); ?>&nbsp;<em><?php _e( '(if included in Fields)', 'strong-testimonials' ); ?></em></label>
	</td>
</tr>

<tr valign="top">
	<th scope="row" class="child parent">
	</th>
	<!-- content -->
	<td class="child">
	
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[content]" <?php checked( 'excerpt', $cycle['content'] ); ?> value="excerpt" /><?php _e( 'Excerpt', 'strong-testimonials' ); ?>
				<p class="description">
					<?php _e( 'Excerpts are hand-crafted summaries of your testimonial.', 'strong-testimonials' ); ?>
					<?php _e( 'You may need to <a id="toggle-screen-options" href="#">enable them</a>.', 'strong-testimonials' ); ?>
					<div class="screenshot" id="screenshot-screen-options"><img src="<?php echo WPMTST_DIR; ?>/images/screen-options.png" /></div>
				</p>
			</label>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[content]" <?php checked( 'truncated', $cycle['content'] ); ?> value="truncated" /><?php _e( 'Content up to', 'strong-testimonials' ); ?>&nbsp;<input type="number" min="10" max="995" step="5" id="cycle-char-limit" name="wpmtst_cycle[char-limit]" value="<?php echo $cycle['char-limit']; ?>" size="3" /><?php _e( 'characters', 'strong-testimonials' ); ?>
			</label>
			<p class="description"><?php _e( 'Will break on a space and add an ellipsis.', 'strong-testimonials' ); ?></p>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[content]" <?php checked( 'entire', $cycle['content'] ); ?> value="entire" /><?php _e( 'Entire content', 'strong-testimonials' ); ?>
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
		<label for="cycle-images"><?php _e( 'Featured Images', 'strong-testimonials' ); ?>&nbsp;<em><?php _e( '(if included in Fields)', 'strong-testimonials' ); ?></em></label>
	</td>
</tr>

<tr valign="top">
	<th scope="row" class="child">
	</th>
	<!-- client_info -->
	<td class="child">
		<input type="checkbox" id="cycle-client" name="wpmtst_cycle[client]" <?php checked( $cycle['client'] ); ?> class="checkbox" />
		<label for="cycle-client"><?php _e( 'Client Information', 'strong-testimonials' ); ?>&nbsp;<em><?php _e( '(if included in Fields)', 'strong-testimonials' ); ?></em></label>
	</td>
</tr>
<!-- end: show parts -->

<!-- read more -->
<tr valign="top">
	<th scope="row">
		"Read more" link
	</th>
	<td>
	
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[more]" <?php checked( 0, $cycle['more'] ); ?> value="0" /><?php _e( 'None', 'strong-testimonials' ); ?>
			</label>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[more]" <?php checked( 1, $cycle['more'] ); ?> value="1" /><?php _e( 'Link to the testimonial', 'strong-testimonials' ); ?>
			</label>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" id="" name="wpmtst_cycle[more]" <?php checked( 2, $cycle['more'] ); ?> value="2" /><?php _e( 'Link to', 'strong-testimonials' ); ?>
			</label>&nbsp;
			<select id="cycle-more-page" name="wpmtst_cycle[more-page]" autocomplete="off">
				<option value=""><?php _e( '— Select a page —', 'strong-testimonials' ) ?></option>
				<?php foreach ( $pages_list as $pages ) : ?>
					<option value="<?php echo $pages->ID; ?>" <?php selected( $cycle['more-page'], $pages->ID ); ?>><?php echo $pages->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		
	</td>
</tr>

<!-- effect -->
<tr valign="top">
	<th scope="row">
		Transition
	</th>
 <td>
		
		<div class="row"><!-- timeout -->
			<div class="alpha">
				<label for="cycle-timeout"><?php _e( 'Show each for', 'strong-testimonials' ); ?></label>
			</div>
			<div>
				<input type="text" id="cycle-timeout" name="wpmtst_cycle[timeout]" value="<?php echo $cycle['timeout']; ?>" size="3" />
				<?php _e( 'seconds', 'strong-testimonials' ); ?>
			</div>
		</div>
		
		<div class="row"><!-- effect -->
			<div class="alpha">
				<label for="cycle-effect"><?php _e( 'Effect', 'strong-testimonials' ); ?></label>
			</div>
			<div>
				<select id="cycle-effect" name="wpmtst_cycle[effect]" autocomplete="off">
					<?php foreach ( $cycle_options['effects'] as $key => $label ) : ?>
					<option value="<?php echo $key; ?>" <?php selected( $cycle['effect'], $key ); ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
				<span class="description"><a href="http://wordpress.org/support/topic/settings-bug-1" target="_blank">Fade is the only effect for now</a></span>
			</div>
		</div>

		<div class="row"><!-- duration -->
			<div class="alpha">
				<label for="cycle-speed"><?php _e( 'Effect duration', 'strong-testimonials' ); ?></label>
			</div>
			<div>
				<input type="text" id="cycle-speed" name="wpmtst_cycle[speed]" value="<?php echo $cycle['speed']; ?>" size="3" />
				<?php _e( 'seconds', 'strong-testimonials' ); ?>
			</div>
		</div>

		<div class="row"><!-- pause -->
			<div>
				<input type="checkbox" id="cycle-pause" name="wpmtst_cycle[pause]" <?php checked( $cycle['pause'] ); ?>  class="checkbox" />
				<label for="cycle-pause"><?php _e( 'Pause on hover', 'strong-testimonials' ); ?></label>
			</div>
		</div>
	</td>
</tr>

</table>
