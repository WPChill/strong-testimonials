/**
 * Submission form validation
 */

 function strongValidation(form) {
    
    this.form = jQuery(form).find('form');

	this.defaults = {
	  ajaxUrl: '',
	  display: {
		successMessage: false
	  },
	  scroll: {
		onError: true,
		onErrorOffset: 100,
		onSuccess: true,
		onSuccessOffset: 100
	  },
	  fields: {},
	};

	this.settings = {};
    this.rules = {};

    this.init();
    }

	strongValidation.prototype.setOpts = function (options) {
	  this.settings = jQuery.extend({}, this.defaults, options);
	};
    
	/**
	 * Add custom validation rule to star-rating pseudo elements.
	 */
     strongValidation.prototype.setRules = function () {
	  for (var i = 0; i < this.settings.fields.length; i++) {

		if ('rating' === this.settings.fields[i].type) {
		  if (1 === this.settings.fields[i].required) {
			this.rules[this.settings.fields[i].name] = {ratingRequired: true};
		  }
		}

	  }
	};

	/**
	 * Initialize.
	 */
     strongValidation.prototype.init = function () {

	  var strongForm = {};

	  if (typeof window['strongForm'] !== 'undefined') {
		strongForm = jQuery(this.form).data("config");
	  }

	  this.setOpts(strongForm);

	  if (this.settings.display.successMessage) {

		this.scrollOnSuccess();

	  } else {

		this.setRules();
		this.changeEvents();
		this.customValidators();
		this.validateForm();

	  }

	};

	strongValidation.prototype.changeEvents = function () {

	  // Trim blanks
	  jQuery('input[type="text"], input[type="url"], input[type="email"], textarea', '.wpmtst-submission-form').on('change blur', function (e) {
		e.target.value = e.target.value.trim();
	  });

	  // Add protocol if missing
	  // Thanks http://stackoverflow.com/a/36429927/51600
	  jQuery('input[type=url]').on('change', function () {
		if (this.value.length && !/^https*:\/\//.test(this.value)) {
		  this.value = 'https://' + this.value;
		}
	  });

	  // Star ratings
	  var ratings = document.getElementsByClassName('strong-rating');
	  for (var i = 0; i < ratings.length; i++) {
		// Handle keystrokes
		ratings[i].addEventListener('click', this.handleRadioEvent, true);
		ratings[i].addEventListener('keyup', this.handleRadioEvent, true);
		// Validate on change
		ratings[i].addEventListener('change', function () { jQuery(this).valid(); }, true);
	  }

	};

    /**
     * Show overlay during form submission.
     */
    strongValidation.prototype.disableForm = function (id) {
    //apply form wait buffer only for the submited form
      if(jQuery('.strong-form-wait').data("formid") == id){
            jQuery('.strong-form-wait[data-formid="'+id+'"]').show();
    }
      jQuery('#wpmtst_submit_testimonial').prop('disabled',true);
    };

    /**
     * Hide overlay after form submission.
     */
    strongValidation.prototype.enableForm = function () {
      jQuery('.strong-form-wait').hide();
      jQuery('#wpmtst_submit_testimonial').prop('disabled',false);
    };
  
	strongValidation.prototype.handleRadioEvent = function (e) {
	  // If key 0-5 fired the event, trigger click on that star (including hidden zero).
	  if (e.keyCode >= 48 && e.keyCode <= 53) {
		var key = e.keyCode - 48;
		jQuery(this).find('input[type="radio"][value=' + key + ']').trigger('click');
	  }
	};

	strongValidation.prototype.customValidators = function () {
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
	  jQuery.validator.addMethod('ratingRequired', function (value, element) {
		return jQuery(element).find('input:checked').val() > 0;
	  }, jQuery.validator.messages.required);
	};
    
	strongValidation.prototype.validateForm = function () {
        
        //remember current object for function calls later
        var theThis = this;
	  /**
	   * Validate the form
	   */
        jQuery(this.form).validate({
            
            onfocusout: false,

            focusInvalid: false,

            invalidHandler: function (form, validator) {
                
            // Focus first invalid input
            var errors = validator.numberOfInvalids();
            if (errors) {
                if (theThis.settings.scroll.onError) {
                if (typeof validator.errorList[0] !== 'undefined') {
                    var firstError = jQuery(validator.errorList[0].element);
                    var fieldOffset = firstError.closest('.form-field').offset();
                    var scrollTop = fieldOffset.top - theThis.settings.scroll.onErrorOffset;
                    jQuery('html, body').animate({scrollTop: scrollTop}, 800, function () { firstError.focus(); });
                }
                } else {
                validator.errorList[0].element.focus();
                }
            }
            },

            submitHandler: function (form) {
            var formid = jQuery(form).data("formid");
            theThis.disableForm(formid);
            // If Ajax
            if (theThis.settings.ajaxUrl !== '') {
               
                window.onbeforeunload = function() {
                return "Please wait while the form is submitted.";
                }

                var formOptions = {
                url: theThis.settings.ajaxUrl,
                data: {
                    action: 'wpmtst_form2'
                },
                success: function(success){ theThis.showResponse(success,formid); },
                };
                jQuery(form).ajaxSubmit(formOptions);

            } else {

                form.submit();
            }
            },

            /* Normalizer not working */
            // normalizer: function( value ) {
            //   return jQuery.trim( value )
            // },

            rules: this.rules,

            errorPlacement: function (error, element) {
            error.appendTo(element.closest('div.form-field'));
            },

            highlight: function (element, errorClass, validClass) {
            if (element.type === 'checkbox') {
                jQuery(element).closest('.field-wrap').addClass(errorClass).removeClass(validClass);
            } else if ('rating' === jQuery(element).data('fieldType')) {
                jQuery(element).closest('.field-wrap').addClass(errorClass).removeClass(validClass);
            } else {
                jQuery(element).addClass(errorClass).removeClass(validClass);
            }
            },

            unhighlight: function (element, errorClass, validClass) {
            if (element.type === 'checkbox') {
                jQuery(element).closest('.field-wrap').removeClass(errorClass).addClass(validClass);
            } else if ('rating' === jQuery(element).data('fieldType')) {
                jQuery(element).closest('.field-wrap').removeClass(errorClass).addClass(validClass);
            } else {
                jQuery(element).removeClass(errorClass).addClass(validClass);
            }
            }

        });
    

	};

	/**
	 * Display message/errors upon Ajax submission
	 *
	 * @param response
	 */
     strongValidation.prototype.showResponse = function (response,formid) {
	  window.onbeforeunload = null;
	  this.enableForm();
	  var obj = JSON.parse(response);
	  if (obj.success) {
		jQuery('.wpmtst-form-id-'+formid).html(obj.message);
		this.scrollOnSuccess();
	  } else {
		for (var key in obj.errors) {
		  if (obj.errors.hasOwnProperty(key)) {
			jQuery('.wpmtst-submission-form').children('.field-' + key)
			  .find('span.error')
			  .remove()
			  .end()
			  .append('<span class="error">' + obj.errors[key] + '</span>');
		  }
		}
	  }
	};

	/**
	 * Scroll to success message
	 */
     strongValidation.prototype.scrollOnSuccess = function () {
	  if (this.settings.scroll.onSuccess) {
		var containerOffset, scrollTop;
		containerOffset = jQuery('.wpmtst-testimonial-success').offset();
		if (containerOffset) {
		  scrollTop = containerOffset.top - this.settings.scroll.onSuccessOffset;
		  // is WordPress admin bar showing?
		  if (jQuery('#wpadminbar').length) {
			scrollTop -= 32;
		  }
		  jQuery('html, body').animate({scrollTop: scrollTop}, 800);
		}
	  }
	};

