<?php
/**
 * Universal (timer)
 */
?>
<div class="row">
  <div>
    <label for="method-universal">
      <input type="radio" id="method-universal" name="wpmtst_compat_options[ajax][method]" value="universal"
					<?php checked( $options['ajax']['method'], 'universal' ); ?>
             data-group="universal"/>
			<?php _e( 'Universal', 'strong-testimonials' ); ?>
    </label>
  </div>
  <div>
    <p class="about"><?php _e( 'Monitor page changes on a timer.', 'strong-testimonials' ); ?></p>
  </div>
</div>

<div class="row" data-sub="universal">
  <div class="radio-sub">
    <label for="universal-timer">
			<?php _ex( 'Check every', 'timer setting', 'strong-testimonials' ); ?>
    </label>
  </div>
  <div>
    <input type="number" id="universal-timer" name="wpmtst_compat_options[ajax][universal_timer]"
           min=".1" max="5" step=".1" size="3"
           value="<?php echo $options['ajax']['universal_timer']; ?>" autocomplete="off">
		<?php _ex( 'seconds', 'timer setting', 'strong-testimonials' ); ?>
  </div>
</div>
