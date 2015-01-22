<?php
/**
 * Settings > Form tab
 *
 * @package Strong_Testimonials
 * @since 1.13
 */
 
?>
<p>
	<em><?php printf( __( 'Customize the form fields <a href="%s">here</a>.', 'strong-testimonials' ), admin_url( 'edit.php?post_type=wpm-testimonial&page=fields' ) ); ?></em>
</p>

<h3><?php _e( 'Labels & Messages', 'strong-testimonials' ); ?></h3>

<table class="form-table multiple compact" cellpadding="0" cellspacing="0">
	<?php $messages = $form_options['messages']; ?>
	<?php foreach ( $messages as $key => $message ) : ?>
	<tr>
		<td>
			<?php echo $messages[$key]['description']; ?>
			<input type="hidden" name="wpmtst_form_options[messages][<?php echo $key; ?>][description]" value="<?php esc_attr_e( $messages[$key]['description'] ); ?>" />
		</td>
		<td>
			<input type="text" id="<?php echo $key; ?>" name="wpmtst_form_options[messages][<?php echo $key; ?>][text]" size="" value="<?php echo esc_attr( $messages[$key]['text'] ); ?>" required />
		</td>
		<td class="actions">
			<input type="button" class="button button-small secondary restore-default-message" value="<?php _ex( 'restore default', 'singular', 'strong-testimonials' ); ?>" />
		</td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="3">
			<input type="button" value="<?php _ex( 'Restore Defaults', 'multiple', 'strong-testimonials' ); ?>" class="button" id="restore-default-messages" name="restore-default-messages">
		</td>
	</tr>
</table>


<h3><?php _ex( 'Actions', 'noun', 'strong-testimonials' );?></h3>

