/**
 * Submission form validation
 *
 * @package Strong_Testimonials
 * @since 1.15.0
 */

jQuery(document).ready(function($) {

	if( typeof( form_ajax_object ) !== 'undefined' && form_ajax_object.ajaxSubmit == "1" ) {

		var formOptions = {
			url: form_ajax_object.ajaxUrl,
			data: {
				action: 'wpmtst_form2'
			},
			success: showResponse
		}

		// attach handler to form's submit event
		$("#wpmtst-submission-form").validate({

			submitHandler: function(form) {
				$(form).ajaxSubmit( formOptions );
			}

		});

	}
	else {
		$("#wpmtst-submission-form").validate();
	}

	function showResponse( responseText, statusText, xhr, $form) {
		$("#wpmtst-form").html(responseText);
	}

});

