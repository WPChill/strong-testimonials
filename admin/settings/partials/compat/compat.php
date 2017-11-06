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

<h2><?php _e( 'Ajax Page Loading' ); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">
	<?php // TODO Move this to method ?>
	<?php include( self::PARTIALS . 'compat-page-loading.php' ); ?>
</table>

<table class="form-table" cellpadding="0" cellspacing="0" data-sub="advanced">
	<?php // TODO Move this to method ?>
	<?php include( self::PARTIALS . 'compat-prerender.php' ); ?>
</table>

<table class="form-table" cellpadding="0" cellspacing="0" data-sub="advanced">
	<?php // TODO Move this to method ?>
	<?php include( self::PARTIALS . 'compat-ajax.php' ); ?>
</table>
