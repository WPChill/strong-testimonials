<th>
	<label for="view-background">
		<?php _e( 'Font Color', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="radio-wrap font-color-section">
		<div class="table-row">

			<div class="radio-buttons table-cell">
				<ul class="radio-list">
					<li>
						<input type="radio" id="fc-none" name="view[data][font-color][type]" value="" <?php checked( $view['font-color']['type'], '' ); ?>>
						<label for="fc-none"><?php _e( 'inherit from theme', 'strong-testimonials' ); ?></label>
					</li>
					<li>
						<input type="radio" id="fc-custom" name="view[data][font-color][type]" value="custom" <?php checked( $view['font-color']['type'], 'custom' ); ?>>
						<label for="fc-custom"><?php _e( 'custom', 'strong-testimonials' ); ?></label>
					</li>
				</ul>
			</div>

			<div id="view-font-color-info" class="radio-description table-cell">

					<div class="font-color-info-inner">

						<div class="font-color-description fc-none"></div>

						<div class="font-color-description fc-custom">
							<div class="font-color-description-inner">
								<label>
									<input type="text" id="fc-color" name="view[data][font-color][color]" value="<?php echo $view['font-color']['color']; ?>" class="wp-color-picker-field">
								</label>
							</div>
						</div>

					</div>

			</div>

		</div>
	</div>

</td>
