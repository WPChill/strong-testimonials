<?php /* translators: In the view editor. */ ?>
<div class="inline then then_slider_type then_not_show_single then_show_multiple" style="display: none;">
    <div class="row">

        <div class="inner-table is-below">
            <div class="inner-table-row bordered header">
                <div class="inner-table-cell">
                    <?php _e( 'minimum screen width', 'strong-testimonials' ); ?>
                </div>
                <div class="inner-table-cell">
                    <?php _e( 'show', 'strong-testimonials' ); ?>
                </div>
                <div class="inner-table-cell">
                    <?php _e( 'margin', 'strong-testimonials' ); ?>
                </div>
                <div class="inner-table-cell">
                    <?php _e( 'move', 'strong-testimonials' ); ?>
                </div>
            </div>

			<?php foreach ( $view['slideshow_settings']['breakpoints'] as $key => $breakpoint ) : ?>
                <div class="inner-table-row bordered">

                    <div class="inner-table-cell">
                        <label>
                        <input id="view-breakpoint_<?php echo $key; ?>"
                               name="view[data][slideshow_settings][breakpoints][<?php echo $key; ?>][width]"
                               value="<?php echo $breakpoint['width']; ?>"
                               type="number"
                               class="input-incremental"> px
                        </label>
                    </div>

                    <div class="inner-table-cell">
                        <label>
                        <select id="view-max_slides_<?php echo $key; ?>"
                                name="view[data][slideshow_settings][breakpoints][<?php echo $key; ?>][max_slides]"
                                class="if selectgroup">
                            <option value="1" <?php selected( $breakpoint['max_slides'], 1 ); ?>>1</option>
                            <option value="2" <?php selected( $breakpoint['max_slides'], 2 ); ?>>2</option>
                            <option value="3" <?php selected( $breakpoint['max_slides'], 3 ); ?>>3</option>
                            <option value="4" <?php selected( $breakpoint['max_slides'], 4 ); ?>>4</option>
                        </select>
                        </label>
                        <div class="option-desc singular" style="display: none;">
							<?php _e( 'slide', 'strong-testimonials' ); ?>
                        </div>
                        <div class="option-desc plural" style="display: none;">
							<?php _e( 'slides', 'strong-testimonials' ); ?>
                        </div>
                    </div>

                    <div class="inner-table-cell">
                        <input id="view-margin_<?php echo $key; ?>"
                               name="view[data][slideshow_settings][breakpoints][<?php echo $key; ?>][margin]"
                               value="<?php echo $breakpoint['margin']; ?>"
                               type="number" min="1" step="1" size="3"
                               class="input-incremental"/> px
                    </div>

                    <div class="inner-table-cell">
                        <label>
                        <select id="view-move_slides_<?php echo $key; ?>"
                                name="view[data][slideshow_settings][breakpoints][<?php echo $key; ?>][move_slides]"
                                class="if selectgroup">
                            <option value="1" <?php selected( $breakpoint['move_slides'], 1 ); ?>>1</option>
                            <option value="2" <?php selected( $breakpoint['move_slides'], 2 ); ?>>2</option>
                            <option value="3" <?php selected( $breakpoint['move_slides'], 3 ); ?>>3</option>
                            <option value="4" <?php selected( $breakpoint['move_slides'], 4 ); ?>>4</option>
                        </select>
                        </label>
                        <div class="option-desc singular" style="display: none;">
							<?php _e( 'slide', 'strong-testimonials' ); ?>
                        </div>
                        <div class="option-desc plural" style="display: none;">
							<?php _e( 'slides', 'strong-testimonials' ); ?>
                        </div>
                    </div>

                </div>

			<?php endforeach; ?>
        </div>

    </div>

    <div class="is-below">
        <input id="restore-default-breakpoints"
               type="button"
               name="restore-default-breakpoints"
               value="<?php _e( 'Restore Default Breakpoints', 'strong-testimonials' ); ?>"
               class="button-secondary" />
        <span id="restored-message"><?php _e( 'defaults restored', 'strong-testimonials' ); ?></span>
    </div>
</div>
