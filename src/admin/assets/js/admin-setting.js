(function ($) {

    // =================== Institution Settings =================== //
    $(document).on("click", "#lwpc_store_settings_save", function (e) {
        e.preventDefault();
        $(this).addClass('loading');
        const that = this;

        $.post(lwpc_admin.ajax_url, {
            action: 'lwpc_store_settings_save',

            settings: $("#settings form").serialize(),
            security: lwpc_admin.ajax_nonce,
        }, function (response) {
            if (response.trim() === 'action_success') {
                $(that).removeClass('loading');
            } else {
                location.reload();
            }
        }).fail(function () {
            alert('Please check your internet connection');
        });
    });

})(jQuery)