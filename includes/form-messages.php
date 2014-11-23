<?php
/**
 * Settings > Messages tab
 *
 * @package Strong_Testimonials
 * @since 1.12.1
 */
 
?>
<table class="form-table compact" cellpadding="0" cellspacing="0">

<tr valign="top">
	<th scope="row">On The Form</th>
	<td></td>
</tr>

<?php
foreach ( $messages as $key => $message ) {
	?>
	<tr>
		<td>
			<?php echo $messages[$key]['description']; ?>
			<input type="hidden" name="wpmtst_messages[<?php echo $key; ?>][description]" value="<?php esc_attr_e( $messages[$key]['description'] ); ?>" />
		</td>
		<td>
			<input type="text" id="<?php echo $key; ?>" name="wpmtst_messages[<?php echo $key; ?>][text]" size="" value="<?php echo esc_attr( $messages[$key]['text'] ); ?>" required />
		</td>
		<td class="actions">
			<input type="button" class="button button-small secondary restore-default-message" value="Restore default" />
		</td>
	</tr>
	<?php
}
?>

</table>

<p>
	<?php //submit_button( __( 'Undo Changes', 'strong-testimonials' ), 'secondary', 'reset', false ); ?>
	<input type="button" value="Restore Defaults" class="button" id="restore-default-messages" name="restore-default-messages">
</p>
