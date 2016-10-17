jQuery(document).ready(function( $ ) {
	if (typeof( formSuccess ) !== 'undefined' && formSuccess.scrollTop == "1") {

		var containerOffset, scrollTop;
		containerOffset = $(".testimonial-success").offset();
		scrollTop = containerOffset.top - formSuccess.offset;

		// is WordPress admin bar showing?
		if ($("#wpadminbar").length) {
			scrollTop -= 32;
		}

		$("html, body").animate({scrollTop: scrollTop}, 800);

	}
});
