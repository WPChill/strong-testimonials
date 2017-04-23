// Pointers
jQuery(document).ready(function ($) {
  wpmtst_open_pointer(0);

  function wpmtst_open_pointer(i) {
    pointer = wpmtstPointer.pointers[i];
    options = $.extend(pointer.options, {
      close: function () {
        $.post(ajaxurl, {
          pointer: pointer.pointer_id,
          action: 'dismiss-wp-pointer'
        });
      }
    });

    $(pointer.target).pointer(options).pointer('open');
  }
});