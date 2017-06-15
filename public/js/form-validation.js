/**
 * Submission form validation
 *
 * @package Strong_Testimonials
 */

// Change events

(function($) {

  // Add protocol if missing
  // Thanks http://stackoverflow.com/a/36429927/51600
  $("input[type=url]").change(function() {
    if ( this.value.length && !/^https*:\/\//.test(this.value) ) {
      this.value = "http://" + this.value;
    }
  });

  // Click star --> Focus next field if valid
  $(".strong-rating").on( "change", function(){
    if ( $(this).valid() ) {
      //$(this).closest(".form-field").next().find("[tabindex=0]").focus();
    }
  });

})(jQuery);


// Checkboxes
//
// Thanks https://stackoverflow.com/a/27891665/51600
//    and http://www.456bereastreet.com/archive/201302/making_elements_keyboard_focusable_and_clickable/
(function ($) {
  var checkboxes = document.getElementsByClassName('checkbox-label');

  function handleCheckboxEvent(e) {
    // If spacebar fired the event, trigger a click.
    if (e.keyCode === 32) {
      $(this).click();
    }
    // Maintain focus too; it's losing it somewhere.
    // NOPE. This breaks Firefox.
    // $(this).focus();
  }

  for ( var i = 0; i < checkboxes.length; i++ ) {
    checkboxes[i].addEventListener("click", handleCheckboxEvent, true);
    checkboxes[i].addEventListener("keyup", handleCheckboxEvent, true);
  }
})(jQuery);


// Star ratings

(function ($) {
  var ratings = document.getElementsByClassName('strong-rating');

  function handleRadioEvent(e) {
    // If key 0-5 fired the event, trigger click on that star (including hidden zero).
    if ( e.keyCode >= 48 && e.keyCode <= 53 ) {
      var key = e.keyCode - 48;
      $(this).find("input[type='radio'][value=" + key + "]").click();
    }
  }

  for ( var i = 0; i < ratings.length; i++ ) {
    ratings[i].addEventListener("click", handleRadioEvent, true);
    ratings[i].addEventListener("keyup", handleRadioEvent, true);
  }
})(jQuery);


// Validate the form

(function($) {

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

        /**
         * Only use elements that can legitimately have a 'name' attribute:
         * <button>, <form>, <fieldset>, <iframe>, <input>, <keygen>, <object>,
         * <output>, <select>, <textarea>, <map>, <meta>, <param>
         *
         * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes
         *
         * jQuery Validate v1.16.0
         * As of 6/10/2017
         */
        // TODO Only if field is required!
        $.validator.addMethod("ratingRequired", function (value, element) {
          // console.log('validator',value,element);
          return $(element).find("input:checked").val() > 0;
        },'Please enter a rating');


         $("#wpmtst-submission-form").validate({

          submitHandler: function(form) {
            if ( !$("[name='strongrating']").valid() ) {
              return false;
            }
            form.submit();
          },

          // Add custom validation rule to star-rating pseudo elements
          rules: {
            strongrating: {
              // required: true,
              ratingRequired: true
            }
          },

          showErrors: strongShowErrors,

          errorPlacement: function(error, element) {
            error.appendTo( element.closest("div.form-field") );
          },

          highlight: function(element, errorClass, validClass) {

            if ( element.type === 'checkbox' ) {
              $(element).closest(".field-wrap").addClass(errorClass).removeClass(validClass);
            } else if ( element.name === 'strongrating' ) {
              $(element).closest(".field-wrap").addClass(errorClass).removeClass(validClass);
            } else {
              $(element).addClass(errorClass).removeClass(validClass);
            }
          },

          unhighlight: function(element, errorClass, validClass) {
            if ( element.type === 'checkbox' ) {
              $(element).closest(".field-wrap").removeClass(errorClass).addClass(validClass);
            } else if ( element.name === "strongrating" ) {
              $(element).closest(".field-wrap").removeClass(errorClass).addClass(validClass);
            } else {
              $(element).removeClass(errorClass).addClass(validClass);
            }
          }

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

		// console.log(errorMap);

    if (strongForm.scrollTopError === "1" ) {

      if (typeof errorList[0] !== "undefined") {
        var fieldOffset, scrollTop;
        fieldOffset = $(errorList[0].element).closest(".form-field").offset();
        scrollTop = fieldOffset.top - strongForm.scrollTopErrorOffset;
        $('html, body').animate({scrollTop: scrollTop}, 800);
      }

		}

		this.defaultShowErrors();

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

    if (strongForm.scrollTopSuccess === "1") {

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
