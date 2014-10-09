<?php
/**
 * Strong Testimonials
 * Client section form
 */
if ( $options['load_page_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_page_style]" value="1" />';
if ( $options['load_widget_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_widget_style]" value="1" />';
if ( $options['load_form_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_form_style]" value="1" />';
if ( $options['admin_notify'] )
	echo '<input type="hidden" name="wpmtst_options[admin_notify]" value="1" />';
if ( $options['honeypot_before'] )
	echo '<input type="hidden" name="wpmtst_options[honeypot_before]" value="1" />';
if ( $options['honeypot_after'] )
	echo '<input type="hidden" name="wpmtst_options[honeypot_after]" value="1" />';
?>
<input type="hidden" name="wpmtst_options[admin_email]" value="<?php esc_attr_e( $options['admin_email'] ); ?>" />
<input type="hidden" name="wpmtst_options[captcha]" value="<?php esc_attr_e( $options['captcha'] ); ?>" />
<input type="hidden" name="wpmtst_options[per_page]" value="<?php esc_attr_e( $options['per_page'] ); ?>" />
<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>" />

<table class="form-table" cellpadding="0" cellspacing="0">

<tr valign="top">
<td>
	<p><b><?php _e( 'Client section shortcodes:', 'strong-testimonials' ); ?></b></p>
	<p><textarea id="client-section" class="widefat code" name="wpmtst_options[client_section]" rows="3"><?php echo $options['client_section']; ?></textarea></p>
	<p><?php _e( 'Your fields:', 'strong-testimonials' ); ?>&nbsp;&nbsp;<?php echo join( " ", $fields_array ); ?><span class="widget-help pushdown2 dashicons dashicons-editor-help"><span class="help"><?php _e( 'These are the fields used on your testimonial submission form. You can change these in the Fields editor.', 'strong-testimonials' ); ?></span></span></p>
	
	<p><?php _e( 'Use these shortcodes to select which client fields appear below each testimonial.', 'strong-testimonials' ); ?> <em><?php _e( 'These shortcodes only work here, not on a page.', 'strong-testimonials' ); ?></em></p>
	
	<?php /*
	<p>Here's an example:</p>
	<div class="shortcode-example code">
		<p class="indent">
			<span class="outdent">[wpmtst-text </span> field="<span class="field example"><?php _e( 'your field', 'strong-testimonials' ); ?></span>" class="<span class="field example"><?php _e( 'your CSS class', 'strong-testimonials' ); ?></span>"]
		</p>
		<p class="indent">
			<span class="outdent">[wpmtst-link </span> url="<span class="field example"><?php _e( 'your field', 'strong-testimonials' ); ?></span>" text="<span class="field example"><?php _e( 'your field', 'strong-testimonials' ); ?></span>" new_tab class="<span class="field example"><?php _e( 'your CSS class', 'strong-testimonials' ); ?></span>"]
		</p>
	</div>
	*/ ?>
	
	<p><?php _e( 'Default:', 'strong-testimonials' ); ?></p>
	<div class="shortcode-example code">
		<p class="indent">
			<span class="outdent">[wpmtst-text </span> field="<span class="field">client_name</span>" class="<span class="field">name</span>"]
		</p>
		<p class="indent">
			<span class="outdent">[wpmtst-link </span> url="<span class="field">company_website</span>" text="<span class="field">company_name</span>" new_tab class="<span class="field">company</span>"]
		</p>
	</div>
	<p><input type="button" class="button" id="restore-default-template" value="<?php _e( 'Restore Default', 'strong-testimonials' ); ?>" /></p>
</td>
</tr>

</table>
