/**
 * Strong Testimonials - Views
 */

// Function to get the Max value in Array
Array.max = function( array ){
	return Math.max.apply( Math, array );
};

// Convert "A String" to "a_string"
function convertLabel(label) {
	return label.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
}

/**
 * jQuery alterClass plugin
 *
 * Remove element classes with wildcard matching. Optionally add classes:
 *   $( '#foo' ).alterClass( 'foo-* bar-*', 'foobar' )
 *
 * https://gist.github.com/peteboere/1517285
 *
 * Copyright (c) 2011 Pete Boere (the-echoplex.net)
 * Free under terms of the MIT license: http://www.opensource.org/licenses/mit-license.php
 */
(function ( $ ) {

	$.fn.alterClass = function ( removals, additions ) {

		var self = this;

		if ( removals.indexOf( '*' ) === -1 ) {
			// Use native jQuery methods if there is no wildcard matching
			self.removeClass( removals );
			return !additions ? self : self.addClass( additions );
		}

		var patt = new RegExp( '\\s' +
			removals.
			replace( /\*/g, '[A-Za-z0-9-_]+' ).
			split( ' ' ).
			join( '\\s|\\s' ) +
			'\\s', 'g' );

		self.each( function ( i, it ) {
			var cn = ' ' + it.className + ' ';
			while ( patt.test( cn ) ) {
				cn = cn.replace( patt, ' ' );
			}
			it.className = $.trim( cn );
		});

		return !additions ? self : self.addClass( additions );
	};

})( jQuery );


(function ( $ ) {
	$.fn.afterToggle = function() {
		// custom handling
		var $categoryDivs = $('.view-category-list-panel');
		// Set initial width to compensate for narrowed box due to checkbox being hidden first
		// and to prevent horizontal jumpiness as filter is applied.
		if ( !$categoryDivs.hasClass("fixed") ) {
			$categoryDivs.width( $categoryDivs.outerWidth(true) ).addClass("fixed");
		}
		return this;
	}
}
( jQuery ));


jQuery(window).on('load', function () {
	jQuery(".view-layout-masonry .example-container")
		.find(".box")
			.width( jQuery(".grid-sizer").width() )
		.end()
		.masonry();
});

