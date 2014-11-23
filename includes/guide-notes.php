	<div class="guide-content notes">
	
		<h3><?php _e( 'Template files', 'strong-testimonials' ); ?></h3>
		<p><?php _e( 'The [strong] shortcode uses template files.', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'To create custom templates, copy <code>templates/theme/testimonials.php</code> from the plugin directory to your theme directory. This template file contains both the loop and the testimonial layout.', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'The plugin will search for that template file in this order: (1) your child theme (if using one), (2) your parent theme, (3) the plugin templates directory.', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'Additional template files can be created such as <code>testimonials-narrow.php</code> and found by adding the shortcode attribute <code>template="narrow"</code>.', 'strong-testimonials' ); ?></p>
		
		<h3><?php _e( 'Template functions', 'strong-testimonials' ); ?></h3>
		<p><?php _e( 'Use <code>wpmtst_field( $field_name )</code> to get one of your custom fields.', 'strong-testimonials' ); ?></p>
		<p><?php _e( "Use <code>wpmtst_field( 'truncated', array( 'char_limit' => 200 ) )</code> to get the truncated testimonial content.", 'strong-testimonials' ); ?></p>
		<p><?php _e( "Use <code>wpmtst_field( 'client', array( 'content' => \$shortcode_content )</code> to get <code>[strong]</code> child shortcodes for the client section (more <a href=\"edit.php?post_type=wpm-testimonial&page=shortcodes\">here</a>).", 'strong-testimonials' ); ?></p>
		<p><?php _e( 'Look at <code>templates/theme/testimonials.php</code> in the plugin directory for examples.', 'strong-testimonials' ); ?></p>
		
		<h3><?php _e( 'Translations', 'strong-testimonials' ); ?></h3>
		<p><?php printf( __( 'There is a POT file in the plugin\'s <code>languages</code> directory. <a href="%s" target=_blank">Contact me</a> to submit a translation file.', 'strong-testimonials' ), 'http://www.wpmission.com/contact/' ); ?></p>
		<p><?php printf( __( 'The field labels on the submission form will not appear in the POT file so they must be translated manually in the <a href="%s">Fields editor</a>.', 'strong-testimonials' ), 'edit.php?post_type=wpm-testimonial&page=fields' ); ?></p>
		<p><?php _e( 'The WordPress browser elements like the <button style="font-size: smaller;">Browse...</button> button and the "No file chosen" message are controlled by the WordPress language and browser language settings.', 'strong-testimonials' ); ?></p>
		
		<h3><?php _e( 'Nofollow links', 'strong-testimonials' ); ?></h3>
		<p><?php _e( 'The Client Details meta box in the post editor now has an option to add <code>rel="nofollow"</code> to client URLs. There is no global setting yet so each one must be enabled manually. Future versions will likely have a global setting with a local override.', 'strong-testimonials' ); ?></p>
		<p></p>
		
		<h3><?php _e( 'Found a bug? Have an idea? Need help?', 'strong-testimonials' ); ?></h3>
		<p><?php printf( __( 'Please use the <a href="%s" target="_blank">support forum</a> or <a href="%s" target="_blank">contact me</a>.', 'strong-testimonials' ), 'http://wordpress.org/support/plugin/strong-testimonials', 'http://www.wpmission.com/contact/' ); ?></p>
		
	</div>
