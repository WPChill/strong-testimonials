jQuery(function ($) {

    elementor.hooks.addAction('panel/open_editor/widget/strong_testimonials_elementor_views', function (panel, model, view) {

        // Get search input
        search_input = panel.$el.find('select[data-setting="strong_testimonials_views_select"]');

        var search_input_active = model.attributes.settings.attributes.strong_testimonials_views_select;

        var search_val, timer, selective_input;
        // Initialize the selectize
        search_input.selectize({
            create: false,
            maxItems: 1,
            closeAfterSelect: false,

            valueField: 'ID',
            labelField: 'post_title',
            searchField:'post_title',

            load: function(query, callback) {
                jQuery.ajax({
                    url: strongAjax.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'strong_testimonials_elementor_ajax_search',
                        st_nonce: strongAjax.ajax_nonce,
                    },
                    error: function() {
                        callback();
                    },
                    success: function( response ) {
                        callback( response.data );
                    }
                    
                });
                
            }
        });
        
    });

});