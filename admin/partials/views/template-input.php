<div>
	<input type="radio" id="template-<?php esc_attr( $key ); ?>" name="view[data][<?php esc_attr( $current_mode ); ?>]" value="<?php esc_attr( $key ); ?>" <?php checked( $key, $view['template'] ); ?>>
	<label for="template-<?php esc_attr( $key ); ?>">
		<?php echo wp_kses_post( $template['config']['name'] ); ?>
	</label>
</div>
