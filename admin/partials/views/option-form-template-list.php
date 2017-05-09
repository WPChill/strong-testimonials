<?php /* translators: On the Views admin screen. */ ?>
<th>
	<?php _e( 'Template', 'strong-testimonials' ); ?>
</th>
<td>
	<div class="radio-wrap">
		<div class="table-row">

			<div class="radio-buttons table-cell">
				<ul class="radio-list">
					<?php
					// Indicate if template not found; for example, after switching themes or deactivating add-ons.
					if ( ! $template_found ) {
						$t  = $view['template'];
						$t2 = 'template-' . str_replace( ':', '-', $t );
						echo '<li>';
						echo '<input type="radio" id="' . $t2 . '" class="error" name="view[data][form-template]" value="' . $t . '" checked>';
						echo '<label for="' . $t2 . '">';
						_e( 'not found', 'strong-testimonials' );
						echo '</label>';
						echo '</li>';
					}

					// Pseudo optgroups
					$current_source = '';
					foreach ( $form_templates as $source => $source_templates ) {
						if ( $source != $current_source ) {
							echo '<div class="template-optgroup">' . $source . '</div>';
							$current_source = $source;
						}
						foreach ( $source_templates as $key => $template ) {
							$key2 = 'template-' . str_replace( ':', '-', $key );
							echo '<li>';
							echo '<input type="radio" id="' . $key2 . '" name="view[data][form-template]" value="' . $key . '"' . checked( $key, $view['template'], false ) . '>';
							echo '<label for="' . $key2 . '">';
							echo $template['name'];
							echo '</label>';
							echo '</li>';
						}
					}
					?>
				</ul>
			</div>

			<!-- Template Info -->
			<div id="view-form-template-info" class="radio-description table-cell">
				<?php
				if ( ! $template_found ) {
					echo '<div class="template-description template-' . $view['template'] . '">';
					echo '<p>';
					echo '<span class="dashicons dashicons-warning error"></span>&nbsp;' . __( 'not found', 'strong-testimonials' );
					// TODO Explain
					// Add template key and explain this could be due to switching themes
					// or deactivating template plugin. It's OK to leave this setting as is.
					echo '</p>';
					echo '</div>';
				}

				foreach ( $form_templates as $source => $source_templates ) {
                    foreach ( $source_templates as $key => $template ) {
                        echo '<div class="template-description template-' . str_replace( ':', '-', $key ) . '">';

                        if ( isset( $template['description'] ) && $template['description'] ) {
                            echo $template['description'];
                        } else {
                            _e( 'no description', 'strong-testimonials' );
                        }

                        if ( isset( $template['screenshot'] ) && $template['screenshot'] ) {
                            echo '<div class="template-screenshot"><img src="' . $template['screenshot'] . '" width="128" height="128"></div>';
                        }

                        echo '</div>'; // template description
                    }
				}
				?>
			</div>

			</div>
	</div>
</td>
