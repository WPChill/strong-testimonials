<?php
/**
 * Access
 *
 * @since 2.40.4
 */
global $wp_roles;
$options = get_option( 'wpmtst_access_options' );
?>
<h2><?php _e( 'Approve Testimonials', 'strong-testimonials' ); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">

    <?php foreach($wp_roles->roles as $key => $role): ?>
    <tr valign="top">
        <td>
            <fieldset>
                <label>
                    <input type="checkbox" name="wpmtst_access_options[<?php echo 'approve_testimonials_' . $key ?>]" <?php checked( $options['approve_testimonials_' . $key] ); ?>>
                    <?php echo $role['name']; ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<hr/>
<h2><?php _e( 'Manage Settings', 'strong-testimonials' ); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">

    <?php foreach($wp_roles->roles as $key => $role): ?>
    <tr valign="top">
        <td>
            <fieldset>
                <label>
                    <input type="checkbox" name="wpmtst_access_options[<?php echo 'manage_settings_' . $key ?>]" <?php checked( $options['manage_settings_' . $key] ); ?>>
                    <?php echo $role['name']; ?>
                </label>
            </fieldset>
        </td>
    </tr>
    <?php endforeach; ?>

</table>
