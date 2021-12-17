// Add to Cookie Cart
// Cookie.js || Troli.js

(function ($) {
    'use strict'

    $(document).ready(function () {
        $('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ LokusTroli.countQty() +'</small></div>');
    });

    $(document).on("click", ".add-troli", function (e) {
        e.preventDefault();

        let productID = $(this).attr("product-id");
        let elInputQty = $(this).closest(".product-action").find("input");

        LokusTroli.addProduct( "7sa87sf6a8faf7989fa",  parseInt(productID), 1, 10000);
        elInputQty.val(LokusTroli.readQuantity(productID));
       
        $(this).hide();
        $(this).closest('.product-action').find('.lwp-stepper').removeClass('lwp-hidden');

        // Update Troli
        $('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ LokusTroli.countQty() +'</small></div>');
    });

    $(document).on('click', '.lwp-stepper .plus', function (e) {

		let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
        let elInputQty = $(this).closest(".lwp-stepper").find("input");

		LokusTroli.addQuantity( productID, 1 ); 
        elInputQty.val(LokusTroli.readQuantity(productID));
        console.log(LokusTroli.readQuantity(productID))
		$('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ LokusTroli.countQty() +'</small></div>');
		$('.lwp-troli-total').text( LokusTroli.calculateTotal() );
		$('.txt-qty-' + productID).text( LokusTroli.readQuantity( productID ) );
		$('.val-qty-' + productID).val( LokusTroli.readQuantity( productID ) );

		// Max
		// Min
		// Changable

	});

	// Qty Sub - Buggy
	$(document).on('click', '.minus', function (e) {
		
		let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
		LokusTroli.reduceQuantity( productID, 1 );
		$('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ LokusTroli.countQty() +'</small></div>');
		$('.lwp-troli-total').text( LokusTroli.calculateTotal() );
		$('.txt-qty-' + productID).text( LokusTroli.readQuantity( productID ) );
		$('.val-qty-' + productID).val( LokusTroli.readQuantity( productID ) );

		
	});

	$(document).on('change', 'input', function (e) {

		let productID = parseInt($(this).closest(".lwp-stepper").attr('product-id'));
		let productQty = parseInt($(this).val());
		LokusTroli.changeQuantity( productID, productQty );
		$('.troli-icon-wrapper').html('<div class="troli-icon svg-wrapper"><img src="' + lwpbackbone.plugin_url + 'src/assets/svg/troli.svg' + '" alt="troli-icon"><small class="troli-qty">'+ LokusTroli.countQty() +'</small></div>');
		$('.lwp-troli-total').text( LokusTroli.calculateTotal() );
		$('.txt-qty-' + productID).text( LokusTroli.readQuantity( productID ) );
		$('.val-qty-' + productID).val( LokusTroli.readQuantity( productID ) );

	});

})(jQuery);