<hr />

<div class="feature-section three-col">
    <div class="col">
        <h3><?php _e( 'Support', 'strong-testimonials' ); ?></h3>
<?php
$links = array();

$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
	esc_url( 'http://wordpress.org/support/plugin/strong-testimonials' ),
	__( 'Use the public support forum', 'strong-testimonials' ) );

$links[] = __( 'or', 'strong-testimonials' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://support.strongplugins.com' ),
		__( 'submit a private support ticket', 'strong-testimonials' ) );

$links[] = __( 'or', 'strong-testimonials' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://strongplugins.com/contact/' ),
		__( 'contact the developer', 'strong-testimonials' ) );
?>
<ul>
	<?php foreach ( $links as $link ) : ?>
		<li><?php echo $link; ?></li>
	<?php endforeach; ?>
</ul>
</div>

<div class="col">
	<h3><?php _e( 'Tutorials', 'strong-testimonials' ); ?></h3>
	<?php
	$links = array();

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://support.strongplugins.com/article/troubleshooting-strong-testimonials/' ),
		__( 'Troubleshooting', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://support.strongplugins.com/article/youtube-twitter-instagram-strong-testimonials/' ),
		__( 'How to add YouTube or Twitter', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://support.strongplugins.com/article/custom-css-strong-testimonials/' ),
		__( 'Using custom CSS', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://support.strongplugins.com/article/enable-comments-strong-testimonials/' ),
		__( 'How to enable comments', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://support.strongplugins.com/article/complete-example-customizing-form/' ),
		__( 'How to customize the form', 'strong-testimonials' ) );
	?>
	<ul>
		<?php foreach ( $links as $link ) : ?>
			<li><?php echo $link; ?></li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="col">
	<h3><?php _e( 'Demos', 'strong-testimonials' ); ?></h3>
	<?php
	$links = array();

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://strongdemos.com/strong-testimonials/display-examples/' ),
		__( 'Display examples', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://strongdemos.com/strong-testimonials/slideshow-examples/' ),
		__( 'Slideshow examples', 'strong-testimonials' ) );

	$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
		esc_url( 'https://strongdemos.com/strong-testimonials/form-examples/' ),
		__( 'Form examples', 'strong-testimonials' ) );
	?>
	<ul>
		<?php foreach ( $links as $link ) : ?>
			<li><?php echo $link; ?></li>
		<?php endforeach; ?>
	</ul>
</div>
</div>
