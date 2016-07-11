<?php
$tags = array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ), 'br' => array() );
?>
<div id="plugin-sidebar">

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
		<p><strong><?php _e( 'Help? Idea? Bug?', 'strong-testimonials' ); ?></strong></p>
		<ul>
			<li><?php echo $link; ?></li>
		</ul>
	</div>

	<?php
	// Resources
	$links = array();

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://www.wpmission.com/tutorials/youtube-twitter-instagram-strong-testimonials/' ),
		__( 'Adding video testimonials', 'strong-testimonials' ) )
		. '<span class="new-doc">NEW</span>';

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
			esc_url( 'https://www.wpmission.com/tutorials/how-to-add-custom-css-in-strong-testimonials/' ),
			__( 'Using custom CSS', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://www.wpmission.com/tutorials/how-to-add-comments-in-strong-testimonials/' ),
		__( 'Enabling comments', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://www.wpmission.com/tutorials/how-to-customize-the-form-in-strong-testimonials/' ),
		__( 'Customize the form', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'http://demos.wpmission.com/strong-testimonials/' ),
		__( 'See the demos', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://www.wpmission.com/newsletter' ),
		__( 'Subscribe to the newsletter', 'strong-testimonials' ) );
	?>
	<div class="has-icon icon-document">
		<p><strong><?php _e( 'Knowledge Base', 'strong-testimonials' ); ?></strong></p>
		<ul>
			<?php foreach ( $links as $link ) : ?>
			<li><?php echo $link; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<?php
	// How to help
	$link1 = sprintf(
		wp_kses(
			__( 'Post an honest <a href="%s" target="_blank">review</a> on wordpress.org.', 'strong-testimonials' ), $tags
		), esc_url( 'https://wordpress.org/support/view/plugin-reviews/strong-testimonials' ) );

	$link2 = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">Donate</a> to the Theme & Plugin Compatibility Fund.', 'strong-testimonials' ), $tags
		), esc_url( 'https://www.wpmission.com/donate' )
	);
	?>
	<div class="has-icon icon-donate">
		<p><strong><?php _e( 'Give Back', 'strong-testimonials' ); ?></strong></p>
		<ul>
			<li><?php echo $link1; ?></li>
			<li><?php echo $link2; ?></li>
		</ul>
	</div>

</div>
