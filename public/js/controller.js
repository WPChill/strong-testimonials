/**
 * Component controller
 */

var strongController = {

  config: {},

  setup: function (settings) {
    this.config = jQuery.extend({}, settings)
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
    var strongSlideshows = jQuery(".strong-view.slider-container[data-state!='init']")

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
    if (strongController.timerId) return

    strongController.timerId = setTimeout(function tick () {
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
   * Initialize controller.
   */
  init: function () {
    console.log('strongController:init')

    var settings = {}
    /** @namespace window.strongControllerParms */
    if (typeof window.strongControllerParms !== 'undefined') {
      settings = window.strongControllerParms
    }
    this.setup(settings)
    console.log('strongController:config', this.config)
  },

  /**
   * Start components.
   */
  start: function(){
    console.log('strongController:start')
    this.initSliders()
  },

  /**
   * Listen.
   */
  listen: function() {
    console.log('strongController:listen')

    switch (this.config.method) {
      case 'universal':
        // Set a timer to check for uninitialized components.
        this.newTimer()
        break

      case 'attr_changed':
        // Observe a specific DOM element.
        this.observeDOMForAttrChanged(document.getElementById(this.config.elementId), this.initSliders)
        break

      case 'nodes_added':
        // Observe a specific DOM element on a timer.
        // Calling initSliders here is too soon; the transition is not complete yet.
        this.observeDOMForAddedNodes(document.getElementById(this.config.elementId), this.newTimer)
        break

      case 'event':
        // The theme/plugin uses an event emitter.

        // jQuery Pjax -!- Not working in any theme tested yet -!-
        // document.addEventListener('pjax:end', strongController.initSliders)

        // Pjax by MoOx
        // @link https://github.com/MoOx/pjax
        document.addEventListener('pjax:success', this.initSliders)

        // Ajax Pagination and Infinite Scroll by Malinky
        // @link https://wordpress.org/plugins/malinky-ajax-pagination/
        document.addEventListener('malinkyLoadPostsComplete', this.initSliders);
        break

      case 'script':
        // The theme/plugin uses a dispatcher.

        // Barba
        // @link http://barbajs.org/
        if (typeof Barba === 'object' && Barba.hasOwnProperty('Dispatcher')) {
          Barba.Dispatcher.on('transitionCompleted', this.initSliders)
        }
        break

      default:
      // no Pjax support
    }
  }

}

// jQuery(document).ready(function ($) {
document.addEventListener("DOMContentLoaded", function(event) {

  // Initialize controller.
  strongController.init()

  // Start components.
  strongController.start()

  // Listen.
  strongController.listen()

})
