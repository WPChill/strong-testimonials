<?php /* translators: On the Views admin screen. */ ?>
<th>
	<?php _e( 'Custom Fields', 'strong-testimonials' ); ?>
</th>
<td>
	<div id="client-section-table">

		<table id="custom-field-list2" class="fields" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>Name</th><th>Type</th><th>Properties</th><th>CSS Class</th><th class="controls">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( isset( $view['client_section'] ) ) {
					foreach ( $view['client_section'] as $key => $field ) {
						wpmtst_view_field_inputs( $key, $field );
					}
				}
				?>
			</tbody>
		</table>

	</div>
	<div id="add-field-bar">
		<input id="add-field" type="button" class="button-secondary" name="add-field" value="<?php _e( 'Add Field', 'strong-testimonials' ); ?>">
	</div>
</td>
