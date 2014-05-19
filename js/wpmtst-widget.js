/*
	wpmtst-widget.js
	Strong Testimonials > widget text slider settings
*/
jQuery(document).ready(function($) { 
	$("#tcycle").cycle({ 
		slides       : '> div',
		fx           : tcycle.effect,
		speed        : parseInt( tcycle.speed ),
		timeout      : parseInt( tcycle.timeout ),
		pauseOnHover : "1" == tcycle.pause ? true : false,
	});
});
