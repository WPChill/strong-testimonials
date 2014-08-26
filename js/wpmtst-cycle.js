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
	
	// Set container height to match tallest element.
	$(".tcycle").each( function(index, el) {
		var heights = $("div.t-slide", el).map(function() {
				return $(this).outerHeight(true);
			}).get();
		
		var maxHeight = Array.max( heights );
		$(el).height( maxHeight );
		
		// Storing variable name in CSS class instead of HTML data elements until the world abandons IE 8-9.
		//   e.g. class="wpmtst-widget-container-2 tcycle tcycle_strong_widget_cycle_2"
		//
		// Example variables added using `wp_localize_script`:
		//   var tcycle_strong_widget_cycle_2 = {"fx":"fade","speed":"1000","timeout":"5000","pause":"1"};
		//   var tcycle_wpmtst_widget_4 = {"fx":"fade","speed":"1500","timeout":"3000","pause":"1"};
		//   var tcycle_cycle_shortcode = {"fx":"fade","speed":"500","timeout":"3000","pause":"1"};
		//
		// Thanks http://stackoverflow.com/a/15505986/51600
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
				pauseOnHover : "1" == parms.pause ? true : false
			});
		}
				
	});
		
});
