/**
 * Submission form validation
 *
 * @package Strong_Testimonials
 */

(function($) {

	// Validate upon normal or AJAX submission
	//noinspection JSUnresolvedVariable
	if (typeof( form_ajax_object ) !== 'undefined' && form_ajax_object.ajaxSubmit == "1") {

		//noinspection JSUnresolvedVariable
		var formOptions = {
			url: form_ajax_object.ajaxUrl,
			data: {
				action: 'wpmtst_form2'
			},
			success: showResponse
		}

		// attach handler to form's submit event
		$("#wpmtst-submission-form").validate({
			showErrors: showErrors,
			submitHandler: function (form) {
				$(form).ajaxSubmit(formOptions);
			}
		});

	}
	else {
		$("#wpmtst-submission-form").validate({
			showErrors: showErrors
		});
	}

	// Thanks http://stackoverflow.com/a/30652843/51600
	function showErrors( errorMap, errorList ) {
		if ( typeof( formError ) !== 'undefined' && formError.scrollTop == "1" ) {
			if (typeof errorList[0] != "undefined") {
				var fieldOffset, scrollTop;
				fieldOffset = $(errorList[0].element).closest(".form-field").offset();
				scrollTop = fieldOffset.top - formError.offset;
				$('html, body').animate({scrollTop: scrollTop}, 800);

			}
			this.defaultShowErrors();
		}
	}

	function showResponse(response) {
		var obj = JSON.parse( response );

		if (obj.success) {
			$("#wpmtst-form").html(obj.message);
		}
		else {
			for (var key in obj.errors) {
				if (obj.errors.hasOwnProperty(key)) {
					$("div.wpmtst-" + key)
						.find('span.error')
							.remove()
							.end()
						.append('<span class="error">' + obj.errors[key] + '</span>');
				}
			}
		}
	}

})( jQuery );
