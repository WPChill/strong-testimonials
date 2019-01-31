<?php
/**
 * Licenses
 *
 * @since 2.1
 * @since 2.18 As a partial file. Using action hook for add-ons to append their info.
 *
 * TODO Add link to member account on website.
 */
?>
<h2><?php esc_html_e( 'Add-on Licenses', 'strong-testimonials' ); ?></h2>
<div class="tab-header">
<p><?php esc_html_e( 'Valid license keys allow you to receive automatic updates and priority support.', 'strong-testimonials' ); ?></p>
<p><?php esc_html_e( 'To transfer a license to another site or to uninstall the add-on, please deactivate the license here first.', 'strong-testimonials' ); ?></p>
</div>
<table class="form-table">
	<thead>
	<tr>
		<th><?php esc_html_e( 'Add-on', 'strong-testimonials' ); ?></th>
		<th class="for-license-key"><?php esc_html_e( 'License Key', 'strong-testimonials' ); ?></th>
		<th class="for-license-status"><?php esc_html_e( 'Status', 'strong-testimonials' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php do_action( 'wpmtst_licenses' ); ?>
	</tbody>
</table>
