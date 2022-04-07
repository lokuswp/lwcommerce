<?php
/**
 * Business Logic of Ecommerce
 *
 * @since 0.5.0
 */
add_filter( "lokuswp/transaction/logic", "lwc_transaction_logic", 10, 1 );
function lwc_transaction_logic( $transaction ) {

	// Business Logic For Digital Product and Free
	$cart_uuid = $transaction['cart'];

	$product_types = lwc_get_product_types( $cart_uuid );
	$subtotal      = lwc_get_subtotal( $cart_uuid );

	// Business Logic :: Only Free Product Digital
	if ( $subtotal == 0 && ! in_array( 'physical', $product_types ) && $product_types[0] == "digital" ) {

		// Create Transaction
		$trx_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_coupon( $transaction['coupon_code'] )
			->set_payment( $transaction['payment_id'] )
			->set_user_fields( $transaction['user_fields'] )
			->set_paid()
			->create();

		// Create Order Meta
		lwc_update_order_meta( $trx_id, "_order_id", $trx_id );
		lwc_update_order_meta( $trx_id, "_order_status", "completed" ); //[ "pending", "processing", "cancelled", "shipping", "completed" ]

		lwc_update_order_meta( $trx_id, "_billing_name", lwp_get_transaction_meta( $trx_id, "_user_field_name" ) );
		lwc_update_order_meta( $trx_id, "_billing_phone", lwp_get_transaction_meta( $trx_id, "_user_field_phone" ) );
		lwc_update_order_meta( $trx_id, "_billing_email", lwp_get_transaction_meta( $trx_id, "_user_field_email" ) );

		// Pro Version :: Set Notification for Admin
		// as_schedule_single_action(strtotime( '+100 seconds' ), 'lokuswp_notification', array( $trx_id . '-admin' ), "lwcommerce");
		// as_schedule_single_action( strtotime( '+7 seconds' ), 'lwcommerce_shipping', array( $trx_id . '-shipping' ), "lwcommerce" );

		// Set Notification Shipping
		lwc_update_order_meta( $trx_id, "_shipping_type", "digital" );

		// Set Notification Completed
		as_schedule_single_action( strtotime( '+3 seconds' ), 'lokuswp_notification', array( $trx_id . '-completed' ), "lwcommerce" );
	}

	// Business Logic :: Only Paid Product Digital
	if ( $subtotal > 0 && ! in_array( 'physical', $product_types ) && $product_types[0] == "digital" ) {
		$trx_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_coupon( $transaction['coupon_code'] )
			->set_payment( $transaction['payment_id'] )
			->set_user_fields( $transaction['user_fields'] )
			->create();
	}

//	// Business Logic :: Paid Product Physical
//	if ( $subtotal > 0 && ! in_array( 'digital', $product_types ) && $product_types[0] == "phsyical" ) {
//		$transaction_id = ( new LWP_Transaction() )
//			->set_cart( $cart_uuid )
//			->set_payment( 'bank-transfer' )
//			->set_user_shipping( "digital", [ "shipper" => "smtp", "service" => "regular" ] )
//			->set_user_fields( 'name', 'Test Name' )
//			->create();
//	}
//
//	// Business Logic :: Paid Product Physical and Digital
//	if ( $subtotal > 0 && in_array( 'digital', $product_types ) || in_array( 'physical', $product_types ) ) {
//		$transaction_id = ( new LWP_Transaction() )
//			->set_cart( $cart_uuid )
//			->set_payment( 'bank-transfer' )
//			->set_user_shipping( "digital", [ "shipper" => "smtp", "service" => "regular" ] )
//			->set_user_shipping( "physical", [ "shipper" => "jne", "service" => "oke" ] )
//			->set_shipping_address( "rumah", [ "country" => "ID", "state" => "Banten", "city" => "Kab Tangerang", "address" => "Alamat Rumah", "zip_code" => 15561 ] )
//			->set_user_fields( 'name', 'Test Name' )
//			->create();
//	}

	return $trx_id;
}

/**
 * Transaction Response
 *
 * @since 0.5.0
 */
add_filter( "lokuswp/rest/transaction/response", "lwc_transaction_response", 10, 2 );
function lwc_transaction_response( $response, $trx_id ) {
	$response['screen'] = array(
		"title"       => "Pesanan sudah selesai",
		"thumbnail"   => "https://zerodha.com/static/images/img3.png",
		"description" => "Deskripsi",
		"support"     => "https://wa.me/624115151"
	);

	$order_status                  = lwc_get_order_meta( $trx_id, "_order_status", true );
	$response['btn_text']          = "Konfirmasi Pembayaran";
	$response['btn_url']           = "https://google.com";
	$response['order_status']      = $order_status;
	$response['order_status_text'] = lwp_get_transaction_status_text( $order_status );
	$response['order_id']          = lwc_get_order_meta( $trx_id, "_order_id", true );

	return $response;
}

/**
 * Transaction Status Text
 *
 * @since 0.5.0
 */
