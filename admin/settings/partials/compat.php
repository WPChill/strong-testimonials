<?php
/**
 * Compatibility settings
 *
 * @since 2.28.0
 */

$options = get_option( 'wpmtst_compat_options' );
?>
<h2><?php _e( 'Themes' ); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">
    <tr valign="top">
        <th scope="row">
			<?php _e( 'Theme Page Refresh', 'strong-testimonials' ); ?>
        </th>
        <td>
            <div class="tab-option-header">
                <p><?php _e( 'about page loading', 'strong-testimonials' ); ?></p>
                <p><?php printf( __( '<a href="%s" target="_blank">article</a>', 'strong-testimonials' ), esc_url( '' ) ); ?></p>
            </div>
            <fieldset>
				<?php /* (blank) | universal | attr_changed | nodes_added | event | script */ ?>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[method]" value=""
							<?php checked( $options['method'], '' ); ?> />
						<?php _e( 'None', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[method]" value="universal"
							<?php checked( $options['method'], 'universal' ); ?> />
						<?php _e( 'Universal', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[method]" value="attr_changed"
							<?php checked( $options['method'], 'attr_changed' ); ?> />
						<?php _e( 'Attribute Change', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[method]" value="nodes_added"
							<?php checked( $options['method'], 'nodes_added' ); ?> />
						<?php _e( 'Nodes Added', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[method]" value="event"
							<?php checked( $options['method'], 'event' ); ?> />
						<?php _e( 'Custom Event', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[method]" value="script"
							<?php checked( $options['method'], 'script' ); ?> />
						<?php _e( 'Specific Script', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
            </fieldset>
        </td>
    </tr>
</table>
