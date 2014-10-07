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
	</ul>
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
<th class="parent" scope="row"><?php _e( 'Spam Control', 'strong-testimonials' );?></th>
<td>
	<p><strong>Honeypot</strong></p>
	<p>These methods are both time-tested and widely used. They can be used simultaneously for more protection.</p>
	<ul>
		<li class="checkbox">
			<input type="checkbox" name="wpmtst_options[honeypot_before]" <?php checked( $options['honeypot_before'] ); ?> />
			<?php _e( 'Before', 'strong-testimonials' ); ?>
			<p class="description">Traps spambots by adding an extra empty field that is invisible to humans but not to spambots which tend to fill in every field they find in the form code. Empty field = human. Not empty = spambot.</p>
		</li>
		<li class="checkbox">
			<input type="checkbox" name="wpmtst_options[honeypot_after]" <?php checked( $options['honeypot_after'] ); ?> />
			<?php _e( 'After', 'strong-testimonials' ); ?>
			<p class="description"><strong>Recommended.</strong> Traps spambots by using JavaScript to add a new field as soon as the form is submitted. Since spambots cannot run JavaScript, the new field never gets added. New field = human. Missing = spambot.</p>
		</li>
	</ul>
</td>
</tr>
<tr valign="top">
<th class="child" scope="row"></th>
<td class="stackem child">
	<p><strong>Captcha</strong></p>
	<p>Captcha can be used alongside honeypot methods. Be sure to configure any plugins first, if necessary.</p>
	<ul>
		<li>
			<label>
				<input type="radio" id="" name="wpmtst_options[captcha]" <?php checked( '', $options['captcha'] ); ?> value="" /> none
			</label>
		</li>
		
		<?php foreach ( $plugins as $key => $plugin ) : ?>
		<li>
			<label class="inline <?php if ( ! $plugin['active'] ) echo 'disabled'; ?>">
				<input type="radio" id="" name="wpmtst_options[captcha]" <?php disabled( ! $plugin['active'] ); ?><?php checked( $key, $options['captcha'] ); ?> value="<?php echo $key; ?>" />
				<?php echo $plugin['name']; ?>
			</label>	
			
			<?php if ( isset( $plugin['installed'] ) && $plugin['installed'] ) : ?>
			
				<?php if ( $plugin['active'] ) : ?>
				
					<?php if ( isset( $plugin['settings'] ) && $plugin['settings'] ) : ?>
						<span class="link"><a href="<?php echo $plugin['settings']; ?>"><?php _e( 'settings', 'strong-testimonials' ); ?></a></span> |
					<?php else : ?>
						<span class="notice">(<?php _e( 'no settings', 'strong-testimonials' ); ?>)</span> |
					<?php endif; ?>
					
				<?php else : ?>
					
					<span class="notice disabled">(<?php _e( 'inactive', 'strong-testimonials' ); ?>)</span> |
					
				<?php endif; ?>
				
			<?php else : ?>
			
				<span class="notice disabled">(<?php _e( 'not installed', 'strong-testimonials' ); ?>)</span> |
				<span class="link"><a href="<?php echo $plugin['search']; ?>"><?php _e( 'install plugin', 'strong-testimonials' ); ?></a></span> |
				
			<?php endif; ?>
			
			<span class="link"><a href="<?php echo $plugin['url']; ?>" target="_blank"><?php _e( 'plugin page', 'strong-testimonials' ); ?></a></span>
		</li>
		<?php endforeach; ?>
	</ul>
</td>
</tr>

</table>