add_filter( "lokuswp/transaction/status/text", "lwc_transaction_status_text", 10, 1 );
function lwc_transaction_status_text( $statuses ) {
	$statuses['completed'] = __( "Selesai", "lwcommerce" );

	return $statuses;
}

/**
 * Download Section
 *
 * @since 0.5.0
 */
add_action( "lokuswp/after-checkout/after", "lwc_set_downloads_in_checkout", 10, 2 );
function lwc_set_downloads_in_checkout( $trx_uuid ) {

	if ( ! $trx_uuid ) {
		return;
	}

	$cart = lwp_get_cart_by( 'transaction_uuid', $trx_uuid, 'on-transaction' );

	if ( isset( $cart ) ) :?>
        <h5><?php _e( "Downloads", "lokuswp" ); ?> </h5>

        <table class="table table-borderless spacing">
            <tbody>

			<?php foreach ( $cart as $item ) : ?>
                <tr>
                    <td class="product-thumbnail">
                        <a href="<?php echo get_permalink( $item->post_id ); ?>">
                            <img src=<?php echo get_the_post_thumbnail_url( $item->post_id ); ?>" alt="<?php echo get_the_title( $item->post_id ); ?>
                            ">
                        </a>
                    </td>
                    <td class="product-item">
                        <h6><?php echo get_the_title( $item->post_id ); ?></h6>
                        <strong><?php echo get_post_meta( $item->post_id, "_attachment_version", true ); ?></strong>
                    </td>
                    <td class="txt-right">
                        <a href="<?php echo get_post_meta( $item->post_id, "_attachment_link", true ) ?? "#"; ?>"
                           class="lokus-btn btn-primary btn-block">
							<?php _e( "Download", "lokuswp" ); ?>
                        </a>
                    </td>
                </tr>
			<?php endforeach; ?>

            </tbody>
        </table>

        <style>
            td {
                padding: 0 !important;
                vertical-align: middle !important;
            }

            .product-thumbnail {
                width: 15%;
            }

            .product-thumbnail img {
                width: 54px;
                border-radius: 8px;
                vertical-align: middle;
            }

            .product-item h6 {
                margin: 0;
            }

        </style>
	<?php endif;
}

/**
 * Product Item Filter
 *
 * @return mixed
 */
add_filter( 'lokuswp/cart/rest/item', 'lwc_rest_cart_item_output', 10, 1 );
function lwc_rest_cart_item_output( $item_data ) {

	$item_id = $item_data['post_id'];

	// WP Common Data
	$item_data['title']     = html_entity_decode( get_the_title( $item_id ), ENT_NOQUOTES, 'UTF-8' );
	$item_data['thumbnail'] = get_the_post_thumbnail_url( $item_id ) ?? LOKUSWP_URI . "src/assets/images/thumbnail-600x350.png";

	if ( get_post_type( $item_id ) == 'product' ) {
		//	$variation_id = $item_data['variation_id'];
		$item_data['product_type'] = empty( get_post_meta( $item_id, '_product_type', true ) ) ? 'undefined' : esc_attr( get_post_meta( $item_id, '_product_type', true ) );
		$item_data['unit_price']   = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_unit_price', true ) ?? null;
		$item_data['price_promo']  = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_price_promo', true ) ?? null;
		$item_data['price_text']   = lwc_get_price_html( $item_id );
		$item_data['weight']       = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_weight', true ) ?? 0;
		$item_data['stock']        = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_stock', true ) ?? 0;
		$item_data['stock_unit']   = get_post_meta( $item_id, '_stock_unit', true ) ?? '';
	}

	$item_data['amount'] = abs( lwc_get_price( $item_id ) ) * abs( $item_data['quantity'] );

	return $item_data;
}

/**
 * Transaction Status
 */
add_action( "lokuswp/after-checkout/status", "lwp_after_checkout_status", 10, 1 );
function lwp_after_checkout_status( $data ) {
	?>
    <div class="row mb-2" style="margin-top:12px;">
        <div class="col-xs-6"><?php _e( "Order Number", "lwcommerce" ); ?></div>
        <div id="lwc-order-id" class="col-xs-6 txt-right"><strong>#1</strong></div>
    </div>

    <div class="row mb-2">
        <div class="col-xs-6"><?php _e( "Order Status", "lwcommerce" ); ?></div>
        <div id="lwc-order-status" class="col-xs-6 txt-right"><?php _e( "Awaiting Payment", "lwcommerce" ); ?></div>
    </div>
	<?php
}

//add_filter( "lokuswp/rest/cart/extras", "lwp_rest_cart_extras", 10, 1 );
//function lwp_rest_cart_extras( $extras ) {
//
//
////	$new['unique_code'] = array(
////		"title"     => "",
////		"text"      => "Unique Code",
////		"amount"    => 214,
////		"operator"  => "+",
////		"formatted" => "Rp 214",
////	);
////
////	array_push( $extras, $new );
//
////	$new[]['biaya_pembayaran'] = array(
////		"title"     => "",
////		"text"      => "Payment Fee",
////		"amount"    => 5000,
////		"operator"  => "+",
////		"formatted" => "Rp 5000",
////	);
//
//	return $extras;
//}