/**
 * Component controller
 */

var strongController = {

  mutationObserver: window.MutationObserver || window.WebKitMutationObserver,

  eventListenerSupported: window.addEventListener,

  /**
   * Initialize sliders
   */
  initSliders: function () {
    console.log('initSliders')
    // Load up our slideshows
    // var strongSlideshows = jQuery('.strong-view.slider-container')
    var strongSlideshows = jQuery('.strong-view.slider-container[data-state!=\'init\']')

    strongSlideshows.each(function () {
      var $that = jQuery(this)
      $that.imagesLoaded(function () {
        $that.strongSlider()
        console.log('- init -')
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
  observeDOMForAttributes: function (obj, callback) {
    if (this.mutationObserver) {

      // define a new observer
      var obs = new this.mutationObserver(function (mutations) {
        console.log('mutation observed')
        callback()
      })
      // have the observer observe obj for changes
      obs.observe(obj, {childList: false, attributes: true, subtree: false, attributeFilter: ['data-pjax']})

    } else if (this.eventListenerSupported) {

      obj.addEventListener('DOMAttrModified', function(e){
        /** currentTarget **/
        if ( e.currentTarget.id === obj.id && e.attrName === 'data-pjax' ) {
          console.log('DOMAttrModified', e.target.id, e.attrName, e.prevValue, e.newValue)
          callback()
        }
      }, false)

    }
  },

  /**
   * Create observer that reacts to nodes added or removed.
   *
   * https://stackoverflow.com/a/14570614/51600
   */
  observeDOMForAddedOrRemoved: function (obj, callback) {
    if (this.mutationObserver) {

      // define a new observer
      var obs = new this.mutationObserver(function (mutations) {
        if (mutations[0].addedNodes.length) {
          console.log('mutation observed')
          callback()
        }
      })
      // have the observer observe obj for changes
      obs.observe(obj, {childList: true, subtree: true})

    } else if (this.eventListenerSupported) {

      obj.addEventListener('DOMNodeInserted', function(e) {
        /** currentTarget **/
        if ( e.currentTarget.id === obj.id ) {
          console.log('DOMNodeInserted:', e.currentTarget.id)
          callback()
        }
      }, false)

    }
  },

  /**
   * Timer variable
   */
  timerId: null,

  /**
   * Set up timer
   */
  newTimer: function () {
      if (this.timerId) return

      this.timerId = setTimeout(function tick () {
        console.log('tick')
        if (jQuery('.strong-view.slider-container').is(':visible')) {
          clearTimeout(strongController.timerId)
          strongController.timerId = null
          console.log('ready')
          strongController.initSliders()
        } else {
          strongController.timerId = setTimeout(tick, 1000)
        }
      }, 1000)
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
    this.observeDOMForAttributes(document.getElementById('content'), strongController.initSliders)

    // Method 3
    /**
     * Observe a specific DOM element on a timer
     */
    // Calling initSliders here is too soon; the transition is not complete yet.
    // this.observeDOMForAddedOrRemoved(document.getElementById('content'), strongController.newTimer)

    // Universal solution: An independent timer
    // this.newTimer()

  }

}


jQuery(document).ready(function ($) {

  strongController.init()

  // Method 2 - The theme/plugin uses a dispatcher or event emitter.
  if (typeof Barba === 'object' && Barba.hasOwnProperty('Dispatcher')) {
    // Barba.Dispatcher.on('transitionCompleted', strongController.initSliders)
  }
  // other examples:
  // ee.addListener('addStuff', strongController.initSliders)

  // Method 4 - The theme/plugin uses a custom jQuery plugin that emits an event.
  // Need to know: Pjax container id/class, event name
  // For example:
  $('.pjax-container').on('pjax:end', strongController.initSliders)

})
