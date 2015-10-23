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
	<div class="row">
		<div class="inline tight">
			<div class="then then_images" style="display: none;">
				<label for="view-gravatar" class=""><?php _e( 'If no Featured image', 'strong-testimonials' ); ?></label>
				<select id="view-gravatar" class="if select selectper" name="view[data][gravatar]" autocomplete="off">
					<option value="no" <?php selected( $view['gravatar'], 'no' ); ?>><?php _e( 'show nothing', 'strong-testimonials' ); ?></option>
					<option value="yes" <?php selected( $view['gravatar'], 'yes' ); ?>><?php _e( 'show Gravatar', 'strong-testimonials' ); ?></option>
					<option value="if" <?php selected( $view['gravatar'], 'if' ); ?>><?php _e( 'show Gravatar only if found', 'strong-testimonials' ); ?></option>
				</select>
			</div>
		</div>
	<div class="inline">
		<div style="display: none;" class="then fast then_not_no then_yes then_if">
			<p class="description">
				<a href="<?php echo admin_url( 'options-discussion.php' ); ?>"><?php _e( 'Gravatar settings', 'strong-testimonials' ); ?></a>
			</p>
		</div>
	</div>
	</div>
</td>