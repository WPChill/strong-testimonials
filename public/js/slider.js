/**
 * Slider Handler
 */

//jQuery(window).on("load", function () {
jQuery(document).ready(function ($) {

  // Load up our slideshows
  var strongSlideshows = jQuery('.strong-view.slider-container');

  strongSlideshows.each(function () {
    // jQuery(this).strongSlider();
    var $that = $(this);
    $that.imagesLoaded(function() {
      $that.strongSlider();
    });
  });

});
