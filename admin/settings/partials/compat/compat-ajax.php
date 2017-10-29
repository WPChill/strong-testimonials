<?php
/**
 * Ajax page loading
 */
?>
<tr valign="top">
  <th scope="row">
		<?php _e( 'Ajax Page Loading', 'strong-testimonials' ); ?>
  </th>
  <td>
    <div class="row header">
      <p><?php _e( 'This option does not perform Ajax page loading.', 'strong-testimonials' ); ?>
        <?php _e( 'Rather, it provides compatibility with themes and plugins that use Ajax to load page content, also known as page animation or transition effects.', 'strong-testimonials' ); ?>
      <p><?php _e( 'If enabled, this will start slideshows, perform pagination and handle form validation as pages change.', 'strong-testimonials' ); ?></p>
      <p><?php _e( 'For use with <strong>Prerender</strong>: All views or None.', 'strong-testimonials' ); ?></p>
    </div>
    <fieldset data-radio-group="method">
			<?php include( self::PARTIALS . 'compat-ajax-none.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-universal.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-observer.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-event.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-script.php' ); ?>
    </fieldset>
  </td>
</tr>
