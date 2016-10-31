<div id="about">

	<div class="section">

		<h2><?php _e( 'Create a view for anything you want to display', 'strong-testimonials' ); ?></h2>

		<p><?php _e( 'A <strong>view</strong> is simply of group of settings. You can create an unlimited number of views.', 'strong-testimonials' ); ?></p>

		<p><?php _e( 'For example, you might have one view to <strong>display a grid</strong> of your testimonials, another view for a testimonial <strong>submission form</strong>, and another view for a <strong>slideshow</strong>.', 'strong-testimonials' ); ?></p>

		<p><?php _e( 'Add the view to your site using its unique <strong>shortcode</strong> or by selecting the view in the <strong>widget</strong>.', 'strong-testimonials' ); ?></p>

	</div>

	<hr>

	<div class="section">

		<h2><?php _e( 'What\'s new' ); ?></h2>

		<p><span class="dashicons dashicons-star-filled"></span><?php _e( 'Star ratings.', 'strong-testimonials' ); ?></p>

		<p><span class="dashicons dashicons-admin-page"></span><?php _e( 'Use standard WordPress pagination just like standard blog posts.', 'strong-testimonials' ); ?></p>

		<p><span class="dashicons dashicons-edit"></span><?php _e( 'A redesigned fields section in the view editor.', 'strong-testimonials' ); ?></p>

		<p><span class="dashicons dashicons-editor-paste-text"></span><?php _e( 'Display some text before a field; for example "Service" or "Rating".', 'strong-testimonials' ); ?></p>

		<p><span class="dashicons dashicons-performance"></span><?php _e( 'Improved compatibility and performance.', 'strong-testimonials' ); ?></p>

	</div>

	<hr>

	<div class="section">

		<h2><?php _e( 'Translation', 'strong-testimonials' ); ?></h2>

		<p><?php _e( 'Strong Testimonials is compatible with WPML and Polylang.', 'strong-testimonials' ); ?></p>

		<p><?php _e( 'In each plugin, domains are added to the <strong>String Translation</strong> screens. Those domains encompass the form fields, the form messages, the notification email, and the "Read more" link text in your views. They are updated automatically when your form fields or settings change.', 'strong-testimonials' ); ?></p>

	</div>

	<hr>

	<div class="section">

		<h2><?php _e( 'Troubleshooting' ); ?></h2>

		<h3><?php _e( 'Try clearing your caches' ); ?></h3>

		<p><?php _e( 'Why? CSS and JavaScript files change occasionally. The plugin tries to load the latest version of each file but sometimes caches still need to be cleared. That includes caching and minifying plugins, server-side caching, and your individual browsers.', 'strong-testimonials' ); ?></p>

		<p><?php _e( '<strong>Pro Tip:</strong> Make it a habit to clear your caches after updating any plugin with front-end output.', 'strong-testimonials' ); ?></p>

		<h3><?php _e( 'Upgrading from version 1?', 'strong-testimonials' ); ?></h3>

		<p><?php
		printf(
			wp_kses(
				__( 'The &#91;strong&#93;, &#91;wpmtst&#93; and &#91;read_more&#93; shortcodes and the original widget were <a href="%" target="_blank">removed in version 2</a>. <em>I promise this is the last major change.</em> Everything now happens in <strong>views</strong>.', 'strong-testimonials' ),
				array(
					'a'      => array( 'href' => array(), 'target' => array(), 'class' => array() ),
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
				)
			),
			esc_url( 'https://www.wpmission.com/strong-testimonials-version-2-coming-soon/' )
		);
		?></p>

		<p><?php
		printf(
			wp_kses(
				__( 'Visit <a href="%" target="_blank">WP Mission</a> for more documentation.', 'strong-testimonials' ),
				array(
					'a'      => array( 'href' => array(), 'target' => array(), 'class' => array() ),
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
				)
			),
			esc_url( 'https://www.wpmission.com' )
		);
		?></p>

	</div>

</div>
