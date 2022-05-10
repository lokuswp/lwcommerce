(function ($) {
    $(document).ready(function () {

        $.ajax({
            url: lwc_admin.ajax_url,
            method: 'POST',
            data: {
                action: 'lwcommerce_download_backbone',
                security: lwc_admin.ajax_nonce,
            },
            success: (res) => {
                if (res == "ajax_success") {
                    $('#store-step').removeClass('hidden');
                    $('#dependency-step').addClass('hidden');
                } else if (res == "ajax_lwcommerce") {
                    window.location.href = lwc_admin.admin_url;
                } else {
                    alert('please check your internet connection!')
                }
            },
        }).fail(() => alert('please check your internet connection!'));


        $('#lwc-setting-store-save').on('click', function () {
            $('.step-to-integration').removeClass('hidden');
            // $(this).addClass('hidden');
        });

        $('.step-to-integration').on('click', function () {

            let textarea = $('textarea[name="address"]').val();
            if (!textarea) {
                alert('Please fill your store Data');
                $('textarea[name="address"]').css('border', '1px solid red');
            } else {
                $('#integration-step').removeClass('hidden');
                $('#store-step').addClass('hidden');
            }

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


})(jQuery)