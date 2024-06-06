jQuery(document).ready(function ($) {
    var uninstall     = $("a.uninstall-st"),
        formContainer = $('#st-uninstall-form');

    formContainer.on('click', '#st-delete_all', function () {
        if ( $('#st-delete_all').is(':checked') ) {
            $('#st-delete_options').prop('checked', true);
            $('#st-delete_transients').prop('checked', true);
            $('#st-delete_cpt').prop('checked', true);
            $('#st-delete_st_tables').prop('checked', true);
            $('#st-delete_terms').prop('checked', true);
        } else {
            $('#st-delete_options').prop('checked', false);
            $('#st-delete_transients').prop('checked', false);
            $('#st-delete_cpt').prop('checked', false);
            $('#st-delete_st_tables').prop('checked', false);
            $('#st-delete_terms').prop('checked', false);
        }
    });

    $(uninstall).on("click", function () {

        $('body').toggleClass('st-uninstall-form-active');
        formContainer.fadeIn();

        formContainer.on('click', '#st-uninstall-submit-form', function (e) {
            formContainer.addClass('toggle-spinner');
            var selectedOptions = {
                delete_options: ($('#st-delete_options').is(':checked')) ? 1 : 0,
                delete_transients: ($('#st-delete_transients').is(':checked')) ? 1 : 0,
                delete_cpt: ($('#st-delete_cpt').is(':checked')) ? 1 : 0,
                delete_st_tables: ($('#st-delete_st_tables').is(':checked')) ? 1 : 0,
                delete_terms : ($('#st-delete_terms').is(':checked')) ? 1 : 0,
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