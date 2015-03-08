/**
 * Strong Testimonials > cycle settings
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
	
	// Shortcode
	$(".strong-container").each(function(index){
		var $el = $(this).find(".tcycle");
		if(!$el.length) return;
		$el.addClass("tcycle_" + index);
		
		// Set container height to match tallest element.
		var heights = $el.find("div.t-slide").map(function() {
				return $(this).outerHeight(true);
			}).get();
		
		var maxHeight = Array.max( heights );
		$el.height( maxHeight );
		
		// Storing variable name in CSS class instead of HTML data elements until the world abandons IE 8-9.
		//   e.g. class="wpmtst-widget-container-2 tcycle tcycle_strong_widget_cycle_2"
		//
		// Thanks http://stackoverflow.com/a/15505986/51600
		var cycleVar = $.grep($el.prop("class").split(/\s+/), function(v, i){
			return v.indexOf('tcycle_') === 0;
		}).join();
		
		if( typeof( window[cycleVar] ) !== 'undefined' ) {
			var parms = window[cycleVar];
			$el.cycle({
				slides       : "> div.t-slide",
				fx           : parms.effect,
				speed        : parseInt( parms.speed ),
				timeout      : parseInt( parms.timeout ),
				pause        : "1" == parms.pause ? true : false,  // Cycle
				pauseOnHover : "1" == parms.pause ? true : false   // Cycle2
			});
		}
				
	});
		
	// Original Shortcode & Widgets
	$("#wpmtst-container.tcycle").add(".wpmtst-widget-container.tcycle").each( function(index, el) {
		// Set container height to match tallest element.
		var heights = $("div.t-slide", el).map(function() {
				return $(this).outerHeight(true);
			}).get();
		
		var maxHeight = Array.max( heights );
		$(el).height( maxHeight );
		
		var cycleVar = $.grep(el.className.split(/\s+/), function(v, i){
			return v.indexOf('tcycle_') === 0;
		}).join();
		
		if( typeof( window[cycleVar] ) !== 'undefined' ) {
			var parms = window[cycleVar];
			$(el).cycle({
				slides       : "> div.t-slide",
				fx           : parms.effect,
				speed        : parseInt( parms.speed ),
				timeout      : parseInt( parms.timeout ),
				pause        : "1" == parms.pause ? true : false,  // Cycle
				pauseOnHover : "1" == parms.pause ? true : false   // Cycle2
			});
		}
				
	});

});
