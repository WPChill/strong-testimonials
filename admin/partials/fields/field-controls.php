<?php
/**
 * Controls
 */
$is_core = ( isset( $field['core'] ) && $field['core'] );
?>
<div class="controls">
	<?php if ( $adding || ! $is_core ) : ?>
		<span><a href="#" class="delete-field"><?php esc_html_e( 'Delete', 'strong-testimonials' ); ?></a></span>
	<?php endif; ?>
	<span class="close-field"><a href="#"><?php echo esc_html_x( 'Close', 'verb', 'strong-testimonials' ); ?></a></span>
</div>
