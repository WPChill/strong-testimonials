/**
 * Pagination
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */

jQuery(document).ready(function ($) {

  var pagerObject = {

    setOpts: function (parms) {
      return {
        id: parms.id,
        pageSize: parseInt(parms.pageSize),
        currentPage: parseInt(parms.currentPage),
        pagerLocation: parms.pagerLocation,
        scrollTop: parseInt(parms.scrollTop),
        offset: parseInt(parms.offset)
      }
    },

    getPagerVar: function ($el) {
      return $.grep($el.prop('class').split(/\s+/), function (v, i) {
        return v.indexOf('strong_pager_') === 0
      }).join()
    },

    initPager: function (el) {
      var $el = $(el)
      var pagerVar = this.getPagerVar($el)
      if (typeof( window[pagerVar] ) !== 'undefined') {
        var opts = this.setOpts(window[pagerVar])
        $el.quickPager(opts)
      }
    }

  }

  $('.strong-paginated').each(function () {
    pagerObject.initPager(this)
  })

})
