<?php
/**
 * Form Settings
 *
 * @since   1.13
 */
$pages_list   = wpmtst_get_pages();
$form_options = get_option( 'wpmtst_form_options' );
$plugins      = apply_filters( 'wpmtst_captcha_plugins', get_option( 'wpmtst_captcha_plugins', array() ) );

/**
 * If integration with selected Captcha plugin has been removed, disable Captcha.
 */
if ( ! is_array( $plugins ) || ! in_array( $form_options['captcha'], array_keys( $plugins ) ) ) {
	$form_options['captcha'] = '';
	update_option( 'wpmtst_form_options', $form_options );
}

foreach ( $plugins as $key => $plugin ) {

	if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin['file'] ) ) {
		$plugins[ $key ]['installed'] = true;
	}

	$plugins[ $key ]['active'] = is_plugin_active( $plugin['file'] );

	/**
	 * If current Captcha plugin has been deactivated, disable Captcha
	 * so corresponding div does not appear on front-end form.
	 */
	if ( $key == $form_options['captcha'] && ! $plugins[ $key ]['active'] ) {
		$form_options['captcha'] = '';
		update_option( 'wpmtst_form_options', $form_options );
	}
}
?>
<input type="hidden" name="wpmtst_form_options[default_recipient]" value="<?php echo esc_attr( htmlentities( serialize( $form_options['default_recipient'] ) ) ); ?>">

<?php
/**
 * ========================================
 * Labels & Messages
 * ========================================
 */
?>
<h2><?php esc_html_e( 'Form Labels & Messages', 'strong-testimonials' ); ?></h2>

<?php do_action( 'wpmtst_before_form_settings', 'form-messages' ); ?>

<table class="form-table compact" cellpadding="0" cellspacing="0">
	<?php
	$messages = $form_options['messages'];
	foreach ( $messages as $key => $message ) :
		$required = isset( $message['required'] ) ? $message['required'] : true;

		$elid = str_replace( '-', '_', $key );
		// $string, $context, $name
		$content = apply_filters( 'wpmtst_l10n', $message['text'], 'strong-testimonials-form-messages', $message['description'] );
		?>

		<tr>
			<th scope="row">
				<label for="<?php echo esc_attr( $elid ); ?>">
					<?php echo esc_html( $message['description'] ); ?>
				</label>
				<input type="hidden" name="wpmtst_form_options[messages][<?php echo esc_attr( $key ); ?>][description]" value="<?php echo esc_attr( $message['description'] ); ?>"/>
			</th>
			<td>
				<?php if ( 'submission_success' == $elid ) : ?>
					<?php
					$settings = array(
						'textarea_name' => "wpmtst_form_options[messages][$key][text]",
						'textarea_rows' => 10,
					);
					wp_editor( $content, $elid, $settings );
					?>
				<?php else : ?>
					<?php if ( 'required_field' == $elid ) : ?>
						<fieldset>
							<label>
								<input type="checkbox" name="wpmtst_form_options[messages][<?php echo esc_attr( $key ); ?>][enabled]" <?php checked( $message['enabled'] ); ?>">
								<?php esc_html_e( 'Display required notice at top of form', 'strong-testimonials' ); ?>
							</label
						</fieldset>
					<?php endif; ?>
					<input type="text" id="<?php echo esc_attr( $elid ); ?>" name="wpmtst_form_options[messages][<?php echo esc_attr( $key ); ?>][text]" value="<?php echo esc_attr( $content ); ?>" <?php echo $required ? 'required' : ''; ?>/>
				<?php endif; ?>
			</td>
			<td class="actions">
				<input type="button" class="button secondary restore-default-message" value="<?php echo esc_attr_x( 'restore default', 'singular', 'strong-testimonials' ); ?>" data-target-id="<?php echo esc_attr( $elid ); ?>"/>
			</td>
		</tr>

	<?php endforeach; ?>

	<tr>
		<td colspan="3">
			<input type="button" id="restore-default-messages" class="button" name="restore-default-messages" value="<?php esc_attr_e( 'Restore Default Messages', 'strong-testimonials' ); ?>"/>
		</td>
	</tr>
</table>

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr>
		<th scope="row" class="tall">
			<?php esc_html_e( 'Scroll', 'strong-testimonials' ); ?>
		</th>
		<td>
			<fieldset>
				<div>
					<label>
						<input type="checkbox" name="wpmtst_form_options[scrolltop_error]" <?php checked( $form_options['scrolltop_error'] ); ?>/>
						<?php echo wp_kses_post( printf( __( 'If errors, scroll to the first error minus %s pixels. On by default.', 'strong-testimonials' ), '<input type="text" name="wpmtst_form_options[scrolltop_error_offset]" value="' . $form_options['scrolltop_error_offset'] . '" size="3">' ) ); ?>
					</label>
				</div>
				<div>
					<label class="block">
						<input type="checkbox" name="wpmtst_form_options[scrolltop_success]" <?php checked( $form_options['scrolltop_success'] ); ?>/>
						<?php echo wp_kses_post( printf( __( 'If success, scroll to the success message minus %s pixels. On by default.', 'strong-testimonials' ), '<input type="text" name="wpmtst_form_options[scrolltop_success_offset]" value="' . $form_options['scrolltop_success_offset'] . '" size="3">' ) ); ?>
					</label>
				</div>
			</fieldset>
		</td>
	</tr>
