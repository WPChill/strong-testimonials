<?php
/**
 * Strong Testimonials
 * General settings form
 */
if ( $options['load_page_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_page_style]" value="1" />';
if ( $options['load_widget_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_widget_style]" value="1" />';
if ( $options['load_form_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_form_style]" value="1" />';
if ( $options['admin_notify'] )
	echo '<input type="hidden" name="wpmtst_options[admin_notify]" value="1" />';
?>
<input type="hidden" name="wpmtst_options[admin_email]" value="<?php esc_attr_e( $options['admin_email'] ); ?>" />
<input type="hidden" name="wpmtst_options[captcha]" value="<?php esc_attr_e( $options['captcha'] ); ?>" />
<input type="hidden" name="wpmtst_options[per_page]" value="<?php esc_attr_e( $options['per_page'] ); ?>" />
<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>" />

<table class="form-table" cellpadding="0" cellspacing="0">

<tr valign="top">
<td>
	<p class="description hilite">This will be replaced with a field editor in an upcoming version.</p>
	<p>Two internal shortcodes are available to show client information below each testimonial. <em>These shortcodes only work here, not on a page.</em></p>
	<p>Here's an example:</p>
	<div class="shortcode-example code">
		<p class="indent">
			<span class="outdent">[wpmtst-text </span> field="<span class="field example"><?php _e( 'your field', 'strong-testimonials' ); ?></span>" class="<span class="field example"><?php _e( 'your CSS class', 'strong-testimonials' ); ?></span>"]
		</p>
		<p class="indent">
			<span class="outdent">[wpmtst-link </span> url="<span class="field example"><?php _e( 'your field', 'strong-testimonials' ); ?></span>" text="<span class="field example"><?php _e( 'your field', 'strong-testimonials' ); ?></span>" target="_blank" class="<span class="field example"><?php _e( 'your CSS class', 'strong-testimonials' ); ?></span>"]
		</p>
	</div>
	<br /><br />
	
	
	<p>Your fields:&nbsp;&nbsp;<?php echo join( " ", $fields_array ); ?><span class="widget-help pushdown2 dashicons dashicons-editor-help"><span class="help">These are the fields used on your testimonial submission form. You can change these in the Fields editor.</span></span></p>
	<p><b>Client section shortcodes:</b></p>
	<p><textarea id="client-section" class="widefat code" name="wpmtst_options[client_section]" rows="3"><?php echo $options['client_section']; ?></textarea></p>
	
	
	<p>Default:</p>
	<div class="shortcode-example code">
		<p class="indent">
			<span class="outdent">[wpmtst-text </span> field="<span class="field">client_name</span>" class="<span class="field">name</span>"]
		</p>
		<p class="indent">
			<span class="outdent">[wpmtst-link </span> url="<span class="field">company_website</span>" text="<span class="field">company_name</span>" target="_blank" class="<span class="field">company</span>"]
		</p>
	</div>
	<p><input type="button" class="button" id="restore-default-template" value="<?php _e( 'Restore Default', 'strong-testimonials' ); ?>" /></p>
</td>
</tr>

</table>
