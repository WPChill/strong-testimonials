<?php /* translators: On the Views admin screen. */ ?>
<div class="template-description">
    <p>
        <?php
        if ( isset( $template['config']['description'] ) && $template['config']['description'] ) {
            echo $template['config']['description'];
        }
        else {
            _e( 'no description', 'strong-testimonials' );
        }
        ?>
    </p>
    <div class="options">
        <div>
            <?php if ( ! isset( $template['config']['options'] ) || ! is_array( $template['config']['options'] ) ) : ?>
				<span><?php esc_html_e( 'No options', 'strong-testimonials' ); ?></span>
            <?php else : ?>
                <?php foreach ( $template['config']['options'] as $option ) : ?>
                    <div style="margin-bottom: 10px;">
                    <?php
                    $name = sprintf( 'view[data][template_settings][%s][%s]', $key, $option->name );
                    $id   = $key . '-' . $option->name;
                    switch ( $option->type ) {
                        case 'select':
                            // Get default if not set
                            if ( ! isset( $view['template_settings'][ $key ][ $option->name ] ) ) {
                                $view['template_settings'][ $key ][ $option->name ] = $option->default;
                            }

                            if ( $option->label ) {
                                printf( '<label for="%s">%s</label>', $id, $option->label );
                            }

                            printf( '<select id="%s" name="%s">', $id, $name );

                            foreach ( $option->values as $value ) {
                                $selected = selected( $value->value, $view['template_settings'][ $key ][ $option->name ], false );
                                printf( '<option value="%s" %s>%s</option>', $value->value, $selected, $value->description );
                            }

                            echo '</select>';
                            break;

                        case 'radio':
                            if ( ! isset( $view['template_settings'][ $key ][ $option->name ] ) ) {
                                $view['template_settings'][ $key ][ $option->name ] = $option->default;
                            }

                            foreach ( $option->values as $value ) {
                                $checked = checked( $value->value, $view['template_settings'][ $key ][ $option->name ], false );
                                printf( '<input type="radio" id="%s" name="%s" value="%s" %s>', $id, $name, $value->value, $checked );
                                printf( '<label for="%s">%s</label>', $id, $value->description );
                            }
							break;

						case 'colorpicker':
							if ( $option->label ) {
								printf( '<label for="%s">%s</label>', $id, $option->label );
							}

							$value = isset( $view['template_settings'][ $key ][ $option->name ] ) ? $view['template_settings'][ $key ][ $option->name ] : $option->default;

							printf( '<input type="text" class="wp-color-picker-field" data-alpha="true" id="%s" name="%s" value="%s">', $id, $name, $value );
							break;

						default:
							do_action( 'wpmtst_views_render_template_option_' . $option->type, $view, $key, $option );
							break;
                    }
                    ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>

	<?php do_action('wpmtst_views_after_template_options', $view, $template, $key ); ?>

</div>
