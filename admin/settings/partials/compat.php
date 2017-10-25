<?php
/**
 * Compatibility settings
 *
 * @since 2.28.0
 */

$options = get_option( 'wpmtst_compat_options' );
?>
<h2><?php _e( 'Prerender' ); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">
    <tr valign="top">
        <th scope="row">
			<?php _e( 'Prerender', 'strong-testimonials' ); ?>
        </th>
        <td>
            <fieldset>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[prerender]" value="current"
					        <?php checked( $options['prerender'], 'current' ); ?> />
				        <?php _e( 'Current page', 'strong-testimonials' ); ?> <?php _e( '(default)', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[prerender]" value="all"
					        <?php checked( $options['prerender'], 'all' ); ?> />
				        <?php _e( 'All views', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[prerender]" value="none"
					        <?php checked( $options['prerender'], 'none' ); ?> />
				        <?php _e( 'None', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
            </fieldset>
        </td>
    </tr>
</table>

<hr />
<h2><?php _e( 'Themes' ); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">
    <tr valign="top">
        <th scope="row">
			<?php _e( 'Ajax Page Loading', 'strong-testimonials' ); ?>
        </th>
        <td>
            <div class="tab-option-header">
                <p><?php _e( 'about page loading', 'strong-testimonials' ); ?></p>
                <p><?php printf( __( '<a href="%s" target="_blank">article</a>', 'strong-testimonials' ), esc_url( '' ) ); ?></p>
            </div>
            <fieldset>
				<?php /* (blank) | universal | nodes_added | event | script */ ?>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[ajax][method]" value=""
							<?php checked( $options['ajax']['method'], '' ); ?> />
						<?php _e( 'None', 'strong-testimonials' ); ?> <?php _e( '(default)', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[ajax][method]" value="universal"
							<?php checked( $options['ajax']['method'], 'universal' ); ?> />
						<?php _e( 'Universal', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[ajax][method]" value="nodes_added"
							<?php checked( $options['ajax']['method'], 'nodes_added' ); ?> />
						<?php _e( 'Nodes Added', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[ajax][method]" value="event"
							<?php checked( $options['ajax']['method'], 'event' ); ?> />
						<?php _e( 'Custom Event', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
                <div class="has-radio">
                    <label>
                        <input type="radio" name="wpmtst_compat_options[ajax][method]" value="script"
							<?php checked( $options['ajax']['method'], 'script' ); ?> />
						<?php _e( 'Specific Script', 'strong-testimonials' ); ?>
                    </label>
                    <p class="description"><?php _e( 'about this option', 'strong-testimonials' ); ?></p>
                </div>
            </fieldset>
        </td>
    </tr>
</table>
