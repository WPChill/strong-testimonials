<th>
	<label for="view-background">
		<?php _e( 'Background', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="radio-wrap background-section">
		<div class="table-row">

			<div class="radio-buttons table-cell">
				<ul class="radio-list">
					<li>
						<input type="radio" id="bg-none" name="view[data][background][type]" value="none" <?php checked( $view['background']['type'], '' ); ?>>
						<label for="bg-none"><?php _e( 'none', 'strong-testimonials' ); ?></label>
					</li>
					<li>
						<input type="radio" id="bg-single" name="view[data][background][type]" value="single" <?php checked( $view['background']['type'], 'single' ); ?>>
						<label for="bg-single"><?php _e( 'single color', 'strong-testimonials' ); ?></label>
					</li>
					<li>
						<input type="radio" id="bg-gradient" name="view[data][background][type]" value="gradient" <?php checked( $view['background']['type'], 'gradient' ); ?>>
						<label for="bg-gradient"><?php _e( 'gradient', 'strong-testimonials' ); ?></label>
					</li>
					<li>
						<input type="radio" id="bg-preset" name="view[data][background][type]" value="preset" <?php checked( $view['background']['type'], 'preset' ); ?>>
						<label for="bg-preset"><?php _e( 'preset', 'strong-testimonials' ); ?></label>
					</li>
				</ul>
			</div>

			<div id="view-background-info" class="radio-description table-cell">

					<div class="background-info-inner">

						<div class="background-description bg-none"></div>

						<div class="background-description bg-single">
							<div class="background-description-inner">
								<label>
									<input type="text" id="bg-color" name="view[data][background][color]" value="<?php echo $view['background']['color']; ?>" class="wp-color-picker-field">
								</label>
							</div>
						</div>

						<div class="background-description bg-gradient">
							<div class="background-description-inner">

								<div class="table">
									<div class="table-row">
										<div class="table-cell">
											<label for="bg-gradient1"><?php _e( 'From top', 'strong-testimonials' ); ?></label>
										</div>
										<div class="table-cell">
											<input type="text" id="bg-gradient1" name="view[data][background][gradient1]" value="<?php echo $view['background']['gradient1']; ?>" class="wp-color-picker-field gradient">
										</div>
									</div>
									<div class="table-row">
										<div class="table-cell">
											<label for ="bg-gradient2"><?php _e( 'To bottom', 'strong-testimonials' ); ?></label>
										</div>
										<div class="table-cell">
											<input type="text" id="bg-gradient2" name="view[data][background][gradient2]" value="<?php echo $view['background']['gradient2']; ?>" class="wp-color-picker-field gradient">
										</div>
									</div>
								</div>

							</div>
						</div>

						<div class="background-description bg-preset">
							<div class="background-description-inner">
								<label for="view-background-preset">
									<select id="view-background-preset" name="view[data][background][preset]">
										<?php
										$presets = wpmtst_get_background_presets();
										$current_preset = ( isset( $view['background']['preset'] ) && $view['background']['preset'] ) ? $view['background']['preset'] : '';
										echo '<option value="" ' . selected( $current_preset, '', false ) . '>&mdash;</option>';
										foreach ( $presets as $key => $preset ) {
											echo '<option value="' . $key . '" ' . selected( $current_preset, $key, false ) . '>' . $preset['label'] . '</option>';
										}
										?>
									</select>
								</label>
							</div>
						</div>

					</div>

				<div class="view-background-info-inner background-preview-wrap">

					<div id="font-color-switcher">
						<div class="inner radio-buttons">
							<input type="radio" id="dark-font-folor" name="view[data][background][example-font-color]" value="dark" <?php checked( $view['background']['example-font-color'], 'dark' ); ?>>
							<label for="dark-font-folor"><?php _e( 'dark text', 'strong-testimonials' ); ?></label>
							<input type="radio" id="light-font-color" name="view[data][background][example-font-color]" value="light" <?php checked( $view['background']['example-font-color'], 'light' ); ?>>
							<label for="light-font-color"><?php _e( 'light text', 'strong-testimonials' ); ?></label>
						</div>
						<div class="inner-help"><?php _e( '(for demo only)', 'strong-testimonials' ); ?></div>
					</div>

					<div id="background-preview" class="<?php echo $view['background']['example-font-color']; ?>">
						Lorem ipsum dolor sit amet, accusam complectitur an eos. No vix perpetua adolescens, vix vidisse maiorum in. No erat falli scripta qui, vis ubique scripta electram ad. Vix prompta adipisci no, ad vidisse expetendis.
					</div>

				</div>

			</div>

		</div>
	</div>

</td>
