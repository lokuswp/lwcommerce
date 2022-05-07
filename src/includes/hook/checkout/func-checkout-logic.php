<?php

use LokusWP\Commerce\Order;

/**
 * Business Logic of Ecommerce
 *
 * @since 0.1.0
 */
add_filter( "lokuswp/transaction/logic", "lwc_transaction_logic", 10, 1 );
function lwc_transaction_logic( $transaction ) {

	// Getting Data
	$cart_uuid     = $transaction['cart_uuid'];
	$product_types = lwc_get_product_types( $cart_uuid );
	$subtotal      = lwc_get_subtotal( $cart_uuid );

	// 🔁 Business Logic :: Only Free Product Digital
	if ( $subtotal == 0 && ! in_array( 'physical', $product_types ) && isset( $product_types[0] ) && $product_types[0] == "digital" ) {

		// Create Transaction
		$trx_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_user_fields( $transaction['user_fields'] )
			->set_payment( $transaction['payment_id'] )
			->set_extras( $transaction['extras'] )
			->set_paid()
			->create();

		// Hook for Digital Products
		do_action( "lwcommerce/logic/product/digital", $trx_id );

		// Set Notification Shipping
		lwc_update_order_meta( $trx_id, "_shipping_type", "digital" );
		lwc_update_order_meta( $trx_id, "_shipping_status", "completed" );

		Order::set_status( $trx_id, "completed" );
	}

	// 🔁 Business Logic :: Only Paid Product Digital
	if ( $subtotal > 0 && ! in_array( 'physical', $product_types ) && $product_types[0] == "digital" ) {

		$trx_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_extras( $transaction['extras'] )
			->set_payment( $transaction['payment_id'] )
			->set_user_fields( $transaction['user_fields'] )
			->create();

		Order::set_status( $trx_id, "pending" );

		// Set Notification Shipping
		lwc_update_order_meta( $trx_id, "_shipping_type", "digital" );
		lwc_update_order_meta( $trx_id, "_shipping_status", "pending" );
	}

	// 🔁 Business Logic :: Only Paid Product Digital with Coupon
	// 🔁 Business Logic :: Free and Paid Product Digital

	// 🔁 Business Logic :: Paid Physical Product

	// Checking Transaction ID
	if ( empty( $trx_id ) ) {
		return false;
	}

	// Common Meta Data
	lwc_update_order_meta( $trx_id, "_order_id", $trx_id );
	lwc_update_order_meta( $trx_id, "_billing_name", lwp_get_transaction_meta( $trx_id, "_user_field_name" ) );
	lwc_update_order_meta( $trx_id, "_billing_phone", lwp_get_transaction_meta( $trx_id, "_user_field_phone" ) );
	lwc_update_order_meta( $trx_id, "_billing_email", lwp_get_transaction_meta( $trx_id, "_user_field_email" ) );

	return abs( $trx_id );
}


/**
 * Transaction Status Text
 *
 * @since 0.1.0
 */
add_filter( "lokuswp/transaction/status/text", "lwc_transaction_status_text", 10, 1 );
function lwc_transaction_status_text( $statuses ) {
	$statuses['completed'] = __( "Selesai", "lwcommerce" );

	return $statuses;
}

/**
 * Product Item Filter
 *
 * @return mixed
 */
add_filter( 'lokuswp/cart/rest/item', 'lwc_rest_cart_item_output', 10, 2 );
function lwc_rest_cart_item_output( $item_data, $item_id ) {

	if ( get_post_type( $item_id ) == 'product' ) {
		//	$variation_id = $item_data['variation_id'];
		$item_data['product_type'] = empty( get_post_meta( $item_id, '_product_type', true ) ) ? 'undefined' : esc_attr( get_post_meta( $item_id, '_product_type', true ) );
		$item_data['unit_price']   = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_unit_price', true ) ?? null;
		$item_data['price_promo']  = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_price_promo', true ) ?? null;
		$item_data['price_text']   = lwc_get_price_html( $item_id );
		$item_data['weight']       = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_weight', true ) ?? 0;
		$item_data['stock']        = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_stock', true ) ?? 0;
		$item_data['stock_unit']   = get_post_meta( $item_id, '_stock_unit', true ) ?? '';
		$item_data['amount']       = floatval( lwc_get_price( $item_id ) ) * abs( $item_data['quantity'] );
	}

	return $item_data;
}

/**
 * Set Item Price Globally
 */
add_filter( "lokuswp/item/price", "lwc_set_item_price", 10, 4 );
function lwc_set_item_price( $price, $post_id, $currency, $payment_id ) {

	if ( get_post_type( $post_id ) == 'product' && $currency == "IDR" ) {
		$price = lwc_get_price( $post_id, $currency );
	}

	return $price;
}

/**
 * Set Item Price Globally
 */
add_filter( "lokuswp/item/price", "lokuswp_paypal_set_item_price", 10, 4 );
function lokuswp_paypal_set_item_price( $price, $post_id, $currency, $payment_id ) {

	if ( $payment_id == "paypal" && $currency == "USD" ) {
		$price = get_post_meta( $post_id, '_price_usd', true );
	}

	return $price;
}

