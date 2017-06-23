/**
 Quick Pager jQuery plugin

 Copyright (C) 2011 by Dan Drayne

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.

 v1.1   18/09/09 * bug fix by John V - http://blog.geekyjohn.com/

 v1.2   09/29/2014
 Modified for Strong Testimonials WordPress plugin by Chris Dillon
 chris@wpmission.com

 v1.3   01/05/2016 -- added offset

 v1.4   03/01/2016 -- added scrollTop
 */

(function ($) {

  $.fn.quickPager = function (options) {

    var defaults = {
      pageSize: 10,
      currentPage: 1,
      holder: null,
      pagerLocation: 'after',
      scrollTop: 1,
      offset: 40
    }

    options = $.extend(defaults, options)

    return this.each(function () {

      var selector = $(this)
      var pageCounter = 1

      selector.wrap('<div class=\'simplePagerContainer\'></div>')

      selector.children().each(function (i) {

        if (i < pageCounter * options.pageSize && i >= (pageCounter - 1) * options.pageSize) {
          $(this).addClass('simplePagerPage' + pageCounter)
        }
        else {
          $(this).addClass('simplePagerPage' + (pageCounter + 1))
          pageCounter++
        }

      })

      // show/hide the appropriate regions
      selector.children().hide()
      selector.children('.simplePagerPage' + options.currentPage).show()

      if (pageCounter <= 1) {
        return
      }

      //Build pager navigation
      var pageNav = '<ul class=\'simplePagerNav\'>'
      for (var i = 1; i <= pageCounter; i++) {
        if (i === options.currentPage) {
          pageNav += '<li class=\'currentPage simplePageNav' + i + '\'><a rel=\'' + i + '\' href=\'#\'>' + i + '</a></li>'
        }
        else {
          pageNav += '<li class=\'simplePageNav' + i + '\'><a rel=\'' + i + '\' href=\'#\'>' + i + '</a></li>'
        }
      }
      pageNav += '</ul>'
      pageNav = '<div class=\'simplePagerList\'>' + pageNav + '</div>'

      if (!options.holder) {
        switch (options.pagerLocation) {
          case 'before':
            selector.before(pageNav)
            break
          case 'both':
            selector.before(pageNav)
            selector.after(pageNav)
            break
          default:
            selector.after(pageNav)
        }
      }
      else {
        $(options.holder).append(pageNav)
      }

      //pager navigation behaviour
      selector.parent().find('.simplePagerNav a').click(function () {

        //grab the REL attribute
        var clickedLink = $(this).attr('rel')
        options.currentPage = clickedLink

        if (options.holder) {
          $(this).closest(options.holder).find('li.currentPage').removeClass('currentPage')
          $(this).closest(options.holder).find('a[rel=\'' + clickedLink + '\']').parent('li').addClass('currentPage')
        }
        else {
          // Remove current page highlight
          $(this).closest('.simplePagerContainer').find('li.currentPage').removeClass('currentPage')
          // Add current page highlight
          $(this).closest('.simplePagerContainer').find('a[rel=\'' + clickedLink + '\']').parent('li').addClass('currentPage')
        }

        // Hide and show relevant links
        selector.children().hide()
        selector.find('.simplePagerPage' + clickedLink).show()

        // Scroll up for any nav click
        if (parseInt(options.scrollTop)) {
          var containerOffset

          // Special cases:
          //   WooCommerce product tabs
          if (selector.closest('.woocommerce-tabs').length) {
            containerOffset = selector.closest('.woocommerce-tabs').offset()
          } else {
            containerOffset = selector.closest('.simplePagerContainer').offset()
          }

          var scrollto = containerOffset.top - options.offset

          // is WordPress admin bar showing?
          if ($('#wpadminbar').length) {
            scrollto -= 32
          }

          $('html, body').animate({scrollTop: scrollto}, 800)
        }

        return false
      })
    })
  }

})(jQuery)
