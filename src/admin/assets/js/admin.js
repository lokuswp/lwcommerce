(function ($) {

    $(document).ready(function () {

        $("#admin-notice-download-backbone").on('click', function (e) {
            e.preventDefault();
            $(this).parent().text("Processing...");
            $.ajax({
                url: lwc_admin_all.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwcommerce_download_backbone',
                    security: lwc_admin_all.ajax_nonce,
                },
                success: () => {
                    location.reload();
                },
            }).fail(() => alert("fail"));
        })

    })

})(jQuery)