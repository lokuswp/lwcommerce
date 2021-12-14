// Add to Cookie Cart
// Cookie.js || Troli.js

(function ($) {
    'use strict'

    $(document).ready(function () {
        $('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ LokusTroli.countQty() +'</small></div>');
    });

    $(document).on("click", ".add-troli", function (e) {
        e.preventDefault();

       LokusTroli.addProduct( "7sa87sf6a8faf7989fa",  parseInt($(this).attr("product-id")), 1, 10000);
       $('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ LokusTroli.countQty() +'</small></div>');
    });

})(jQuery);