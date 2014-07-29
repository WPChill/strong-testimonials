/**
 * wpmtst-cycle.js
 * Strong Testimonials > slider settings
 */
 
jQuery(document).ready(function($) { 

	// Not sure if this is a problem with my CSS or if Cycle
	// is expecting elements to be of equal sizes, but we have
	// to set the container to match the height of the tallest div.
	
	// debug
	// $(".tcycle > div").each( function( index ) {
		// console.log(index, $(this).height(), $(this).outerHeight(true));
	// });

	// thanks http://stackoverflow.com/a/5052710/51600
	
	// Function to get the max value in array
	Array.max = function( array ){
		return Math.max.apply( Math, array );
	};

	// ------------------
	// Multiple instances
	// ------------------
	
	$(".tcycle").each( function(index, el) {
		var heights = $("> div", el).map(function() {
				return $(this).outerHeight(true);
		}).get();
		
		var maxHeight = Array.max( heights );
		$(el).height( maxHeight );
	});
		
	// Example CDATA section:
	// var cycleWidget = {"effect":"fade","speed":"1000","timeout":"8000","pause":"1","div":".wpmtst-widget-container"};
	// var cycleShortcode = {"effect":"fade","speed":"1000","timeout":"5000","pause":"1","div":"#wpmtst-container"};

	// Widget
	if( typeof(cycleWidget) !== 'undefined' )
		cycleIt( cycleWidget );
	
	// Shortcode
	if( typeof(cycleShortcode) !== 'undefined' )
		cycleIt( cycleShortcode );
	
	function cycleIt( el ) {
		$( el.div).cycle({ 
			slides       : "> div",
			fx           : el.effect,
			speed        : parseInt( el.speed ),
			timeout      : parseInt( el.timeout ),
			pauseOnHover : "1" == el.pause ? true : false
		});
	}
	
});
