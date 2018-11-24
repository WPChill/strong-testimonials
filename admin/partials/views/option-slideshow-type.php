<div class="row">

    <div class="inline inline-middle">
        <label>
            <select id="view-slider_type" name="view[data][slideshow_settings][type]" class="if selectgroup">
                <option value="single" <?php selected( $view['slideshow_settings']['type'], 'single' ); ?>><?php _e( 'single', 'strong-testimonials' ); ?></option>
                <option value="multiple" <?php selected( $view['slideshow_settings']['type'], 'multiple' ); ?>><?php _e( 'multiple', 'strong-testimonials' ); ?></option>
            </select>
            <div class="option-desc singular" style="display: none;">
				<?php _e( 'slide at a time', 'strong-testimonials' ); ?>
            </div>
            <div class="option-desc plural" style="display: none;">
				<?php _e( 'slides at a time', 'strong-testimonials' ); ?>
            </div>
        </label>
    </div>

</div>
