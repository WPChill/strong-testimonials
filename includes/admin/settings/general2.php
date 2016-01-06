<?php
/**
 * Settings
 *
 * @package Strong_Testimonials
 * @since 1.13
 */
?>
<input type="hidden" name="wpmtst_options[shortcode]" value="<?php esc_attr_e( $options['shortcode'] ); ?>">
<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>">
<input type="hidden" name="wpmtst_options[client_section]" value="<?php esc_attr_e( $options['client_section'] ); ?>">
<input type="hidden" name="wpmtst_options[per_page]" value="<?php echo $options['per_page']; ?>">
<input type="hidden" name="wpmtst_options[load_page_style]" value="<?php echo $options['load_page_style']; ?>">
<input type="hidden" name="wpmtst_options[load_widget_style]" value="<?php echo $options['load_widget_style']; ?>">
<input type="hidden" name="wpmtst_options[load_form_style]" value="<?php echo $options['load_form_style']; ?>">
<input type="hidden" name="wpmtst_options[load_rtl_style]" value="<?php echo $options['load_rtl_style']; ?>">

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<th scope="row">
			<?php _e( 'Reordering', 'strong-testimonials' ); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wpmtst_options[reorder]" <?php checked( $options['reorder'] ); ?>>
				<?php _e( 'Enable drag-and-drop reordering in the testimonial list.', 'strong-testimonials' ); ?>
			</label>
		</td>
	</tr>
</table>
