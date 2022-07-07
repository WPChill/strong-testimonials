jQuery(document).ready(function($){

    // Dismiss challenge on cancel
$('body').on('click','#wpmtst-challenge-close',function (e) {
    e.preventDefault();
    set_challenge_hidden()

});

// Dismiss challenge on start
$('body').on('click',' #wpmtst-challenge-button',function (e) {
    e.preventDefault();
    set_challenge_hidden();
    location.href = $(this).attr('href');

});

function set_challenge_hidden(){
    jQuery.ajax({
        url: wpmtstChallenge.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'wpmtst_challenge_hide',
            'nonce' : wpmtstChallenge.nonce
        },
        success: function( ) {
            $( '.wpmtst-challenge-wrap' ).fadeOut( 200, function() {
                $( '.wpmtst-challenge-wrap' ).remove();
            });
        }
    });
}
});