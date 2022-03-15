<?php
/**
 * Field Label
 */
?>
<tr class="field-label-row">
    <th><?php echo esc_html_x( 'Label', 'noun', 'strong-testimonials' ); ?></th>
    <td>
        <input class="field-label" type="text" name="fields[<?php echo esc_attr( $key ); ?>][label]" value="<?php echo esc_attr( $field['label'] ); ?>">
        <label>
            <input type="checkbox" name="fields[<?php echo esc_attr( $key ); ?>][show_label]" <?php checked( $field['show_label'], true ); ?>>
            <span class="help inline"><?php esc_html_e( 'Show this label on the form.', 'strong-testimonials' ); ?></span>
        </label>
    </td>
</tr>
