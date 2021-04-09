<?php
/**
 * Form Settings
 *
 * @since   1.13
 */

$pages_list   = wpmtst_get_pages();
$form_options = get_option( 'wpmtst_form_options' );

?>
<input type="hidden"
       name="wpmtst_form_options[default_recipient]"
       value="<?php echo esc_attr( htmlentities( serialize( $form_options['default_recipient'] ) ) ); ?>">

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
					<?php echo esc_html_x( $message['description'], 'description', 'strong-testimonials' ); ?>
                </label>
                <input type="hidden" name="wpmtst_form_options[messages][<?php echo esc_attr( $key ); ?>][description]"
                       value="<?php echo esc_attr( $message['description'] ); ?>"/>
            </th>
            <td>
				<?php if ( 'submission_success' == $elid ): ?>
					<?php
					$settings = array(
						'textarea_name' => "wpmtst_form_options[messages][$key][text]",
						'textarea_rows' => 10,
					);
					wp_editor( $content, $elid, $settings );
					?>
				<?php else: ?>
					<?php if ( 'required_field' == $elid ): ?>
                        <fieldset>
                            <label>
                                <input type="checkbox"
                                       name="wpmtst_form_options[messages][<?php echo esc_attr( $key ); ?>][enabled]"
                                       <?php checked( $message['enabled'] ); ?>>
								<?php esc_html_e( 'Display required notice at top of form', 'strong-testimonials' ); ?>
							</label>
                        </fieldset>
					<?php endif; ?>
                    <input type="text" id="<?php echo esc_attr( $elid ); ?>"
                           name="wpmtst_form_options[messages][<?php echo esc_attr( $key ); ?>][text]"
                           value="<?php echo esc_attr( $content ); ?>"
						   <?php echo $required ? 'required' : '' ?>/>
				<?php endif; ?>
            </td>
            <td class="actions">
                <input type="button" class="button secondary restore-default-message"
                       value="<?php echo esc_html_x( 'restore default', 'singular', 'strong-testimonials' ); ?>"
                       data-target-id="<?php echo esc_attr( $elid ); ?>"/>
            </td>
        </tr>

	<?php endforeach; ?>

    <tr>
        <td colspan="3">
            <input type="button" id="restore-default-messages" class="button"
                   name="restore-default-messages"
                   value="<?php esc_html_e( 'Restore Default Messages', 'strong-testimonials' ); ?>"/>
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
                        <input type="checkbox"
                               name="wpmtst_form_options[scrolltop_error]" <?php checked( $form_options['scrolltop_error'] ); ?>/>
						<?php printf( esc_html__( 'If errors, scroll to the first error minus %s pixels. On by default.', 'strong-testimonials' ), '<input type="text" name="wpmtst_form_options[scrolltop_error_offset]" value="' . esc_attr( $form_options['scrolltop_error_offset'] ) . '" size="3">' ); ?>
                    </label>
                </div>
                <div>
                    <label class="block">
                        <input type="checkbox"
                               name="wpmtst_form_options[scrolltop_success]" <?php checked( $form_options['scrolltop_success'] ); ?>/>
						<?php printf( esc_html__( 'If success, scroll to the success message minus %s pixels. On by default.', 'strong-testimonials' ), '<input type="text" name="wpmtst_form_options[scrolltop_success_offset]" value="' . esc_attr( $form_options['scrolltop_success_offset'] ) . '" size="3">' ); ?>
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
        
        <?php do_action('wpmtst_before_form_actions', $form_options); ?>
        
	<tr>
		<th scope="row">
			<label for="redirect-page">
				<?php esc_html_e( 'Upon Successful Submission', 'strong-testimonials' ); ?>
                <div class="wpmtst-tooltip"><span>[?]</span>
                    <div class="wpmtst-tooltip-content"><?php esc_html_e('This setting is overwritten by "Submit form without reloading the page (Ajax)" setting found in Form view in the "Views" section.','strong-testimonials'); ?></div>
                </div>
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

					<option value=""><?php esc_html_e( '&mdash; select a page &mdash;', 'strong-testimonials' ); ?></option>
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
						<?php esc_html_e( 'Published', 'strong-testimonials' ); ?>
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
                                            <?php esc_html_e( 'Receive an email when new testimonials are submitted and waiting for approval.', 'strong-testimonials' ); ?>
                                    </label>
                            </fieldset>
			</div>
			<div class="email-container" id="admin-notify-fields" <?php echo ( $form_options['admin_notify'] ) ? '' : 'style="display: none;"'; ?>>
				<?php
				include 'email-from.php';
				include 'email-to.php';
				include 'email.php';
				do_action( 'wpmtst_after_notification_fields', 'notification' );
				?>
                        </div>
                        <?php do_action('wpmtst_after_notification_options', $form_options); ?>
                        <?php do_action( 'wpmtst_after_mail_notification_settings' );  ?>
                    
        </td>
    </tr>
    
</table>

<?php do_action( 'wpmtst_after_form_settings', $form_options, 'wpmtst_form_options' );  ?>