/* eslint-disable no-undef */
( function ( $ ) {
	$( document ).ready( function () {
		// Dismiss challenge on cancel
		$( 'body' ).on( 'click', '#wpmtst-challenge-close', function ( e ) {
			e.preventDefault();
			setChallengeHidden();
		} );

		// Dismiss challenge on start
		$( 'body' ).on( 'click', ' #wpmtst-challenge-button', function ( e ) {
			e.preventDefault();
			setChallengeHidden();
			location.href = $( this ).attr( 'href' );
		} );

		function setChallengeHidden() {
			jQuery.ajax( {
				url: wpmtstChallenge.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'wpmtst_challenge_hide',
					nonce: wpmtstChallenge.nonce,
				},
				success() {
					$( '.wpmtst-challenge-wrap' ).fadeOut( 200, function () {
						$( '.wpmtst-challenge-wrap' ).remove();
					} );
				},
			} );
		}
	} );
} )( jQuery );
