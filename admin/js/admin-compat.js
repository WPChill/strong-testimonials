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

    $('[data-group]').each(function (index, el) {
      var group = $(this).data('group')
      var $sub = $('[data-sub=\'' + group + '\']')
      if ($(this).is(':checked')) {
        $sub.fadeIn()
      } else {
        $sub.fadeOut(quick)
      }
    })

    matchPrerenderSetting()
    highlightRadioLabel()
  }

  // Trigger "All views" if necessary
  function matchPrerenderSetting () {
    if ($('#method-none').is(':not(:checked)')) {
      saveCurrentSettings()
      $('#prerender-all').prop('checked', true)
      $('#prerender-current').prop('disabled', true)
      currentSettings['prerender'].forced = true
    } else {
      if (currentSettings['prerender'].forced) {
        $('#prerender-' + currentSettings['prerender'].value).prop('checked', true)
        $('#prerender-current').prop('disabled', false)
        currentSettings['prerender'].forced = false
      }
    }
  }

  // UI
  function highlightRadioLabel () {
    $('input:radio:checked').closest('label').addClass('current')
    $('input:radio:not(:checked)').closest('label').removeClass('current')
  }

  // Listen
  $('.form-table').on('change', function (e) {
    updateDisplay()
  })

  // Start
  saveCurrentSettings()
  updateDisplay()

})(jQuery)
