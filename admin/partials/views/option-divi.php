<?php
/* translators: On the Views admin screen. */
global $view;
?>
<th>
    <label for="view-divi_builder"><?php _e( 'Divi Builder', 'strong-testimonials-lucid-theme' ); ?></label>
</th>
<td>
    <div class="row">
        <div class="row-inner">
            <input type="checkbox" id="view-divi_builder" class="if toggle checkbox"
                   name="view[data][divi_builder]" value="1" <?php checked( $view['divi_builder'] ); ?>/>
            <label for="view-divi_builder">
                <?php _e( 'Check this if using Divi Builder version 2+', 'strong-testimonials-lucid-theme' ); ?>
            </label>
            <p class="description short">
                <?php _e( 'This is only required if adding this view using the Visual Builder in <b>version 2</b> of the Divi Builder plugin by Elegant Themes.', 'strong-testimonials-lucid-theme' ); ?>
            </p>
        </div>
    </div>
</td>
