/*
	wpmtst-admin.js
	Strong Testimonials > admin screens
*/
jQuery(document).ready(function($) {
	
	// Function to get the Max value in Array
	Array.max = function( array ){
			return Math.max.apply( Math, array );
	};


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
	
	
	// ------------------
	// Custom field table
	// ------------------
	
	$(".custom-field").hide();
	
	// sortable
	$("#custom-field-list").sortable({
		placeholder: "sortable-placeholder",
		forcePlaceholderSize: true,
		handle: ".handle",
		cursor: "move",
	});
	
	// click handler (delegated)
	$("#custom-field-list").on("click", "a.field", function(e){
		$(this)
			.blur()
			.closest("li")
			.toggleClass("open")
			.find(".custom-field")
			.toggleClass("open")
			.slideToggle("slow")
			.find(".first-field")
			.focus()
			.select();
		return false;
	});
	
	// update list item label when field label changes
	$("#custom-field-list").on("change blur", "input.field-label", function(e){
		var newLabel = $(this).val();
		var $parent = $(this).closest("li");
		
		// fill in blank label
		if( ! $(this).val() ) {
			$(this).val("New Field");
		}

		// update parent list item
		$parent.find("a.field").html(newLabel);
		
		// fill in blank field name
		$fieldName = $parent.find("input.field-name");
		if( ! $fieldName.val() ) {
			var newFieldName = newLabel.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
			$fieldName.val(newFieldName);
		}
	});
	
	// fill in blank field name
	$("#custom-field-list").on("blur", "input.field-name", function(e){
		var fieldLabel = $(this).closest(".field-table").find(".field-label").val();
		
		if( ! $(this).val() ) {
			var newFieldName = fieldLabel.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
			$(this).val(newFieldName);
		}
	});
	
	// restore defaults
	$("#restore-defaults").click(function(){
		return confirm("Restore the default fields?");
	});
	
	// delete field
	$("#custom-field-list").on("click", ".delete-field", function(){
		var thisField = $(this).closest("li");
		var thisLabel = thisField.find(".field").html();
		var yesno = confirm("Delete \"" + thisLabel + "\"?");
		if( yesno ) {
			thisField.fadeOut(function(){$(this).remove()});
		}
	});
	
	// close field
	$("#custom-field-list").on("click", "span.close-field a", function(){
		$(this)
			.blur()
			.closest("li")
			.toggleClass("open")
			.find(".custom-field")
			.toggleClass("open")
			.slideToggle();
		return false;
	});

	// add new field
	$("#add-field").click(function(e) {
		var keys = $("#custom-field-list > li").map(function() {
			var key_id = $(this).attr("id");
			return key_id.substr( key_id.lastIndexOf("-")+1 );
		}).get();
		var nextKey = Array.max(keys)+1;

		var data = {
			'action' : 'wpmtst_add_field',
			'key'    : nextKey,
			'fieldClass' : null,
			'fieldType' : null,
		};
		$.get( ajaxurl, data, function( response ) {
			// disable Add button
			$("#add-field").attr("disabled","disabled");
			
			// create list item
			var $li = $('<li id="field-'+nextKey+'">').append( response );
			
			// append to list
			$("#custom-field-list").append($li);
			
			// {
			// We need to disable any Post fields already in use.
			// ---------------------------------------------------------
			// Doing this client-side so a Post field can be added 
			// but not saved before adding more fields;
			// i.e. add multiple fields of either type without risk
			// of duplicating single Post fields before clicking "Save".
			
			$("#custom-field-list").find('input[name$="[record_type]"]').each(function(index) {
				if( "post" == $(this).val() ) {
					var name = $(this).closest("li").find(".field-name").val();
					$li.find("select.field-type.new").find('option[value="'+name+'"]').attr("disabled","disabled");
				}
			});
			// }
			
			// hide "Close" link until Type is selected
			$("span.close-field").hide();
			
			// click it to open
			$li.find("a.field").click();
		});
	});

	// field type change
	$("#custom-field-list")
		.on("focus", ".field-type", function() {
			// console.log('field type focus');
			
			// store existing values on parent element
			
			// find parent element
			var fieldType = $(this).val();
			// var $table = $(this).closest("table");
			var $parent = $(this).closest("li");
			
			// label
			var $fieldLabel = $parent.find('input.field-label');
			$fieldLabel.data('oldValue',$fieldLabel.val());
			
			// name
			var $fieldName = $parent.find('input.field-name');
			$fieldName.data('oldValue',$fieldName.val());
			
			// admin-table
			// var $fieldAdminTable = $parent.find('input.field-admin-table');
			// $fieldAdminTable.data('oldValue',$fieldAdminTable.val());
			
		})
		.on("change", ".field-type", function() {
			// force values if selecting a Post field
			
			var fieldType = $(this).val();
			console.log('new field type:', fieldType);
			
			var $table = $(this).closest("table");
			var $parent = $(this).closest('li');
			var key_id = $parent.attr("id");
			var key = key_id.substr( key_id.lastIndexOf("-")+1 );
			
			var $fieldLabel = $parent.find('input.field-label');
			var $fieldName  = $parent.find('input.field-name');
			

			// get type of field from its optgroup
			// **********************
			// IS THERE A BETTER WAY?
			// **********************
			var fieldClass = $(this).find("option[value='"+fieldType+"']").closest("optgroup").attr("class");
			var postOrCustom = fieldClass.substr(0,fieldClass.indexOf("-"));
			console.log('record type:', postOrCustom);
			
			// Find the record type (Post or Custom).
			// If found, we are changing the type of an existing field.
			// If not found, we are adding a new field.
			// **********************
			// IS THERE A BETTER WAY?
			// **********************
			var $fieldRecordType = $parent.find('input[name$="[record_type]"]');
			if( $fieldRecordType.length ) {
			
				// --------
				// changing
				// --------
				// could be changing after being *added* but before being *saved*
				console.log('changing existing field');
				
				if( postOrCustom == "post" ) {
				
					if( fieldType == 'post_title' ) {
						$fieldLabel.val('Testimonial Title');
						$fieldName.val('post_title').attr('disabled','disabled');
					}
					else if( fieldType == 'featured_image' ) {
						$fieldLabel.val('Photo');
						$fieldName.val('featured_image').attr('disabled','disabled');
					}
					
				}
				else {
				
					// if switching back from Post field to Custom field
					var fieldName = $fieldName.val();
					if( fieldName == 'post_title' || fieldName == 'featured_image' ) {
						$fieldLabel.val($fieldLabel.data('oldValue'));
						$fieldName.val($fieldName.data('oldValue')).removeAttr('disabled');
						$parent.find(".custom-field-header a.field").html( $fieldLabel.val() );
					}
				
				}
				
				// update admin-table setting
				var data = {
					'action'     : 'wpmtst_add_field_4',
					'key'        : key,
					'fieldClass' : postOrCustom,
					'fieldType'  : fieldType,
				};
				$.get( ajaxurl, data, function( response ) {
					$table.find("tr.field-admin-table").replaceWith(response);
				});
				
			}
			else {
			
				// ------
				// adding
				// ------
				
				if( postOrCustom == 'post' ) {
				
					if( fieldType == 'post_title' ) {
						$fieldLabel.val('Testimonial Title');
						$fieldName.val('post_title').attr('disabled','disabled');
					}
					else if( fieldType == 'featured_image' ) {
						$fieldLabel.val('Photo');
						$fieldName.val('featured_image').attr('disabled','disabled');
					}
					
				}

				// Nesting Ajax calls for now.
				// secondary form fields
				var data1 = {
					'action'     : 'wpmtst_add_field_2',
					'key'        : key,
					'fieldClass' : postOrCustom,
					'fieldType'  : fieldType,
				};
				$.get( ajaxurl, data1, function( response ) {
				
					$table.append(response);
					
					// admin-table field
					var data2 = {
						'action'     : 'wpmtst_add_field_4',
						'key'        : key,
						'fieldClass' : postOrCustom,
						'fieldType'  : fieldType,
					};
					$.get( ajaxurl, data2, function( response ) {
					
						$table.append(response);
						
						// hidden inputs
						var data3 = {
							'action'     : 'wpmtst_add_field_3',
							'key'        : key,
							'fieldClass' : postOrCustom,
							'fieldType'  : fieldType,
						};
						$.get( ajaxurl, data3, function( response ) {
						
							$table.parent().append(response);
						
						});
					
					});
				
				});

				
				// Successfully added so show "Close" link...
				$("span.close-field").show();
				// ...and enable "Add New Field" button.
				$("#add-field").removeAttr("disabled");
			}
			
			// update parent list item
			$parent.find(".custom-field-header a.field").html( $fieldLabel.val() );
				
			// change [record_type]
			$fieldRecordType.val(postOrCustom);
			
		}); // on(change)
		
});
