<?php
	$tags = array( 'a' => array( 'href' => array(), 'target' => array() ) );
?>
<div id="plugin-sidebar">

	<?php
	// Need help?
	$link = sprintf(
		wp_kses(
			__( 'Use the <a href="%s" target="_blank">support forum</a> or <a href="%s" target="_blank">contact me</a>.', 'strong-testimonials' ), $tags
		), esc_url( 'http://wordpress.org/support/plugin/strong-testimonials' ), esc_url( 'https://www.wpmission.com/contact/' )
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
	$link1 = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">How to customize the form</a>', 'strong-testimonials' ), $tags
		), esc_url( 'https://www.wpmission.com/tutorial/how-to-customize-the-form-in-strong-testimonials/' )
	);

	$link2 = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">See the new demos</a>', 'strong-testimonials' ), $tags
		), esc_url( 'http://demos.wpmission.com/strong-testimonials/' )
	);

	$link3 = sprintf(
		wp_kses(
			__( '<a href="%s" target="_blank">Subscribe to the newsletter</a>', 'strong-testimonials' ), $tags
		), esc_url( 'https://www.wpmission.com/newsletter' )
	);
	?>
	<div class="has-icon icon-document">
		<strong><?php _e( 'More resources', 'strong-testimonials' ); ?></strong>
		<ul>
			<li><?php echo $link1; ?></li>
			<li><?php echo $link2; ?></li>
			<li><?php echo $link3; ?></li>
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
		<strong><?php _e( 'Want to help?', 'strong-testimonials' ); ?></strong><br>
		<ul>
			<li><?php echo $link1; ?></li>
			<li><?php echo $link2; ?></li>
		</ul>
	</div>

</div>
