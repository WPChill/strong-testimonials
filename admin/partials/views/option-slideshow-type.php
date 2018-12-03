<?php /* translators: In the view editor. */ ?>
<div class="row">

    <div class="inline inline-middle">
        <label>
            <select id="view-slider_type" name="view[data][slideshow_settings][type]" class="if selectgroup">
                <option value="show_single" <?php selected( $view['slideshow_settings']['type'], 'show_single' ); ?>><?php _e( 'single', 'strong-testimonials' ); ?></option>
                <option value="show_multiple" <?php selected( $view['slideshow_settings']['type'], 'show_multiple' ); ?>><?php _e( 'multiple', 'strong-testimonials' ); ?></option>
            </select>
        </label>
        <div class="option-desc singular" style="display: none;">
            <?php _e( 'slide at a time', 'strong-testimonials' ); ?>
        </div>
        <div class="option-desc plural" style="display: none;">
            <?php _e( 'slides at a time with these responsive breakpoints:', 'strong-testimonials' ); ?>
        </div>
    </div>

</div>
