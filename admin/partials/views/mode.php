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
        <?php //TODO abstract this ?>
        <div class="mode-description">
            <div class="then fast then_display then_not_slideshow then_not_form then_not_single_template" style="display: none;">
                <p><?php echo $view_options['mode']['display']['description'];?></p>
            </div>
            <div class="then fast then_not_display then_slideshow then_not_form then_not_single_template" style="display: none;">
                <p><?php echo $view_options['mode']['slideshow']['description'];?></p>
            </div>
            <div class="then fast then_not_display then_not_slideshow then_form then_not_single_template" style="display: none;">
                <p><?php echo $view_options['mode']['form']['description'];?></p>
            </div>
            <?php if ( isset( $view_options['mode']['single_template'] ) ) : ?>
            <div class="then fast then_not_display then_not_slideshow then_not_form then_single_template" style="display: none;">
                <p><?php echo $view_options['mode']['single_template']['description'];?></p>
            </div>
            <?php endif; ?>
        </div>
	</div>
</div>
