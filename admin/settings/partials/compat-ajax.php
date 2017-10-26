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
			<?php include( WPMTST_ADMIN . 'settings/partials/compat-ajax-none.php' ); ?>
			<?php include( WPMTST_ADMIN . 'settings/partials/compat-ajax-universal.php' ); ?>
			<?php include( WPMTST_ADMIN . 'settings/partials/compat-ajax-observer.php' ); ?>
			<?php include( WPMTST_ADMIN . 'settings/partials/compat-ajax-event.php' ); ?>
			<?php include( WPMTST_ADMIN . 'settings/partials/compat-ajax-script.php' ); ?>
    </fieldset>
  </td>
</tr>
