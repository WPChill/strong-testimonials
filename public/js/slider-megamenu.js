/**
 * Slider Handler
 */

// Function to get the max value in array
Array.max = function (array) {
	return Math.max.apply(Math, array);
};

jQuery(document).ready(function( $ ) {

	// Find Mega Menu items
	var megaMenuItems = jQuery('li.mega-menu-item');

	// Load up our slideshows
	var strongSlideshows = $('.strong-view.slider-container');

	if( megaMenuItems.length ) {
		// Add class to not auto-start and set height to zero to avoid flash
		// when full background shrinks to max height of slideshow.
		megaMenuItems.find('.strong-view.slider-container').addClass('noinit');
		// and underlay other slideshows (edge case).
		strongSlideshows.not('.noinit').css('opacity', '.99');
	}

	// Start normal slideshows.
	strongSlideshows.not('.noinit').each(function () {
		$(this).strongSlider();
	});

	// Start slideshow when menu item opens.
	megaMenuItems.on('open_panel', function () {
		jQuery(this).find('.strong-view.slider-container').each(function () {
			$(this).strongSlider();
		});
	});

});
