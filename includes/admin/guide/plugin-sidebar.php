<?php
$tags = array( 'a' => array( 'href' => array(), 'target' => array(), 'class' => array() ), 'br' => array() );
?>
<div id="plugin-sidebar">

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

	$links[] = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">How to enable comments</a>', 'strong-testimonials' ), $tags
		), esc_url( 'https://www.wpmission.com/tutorials/how-to-add-comments-in-strong-testimonials/' )
	) . '<span class="new-doc">NEW</span>';

	$links[] = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">How to customize the form</a>', 'strong-testimonials' ), $tags
		), esc_url( 'https://www.wpmission.com/tutorials/how-to-customize-the-form-in-strong-testimonials/' )
	);

	$links[] = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">See the demos</a>', 'strong-testimonials' ), $tags
		), esc_url( 'http://demos.wpmission.com/strong-testimonials/' )
	);

	$links[] = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">Subscribe to the newsletter</a>', 'strong-testimonials' ), $tags
		), esc_url( 'https://www.wpmission.com/newsletter' )
	);
	?>
	<div class="has-icon icon-document">
		<p><strong><?php _e( 'More resources', 'strong-testimonials' ); ?></strong></p>
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
		<p><strong><?php _e( 'Want to help?', 'strong-testimonials' ); ?></strong></p>
		<ul>
			<li><?php echo $link1; ?></li>
			<li><?php echo $link2; ?></li>
		</ul>
	</div>

</div>
