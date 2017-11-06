<?php
/**
 * Specific script
 */
?>
<div class="row">
    <div>
        <label for="method-script">
            <input type="radio" id="method-script" name="wpmtst_compat_options[ajax][method]" value="script"
				<?php checked( $options['ajax']['method'], 'script' ); ?>
                   data-group="script"/>
			<?php _e( 'Specific script', 'strong-testimonials' ); ?>
        </label>
    </div>
    <div>
        <p class="about"><?php _e( 'Register a callback for a specific Ajax script.', 'strong-testimonials' ); ?></p>
        <p class="description"><?php _e( 'For advanced users.', 'strong-testimonials' ); ?></p>
    </div>
</div>

<div class="row" data-sub="script">
    <div class="radio-sub">
        <label for="script-name">
			<?php _e( 'Script name', 'strong-testimonials' ); ?>
        </label>
    </div>
    <div>
        <select id="script-name" name="wpmtst_compat_options[ajax][script]">
            <option value="" <?php selected( $options['ajax']['script'], '' ); ?>>
				<?php _e( '&mdash; Select &mdash;' ); ?>
            </option>
            <option value="barba" <?php selected( $options['ajax']['script'], 'barba' ); ?>>Barba.js</option>
        </select>
    </div>
</div>
