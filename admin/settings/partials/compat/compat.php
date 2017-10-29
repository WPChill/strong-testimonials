<?php
/**
 * Compatibility settings
 *
 * @since 2.28.0
 */

$options = get_option( 'wpmtst_compat_options' );
?>
<h2><?php _e( 'Common Scenarios' ); ?></h2>
<table class="form-table" cellpadding="0" cellspacing="0">
	<?php include( self::PARTIALS . 'compat-scenarios.php' ); ?>
</table>

<h2><?php _e( 'Resource Loading' ); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">
  <?php include( self::PARTIALS . 'compat-prerender.php' ); ?>
</table>

<table class="form-table" cellpadding="0" cellspacing="0">
	<?php include( self::PARTIALS . 'compat-ajax.php' ); ?>
</table>
