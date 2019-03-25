export default class RangeSlider {

	constructor($element){
		this.$element = $element;
		this.$slider = this.$element.find('.wpmtst-range__slider');
		this.$minInput = this.$element.find('.wpmtst-range__min');
		this.$maxInput = this.$element.find('.wpmtst-range__max');

		this.initRangeSlider();
	}

	initRangeSlider() {
		this.$slider.slider({
			range: true,
			min: this.$slider.data('min'),
			max: this.$slider.data('max'),
			values: this.$slider.data('values').split(","),
			slide: ( event, ui ) => {
				this.$minInput.val( ui.values[ 0 ] );
				this.$maxInput.val( ui.values[ 1 ] );
			}
		});
	}
}


