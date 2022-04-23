<?php
/**
 * Processing Cart Data from Cart Cookie
 * Scan and Sync Product
 *
 * @since 0.1.0
 */
add_filter( "lokuswp/cart/cookie/item", "lwc_cart_processing", 10, 2 );
function lwc_cart_processing( $cart_item, $post_id ) {
	if ( get_post_type( $post_id ) == 'product' ) {
		$cart_item['price']       = abs( lwc_get_price( $post_id ) );
		$cart_item['unit_price']  = abs( lwc_get_unit_price( $post_id ) );
		$cart_item['price_promo'] = abs( lwc_get_price_promo( $post_id ) );

		// Funnel Tracking
		lwp_set_meta_counter( "_product_on_cart", $post_id );
	}

	return $cart_item;
}
