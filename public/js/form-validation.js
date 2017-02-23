/**
 * Submission form validation
 *
 * @package Strong_Testimonials
 */

(function($) {

  // Add protocol if missing
  // Thanks http://stackoverflow.com/a/36429927/51600
  $("input[type=url]").change(function() {
    if ( this.value.length && !/^https*:\/\//.test(this.value) ) {
      this.value = "http://" + this.value;
    }
  });


	// Validate upon normal or Ajax submission
	if (typeof strongForm !== 'undefined') {

    if (strongForm.displaySuccessMessage) {

      strongScrollOnSuccess();

    } else {

      if (strongForm.hasOwnProperty("ajaxUrl")) {

        var formOptions = {
          url: strongForm.ajaxUrl,
          data: {
            action: 'wpmtst_form2'
          },
          success: strongShowResponse
        }

        // attach handler to form's submit event
        $("#wpmtst-submission-form").validate({
          showErrors: strongShowErrors,
          submitHandler: function (form) {
            $(form).ajaxSubmit(formOptions);
          }
        });

      } else {

        $("#wpmtst-submission-form").validate({
          showErrors: strongShowErrors
        });

      }

    }

  }


  /**
   * Custom error handler
   *
   * Thanks http://stackoverflow.com/a/30652843/51600
   *
   * @param errorMap
   * @param errorList
   */
	function strongShowErrors( errorMap, errorList ) {

		if (typeof strongForm === 'undefined') {
		  return;
		}

    if (strongForm.scrollTopError == "1" ) {

			if (typeof errorList[0] != "undefined") {
				var fieldOffset, scrollTop;
				fieldOffset = $(errorList[0].element).closest(".form-field").offset();
				scrollTop = fieldOffset.top - strongForm.scrollTopErrorOffset;
				$('html, body').animate({scrollTop: scrollTop}, 800);
			}

			this.defaultShowErrors();

		}

	}


  /**
   * Display message/errors upon Ajax submission
   *
   * @param response
   */
	function strongShowResponse(response) {
    var obj = JSON.parse(response);

    if (obj.success) {

      $("#wpmtst-form").html(obj.message);
      strongScrollOnSuccess();

    } else {

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


  /**
   * Scroll to success message
   */
  function strongScrollOnSuccess() {

    if (typeof strongForm === 'undefined') {
      return;
    }

    if (strongForm.scrollTopSuccess == "1") {

      var containerOffset, scrollTop;

      containerOffset = $(".testimonial-success").offset();

      if (containerOffset) {
        scrollTop = containerOffset.top - strongForm.scrollTopSuccessOffset;

        // is WordPress admin bar showing?
        if ($("#wpadminbar").length) {
          scrollTop -= 32;
        }

        $("html, body").animate({scrollTop: scrollTop}, 800);
      }

    }

  }

})(jQuery);
