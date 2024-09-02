/**
 * Component Controller
 *
 * Version 1.2
 * For Strong Testimonials version 2.31
 *
 * @namespace window.strongControllerParms
 */

 'use strict';
 var debugit = false;
 
 var strongController = {
 
   grids: {},
 
   iframes: {},
 
   defaults: {
     initializeOn: 'windowLoad',
     method: '',
     universalTimer: 500,
     observerTimer: 500,
     containerId: 'page',    // = what we listen to  (try page > content > primary)
     addedNodeId: 'content', // = what we listen for
     event: '',
     script: '',
     debug: false
   },
 
   config: {},
 
   setup: function (settings) {
     // Convert strings to integers
     settings.universalTimer = parseInt(settings.universalTimer);
     settings.observerTimer = parseInt(settings.observerTimer);
     // Convert strings to booleans
     settings.debug = !!settings.debug;
     debugit = settings.debug;
     this.config = jQuery.extend({}, this.defaults, settings);
   },
 
   mutationObserver: window.MutationObserver || window.WebKitMutationObserver,
 
   eventListenerSupported: window.addEventListener,
 
   checkInit: function () {
     return jQuery('.strong-view[data-state="idle"]').length;
   },
 
   /**
    * Initialize sliders.
    */
   initSliders: function () {
     var sliders = jQuery('.strong-view.slider-container[data-state="idle"]');
     if (debugit) console.log('sliders found:', sliders.length);
     if (sliders.length) {
       // Initialize independently
       sliders.each(function () {
 
         var $slider = jQuery(this);
 
         // don't init if it's only a single testimonial
         var count = $slider.data('count');
         if( count !== undefined && count === 1 ) {
             return;
         }
 
         $slider.strongSlider();
       });
     }
   },
 
   /**
    * Initialize paginated views.
    */
   initPagers: function () {
     var pagers = jQuery('.strong-pager[data-state="idle"]');
     if (debugit) console.log('pagers found:', pagers.length);
     if (pagers.length) {
       pagers.each(function () {
         jQuery(this).strongPager();
       });
     }
   },
 
   /**
    * Initialize layouts.
    */
   initLayouts: function () {
     /*
      * Masonry
      */
     this.grids = jQuery('.strong-view[data-state="idle"] .strong-masonry');
     if (debugit) console.log('Masonry found:', this.grids.length);
     if (this.grids.length) {
       // Add our element sizing.
       this.grids.prepend('<div class="grid-sizer"></div><div class="gutter-sizer"></div>');
 
       // Initialize Masonry after images are loaded.
       this.grids.imagesLoaded(function () {
         strongController.grids.masonry({
           columnWidth: '.grid-sizer',
           gutter: '.gutter-sizer',
           itemSelector: '.wpmtst-testimonial',
           percentPosition: true
         });
 
         strongController.grids.closest('.strong-view').attr('data-state', 'init');
       });
     }
 
   },
 
   /**
    * Initialize form validation.
    */
   initForm: function () {
    var forms = jQuery('.strong-form[data-state="idle"]');
    var messages = jQuery('.wpmtst-testimonial-success');
    if (debugit) console.log('forms found:', forms.length);
    if (debugit) console.log('messages found:', messages.length);
    if (forms.length || messages.length) {
        jQuery( forms ).each(function() {
           var eachform = new strongValidation(this);
        });
       // initialize Captcha plugins here
     }
   },
 
   /**
    * Look for iframes.
    */
   initIframes: function () {
     this.iframes = jQuery('iframe');
   },
 
   /**
    * Listen for custom events from other scripts.
    */
   customEvents: function () {
     addEventListener( 'toggleFullContent', function (event) {
       if (strongController.grids.length) {
         strongController.grids.masonry();
       }
     });
   },
 
   /**
    * Create observer that reacts to nodes added or removed.
    *
    * https://stackoverflow.com/a/14570614/51600
    */
   observer: function (obj, callback) {
     if (this.mutationObserver) {
 
       // Define a new observer
       var obs = new this.mutationObserver(function (mutations) {
         // Loop through mutations
         for (var i = 0; i < mutations.length; i++) {
           if (mutations[i].addedNodes.length) {
             if (debugit) console.log('mutation observed', mutations);
             // Loop through added nodes
             for (var j = 0; j < mutations[i].addedNodes.length; j++) {
               if (mutations[i].addedNodes[j].id === strongController.config.containerId) {
                 if (debugit) console.log('+', strongController.config.containerId);
                 callback();
                 return;
               }
             }
           }
         }
       });
       // Have the observer observe obj for changes
       obs.observe(obj, {childList: true, subtree: true});
 
     } else if (this.eventListenerSupported) {
 
       obj.addEventListener('DOMNodeInserted', function (e) {
         /** currentTarget **/
         if (e.currentTarget.id === obj.id) {
           if (debugit) console.log('DOMNodeInserted:', e.currentTarget.id);
           callback();
         }
       }, false);
 
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
       if (debugit) console.log('tick > checkInit', strongController.checkInit());
 
       // Check for uninitialized components (sliders, paginated, layouts)
       if (strongController.checkInit()) {
         strongController.start();
       }
     }, strongController.config.universalTimer);
   },
 
   /**
    * Set up timeout
    */
   newTimeout: function () {
     strongController.timeoutId = setTimeout(function tick () {
       if (debugit) console.log('tick > checkInit', strongController.checkInit());
 
       // Check for uninitialized components (sliders, paginated, layouts)
       if (strongController.checkInit()) {
         strongController.start();
       }
     }, strongController.config.observerTimer);
   },
 
   /**
    * Initialize controller.
    */
   init: function () {
     if (debugit) console.log('strongController init');
 
     // Get settings
     var settings = {};
     if (typeof window.strongControllerParms !== 'undefined') {
       settings = window.strongControllerParms;
     } else {
       if (debugit) console.log('settings not found');
     }
 
     // Configure
     this.setup(settings);
     if (debugit) console.log('config', this.config);
 
     /*
      * Start on specific event
      */
     if ('documentReady' === this.config.initializeOn) {
 
       jQuery(document).ready(function () {
         if (debugit) console.log('document ready');
         // Start components.
         strongController.start();
         // Listen.
         strongController.listen();
       });
 
     } else { // Fail-safe
 
       jQuery(window).on('load', function () {
         if (debugit) console.log('window load');
         // Start components.
         strongController.start();
         // Listen.
         strongController.listen();
       });
 
     }
 
     // Regardless of initializeOn setting, check for embeds in Masonry on window load.
     jQuery(window).on('load', function () {
       strongController.listenForIframeReady();
     });
     
     jQuery('textarea.max-length, input.text.max-length').on('keyup', function() {
         var maxLength =  jQuery(this).attr('maxlength');
         var textLength = jQuery(this).val().length;
         if (maxLength !== null) {
             jQuery(this).parent().find('.max-length-counter').html(textLength + ' characters out of ' + maxLength);
         }
     });
 
   },
 
   /**
    * Start components.
    */
   start: function () {
     if (debugit) console.log('start');
     strongController.initSliders();
     strongController.initPagers();
     strongController.initLayouts();
     strongController.initForm();
     strongController.initIframes();
     strongController.customEvents();
   },
 
   /**
    * Listen.
    */
   listen: function () {
     if (debugit) console.log('listen');
 
     switch (this.config.method) {
       case 'universal':
         // Set a timer to check for idle components.
         this.newInterval();
         break;
 
       case 'observer':
         // Observe a specific DOM element on a timer.
         // Calling start() here is too soon; the transition is not complete yet.
         this.observer(document.getElementById(this.config.containerId), this.newTimeout);
         break;
 
       case 'event':
         // The theme/plugin uses an event emitter.
 
         // jQuery Pjax -!- Not working in any theme tested yet -!-
         // event name = pjax:end
 
         // Pjax by MoOx
         // @link https://github.com/MoOx/pjax
         // event name = pjax:success
 
         // Ajax Pagination and Infinite Scroll by Malinky
         // @link https://wordpress.org/plugins/malinky-ajax-pagination/
         // event name = malinkyLoadPostsComplete
 
         document.addEventListener(this.config.event, this.start);
         break;
 
       case 'script':
         // The theme/plugin uses a dispatcher.
 
         switch (this.config.script) {
           case 'barba':
             // Barba
             // @link http://barbajs.org/
             if (typeof Barba === 'object' && Barba.hasOwnProperty('Dispatcher')) {
               Barba.Dispatcher.on('transitionCompleted', this.start);
             }
             break;
           default:
         }
         break;
 
       default:
       // no Pjax support
     }
   },
 
   /**
    * Listen.
    */
   listenForIframeReady: function () {
     if (debugit) console.log('listenForIframeReady');
 
     if (strongController.iframes.length && strongController.grids.length) {
 
       strongController.iframes.ready(function () {
         // still needs a moment to render
         setTimeout(function () {
           strongController.grids.masonry();
           if (debugit) console.log('listenForIframeReady', 'timeout 1');
         }, 1000);
         // just in case
         setTimeout(function () {
           strongController.grids.masonry();
           if (debugit) console.log('listenForIframeReady', 'timeout 2');
         }, 2000);
       });
 
     } else {
 
       if (debugit) console.log('listenForIframeReady', 'no iframes or Masonry found');
 
     }
   }
 };
 
 // Initialize controller.
 strongController.init();
 
 
 
