<?php
/*****************************************
 * Add Shipping Tab to Checkout Page
 *
 * @since 0.1.0
 *****************************************
 */
add_action( "lokuswp/transaction/tab/header", function ( $cart_uuid ) {

	if ( $cart_uuid ) {
		$product_types_in_cart = lwp_get_post_types_in_cart( 'product', $cart_uuid, "on-cart" ); // [ 'digital', 'physical' ]
		if ( in_array( 'physical', $product_types_in_cart ) ) {
			?>
            <div class="swiper-slide">
				<?php _e( 'Shipping', 'lwcommerce' ); ?>
            </div>
			<?php
		}

	}

} );

add_action( "lokuswp/transaction/tab/content", function ( $cart_uuid ) {

	if ( $cart_uuid ) {
		$product_types_in_cart = lwp_get_post_types_in_cart( 'product', $cart_uuid, "on-cart" ); // [ 'digital', 'physical' ]
		if ( in_array( 'physical', $product_types_in_cart ) ) {
			require_once LWC_PATH . 'src/templates/presenter/checkout/shipping.php';
		}
	}

} );
