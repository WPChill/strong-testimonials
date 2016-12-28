<?php
/**
 * Field Label
 */
?>
<tr class="field-label-row">
	<th><?php _ex( 'Label', 'noun', 'strong-testimonials' ); ?></th>
	<td>
		<input type="text" class="field-label"
			   name="fields[<?php echo $key; ?>][label]"
			   value="<?php echo wpmtst_htmlspecialchars( $field['label'] ); ?>">
		<label>
			<span class="help">
				<input type="checkbox" name="fields[<?php echo $key; ?>][show_label]"
					<?php checked( $field['show_label'], true ); ?>><?php _e( 'Show this label on the form.', 'strong-testimonials' ); ?>
			</span>
		</label>
	</td>
</tr>
