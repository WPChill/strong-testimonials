export default class ItemCreation {

	constructor($element){
		this.$itemCreation = $element;
		this.$addButton = this.$itemCreation.next('input');
		this.$input = this.$addButton.next('input');
		this.fields = this.$input.val() === '' ? [] : JSON.parse( this.$input.val() );

		this.initSortable();

		//events
		this.$addButton.on('click', (e) => this.onAddButtonClick(e) );
		this.$itemCreation.on('click', '.wpmtst-item-creation__icon.delete', (e) => this.onDeleteItemClick(e) );
		this.$itemCreation.on('click', '.wpmtst-item-creation__icon.toggle', (e) => this.onToggleClick(e) );
		this.$itemCreation.on('change keyup paste', '.wpmtst-item-creation__field-property input', (e) => this.onFieldPropertyChange(e) );
	}

	initSortable() {
		this.$itemCreation.sortable({
			handle: ".handle",
			update: ( event, ui ) => this.onSortUpdate( event, ui )
		});
	}

	onFieldPropertyChange( e ) {
		let input = jQuery( e.target );
		let item = input.parents('.wpmtst-item-creation__item');
		let fieldPropertyName = input.parent().data('name');

		if( 'label' === fieldPropertyName ) {
			item.find('.wpmtst-item-creation__description').html( input.val() );
		}

		this.fields[ item.index() ][ fieldPropertyName ] = input.val();
		this.$input.val( JSON.stringify( this.fields ) );
	}

	onSortUpdate( event, ui ) {

		this.fields = [];
		let items = this.$itemCreation.find('.wpmtst-item-creation__item');

		items.each( (index) => {
			let field = {};
			let fieldProperties = items.eq( index ).find('.wpmtst-item-creation__field-property');

			fieldProperties.each( (index) => {

				let fieldProperty = fieldProperties.eq( index );
				let fieldPropertyName = fieldProperty.data('name');
				let fieldPropertyValue = fieldProperty.find('input').val();

				field[ fieldPropertyName ] = fieldPropertyValue;

			});

			this.fields.push( field );
			this.$input.val( JSON.stringify( this.fields ) );
		});

	}

	onAddButtonClick( e ) {
		this.fields.push( { value: '', label: 'Label' } );
		this.$input.val( JSON.stringify( this.fields ) ).trigger('change');

		this.$itemCreation.append( '<div class="wpmtst-item-creation__item"><div><div class="wpmtst-item-creation__link"><span class="wpmtst-item-creation__description">Label</span><div class="wpmtst-item-creation__controls"><span class="handle wpmtst-item-creation__icon" title="drag and drop to reorder"></span><span class="delete wpmtst-item-creation__icon" title="remove"></span></div><div class="wpmtst-item-creation__controls"><span class="toggle wpmtst-item-creation__icon" title="click to open or close"></span></div></div><div class="wpmtst-item-creation__field-properties"><div class="wpmtst-item-creation__field-property" data-name="value"><label>Value</label><input type="text" value=""></div><div class="wpmtst-item-creation__field-property" data-name="label"><label>Label</label><input type="text" value="Label"></div></div></div></div>' );
	}

	onDeleteItemClick( e ) {
 		let item = jQuery( e.target ).parents('.wpmtst-item-creation__item');

		//remove from array
		this.fields.splice( item.index(), 1 );
		this.$input.val( JSON.stringify( this.fields ) ).trigger('change');

		//remove from dom
		item.remove();
	}

	onToggleClick( e ) {
		console.log('toggle click');
		jQuery( e.target ).parents('.wpmtst-item-creation__item').toggleClass('wpmtst-item-creation__item--open');
	}

}


