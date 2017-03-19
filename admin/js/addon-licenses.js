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
		parent.find(".response").html("").removeClass("activation-error");

		$.get(ajaxurl, {
			'action': 'wpmtst_activate_license',
			'plugin': parent.data("addon"),
			'security': strongAddonAdmin.ajax_nonce
		},
		function (response) {
			parent.find(".indicator").removeClass("doing-ajax");
			if ( 0 == response ) {
				response = { failure: true, data: strongAddonAdmin.errorMessage };
			}
			if ( response.success ) {
				parent.find(".addon-inactive").hide();
				parent.find(".addon-active").showInlineBlock();
			} else {
				parent.find(".addon-active").hide();
				parent.find(".addon-inactive").showInlineBlock();
				parent.find(".response").html( response.data ).addClass("activation-error");
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
				if ( 0 == response ) {
					response = { failure: true, data: strongAddonAdmin.errorMessage };
				}
				if ( response.success ) {
					parent.find(".addon-active").hide();
					parent.find(".addon-inactive").showInlineBlock();
				} else {
					parent.find(".addon-inactive").hide();
					parent.find(".addon-active").showInlineBlock();
					parent.find(".response").html( response.data ).addClass("activation-error");
				}
			});

	});

})(jQuery);
