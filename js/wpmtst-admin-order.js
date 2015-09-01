/**
 *  Strong Testimonials > admin list order
 */

jQuery(document).ready(function ($) {
	$("td.column-order").hover(
		function () {
			$(this).closest("tr").addClass("reorder-hover");
		},
		function () {
			$(this).closest("tr").removeClass("reorder-hover");
		}
	);
});

// set cell widths to prevent shrinkage
function setCellWidths() {
	jQuery('td, th', 'table.posts').each(function () {
		var cell = jQuery(this);
		cell.width(cell.width());
	});
};

// reset cell widths
function resetCellWidths(reset) {
	jQuery('td, th', 'table.posts').each(function () {
		var cell = jQuery(this);
		cell.width('');
	}).promise().done(setCellWidths);
};

// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
// Thanks http://davidwalsh.name/javascript-debounce-function
function debounce(func, wait, immediate) {
	var timeout;
	return function () {
		var context = this, args = arguments;
		var later = function () {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
};


// window resize listener
var myEfficientFn = debounce(resetCellWidths, 100);
window.addEventListener('resize', myEfficientFn);


(function ($) {

	setCellWidths();

	// make rows sortable
	$('table.posts #the-list').sortable({
		items: 'tr',
		axis: 'y',
		handle: 'td.column-order',
		forcePlaceholderSize: true,
		placeholder: "sortable-placeholder",
		start: function (e, ui) {
			// set height of placeholder to match current dragged element
			ui.placeholder.height(ui.helper.height());
			ui.helper.css("cursor", "move");
		},
		helper: function (e, ui) {
			var $originals = ui.children();
			var $helper = ui.clone();
			$helper.children().each(function (index) {
				// set helper cell sizes to match the original sizes
				$(this).width($originals.eq(index).width());
			});
			return $helper;
		},
		update: function (e, ui) {
			$.post(ajaxurl, {
					action: 'update-menu-order',
					order: $('#the-list').sortable('serialize'),
				},
				function (data) {
					// update menu order shown
					var $orders = $(".menu-order");
					var obj = JSON.parse(data);
					var orderArray = $.map(obj, function (val, i) {
						$orders.eq(i).html(val);
					});
					// update zebra striping
					//$("#the-list tr").removeClass("alternate");
				})
				.done(function () {
					// alert( "second success" );
					ui.item.effect('highlight', {}, 2000);
				})
			/*
			 .fail(function() {
			 // alert( "error" );
			 })
			 .always(function() {
			 // alert( "finished" );
			 });
			 */
		}
	});

})(jQuery);
