<hr />

<div class="feature-section three-col">
    <div class="col">
        <h3><?php _e( 'Support', 'strong-testimonials' ); ?></h3>
<?php
$links = array();

$links[] = sprintf( '<a href="%s" target="_blank">%s</a>',
	esc_url( 'http://wordpress.org/support/plugin/strong-testimonials' ),
	__( 'Use the community support forum', 'strong-testimonials' ) );

?>
<ul>
	<?php foreach ( $links as $link ) : ?>
		<li><?php echo $link; ?></li>
	<?php endforeach; ?>
</ul>
</div>

</div>