jQuery(document).ready(function($) {
	'use strict';

	// Masonry example
	var masonryExample = $(".view-layout-masonry .example-container");
	masonryExample.find(".box").width( $(".grid-sizer").width() ).end().masonry({
		columnWidth: '.grid-sizer',
		gutter: 10,
		itemSelector: '.box',
		percentPosition: true
	});

	// Column count selector
	var columnCount = $("#view-column-count");
	var columnCountChange = function () {
		var col = columnCount.val();
		$(".example-container").alterClass("col-*", "col-" + col);
		masonryExample.find(".box").width( $(".grid-sizer").width() ).end().masonry();
	}

	columnCountChange();
	columnCount.on("change", columnCountChange);
	$("input[name='view[data][layout]']").on("change",function(){
		if ( 'masonry' == $(this).val() ) {
			setTimeout( columnCountChange, 200);
		}
	});

	// Background color picker
	var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		//defaultColor: '#FFFFFF',
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){
			setTimeout( function() {
				updateBackgroundPreview();
			}, 250);
		},
		// a callback to fire when the input is emptied or an invalid color
		clear: function(event, ui) {
			setTimeout( function() {
				updateBackgroundPreview();
			}, 250);
		},
		// hide the color picker controls on load
		//hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
	$('.wp-color-picker-field').wpColorPicker(myOptions);

	/**
	 * Example font color toggle
	 */
	$("input[name='view[data][background][example-font-color]']").on("change", function() {
		$("#background-preview").toggleClass("light dark");
	});

	/**
	 * Restore defaults
 	 */
	$("#restore-defaults").click(function(){
		return confirm("Restore the default settings?");
	});

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
				//formTemplateDescriptions();
				break;
			case 'slideshow':
				break;
			case 'display':
				// update single/multiple selector ONLY
				$.fn.selectPerOption($("#view-single_or_multiple"));
				break;
			default:
		}
		return this;
	}

	/**
	 * Plugin: Toggle dependent options for checkboxes.
	 *
	 * Show/hide other option groups when checkbox is "on".
	 * Single value
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
		return this;
	}

	/**
	 * Plugin: Toggle dependent options for checkboxes.
	 *
	 * Show/hide other option groups when checkbox is "on".
	 * Multiple values
	 *
	 * @since 1.20.0
	 */
	$.fn.selectPerOption = function(el, speed) {
		speed = speed || 400;
		var fast = 0;
		//var option = $(el).attr("id").split("-").pop();
		var currentValue = $(el).val();
		var deps       = ".then_" + currentValue;
		var depsFast   = deps + ".fast";
		var indeps     = ".then_not_" + currentValue;
		var indepsFast = indeps + ".fast";
		if (currentValue) {

			$(depsFast).not(".then_not_" + currentMode).fadeIn(fast);
			$(deps).not(".fast, .then_not_" + currentMode).fadeIn(speed);

			$(indepsFast).fadeOut(fast);
			$(indeps).not(".fast").fadeOut(speed);
		}
		else {

			$(indepsFast).fadeIn(fast);
			$(indeps).not(".fast").fadeIn(speed);

			$(depsFast).fadeOut(fast);
			$(deps).not(".fast").fadeOut(speed);

		}
		return this;
	}

	/**
	 * Plugin: Toggle dependent options for selects.
	 *
	 * Show/hide other option groups when one and only one *specific* option is selected.
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
		return this;
	}

	/**
	 * Plugin: Toggle dependent options for selects.
	 *
	 * Show/hide other option groups when any *non-empty (initial)* option is selected.
	 * class="if selectany"
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
		return this;
	}

	/**
	 * Plugin: Toggle dependent options for checkboxes.
	 *
	 * Show/hide other option groups when checkbox is "on".
	 * Multiple values
	 * using both option and value (which is different than other functions)
	 * TODO Is this a duplicate of the checkbox version?
	 *
	 * @since 1.20.0
	 */
	$.fn.selectGroupOption = function(el, speed) {
		speed = speed || 400;
		var fast = 100;
		var option = $(el).attr("id").split("-").pop();
		var currentValue = $(el).val();
		var deps       = ".then_" + option + ".then_" + currentValue;
		var depsFast   = deps + ".fast";
		var indeps     = ".then_" + option + ".then_not_" + currentValue;
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
		return this;
	}


	/**
	 * Initial state
	 */
	var $mode = $("#view-mode");
	var currentMode = $mode.find("input:checked").val();
	$mode.find("input:checked").closest("label").addClass("checked");
	$.fn.updateScreen(currentMode);

	/**
	 * Mode listener
	 */
	$mode.find("input").on("change", function() {
		currentMode = $(this).val();
		$mode.find("input").not(":checked").closest("label").removeClass("checked");
		$mode.find("input:checked").closest("label").addClass("checked");
		$.fn.updateScreen(currentMode);

		// Force default template since we have more than one group of templates.
		$("input[type=radio][name='view[data][template]'][value='default:content']").prop("checked", true);
		templateRadios.change();
		$("input[type=radio][name='view[data][form-template]'][value='default:form']").prop("checked", true);
		formTemplateRadios.change();
		layoutRadios.change();
		backgroundRadios.change();
	});

	/**
	 * Initial state & Change listeners
	 */
	function initialize() {
		$(".if.toggle").each(function(index,el) {
			$.fn.toggleOption(this);
			$(this).on("change", function() {
				$.fn.toggleOption(this);
			});
		});

		$(".if.select").each(function(index,el) {
			$.fn.selectOption(this);
			$(this).on("change", function() {
				$.fn.selectOption(this);
			});
		});

		$(".if.selectany").each(function(index,el) {
			$.fn.selectAnyOption(this);
			$(this).on("change", function() {
				$.fn.selectAnyOption(this);
			});
		});

		// this is unhiding pagination in slideshow mode
		$(".if.selectper").each(function(index,el) {
			$.fn.selectPerOption(this);
			$(this).on("change", function() {
				$.fn.selectPerOption(this).afterToggle();
			});
		});

		$(".if.selectgroup").each(function(index,el) {
			$.fn.selectGroupOption(this);
			$(this).on("change", function() {
				$.fn.selectGroupOption(this);
			});
		});

		$(".field-name select").each(function() {
			var $el = $(this);
			var fieldValue = $el.val();
			var $elParent = $el.closest("tr");
			var key = $elParent.attr("id").split('-').slice(-1)[0];
			var typeSelect = $elParent.find("td.field-type select");
			if( fieldValue == 'post_date' ) {
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
	 * Link field text change listener
	 */
	function textChangeListener() {
		$('select[id^="view-fieldtext"]').on("change", function () {
			if ($(this).val() == 'custom') {
				var key = $(this).closest("tr").attr("id").split('-').slice(-1)[0];
				$("#view-fieldtext" + key + "-custom").focus();
			}
		});
	}

	textChangeListener();

	/**
	 * Template change listener
	 */
	var templateRadios = $("input[type=radio][name='view[data][template]']");

	function templateDescriptions() {
		var templateID = templateRadios.filter(":checked").attr("id");
		var template = templateRadios.filter(":checked").val();

		$("#view-template-info")
			.find(".template-description:visible")
			.hide()
			.end()
			.find("." + templateID)
			.show();

		// Check for forced options
		if (template) {
			$("input.forced").removeProp("disabled").removeClass("forced");
			var data = {
				'action': 'wpmtst_force_check',
				'template': template,
			};
			$.get(ajaxurl, data, function (response) {
				if (response) {
					var $el = $("#" + response);
					$el.prop("checked", true).change();
					var inputName = $el.prop("name");
					$("input[name='" + inputName + "']").prop("disabled", true).addClass("forced");
				}
			});
		}

		// Special handling
		if ('unstyled:content' == template) {
			$("input[name='view[data][background][type]']").prop("disabled",true);
			$("#font-color-switcher").hide();
		}
		else {
			$("input[name='view[data][background][type]']").prop("disabled",false);
			$("#font-color-switcher").show();
		}
	}

	templateDescriptions();

	templateRadios.on("change", templateDescriptions);

	/**
	 * Form template change listener
	 */
	var formTemplateRadios = $("input[type=radio][name='view[data][form-template]']");

	function formTemplateDescriptions() {
		var template = formTemplateRadios.filter(":checked").attr("id");
		$("#view-form-template-info")
			.find(".template-description:visible")
				.hide()
			.end()
			.find("." + template)
				.show();
	}

	formTemplateDescriptions();

	formTemplateRadios.on("change", formTemplateDescriptions);

	/**
	 * Layout change listener
	 */
	var layoutRadios = $("input[type=radio][name='view[data][layout]']");

	function layoutDescriptions() {
		var layout = layoutRadios.filter(":checked").attr("id");
		// TODO Can use alterClass here instead?
		$("#view-layout-info")
			.find(".layout-description")
				.hide()
			.end()
			.find("." + layout)
				.show();

		// Special handling

		if ( 'view-layout-normal' == layout )
			$("#column-count-wrapper").fadeOut();
		else
			$("#column-count-wrapper").fadeIn();

		if( 'view-layout-masonry' == layout ) {
			if ( $("#view-pagination").is(":checked") ) {
				alert('Masonry is incompatible with pagination. Please disable pagination first.');
				$("#view-layout-normal").prop("checked", true).change();
			}
		}
	}

	layoutDescriptions();

	layoutRadios.on("change", layoutDescriptions);

	/**
	 * Pagination change listener
	 */
	function paginationChangeListener() {
		// Pagination is incompatible with Masonry
		if ( $(this).is(":checked") && "masonry" == layoutRadios.filter(":checked").val() ) {
			alert('Pagination is incompatible with Masonry. Please select another layout first.');
			$(this).prop("checked", false).change();
		}
	}

	$("#view-pagination").on("change",paginationChangeListener);

	/**
	 * Background change listener
	 */
	var backgroundRadios = $("input[type=radio][name='view[data][background][type]']"),
		backgroundPreview = $("#background-preview"),
		backgroundPresetSelector = $("#view-background-preset");

	function backgroundDescriptions() {
		var backgroundID = backgroundRadios.filter(":checked").attr("id");

		$("#view-background-info")
			.find(".background-description:visible")
				.hide()
			.end()
			.find("." + backgroundID)
				.show();

		updateBackgroundPreview();
	}

	backgroundDescriptions();

	backgroundRadios.on("change", backgroundDescriptions);

	backgroundPresetSelector.on( "change", function() {
		backgroundPreset( $(this).val() );
	} );

	function updateBackgroundPreview() {
		var c1,
			c2,
			background = backgroundRadios.filter(":checked").val();

		switch ( background ) {
			case 'none':
				backgroundPreview.css( "background", "transparent" );
				break;
			case 'single':
				c1 = document.getElementById("bg-color").value;
				backgroundPreview.css( "background", c1 );
				break;
			case 'gradient':
				c1 = document.getElementById( "bg-gradient1" ).value;
				c2 = document.getElementById( "bg-gradient2" ).value;
				backgroundPreview.css(constructGradientCSS(c1, c2));
				break;
			case 'preset':
				backgroundPreset( backgroundPresetSelector.val() );
				break;
			default:
		}

	}

	function backgroundPreset( preset ) {
		if ( !preset ) {
			backgroundPreview.css( "background", "transparent" );
			return;
		}

		var data = {
			'action' : 'wpmtst_get_background_preset_colors',
			'key'    : preset,
		};
		$.get( ajaxurl, data, function( response ) {
			var presetObj = JSON.parse(response);
			if ( presetObj.color && presetObj.color2 ) {
				backgroundPreview.css(constructGradientCSS(presetObj.color, presetObj.color2));
			}
			else if (presetObj.color ) {
				backgroundPreview.css( "background", presetObj.color );
			}
			else {
				backgroundPreview.css( "background", "transparent" );
			}
		});
	}

	function constructGradientCSS( c1, c2 ) {
		return {
			"background": "linear-gradient(to bottom, "+c1+" 0%, "+c2+" 100%)"
		}
	}

	//$.fn.updateScreen(currentMode);

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
		var keys = $("#custom-field-list2").find("tbody tr").map(function() {
			return $(this).attr("id").split('-').slice(-1)[0];
		}).get();
		var nextKey = Array.max(keys)+1;
		var data = {
			'action' : 'wpmtst_view_add_field',
			'key'    : nextKey,
		};
		$.get( ajaxurl, data, function( response ) {
			// append to list
			$("#custom-field-list2").find("tbody").append(response);
		});
	});

	/**
	 * Field type change listener
	 */
	customFieldList.on("change", ".field-type select", function() {
		var $el = $(this);
		var $elParent = $el.closest("tr");
		var fieldType = $el.val();
		var fieldName = $elParent.find(".field-name").find("select").val();
		var key = $elParent.attr("id").split('-').slice(-1)[0];
		var data;

		switch (fieldType) {

			case 'link2':
			case 'link':
				// if changing to [link], add link fields
				data = {
					'action': 'wpmtst_view_add_field_link',
					'fieldName': fieldName,
					'fieldType': fieldType,
					'key': key,
				};
				$.get( ajaxurl, data, function( response ) {
					// insert into placeholder div
					$elParent.find(".field-meta").html(response);

					// Trigger conditional select
					var $newFieldSelect = $elParent.find(".if.selectgroup");
					$.fn.selectGroupOption($newFieldSelect);
					$newFieldSelect.on("change", function() {
						$.fn.selectGroupOption($newFieldSelect);
					});
					textChangeListener();

					// Get field name --> Get field label --> Populate link_text label
					var fieldName = $elParent.find(".field-name").find("select").val();
					var data2 = {
						'action': 'wpmtst_view_get_label',
						'name': fieldName,
					};
					$.get( ajaxurl, data2, function( response ) {
						var key = $elParent.attr("id").split('-').slice(-1)[0];
						$("#view-fieldtext" + key + "-label").val(response);
					});

				});
				break;

			case 'date':
				// if changing to [date], add date fields
				data = {
					'action' : 'wpmtst_view_add_field_date',
					'key'    : key,
				};
				$.get( ajaxurl, data, function( response ) {
					// insert into placeholder div
					$elParent.find(".field-meta").html(response);
				});
				break;

			case 'text':
				// if changing to [text], remove meta fields
				$elParent.find(".field-meta").empty();
				break;

			default:

		}
	});

	/**
	 * Field name change listener.
	 */
	customFieldList.on("change", ".field-name select", function() {
		var $el = $(this);
		var $elParent = $el.closest("tr");
		var fieldValue = $el.val();
		var key = $elParent.attr("id").split('-').slice(-1)[0];
		var typeSelect = $elParent.find("td.field-type select");

		switch( fieldValue ) {
			case 'post_date':
				// Hide type selector if date field.
				$(typeSelect).val("date").prop("disabled", true);

				// add format field
				var data = {
					'action': 'wpmtst_view_add_field_date',
					'key': key,
				};
				$.get( ajaxurl, data, function( response ) {
					// Insert into placeholder div. Add hidden field because we are
					// disabling the <select> so its value will not be submitted.
					$elParent.find(".field-meta").html(response);
					$el.parent().append('<input type="hidden" class="save-type" name="view[data][client_section][' + key + '][type]" value="date">');
				});
				break;

			case 'link2':
			case 'link':
				// Get field name --> Get field label --> Populate link_text label
				var fieldName = $elParent.find(".field-name").find("select").val();
				var data2 = {
					'action' : 'wpmtst_view_get_label',
					'name'   : fieldName,
				};
				$.get( ajaxurl, data2, function( response ) {
					var key = $elParent.attr("id").split('-').slice(-1)[0];
					$("#view-fieldtext" + key + "-label").val(response);
				});
				//break;

			default:
				$(typeSelect).val("text").prop("disabled",false);
				// remove meta field
				$elParent.find(".field-meta").empty();
				// remove the saved type that's only necessary when we disable the input (above)
				$el.parent().find("input.save-type").remove();
		}
	});

	/**
	 * Delete a client field
	 */
	customFieldList.on("click", ".delete-field", function(){
		var thisField = $(this).closest("tr");
		var yesno = confirm("Remove this field?");
		if( yesno ) {
			thisField.fadeOut(function(){$(this).remove()});
		}
	});

});
