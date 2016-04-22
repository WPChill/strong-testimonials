<?php
/**
 * Settings
 *
 * @package Strong_Testimonials
 * @since 1.13
 */

$options = get_option( 'wpmtst_options' );
$tags    = array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ), 'br' => array() );
?>
<h3>Admin</h3>
<table class="form-table" cellpadding="0" cellspacing="0">

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Reordering', 'strong-testimonials' ); ?>
		</th>
		<td>
			<fieldset>
			<label>
				<input type="checkbox" name="wpmtst_options[reorder]" <?php checked( $options['reorder'] ); ?>>
				<?php _e( 'Enable drag-and-drop reordering in the testimonial list. Off by default.', 'strong-testimonials' ); ?>
				<p class="description"><?php _e( 'Then set <b>Order</b> to "menu order" in the View.', 'strong-testimonials' ); ?></p>
			</label>
			</fieldset>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Custom Fields Meta Box', 'strong-testimonials' ); ?>
		</th>
		<td>
			<fieldset>
			<label>
				<input type="checkbox" name="wpmtst_options[support_custom_fields]" <?php checked( $options['support_custom_fields'] ); ?>>
				<?php _e( 'Show the <strong>Custom Fields</strong> meta box in the testimonial post editor. This does not affect the <strong>Client Fields</strong> meta box. Off by default.', 'strong-testimonials' ); ?>
				<p class="description"><?php _e( 'For advanced users.', 'strong-testimonials' ); ?></p>
			</label>
			</fieldset>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Troubleshooting', 'strong-testimonials' ); ?>
		</th>
		<td>
			<fieldset>
				<span style="display: inline-block; margin-right: 20px; vertical-align: middle;">Notification Emails</span>
				<label style="display: inline-block; vertical-align: middle;">
					<select id="email_log_level" name="wpmtst_options[email_log_level]">
						<option value="0" <?php selected( $options['email_log_level'], 0 ); ?>>
							<?php _e( 'Log nothing', 'strong-testimonials' ); ?>
						</option>
						<option value="1" <?php selected( $options['email_log_level'], 1 ); ?>>
							<?php _e( 'Log failed emails only (default)', 'strong-testimonials' ); ?>
						</option>
						<option value="2" <?php selected( $options['email_log_level'], 2 ); ?>>
							<?php _e( 'Log both successful and failed emails', 'strong-testimonials' ); ?>
						</option>
					</select>
				</label>
			</fieldset>
			<?php if ( file_exists( WPMTST_DIR . 'strong-debug.log' ) ) : ?>
				<p><a href="<?php echo WPMTST_URL . 'strong-debug.log'; ?>" download="strong-testimonials.log"><?php _e( 'Download the log file', 'strong-testimonials' ); ?></a></p>
			<?php else : ?>
				<p><em><?php _e( 'No log file yet.', 'strong-testimonials' ); ?></em></p>
			<?php endif; ?>
		</td>
	</tr>

</table>

<h3>Output</h3>
<table class="form-table" cellpadding="0" cellspacing="0">

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Scroll Top', 'strong-testimonials' ); ?>
		</th>
		<td>
			<fieldset>
			<label>
				<input type="checkbox" name="wpmtst_options[scrolltop]" <?php checked( $options['scrolltop'] ); ?>>
				<?php printf( __( 'When a new page is selected in paginated Views, scroll to the top of the container minus %s pixels. On by default.', 'strong-testimonials' ), '<input type="text" name="wpmtst_options[scrolltop_offset]" value="' . $options['scrolltop_offset'] . '" size="3">' ); ?>
			</label>
			</fieldset>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<?php _e( 'Remove Whitespace', 'strong-testimonials' ); ?>
		</th>
		<td>
			<fieldset>
			<label>
				<input type="checkbox" name="wpmtst_options[remove_whitespace]" <?php checked( $options['remove_whitespace'] ); ?>>
				<?php _e( 'Remove space between HTML tags in View output to prevent double paragraphs <em>(wpautop)</em>. On by default.', 'strong-testimonials' ); ?>
			</label>
			</fieldset>
		</td>
	</tr>


	<tr valign="top">
		<th scope="row">
			<?php _e( 'Comments', 'strong-testimonials' ); ?>
		</th>
		<td>
			<fieldset>
			<label>
				<input type="checkbox" name="wpmtst_options[support_comments]" <?php checked( $options['support_comments'] ); ?>>
				<?php _e( 'Allow comments on new testimonials. Requires using your theme\'s single post template. Off by default.', 'strong-testimonials' ); ?>
			</label>
			</fieldset>
			<p class="description">To enable comments:</p>
			<ul class="description">
				<li>For individual testimonials, use the <strong>Discussion</strong> meta box in the post editor or <strong>Quick Edit</strong> in the testimonial list.</li>
				<li>For multiple testimonials, use <strong>Bulk Edit</strong> in the testimonial list.</li>
			</ul>
			<p class="description"><?php printf(
					wp_kses(
						__( '<a href="%s" target="_blank">Full tutorial here</a>', 'strong-testimonials' ), $tags
					), esc_url( 'https://www.wpmission.com/tutorials/how-to-add-comments-in-strong-testimonials/' )
				)?></p>
		</td>
	</tr>

</table>
