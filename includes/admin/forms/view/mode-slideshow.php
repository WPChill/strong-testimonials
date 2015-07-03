	<th>
		<div class="inline">
			<label for="view-show_for">
				<?php /* translators: This is on the Views admin screen. */ ?>
				<?php _ex( 'Show each for', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="view-show_for" name="view[data][show_for]" value="<?php echo $view['show_for']; ?>" size="3">
			<?php /* translators: This is on the Views admin screen. */ ?>
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>
		<div class="inline">
			<label for="view-effect_for">
				<?php /* translators: This is on the Views admin screen. */ ?>
				<?php _ex( 'Fade transition for', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
			<input type="text" id="view-effect_for" name="view[data][effect_for]" value="<?php echo $view['effect_for']; ?>" size="3">
			<?php /* translators: This is on the Views admin screen. */ ?>
			<?php _ex( 'seconds', 'time setting', 'strong-testimonials' ); ?>
		</div>
		<div class="inline">
			<input type="checkbox" id="view-no_pause" name="view[data][no_pause]" value="0" <?php checked( ! $view['no_pause'] ); ?> class="checkbox">
			<label for="view-no_pause">
				<?php /* translators: This is on the Views admin screen. */ ?>
				<?php _ex( 'Pause on hover', 'slideshow setting', 'strong-testimonials' ); ?>
			</label>
		</div>
	</th>
