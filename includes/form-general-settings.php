<?php
/**
 * General settings form.
 *
 * @package Strong_Testimonials
 */
?>
<input type="hidden" name="wpmtst_options[plugin_version]" value="<?php esc_attr_e( $options['plugin_version'] ); ?>" />
<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>" />
<input type="hidden" name="wpmtst_options[client_section]" value="<?php esc_attr_e( $options['client_section'] ); ?>" />

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<th scope="row">
			<?php _e( 'Shortcode', 'strong-testimonials' ); ?>
		</th>
		<td>
			<p>
				<label>
					<input id="wpmtst_shortcode" type="text" name="wpmtst_options[shortcode]" size="20" value="<?php echo esc_attr( $options['shortcode'] ); ?>" autocomplete="off" placeholder="enter a word" />
				</label>
				<span class="between-inputs">or</span>
				<input id="restore-default-shortcode" type="button" class="button secondary restore-default-shortcode" value="<?php _ex( 'restore default', 'singular', 'strong-testimonials' ); ?>" />
				<span class="between-inputs">or</span>
				<select id="wpmtst_shortcode_select">
					<option value="0">&ndash; <?php _e( 'select one', 'strong-testimonials' ); ?> &ndash;</option>
					<optgroup label="<?php _e( 'variants', 'strong-testimonials' ); ?>">
						<option>strong2</option>
						<option>STRONG</option>
						<option>my_strong</option>
						<option>strong-testimonials</option>
					</optgroup>
					<optgroup label="<?php _e( 'languages', 'strong-testimonials' ); ?>">
						<option>fuerte</option>
						<option>fort</option>
						<option>forte</option>
						<option>sterk</option>
						<option>stark</option>
					</optgroup>
					<optgroup label="<?php _e( 'similar', 'strong-testimonials' ); ?>">
						<option>applause</option>
						<option>bravos</option>
						<option>cheers</option>
						<option>kudos</option>
						<option>praise</option>
						<option>raves</option>
					</optgroup>
				</select>
			</p>
			<p>
				<?php _e( 'The hyphen <code>-</code> and underscore <code>_</code> are allowed. &nbsp;&nbsp;Capital letters too: <code>strong</code>, <code>Strong</code>, <code>STRONG</code> are all different.', 'strong-testimonials' ); ?>
			</p>
			<p>
				<?php _e( '<strong>Why?</strong> A theme or another plugin with a <code>[strong]</code> shortcode will cause a conflict. Try a unique word here if the <code>[strong]</code> shortcode is not working. Then replace <code>[strong]</code> with your new shortcode throughout your site.', 'strong-testimonials' ); ?>
			</p>
		</td>
	</tr>
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
			<?php _e( 'Pagination', 'strong-testimonials' ); ?>
		</th>
		<td>
			<p>
				<label>
					<input type="text" name="wpmtst_options[per_page]" size="2" value="<?php echo esc_attr( $options['per_page'] ); ?>" />
					<?php _e( 'The number of testimonials to show per page.', 'strong-testimonials' ); ?>
				</label>
			</p>
			<p>
				<?php /* translators: %s is a shortcode. */ ?>
				<?php echo sprintf( __( 'This applies to the %s shortcode only.', 'strong-testimonials' ), '<code>[wpmtst-all]</code>' ); ?>
			</p>
			<p><?php _e( 'Enter <code>-1</code> for no pagination.', 'strong-testimonials' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e( 'Reordering', 'strong-testimonials' ); ?>
		</th>
		<td>
			<p>
				<label>
					<input type="checkbox" name="wpmtst_options[reorder]" <?php checked( $options['reorder'] ); ?> />
					<?php /* translators: %s is a shortcode. */ ?>
					<?php _e( 'Enable drag-and-drop reordering in the testimonial list.', 'strong-testimonials' ); ?>
				</label>
			</p>
			<p><?php _e( 'Enabling this will overwrite any existing order settings.', 'strong-testimonials' ); ?></p>
			<p><?php _e( 'Disable this if you want to set the order manually using the Order field.', 'strong-testimonials' ); ?></p>
		</td>
	</tr>
</table>
