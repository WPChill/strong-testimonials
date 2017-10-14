/**
 * Component controller
 */

var strongController = {

  config: {},

  setup: function (settings) {
    this.config = jQuery.extend(this.defaults, settings)
  },

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
  observeDOMForAttrChanged: function (obj, callback) {
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
  observeDOMForAddedNodes: function (obj, callback) {
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

    var self = this
    this.timerId = setTimeout(function tick () {
      console.log('tick')
      if (jQuery('.strong-view.slider-container').is(':visible')) {
        clearTimeout(self.timerId)
        self.timerId = null
        console.log('ready')
        self.initSliders()
      } else {
        self.timerId = setTimeout(tick, 1000)
      }
    }, 1000)
  },

  /**
   * Initialize controller
   */
  init: function () {
    console.log('strongController:init')

    var settings = {}
    /** @namespace window.strongControllerParms */
    if (typeof window.strongControllerParms !== 'undefined') {
      settings = window.strongControllerParms
    }
    this.setup(settings)

    this.initSliders()
  }

}


jQuery(document).ready(function ($) {

  // TODO Convert to a class
  // TODO Store target element in config
  strongController.init()

  switch (strongController.config.method) {
    case "1":
      // New method 1: Universal
      strongController.newTimer()
      break;
    case "2":
      // Observe a specific DOM element
      strongController.observeDOMForAttrChanged(document.getElementById('content'), strongController.initSliders)
      break;
    case "3":
      // Observe a specific DOM element on a timer
      // Calling initSliders here is too soon; the transition is not complete yet.
      strongController.observeDOMForAddedNodes(document.getElementById('content'), strongController.newTimer)
      break;
    case "4": // TODO Test
      // The theme/plugin uses a custom jQuery plugin that emits an event.
      // Need to know: Pjax container id/class, event name
      // For example:
      $('.pjax-container').on('pjax:end', strongController.initSliders)
      break;
    case "5":
      // The theme/plugin uses a dispatcher or event emitter.
      if (typeof Barba === 'object' && Barba.hasOwnProperty('Dispatcher')) {
        // Barba.Dispatcher.on('transitionCompleted', strongController.initSliders)
      }
      // other examples:
      // ee.addListener('addStuff', strongController.initSliders)
      break;
    default:
      // no Pjax support
  }

})
