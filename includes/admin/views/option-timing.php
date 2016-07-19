<?php /* translators: This is on the Views admin screen. */ ?>
<th>
	<?php _e( 'Timing', 'strong-testimonials' ); ?>
</th>
<td>
	<div class="row">

		<div class="inline inline-middle">
			<label for="view-show_for">
				<?php _ex( 'Show each for', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
			<input type="number" id="view-show_for" class="input-incremental" name="view[data][show_for]" min=".1" step=".1" value="<?php echo $view['show_for']; ?>" size="3">
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>

		<div class="inline inline-middle">
			<label for="view-effect">
				<?php _e( 'then', 'strong-testimonials' ); ?>
			</label>
			<select id="view-effect" name="view[data][effect]" class="if selectnot">
				<?php foreach ( $slideshow_effect_options as $key => $label ) : ?>
					<option value="<?php echo $key; ?>" <?php selected( $view['effect'], $key ); ?> <?php echo 'none' == $key ? 'class="trip"' : ''; ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="inline inline-middle then then_effect then_none">
			<label for="view-effect_for">
				<?php _e( 'for', 'strong-testimonials' ); ?>
			</label>
			<input type="number" id="view-effect_for" class="input-incremental" name="view[data][effect_for]" min=".1" step=".1" value="<?php echo $view['effect_for']; ?>" size="3">
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>

		<div class="inline inline-middle">
			<input type="checkbox" id="view-no_pause" name="view[data][no_pause]" value="0" <?php checked( ! $view['no_pause'] ); ?> class="checkbox">
			<label for="view-no_pause">
				<?php _ex( 'Pause on hover', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
		</div>

	</div>
</td>
