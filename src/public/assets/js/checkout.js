(function ($) {
    'use strict'

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

})(jQuery);