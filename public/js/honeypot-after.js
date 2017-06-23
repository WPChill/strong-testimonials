(function ($) {
  var forms = '#wpmtst-submission-form'
  $(forms).submit(function () {
    $('<input>').attr('type', 'hidden')
      .attr('name', 'wpmtst_after')
      .attr('value', '1')
      .appendTo(forms)
    return true
  })
})(jQuery)
