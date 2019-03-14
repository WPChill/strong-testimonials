export default class Notice {

	constructor($element){
		this.$element = $element;
		this.key = $element.data('key');
		this.nonce = $element.data('nonce');

		jQuery(document).on( 'click', '.wpmtst-notice .notice-dismiss', () => this.onDismissClick() );
	}

	onDismissClick() {
		jQuery.ajax({
			type: "POST",
			data : { action: "wpmtst_dismiss_notice", nonce: this.nonce, key: this.key },
			url : ajaxurl
		});
	}

}


