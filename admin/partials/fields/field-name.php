<?php
/**
 * Field Name
 *
 * Disabled inputs are not posted so store the field name in a hidden input.
 */
?>
<tr class="field-name-row">
	<th><?php echo esc_html_x( 'Name', 'noun', 'strong-testimonials' ); ?></th>
	<td>
		<?php
		// Field names for certain types are read-only.
		if ( $field['name_mutable'] ) :
			?>
			<input class="field-name" type="text" name="fields[<?php echo esc_attr( $key ); ?>][name]" value="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>">
			<span class="help field-name-help"><?php esc_html_e( 'Use only lowercase letters, numbers, and underscores.', 'strong-testimonials' ); ?></span>
			<span class="help field-name-help important"><?php esc_html_e( 'Cannot be "name" or "date".', 'strong-testimonials' ); ?></span>
		<?php else : ?>
			<input class="field-name" type="text" value="<?php echo esc_attr( $field['name'] ); ?>" disabled="disabled">
			<input type="hidden" name="fields[<?php echo esc_attr( $key ); ?>][name]" value="<?php echo esc_attr( $field['name'] ); ?>">
		<?php endif ?>
	</td>
</tr>
