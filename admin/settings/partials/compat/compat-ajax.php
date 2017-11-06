<?php
/**
 * Ajax page loading
 */
?>
<tr valign="top">
  <th scope="row">
		<?php _e( 'Monitor', 'strong-testimonials' ); ?>
  </th>
  <td>
    <div class="row header">
      <p><?php _e( 'Initialize slideshows, pagination and form validation as pages change.', 'strong-testimonials' ); ?></p>
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