<table class="form-table multiple" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php _e( 'Post status', 'strong-testimonials' ); ?>
		</td>
		<td>
			<ul class="compact">
				<li>
					<label>
						<input type="radio" name="wpmtst_form_options[post_status]" <?php checked( 'pending', $form_options['post_status'] ); ?> value="pending" />
						<?php _e( 'Pending' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="radio" name="wpmtst_form_options[post_status]" <?php checked( 'publish', $form_options['post_status'] ); ?> value="publish" />
						<?php _e( 'Published' ); ?>
					</label>
				</li>
			</ul>
		</td>
	</tr>
	
	<tr>
		<td class="align-top">
			<?php _e( 'Notification', 'strong-testimonials' ); ?>
		</td>
		
		<td class="has-divs">
		
			<div>
				<label>
					<input id="wpmtst-options-admin-notify" type="checkbox" name="wpmtst_form_options[admin_notify]" <?php checked( $form_options['admin_notify'] ); ?> />
					<?php _e( 'Send email upon new testimonial submission', 'strong-testimonials' ); ?>
				</label>
			</div>
			
			
			<!-- FROM -->
			<hr>
			
			<div>
			
				<div class="subsection"><?php _e( "From:", 'strong-testimonials' ); ?></div>
				
				<div>
					<div class="left-col">
						<label for="wpmtst-options-sender-name">
							<span><?php _e( "Name", 'strong-testimonials' ); ?></span>
						</label>
					</div>
					<div class="right-col">
						<input id="wpmtst-options-sender-name" type="text" size="30" placeholder="sender's name" name="wpmtst_form_options[sender_name]" value="<?php echo esc_attr( $form_options['sender_name'] ); ?>" />
					</div>
				</div>
				
				<div>
					<div class="left-col">
						<?php _e( "Email", 'strong-testimonials' ); ?>
					</div>
					<div class="right-col">
						<div>
							<label>
								<input id="wpmtst-options-sender-site-email-1" type="radio" name="wpmtst_form_options[sender_site_email]" <?php checked( $form_options['sender_site_email'], 1 ); ?> value="1" /> site admin email ( <?php echo get_bloginfo( 'admin_email' ); ?> ) <a href="<?php echo admin_url( 'options-general.php'); ?>">change</a>
							</label>
						</div>
					</div>
				</div>
				
				<div class="left-col"></div>
				<div class="right-col">
					<label>
						<input id="wpmtst-options-sender-site-email-0" class="focus-next-field" type="radio" name="wpmtst_form_options[sender_site_email]" <?php checked( $form_options['sender_site_email'], 0 ); ?> value="0" />
					</label>
					<label>
						<input id="wpmtst-options-sender-email" type="email" size="30" placeholder="sender's email" name="wpmtst_form_options[sender_email]" value="<?php echo esc_attr( $form_options['sender_email'] ); ?>" />
					</label>
				</div>
				
			</div>
			
			<!-- TO -->
			
			<div>
			
				<div class="subsection"><?php _e( "To:", 'strong-testimonials' ); ?></div>
				
				<div>
					<div class="left-col">
						<label for="wpmtst-options-admin-name">
							<?php _e( "Name", 'strong-testimonials' ); ?>
						</label>
					</div>
					<div class="right-col">
						<input id="wpmtst-options-admin-name" type="text" size="30" placeholder="name" name="wpmtst_form_options[admin_name]" value="<?php echo esc_attr( $form_options['admin_name'] ); ?>" />
					</div>
				</div>
				
				<div>
					<div class="left-col">
						<?php _e( "Email", 'strong-testimonials' ); ?>
					</div>
					<div class="right-col">
						<label>
							<input id="wpmtst-options-admin-site-email-1" type="radio" name="wpmtst_form_options[admin_site_email]" <?php checked( $form_options['admin_site_email'], 1 ); ?> value="1" /> site admin email ( <?php echo get_bloginfo( 'admin_email' ); ?> ) <a href="<?php echo admin_url( 'options-general.php'); ?>">change</a>
						</label>
					</div>
				</div>
				
				<div>
					<div class="left-col"></div>
					<div class="right-col">
						<label>
							<input id="wpmtst-options-admin-site-email-0" class="focus-next-field" type="radio" name="wpmtst_form_options[admin_site_email]" <?php checked( $form_options['admin_site_email'], 0 ); ?> value="0" />
						</label>
						<label>
							<input id="wpmtst-options-admin-email" type="email" size="30" placeholder="email address" name="wpmtst_form_options[admin_email]" value="<?php echo esc_attr( $form_options['admin_email'] ); ?>" />
						</label>
					</div>
				</div>
				
			</div>
			
			<hr>
			
			<div class="template-tags-help">
				<div class="title"><?php _e( "Template tags for Subject and Message:", 'strong-testimonials' ); ?></div>
				<div class="content">
					<ul>
						<li>%BLOGNAME% - the site title</li>
						<li>%TITLE% - the testimonial title</li>
						<li>%CONTENT% - the testimonial content</li>
						<li>%STATUS% - pending or published</li>
						<li>include your custom fields using this pattern:<br>%FIELD_NAME%<br>for example: %CLIENT_NAME%, %EMAIL%<li>
					</ul>
				</div>
			</div>
			</div>

			<div class="half-width">
			
				<!-- SUBJECT -->
				<div>
					<div class="subsection"><?php _e( "Subject:", 'strong-testimonials' ); ?></div>
				</div>
				
				<div class="input-email-subject">
					<label>
						<input id="wpmtst-options-email-subject" class="wide" type="text" size="50" placeholder="subject line" name="wpmtst_form_options[email_subject]" value="<?php echo esc_attr( $form_options['email_subject'] ); ?>" />
					</label>
				</div>
				
				<!-- MESSAGE -->
				
				<div>
					<label for="wpmtst-options-email-message">
						<span class="subsection"><?php _e( "Message:", 'strong-testimonials' ); ?></span>
					</label>
				</div>
				<div>
					<textarea id="wpmtst-options-email-message" rows="6" placeholder="message text" name="wpmtst_form_options[email_message]"><?php echo esc_attr( $form_options['email_message'] ); ?></textarea>
				</div>
			
			</div><!-- .half-width -->
			
		</td><!-- .has-divs -->
	</tr>
</table>

<h3><?php _e( 'Spam Control', 'strong-testimonials' );?></h3>

<table class="form-table multiple" cellpadding="0" cellspacing="0">
	<tr>
		<td class="align-top">
			<p><?php _ex( 'Honeypot', 'spam control techniques', 'strong-testimonials' ); ?></p>
		</td>
		<td>
			<p><?php _e( 'These methods are both time-tested and widely used. They can be used simultaneously for more protection.', 'strong-testimonials' ); ?></p>
			<ul>
				<li class="checkbox">
					<input type="checkbox" name="wpmtst_form_options[honeypot_before]" <?php checked( $form_options['honeypot_before'] ); ?> />
					<?php _e( 'Before', 'strong-testimonials' ); ?>
					<p class="description"><?php _e( 'Traps spambots by adding an extra empty field that is invisible to humans but not to spambots which tend to fill in every field they find in the form code. Empty field = human. Not empty = spambot.', 'strong-testimonials' ); ?></p>
				</li>
				<li class="checkbox">
					<input type="checkbox" name="wpmtst_form_options[honeypot_after]" <?php checked( $form_options['honeypot_after'] ); ?> />
					<?php _e( 'After', 'strong-testimonials' ); ?>
					<p class="description"><strong><?php _e( 'Recommended.', 'strong-testimonials' ); ?></strong> <?php _e( 'Traps spambots by using JavaScript to add a new field as soon as the form is submitted. Since spambots cannot run JavaScript, the new field never gets added. New field = human. Missing = spambot.', 'strong-testimonials' ); ?></p>
				</li>
			</ul>
		</td>
	</tr>
	<tr valign="top">
		<td class="align-top">
			<p><?php _e( 'Captcha', 'strong-testimonials' ); ?></strong>
		</td>
		<td class="stackem">
			<p><?php _e( 'Can be used alongside honeypot methods. Be sure to configure any plugins first, if necessary.', 'strong-testimonials' ); ?></p>
			<ul>
				<li>
					<label>
						<input type="radio" id="" name="wpmtst_form_options[captcha]" <?php checked( '', $form_options['captcha'] ); ?> value="" /> none
					</label>
				</li>
				
				<?php foreach ( $plugins as $key => $plugin ) : ?>
				<li>
					<label class="inline <?php if ( ! $plugin['active'] ) echo 'disabled'; ?>">
						<input type="radio" id="" name="wpmtst_form_options[captcha]" <?php disabled( ! $plugin['active'] ); ?><?php checked( $key, $form_options['captcha'] ); ?> value="<?php echo $key; ?>" />
						<?php echo $plugin['name']; ?>
					</label>	
					
					<?php if ( isset( $plugin['installed'] ) && $plugin['installed'] ) : ?>
					
						<?php if ( $plugin['active'] ) : ?>
						
							<?php if ( isset( $plugin['settings'] ) && $plugin['settings'] ) : ?>
								<span class="link"><a href="<?php echo $plugin['settings']; ?>"><?php _ex( 'settings', 'link', 'strong-testimonials' ); ?></a></span> |
							<?php else : ?>
								<span class="notice"><?php _e( 'no settings', 'strong-testimonials' ); ?></span> |
							<?php endif; ?>
							
						<?php else : ?>
							
							<span class="notice disabled"><?php _ex( 'inactive', 'adjective', 'strong-testimonials' ); ?></span> |
							
						<?php endif; ?>
						
					<?php else : ?>
					
						<span class="notice disabled">(<?php _e( 'not installed', 'strong-testimonials' ); ?>)</span> |
						<span class="link"><a href="<?php echo $plugin['search']; ?>"><?php _ex( 'install plugin', 'link', 'strong-testimonials' ); ?></a></span> |
						
					<?php endif; ?>
					
					<span class="link"><a href="<?php echo $plugin['url']; ?>" target="_blank"><?php _ex( 'plugin page', 'link', 'strong-testimonials' ); ?></a></span>
				</li>
				<?php endforeach; ?>
			</ul>
		</td>
	</tr>
</table>
