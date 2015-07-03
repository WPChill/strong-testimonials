<?php
/**
 * Template Name: Simple Testimonial Form
 *
 * @author Chris Dillon chris@wpmission.com
 * @package Strong_Testimonials
 * @since 1.21.0
 */
?>
<div class="strong-view strong-form simple <?php wpmtst_container_class(); ?>">

	<div id="wpmtst-form">

		<p class="required-notice">
			<span class="required symbol"></span><?php wpmtst_form_message( 'required-field' ); ?>
		</p>

		<form <?php wpmtst_form_info(); ?>>

			<?php wpmtst_form_nonce(); ?>

			<?php wpmtst_form_hidden_fields(); ?>

			<?php do_action( 'wpmtst_form_before_fields' ); ?>

			<?php wpmtst_all_form_fields(); ?>

			<?php do_action( 'wpmtst_form_after_fields' ); ?>

			<?php wpmtst_form_submit_button(); ?>

		</form>

	</div>

</div>
