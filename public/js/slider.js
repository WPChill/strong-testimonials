/**
 * Slider Handler
 */

jQuery(window).on("load", function () {

	// Load up our slideshows
	var strongSlideshows = jQuery('.strong-view.slider-container');

	strongSlideshows.each(function () {
		jQuery(this).strongSlider();
	});

});
