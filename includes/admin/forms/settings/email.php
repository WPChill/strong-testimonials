<div class="subsubsection">
	<div class="template-tags-help">
		<div class="title"><?php _e( "Template tags for Subject and Message:", 'strong-testimonials' ); ?></div>
		<div class="content">
			<ul>
				<li>%BLOGNAME% - <?php _e( 'the site title', 'strong-testimonials' ); ?></li>
				<li>%TITLE% - <?php _e( 'the testimonial title', 'strong-testimonials' ); ?></li>
				<li>%CONTENT% - <?php _e( 'the testimonial content', 'strong-testimonials' ); ?></li>
				<li>%STATUS% - <?php _e( 'pending or published', 'strong-testimonials' ); ?></li>
				<li><?php _e( 'pattern for custom fields:', 'strong-testimonials' ); ?> %FIELD_NAME%<br><?php _e( 'for example:', 'strong-testimonials' ); ?> %CLIENT_NAME%, %EMAIL%</li>
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
						       placeholder="<?php _e( 'subject line', 'strong-testimonials' ); ?>" name="wpmtst_form_options[email_subject]"
						       value="<?php echo esc_attr( $form_options['email_subject'] ); ?>">
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
					<textarea id="wpmtst-options-email-message" rows="6" placeholder="<?php _e( 'message text', 'strong-testimonials' ); ?>"
					          name="wpmtst_form_options[email_message]"><?php echo esc_attr( $form_options['email_message'] ); ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</div>