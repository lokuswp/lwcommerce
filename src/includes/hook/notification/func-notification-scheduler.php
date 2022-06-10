<?php

//add_action( 'lokuswp/transaction/status/paid', 'lwp_transaction_paid', 99, 1 );
//function lwp_transaction_paid( $trx_id ) {
//
//	// Check Subtotal && Digital Type Only
//	$trx           = (object) lwp_get_transaction( $trx_id );
//	$product_types = lwc_get_product_types( $trx->cart_uuid ); // ["digital", "physical"]
//
//	// Check Stock -> Out of Stock
//	//as_schedule_single_action(strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-cancelled' ), "lwcommerce");
//
//	// Only Digital
//	if ( isset( $product_types[0] ) && $product_types[0] == "digital" ) {
//		as_schedule_single_action( strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-completed' ), "lwcommerce" );
//	}
//
//	// Only Physical
//	if ( isset( $product_types[0] ) && $product_types[0] == "physical" ) {
//		as_schedule_single_action( strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-processing' ), "lwcommerce" );
//	}
//
//	// Physical and Digital
//	//	if(in_array( 'physical', $product_types ) || in_array( 'digital', $product_types )) {
//	//		as_schedule_single_action( strtotime( '+100 seconds' ), 'lokuswp_notification', array( $order_id . '-processing' ), "lwcommerce" );
//	//	}
//}


/**
 * Add Property to Object
 *
 * @source Hook Source lokuswp/src/includes/module/notification/channels/class-notification-email.php | prepare_data() | on line 79
 */
add_filter( "lokuswp/notification/email/data", "lwc_notification_email_data", 10, 2 );
function lwc_notification_email_data( $data, $trx_id ) {
	$cart_uuid          = $data['cart_uuid'];
	$post_types_in_cart = lwp_get_post_types_in_cart( 'post', $cart_uuid, 'on-transaction' ); // [ 'product', 'program' ]

	if ( in_array( 'product', $post_types_in_cart ) ) {
		$data['brand_name'] = lwc_get_settings( 'store', 'name' );
		$data['brand_logo'] = lwc_get_settings( 'store', 'logo', 'esc_url', LWC_URL . 'src/admin/assets/images/lwcommerce.png' );
	}

	return $data;
}

