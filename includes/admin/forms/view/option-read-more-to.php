<!-- MORE PAGE -->
<td colspan="2" style="display: none;" class="checkbox then then_read_more_to">
	<div class="row top-of-cell">
		<label>
			<select id="view-page" name="view[data][more_page]" autocomplete="off">
				<option value=""><?php _e( '&mdash; select &mdash;' ); ?></option>
				<?php foreach ( $pages_list as $pages ) : ?>
					<option value="<?php echo $pages->ID; ?>" <?php selected( isset( $view['more_page'] ) ? $view['more_page'] : 0, $pages->ID ); ?>><?php echo $pages->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label for="view-find-page" style="display: inline-block">
			<?php _e( 'or enter its ID or slug', 'strong-testimonials' ); ?>
		</label>
		<input type="text" id="view-find-page" name="view[data][find_page]" size="25">
	</div>
</td>
