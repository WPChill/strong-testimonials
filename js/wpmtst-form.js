/**
 * Submission form validation
 *
 * @package Strong_Testimonials
 */

(function($) {

	// Add protocol if missing
	// Thanks http://stackoverflow.com/a/36429927/51600
	$("input[type=url]").change(function() {
		if (!/^https*:\/\//.test(this.value)) {
			this.value = "http://" + this.value;
		}
	});

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

			submitHandler: function (form) {
				$(form).ajaxSubmit(formOptions);
			}

		});

	}
	else {
		$("#wpmtst-submission-form").validate();
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
