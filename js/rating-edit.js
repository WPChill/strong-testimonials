/**
 * Edit rating
 */

jQuery(document).ready(function($) {

	var ratingForm = $('#rating-form'),
		ratingDisplay = $('#rating-display');

	//ratingDisplay.show();

	var editRating = function() {
		var revert_e,
			postId = $('#post_ID').val() || 0,
			buttons2 = $('#edit-rating-buttons-2');

		//TODO REFACTOR so "off" isn't necssary!
		buttons2.children('.save').off( "click");
		buttons2.children('.cancel').off("click");

		ratingDisplay.hide();
		ratingForm.showInlineBlock();

		var box = $('#edit-rating-success');
		box.html('');

		revert_e = $( '#current-rating' ).val();

		buttons2.children( '.save' ).on( "click", function() {
			var new_rating = ratingForm.find("input:checked").val();
			var field_name = ratingForm.find("input:checked").attr("name");

			if ( new_rating == revert_e ) {
				buttons2.children('.cancel').click();
				return;
			}

			$.post(ajaxurl, {
				action: 'wpmtst_edit_rating',
				post_id: postId,
				field_name: field_name,
				rating: new_rating,
				editratingnonce: $('#editratingnonce').val()
			}, function(data) {
				var obj = JSON.parse( data );

				var stars = ratingDisplay.find(".inner");
				stars.html(obj.display);

				box.html(obj.message);

				ratingForm.find("input[value="+new_rating+"]").prop("checked", true);

				if (box.hasClass('hidden')) {
					box.fadeIn('fast', function () {
						box.removeClass('hidden');
					});
				}

				ratingForm.hide();
				ratingDisplay.showInlineBlock();
			});
			return false;
		});

		buttons2.children( '.cancel' ).on("click", function() {
			ratingForm.find("input[value="+revert_e+"]").prop("checked", true);
			ratingForm.hide();
			ratingDisplay.showInlineBlock();
			return false;
		});

	}

	$("#edit-rating").click( editRating );

});
