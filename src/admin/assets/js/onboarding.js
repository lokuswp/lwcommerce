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

            let whatsapp = $('input[name="whatsapp"]').val();
            console.log(whatsapp);
            if (!whatsapp) {
                alert('Please Fill Whatsapp Number');
                $('input[name="whatsapp"]').css('border', '1px solid red');
            } else {
                $('#integration-step').removeClass('hidden');
                $('#store-step').addClass('hidden');
            }

        });

    });


    /*****************************************
     * Saving Store Data
     * When user Fill Store Data, Hit AJAX
     *
     * @since 0.1.0
     ***************************************
     */
    $(document).on("click", "#lwc-setting-store-save", function (e) {
        e.preventDefault();

        let whatsapp = $('input[name="whatsapp"]').val();
        if (whatsapp == '' || whatsapp == undefined) {
            alert('Please Fill Whatsapp Number');
            $('input[name="whatsapp"]').css('border', '1px solid red');
        }else{
            $('input[name="whatsapp"]').css('border', '1px solid #bcc3ce');
            $(this).addClass('loading');
            const that = this;
            $.post(lwc_admin.ajax_url, {
                action: 'lwc_store_settings_save',
                settings: $("#settings form").serialize(),
                security: lwc_admin.ajax_nonce,
            }, function (response) {
                if (response.trim() === 'action_success') {
                    $(that).removeClass('loading');
                    $('.step-to-integration').removeClass('hidden');
                } else {
                    location.reload();
                }
            }).fail(function () {
                alert('Please check your internet connection');
            });
        }

    });

})(jQuery)