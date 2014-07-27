/**
 * wpmtst-widget.js
 * Strong Testimonials > slider settings
 */
 
jQuery(document).ready(function($) { 

	// Not sure if this is a problem with my CSS or if Colorbox
	// is expecting elements to be of equal sizes, but we have
	// to set the container to match the height of the tallest div.
	
	// debug
	// $(".tcycle > div").each( function( index ) {
		// console.log(index, $(this).height(), $(this).outerHeight(true));
	// });

	// thanks http://stackoverflow.com/a/5052710/51600
	
	// Function to get the Max value in Array
	Array.max = function( array ){
			return Math.max.apply( Math, array );
	};

	var heights = $(".tcycle > div").map(function() {
			return $(this).outerHeight(true);
	}).get();
	
	var maxHeight = Array.max( heights );
	$(".tcycle").height( maxHeight );

	
	// Add Colorbox to testimonials div.
	// Handles both widget and shortcode.
	$(".tcycle").cycle({ 
		slides       : "> div",
		fx           : tcycle.effect,
		speed        : parseInt( tcycle.speed ),
		timeout      : parseInt( tcycle.timeout ),
		pauseOnHover : "1" == tcycle.pause ? true : false,
	});
});
