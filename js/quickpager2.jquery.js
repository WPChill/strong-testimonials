/**
  Quick Pager 2 v0.2.1
  A jQuery plugin for simple Prev / Next pagination,
	Copyright (c) 2014 Chris Dillon.

  Based on Quick Pager, https://github.com/dan0/simplepager
	portions copyright (c) 2011 by Dan Drayne.

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

	v1.1/		18/09/09 * bug fix by John V - http://blog.geekyjohn.com/

*/

(function($) {

  $.fn.quickPager2 = function(options) {

    var defaults = {
      pageSize: 10,
      currentPage: 1,
      holder: null,
      pagerLocation: "after"
    };

    var options = $.extend(defaults, options);

    return this.each(function() {

      var selector = $(this);
      var pageCounter = 1;

      selector.wrap("<div class='simplePagerContainer'></div>");

      selector.children().each(function(i){

        if(i < pageCounter*options.pageSize && i >= (pageCounter-1)*options.pageSize) {
          $(this).addClass("simplePagerPage"+pageCounter);
        }
        else {
          $(this).addClass("simplePagerPage"+(pageCounter+1));
          pageCounter ++;
        }

      });

      // show/hide the appropriate regions
      selector.children().hide();
      selector.children(".simplePagerPage"+options.currentPage).show();

      if(pageCounter <= 1) {
        return;
      }

      // Build pager navigation
      var pageNav = "<ul class='simplePagerNav'>";
      var liClasses = '';
      for (i=1;i<=pageCounter;i++){
        if (i==options.currentPage) {
          liClasses += "previousPage simplePageNav"+i;
          if (i==1 && options.currentPage==1) {
            liClasses += " disabledPage";
          }
          pageNav += "<li class='"+liClasses+"'><a rel='"+i+"' href='#'>Prev</a></li>"; // [Prev] link
        }
        else {
          if (i==options.currentPage+1){
            liClasses = "nextPage simplePageNav"+i;
            pageNav += "<li class='"+liClasses+"'><a rel='"+i+"' href='#'>Next</a></li>"; // [Next] link
          }
        }
      }
      pageNav += "</ul>";

      if(!options.holder) {
        switch(options.pagerLocation)
        {
          case "before":
            selector.before(pageNav);
          break;
          case "both":
            selector.before(pageNav);
            selector.after(pageNav);
          break;
          default:
            selector.after(pageNav);
        }
      }
      else {
        $(options.holder).append(pageNav);
      }

      //pager navigation behaviour
      selector.parent().find(".simplePagerNav a").click(function() {

        //grab the REL attribute
        var clickedLink = $(this).attr("rel");
        var clickedPrev = $(this).parent("li").hasClass("previousPage");
        var clickedNext = $(this).parent("li").hasClass("nextPage");
        options.currentPage = clickedLink;

        if(options.holder) {
          $(this).parent("li").parent("ul").parent(options.holder).find("li.currentPage").removeClass("currentPage");
          $(this).parent("li").parent("ul").parent(options.holder).find("a[rel='"+clickedLink+"']").parent("li").addClass("currentPage");
        }
        else {
          // Changed to [Prev] + [Next] links

          // $(this).parent("li").parent("ul").find("li.disabledPage").removeClass("disabledPage");
          $(".simplePagerNav").find("li.disabledPage").removeClass("disabledPage");

          // [Next] clicked
          if (clickedNext) {

            // change [Next] link
            if ( clickedLink == pageCounter ) {
              // leave as link to last page, but change appearance (optional)
              $(".simplePagerNav li.nextPage").addClass("disabledPage");
            } else {
              // change target
              $(".simplePagerNav li.nextPage").find("a").attr("rel",parseFloat(clickedLink)+1);
            }

            // change [Prev] target
            $(".simplePagerNav li.previousPage").find("a").attr("rel",parseFloat(clickedLink));
          }

          // [Prev] clicked
          else if ( clickedPrev ) {

            // change [Prev] link
            if ( clickedLink == 1 ) {
              // leave as link to first page, but change appearance (optional)
              $(".simplePagerNav li.previousPage").addClass("disabledPage");
            } else {
              // change [Prev] target
              $(".simplePagerNav li.previousPage").find("a").attr("rel",parseFloat(clickedLink)-1);
            }

            // change [Next] target
            $(".simplePagerNav li.nextPage").find("a").attr("rel",parseFloat(clickedLink));

          }

        }
        // debug
        // $(".simplePagerNav li.previousPage a").each(function(index){$(this).html("Prev ["+$(this).prop("rel")+"]")});
        // $(".simplePagerNav li.nextPage a").each(function(index){$(this).html("Next ["+$(this).prop("rel")+"]")});

        // hide and show relevant links
        selector.children().hide();
        selector.find(".simplePagerPage"+clickedLink).show();

        // Scroll to top for any click
        $("html, body").animate({ "scrollTop": $("div.content").offset().top }, 800);

        return false;
      });
    });
  }

})(jQuery);
