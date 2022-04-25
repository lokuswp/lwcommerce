<?php

add_action( "lokuswp/transaction/save", "lwc_create_transaction", 10, 2 );
function lwc_create_transaction( $trx_id, $trx_data ) {

//	lwc_update_order_meta( $trx_id, "_shipping_type", "physical" );
//	lwc_update_order_meta( $trx_id, "_shipping_courier", "JNE" );
//	lwc_update_order_meta( $trx_id, "_shipping_service", "OKE" );
//	lwc_update_order_meta( $trx_id, "_shipping_status", "unfulfilled" ); // [ "unfulfilled", "fulfilled" ]
//
//	// Shipping Address
//	lwc_update_order_meta( $trx_id, "_shipping_country", "ID" );
//	lwc_update_order_meta( $trx_id, "_shipping_state", 104 );
//	lwc_update_order_meta( $trx_id, "_shipping_city", 10412 );
//	lwc_update_order_meta( $trx_id, "_shipping_address", "Desa Sana" );
//	lwc_update_order_meta( $trx_id, "_shipping_postal_code", 15560 );

}

add_action( "lokuswp/transaction/save", "lwc_pro_create_transaction", 10, 2 );
function lwc_pro_create_transaction( $trx_id, $trx_data ) {
	$inv_number = 'INV/' . lwp_current_date( 'ymd' ) . '/' .
	              lwp_number_to_roman( lwp_current_date( 'y' ) ) . '/' .
	              lwp_number_to_roman( lwp_current_date( 'n' ) ) . '/' .
	              str_pad( $trx_id, 7, '0', STR_PAD_LEFT
	              );
	lwc_update_order_meta( $trx_id, "_billing_invoice", $inv_number );
}