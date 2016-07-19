<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-slideshow_nav">
		<?php _e( 'Navigation', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row">
		<div class="inline">
			<select id="view-slideshow_nav" name="view[data][slideshow_nav]" class="if select">
				<option value="none" <?php selected( $view['slideshow_nav'], false ); ?> class="trip"><?php _e( 'none', 'strong-testimonials' ); ?></option>
				<?php foreach ( $slideshow_nav_options as $key => $label ) : ?>
				<option value="<?php echo $key; ?>" <?php selected( $view['slideshow_nav'], $key ); ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</td>