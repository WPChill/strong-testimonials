/**
 * Strong Testimonials admin
 *
 * @namespace jQuery
 */

jQuery(document).ready(function ($) {

  /**
   * ----------------------------------------
   * Admin notification email events
   * ----------------------------------------
   */

  var $notifyAdmin = $('#wpmtst-options-admin-notify');
  var $notifyFields = $('#admin-notify-fields');

  if ($notifyAdmin.is(':checked')) {
    $notifyFields.slideDown();
  }

  $notifyAdmin.on( 'change', function (e) {
    if ($(this).is(':checked')) {
      $notifyFields.slideDown();
      $(this).trigger('blur');
    }
    else {
      $notifyFields.slideUp();
    }
  });

  $('#add-recipient').on('click', function (e) {
    var $this = $(this);
    var key = $this.parent().siblings('.recipient').length;
    var data = {
      'action': 'wpmtst_add_recipient',
      'key': key,
      'nonce': wpmtst_admin_form_script_nonce
    };
    $.post(ajaxurl, data, function (response) {
      $this.parent().before(response).prev().find('.admin_name').first().trigger('focus');
    });
  });

  $notifyFields.on('click', '.delete-recipient', function (e) {
    $(this).closest('.email-option.recipient').remove();
  });

  /**
   * ----------------------------------------
   * Admin notification email tags
   *
   * Thanks http://skfox.com/2008/11/26/jquery-example-inserting-text-with-drag-n-drop/
   * ----------------------------------------
   */

  var $tagListItems = $('#wpmtst-tags-list li');
  $tagListItems.attr('title', wpmtst_admin.templateTagTitle);

  $tagListItems.on('click', function () {
    $(this).closest('.email-container').find('#wpmtst-option-email-message').insertAtCaret($(this).text());
    return false;
  });

  $.fn.insertAtCaret = function (myValue) {
    return this.each(function () {
      var sel;
      //IE support
      if (document.selection) {
        this.trigger('focus');
        sel = document.selection.createRange();
        sel.text = myValue;
        this.trigger('focus');
      }
      //MOZILLA / NETSCAPE support
      else if (this.selectionStart || this.selectionStart === '0') {
        var startPos = this.selectionStart;
        var endPos = this.selectionEnd;
        var scrollTop = this.scrollTop;
        this.value = this.value.substring(0, startPos) + myValue + this.value.substring(endPos, this.value.length);
        this.trigger('focus');
        this.selectionStart = startPos + myValue.length;
        this.selectionEnd = startPos + myValue.length;
        this.scrollTop = scrollTop;
      } else {
        this.value += myValue;
        this.trigger('focus');
      }
    });
  };

  /**
   * Autosize textarea
   */
  var ta = document.getElementsByClassName('autosize');
  autosize(ta);

  /**
   * ----------------------------------------
   * Form Settings
   * ----------------------------------------
   */

  /**
   * Restore all default messages
   */
  $('#restore-default-messages').on('click', function (e) {
    var data = {
      'action': 'wpmtst_restore_default_messages'
    };

    $.get(ajaxurl, data, function (response) {

      var object = JSON.parse(response);

      for (var key in object) {

        if (object.hasOwnProperty(key)) {

          var targetId = key.replace(/-/g, '_');
          var el = $('[id=\'' + targetId + '\']');
          el.val(object[key]['text']);

          if ('submission_success' === targetId) {
            var editor = tinyMCE.activeEditor;
            if (editor && editor instanceof tinymce.Editor && !editor.hidden) {
              tinyMCE.activeEditor.setContent(object[key]['text']);
            }
          }

        }

      }

    });
  });

  /**
   * Restore a single default message
   */
  $('.restore-default-message').on('click', function (e) {
    var targetId = $(e.target).data('targetId');
    var data = {
      'action': 'wpmtst_restore_default_message',
      'field': targetId
    };

    $.get(ajaxurl, data, function (response) {

      var object = JSON.parse(response);

      $('[id=\'' + targetId + '\']').val(object['text']);

      if ('submission_success' === targetId) {
        var editor = tinyMCE.activeEditor;
        if (editor && editor instanceof tinymce.Editor && !editor.hidden) {
          tinyMCE.activeEditor.setContent(object['text']);
        }
      }
    });

  });

});
