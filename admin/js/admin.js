/**
 *	Strong Testimonials admin
 */

// Function to get the Max value in Array
Array.max = function (array) {
  return Math.max.apply(Math, array);
};

jQuery(document).ready(function ($) {

  // Convert "A String" to "a_string"
  function convertLabel(label) {
    return label.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
  }

  // Remove invalid characters
  function removeSpaces(word) {
    //return word.replace(/\s+/g, "_");
    return word.replace(/[^\w\s(?!\-)]/gi, '')
  }

  $.fn.showInlineBlock = function () {
    return this.css('display', 'inline-block');
  };

  /**
   * ========================================
   * General events
   * ========================================
   */

  // Add protocol if missing
  // Thanks http://stackoverflow.com/a/36429927/51600
  $("input[type=url]").change(function () {
    if (this.value.length && !/^https*:\/\//.test(this.value)) {
      this.value = "http://" + this.value;
    }
  });

  $("ul.ui-tabs-nav li a").click(function () {
    $(this).blur();
  });

  $(".focus-next-field").change(function (e) {
    if ($(e.target).is(":checked")) {
      $(e.target).parent().next().find("input").focus().select();
    }
  });

  // toggle screenshots
  $("#toggle-screen-options").add("#screenshot-screen-options").click(function (e) {
    $(this).blur();
    $("#screenshot-screen-options").slideToggle();
  });

  // toggle screenshots
  $("#toggle-help").click(function (e) {
    $(this).toggleClass("closed open").blur();
    $("#help-section").slideToggle();
  });

  /**
   * ========================================
   * Admin notification email events
   * ========================================
   */

  var $notifyAdmin = $("#wpmtst-options-admin-notify");
  var $notifyFields = $("#admin-notify-fields");

  if ($notifyAdmin.is(":checked")) {
    $notifyFields.slideDown();
  }

  $notifyAdmin.change(function (e) {
    if ($(this).is(":checked")) {
      $notifyFields.slideDown();
      $(this).blur();
    }
    else {
      $notifyFields.slideUp();
    }
  });

  $("#add-recipient").click(function (e) {
    var $this = $(this);
    var key = $this.closest("tr").siblings().length - 1;
    var data = {
      'action': 'wpmtst_add_recipient',
      'key': key,
    };
    $.get(ajaxurl, data, function (response) {
      $this.closest("tr").before(response).prev("tr").find(".name-email").first().focus();
    });
  });

  $notifyFields.on('click', ".delete-recipient", function (e) {
    $(this).closest("tr").remove();
  });

  /**
   * ========================================
   * Form Settings
   * ========================================
   */

  /**
   * Restore all default messages
   */
  $("#restore-default-messages").click(function (e) {
    var data = {
      'action': 'wpmtst_restore_default_messages'
    };

    $.get(ajaxurl, data, function (response) {

      var object = JSON.parse(response);

      for (var key in object) {

        if (object.hasOwnProperty(key)) {

          var targetId = key.replace(/-/g, '_');
          var el = $("[id='" + targetId + "']");
          el.val(object[key]["text"]);

          if ('submission_success' == targetId) {
            var editor = tinyMCE.activeEditor;
            if (editor && editor instanceof tinymce.Editor && !editor.hidden) {
              tinyMCE.activeEditor.setContent(object[key]["text"]);
            }
          }

        }

      }

    });
  });

  /**
   * Restore a single default message
   */
  $(".restore-default-message").click(function (e) {
    var targetId = $(e.target).data("targetId");
    var data = {
      'action': 'wpmtst_restore_default_message',
      'field': targetId
    };

    $.get(ajaxurl, data, function (response) {

      var object = JSON.parse(response);

      $("[id='" + targetId + "']").val(object["text"]);

      if ('submission_success' == targetId) {
        var editor = tinyMCE.activeEditor;
        if (editor && editor instanceof tinymce.Editor && !editor.hidden) {
          tinyMCE.activeEditor.setContent(object["text"]);
        }
      }
    });

  });

  /**
   * ========================================
   * View List Table
   * ========================================
   */

  $('table.wpm-testimonial_page_testimonial-views th.manage-column').on('click',function(e){
    var columnName = $(this).attr('id');
    // get the opposite class
    var columnOrder = $(this).hasClass('asc') ? 'desc' : $(this).hasClass('desc') ? 'asc' : '';
    var data = {
      'action': 'wpmtst_save_view_list_order',
      'name': columnName,
      'order': columnOrder
    };
     $.get(ajaxurl, data, function (response) {});
  });

});
