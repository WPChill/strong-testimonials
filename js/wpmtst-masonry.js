/**
 * For the Masonry template.
 */

jQuery(document).ready( function ($) {

	var $grid = jQuery('.strong-masonry');

	// Add our element sizing.
	$grid.prepend('<div class="grid-sizer"></div><div class="gutter-sizer"></div>');

	// Initialize Masonry after images are loaded. This is a fix for Chrome and Safari.
	$grid.imagesLoaded( function () {
		$grid.masonry({
			columnWidth: '.grid-sizer',
			gutter: '.gutter-sizer',
			itemSelector: '.testimonial',
			percentPosition: true
		});
	});

});
