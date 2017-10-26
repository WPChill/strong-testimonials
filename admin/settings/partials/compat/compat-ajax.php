<?php
/**
 * Ajax page loading
 */
?>
<tr valign="top">
  <th scope="row">
		<?php _e( 'Ajax page loading', 'strong-testimonials' ); ?>
  </th>
  <td>
    <div class="row">
      <p><?php _e( 'about page loading', 'strong-testimonials' ); ?></p>
      <p><?php printf( __( '<a href="%s" target="_blank">article</a>', 'strong-testimonials' ), esc_url( '' ) ); ?></p>
    </div>
    <fieldset>
			<?php include( self::PARTIALS . 'compat-ajax-none.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-universal.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-observer.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-event.php' ); ?>
			<?php include( self::PARTIALS . 'compat-ajax-script.php' ); ?>
    </fieldset>
  </td>
</tr>
