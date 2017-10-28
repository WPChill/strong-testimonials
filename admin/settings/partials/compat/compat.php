<?php
/**
 * Compatibility settings
 *
 * @since 2.28.0
 */

$options = get_option( 'wpmtst_compat_options' );
?>
<h2><?php _e( 'Resource Loading' ); ?></h2>
<table class="form-table" cellpadding="0" cellspacing="0">
  <?php include( self::PARTIALS . 'compat-prerender.php' ); ?>
</table>

<hr/>

<h2><?php _e( 'Themes' ); ?></h2>
<table class="form-table" cellpadding="0" cellspacing="0">
	<?php include( self::PARTIALS . 'compat-ajax.php' ); ?>
</table>
