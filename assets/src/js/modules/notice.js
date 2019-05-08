export default class Notice {

	constructor($element){
		this.$element = $element;
		this.key = $element.data('key');
		this.nonce = $element.data('nonce');

		this.$element.on( 'click', '.notice-dismiss', () => this.onDismissClick() );
	}

	onDismissClick() {

		this.$element.remove();

		jQuery.ajax({
			type: "POST",
			data : { action: "wpmtst_dismiss_notice", nonce: this.nonce, key: this.key },
			url : ajaxurl
		});
	}

}


