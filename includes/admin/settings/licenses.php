<?php
/**
 * Licenses
 *
 * Only works for one add-on, Multiple Forms, for now.
 *
 * @package Strong_Testimonials
 * @since 2.1
 */

$license = get_option( 'wpmst_mf_license_key' );
$status  = get_option( 'wpmst_mf_license_status' );
?>
<table class="form-table">
	<tbody>
	<tr>
		<th scope="row">
			<label for="wpmst_mf_license_key">
				<?php _e( 'Multiple Forms License Key' ); ?>
			</label>
		</th>
		<td>
			<input id="wpmst_mf_license_key" name="wpmst_mf_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>"/>
		</td>
	</tr>
	<?php if ( false !== $license ) { ?>
		<tr>
			<th scope="row">
				<?php _e( 'Activate License' ); ?>
			</th>
			<td>
				<?php if ( $status !== false && $status == 'valid' ) { ?>
					<span style="color:green;"><?php _e( 'active' ); ?></span>
					<?php wp_nonce_field( 'wpmst_mf_nonce', 'wpmst_mf_nonce' ); ?>
					<input type="submit" class="button-secondary" name="wpmst_mf_license_deactivate" value="<?php _e( 'Deactivate License' ); ?>"/>
				<?php }
				else {
					wp_nonce_field( 'wpmst_mf_nonce', 'wpmst_mf_nonce' ); ?>
					<input type="submit" class="button-secondary" name="wpmst_mf_license_activate" value="<?php _e( 'Activate License' ); ?>"/>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
