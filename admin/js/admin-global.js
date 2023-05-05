/**
 * Strong Testimonials admin
 *
 * @namespace wpmtst_admin
 */

jQuery(document).ready(function ($) {
	/**
	 * ----------------------------------------
	 * Persistent admin notices.
	 * Dismissible from any page.
	 * ----------------------------------------
	 */
	$('.wpmtst.notice.is-dismissible').on('click', '.notice-dismiss', function (event) {
		event.preventDefault()
		var $this = $(this)
		if ('undefined' === $this.parent().attr('data-key')) {
			return
		}
		$.post(ajaxurl, {
			action: 'wpmtst_dismiss_notice',
			key   : $this.parent().attr('data-key'),
			nonce : wpmtst_admin.nonce
		})
	});

	$('input[name="wpmtst_options[disable_rewrite]"]').on('click', function () {

		if ($(this).is(':checked')) {
			$('tr[data-setting="single_testimonial_slug"]').hide();
		} else {
			$('tr[data-setting="single_testimonial_slug"]').show();
		}
	});

	jQuery(document).on('click', '#st-master-license-btn', (event) => {
		event.preventDefault();
		const target     = jQuery(event.target),
			  action     = target.data('action'),
			  nextAction = ('activate' === action) ? 'deactivate' : 'activate',
			  nextText   = ('activate' === action) ? wpmtst_admin.deactivate : wpmtst_admin.activate,
			  nonce      = target.parent().find('input[type="hidden"]').val(),
			  license    = jQuery('input#strong_testimonials_license_key').val(),
			  email      = jQuery('input#strong_testimonials_email').val(),
			  label      = target.parents('.wpmtst-master-license').find('.strong-testimonials-license-label'),
			  data       = {
				  action      : 'wpmtst_license_action',
				  nonce       : nonce,
				  license     : license,
				  email       : email,
				  click_action: action
			  },
			  buttonText = ('deactivate' === action) ? wpmtst_admin.deactivating : wpmtst_admin.activating;
		target.text(buttonText);
		target.addClass('wpmtst-disabled button-disabled');

		if ( '' === license ) {
			label.html(wpmtst_admin.enter_license);
			target.text(wpmtst_admin.activate);
			return;
		}

		jQuery.post(ajaxurl, data, (response) => {
			if (response.success) {
				label.html(response.data.message);
				target.data('action', nextAction);
				target.html(nextText);
				// Refresh window after 1.5 seconds.
				setTimeout(() => { window.location.reload(); }, 1500);
			} else {
				if ('undefined' !== typeof response.data) {
					label.html(response.data.message);
				} else {
					label.html(wpmtst_admin.something_wrong);
				}
				// Refresh window after 3.5 seconds.
				setTimeout(() => { window.location.reload(); }, 3500);
			}
		});
	});

	jQuery(document).on('click', '#st-forgot-license', (event) => {
		event.preventDefault();

		const target     = jQuery(event.target),
			  nonce      = target.data('nonce'),
			  email      = target.parent().find('input[type="email"]').val(),
			  label      = target.parents('.wpmtst-master-license').find('.strong-testimonials-license-label'),
			  buttonText = target.text(),
			  actionText = wpmtst_admin.retrieving_data;

		target.text(actionText);

		if (!email || '' === email) {
			label.html(wpmtst_admin.enter_email);
			return;
		}

		const data = {
			action: 'wpmtst_forgot_license',
			nonce : nonce,
			email : email
		};

		jQuery.post(ajaxurl, data, (response) => {
			target.text(buttonText);
			if (response.success) {
				label.html(response.data.message);
			} else {
				if ('undefined' !== typeof response.data) {
					label.html(response.data.message);
				} else {
					label.html(wpmtst_admin.something_wrong);
				}
			}
		});
	});
});
