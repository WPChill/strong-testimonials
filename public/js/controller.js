/**
 * Component controller
 *
 * @namespace window.strongControllerParms
 */

'use strict'

var strongController = {

  defaults: {
    method: '',
    script: '',
    // elementId: 'content', // normally holds all page content
    elementId: 'primary', // used in our Barba example
    containerId: "main",
    debug: false
  },

  config: {},
  
  debug: false,

  setup: function (settings) {
    this.config = jQuery.extend({}, this.defaults, settings)
    // Convert strings to booleans
    this.debug = this.config.debug = !!this.config.debug
  },

  mutationObserver: window.MutationObserver || window.WebKitMutationObserver,

  eventListenerSupported: window.addEventListener,

  checkInit: function () {
    return jQuery(".strong-view[data-state='idle']").length
  },

  log: function () {
    if (this.debug) {
      if (arguments.length === 1)
        console.log(arguments[0])
      else if (arguments.length === 2)
        console.log(arguments[0], arguments[1])
      else
        console.log(arguments)
    }
  },

  /**
   * Initialize sliders.
   */
  initSliders: function () {
    var sliders = jQuery(".strong-view.slider-container[data-state='idle']")
    this.log('sliders found:', sliders.length)
    if (sliders.length) {
      sliders.strongSlider()
    }
  },

  /**
   * Initialize paginated views.
   */
  initPaginated: function () {
    var pagers = jQuery(".strong-pager[data-state='idle']")
    this.log('pagers found:', pagers.length)
    if (pagers.length) {
      pagers.strongPager()
    }
  },

  /**
   * Initialize layouts.
   */
  initLayouts: function () {
    /*
     * Masonry
     */
    var grids = jQuery(".strong-view[data-state='idle'] .strong-masonry")
    this.log('Masonry found:', grids.length)
    if (grids.length) {
      // Add our element sizing.
      grids.prepend('<div class="grid-sizer"></div><div class="gutter-sizer"></div>')

      // Initialize Masonry after images are loaded.
      grids.imagesLoaded(function () {
        grids.masonry({
          columnWidth: '.grid-sizer',
          gutter: '.gutter-sizer',
          itemSelector: '.testimonial',
          percentPosition: true
        })
        grids.closest('.strong-view').attr('data-state', 'init')
      })
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
          strongController.log('mutation observed', mutations)
          for (var i=0; i < mutations[0].addedNodes.length; i++) {
            if (mutations[0].addedNodes[i].id === strongController.config.containerId) {
              strongController.log('+ added:', strongController.config.containerId)
              callback()
            }
          }
        }
      })
      // have the observer observe obj for changes
      obs.observe(obj, {childList: true, subtree: true})

    } else if (this.eventListenerSupported) {

      obj.addEventListener('DOMNodeInserted', function(e) {
        /** currentTarget **/
        if ( e.currentTarget.id === obj.id ) {
          strongController.log('DOMNodeInserted:', e.currentTarget.id)
          callback()
        }
      }, false)

    }
  },

  /**
   * Timer variables
   */
  intervalId: null,
  timeoutId: null,

  /**
   * Set up interval
   */
  newInterval: function () {
      strongController.intervalId = setInterval(function tick () {
        strongController.log('checkInit', strongController.checkInit())

        // Creating an artificial event by checking for uninitialized components (sliders, paginated, layouts)
        if (strongController.checkInit()) {
          strongController.start()
        }
      }, 500)
  },

  /**
   * Set up timeout
   */
  newTimeout: function () {
      strongController.timeoutId = setTimeout(function tick () {
        strongController.log('checkInit', strongController.checkInit())

        // Creating an artificial event by checking for uninitialized components (sliders, paginated, layouts)
        if (strongController.checkInit()) {
          strongController.start()
        }
      }, 500)
  },

  /**
   * Initialize controller.
   */
  init: function () {
    jQuery(document).focus() // if dev console open
    this.log('strongController init')

    var settings = {}
    if (typeof window.strongControllerParms !== 'undefined') {
      settings = window.strongControllerParms
    } else {
      this.log('settings not found')
    }
    this.setup(settings)
    this.log('config', this.config)
  },

  /**
   * Start components.
   */
  start: function() {
    strongController.log('start')
    strongController.initSliders()
    strongController.initPaginated()
    strongController.initLayouts()
  },

  /**
   * Listen.
   */
  listen: function() {
    this.log('listen')

    switch (this.config.method) {
      case 'universal':
        // Set a timer to check for idle components.
        this.newInterval()
        break

      case 'nodes_added':
        // Observe a specific DOM element on a timer.
        // Calling start() here is too soon; the transition is not complete yet.
        this.observeDOMForAddedNodes(document.getElementById(this.config.elementId), this.newTimeout)
        break

      case 'event':
        // The theme/plugin uses an event emitter.

        // jQuery Pjax -!- Not working in any theme tested yet -!-
        // document.addEventListener('pjax:end', this.start)

        // Pjax by MoOx
        // @link https://github.com/MoOx/pjax
        document.addEventListener('pjax:success', this.start)

        // Ajax Pagination and Infinite Scroll by Malinky
        // @link https://wordpress.org/plugins/malinky-ajax-pagination/
        document.addEventListener('malinkyLoadPostsComplete', this.start);
        break

      case 'script':
        // The theme/plugin uses a dispatcher.

        // Barba
        // @link http://barbajs.org/
        if (typeof Barba === 'object' && Barba.hasOwnProperty('Dispatcher')) {
          Barba.Dispatcher.on('transitionCompleted', this.start)
        }
        break

      default:
      // no Pjax support
    }
  }

}

jQuery(document).ready(function ($) {
// document.addEventListener("DOMContentLoaded", function(event) {

  // Initialize controller.
  strongController.init()

  // Start components.
  strongController.start()

  // Listen.
  strongController.listen()

})
