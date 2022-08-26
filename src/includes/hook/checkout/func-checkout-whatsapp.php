<?php

/**
 * Transaction Response for Checkout to Whatsapp
 *
 * @since 0.1.0
 */
add_filter( "lokuswp/rest/transaction/response", "lwc_transaction_response_checkout_whatsapp", 10, 2 );
function lwc_transaction_response_checkout_whatsapp( $response, $trx_id ) {

    // Skip when Checkout WHatsapp Off
    if(lwp_get_settings( 'lwcommerce', 'appearance', 'checkout_whatsapp' ) != "on" ){
        return $response;
    }

    // When Subtotal Zero && Item Just Once, With Product Type : Digital && Quantity : 1 = Fail this Feature
    if( abs($response['subtotal']) == 0.0 && count($response['items']) == 1 && $response['items'][0]['product_type'] == 'digital'  && $response['items'][0]['quantity'] == 1 ){
        return $response;
    }

	// Get Transaction by ID
	$transaction = lwp_get_transaction( $trx_id );

	$payment_id   = 'payment-' . $transaction['payment_id'];
	$payment_data = lwp_get_option( $payment_id );

	$object = [];
	if ( ! empty( $payment_data ) && class_exists( $payment_data['payment_class'] ) ) {

		// Getting Template From Payment
		$instance                             = new $payment_data['payment_class'];
		$object                               = [
			'notifiction_block_payment_text' => $instance->notification_text(),
		];
		$object['payment_data']               = (array) $payment_data;
		$object['payment_data']['expired_at'] = date( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( $transaction['created_at'] ) ) );
		$object                               = array_merge( $object, (array) $transaction );
	}

	$transaction = (object) $object;

	$default_template = 'Hi, Saya sudah pesan
ID Pesanan : *#{{order_id}}*

*Detail Pesanan*
{{summary}}

*Pembayaran*
{{payment}}

Tolong segera diproses ya min,
{{order_link}}

ini bukti pembayarannya';

	$checkout_template = lwp_get_settings( 'lwcommerce', 'general', 'checkout_template' );
	$checkout_template = empty( $checkout_template ) ? $default_template : $checkout_template;

	$checkout_template = str_replace( "{{order_id}}", lwc_get_order_meta( $trx_id, "_order_id", true ), $checkout_template );
	$checkout_template = str_replace( "{{payment}}", lwp_get_notification_block_payment_text( $transaction->currency, $transaction ), $checkout_template );
	$checkout_template = str_replace( "{{summary}}", lwp_get_notification_block_summary_text( $transaction->currency, $transaction ), $checkout_template );
	$checkout_template = str_replace( "{{order_link}}", get_permalink( lwp_get_settings( "lokuswp", 'settings', 'checkout_page' ) ) . 'trx/' . $transaction->transaction_uuid, $checkout_template );


	if ( lwp_get_settings( "lwcommerce", "appearance", "checkout_whatsapp" ) == "on" ) {
		$response['checkout_whatsapp'] = urlencode( $checkout_template );
		$response['admin_whatsapp']    = lwp_sanitize_phone( lwp_get_settings( 'lwcommerce', 'store', 'whatsapp' ) );
	}

	return $response;
}