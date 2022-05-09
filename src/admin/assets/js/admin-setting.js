(function ($) {
    //=============== Admin - Payment ===============//
    // Enabled
    $(document).on("change", ".lwcommerce-shipping-status", function (e) {

        let id = $(this).find('input[type="checkbox"]').attr('id');
        let state = ($(this).find('input[type="checkbox"]').is(":checked")) ? 'on' : 'off';

        $.post(lwc_admin.ajax_url, {
            action: 'lwc_admin_shipping_status',
            id: id,
            state: state,
            security: lwc_admin.ajax_nonce,
        }, function (response) {

            if (response.trim() == 'action_success') {
                // give feedback
            }

        }).fail(function () {
            alert('Please check your internet connection');
        });

    });


    // =================== Store Settings =================== //
    $(document).on("click", "#lwc-setting-store-save", function (e) {
        console.log($("#settings form").serialize())
        e.preventDefault();
        $(this).addClass('loading');
        const that = this;

        $.post(lwc_admin.ajax_url, {
            action: 'lwc_store_settings_save',
            settings: $("#settings form").serialize(),
            security: lwc_admin.ajax_nonce,
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
    $(document).on("change", ".lwc_shipping_package_status", function (e) {
        $(this).addClass('loading');
        const packageId = $(this).attr('data-action');
        const that = $(this);

        $.post(lwc_admin.ajax_url, {
            action: 'lwc_shipping_package_status',
            status: that.val(),
            package_id: packageId,
            security: lwc_admin.ajax_nonce,
        }, function (response) {
            console.log(response)
            // if (!response) alert('action failed');
            that.removeClass('loading')
        }).fail(function () {
            alert('Please check your internet connection');
        });
    });

    // =================== Shipping Settings =================== //
    $(document).on("click", "#lwc-setting-shipping-save", function (e) {
        e.preventDefault();
        $(this).addClass('loading');
        const that = this;

        $.post(lwc_admin.ajax_url, {
            action: 'lwc_shipping_settings_save',
            settings: $("#settings form").serialize(),
            security: lwc_admin.ajax_nonce,
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


    /**
     * ⚡ Show Shipping Manager
     * Dsiplaying Shipping Manager
     *
     * @scope Global
     * @since 0.5.0
     */
    $(document).on("click", ".lwc-shipping-manager", function (e) {

        let shippingEditor = $("#lwc-shipping-manager-editor");
        // On Loading
        // shippingEditor.html(shimmer);

        // On Completed
        let thisID = $(this).attr('id');

        // // AJAX Request
        // $.post(lwc_admin.ajax_url, {
        //     action: 'lwc_admin_shipping_manage',
        //     id: thisID,
        //     security: lwc_admin.ajax_nonce,
        // }, function (response) {

        //     let html = response;

        //     // Manipulate InnerHTML
        //     var $html = $('<div />', {
        //         html: html
        //     });

        //     $html.find('form').attr("id", thisID + '_form'); // Change ID
        //     shippingEditor.html($html.html());

        //     //  $(".selectlive").select2({
        //     //      allowClear: true,
        //     //      width: '100%',
        //     //  });

        shippingEditor.find('.shipping-editor').removeClass('d-hide');

        // }).fail(function () {
        //     alert('Please check your internet connection');
        // });

        shippingEditor.parent().show();
        shippingEditor.parent().css('z-index', '9999');
    });

    /**
     * ⚡ Close Payment Method Manager Panel
     * Dsiplaying Payment Method Manager
     *
     * @scope Global
     * @since 0.5.0
     */
    $(document).on("click", "#lwc-shipping-manager-editor .panel-close", function (e) {

        let shippingEditor = $("#lwc-shipping-manager-editor");

        shippingEditor.parent().hide();
        shippingEditor.parent().css('z-index', '0');
        shippingEditor.html('');

        //  $(".selectlive").select2('destroy');
    });

    /******************************************/
    /* Save license
    /******************************************/
    $(document).on("click", ".lwc-license-register", function (e) {
        const that = $(this);
        const inputKey = $(this).closest('.card-header').find('input.lwc-license-key');
        const errorMessage = $('#error-message');
        errorMessage.hide();

        if (inputKey.val() !== '') {
            that.addClass('loading');
            $.ajax({
                url: lwc_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'lokuswp_license_save',
                    license_key: inputKey.val(),
                    slug: that.attr('data-slug'),
                    security: lwc_admin.ajax_nonce,
                },
                success: data => {
                    if (data.success === 'false' || !data.success) {
                        errorMessage.show();
                        errorMessage.html(data.message);
                        inputKey.css('border', '1px solid red');
                        that.removeClass('loading');
                        return;
                    }

                    location.reload();
                }
            }).fail(function () {
                alert('Please check your internet connection');
            })
        } else {
            inputKey.css('border', '1px solid red');
            errorMessage.show();
            errorMessage.html('Please enter a license key');
        }
    });

})(jQuery)