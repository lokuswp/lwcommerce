<?php

use LokusWP\Commerce\Order;

/**
 * Business Logic on Checkout
 *
 * @since 0.1.0
 */
add_filter( "lokuswp/transaction/logic", "lwc_transaction_logic", 10, 1 );
function lwc_transaction_logic( $transaction ) {

	// Getting Data from Cart
	$cart_uuid             = $transaction['cart_uuid'];
	$post_types_in_cart    = lwp_get_post_types_in_cart( 'post', $cart_uuid ); // [ 'product', 'program' ]
	$product_types_in_cart = lwp_get_post_types_in_cart( 'product', $cart_uuid ); // [ 'digital', 'physical' ]

	$is_product = array_filter( $post_types_in_cart, function ( $post_type ) {
		return strpos( $post_type, 'product' ) !== false;
	} ); // filter if has product post type

	$subtotal = lwc_get_subtotal( $cart_uuid );

	/**
	 * 🔁 Business Logic :: Checkout Free Digital Product
	 * Post_Types : Product
	 * Product_Types : Digital
	 * Subtotal : 0 ( Free )
	 */
	if ( isset( $product_types_in_cart[0] ) &&
	     ! empty( $is_product ) &&
	     $product_types_in_cart[0] == "digital" &&
	     ! in_array( 'physical', $product_types_in_cart ) && // Not Physical
	     $subtotal == 0 ) {

		// Create Transaction
		$trx_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_user_fields( $transaction['user_fields'] )
			->set_payment( $transaction['payment_id'] )
			->set_extras( $transaction['extras'] )
			->set_paid() // Status : Completed
			->create();

		// Hook for Digital Products
		// do_action( "lwcommerce/logic/product/digital", $trx_id );

		// Set Notification Shipping
		lwc_update_order_meta( $trx_id, "_shipping_type", "digital" );
		lwc_update_order_meta( $trx_id, "_shipping_status", "completed" );

		Order::set_status( $trx_id, "completed" );
	}

	/**
	 * 🔁 Business Logic :: Checkout Paid Digital Product
	 * Post_Types : Product
	 * Product_Types : Digital
	 * Subtotal : > 1
	 */
	if ( isset( $product_types_in_cart[0] ) &&
	     ! empty( $is_product ) &&
	     $product_types_in_cart[0] == "digital" &&
	     ! in_array( 'physical', $product_types_in_cart ) && // Not Physical
	     $subtotal > 0 ) {


		// Create Transaction
		$trx_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_user_fields( $transaction['user_fields'] )
			->set_payment( $transaction['payment_id'] )
			->set_extras( $transaction['extras'] )
			->create();

		// Set Order Status : Pending
		Order::set_status( $trx_id, "pending" );

		// Set Shipping Status
		lwc_update_order_meta( $trx_id, "_shipping_type", "digital" );
		lwc_update_order_meta( $trx_id, "_shipping_status", "-" );
	}


	// 🔁 Business Logic :: Checkout Free and Paid Product Digital

	/**
	 * 🔁 Business Logic :: Checkout Paid Physical Product
	 * Post_Types : Product
	 * Product_Types : Physical
	 * Shipping : JNE
	 * Total : > 1
	 */

	if ( isset( $product_types_in_cart[0] ) &&
	     ! empty( $is_product ) &&
	     $product_types_in_cart[0] == "physical" &&
	     ! in_array( 'digital', $product_types_in_cart ) && // Not Physical
	     $subtotal > 0 ) {

		// Create Transaction
		$trx_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_user_fields( $transaction['user_fields'] )
			->set_payment( $transaction['payment_id'] )
			->set_extras( $transaction['extras'] )
			->create();
		
		// Set Order Status : Pending
		Order::set_status( $trx_id, "pending" );

		// Set Shipping Status
		lwc_update_order_meta( $trx_id, "_shipping_type", "physical" );
		lwc_update_order_meta( $trx_id, "_shipping_status", "pending" );
	}

	// TODO 2.0.0 :: Support Multiple Transaction
	if ( ! empty( $trx_id ) && ! empty( $is_product ) ) {
		$transaction['transaction_id'] = abs( $trx_id );

		// Order Meta
		lwc_update_order_meta( $trx_id, "_order_id", $trx_id );
	}

	return $transaction;
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
 * Set Item Price Globally
 */
add_filter( "lokuswp/item/price", "lwc_set_item_price", 10, 4 );
function lwc_set_item_price( $price, $post_id, $currency, $payment_id ) {

	if ( ( get_post_type( $post_id ) === 'product' || get_post_type( $post_id ) === 'product_variant' ) && $currency == "IDR" ) {
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