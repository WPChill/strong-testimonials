<?php
/**
 * Strong Testimonials
 * General settings form
 */
?>
<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>" />
<input type="hidden" name="wpmtst_options[client_section]" value="<?php esc_attr_e( $options['client_section'] ); ?>" />

<table class="form-table" cellpadding="0" cellspacing="0">

<tr valign="top">
<th scope="row"><?php _e( 'Load stylesheets', 'strong-testimonials' );?></th>
<td class="stackem">
	<label>
		<input type="checkbox" name="wpmtst_options[load_page_style]" <?php checked( $options['load_page_style'] ); ?> />
		<?php _e( 'Pages', 'strong-testimonials' ); ?>
	</label>
	<label>
		<input type="checkbox" name="wpmtst_options[load_widget_style]" <?php checked( $options['load_widget_style'] ); ?> />
		<?php _e( 'Widget', 'strong-testimonials' ); ?>
	</label>
	<label>
		<input type="checkbox" name="wpmtst_options[load_form_style]" <?php checked( $options['load_form_style'] ); ?> />
		<?php _e( 'Submission Form', 'strong-testimonials' ); ?>
	</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'The number of testimonials to show per page', 'strong-testimonials' ); ?></th>
<td>
	<input type="text" name="wpmtst_options[per_page]" size="3" value="<?php echo esc_attr( $options['per_page'] ); ?>" />
	<?php echo sprintf( __( 'This applies to the %s shortcode.', 'strong-testimonials' ), '<span class="code">[wpmtst-all]</span>' ); ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'When a new testimonial is submitted', 'strong-testimonials' );?></th>
<td>
	<label>
		<input id="wpmtst-options-admin-notify" type="checkbox" name="wpmtst_options[admin_notify]" <?php checked( $options['admin_notify'] ); ?> />
		<?php _e( 'Send notification email to', 'strong-testimonials' ); ?>
	</label>
	<input id="wpmtst-options-admin-email" type="email" size="30" placeholder="email address" name="wpmtst_options[admin_email]" value="<?php echo esc_attr( $options['admin_email'] ); ?>" />
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'CAPTCHA plugin', 'strong-testimonials' );?></th>
<td>
	<select name="wpmtst_options[captcha]" autocomplete="off">
		<option value=""><?php _e( 'none' );?></option>
		<?php foreach ( $plugins as $key => $plugin ) : ?>
		<?php if ( $plugin['active'] ) : ?>
		<option value="<?php echo $key; ?>" <?php selected( $options['captcha'], $key ); ?>><?php echo $plugin['name']; ?></option>
		<?php endif; ?>
		<?php endforeach; ?>
	</select>
</td>
</tr>

</table>
