/**
 * Pagination
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */

jQuery(document).ready(function($) {

	if( typeof( pagerVar ) !== 'undefined' ) {
	
		// #wpmtst-container will be phased out soon ~!~
		$("#wpmtst-container").quickPager({ 
				pageSize      : pagerVar.pageSize, 
				currentPage   : pagerVar.currentPage, 
				pagerLocation : pagerVar.pagerLocation 
		});
		$(".strong-content").quickPager({ 
				pageSize      : pagerVar.pageSize, 
				currentPage   : pagerVar.currentPage, 
				pagerLocation : pagerVar.pagerLocation 
		});
	
	}
	
});