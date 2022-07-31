<?php
/*****************************************
 * Add Shipping Tab to Checkout Page
 *
 * @since 0.1.0
 *****************************************
 */
add_action( "lokuswp/transaction/tab/header", function ( $cart_uuid ) {

	?>
    <div id="shipping-tab" class="swiper-slide" style=" display: none;">
		<?php _e( 'Shipping', 'lwcommerce' ); ?>
    </div>
	<?php

} );

add_action( "lokuswp/transaction/tab/content", function ( $cart_uuid ) {
	require_once LWC_PATH . 'src/templates/presenter/checkout/shipping.php';
} );
