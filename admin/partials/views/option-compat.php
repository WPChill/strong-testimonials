<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-compat">
		<?php _e( 'Compatibility mode', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="table">
		<div class="table-row">
			<div class="table-cell">
				<div class="option-wrap">
					<select id="view-compat" class="if selectper" name="view[data][compat]">
						<option value="compat_off" <?php selected( $view['compat'], false ); ?>>
							<?php _e( 'off (default)', 'strong-testimonials' ); ?>
						</option>
						<option value="compat_on" <?php selected( $view['compat'] ); ?>>
							<?php _e( 'on', 'strong-testimonials' ); ?>
						</option>
					</select>
				</div>
			</div>

			<div class="table-cell">
				<!-- Form notes -->
				<div class="then then_not_display then_form then_not_slideshow" style="display: none;">
					<p class="description tall">
						<?php _e( 'Turning this <b>on</b> may help if the form is not working, especially with page builders and popup makers. ', 'strong-testimonials' ); ?>
					</p>
					<p class="description tall">
						<?php _e( 'Required <b>on</b> if using the <a href="https://wordpress.org/plugins/popup-maker/" target="_blank">Popup Maker</a> plugin.', 'strong-testimonials' ); ?>
					</p>
				</div>
				<!-- Slideshow notes -->
				<div class="then then_not_display then_not_form then_slideshow" style="display: none;">
					<p class="description tall">
						<?php _e( 'Turning this <b>on</b> may help if the slideshow is not working, especially with page builders.', 'strong-testimonials' ); ?>
					</p>
					<p class="description tall">
						<?php _e( 'Required <b>on</b> if using the template function <code>strong_testimonials_view()</code>.', 'strong-testimonials' ); ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</td>
