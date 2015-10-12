<?php /* translators: On the Views admin screen. */ ?>
<th class="checkbox">
	<input type="checkbox" id="view-images" class="if toggle" name="view[data][thumbnail]" value="1" <?php checked( $view['thumbnail'] ); ?> class="checkbox">
	<label for="view-images"><?php _e( 'Featured Image', 'strong-testimonials' ); ?></label>
</th>
<td class="w1">
	<div class="inline">
		<p class="description"><?php _e( '(if included in Fields)', 'strong-testimonials' ); ?></p>
	</div>
</td>
<td>
	<div class="row top-of-cell">
		<div class="then then_images" style="display: none;">
			<div class="inline">
				<label for="view-thumbnail_size" class="">Size</label>
				<select id="view-thumbnail_size" class="if select" name="view[data][thumbnail_size]" autocomplete="off">
					<?php foreach ( $image_sizes as $key => $size ) : ?>
						<option<?php if ( 'custom' == $key ) echo ' class="trip"'; ?> value="<?php echo $key; ?>"<?php selected( $key, $view['thumbnail_size'] ); ?>><?php echo $size['label']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="inline tight then then_thumbnail_size">
				<label for="thumbnail_width" class="">width</label>
				<input id="thumbnail_width" class="input-number-px" type="text" name="view[data][thumbnail_width]" value="<?php echo $view['thumbnail_width']; ?>"> px
			</div>
			<div class="inline tight then then_thumbnail_size">
				<label for="thumbnail_height" class="">height</label>
				<input id="thumbnail_height" class="input-number-px" type="text" name="view[data][thumbnail_height]" value="<?php echo $view['thumbnail_height']; ?>"> px
			</div>
		</div>
	</div>
	<div class="row">
		<div class="then then_images" style="display: none;">
			<input type="checkbox" id="view-lightbox" class="if toggle" name="view[data][lightbox]" value="1" <?php checked( $view['lightbox'] ); ?> class="checkbox">
			<label for="view-lightbox"><?php _e( 'Open full-size image in lightbox', 'strong-testimonials' ); ?></label>
		</div>
	</div>
</td>