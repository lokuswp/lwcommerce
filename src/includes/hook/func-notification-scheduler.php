<?php

add_action( 'lokuswp/transaction/status/paid', 'lwp_transaction_paid', 99, 1 );
function lwp_transaction_paid( $trx_id ) {

// Check Subtotal && Digital Type Only
	$trx           = (object) lwp_get_transaction( $trx_id );
	$product_types = lwc_get_product_types( $trx->cart_uuid ); // ["digital", "physical"]

// Check Stock -> Out of Stock
//as_schedule_single_action(strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-cancelled' ), "lwcommerce");

// Only Digital
	if ( isset($product_types[0]) && $product_types[0] == "digital" ) {
		as_schedule_single_action( strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-completed' ), "lwcommerce" );

// Pro Version Only
// as_schedule_single_action(strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-shipping' ), "lwcommerce");
	}

// Only Physical
	if ( isset($product_types[0]) && $product_types[0] == "physical" ) {
		as_schedule_single_action( strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-processing' ), "lwcommerce" );
	}

// Physical and Digital
//	if(in_array( 'physical', $product_types ) || in_array( 'digital', $product_types )) {
//		as_schedule_single_action( strtotime( '+100 seconds' ), 'lokuswp_notification', array( $order_id . '-processing' ), "lwcommerce" );
//	}
}