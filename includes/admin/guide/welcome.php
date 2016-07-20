<?php
$plugin_data    = get_plugin_data( WPMTST_DIR . 'strong-testimonials.php', false );
$plugin_version = $plugin_data['Version'];
$major_minor    = strtok( $plugin_version, '.' ) . '.' . strtok( '.' );
?>
<div class="wrap wpmtst welcome">

	<h3 class="large"><?php printf( __( 'Welcome to Strong Testimonials %s', 'strong-testimonials' ), $major_minor ); ?></h3>

	<?php do_action( 'wpmtst_guide_before_content' ); ?>

	<h4 class="large"><?php _e( 'Upgrading from version 1?' ); ?></h4>

	<p>The &#91;strong&#93;, &#91;wpmtst&#93; and &#91;read_more&#93; shortcodes and the original widget <span
			style="color: red;">were removed</span> in version 2.0. <a href="https://www.wpmission.com/strong-testimonials-version-2-coming-soon/" target="_blank">Here's why</a>. <em>I promise this is the last major change.</em></p>

	<p>Everything now happens in <a href="<?php echo admin_url('edit.php?post_type=wpm-testimonial&page=testimonial-guide&tab=views' ); ?>">Views</a>.</p>

	<h4 class="large"><?php _e( 'What\'s new' ); ?></h4>

	<h5>New features from your requests</h5>

	<p>A new template named "Modern".</p>

	<p>The slideshow now has several navigation options. Finally!</p>

	<p>An indicator bubble when new submissions are awaiting moderation.</p>

	<p>The "Read more" link now works in the same way as blog posts for a consistent user experience. The excerpt length and link text are customizable.</p>

	<p>Adding a "Read more" to <b>another page</b> is now a separate option and allows linking to a post as well.</p>

	<p>YouTube, Twitter, Instagram and more can now be embedded in testimonial content. <a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings' ); ?>">Embed width setting</a> | <a href="https://codex.wordpress.org/Embeds">More on embeds</a> | <a href="https://www.wpmission.com/tutorials/youtube-twitter-instagram-strong-testimonials/" target="_blank">Tutorial</a></p>

	<h5>Compatibility</h5>

	<p>Compatible with <a href="https://wordpress.org/plugins/megamenu/">Max Mega Menu</a>, <a href="https://wordpress.org/plugins/foobox-image-lightbox/">FooBox Image Lightbox</a> and <a href="http://fooplugins.com/plugins/foobox/">FooBox Pro</a>.</p>

	<p>An option to queue the notification emails when using services like <a href="https://www.mandrill.com/">Mandrill</a> or plugins like <a href="https://wordpress.org/plugins/postman-smtp/">Postman SMTP</a> that replace the standard <code>wp_mail</code> function.</p>

	<p>To adhere to WordPress guidelines, Font Awesome is now included in the plugin instead of loading via CDN.</p>

	<?php do_action( 'wpmtst_guide_after_content' ); ?>

</div>
