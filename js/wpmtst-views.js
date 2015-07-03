/**
 * Strong Testimonials - Views
 */

jQuery(document).ready(function($) {
	'use strict';

	// Function to get the Max value in Array
	Array.max = function( array ){
		return Math.max.apply( Math, array );
	};

	// Convert "A String" to "a_string"
	function convertLabel(label) {
		return label.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
	}

	// UI

	// Background color picker
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: false,
		// a callback to fire whenever the color changes to a valid color
		//change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		//clear: function() {},
		// hide the color picker controls on load
		//hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
	$('.wp-color-picker-field').wpColorPicker(myOptions);
	
	// Toggle screenshot
	$("#toggle-screen-options").add("#screenshot-screen-options").click(function(e) {
		$("#screenshot-screen-options").slideToggle();
		e.preventDefault();
	}).blur();

	$("#view-content").change(function(){
		$("#screenshot-screen-options").slideUp();
	});
	
	// Restore defaults
	$("#restore-defaults").click(function(){
		return confirm("Restore the default settings?");
	});

	/**
	 * Categories
	 */
	var catAllOption = $("#view_category_all");
	var catListOption = $("#view_category_list");

	function setCategoryAllCheckbox() {
		catAllOption.prop("checked", true).attr("disabled","disabled");
		// check all the other boxes
		catListOption.find("input:checkbox").prop("checked", true);
	}

	catListOption.change(function(){

		// checked group
		var $checked = $(this).find("input:checkbox:checked");

		// unchecked group
		var $unchecked = $(this).find("input:checkbox:not(:checked)");

		if ($checked.length > 0) {  // if any checked

			if ($unchecked.length == 0) {
			  // if all checked, check the "All" box and disable it
				catAllOption.prop("checked", true).attr("disabled","disabled");
			} else {
				// some checked, uncheck the "all" box and enable it
				catAllOption.prop("checked", false).removeAttr("disabled");
			}

		} else {  // none checked

			// check the "All" box and enable it
			catAllOption.prop("checked", true).attr("disabled","disabled");

			// check all the other boxes
			catListOption.find("input:checkbox").prop("checked", true);

		}

	});

	updateViewCategoryAll();
	catAllOption.change( updateViewCategoryAll );
	
	function updateViewCategoryAll() {
		if (catAllOption.is(":checked")) {
			// check all the other boxes
			catListOption.find("input:checkbox").prop("checked", true);
			// disable the "All" box
			catAllOption.attr("disabled","disabled");
		}
	}

	/**
	 * -----------------
	 * Dependent options
	 * -----------------
	 */

	/**
	 * Plugin: Show/Hide parts based on current Mode
	 */
	$.fn.updateScreen = function(mode, speed) {
		speed = speed || 400;
		if (!mode)
			return;

		$(".then_" + mode).fadeIn(speed);
		$(".then_not_" + mode).fadeOut(speed);

		/**
		 * Special handling
		 */
		switch (mode) {
			case 'form':
				// deselect categories if switching to Form
				//$('#view_category_list_form input[type="checkbox"]').prop("checked", false);
				break;
			case 'slideshow':
				// force all categories
				setCategoryAllCheckbox();
				break;
			case 'display':
				// update single/multiple selector ONLY
				$.fn.selectPerOption($("#view-single_or_multiple"));
				break;
			default:
		}
	}

	/**
	 * Plugin: Toggle dependent options for checkboxes.
	 *
	 * Show/hide other option groups when checkbox is "on".
	 */
	$.fn.toggleOption = function(el, speed) {
		speed = speed || 400;
		var option = $(el).attr("id").split("-").pop();
		var checked = $(el).prop("checked");
		var deps = ".then_" + option;
		if(checked) {
			$(deps).fadeIn(speed);
		}
		else {
			$(deps).fadeOut(speed);
		}
	}

	/**
	 * Plugin: Toggle dependent options for selects.
	 *
	 * Show/hide other option groups when a *specific* option is selected.
	 */
	$.fn.selectOption = function(el, speed) {
		speed = speed || 400;
		var currentValue = $(el).val();
		var tripValue = $(el).find(".trip").val();
		var option = $(el).attr("id").split("-").pop();
		var deps = ".then_" + option;
		if(currentValue == tripValue) {
			$(deps).fadeIn(speed);
		}
		else {
			$(deps).fadeOut(speed);
		}
	}

	/**
	 * Plugin: Toggle dependent options for selects.
	 *
	 * Show/hide other option groups when any *non-empty (initial)* option is selected.
	 */
	$.fn.selectAnyOption = function(el, speed) {
		speed = speed || 400;
		var currentValue = $(el).val();
		var option = $(el).attr("id").split("-").pop();
		var deps = ".then_" + option + ".then_" + currentValue;
		var indeps = ".then_not_" + option + ".then_" + currentValue;
		if(currentValue) {
			$(deps).fadeIn(speed);
			$(indeps).fadeOut(speed);
		}
		else {
			$(deps).fadeOut(speed);
			$(indeps).fadeIn(speed);
		}
	}

	/**
	 * Plugin: Toggle dependent options for checkboxes.
	 *
	 * Show/hide other option groups when checkbox is "on".
	 *
	 * @since 1.20.0
	 */
	$.fn.selectPerOption = function(el, speed) {
		speed = speed || 400;
		var fast = 100;
		var option = $(el).attr("id").split("-").pop();
		var currentValue = $(el).val();
		var deps       = ".then_" + currentValue;
		var depsFast   = deps + ".fast";
		var indeps     = ".then_not_" + currentValue;
		var indepsFast = indeps + ".fast";
		if (currentValue) {
			$(depsFast).fadeIn(fast);
			$(deps).not(".fast").fadeIn(speed);
			$(indepsFast).fadeOut(fast);
			$(indeps).not(".fast").fadeOut(speed);
		}
		else {
			$(indepsFast).fadeIn(fast);
			$(indeps).not(".fast").fadeIn(speed);
			$(depsFast).fadeOut(fast);
			$(deps).not(".fast").fadeOut(speed);
		}
	}

	/**
	 * Initial state
	 */
	var $mode = $("#view-mode");
	var currentMode = $mode.find("input:checked");
	currentMode.closest("label").addClass("checked");
	$.fn.updateScreen(currentMode.val());

	/**
	 * Mode listener
	 */
	//$("input[name='view[data][mode]']").change(function() {
	$mode.find("input").change(function() {
		currentMode = $(this).val();
		$mode.find("input").not(":checked").closest("label").removeClass("checked");
		$mode.find("input:checked").closest("label").addClass("checked");
		$.fn.updateScreen(currentMode);
	});

	/**
	 * Initial state & Change listeners
	 */
	function initialize() {
		$(".if.toggle").each(function(index,el) {
			$.fn.toggleOption(this);
			$(this).change(function() {
				$.fn.toggleOption(this);
			});
		});

		$(".if.select").each(function(index,el) {
			$.fn.selectOption(this);
			$(this).change(function() {
				$.fn.selectOption(this);
			});
		});

		$(".if.selectany").each(function(index,el) {
			$.fn.selectAnyOption(this);
			$(this).change(function() {
				$.fn.selectAnyOption(this);
			});
		});

		$(".if.selectper").each(function(index,el) {
			$.fn.selectPerOption(this);
			$(this).change(function() {
				$.fn.selectPerOption(this);
			});
		});

		$(".field-name select").each(function() {
			var $el = $(this);
			var fieldValue = $el.val();
			var elParent = $el.closest("tr");
			var key_id = elParent.attr("id");
			var key = key_id.substr( key_id.lastIndexOf("-")+1 );
			var typeSelect = elParent.find("td.field-type select");
			if( fieldValue == 'date' ) {
				$(typeSelect).prop("disabled", true);
				$(typeSelect).parent().append('<input type="hidden" class="save-type" name="view[data][client_section][' + key + '][type]" value="date">');
			} else {
				$(typeSelect).prop("disabled", false);
				$(typeSelect).parent().find("input.save-type").remove();
			}
		});

	}
	initialize();

	/**
	 * -------------
	 * Client fields
	 * -------------
	 */

	/**
	 * Make client fields sortable
	 */

	// First, set width on header cells to prevent collapse
	// when dragging a row without column 3.
	$("table.fields th").each(function(index){
		$(this).width($(this).outerWidth());
	});

	var customFieldList = $("#custom-field-list2");
	customFieldList.find("tbody").sortable({
		placeholder: "sortable-placeholder",
		// forcePlaceholderSize: true,
		handle: ".handle",
		cursor: "move",
		helper: function(e, tr) {
			var $originals = tr.children();
			var $helper = tr.clone();
			$helper.children().each(function(index) {
				// Set helper cell sizes to match the original sizes
				$(this).width($originals.eq(index).width());
			});
			return $helper;
		},
		start: function(e, ui){
			ui.placeholder.height(ui.item.height());
		}
	});
	//}).disableSelection(); // <-- this breaks Firefox

	/**
	 * Add client field
	 */
	$("#add-field").click(function(e) {
		var keys = $("#custom-field-list2 tbody tr").map(function() {
			var key_id = $(this).attr("id");
			return key_id.substr( key_id.lastIndexOf("-")+1 );
		}).get();
		var nextKey = Array.max(keys)+1;
		var data = {
			'action' : 'wpmtst_view_add_field',
			'key'    : nextKey,
		};
		$.get( ajaxurl, data, function( response ) {
			// append to list
			$("#custom-field-list2").append(response);
		});
	});

	/**
	 * Field type change listener
	 */
	customFieldList.on("change", ".field-type select", function() {
		var $el = $(this);
		var fieldType = $el.val();
		var key_id = $el.closest("tr").attr("id");
		var key = key_id.substr( key_id.lastIndexOf("-")+1 );

		switch (fieldType) {
			case 'link':
				// if changing to [link], add link fields
				var data = {
					'action' : 'wpmtst_view_add_field_link',
					'key'    : key,
				};
				$.get( ajaxurl, data, function( response ) {
					// insert into placeholder div
					$el.closest(".field2").find(".field-meta").html(response);
				});
				break;

			case 'date':
				// if changing to [date], add date fields
				var data = {
					'action' : 'wpmtst_view_add_field_date',
					'key'    : key,
				};
				$.get( ajaxurl, data, function( response ) {
					// insert into placeholder div
					$el.closest(".field2").find(".field-meta").html(response);
				});
				break;

			case 'text':
				// if changing to [text], remove meta fields
				$el.closest(".field2").find(".field-meta").empty();
				break;

			default:
		}
	});

	/**
	 * Hide type selector if date field.
	 */
	customFieldList.on("change", ".field-name select", function() {
		var $el = $(this);
		var fieldValue = $el.val();
		var key_id = $el.closest("tr").attr("id");
		var key = key_id.substr( key_id.lastIndexOf("-")+1 );
		var typeSelect = $el.closest("tr").find("td.field-type select");
		if( fieldValue == 'date' ) {
			$(typeSelect).val("date").prop("disabled", true);

			// add format field
			var data = {
				'action': 'wpmtst_view_add_field_date',
				'key': key,
			};
			$.get( ajaxurl, data, function( response ) {
				// Insert into placeholder div. Add hidden field because we are
				// disabling the <select> so its value will not be submitted.
				$el.closest(".field2").find(".field-meta").html(response);
				$el.parent().append('<input type="hidden" class="save-type" name="view[data][client_section][' + key + '][type]" value="date">');
			});
		} else {
			$(typeSelect).val("text").prop("disabled",false);
			// remove meta field
			$el.closest(".field2").find(".field-meta").empty();
			$el.parent().find("input.save-type").remove();
		}
	});

	/**
	 * Delete a client field
	 */
	customFieldList.on("click", ".delete-field", function(){
		var thisField = $(this).closest("tr");
		var thisLabel = thisField.find(".field-name option:selected").html();
		var yesno = confirm("Remove this field?");
		if( yesno ) {
			thisField.fadeOut(function(){$(this).remove()});
		}
	});

});
