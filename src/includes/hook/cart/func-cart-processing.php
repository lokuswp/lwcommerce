<?php
/**
 * Processing Cart Data from Cart Cookie
 * Scan and Sync Product
 *
 * @since 0.1.0
 */
add_filter( "lokuswp/cart/cookie/item", "lwc_cart_processing", 10, 2 );
function lwc_cart_processing( $cart_item, $post_id ) {
	if ( get_post_type( $post_id ) == 'product' || get_post_type( $post_id ) == 'product_variant' ) {
		$cart_item['price']       = abs( lwc_get_price( $post_id ) );
		$cart_item['unit_price']  = abs( lwc_get_unit_price( $post_id ) );
		$cart_item['price_promo'] = abs( lwc_get_promo_price( $post_id ) );

		// Funnel Tracking
//		lwp_set_meta_counter( "_product_on_cart", $post_id );
	}

	return $cart_item;
}


/**
 * Product Item Filter
 *
 * @return mixed
 */
add_filter( 'lokuswp/cart/rest/item', 'lwc_rest_cart_item_output', 10, 2 );
function lwc_rest_cart_item_output( $item_data, $item_id ) {

	if ( get_post_type( $item_id ) == 'product' || get_post_type( $item_id ) == 'product_variant' ) {
		//	$variation_id = $item_data['variation_id'];
		$item_data['product_type'] = empty( get_post_meta( $item_id, '_product_type', true ) ) ? 'undefined' : esc_attr( get_post_meta( $item_id, '_product_type', true ) );
		$item_data['unit_price']   = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_unit_price', true ) ?? null;
		$item_data['price_promo']  = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_promo_price', true ) ?? null;
		$item_data['price_text']   = lwc_get_price_html( $item_id );
		$item_data['weight']       = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_weight', true ) ?? 0;
		$item_data['stock']        = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_stock', true ) ?? 0;
		$item_data['stock_unit']   = get_post_meta( $item_id, '_stock_unit', true ) ?? '';
		$item_data['amount']       = floatval( lwc_get_price( $item_id ) ) * abs( $item_data['quantity'] );
	}



	return $item_data;
}