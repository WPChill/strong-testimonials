<div id="plugin-sidebar">

    <div class="sidebar-block sidebar-links">

		<p class="sig"><?php _e( 'Thanks for choosing Strong Testimonials!', 'strong-testimonials' ); ?></p>

		<?php
		// Need help?
		$link = sprintf(
			wp_kses(
				__( 'Use the <a href="%s" target="_blank">plugin support</a> forum<br>
					or <a href="%s" target="_blank">submit a trouble ticket</a><br>
					or <a href="%s" target="_blank">contact me</a>.', 'strong-testimonials' ), $tags
			),
			esc_url( 'http://wordpress.org/support/plugin/strong-testimonials' ),
			esc_url( 'https://www.wpmission.com/support/' ),
			esc_url( 'https://www.wpmission.com/contact/' )
		);
		?>
		<div class="has-icon icon-help">
			<h3><?php _e( 'Help? Idea? Bug?', 'strong-testimonials' ); ?></h3>
			<ul>
				<li><?php echo $link; ?></li>
			</ul>
		</div>

		<?php
		// Resources
		$links = array();

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://www.wpmission.com/docs/youtube-twitter-instagram-strong-testimonials/' ),
			__( 'Adding video testimonials', 'strong-testimonials' ) );
			//. '<span class="new-doc">NEW</span>';

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://www.wpmission.com/docs/custom-css-strong-testimonials/' ),
				__( 'Add custom CSS', 'strong-testimonials' ) );

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://www.wpmission.com/docs/enable-comments-strong-testimonials/' ),
			__( 'Enable comments', 'strong-testimonials' ) );

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://www.wpmission.com/docs/complete-example-customizing-form/' ),
			__( 'Customize the form', 'strong-testimonials' ) );

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'http://demos.wpmission.com/strong-testimonials/' ),
			__( 'See the demos', 'strong-testimonials' ) );

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://www.wpmission.com/newsletter' ),
			__( 'Subscribe to the newsletter', 'strong-testimonials' ) );
		?>
        <div class="has-icon icon-document">
                <h3><?php _e( 'Knowledge Base', 'strong-testimonials' ); ?></h3>
			<ul>
				<?php foreach ( $links as $link ) : ?>
				<li><?php echo $link; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php
		$link1 = sprintf(
			wp_kses( __( 'Post an honest <a href="%s" target="_blank">review</a> on wordpress.org.', 'strong-testimonials' ), $tags ),
            esc_url( 'https://wordpress.org/support/view/plugin-reviews/strong-testimonials' ) );
		?>
		<div class="has-icon icon-donate">
			<h3><?php _e( 'Contribute', 'strong-testimonials' ); ?></h3>
			<ul>
				<li><?php echo $link1; ?></li>
			</ul>
		</div>

	</div>

    <div class="sidebar-block sidebar-news">
        <h2>Review Markup Add-on</h2>
        <p>Improve your search engine results by adding review markup to your testimonials. </p>
        <div class="actions">
            <button><a href="https://www.wpmission.com/downloads/strong-testimonials-review-markup" target="_blank">Learn more</a></button>
        </div>
    </div>

    <div class="sidebar-block sidebar-news">
        <h2>Multiple Forms Add-on</h2>
        <p>Need more forms for different products or services? No problem. Create unlimited forms.</p>
        <div class="actions">
            <button><a href="https://www.wpmission.com/downloads/strong-testimonials-multiple-forms" target="_blank">Learn more</a></button>
        </div>
    </div>

    <div class="sidebar-block sidebar-news">
        <h2>Properties Add-on</h2>
        <p>Want to rename 'testimonials' to 'reviews'? Want to change which features are available in the post editor? Gain maximum control.</p>
        <div class="actions">
            <button><a href="https://www.wpmission.com/downloads/strong-testimonials-properties" target="_blank">Learn more</a></button>
        </div>
    </div>

</div>
