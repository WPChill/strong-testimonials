/**
 * Submission form validation
 *
 * @package Strong_Testimonials
 */

jQuery(document).ready(function( $ ) {

	// Add protocol if missing
	// Thanks http://stackoverflow.com/a/36429927/51600
	$("input[type=url]").change(function() {
		if ( this.value.length && !/^https*:\/\//.test(this.value) ) {
			this.value = "http://" + this.value;
		}
	});

	// Scroll to first error, if any
	if ( typeof( formError ) !== 'undefined' && formError.scrollTop == "1" ) {
		var containerOffset, scrollTop;
		containerOffset = $(".error:first").closest(".form-field").offset();
		if ( containerOffset ) {
			scrollTop = containerOffset.top - formError.offset;

			// is WordPress admin bar showing?
			if ($("#wpadminbar").length) {
				scrollTop -= 32;
			}

			$("html, body").animate({scrollTop: scrollTop}, 800);
		}
	}

});
