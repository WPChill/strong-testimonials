/*
	wpmtst-admin.js
	Strong Testimonials > admin screens
*/
jQuery(document).ready(function($) {
	
	// widget events
	$('#widgets-right').click(function(e) {
		// Listen to widget container because deeper handlers are lost after Ajax update.
	
		if ( "wpmtst-mode-setting" == e.target.className ) {
		
			// get selected tab
			var tab = e.target.getAttribute("value");
			// find parent div
			var $p = $(e.target).closest(".wpmtst-mode");
			
			// highlight current tab
			$(e.target).closest("ul").find("li").each( function( i, el ) {
				var tabvalue = $(this).find('input').val();
				if ( tabvalue == tab ) {
					$(this).addClass("radio-current");
					$p.find(".wpmtst-mode-" + tabvalue).show();
				} else {
					$(this).removeClass("radio-current");
					$p.find(".wpmtst-mode-" + tabvalue).hide();
				}
			});
			
		}
		
		// Switches and related settings.
		if ( e.target.getAttribute("id") ) { // not all elements have id's
		
			var eId = e.target.getAttribute("id"); // like "widget-wpmtst-widget-2-cycle-all"
			
			var ePos1 = eId.indexOf("cycle-all");
			var ePos2 = eId.indexOf("char-switch");
			
			if ( ePos1 > 0 ) {
			
				// Disable "number to show" if "show all" is checked.
				var eBase = eId.substr(0,ePos1); // like "widget-wpmtst-widget-2-"
				var eValue = eBase + 'cycle-limit';
				if ( e.target.checked ) {
					document.getElementById(eValue).setAttribute("readonly", "readonly");
				} else {
					document.getElementById(eValue).removeAttribute("readonly");
				}
				
			} else if ( ePos2 > 0 ) {
			
				// Disable character limit input if not checked.
				var eBase = eId.substr(0,ePos2); // like "widget-wpmtst-widget-2-"
				var eValue = eBase + 'char-limit';
				if ( e.target.checked ) {
					document.getElementById(eValue).removeAttribute("readonly");
				} else {
					document.getElementById(eValue).setAttribute("readonly", "readonly");
				}
			}
			
		}
		
	});
	
	
	// enabling "admin notify" focuses "admin email" input
	$("#wpmtst-options-admin-notify").change(function(e){
		if ($(e.target).is(":checked")) {
			$("#wpmtst-options-admin-email").focus();
		}
	});
	
});
