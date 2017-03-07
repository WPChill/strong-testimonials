<?php
/**
 * Template Name: Default Form
 * Description: The default form template.
 */
?>
<div class="strong-view strong-form">

	<div id="wpmtst-form">

        <div class="strong-form-inner">

            <p class="required-notice">
                <span class="required symbol"></span><?php wpmtst_form_message( 'required-field' ); ?>
            </p>

            <form enctype="multipart/form-data" autocomplete="off">

                <?php wpmtst_all_form_fields( $new_fields ); ?>

                <?php wpmtst_form_submit_button( true ); ?>

            </form>

        </div>

	</div>

</div>