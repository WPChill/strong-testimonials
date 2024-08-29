import './admin.scss';

/**
 * Strong Testimonials admin
 *
 * @namespace jQuery
 */

// Function to get the Max value in Array
Array.max = function ( array ) {
	return Math.max.apply( Math, array );
};

jQuery( document ).ready( function ( $ ) {
	// Convert "A String" to "a_string"
	function convertLabel( label ) {
		return label.replace( /\s+/g, '_' ).replace( /\W/g, '' ).toLowerCase();
	}

	// Remove invalid characters
	function removeSpaces( word ) {
		//return word.replace(/\s+/g, "_");
		return word.replace( /[^\w\s(?!\-)]/gi, '' );
	}

	$.fn.showInlineBlock = function () {
		return this.css( 'display', 'inline-block' );
	};

	/**
	 * ----------------------------------------
	 * General events
	 * ----------------------------------------
	 */

	// Add protocol if missing
	// Thanks http://stackoverflow.com/a/36429927/51600
	$( 'input[type=url]' ).on( 'change', function () {
		if ( this.value.length && ! /^https*:\/\//.test( this.value ) ) {
			this.value = 'http://' + this.value;
		}
	} );

	$( 'ul.ui-tabs-nav li a' ).on( 'click', function () {
		$( this ).trigger( 'blur' );
	} );

	$( '.focus-next-field' ).on( 'change', function ( e ) {
		if ( $( e.target ).is( ':checked' ) ) {
			$( e.target )
				.parent()
				.next()
				.find( 'input' )
				.focus()
				.trigger( 'select' );
		}
	} );

	// toggle screenshots
	$( '#toggle-screen-options' )
		.add( '#screenshot-screen-options' )
		.on( 'click', function ( e ) {
			$( this ).trigger( 'blur' );
			$( '#screenshot-screen-options' ).slideToggle();
		} );

	// toggle screenshots
	$( '#toggle-help' ).on( 'click', function ( e ) {
		$( this ).toggleClass( 'closed open' ).trigger( 'blur' );
		$( '#help-section' ).slideToggle();
	} );

	/**
	 * ----------------------------------------
	 * View List Table
	 * ----------------------------------------
	 */

	/**
	 * Save sort order
	 */
	$( 'table.wpm-testimonial_page_testimonial-views th.manage-column' ).on(
		'click',
		function ( e ) {
			const columnName = $( this ).attr( 'id' );
			// get the opposite class
			const columnOrder = $( this ).hasClass( 'asc' )
				? 'desc'
				: $( this ).hasClass( 'desc' )
				? 'asc'
				: '';
			const data = {
				action: 'wpmtst_save_view_list_order',
				name: columnName,
				order: columnOrder,
				nonce: wpmtst_admin_script_nonce,
			};
			$.post( ajaxurl, data, function ( response ) {} );
		}
	);

	/**
	 * Sticky views
	 */
	$( 'table.wpm-testimonial_page_testimonial-views' ).on(
		'click',
		'.stickit',
		function ( e ) {
			const icon = $( this );
			icon.closest( '.wp-list-table-wrap' )
				.find( '.overlay' )
				.fadeIn( 200 );
			icon.trigger( 'blur' ).toggleClass( 'stuck' );
			const id = $( this ).closest( 'tr' ).find( 'td.id' ).html();
			const data = {
				action: 'wpmtst_save_view_sticky',
				id,
				nonce: wpmtst_admin_script_nonce,
			};
			$.post( ajaxurl, data, function ( response ) {
				if ( response ) {
					window.location.reload();
				}
			} );
		}
	);
} );

import Notice from '../blocks/modules/notice';
import AddonsPage from '../blocks/modules/AddonsPage';

class WPMTST_Admin {
	constructor() {
		this.initNotices();
		this.initAddonsPage();
	}

	initNotices( $div = jQuery( 'body' ) ) {
		$div.find( '.wpmtst-notice' ).each( function ( index ) {
			new Notice( jQuery( this ) );
		} );
	}

	initAddonsPage() {
		new AddonsPage( jQuery( this ) );
	}
}

window.WPMTST_Admin = new WPMTST_Admin();
