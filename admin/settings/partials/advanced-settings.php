<?php

$options = get_option( 'strong_testimonials_advanced_settings' );

?>
<div class="row">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row" valign="top">
					<?php esc_html_e( 'Debug log', 'strong-testimonials' ); ?>
				</th>
				<td>
                    <input id="strong_testimonials_debug_log_enable" name="strong_testimonials_advanced_settings[debug_log]" type="checkbox"  <?php echo ( isset($options['debug_log']) && $options['debug_log'] == 'on'  ? 'checked' : '') ;  ?>/>
					<label class="description strong-testimonials-license-label" for="strong_testimonials_license_key"> <?php esc_html_e( 'Enable debug log. Off by default.', 'strong-testimonials' ); ?> </label>
                    <p class="description"><?php esc_html_e( 'Creates debug logs for Strong Testimonial module and it \'s addons.', 'strong-testimonials' ); ?> </p>
                </td>
			</tr>

		</tbody>
	</table>
    <p class="submit-buttons"><input type="submit" name="submit-form" id="submit-form" class="button button-primary" value="<?php esc_html_e( 'Save changes', 'strong-testimonials' ); ?>"></p>
</div>
