jQuery(document).ready(function ($) {
    var uninstall     = $("a.uninstall-st"),
        formContainer = $('#st-uninstall-form');

    formContainer.on('click', '#delete_all', function () {
        if ( $('#delete_all').is(':checked') ) {
            $('#delete_options').prop('checked', true);
            $('#delete_transients').prop('checked', true);
            $('#delete_cpt').prop('checked', true);
            $('#delete_st_tables').prop('checked', true);
            $('#delete_terms').prop('checked', true);
        } else {
            $('#delete_options').prop('checked', false);
            $('#delete_transients').prop('checked', false);
            $('#delete_cpt').prop('checked', false);
            $('#delete_st_tables').prop('checked', false);
            $('#delete_terms').prop('checked', false);
        }
    });

    $(uninstall).on("click", function () {

        $('body').toggleClass('st-uninstall-form-active');
        formContainer.fadeIn();

        formContainer.on('click', '#st-uninstall-submit-form', function (e) {
            formContainer.addClass('toggle-spinner');
            var selectedOptions = {
                delete_options: ($('#delete_options').is(':checked')) ? 1 : 0,
                delete_transients: ($('#delete_transients').is(':checked')) ? 1 : 0,
                delete_cpt: ($('#delete_cpt').is(':checked')) ? 1 : 0,
                delete_st_tables: ($('#delete_st_tables').is(':checked')) ? 1 : 0,
                delete_terms : ($('#delete_terms').is(':checked')) ? 1 : 0,
            };

            var data = {
                'action': 'st_uninstall_plugin',
                'security': wpStUninstall.nonce,
                'dataType': "json",
                'options': selectedOptions
            };

            $.post(
                ajaxurl,
                data,
                function (response) {
                    // Redirect to plugins page
                    window.location.href = wpStUninstall.redirect_url;
                }
            );
        });

        // If we click outside the form, the form will close
        // Stop propagation from form
        formContainer.on('click', function (e) {
            e.stopPropagation();
        });

        $('.st-uninstall-form-wrapper, .close-uninstall-form').on('click', function (e) {
            e.stopPropagation();
            formContainer.fadeOut();
            $('body').removeClass('st-uninstall-form-active');
        });

        $(document).on("keyup", function (e) {
            if ( e.key === "Escape" ) {
                formContainer.fadeOut();
                $('body').removeClass('st-uninstall-form-active');
            }
        });
    });
});