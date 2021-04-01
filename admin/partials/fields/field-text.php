<?php
/**
 * Field text for checkbox and radio.
 */
?>
<tr class="field-label-row">
	<th><?php echo esc_html_x( 'Text', 'noun', 'strong-testimonials' ); ?></th>
	<td>
		<input type="text" class="field-label"
			name="fields[<?php echo esc_attr( $key ); ?>][text]"
			value="<?php echo esc_attr( $field['text'] ); ?>"
			placeholder="<?php esc_html_e( 'next to the checkbox', 'strong-testimonials' ); ?>">
	</td>
</tr>
