jQuery(document).ready(function($) {
	$(".notice-dismiss",".wpmtst.notice").click(function(){
		$.get( ajaxurl, { 'action': 'wpmtst_dismiss_notice' }, function( response ) {
		});
	});
});
