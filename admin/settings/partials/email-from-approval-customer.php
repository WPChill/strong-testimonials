<div class="email-option-first">
    <div class="email-option-row">

        <div class="email-option-desc">
			<?php _e( "From", 'strong-testimonials' ); ?>
        </div>

        <div class="email-option-inputs">

            <div class="email-option header">

                <div class="email-option-part">
                    <div class="email-option-label">
                        <?php _e( "Name", 'strong-testimonials' ); ?>
                    </div>
                </div>

                <div class="email-option-part">
                    <div class="email-option-label">
			<?php _e( "Email", 'strong-testimonials' ); ?>
                    </div>
                </div>

            </div>

            <div class="email-option body recipient">

                <div class="email-option-part">
                    <div class="email-option-fieldset">
                        <div class="controls"></div>
                        <div class="fields">
                            <input type="text"
                                   id="wpmtst-options-sender-name"
                                   name="wpmtst_form_options[sender_name_for_customer_approval]"
                                   value="<?php echo esc_attr( $form_options['sender_name_for_customer_approval'] ); ?>"
                                   placeholder="<?php _e( '(optional)', 'strong-testimonials' ); ?>">
                        </div>
                    </div>
                </div>

                <div class="email-option-part">

                    <div class="email-option-fieldset">

                        <div class="controls">
                            <input type="radio"
                                   id="wpmtst-options-sender-site-email-1"
                                   name="wpmtst_form_options[sender_site_customer_approval_email]" <?php checked( $form_options['sender_site_customer_approval_email'], 1 ); ?>
                                   value="1">
                        </div>
                        <div class="fields">
                            <?php _e( 'admin:', 'strong-testimonials' ); ?>
                            &nbsp;<?php echo get_bloginfo( 'admin_email' ); ?>
                        </div>

                    </div>

                    <div class="email-option-fieldset">

                        <div class="controls">
                            <input class="focus-next-field" type="radio"
                                   id="wpmtst-options-sender_site_customer_approval_email-0"
                                   name="wpmtst_form_options[sender_site_customer_approval_email]" <?php checked( $form_options['sender_site_customer_approval_email'], 0 ); ?>
                                   value="0">
                        </div>
                        <div class="fields">
                            <input type="email"
                                   id="wpmtst-options-sender-site-customer-approval-email"
                                   name="wpmtst_form_options[sender_approval_email]"
                                   value="<?php echo esc_attr( $form_options['sender_approval_email'] ); ?>"
                                   placeholder="<?php _e( 'email address', 'strong-testimonials' ); ?>">
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
