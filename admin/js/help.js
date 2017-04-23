(function ($) {
  'use strict';

  $(".open-help-tab").on("click", function () {
    var tab = this.hash;
    var tabLink = $('#contextual-help-columns').find('a[href="' + tab + '"]');
    if ($("#screen-meta").is(":hidden")) {
      $("#contextual-help-link").click().promise().done(function () {
        tabLink.click();
      });
    }
    $("html, body").animate({scrollTop: 0}, 800);
    return false;
  });
})(jQuery);
