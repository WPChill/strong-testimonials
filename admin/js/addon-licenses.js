/**
 * Add-on License Activation
 */
(function ($) {

  $.fn.showInlineBlock = function () {
    return this.css('display', 'inline-block');
  };

  $("td.status").on("click", function () {
    $(this).children(".ajax-done").hide();
    $(this).children(".indicator").addClass("doing-ajax");
  });

  /**
   * Activate license
   */
  $(".activator").on("click", function () {

    var parent = $(this).closest("td");
    var status = parent.parent().find("input[name$='[license][status]']");
    parent.find(".response").html("").removeClass("activation-error");

    $.get(ajaxurl, {
        'action': 'wpmtst_activate_license',
        'plugin': parent.data("addon"),
        'security': strongAddonAdmin.ajax_nonce
      },
      function (response) {
        parent.find(".indicator").removeClass("doing-ajax");
        if (0 == response) {
          response = {failure: true, data: strongAddonAdmin.errorMessage};
        }
        if (response.success) {
          parent.find(".addon-inactive").hide();
          parent.find(".addon-active").showInlineBlock();
          status.val(response.data);
        } else {
          parent.find(".addon-active").hide();
          parent.find(".addon-inactive").showInlineBlock();
          parent.find(".response").html(response.data).addClass("activation-error");
          status.val('');
        }
      });

  });


  /**
   * Deactivate license
   */
  $(".deactivator").on("click", function () {

    var parent = $(this).closest("td");
    parent.find(".response").html("").removeClass("activation-error");

    $.get(ajaxurl, {
        'action': 'wpmtst_deactivate_license',
        'plugin': parent.data("addon"),
        'security': strongAddonAdmin.ajax_nonce
      },
      function (response) {
        parent.find(".indicator").removeClass("doing-ajax");
        if (0 == response) {
          response = {failure: true, data: strongAddonAdmin.errorMessage};
        }
        if (response.success) {
          parent.find(".addon-active").hide();
          parent.find(".addon-inactive").showInlineBlock();
        } else {
          parent.find(".addon-inactive").hide();
          parent.find(".addon-active").showInlineBlock();
          parent.find(".response").html(response.data).addClass("activation-error");
        }
      });

  });

  /**
   * Prevent copy/inspect license key
   */

  $(document).on('contextmenu dragstart', function () {
    return false;
  });

  /**
   * Monitor which keys are being pressed
   */
  var st_protection_keys = {
    'alt': false,
    'shift': false,
    'meta': false,
  };

  $(document).on('keydown', function (e) {

    // Alt Key Pressed
    if (e.altKey) {
      st_protection_keys.alt = true;
    }

    // Shift Key Pressed
    if (e.shiftKey) {
      st_protection_keys.shift = true;
    }

    // Meta Key Pressed (e.g. Mac Cmd)
    if (e.metaKey) {
      st_protection_keys.meta = true;
    }

    if (e.ctrlKey && '85' == e.keyCode) {
      st_protection_keys.ctrl = true;
    }


  });
  $(document).on('keyup', function (e) {

    // Alt Key Released
    if (!e.altKey) {
      st_protection_keys.alt = false;
    }

    // Shift Key Released
    if (e.shiftKey) {
      st_protection_keys.shift = false;
    }

    // Meta Key Released (e.g. Mac Cmd)
    if (!e.metaKey) {
      st_protection_keys.meta = false;
    }

    if (!e.ctrlKey) {
      st_protection_keys.ctrl = false;
    }

  });

  /**
   * Prevent automatic download when Alt + left click
   */
  jQuery(document).on('click', '#strong_testimonials_license_key', function (e) {
    if (st_protection_keys.alt || st_protection_keys.shift || st_protection_keys.meta || st_protection_keys.ctrl) {
      // User is trying to download - stop!
      e.preventDefault();
      return false;
    }
  });

  jQuery(document).on('keydown click',function(e){
    if (st_protection_keys.ctrl) {
      // User is trying to view source
      e.preventDefault();
      return false;
    }
  });

})(jQuery);


