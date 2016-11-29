/**
 * Slider Handler
 */

// Function to get the max value in array
Array.max = function (array) {
	return Math.max.apply(Math, array);
};

jQuery(document).ready(function( $ ) {

	// Load up our slideshows
	var strongSlideshows = $('.strong-view.slider-container');

	strongSlideshows.each(function () {
		$(this).strongSlider();
	});

});
