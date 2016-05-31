<?php
$plugin_data    = get_plugin_data( WPMTST_DIR . 'strong-testimonials.php', false );
$plugin_version = $plugin_data['Version'];
$major_minor = strtok( $plugin_version, '.' ) . '.' . strtok( '.' );
?>
<div class="wrap wpmtst welcome">

	<h3 class="large"><?php printf( __( 'Welcome to Strong Testimonials %s', 'strong-testimonials' ), $major_minor ); ?></h3>

	<?php do_action( 'wpmtst_guide_before_content' ); ?>

	<h4 class="large"><?php _e( 'Views in, [strong] out' ); ?></h4>

	<p>The &#91;strong&#93;, &#91;wpmtst&#93; and &#91;read_more&#93; shortcodes and the original widget <span
			style="color: red;">were removed</span> in version 2.0. Everything
		now happens in <b>Views</b>.</p>

	<p>Why? <a href="https://www.wpmission.com/strong-testimonials-version-2-coming-soon/" target="_blank">Read more here</a>. <em>I promise this is the last major change.</em></p>

	<h4 class="large"><?php _e( 'What\'s new' ); ?></h4>

	<p>An option to queue the notification emails (under Settings > Form Actions > Notification) when using services like <a href="https://www.mandrill.com/">Mandrill</a> or plugins like <a href="https://wordpress.org/plugins/postman-smtp/">Postman SMTP</a> that replace the standard <code>wp_mail</code> function.</p>

	<p>New "No Quotes" template.</p>

	<p>Comments are now available for individual testimonials. <a href="https://www.wpmission.com/tutorials/how-to-add-comments-in-strong-testimonials/" target="_blank">Tutorial</a></p>

	<p>Videos, tweets, and photos can now be embedded in testimonial content.<br><a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings' ); ?>">Embed width setting</a> | <a href="https://codex.wordpress.org/Embeds">More on embeds</a> | <a href="https://www.wpmission.com/tutorials/youtube-twitter-instagram-strong-testimonials/" target="_blank">Tutorial</a></p>

	<p class="sig"><?php _e( 'Thanks for choosing Strong Testimonials!', 'strong-testimonials' ); ?></p>

	<?php do_action( 'wpmtst_guide_after_content' ); ?>

</div>
