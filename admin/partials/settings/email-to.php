<div class="subsubsection">
	<table>
		<thead>
			<tr>
				<th colspan="2"><?php _e( "To:", 'strong-testimonials' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<label for="wpmtst-options-sender-name">
						<span><?php _e( "Name", 'strong-testimonials' ); ?></span>
					</label>
				</td>
				<td><?php _e( "Email", 'strong-testimonials' ); ?></td>
			</tr>
			<?php
            if ( isset( $form_options['recipients'] ) && $form_options['recipients'] ) {
	            foreach ( $form_options['recipients'] as $key => $recipient ) {
		            include 'recipient.php';
	            }
            }
			?>
			<tr>
				<td colspan="2"><input type="button" class="button" id="add-recipient" value="Add recipient"></td>
			</tr>
		</tbody>
	</table>
</div>
