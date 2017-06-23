/**
 * Slider Handler
 */

jQuery(document).ready(function ($) {

  // Load up our slideshows
  var strongSlideshows = $('.strong-view.slider-container')

  strongSlideshows.each(function () {
    var $that = $(this)
    $that.imagesLoaded(function () {
      $that.strongSlider()
    })
  })

})
