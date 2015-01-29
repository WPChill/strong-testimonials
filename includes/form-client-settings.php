<?php
/**
 * Settings > Client section
 *
 * @since 1.7.0
 * @package Strong_Testimonials
 */
?>

<div class="update-nag">
	<?php printf( __( 'These shortcodes will be deprecated soon. Please migrate to the <a href="%s"><code>[strong]</code></a> shortcode.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=shortcodes' ) ); ?>
</div>

<?php 
if ( $options['load_page_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_page_style]" value="1" />';
if ( $options['load_widget_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_widget_style]" value="1" />';
if ( $options['load_form_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_form_style]" value="1" />';
if ( $options['load_rtl_style'] )
	echo '<input type="hidden" name="wpmtst_options[load_rtl_style]" value="1" />';
?>
<input type="hidden" name="wpmtst_options[per_page]" value="<?php esc_attr_e( $options['per_page'] ); ?>" />
<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>" />

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<p><b><?php _e( 'Client section shortcodes:', 'strong-testimonials' ); ?></b></p>
			<p><textarea id="client-section" class="widefat code" name="wpmtst_options[client_section]" rows="3"><?php echo $options['client_section']; ?></textarea></p>
			<p><?php _e( 'Your fields:', 'strong-testimonials' ); ?>&nbsp;&nbsp;<?php echo join( " ", $fields_array ); ?><span class="widget-help pushdown2 dashicons dashicons-editor-help"><span class="help"><?php _e( 'These are the fields used on your testimonial submission form. You can change these in the Fields editor.', 'strong-testimonials' ); ?></span></span></p>
			
			<p><?php _e( 'Use these shortcodes to select which client fields appear below each testimonial.', 'strong-testimonials' ); ?> <em><?php _e( 'These shortcodes only work here, not on a page.', 'strong-testimonials' ); ?></em></p>
			
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
