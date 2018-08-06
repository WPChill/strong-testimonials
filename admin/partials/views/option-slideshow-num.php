<?php /* translators: In the view editor. */ ?>
<th>
	<?php _e( 'Number', 'strong-testimonials' ); ?>
</th>
<td>
    <div class="row">

        <div class="inline inline-middle">
            <label for="view-max_slides">
				<?php _e( 'Show', 'strong-testimonials' ); ?>
            </label>
                <select id="view-max_slides" name="view[data][slideshow_settings][max_slides]" class="if selectgroup">
                    <option value="1" <?php selected( $view['slideshow_settings']['max_slides'], 1 ); ?>>1</option>
                    <option value="2" <?php selected( $view['slideshow_settings']['max_slides'], 2 ); ?>>2</option>
                    <option value="3" <?php selected( $view['slideshow_settings']['max_slides'], 3 ); ?>>3</option>
                </select>
                <div class="option-desc singular" style="display: none;">
				    <?php _e( 'slide at a time', 'strong-testimonials' ); ?>
                </div>
                <div class="option-desc plural" style="display: none;">
				    <?php _e( 'slides at a time', 'strong-testimonials' ); ?>
                </div>
        </div>

        <div class="inline inline-middle then then_max_slides then_not_1 then_2 then_3" style="display: none;">
            <label for="view-margin">
			    <?php _ex( 'with a margin of', 'strong-testimonials' ); ?>
            </label>
            <input type="number" id="view-margin" class="input-incremental"
                   name="view[data][slideshow_settings][margin]" min="1" step="1"
                   value="<?php echo $view['slideshow_settings']['margin']; ?>" size="3"/> px
        </div>

        <div class="inline inline-middle then then_max_slides then_not_1 then_2 then_3" style="display: none;">
            <label for="view-move_slides">
				<?php _ex( 'and move', 'strong-testimonials' ); ?>
            </label>
                <select id="view-move_slides" name="view[data][slideshow_settings][move_slides]" class="if selectgroup">
                    <option value="1" <?php selected( $view['slideshow_settings']['move_slides'], 1 ); ?>>1</option>
                    <option value="2" <?php selected( $view['slideshow_settings']['move_slides'], 2 ); ?>>2</option>
                    <option value="3" <?php selected( $view['slideshow_settings']['move_slides'], 3 ); ?>>3</option>
                </select>
                <div class="option-desc singular" style="display: none;">
		            <?php _e( 'slide at a time', 'strong-testimonials' ); ?>
                </div>
                <div class="option-desc plural" style="display: none;">
		            <?php _e( 'slides at a time', 'strong-testimonials' ); ?>
                </div>
        </div>

    </div>
</td>
