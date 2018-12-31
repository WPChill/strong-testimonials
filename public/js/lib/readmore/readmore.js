/**
 * readmore.js
 */
(function () {

  /*
   * forEach method
   * thanks https://toddmotto.com/ditch-the-array-foreach-call-nodelist-hack/
   */
  var forEach = function (array, callback, scope) {
    for (var i = 0; i < array.length; i++) {
      callback.call(scope, i, array[i]); // passes back stuff we need
    }
  };

  /*
   * Do stuff at the end of animation.
   */
  var onAnimationEnd = function (event) {
    if (event.type === 'animationend' && event.animationName === 'fadeOutUp') {
      // Add `hidden` attribute
      event.target.setAttribute('hidden', 'true');
      // Show read-more link
      event.target.parentElement.querySelector('.readmore').style.display = 'inline';
    }
  };

  // Only in modern browsers.
  if ('querySelector' in document && 'addEventListener' in window) {

    // Listen for an animation.
    var fullTextWrappers = document.querySelectorAll('.readmore-content');

    forEach(fullTextWrappers, function (index, fullTextWrapper) {
      fullTextWrapper.addEventListener('transitionend', onAnimationEnd);
      fullTextWrapper.addEventListener('animationend', onAnimationEnd);
    });

    // Listen to each button.
    var toggleButtons = document.querySelectorAll('.readmore-toggle');

    forEach(toggleButtons, function (index, toggleButton) {

      toggleButton.addEventListener('click', function (event) {

        var fullTextWrapper = this.parentElement.querySelector('.readmore-content');
        var ellipsis = this.parentElement.querySelector('.ellipsis');
        var toggleButtonText = this.querySelector('.readmore-text');

        // change attributes and text if full text is shown/hidden
        if (fullTextWrapper.hasAttribute('hidden')) {

          // show

          // 1. remove hidden attribute so we can animate it
          fullTextWrapper.removeAttribute('hidden');

          // 2. update toggle link
          toggleButtonText.innerText = 'Show Less';
          toggleButton.setAttribute('aria-expanded', true);
          ellipsis.style.display = 'none';

          // 3. animate it
          fullTextWrapper.classList.add('fadeInDown');
          fullTextWrapper.classList.remove('fadeOutUp');
          fullTextWrapper.classList.remove('faster');

        } else {

          // hide

          // 1. update toggle link
          // hide link during transition
          toggleButton.style.display = 'none';
          toggleButton.setAttribute('aria-expanded', false);
          // change link text
          toggleButtonText.innerText = 'Show More';
          // Show ellipsis
          ellipsis.style.display = 'inline';

          // 2. animate it
          fullTextWrapper.classList.add('fadeOutUp');
          fullTextWrapper.classList.add('faster');
          fullTextWrapper.classList.remove('fadeInDown');

          // 3. do stuff at end of animation (the event listener above)

        }

      });

    });

  }

})();
