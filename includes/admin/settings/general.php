<?php
/**
 * General settings form.
 *
 * @package Strong_Testimonials
 */
?>

<div class="update-nag strong">
	<h2>The [strong] shortcode has reached the end of its capacity and will be removed <span class="v2warning">soon</span>.</h2>
	<p>All future development will be in <b><a href="<?php admin_url( 'edit.php?post_type=wpm-testimonial&page=views'); ?>">Views</a></b>. Take a few minutes to convert everything now and reap the rewards later.</p>
	<p>This decision was not made lightly and I believe it is necessary to move forward. ~ <i><a href="http://www.wpmission.com/contact" target="_blank">Chris</a></i></p>
</div>

<input type="hidden" name="wpmtst_options[default_template]" value="<?php esc_attr_e( $options['default_template'] ); ?>">
<input type="hidden" name="wpmtst_options[client_section]" value="<?php esc_attr_e( $options['client_section'] ); ?>">
<input type="hidden" name="wpmtst_options[per_page]" value="<?php echo $options['per_page']; ?>">
<input type="hidden" name="wpmtst_options[reorder]" value="<?php echo $options['reorder']; ?>">

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<th scope="row">
			<?php _e( 'Shortcode', 'strong-testimonials' ); ?>
		</th>
		<td>
			<p>
				<label>
					<input id="wpmtst_shortcode" type="text" name="wpmtst_options[shortcode]" size="20" value="<?php echo esc_attr( $options['shortcode'] ); ?>" autocomplete="off" placeholder="<?php _e( 'enter a word', 'strong-testimonials' ); ?>" />
				</label>
				<span class="between-inputs">or</span>
				<input id="restore-default-shortcode" type="button" class="button secondary restore-default-shortcode" value="<?php _ex( 'restore default', 'singular', 'strong-testimonials' ); ?>">
				<span class="between-inputs">or</span>
				<select id="wpmtst_shortcode_select">
					<option value="0"><?php _e( '&mdash; select &mdash;', 'strong-testimonials' ); ?></option>
					<option>testimonials</option>
					<option>my_testimonials</option>
					<option>strong_testimonials</option>
					<option>applause</option>
					<option>bravos</option>
					<option>cheers</option>
					<option>kudos</option>
					<option>praise</option>
					<option>raves</option>
					<option>reviews</option>
				</select>
			</p>
			<p>
				<?php _e( 'The hyphen <code>-</code> and underscore <code>_</code> are allowed. &nbsp;&nbsp;Capital letters too: <code>strong</code>, <code>Strong</code>, <code>STRONG</code> are all different.', 'strong-testimonials' ); ?>
			</p>
			<p>
				<?php _e( '<strong>Why?</strong> A theme or another plugin with a <code>[strong]</code> shortcode will cause a conflict. Try a unique word here if the <code>[strong]</code> shortcode is not working. Then update your site with the new shortcode.', 'strong-testimonials' ); ?>
			</p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e( 'Load stylesheets', 'strong-testimonials' );?>
		</th>
		<td class="stackem">
			<p><?php _e( 'This applies to the <code>[strong]</code> shortcode only.', 'strong-testimonials' ); ?></p>
			<ul>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_page_style]" <?php checked( $options['load_page_style'] ); ?>>
						<?php _e( 'Pages', 'strong-testimonials' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_widget_style]" <?php checked( $options['load_widget_style'] ); ?>>
						<?php _e( 'Widget', 'strong-testimonials' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_form_style]" <?php checked( $options['load_form_style'] ); ?>>
						<?php _e( 'Submission Form', 'strong-testimonials' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="wpmtst_options[load_rtl_style]" <?php checked( $options['load_rtl_style'] ); ?>>
						<?php _e( 'RTL', 'strong-testimonials' ); ?>
					</label>
				</li>
			</ul>
		</td>
	</tr>
</table>
