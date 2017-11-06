<?php
/**
 * Page Loading
 */
?>
<tr valign="top">
	<th scope="row">
		<?php _e( 'Type', 'strong-testimonials' ); ?>
	</th>
	<td>
    <div class="row header">
      <p><?php _e( 'This does not perform Ajax page loading.', 'strong-testimonials' ); ?>
	      <?php _e( 'It provides compatibility with themes and plugins that use Ajax to load pages, also known as page animation or transition effects.', 'strong-testimonials' ); ?>
      </p>
    </div>
		<fieldset data-radio-group="prerender">
			<?php include( self::PARTIALS . 'compat-page-loading-none.php' ); ?>
			<?php include( self::PARTIALS . 'compat-page-loading-general.php' ); ?>
			<?php include( self::PARTIALS . 'compat-page-loading-advanced.php' ); ?>
		</fieldset>
	</td>
</tr>
