/**
 * For the Masonry template.
 */

jQuery(document).ready(function ($) {

  var $grid = $('.strong-masonry')

  // Add our element sizing.
  $grid.prepend('<div class="grid-sizer"></div><div class="gutter-sizer"></div>')

  // Initialize Masonry after images are loaded.
  $grid.imagesLoaded(function () {
    $grid.masonry({
      columnWidth: '.grid-sizer',
      gutter: '.gutter-sizer',
      itemSelector: '.testimonial',
      percentPosition: true
    })
  })

})
