/**
 * Compatibility settings tab
 */

;(function ($) {

  var currentSettings = {}

  // Store current setting(s)
  function saveCurrentSettings () {
    $('[data-radio-group]').each(function (index, el) {
      var radioGroup = $(this).data('radioGroup')
      currentSettings[radioGroup] = {
        value: $(this).find(':checked').val(),
        forced: false
      }
    })
  }

  // Toggle dependent inputs
  function updateDisplay () {
    var quick = 200

    matchMethodSetting()
    highlightRadioLabel()

    $('[data-group]').each(function (index, el) {
      var group = $(this).data('group')
      var $sub = $("[data-sub='" + group + "']")
      if ($(this).is(':checked')) {
        $sub.fadeIn()
      } else {
        $sub.fadeOut(quick)
      }
    })
  }

  // Update available options
  function matchMethodSetting () {
    if ($('#prerender-current').is(':checked')) {
      saveCurrentSettings()
      $('#method-none').prop('checked', true)
      $('#method-universal').prop('disabled', true)
      $('#method-observer').prop('disabled', true)
      $('#method-event').prop('disabled', true)
      $('#method-script').prop('disabled', true)
      currentSettings['method'].forced = true
    } else {
      if (currentSettings['method'].forced) {
        $('#method-' + currentSettings['method'].value).prop('checked', true)
        $('#method-universal').prop('disabled', false)
        $('#method-observer').prop('disabled', false)
        $('#method-event').prop('disabled', false)
        $('#method-script').prop('disabled', false)
        currentSettings['method'].forced = false
      }
    }
  }

  // UI
  function highlightRadioLabel () {
    $('input:radio:checked').closest('label').addClass('current')
    $('input:radio:not(:checked)').closest('label').removeClass('current')
  }

  // Number spinner
  function initSpinner () {
    $("input[type='number']").each(function () {
      $(this).number();
    });
  }

  // Preset
  function setScenario1() {
    $('#prerender-all').click()
    $('#method-universal').click()
  }

  // Listen for change
  $('.form-table').on('change', function (e) {
    updateDisplay()
  })

  // Listen for presets
  $('#set-scenario-1').click(function(e) {
    $(this).blur()
    setScenario1()
    e.preventDefault()
  })

  // Start
  saveCurrentSettings()
  updateDisplay()
  initSpinner()

})(jQuery)
