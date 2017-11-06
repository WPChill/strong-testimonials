<?php
/**
 * Prerender
 */
?>
<tr valign="top">
	<th scope="row">
		<?php _e( 'Prerender', 'strong-testimonials' ); ?>
	</th>
	<td>
    <div class="row header">
      <p><?php _e( 'Load stylesheets and populate script variables   up front.', 'strong-testimonials' ); ?>
        <a class="open-help-tab" href="#tab-panel-wpmtst-help-prerender"><?php _e( 'Help' ); ?></a>
      </p>
    </div>
		<fieldset data-radio-group="prerender">
			<?php include( self::PARTIALS . 'compat-prerender-current.php' ); ?>
			<?php include( self::PARTIALS . 'compat-prerender-all.php' ); ?>
			<?php include( self::PARTIALS . 'compat-prerender-none.php' ); ?>
		</fieldset>
	</td>
</tr>
