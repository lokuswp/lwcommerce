(function ($) {
    'use strict'
    // console.log("lwcommerce/public.js");
    function lwcUpdateCartIcon() {
        $('.cart-icon-wrapper').html('<div class="cart-icon svg-wrapper"><small class="cart-qty">' + lokusCart.countQty() + '</small><img src="' + lokuswp.plugin_url + 'src/assets/svg/cart.svg' + '" alt="cart-icon"></div>');
    }

    /**
     * Processing View After Checkout
     */
    document.addEventListener('lokuswp-transaction-success', function ( response ) {

        // Order
        document.getElementById("lwc-order-id").innerHTML = "#" + response.detail.order_id;
        document.getElementById("lwc-order-status").innerHTML = response.detail.order_status_text;

        // Button
        document.getElementById("trx-btn-action").innerText = response.detail.btn_text;
        document.getElementById("trx-btn-action").setAttribute("href", response.detail.btn_url);

        // Load Download Section
    }, false);


    $(document).ready(function () {
        lwcUpdateCartIcon();
    });

    /**
     * Add to Cart Button
     * 
     * @since 0.5.0
     */
    $(document).on("click", ".lwc-addtocart", function (e) {
        e.preventDefault();

        let productID = $(this).attr("product-id");
        let elInputQty = $(this).closest(".product-action").find("input");

        lokusCart.addProduct("7sa87sf6a8faf7989fa", parseInt(productID), 1, 10000);
        elInputQty.val(lokusCart.readQuantity(productID));

        $(this).hide();
        $(this).closest('.product-action').find('.lokuswp-stepper').removeClass('lwp-hidden');

        // Hook for Event Trigger
        let product = {
            id: productID,
            price: 100000,
            currency: "IDR",
        };
        Hooks.do_action('lwcommerce/product/addtocart', product );

        // Update Troli
        lwcUpdateCartIcon();
    });

    /**
     * On User Change Cites in Shipping Section
     * Getting Package Based on Selected Cities
     */
    $(document).on('change', '#lwcommerce-shipping #cities', function (e) {

        let destination = $('#cities').find(":selected").val();
        // Request to REST API
        jQuery.ajax({
            url: lokuswp.rest_url + "lwcommerce/v1/shipping/active?destination=" + destination,
            type: 'GET',
            success: function (response) {

                // Formatting Struct and Data
                let shippingStruct = jQuery('#struct-shipping-services').html();

                let rawData = {};
                rawData = response;

                rawData.currencyFormat = function () {
                    return function (val, render) {
                        return lokusWPCurrencyFormat(true, render(val));
                    };
                }

                let shippingData = {
                    'shippingChannel': rawData
                };

                // Rendering with Mustache
                jQuery("#lwcommerce-shipping-services").html(Mustache.to_html(shippingStruct, shippingData));

                // Saving to Local with Cache
                // lokusCookie.set("lokuswp_shipping_list", JSON.stringify(shippingData), 1); // 1 Day Expired
            },
            error: function (data) {
                $(document).snackbar('Tidak dapat mengambil data dari server, Silahkan Coba Lagi');
                console.log(data);
            }
        });

    });

    /**
     * User Click or change Shipping Channel
     */
    $(document).on('change', 'input[name="shipping_channel"]', function (e) {
        e.preventDefault();
        console.log("User Choose or Change Shipping Channel");

        let title = $(this).attr("title");
        let service = $(this).attr('service');
        let cost = $(this).attr('cost');

        lokusTransaction.setExtra("shipping", "Biaya Pengiriman", title + ' ' + service, cost);

        // Render Summary
        lokusTransaction.renderExtras();
    });

    /**
     * Event when user click complete order
     */
    $(document).on('click', '#lwc-verify-shipping', function (e) {
        e.preventDefault();

        //Shipping Checking
        swiperTabsContent.slideTo(2);
    });

})(jQuery);