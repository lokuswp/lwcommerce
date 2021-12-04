// Add to Cookie Cart
// Cookie.js || Troli.js

(function ($) {
    'use strict'

    $(document).on("click", ".add-troli", function (e) {
        e.preventDefault();
        alert( $(this).attr("product-id") );
    });

})(jQuery);