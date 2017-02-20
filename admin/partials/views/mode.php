<?php /* translators: On the Views admin screen. */ ?>
<div class="view-info mode-select">
	<div id="view-mode" class="large">
		<span class="title"><?php _e( 'Mode', 'strong-testimonials' ); ?></span>
		<?php foreach ( $view_options['mode'] as $mode ) : ?>
			<label>
				<input id="<?php echo $mode['name']; ?>" type="radio" name="view[data][mode]"
					   value="<?php echo $mode['name']; ?>" <?php checked( $view['mode'], $mode['name'] ); ?>>
				<?php echo $mode['label']; ?>
			</label>
		<?php endforeach; ?>
	</div>
</div>
