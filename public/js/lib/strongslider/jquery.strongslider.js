/**
 * Strong Slider
 *
 */

;(function ($) {

  $.fn.strongSlider = function () {

    var userSettings

    // Get unique slider variable from container
    var settingsVar = this.data('slider-var')

    // Parse and convert settings
    var parseSettings = function (parms) {
      if ('none' == parms.mode) {
        parms.mode = 'fade'
        parms.speed = 0
      }

      return {
        // Basic settings
        slideSelector: 'div.t-slide',
        mode: parms.mode,
        auto: true,  // the master switch
        speed: parseInt(parms.speed),
        pause: parseInt(parms.pause),

        // Behavior
        autoHover: 1 === parseInt(parms.autoHover),
        autoStart: 1 === parseInt(parms.autoStart),
        stopAutoOnClick: 1 === parseInt(parms.stopAutoOnClick),
        adaptiveHeight: 1 === parseInt(parms.adaptiveHeight),
        adaptiveHeightSpeed: parseInt(parms.adaptiveHeightSpeed),
        stretch: parseInt(parms.stretch),

        // Next / Prev
        controls: 1 === parseInt(parms.controls),
        prevText: parms.prevText,
        nextText: parms.nextText,

        // Start / Stop
        autoControls: 1 === parseInt(parms.autoControls),
        startText: parms.startText,
        stopText: parms.stopText,
        // autoControlsCombine: false,
        autoControlsCombine: 1 === parseInt(parms.autoControlsCombine),

        // Pager
        pager: 1 === parseInt(parms.pager),
        buildPager: 'icons' === parms.buildPager ? function (slideIndex) {
          return ''
        } : null,

        fullSetButtons: 1 === parseInt(parms.fullSetButtons),
        fullSetText: 1 === parseInt(parms.fullSetText),
        simpleSetText: 1 === parseInt(parms.simpleSetText),
        simpleSetPager: 1 === parseInt(parms.simpleSetPager)
      }
    }

    // Parse user settings
    if (typeof( window[settingsVar] ) !== 'undefined') {
      userSettings = parseSettings(window[settingsVar])
    }

    // Merge user settings onto defaults
    var settings = $.extend({}, userSettings)

    // Instantiate slider object
    var slider = this.children('.wpmslider-wrapper').wpmSlider(settings)

    // Custom control sets
    if (settings.fullSetButtons || settings.fullSetText) {
      // Add prev/next buttons
      this.find('.wpmslider-controls')
        .addClass('wpmslider-has-controls-full')
        .append('<div class="wpmslider-controls-full"><div class="wpmslider-controls-full-item"><a class="wpmslider-next">' + settings.nextText + '</a></div></div>')
        .prepend('<div class="wpmslider-controls-full"><div class="wpmslider-controls-full-item"><a class="wpmslider-prev">' + settings.prevText + '</a></div></div>')

      // Bind new event handlers
      this.find('.wpmslider-next', '.wpmslider-controls-full').on('click', function () {
        slider.goToNextSlide()
        slider.stopAuto()
      })
      this.find('.wpmslider-prev', '.wpmslider-controls-full').on('click', function () {
        slider.goToPrevSlide()
        slider.stopAuto()
      })
    }

    // Move <next> to bookend pagination
    if (settings.pager && ( settings.simpleSetText || settings.simpleSetPager )) {
      this.find('.wpmslider-next').appendTo(this.find('.wpmslider-controls')).wrap('<div class="wpmslider-controls-direction"></div>')
    }

    // Listen for orientation changes
    window.addEventListener('orientationchange', function () {
      slider.resetHeight()
    }, false)

    // Listen for window resize or emulator device change
    var updateLayout = _.debounce(function (e) {
      slider.reloadSlider()
    }, 250)

    window.addEventListener('resize', updateLayout, false)

    return this
  }

})(jQuery)
