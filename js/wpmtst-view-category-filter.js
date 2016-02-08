/**
 * View Category Filter
 *
 * Adapted from the excellent:
 *
 * Plugin Name:       Post Category Filter
 * Plugin URI:        http://www.jahvi.com
 * Description:       Filter post categories and taxonomies live in the WordPress admin area
 * Version:           1.2.4
 * Author:            Javier Villanueva
 * Author URI:        http://www.jahvi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

(function ( $ ) {

	'use strict';

	$(function () {

        var $categoryDivs = $('.view-category-list-panel');

		$(".fc-search-wrap")
            .append('<input type="search" class="fc-search-field" placeholder="' + wpmtst_fc_plugin.placeholder + '" /><span class="fc-search-clear"></span>');

        $categoryDivs.on('keyup search', '.fc-search-field', function (event) {

			$(this).parent().find(".fc-search-clear").show();

            var searchTerm = event.target.value,
                $listItems = $(this).closest(".view-category-list-panel").find('.view-category-list li');

            if ($.trim(searchTerm)) {

                $listItems.hide().filter(function () {
                    return $(this).text().toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
                }).show();

            } else {

                $listItems.show();

            }

        });

		// Clear search input and show all list items
		$(".fc-search-clear").on("click", function() {

			$(this).closest(".view-category-list-panel").find('.view-category-list li').show();

			$(this).parent().find('input').val('').focus().parent().find(".fc-search-clear").hide();

		});

	});

}(jQuery));
