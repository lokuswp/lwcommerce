(function ($) {

    /*****************************************
     * Getting Screen Dynamically
     * Dynamic Get Screen
     *
     * @since 0.1.0
     ***************************************
     */
    function lwc_get_onboarding_store_screen() {

        $.ajax({
            url: lwc_admin.ajax_url,
            method: 'POST',
            data: {
                action: 'lwcommerce_onboarding_store_screen',
                security: lwc_admin.ajax_nonce,
            },
            success: (res) => {
                res = JSON.parse(res);
                if (res.code == "success_get_store_screen") {
                    $("#manage-store").prepend(res.template);
                    $('#store-step').removeClass('hidden');
                    $('#dependency-step').addClass('hidden');
                } else {
                    alert('Failed to get store screen');
                }
            },
        }).fail(() => location.reload());

    }

    $(document).ready(function () {
        /*****************************************
         * Trigger Downloading LokusWP Backbone
         * When Onboarding Screen Loaded, Hit AJAX
         *
         * @since 0.1.0
         ***************************************
         */
        if (lwc_admin.plugin_exist == "") {
            $.ajax({
                url: lwc_admin.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwcommerce_download_backbone',
                    security: lwc_admin.ajax_nonce,
                },
                success: (res) => {
                    location.reload();
                },
            }).fail(() => location.reload());
        } else {
            lwc_get_onboarding_store_screen();
        }

        // Store Verification Filing
        $('.step-to-integration').on('click', function () {

            let textarea = $('textarea[name="address"]').val();
            if (!textarea) {
                alert('Please your store address');
                $('textarea[name="address"]').css('border', '1px solid red');
            } else {
                $('#integration-step').removeClass('hidden');
                $('#store-step').addClass('hidden');
            }

        });

    });

})(jQuery)