<div class="subsubsection ib">
	<div class="template-tags-help">
		<div class="title"><?php _e( "Template tags for Subject and Message:", 'strong-testimonials' ); ?></div>
		<div class="content">
			<ul>
				<li>%BLOGNAME% - the site title</li>
				<li>%TITLE% - the testimonial title</li>
				<li>%CONTENT% - the testimonial content</li>
				<li>%STATUS% - pending or published</li>
				<li>include your custom fields using this pattern:<br>%FIELD_NAME%<br>for example: %CLIENT_NAME%,
					%EMAIL%
				<li>
			</ul>
		</div>
	</div>
	<table class="half-width">
		<thead>
			<tr>
				<th><?php _e( "Subject:", 'strong-testimonials' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="single-field">
					<label>
						<input id="wpmtst-options-email-subject" class="wide" type="text" size="50"
						       placeholder="subject line" name="wpmtst_form_options[email_subject]"
						       value="<?php echo esc_attr( $form_options['email_subject'] ); ?>"/>
					</label>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="half-width">
		<thead>
			<tr>
				<th>
					<label for="wpmtst-options-email-message">
						<span class="subsection-title"><?php _e( "Message:", 'strong-testimonials' ); ?></span>
					</label>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="single-field">
					<textarea id="wpmtst-options-email-message" rows="6" placeholder="message text"
					          name="wpmtst_form_options[email_message]"><?php echo esc_attr( $form_options['email_message'] ); ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</div>