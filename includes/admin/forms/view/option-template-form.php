<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-form-template">
		<?php _e( 'Template', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<select id="view-form-template" name="view[data][form-template]" size="" style="height: auto;" autocomplete="off">
		<option value="" <?php selected( '', $view['template'] ); ?>>
			<?php _e( 'default', 'strong-testimonials' ); ?>
		</option>
		<optgroup label="<?php _e( 'In the theme', 'strong-testimonials' ); ?>">
			<?php if ( ! $theme_form_templates ) : ?>
				<option disabled="disabled">none</option>
			<?php else : ?>
				<?php foreach ( $theme_form_templates as $name => $file ) : ?>
					<?php $file = str_replace( array( '.php', 'testimonials-' ), array( '', '' ), $file ); ?>
					<option value="<?php echo $file; ?>"<?php selected( $file, $view['template'] ); ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
		</optgroup>
		<optgroup label="<?php _e( 'In the plugin', 'strong-testimonials' ); ?>">
			<?php foreach ( $plugin_form_templates as $name => $file ) : ?>
				<option value="<?php echo $file; ?>"<?php selected( $file, $view['template'] ); ?>><?php echo $name; ?></option>
			<?php endforeach; ?>
		</optgroup>
	</select>
</td>