</table>

<?php
/**
 * ========================================
 * Actions
 * ========================================
 */
?>
<hr>
<h3><?php esc_html_e( 'Form Actions', 'strong-testimonials' ); ?></h3>

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr>
		<th scope="row">
			<label for="redirect-page">
				<?php esc_html_e( 'Upon Successful Submission', 'strong-testimonials' ); ?>
			</label>
		</th>
		<td>
			<div>
				<label class="success-action">
					<input type="radio" name="wpmtst_form_options[success_action]" value="message" <?php checked( 'message', $form_options['success_action'] ); ?>/> <?php esc_html_e( 'display message', 'strong-testimonials' ); ?>
				</label>
			</div>

			<div>
				<label class="success-action">
					<input type="radio" name="wpmtst_form_options[success_action]" value="id" <?php checked( 'id', $form_options['success_action'] ); ?>/> <?php esc_html_e( 'redirect to a page', 'strong-testimonials' ); ?>
				</label>

				<select id="redirect-page" name="wpmtst_form_options[success_redirect_id]">

					<option value=""><?php esc_html_e( '&mdash; select a page &mdash;' ); ?></option>

					<?php foreach ( $pages_list as $pages ) : ?>

						<option value="<?php echo esc_attr( $pages->ID ); ?>" <?php selected( isset( $form_options['success_redirect_id'] ) ? $form_options['success_redirect_id'] : 0, $pages->ID ); ?>>
							<?php echo esc_html( $pages->post_title ); ?>
						</option>

					<?php endforeach; ?>

				</select>

				<div style="display: inline-block; text-indent: 20px;">
					<label>
						<?php echo esc_html_x( 'or enter its ID or slug', 'to select a redirect page', 'strong-testimonials' ); ?>
						&nbsp;
						<input type="text" id="redirect-page-2" name="wpmtst_form_options[success_redirect_2]" size="30">
					</label>
				</div>
			</div>

			<div>
				<label class="success-action">
					<input type="radio" name="wpmtst_form_options[success_action]" value="url" <?php checked( 'url', $form_options['success_action'] ); ?>/>
					<?php esc_html_e( 'redirect to a URL', 'strong-testimonials' ); ?>
				</label>
				<label>
					<input type="text" id="redirect-page-3" name="wpmtst_form_options[success_redirect_url]" value="<?php echo esc_attr( $form_options['success_redirect_url'] ); ?>" size="75"/>
				</label>
			</div>

		</td>
	</tr>

	<tr>
		<th scope="row">
			<label>
				<?php esc_html_e( 'Post Status', 'strong-testimonials' ); ?>
			</label>
		</th>
		<td>
			<ul class="compact">
				<li>
					<label>
						<input type="radio" name="wpmtst_form_options[post_status]" value="pending" <?php checked( 'pending', $form_options['post_status'] ); ?>/>
						<?php esc_html_e( 'Pending', 'strong-testimonials' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="radio" name="wpmtst_form_options[post_status]" value="publish" <?php checked( 'publish', $form_options['post_status'] ); ?>/>
						<?php esc_html_e( 'Published' ); ?>
					</label>
				</li>
			</ul>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="wpmtst-options-admin-notify">
				<?php esc_html_e( 'Notification Email', 'strong-testimonials' ); ?>
			</label>
		</th>

		<td>
			<div class="match-height">
				<fieldset>
					<label for="wpmtst-options-admin-notify">
						<input id="wpmtst-options-admin-notify" type="checkbox" name="wpmtst_form_options[admin_notify]" <?php checked( $form_options['admin_notify'] ); ?>/>
						<?php esc_html_e( 'Send an email upon new testimonial submission.', 'strong-testimonials' ); ?>
					</label>
				</fieldset>
			</div>
			<div class="email-container" id="admin-notify-fields" <?php echo ( $form_options['admin_notify'] ) ? '' : 'style="display: none;"'; ?>>
				<?php
				require 'email-from.php';
				require 'email-to.php';
				require 'email.php';
				do_action( 'wpmtst_after_notification_fields', 'notification' );
				?>
			</div>
		</td>
	</tr>
</table>

<?php
/**
 * ========================================
 * Spam Control
 * ========================================
 */
?>
<hr>
<h3><?php esc_html_e( 'Form Spam Control', 'strong-testimonials' ); ?></h3>

<table class="form-table" cellpadding="0" cellspacing="0">
	<tr>
		<th scope="row">
			<label>
				<?php echo esc_html_x( 'Honeypot', 'spam control techniques', 'strong-testimonials' ); ?>
			</label>
		</th>
		<td>
			<p>
				<?php esc_html_e( 'These methods for trapping spambots are both time-tested and widely used. May be used simultaneously for more protection.', 'strong-testimonials' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'However, honeypots may not be compatible with WP-SpamShield, Ajax page loading, caching or minification.', 'strong-testimonials' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'If your form is not working properly, try disabling these.', 'strong-testimonials' ); ?>
			</p>
			<?php // TODO Add link to article that explains Ajax page loading. ?>
			<ul>
				<li class="checkbox">
					<label>
						<input type="checkbox" name="wpmtst_form_options[honeypot_before]" <?php checked( $form_options['honeypot_before'] ); ?>/>
						<?php esc_html_e( 'Before', 'strong-testimonials' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Adds a new empty field that is invisible to humans. Spambots tend to fill in every field they find in the form. Empty field = human. Not empty = spambot.', 'strong-testimonials' ); ?></p>
				</li>
				<li class="checkbox">
					<label>
						<input type="checkbox" name="wpmtst_form_options[honeypot_after]" <?php checked( $form_options['honeypot_after'] ); ?>/>
						<?php esc_html_e( 'After', 'strong-testimonials' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Adds a new field as soon as the form is submitted. Spambots cannot run JavaScript so the new field never gets added. New field = human. Missing = spambot.', 'strong-testimonials' ); ?></p>
				</li>
			</ul>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label>
				<a name="captcha-section"></a><?php esc_html_e( 'Captcha', 'strong-testimonials' ); ?>
			</label>
		</th>
		<td class="stackem">
			<p>
				<?php esc_html_e( 'Enable Captcha using one of these plugins. Be sure to configure any plugins first, if necessary.', 'strong-testimonials' ); ?>
				<?php esc_html_e( 'May be used alongside honeypot methods.', 'strong-testimonials' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'May not be compatible with Ajax page loading.', 'strong-testimonials' ); ?>
			</p>
			<ul>
				<li>
					<label>
						<input type="radio" name="wpmtst_form_options[captcha]" <?php checked( '', $form_options['captcha'] ); ?> value=""/>
						none
					</label>
				</li>

				<?php foreach ( $plugins as $key => $plugin ) : ?>
					<li>
						<label class="inline
						<?php
						if ( ! $plugin['active'] ) {
							echo 'disabled';}
						?>
						">
							<input type="radio" name="wpmtst_form_options[captcha]" <?php disabled( ! $plugin['active'] ); ?><?php checked( $key, $form_options['captcha'] ); ?> value="<?php echo esc_attr( $key ); ?>"/>
							<?php echo esc_html( $plugin['name'] ); ?>
						</label>

						<?php if ( isset( $plugin['installed'] ) && $plugin['installed'] ) : // installed ?>

							<?php if ( $plugin['active'] ) : // active ?>

								<?php if ( isset( $plugin['settings'] ) && $plugin['settings'] ) : ?>
									<span class="link"><a href="<?php echo esc_url( $plugin['settings'] ); ?>"><?php echo esc_html_x( 'settings', 'link', 'strong-testimonials' ); ?></a></span>
								<?php else : ?>
									<span class="notice"><?php esc_html_e( 'no settings', 'strong-testimonials' ); ?></span>
								<?php endif; ?>

							<?php else : // inactive ?>

								<span class="notice disabled"><?php echo esc_html_x( 'inactive', 'adjective', 'strong-testimonials' ); ?></span>

							<?php endif; ?>
							|

						<?php else : // not installed ?>

							<span class="notice disabled">(<?php esc_html_e( 'not installed', 'strong-testimonials' ); ?>)</span>

							<?php if ( isset( $plugin['search'] ) && $plugin['search'] ) : ?>
								<span class="link"><a href="<?php echo esc_url( $plugin['search'] ); ?>"><?php echo esc_html_x( 'install plugin', 'link', 'strong-testimonials' ); ?></a></span>
								|
							<?php endif; ?>

						<?php endif; // whether installed ?>

						<span class="link"><a href="<?php echo esc_url( $plugin['url'] ); ?>" target="_blank"><?php echo esc_html_x( 'plugin page', 'link', 'strong-testimonials' ); ?></a></span>

						<?php if ( isset( $plugin['desc'] ) && $plugin['desc'] ) : ?>
							<p class="description
							<?php
							if ( isset( $plugin['style'] ) ) {
								echo esc_attr( $plugin['style'] );
							}
							?>
							"><?php echo wp_kses_post( $plugin['desc'] ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</td>
	</tr>
</table>
