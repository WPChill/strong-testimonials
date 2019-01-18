/**
 * readmore.js
 *
 * @param toggleButtonText
 * @param toggleButtonText.dataset
 * @param toggleButtonText.dataset.moreText
 * @param toggleButtonText.dataset.lessText
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
      event.target.parentElement.querySelector('.readmore-toggle').style.display = 'inline';

      // Show ellipsis
      var ellipsis = event.target.parentElement.querySelector('.ellipsis');
      if (ellipsis) {
        ellipsis.style.display = 'inline';
      }

      fireCustomEvent();
    }
  };

  var fireCustomEvent = function () {
    window.dispatchEvent(new Event('toggleFullContent'));
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

      toggleButton.addEventListener('click', function () {

        var fullTextWrapper = this.parentElement.querySelector('.readmore-content');
        if (!fullTextWrapper) {
          return;
        }

        var ellipsis = this.parentElement.querySelector('.ellipsis');
        var toggleButtonText = this.querySelector('.readmore-text');

        // change attributes and text if full text is shown/hidden
        if (fullTextWrapper.hasAttribute('hidden')) {

          // show

          // 1. remove hidden attribute so we can animate it
          fullTextWrapper.removeAttribute('hidden');

          // 2. update toggle link
          // change text (may be blank)
          toggleButtonText.innerText = toggleButtonText.dataset.lessText;
          toggleButton.setAttribute('aria-expanded', true);
          if( ellipsis ) {
            ellipsis.style.display = 'none';
          }

          // 3. animate it
          fullTextWrapper.classList.add('fadeInDown');
          fullTextWrapper.classList.remove('fadeOutUp');
          fullTextWrapper.classList.remove('faster');

          fireCustomEvent();

        } else {

          // hide

          // 1. update toggle link
          // hide link during transition
          toggleButton.style.display = 'none'
          toggleButton.setAttribute('aria-expanded', false);
          // change link text (may be blank)
          toggleButtonText.innerText = toggleButtonText.dataset.moreText;

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
