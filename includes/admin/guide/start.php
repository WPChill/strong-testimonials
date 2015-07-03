<div class="guide-content start">

	<?php do_action( 'wpmtst_guide_before_content' ); ?>

	<section>
		<h3><?php _e( 'Two ways to add testimonials', 'strong-testimonials' ); ?></h3>
		
		<h4><?php _e( '1. Views <em>(recommended)', 'strong-testimonials' ); ?></em></h4>
		
		<p><?php _e( 'A View can display your testimonials, create a slideshow, or show a testimonial submission form.', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'Set it up and add it to a page with the <code>[testimonial_view]</code> shortcode or add it to a sidebar with a widget.', 'strong-testimonials' ); ?></p>
		
		<h4><?php _e( '2. The [strong] shortcode', 'strong-testimonials' ); ?></h4>
		
		<p><?php _e( 'This shortcode has the same options as a View.', 'strong-testimonials' ); ?></p>
	
		<p><?php _e( 'Examples:', 'strong-testimonials' ); ?></p>
		<p><code>[strong form]</code></p>
		<p><code>[strong slideshow excerpt category=2]</code></p>
		<pre><code>[strong title thumbnail newest]
   [client]
      [field name="client_name" class="name"]
      [field name="company_name" url="company_website" class="company" new_tab]
      [date class="date" format="F Y"]
   [/client]
[/strong]</code></pre>
		
		<p><?php printf( 
				__( 'The shortcode language can be overwhelming at first. See the <a href="%s">Shortcodes tab</a> and <a href="%s" target="_blank">these demos</a> for help.', 'strong-testimonials' ), 
				admin_url( 'edit.php?post_type=wpm-testimonial&page=guide&tab=shortcodes' ), 
				'http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/' ); ?></p>
	
		<h3><?php _e( 'Views are better', 'strong-testimonials' ); ?></h3>
	
		<p><?php _e( 'They are easier to learn and adjust. They allow more room to add new features that might not fit as yet another shortcode attribute.', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'I admit the <code>[strong]</code> shortcode is (overly) complex. And for a shortcode, it\'s not very short; maybe "longcode" is a better term :)', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'If you\'re just getting started, try Views first.', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'If you already have some <code>[strong]</code> shortcodes, I encourage you to convert them to Views and I\'m here to help if you need.', 'strong-testimonials' ); ?></p>
		
		<p><strong style="color:#CD0000;"><?php _e( 'Warning:</strong> The original <code>[wpmtst]</code> shortcodes and the original widget will be removed soon so definitely replace those.', 'strong-testimonials' ); ?></p>
	</section>
		
	<section>
		<h3><?php _e( 'Thanks for choosing Strong Testimonials', 'strong-testimonials' ); ?></h3>

		<p><?php printf( __( 'If you have not already, please consider posting a review and casting a compatibility vote on <a href="%s" target="_blank" rel="nofollow">wordpress.org</a>.', 'strong-testimonials' ), 'https://wordpress.org/plugins/strong-testimonials/' ); ?></em></p>

		<p><?php printf( 
				__( 'I also invite you to join <a href="%s" target="_blank">WP Mission</a> to <a href="%s" target="_blank">submit feature requests</a> and vote on the future of our plugins. Seriously, your input matters.', 'strong-testimonials' ), 
				'https://www.wpmission.com/', 
				'https://www.wpmission.com/feature-request' ); ?></p>

		<p>
			<strong>Chris Dillon</strong><br>
			Founder, <a href="https://www.wpmission.com" target="_blank">WP Mission</a><br>
		</p>
	</section>

</div>
