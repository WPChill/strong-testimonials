/**
 * Strong Testimonials slideshow
 *
 * Version: 0.3
 *
 * @since 0.2.0 Compatible with Max Mega Menu plugin.
 * @since 0.3.0 Compatible with both versions of Cycle.
 */

// Function to get the max value in array
Array.max = function (array) {
	return Math.max.apply(Math, array);
};


/**
 * Set height of slideshow container and initialize Cycle after all images
 * have loaded for accurate height and width of elements.
 *
 * Using Actual plugin to get width of hidden slides. Excellent!
 * http://stackoverflow.com/a/5555792/51600
 * http://dreamerslab.com/blog/en/get-hidden-elements-width-and-height-with-jquery/
 */

jQuery(window).on("load", function () {

	var megaMenuItems,
		strongSlideshows;

	megaMenuItems    = jQuery('li.mega-menu-item');
	strongSlideshows = jQuery('.strong_cycle');

	if( megaMenuItems.length ) {
		// Add class to not auto-start and set height to zero to avoid flash 
		// when full background shrinks to max height of slideshow.
		megaMenuItems.find('.strong_cycle').addClass('noinit').css('height', 0);
		// and underlay other slideshows (edge case).
		strongSlideshows.not('.noinit').parent('.strong-view').css('opacity', '.99');
	}

	var cycleObject = {

		setViewHeight: function ($el) {
			var heights = $el.find('div.t-slide').map(function () {
				return jQuery(this).actual('outerHeight');
			}).get();

			var maxHeight = Array.max(heights);
			$el.height(maxHeight);
		},

		getCycleVar: function ($el) {
			return jQuery.grep($el.prop('class').split(/\s+/), function (v, i) {
				return v.indexOf('strong_cycle_') === 0;
			}).join();
		},

		setOpts: function (parms) {
			return {
				// Cycle2
				autoHeight: false,
				pagerTemplate: parms.pagerTemplate,
				pauseOnHover: "1" == parms.pause,
				slides: '> div.t-slide',

				// Cycle
				activePagerClass: 'cycle-pager-active',
				pagerAnchorBuilder: function( idx, slide) {
					return parms.pagerTemplate.replace( /{{slideNum}}/, idx + 1 )
				},
				pause: "1" == parms.pause,
				pauseOnPagerHover: "1" == parms.pause,
				slideExpr: '> div.t-slide',

				// common
				fx: parms.fx,
				log: false,
				maxZ: parseInt(parms.maxZ) ? parseInt(parms.maxZ) : 9,
				next: parms.next,
				pager: parms.pager,
				prev: parms.prev,
				speed: parseInt(parms.speed),
				timeout: parseInt(parms.timeout)
			};
		},

		initCycle: function (el) {
			var $el = jQuery(el);
			this.setViewHeight($el);
			var cycleVar = this.getCycleVar($el);
			if (typeof( window[cycleVar] ) !== 'undefined') {
				var opts = this.setOpts(window[cycleVar]);
				$el.cycle(opts);
			}
		}
	}


	// Start normal slideshows.
	strongSlideshows.not('.noinit').each(function () {
		cycleObject.initCycle(this);
	});

	// Start slideshow when menu item opens.
	megaMenuItems.on('open_panel', function () {
		jQuery(this).find('.strong_cycle').each(function () {
			cycleObject.initCycle(this);
		});
	});


	// When menu closes, destroy Cycle slideshow and reset height.
	megaMenuItems.on('close_panel', function () {
		jQuery(this).find('.strong_cycle').each(function () {
			jQuery(this).cycle('destroy').css('height', 0);
		});
	});

});
