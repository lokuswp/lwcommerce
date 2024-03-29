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

        if (typeof checkVariation === "function" && typeof variantSelected === "boolean") {
            checkVariation(productID);
            if (!variantSelected && $(this).attr("is-variant-exists") === '1') return;
        }

        // Add to Cart
        lokusCart.addProduct(productID, 1, productPrice);

        // Read Quantity
        let elInputQty = $(this).closest(".product-action").find("input");
        elInputQty.val(lokusCart.readQuantity(productID));

        // Show Qty Stepper
        $(this).hide();
        $(this).closest('.product-action').find('.lokuswp-stepper').removeClass('lwp-hidden');

        lwpRenderCart();

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

    $(document).on('change', 'input[name="shipping_type"]', function (e) {
        e.preventDefault();

        // swiperTabsNav.updateAutoHeight();
        swiperTabsContent.updateAutoHeight();
    });

})(jQuery);