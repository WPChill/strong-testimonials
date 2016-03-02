/**
 * Pagination
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */

jQuery(document).ready(function($) {

	if( typeof( pagerVar ) !== 'undefined' ) {

		$(pagerVar.id).quickPager({
			pageSize: pagerVar.pageSize,
			currentPage: pagerVar.currentPage,
			pagerLocation: pagerVar.pagerLocation,
			scrollTop: pagerVar.scrollTop,
			offset: pagerVar.offset
		});

	}

});
