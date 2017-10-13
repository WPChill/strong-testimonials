/**
 * Component controller
 */

var strongController = {

  /**
   * Initialize sliders
   */
  initSliders: function () {
    console.log('initSliders')
    // Load up our slideshows
    // var strongSlideshows = jQuery('.strong-view.slider-container')
    var strongSlideshows = jQuery(".strong-view.slider-container[data-state!='init']")

    strongSlideshows.each(function () {
      var $that = jQuery(this)
      $that.imagesLoaded(function () {
        $that.strongSlider()
      })
    })
  },

  initPagination: function () {
    console.log('initPagination')
  },

  initLayouts: function () {
    console.log('initLayouts')
  },

  /**
   * Create observer that reacts to a change in attributes, e.g. data-pjax.
   *
   * https://stackoverflow.com/a/14570614/51600
   */
  observeDOMForAttributes: (function () {
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver

    return function (obj, callback, props) {
      if (MutationObserver) {
        // define a new observer
        var obs = new MutationObserver(function (mutations) {
          console.log('mutation observed')
          callback()
        })
        // have the observer observe obj for changes
        obs.observe(obj, {childList: false, attributes: true, subtree: false})
      }
    }
  })(),

  /**
   * Create observer that reacts to nodes added or removed.
   *
   * https://stackoverflow.com/a/14570614/51600
   */
  observeDOMForAddedOrRemoved: (function () {
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver

    return function (obj, callback) {
      if (MutationObserver) {
        // define a new observer
        var obs = new MutationObserver(function (mutations) {
          if (mutations[0].addedNodes.length || mutations[0].removedNodes.length) {
            console.log('mutation observed')
            callback()
          }
        })
        // have the observer observe obj for changes
        obs.observe( obj, {childList:true, subtree:true});
      }
    }
  })(),

  /**
   * Timer variable
   */
  timerId: null,

  /**
   * Set up timer
   */
  newTimer: function() {
    if (this.timerId) return

    this.timerId = setTimeout(function tick () {
      console.log('tick');
      if (jQuery('.strong-view.slider-container').is(":visible")) {
        clearTimeout(strongController.timerId)
        strongController.timerId = null
        console.log('ready');
        strongController.initSliders()
      } else {
        strongController.timerId = setTimeout(tick, 1000);
      }
    }, 1000);
  },

  /**
   * Initialize controller
   */
  init: function () {
    console.log('strongController:init')
    this.initSliders()

    // Method 1
    /**
     * Observe a specific DOM element
     */
    this.observeDOMForAttributes(document.getElementById('content'), function () {
        console.log('DOM changed')
        strongController.initSliders()
      })

    // Method 3
    /**
     * Observe a specific DOM element
     */
    this.observeDOMForAddedOrRemoved(document.getElementById('content'), function () {
      console.log('DOM changed')
      //strongController.newTimer()
    })
    /**
     * Set initial timer
     */
    //this.newTimer()

  }

}

jQuery(document).ready(function ($) {

  strongController.init()

  // Method 2
  if (typeof Barba === 'object') {
    //Barba.Dispatcher.on('transitionCompleted', strongController.initSliders)
  }

})
