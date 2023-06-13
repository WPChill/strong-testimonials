<?php
/**
 * Field Header
 */
$field_link = $field['label'] ? $field['label'] : ucwords( $field['name'] );
?>
<div class="custom-field-header">
<span class="link" title="<?php esc_html_e( 'click to open or close', 'strong-testimonials' ); ?>">
	<a class="field" href="#"><?php echo esc_html( $field_link ); ?></a>
	<span class="handle" title="<?php esc_html_e( 'drag and drop to reorder', 'strong-testimonials' ); ?>"></span>
	<span class="toggle"></span>
</span>
</div>
