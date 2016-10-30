<tr>
	<td>
		<!-- Name field -->
		<span class="controls">
			<?php if ( ! isset( $recipient['primary'] ) ) : ?>
			<span class="delete-recipient dashicons dashicons-no"></span>
			<?php endif; ?>
		</span>
		<input type="text" class="name-email" size="30" placeholder="<?php _e( '(optional)', 'strong-testimonials' ); ?>" name="wpmtst_form_options[recipients][<?php echo $key; ?>][admin_name]" value="<?php echo esc_attr( $recipient['admin_name'] ); ?>">
	</td>
	<td>
		<!-- Email field -->
		<?php if ( isset( $recipient['primary'] ) ) : ?>
			
		<!-- Primary -->	
		<input type="hidden" name="wpmtst_form_options[recipients][<?php echo $key; ?>][primary]" value="1">
		<label class="block">
			<input id="wpmtst-options-admin-site-email-1" type="radio" name="wpmtst_form_options[recipients][<?php echo $key; ?>][admin_site_email]" <?php checked( $recipient['admin_site_email'], 1 ); ?> value="1"> <?php _e( 'site admin email:', 'strong-testimonials' ); ?>&nbsp;<?php echo get_bloginfo( 'admin_email' ); ?>
		</label>
		<label class="block">
			<input id="wpmtst-options-admin-site-email-0" type="radio" class="focus-next-field" name="wpmtst_form_options[recipients][<?php echo $key; ?>][admin_site_email]" <?php checked( $recipient['admin_site_email'], 0 ); ?> value="0">
			<input id="wpmtst-options-admin-email" type="email" class="name-email" size="30" placeholder="<?php _e( 'email address', 'strong-testimonials' ); ?>" name="wpmtst_form_options[recipients][<?php echo $key; ?>][admin_email]" value="<?php echo esc_attr( $recipient['admin_email'] ); ?>"/>
		</label>
			
		<?php else : ?>
			
		<!-- Additonal -->
		<label class="indent">
			<input type="email" class="name-email" size="30" placeholder="<?php _e( 'email address', 'strong-testimonials' ); ?>" name="wpmtst_form_options[recipients][<?php echo $key; ?>][admin_email]" value="<?php echo esc_attr( $recipient['admin_email'] ); ?>"/>
		</label>
			
		<?php endif; ?>
	</td>
</tr>
