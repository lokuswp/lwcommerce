// Add to Cookie Cart
// Cookie.js || Troli.js

(function ($) {
    'use strict'

    $(document).ready(function () {
        $('.cart-icon-wrapper').html('<div class="cart-icon svg-wrapper"><img src="' + lokuswp.plugin_url + 'src/assets/svg/cart.svg' + '" alt="cart-icon"><small class="cart-qty">' + lokusCart.countQty() + '</small></div>');
    });

    /**
     * Add to Cart Product
     */
    $(document).on("click", ".lwpc-addtocart", function (e) {
        e.preventDefault();

        let productID = $(this).attr("product-id");
        let elInputQty = $(this).closest(".product-action").find("input");

        lokusCart.addProduct("7sa87sf6a8faf7989fa", parseInt(productID), 1, 10000);
        elInputQty.val(lokusCart.readQuantity(productID));

        $(this).hide();
        $(this).closest('.product-action').find('.lwp-stepper').removeClass('lwp-hidden');

        // Update Troli
        $('.cart-icon-wrapper').html('<div class="cart-icon svg-wrapper"><img src="' + lokuswp.plugin_url + 'src/assets/svg/cart.svg' + '" alt="cart-icon"><small class="cart-qty">' + lokusCart.countQty() + '</small></div>');
    });

    // /**
    //  * Add Quantity Product
    //  */
    // $(document).on('click', '.lwp-stepper .plus', function (e) {

    //     let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
    //     let elInputQty = $(this).closest(".lwp-stepper").find("input");

    //     lokusCart.addQuantity(productID, 1);
    //     elInputQty.val(lokusCart.readQuantity(productID));

    //     lwp_render_cart(productID);

    //     // Max
    //     // Min
    //     // Changable

    // });

    // /**
    //  * Decrease Quantity Product
    //  */
    // $(document).on('click', '.lwp-stepper .minus', function (e) {

    //     let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
    //     lokusCart.reduceQuantity(productID, 1);

    //     lwp_render_cart(productID);

    // });

    // /**
    //  * Change Product 
    //  */
    // $(document).on('change', '.lwp-stepper input', function (e) {

    //     let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
    //     let productQty = parseInt($(this).val());
    //     lokusCart.changeQuantity(productID, productQty);

    //     lwp_render_cart(productID);
    // });


    /**
     * On User Change Cites in Shipping Section
     * Getting Package Based on Selected Cities
     */
    $(document).on('change', '#lwpcommerce-shipping #cities', function (e) {
        // Request to REST API
        jQuery.ajax({
            url: lokuswp.rest_url + "lwpcommerce/v1/shipping/active?destination=456",
            type: 'GET',
            success: function (response) {

                // Formatting Struct and Data
                let shippingStruct = jQuery('#struct-shipping-channel').html();

                let rawData = {};
                rawData = response;

                let shippingData = {
                    'shippingChannel': rawData
                };
                shippingData.currencyFormat = function () {
                    return function (val, render) {
                        return lokusWPCurrencyFormat(true, render(val));
                    };
                }

                // Rendering with Mustache
                jQuery("#lwpcommerce-shipping-channel").html(Mustache.to_html(shippingStruct, shippingData));

                // Saving to Local with Cache
                // lokusCookie.set("lokuswp_shipping_list", JSON.stringify(shippingData), 1); // 1 Day Expired
            },
            error: function (data) {
                alert('AJAX Error, See Console Log !!!');
                console.log(data);
            }
        });

    });

    /**
     * Event on User Click or change Shipping Channel
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

  

})(jQuery);