(function ($) {

    // =================== Store Settings =================== //
    $(document).on("click", "#lwpc-setting-store-save", function (e) {
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

    // =================== Shipping Package Status =================== //
    $(document).on("change", ".lwpc_shipping_package_status", function (e) {
        $(this).addClass('loading');
        const packageId = $(this).attr('data-action');
        const that = $(this);

        $.post(lwpc_admin.ajax_url, {
            action: 'lwpc_shipping_package_status',
            status: that.val(),
            package_id: packageId,
            security: lwpc_admin.ajax_nonce,
        }, function (response) {
            console.log(response)
            // if (!response) alert('action failed');
            that.removeClass('loading')
        }).fail(function () {
            alert('Please check your internet connection');
        });
    });

    // =================== Shipping Settings =================== //
    $(document).on("click", "#lwpc-setting-shipping-save", function (e) {
        e.preventDefault();
        $(this).addClass('loading');
        const that = this;

        $.post(lwpc_admin.ajax_url, {
            action: 'lwpc_shipping_settings_save',
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