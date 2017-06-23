/**
 * Submission form validation
 *
 * @package Strong_Testimonials
 */

(function ($) {

  var strongValidation = {

    defaults: {
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
      fields: {}
    },

    settings: {},

    setOpts: function (options) {
      this.settings = $.extend({}, this.defaults, options)
    },

    /**
     * Add custom validation rule to star-rating pseudo elements.
     */
    rules: {},

    setRules: function () {
      for (var i = 0; i < this.settings.fields.length; i++) {
        if ('rating' === this.settings.fields[i].type) {
          if (1 === this.settings.fields[i].required) {
            this.rules[this.settings.fields[i].name] = {ratingRequired: true}
          }
        }
      }
    },

    /**
     * Initialize.
     */
    init: function () {

      var strongForm = {}
      if (typeof window['strongForm'] !== 'undefined') {
        strongForm = window['strongForm']
      }
      this.setOpts(strongForm)

      if (this.settings.display.successMessage) {

        this.scrollOnSuccess()

      } else {

        this.setRules()
        this.changeEvents()
        this.customValidators()
        this.validateForm()

      }

    },

    changeEvents: function () {

      // Add protocol if missing
      // Thanks http://stackoverflow.com/a/36429927/51600
      $('input[type=url]').change(function () {
        if (this.value.length && !/^https*:\/\//.test(this.value)) {
          this.value = 'http://' + this.value
        }
      })

      // Star ratings
      var ratings = document.getElementsByClassName('strong-rating')
      for (var i = 0; i < ratings.length; i++) {
        // Handle keystrokes
        ratings[i].addEventListener('click', this.handleRadioEvent, true)
        ratings[i].addEventListener('keyup', this.handleRadioEvent, true)
        // Validate on change
        ratings[i].addEventListener('change', function () { $(this).valid() }, true)
      }

    },

    handleRadioEvent: function (e) {
      // If key 0-5 fired the event, trigger click on that star (including hidden zero).
      if (e.keyCode >= 48 && e.keyCode <= 53) {
        var key = e.keyCode - 48
        $(this).find('input[type=\'radio\'][value=' + key + ']').click()
      }
    },

    customValidators: function () {
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
      $.validator.addMethod('ratingRequired', function (value, element) {
        return $(element).find('input:checked').val() > 0
      }, $.validator.messages.required)
    },

    validateForm: function () {

      /**
       * Validate the form
       */
      $('#wpmtst-submission-form').validate({

        onfocusout: false,

        focusInvalid: false,

        invalidHandler: function (form, validator) {
          // Focus first invalid input
          var errors = validator.numberOfInvalids()
          if (errors) {
            if (strongValidation.settings.scroll.onError) {
              if (typeof validator.errorList[0] !== 'undefined') {
                var firstError = $(validator.errorList[0].element)
                var fieldOffset = firstError.closest('.form-field').offset()
                var scrollTop = fieldOffset.top - strongValidation.settings.scroll.onErrorOffset
                $('html, body').animate({scrollTop: scrollTop}, 800, function () { firstError.focus() })
              }
            } else {
              validator.errorList[0].element.focus()
            }
          }
        },

        submitHandler: function (form) {
          // If Ajax
          if (strongValidation.settings.ajaxUrl !== '') {
            var formOptions = {
              url: strongValidation.settings.ajaxUrl,
              data: {
                action: 'wpmtst_form2'
              },
              success: strongValidation.showResponse
            }
            $(form).ajaxSubmit(formOptions)
          } else {
            form.submit()
          }
        },

        rules: strongValidation.rules,

        errorPlacement: function (error, element) {
          error.appendTo(element.closest('div.form-field'))
        },

        highlight: function (element, errorClass, validClass) {
          if (element.type === 'checkbox') {
            $(element).closest('.field-wrap').addClass(errorClass).removeClass(validClass)
          } else if ('rating' === $(element).data('fieldType')) {
            $(element).closest('.field-wrap').addClass(errorClass).removeClass(validClass)
          } else {
            $(element).addClass(errorClass).removeClass(validClass)
          }
        },

        unhighlight: function (element, errorClass, validClass) {
          if (element.type === 'checkbox') {
            $(element).closest('.field-wrap').removeClass(errorClass).addClass(validClass)
          } else if ('rating' === $(element).data('fieldType')) {
            $(element).closest('.field-wrap').removeClass(errorClass).addClass(validClass)
          } else {
            $(element).removeClass(errorClass).addClass(validClass)
          }
        }

      })

    },

    /**
     * Display message/errors upon Ajax submission
     *
     * @param response
     */
    showResponse: function (response) {
      var obj = JSON.parse(response)
      if (obj.success) {
        $('#wpmtst-form').html(obj.message)
        strongValidation.scrollOnSuccess()
      } else {
        for (var key in obj.errors) {
          if (obj.errors.hasOwnProperty(key)) {
            $('div.wpmtst-' + key)
              .find('span.error')
              .remove()
              .end()
              .append('<span class="error">' + obj.errors[key] + '</span>')
          }
        }
      }
    },

    /**
     * Scroll to success message
     */
    scrollOnSuccess: function () {
      if (strongValidation.settings.scroll.onSuccess) {
        var containerOffset, scrollTop
        containerOffset = $('.testimonial-success').offset()
        if (containerOffset) {
          scrollTop = containerOffset.top - strongValidation.settings.scroll.onSuccessOffset
          // is WordPress admin bar showing?
          if ($('#wpadminbar').length) {
            scrollTop -= 32
          }
          $('html, body').animate({scrollTop: scrollTop}, 800)
        }
      }
    }
  }

  strongValidation.init()

})(jQuery)
