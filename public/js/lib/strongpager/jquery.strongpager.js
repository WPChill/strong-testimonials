/**
 * Strong Pager jQuery plugin
 */
;(function ($) {

  var defaults = {
    pageSize: 10,
    currentPage: 1,
    pagerLocation: 'after',
    scrollTop: 1,
    offset: 40
  }

  $.fn.strongPager = function (options) {

    if (this.length === 0) {
      return this
    }

    // create a namespace to be used throughout the plugin
    var pager = {}
    // set a reference to our slider element
    var el = this

    /**
     * Initialize
     */
    var init = function () {

      var pagerVar = el.parent().data('pager-var')
      var config = {}

      if (typeof( window[pagerVar] ) !== 'undefined') {
        config = window[pagerVar].config
      }

      // Merge user options with the defaults
      pager.settings = $.extend(defaults, config, options)

      pager.pageCounter = 0
      pager.currentPage = pager.settings.currentPage

      setup()
    }

    /**
     * Scroll upon navigation
     */
    var scroll = function () {
      // Scroll up for any nav click
      if (pager.settings.scrollTop) {
        $('html, body').animate({scrollTop: pager.scrollto}, 800)
      }
    }

    /**
     * Paginate
     */
    var paginate = function () {
      var pageCounter = 1

      el.wrap('<div class="simplePagerContainer"></div>')

      el.children().each(function (i) {
        var rangeEnd = pageCounter * pager.settings.pageSize - 1
        if ( i > rangeEnd) {
          pageCounter++
        }
        $(this).addClass('simplePagerPage' + pageCounter)
      })

      pager.pageCounter = pageCounter
    }

    /**
     * Calculate offset for scrolling
     */
    var findOffset = function () {
      var containerOffset

      // WooCommerce product tabs
      if (el.closest('.woocommerce-tabs').length) {
        containerOffset = el.closest('.woocommerce-tabs').offset()
      } else {
        containerOffset = el.closest('.simplePagerContainer').offset()
      }

      pager.scrollto = containerOffset.top - pager.settings.offset

      // WordPress admin bar
      if (document.getElementById('#wpadminbar')) {
        pager.scrollto -= 32
      }
    }

    /**
     * Hide all and show current
     */
    var switchPages = function (fade) {
      el.children().hide()
      var newPage = el.children('.simplePagerPage' + pager.currentPage)
      if (fade)
        newPage.fadeIn()
      else
        newPage.show()
    }

    /**
     * Add navigation
     */
    var addNavigation = function () {
      var nav = '<ul class="simplePagerNav">'
      var cssClass

      for (var i = 1; i <= pager.pageCounter; i++) {
        cssClass = ""
        if (i === pager.currentPage) {
          cssClass = "currentPage "
        }
        nav += '<li class="' + cssClass + 'simplePageNav' + i + '"><a rel="' + i + '" href="#">' + i + '</a></li>'
      }
      nav += '</ul>'
      nav = '<div class="simplePagerList">' + nav + '</div>'

      switch (pager.settings.pagerLocation) {
        case 'before':
          el.before(nav)
          break
        case 'both':
          el.before(nav)
          el.after(nav)
          break
        default:
          el.after(nav)
      }
    }

    /**
     * Navigation behavior
     */
    var navigationHandler = function () {
      el.parent().find('.simplePagerNav a').click(function (e) {
        var $this = $(e.target)
        var container

        container = $this.closest('.simplePagerContainer')

        // Get the REL attribute
        pager.currentPage = $this.attr('rel')

        // Remove current page highlight
        container.find('li.currentPage').removeClass('currentPage')

        // Add current page highlight
        container.find('a[rel="' + pager.currentPage + '"]').parent('li').addClass('currentPage')

        // Switch pages
        switchPages(true)

        // Scroll up for any nav click
        scroll()

        return false
      })
    }

    /**
     * Setup
     */
    var setup = function () {
      paginate()
      // Bail if only one page
      if (pager.pageCounter <= 1) {
        return
      }

      findOffset()
      switchPages()
      addNavigation()
      navigationHandler()
    }

    /**
     * Start it up
     */
    init()

    return this
  }

})(jQuery)
