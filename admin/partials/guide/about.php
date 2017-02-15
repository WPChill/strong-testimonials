<div id="about">

	<div class="section">

		<h2>Looking for shortcodes?</h2>

		<p>
			<?php _e( '<strong>You must create a view first.</strong>', 'strong-testimonials' ); ?>
			<?php _e( 'A view is simply of group of settings for what you want to display. ', 'strong-testimonials' ); ?>
		</p>

		<p>
			<?php _e( 'Instead of learning multiple shortcodes with dozens of options, a view contains all the options in an easy-to-use editor.', 'strong-testimonials' ); ?>
			<?php _e( 'Display the view using its simple shortcode (which you will see after you save it) or the widget.', 'strong-testimonials' ); ?>
		</p>

		<p>
			<?php _e( 'You can create an unlimited number of views.', 'strong-testimonials' ); ?>
			<?php _e( 'For example, you might have one view to <strong>display a grid</strong> of your testimonials, another view for a testimonial <strong>submission form</strong>, and another view for a <strong>slideshow</strong>.', 'strong-testimonials' ); ?>
		</p>

	</div>

	<hr>

	<div class="section">

		<h2><?php _e( 'What\'s new' ); ?></h2>

        <p><span class="dashicons dashicons-external"></span><?php _e( 'Form success redirect options.', 'strong-testimonials' ); ?></p>

        <p><span class="dashicons dashicons-editor-kitchensink"></span><?php _e( 'Form success message editor.', 'strong-testimonials' ); ?></p>

		<p><span class="dashicons dashicons-controls-play"></span><?php _e( 'More slideshow options.', 'strong-testimonials' ); ?></p>

		<p><span class="dashicons dashicons-star-filled"></span><?php _e( 'Star ratings.', 'strong-testimonials' ); ?></p>

		<p><span class="dashicons dashicons-admin-page"></span><?php _e( 'Use standard WordPress pagination just like standard blog posts.', 'strong-testimonials' ); ?></p>

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

		<h3><?php _e( 'Upgrading from version 1?', 'strong-testimonials' ); ?></h3>

		<p><?php
		printf(
			wp_kses(
				__( 'The &#91;strong&#93;, &#91;wpmtst&#93; and &#91;read_more&#93; shortcodes and the original widget were <a href="%s" target="_blank">removed in version 2</a>. Everything now happens in <strong>views</strong>. <em>I promise this is the last major change.</em>', 'strong-testimonials' ),
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
				__( 'Visit <a href="%s" target="_blank">WP Mission</a> for more documentation.', 'strong-testimonials' ),
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
