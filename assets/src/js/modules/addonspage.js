export default class AddonsPage {

	constructor(){

		if( ! jQuery( 'body' ).hasClass( 'wpm-testimonial_page_strong-testimonials-addons' ) ) {
			return;
		}

		this.reloadButton = jQuery('#wpmtst-reload-extensions');

		//events
		this.reloadButton.on('click', (e) => this.onReloadExtensionsClick(e) );
	}

	onReloadExtensionsClick(e) {
		e.preventDefault();

		this.reloadButton.addClass( 'updating-message' );

	 	jQuery.ajax({
			type: "POST",
			data : { action: "wpmtst_reload_extensions", nonce: this.reloadButton.data('nonce') },
			url : ajaxurl,
			success: function( response ) {
				location.reload();
			}
		});
	}


}


