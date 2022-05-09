(function ($) {
    'use strict'

    /**
     * Add to Cart Button
     *
     * @since 0.5.0
     */
    $(document).on("click", ".lwc-add-to-cart", function (e) {
        e.preventDefault();

        let productID = $(this).attr("product-id");
        let productPrice = $(this).attr("price");

        // Add to Cart
        lokusCart.addProduct(productID, 1);

        // Read Quantity
        let elInputQty = $(this).closest(".product-action").find("input");
        elInputQty.val(lokusCart.readQuantity(productID));

        // Show Qty Stepper
        $(this).hide();
        $(this).closest('.product-action').find('.lokuswp-stepper').removeClass('lwp-hidden');

        // Hook for Event Trigger
        let product = {
            id: productID,
            price: productPrice,
            currency: "IDR",
        };
        Hooks.do_action('lwcommerce/product/add_to_cart', product);

        // Trigger Listener : lwpUpdateCartIcon
        document.dispatchEvent(new CustomEvent("lwpUpdateCartIcon", {
            detail: {
                hello: 'world'
            },
            bubbles: true,
            cancelable: true
        }));
    });

    /**
     * On User Change Cites in Shipping Section
     * Getting Package Based on Selected Cities
     */
    // $(document).on('change', '#lwcommerce-shipping #cities', function (e) {
    //
    //     let destination = $('#cities').find(":selected").val();
    //     // Request to REST API
    //     jQuery.ajax({
    //         url: lokuswp.rest_url + "lwcommerce/v1/shipping/active?destination=" + destination,
    //         type: 'GET',
    //         success: function (response) {
    //
    //             // Formatting Struct and Data
    //             let shippingStruct = jQuery('#struct-shipping-services').html();
    //
    //             let rawData = {};
    //             rawData = response;
    //
    //             rawData.currencyFormat = function () {
    //                 return function (val, render) {
    //                     return lokusWPCurrencyFormat(true, render(val));
    //                 };
    //             }
    //
    //             let shippingData = {
    //                 'shippingChannel': rawData
    //             };
    //
    //             // Rendering with Mustache
    //             jQuery("#lwcommerce-shipping-services").html(Mustache.to_html(shippingStruct, shippingData));
    //
    //             // Saving to Local with Cache
    //             // lokusCookie.set("lokuswp_shipping_list", JSON.stringify(shippingData), 1); // 1 Day Expired
    //         },
    //         error: function (data) {
    //             $(document).snackbar('Tidak dapat mengambil data dari server, Silahkan Coba Lagi');
    //             console.log(data);
    //         }
    //     });
    //
    // });
    //
    // /**
    //  * User Click or change Shipping Channel
    //  */
    // $(document).on('change', 'input[name="shipping_channel"]', function (e) {
    //     e.preventDefault();
    //     console.log("User Choose or Change Shipping Channel");
    //
    //     let title = $(this).attr("title");
    //     let service = $(this).attr('service');
    //     let cost = $(this).attr('cost');
    //
    //     lokusTransaction.setExtra("shipping", "Biaya Pengiriman", title + ' ' + service, cost);
    //
    //     // Render Summary
    //     lokusTransaction.renderExtras();
    // });
    //
    // /**
    //  * Event when user click complete order
    //  */
    // $(document).on('click', '#lwc-verify-shipping', function (e) {
    //     e.preventDefault();
    //
    //     //Shipping Checking
    //     swiperTabsContent.slideTo(2);
    // });

})(jQuery);