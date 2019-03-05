<?php

$fields = array();
if ( isset( $field['select_options'] ) && $field['select_options'] != '' ) {
	$fields = json_decode( $field['select_options'], true );
}

?>

<tr class="field-secondary">
	<th><?php esc_html_e( 'Options', 'strong-testimonials' ); ?></th>
	<td>
		<div class="wpmtst-item-creation">

			<?php foreach ( $fields as $item ) : ?>

				<div class="wpmtst-item-creation__item">
					<div>
						<div class="wpmtst-item-creation__link">
							<span class="wpmtst-item-creation__description"><?php echo esc_html( $item['label'] ); ?></span>
							<div class="wpmtst-item-creation__controls">
								<span class="handle wpmtst-item-creation__icon" title="drag and drop to reorder"></span>
								<span class="delete wpmtst-item-creation__icon" title="remove"></span>
							</div>
							<div class="wpmtst-item-creation__controls">
								<span class="toggle wpmtst-item-creation__icon" title="click to open or close"></span>
							</div>
						</div>

						<div class="wpmtst-item-creation__field-properties">
							<?php foreach ( $item as $k => $value ) : ?>
								<div class="wpmtst-item-creation__field-property" data-name="<?php echo esc_attr( $k ); ?>">
									<label><?php echo esc_html( ucfirst( $k ) ); ?></label>
									<input type="text" value="<?php echo esc_attr( $value ); ?>">
								</div>
							<?php endforeach; ?>
						</div><!-- wpmtst-item-creation__field-properties -->

					</div>

				</div><!-- wpmtst-item-creation__item -->

			<?php endforeach; ?>

		</div><!-- wpmtst-item-creation -->

		<input type="button" value="Add Option" class="button-secondary">
		<input type="hidden" name="fields[<?php echo esc_attr( $key ); ?>][select_options]" value="<?php echo esc_html( wp_json_encode( $fields ) ); ?>"/>
	</td>
</tr>
