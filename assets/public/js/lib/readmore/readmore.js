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
	 * Do stuff at the end of animation.
	 */
	var onAnimationEnd = function (event) {
		if (event.type === 'animationend' && event.animationName === 'fadeOutUp') {

			// Add `hidden` attribute
			event.target.setAttribute('hidden', 'true');
            event.target.style.display = 'none';

			// Show read-more link
			event.target.parentElement.parentElement.querySelector('.readmore-toggle').style.display = 'inline';

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
		document.addEventListener('transitionend', function (event) {
			if (event.target.matches('.readmore-content')) {
				onAnimationEnd(event);
			}
		}, false);

		document.addEventListener('animationend', function (event) {
			if (event.target.matches('.readmore-content')) {
				onAnimationEnd(event);
			}
		}, false);

		// Listen to each readmore link.
		document.addEventListener('click', function (event) {


			if (!event.target.matches('.readmore-text')) {
				return;
			}

			var toggleButtonText = event.target;
			var theContainer = jQuery( event.target ).parents( '.wpmtst-testimonial-content' );
			var toggleButton = toggleButtonText.parentElement;
			var excerptWrapper = jQuery( theContainer ).find( '.readmore-excerpt' );

			var fullTextWrapper = jQuery( theContainer ).find( '.readmore-content' )[0];
			var ellipsis = jQuery( theContainer ).find( '.ellipsis' )[0];
			var allHtml = false;

			if ( excerptWrapper.hasClass( 'all-html' ) ) {
				allHtml = true;
			}

			if ( !fullTextWrapper ) {
				return;
			}

			// change attributes and text if full text is shown/hidden
			if (fullTextWrapper.hasAttribute('hidden')) {
				// show
				// 1. remove hidden attribute so we can animate it
				fullTextWrapper.removeAttribute('hidden');
                fullTextWrapper.style.display = 'inline';

				// 2. update toggle link
				// change text (may be blank)
				toggleButtonText.innerText = toggleButtonText.dataset.lessText;
				toggleButton.setAttribute('aria-expanded', true);
				if (ellipsis) {
					ellipsis.style.display = 'none';
				}

				// 3. animate it
				fullTextWrapper.classList.add( 'fadeInDown' );
				fullTextWrapper.classList.remove( 'fadeOutUp' );
				fullTextWrapper.classList.remove( 'faster' );

				excerptWrapper[0].style.display = 'none';

				fireCustomEvent();

			} else {
				// hide
				// 1. update toggle link

                fullTextWrapper.style.display = 'none';
				fullTextWrapper.setAttribute('hidden', true);

				toggleButton.setAttribute( 'aria-expanded', false );
				// change link text (may be blank)
				toggleButtonText.innerText = toggleButtonText.dataset.moreText;

				// 2. animate it
				fullTextWrapper.classList.add( 'fadeOutUp' );
				fullTextWrapper.classList.add( 'faster' );
				fullTextWrapper.classList.remove( 'fadeInDown' );

				excerptWrapper[0].style.display = 'block';

				// 3. Add back the elipsis if needed.
				if (ellipsis) {
					ellipsis.style.display = 'inline';
				}

				// 4. do stuff at end of animation (the event listener above)
				fireCustomEvent();
			}

		}, false);



	}

})();
