<?php
/**
 * Prerender
 */
?>
<tr valign="top">
	<th scope="row">
		<?php _e( 'Prerender', 'strong-testimonials' ); ?>
	</th>
	<td>
		<fieldset data-radio-group="prerender">
			<?php
			/*
			 * Current (default)
			 */
			?>
			<div class="row">
				<div>
					<label for="prerender-current">
						<input type="radio" id="prerender-current" name="wpmtst_compat_options[prerender]"
						       value="current" <?php checked( $options['prerender'], 'current' ); ?>/>
						<?php _e( 'Current page', 'strong-testimonials' ); ?> <em><?php _e( '(default)', 'strong-testimonials' ); ?></em>
					</label>
				</div>
				<div>
					<span class="about"><?php _e( 'about this option', 'strong-testimonials' ); ?></span>
				</div>
			</div>

			<?php
			/*
			 * All
			 */
			?>
			<div class="row">
				<div>
					<label for="prerender-all">
						<input type="radio" id="prerender-all" name="wpmtst_compat_options[prerender]"
						       value="all" <?php checked( $options['prerender'], 'all' ); ?>/>
						<?php _e( 'All views', 'strong-testimonials' ); ?>
					</label>
				</div>
				<div>
					<span class="about"><?php _e( 'about this option', 'strong-testimonials' ); ?></span>
				</div>
			</div>

			<?php
			/*
			 * None
			 */
			?>
      <div class="row">
				<div>
					<label for="prerender-none">
						<input type="radio" id="prerender-none" name="wpmtst_compat_options[prerender]"
						       value="none" <?php checked( $options['prerender'], 'none' ); ?>/>
						<?php _e( 'None', 'strong-testimonials' ); ?>
					</label>
				</div>
				<div>
					<span class="about"><?php _e( 'about this option', 'strong-testimonials' ); ?></span>
				</div>
			</div>
		</fieldset>
	</td>
</tr>

