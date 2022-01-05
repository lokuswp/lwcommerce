// Add to Cookie Cart
// Cookie.js || Troli.js

(function ($) {
    'use strict'

    $(document).ready(function () {
        $('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ lokusCart.countQty() +'</small></div>');
    });

    $(document).on("click", ".lwpc-addtocart", function (e) {
        e.preventDefault();

        let productID = $(this).attr("product-id");
        let elInputQty = $(this).closest(".product-action").find("input");

        lokusCart.addProduct( "7sa87sf6a8faf7989fa",  parseInt(productID), 1, 10000);
        elInputQty.val(lokusCart.readQuantity(productID));
       
        $(this).hide();
        $(this).closest('.product-action').find('.lwp-stepper').removeClass('lwp-hidden');

        // Update Troli
        $('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ lokusCart.countQty() +'</small></div>');
    });

    $(document).on('click', '.lwp-stepper .plus', function (e) {

		let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
        let elInputQty = $(this).closest(".lwp-stepper").find("input");

		lokusCart.addQuantity( productID, 1 ); 
        elInputQty.val(lokusCart.readQuantity(productID));
        console.log(lokusCart.readQuantity(productID))
		$('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ lokusCart.countQty() +'</small></div>');
		$('.lwp-troli-total').text( lokusCart.calculateTotal() );
		$('.txt-qty-' + productID).text( lokusCart.readQuantity( productID ) );
		$('.val-qty-' + productID).val( lokusCart.readQuantity( productID ) );

		// Max
		// Min
		// Changable

	});

	// Qty Sub - Buggy
	$(document).on('click', '.lwp-stepper .minus', function (e) {
		
		let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
		lokusCart.reduceQuantity( productID, 1 );
		$('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ lokusCart.countQty() +'</small></div>');
		$('.lwp-troli-total').text( lokusCart.calculateTotal() );
		$('.txt-qty-' + productID).text( lokusCart.readQuantity( productID ) );
		$('.val-qty-' + productID).val( lokusCart.readQuantity( productID ) );

		
	});

	$(document).on('change', '.lwp-stepper input', function (e) {

		let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
		let productQty = parseInt($(this).val());
		lokusCart.changeQuantity( productID, productQty );
		$('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ lokusCart.countQty() +'</small></div>');
		$('.lwp-troli-total').text( lokusCart.calculateTotal() );
		$('.txt-qty-' + productID).text( lokusCart.readQuantity( productID ) );
		$('.val-qty-' + productID).val( lokusCart.readQuantity( productID ) );

	});

})(jQuery);