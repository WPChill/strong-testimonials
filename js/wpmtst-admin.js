/**
 *	Strong Testimonials > admin screens
 */

jQuery(document).ready(function($) {

	// Function to get the Max value in Array
	Array.max = function( array ){
		return Math.max.apply( Math, array );
	};

	// Convert "A String" to "a_string"
	function convertLabel(label) {
		return label.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
	}

	// Remove invalid characters
	function removeSpaces(word) {
		//return word.replace(/\s+/g, "_");
		return word.replace(/[^\w\s(?!\-)]/gi, '')
	}

	// --------------
	// General events
	// --------------

	$("ul.ui-tabs-nav li a").click(function(){
		$(this).blur();
	});

	$(".focus-next-field").change(function(e) {
		if( $(e.target).is(":checked") ) {
			$(e.target).parent().next().find("input").focus().select();
		}
	});

	// toggle screenshots
	$("#toggle-screen-options").add("#screenshot-screen-options").click(function(e) {
		$(this).blur();
		$("#screenshot-screen-options").slideToggle();
	});

	// toggle screenshots
	$("#toggle-help").click(function(e) {
		$(this).toggleClass("closed open").blur();
		$("#help-section").slideToggle();
	});

	// -------------------------
	// Admin notification email events
	// -------------------------

	var $notifyAdmin = $("#wpmtst-options-admin-notify");
	var $notifyFields = $("#admin-notify-fields");

	if( $notifyAdmin.is(":checked") ) {
		$notifyFields.slideDown();
	}

	$notifyAdmin.change(function(e) {
		if( $(this).is(":checked") ) {
			$notifyFields.slideDown();
			$(this).blur();
		}
		else {
			$notifyFields.slideUp();
		}
	});

	$("#add-recipient").click(function(e){
		var $this = $(this);
		var key = $this.closest("tr").siblings().length-1;
		var data = {
			'action': 'wpmtst_add_recipient',
			'key': key,
		};
		$.get( ajaxurl, data, function( response ) {
			$this.closest("tr").before(response).prev("tr").find(".name-email").first().focus();
		});
	});

	$notifyFields.on('click',".delete-recipient",function(e){
		$(this).closest("tr").remove();
	});

	// -------------
	// Form Settings
	// -------------

	$("#restore-default-messages").click(function(e){
		var data = {
			'action': 'wpmtst_restore_default_messages'
		};
		$.get( ajaxurl, data, function( response ) {
			var object = JSON.parse( response );
			for (var key in object) {
				if (object.hasOwnProperty(key)) {
					$("input[id='" + key + "']").val( object[key]["text"] );
				}
			}
		});
	});

	$(".restore-default-message").click(function(e){
		var input = $(e.target).closest("tr").find("input[type='text']").attr("id");
		var data = {
			'action': 'wpmtst_restore_default_message',
			'field': input
		};
		$.get( ajaxurl, data, function( response ) {
			var object = JSON.parse( response );
			$("input[id='" + input + "']").val( object["text"] );
		});
	});

});
