<?php
/**
 * General settings form.
 *
 * @package Strong_Testimonials
 */
?>
<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>" />
<input type="hidden" name="wpmtst_options[client_section]" value="<?php esc_attr_e( $options['client_section'] ); ?>" />

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<th scope="row">
			<?php _e( 'Load stylesheets', 'strong-testimonials' );?>
		</th>
		<td class="stackem">
			<ul>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_page_style]" <?php checked( $options['load_page_style'] ); ?> />
						<?php _e( 'Pages', 'strong-testimonials' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_widget_style]" <?php checked( $options['load_widget_style'] ); ?> />
						<?php _e( 'Widget', 'strong-testimonials' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_form_style]" <?php checked( $options['load_form_style'] ); ?> />
						<?php _e( 'Submission Form', 'strong-testimonials' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_rtl_style]" <?php checked( $options['load_rtl_style'] ); ?> />
						<?php _e( 'RTL', 'strong-testimonials' ); ?>
					</label>
				</li>
			</ul>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e( 'The number of testimonials to show per page', 'strong-testimonials' ); ?>
		</th>
		<td>
			<input type="text" name="wpmtst_options[per_page]" size="3" value="<?php echo esc_attr( $options['per_page'] ); ?>" />
			<?php /* translators: %s is a shortcode. */ ?>
			<?php echo sprintf( __( 'This applies to the %s shortcode.', 'strong-testimonials' ), '<span class="code">[wpmtst-all]</span>' ); ?>
		</td>
	</tr>
</table>
