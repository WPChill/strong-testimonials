<?php
/**
 * Template Name: Simple Form
 * Description: A simple form template.
 */
?>
<div class="strong-view strong-form <?php wpmtst_container_class(); ?>"<?php wpmtst_container_data(); ?>>
        <?php $form_options = get_option( 'wpmtst_form_options' ); ?>
	<?php do_action( 'wpmtst_before_form' ); ?>

	<div class="wpmtst-form wpmtst-form-id-<?php echo esc_attr( WPMST()->atts( 'form_id' ) );?>">

        <div class="strong-form-inner">
            <?php if (isset($form_options['members_only']) && $form_options['members_only'] == true && isset($form_options['members_only_message']) && !is_user_logged_in()): ?>
                <span class="error"><?php echo esc_attr( $form_options['members_only_message'] ); ?></span>
                <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" alt="<?php esc_attr_e( 'Login', 'strong-testimonials' ); ?>">
                    <?php esc_html_e( 'Login', 'strong-testimonials' ); ?>
                </a>
            <?php else: ?>
	        <?php wpmtst_field_required_notice(); ?>

                <form <?php wpmtst_form_info(); ?>>

                    <?php wpmtst_form_setup(); ?>

                    <?php do_action( 'wpmtst_form_before_fields' ); ?>

                    <?php wpmtst_all_form_fields(); ?>

                    <?php do_action( 'wpmtst_form_after_fields' ); ?>

                    <?php wpmtst_form_submit_button(); ?>

                </form>
            <?php endif; ?>
        </div>

	</div>

	<?php do_action( 'wpmtst_after_form' ); ?>

</div>
