<?php
/**
 * Custom event
 */
?>
<div class="row">
  <div>
    <label for="method-event">
      <input type="radio" id="method-event" name="wpmtst_compat_options[ajax][method]" value="event"
					<?php checked( $options['ajax']['method'], 'event' ); ?>
             data-group="event"/>
			<?php _e( 'Custom event', 'strong-testimonials' ); ?>
    </label>
  </div>
  <div>
    <p class="about"><?php _e( 'Listen for specific events.', 'strong-testimonials' ); ?>
      <span class="description"><?php _e( 'For advanced users.', 'strong-testimonials' ); ?></span></p>
  </div>
</div>

<div class="row" data-sub="event">
  <div class="radio-sub">
    <label for="event-name">
			<?php _e( 'Event name', 'strong-testimonials' ); ?>
    </label>
  </div>
  <div>
    <input type="text" id="event-name" class="code"
           name="wpmtst_compat_options[ajax][event]"
           value="<?php echo $options['ajax']['event']; ?>" size="30"/>
  </div>
</div>
