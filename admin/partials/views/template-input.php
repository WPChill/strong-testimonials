<div>
	<input type="radio" id="template-<?php echo esc_attr( $key ); ?>" name="view[data][<?php echo esc_attr( $current_mode ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $key, $view['template'] ); ?>>
	<label for="template-<?php echo esc_attr( $key ); ?>">
		<?php echo wp_kses_post( $template['config']['name'] ); ?>
	</label>
</div>
