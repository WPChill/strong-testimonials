<div id="plugin-sidebar">

    <div class="sidebar-block sidebar-links">

		<?php
		// Need help?
        $links = array();

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'http://wordpress.org/support/plugin/strong-testimonials' ),
			__( 'Public support forum', 'strong-testimonials' ) )
			. ' ' . __( 'or', 'strong-testimonials');

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://support.strongplugins.com' ),
            __( 'Private support ticket', 'strong-testimonials' ) )
            . ' ' . __( 'or', 'strong-testimonials');

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://strongplugins.com/contact/' ),
            __( 'Contact the developer', 'strong-testimonials' ) );
		?>
		<div class="has-icon icon-help">
			<h3><?php _e( 'Help? Idea? Bug?', 'strong-testimonials' ); ?></h3>
			<ul>
				<?php foreach ( $links as $link ) : ?>
                <li><?php echo $link; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php
		// Resources
		$links = array();

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://support.strongplugins.com/article/troubleshooting-strong-testimonials/' ),
			__( 'Troubleshoot', 'strong-testimonials' ) )
			. '<span class="new-doc">NEW</span>';

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://support.strongplugins.com/article/youtube-twitter-instagram-strong-testimonials/' ),
			__( 'Add YouTube or Twitter', 'strong-testimonials' ) );
			//. '<span class="new-doc">NEW</span>';

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://support.strongplugins.com/article/custom-css-strong-testimonials/' ),
				__( 'Add custom CSS', 'strong-testimonials' ) );

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://support.strongplugins.com/article/enable-comments-strong-testimonials/' ),
			__( 'Enable comments', 'strong-testimonials' ) );

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://support.strongplugins.com/article/complete-example-customizing-form/' ),
			__( 'Customize the form', 'strong-testimonials' ) );

		$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'http://demos.wpmission.com/strong-testimonials/' ),
			__( 'See the demos', 'strong-testimonials' ) );

		//$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		//	esc_url( 'https://strongplugins.com/newsletter' ),
		//	__( 'Subscribe to the newsletter', 'strong-testimonials' ) );
		?>
        <div class="has-icon icon-document">
                <h3><?php _e( 'How To', 'strong-testimonials' ); ?></h3>
			<ul>
				<?php foreach ( $links as $link ) : ?>
				<li><?php echo $link; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php
		$link1 = sprintf(
			wp_kses( __( 'Good <a href="%s" target="_blank">reviews</a> are appreciated!', 'strong-testimonials' ), $tags ),
            esc_url( 'https://wordpress.org/support/plugin/strong-testimonials/reviews/#new-post' ) );
		?>
		<div class="has-icon icon-donate">
			<h3><?php _e( 'Like It?', 'strong-testimonials' ); ?></h3>
			<ul>
				<li><?php echo $link1; ?></li>
			</ul>
		</div>

	</div>

    <div class="sidebar-block sidebar-news addon review-markup">
        <h2>Review Markup Add-on</h2>
        <p>Improve your search engine results by adding review markup to your testimonials. </p>
        <div class="actions">
            <button><a href="https://strongplugins.com/plugins/strong-testimonials-review-markup" target="_blank">Learn more</a></button>
        </div>
    </div>

    <div class="sidebar-block sidebar-news addon multiple-forms">
        <h2>Multiple Forms Add-on</h2>
        <p>Need more forms for different products or services? No problem. Create unlimited forms.</p>
        <div class="actions">
            <button><a href="https://strongplugins.com/plugins/strong-testimonials-multiple-forms" target="_blank">Learn more</a></button>
        </div>
    </div>

    <div class="sidebar-block sidebar-news addon properties">
        <h2>Properties Add-on</h2>
        <p>Want to rename 'testimonials' to 'reviews'? Want to change which features are available in the post editor? Gain maximum control.</p>
        <div class="actions">
            <button><a href="https://strongplugins.com/plugins/strong-testimonials-properties" target="_blank">Learn more</a></button>
        </div>
    </div>

</div>
