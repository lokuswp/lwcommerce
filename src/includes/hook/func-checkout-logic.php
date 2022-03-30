<?php

add_filter( "lokuswp/transaction/logic", "lwc_transaction_logic", 10, 1 );
function lwc_transaction_logic( $transaction ) {

	// Business Logic For Digital Product and Free
	$cart_uuid      = $transaction['cart'];
	$cart           = lwp_get_cart_by( "cart_uuid", $cart_uuid );
	$transaction_id = 0;

	$product_types = [];
	$subtotal      = 0;
	foreach ( $cart as $item ) {
		$product_id      = $item->post_id;
		$product_type    = get_post_meta( $product_id, '_product_type', true );
		$product_types[] = $product_type;

		if ( $product_type == 'digital' ) {
			// Shipping Digital

		}

		if ( $product_type == 'physical' ) {
			// Shipping Physical

			// Shipping Cost
			//$courier = isset( $shipping['courier'] ) ? sanitize_text_field( $shipping['courier'] ) : null;
			//if ( has_filter( "lwcommerce/shipping/gateway/{$courier}" ) ) {
			//	$prepared_transaction = apply_filters( "lwcommerce/shipping/gateway/{$courier}", $shipping, $prepared_transaction );
			//}
			//$shipping_cost = $prepared_transaction['shipping_cost'] ?? 0; // Fallback to 0
		}

		$subtotal += lwc_get_price( $product_id );
	}
	$product_type = array_unique( $product_types );

	// Business Logic :: Free Product Digital
	if ( $subtotal == 0 && ! in_array( 'physical', $product_types ) && $product_types[0] == "digital" ) {
		$transaction_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_payment( $transaction['payment_id'] )
			->set_user_shipping( "digital", [ "shipper" => "smtp", "service" => "regular" ] )
			->set_user_fields( $transaction['user_fields'] )
			->set_paid()
			->create();
	}

	// Business Logic :: Paid Product Digital
	if ( $subtotal > 0 && ! in_array( 'physical', $product_types ) && $product_types[0] == "digital" ) {
		$transaction_id = ( new LWP_Transaction() )
			->set_cart( $cart_uuid )
			->set_payment( $transaction['payment_id'] )
			->set_user_shipping( "digital", [ "shipper" => "smtp", "service" => "regular" ] )
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

	return $transaction_id;
}

add_filter( "lokuswp/rest/transaction/response", "lwc_transaction_response", 10, 2 );
function lwc_transaction_response( $response ) {
	$response['screen'] = array(
		"title"       => "Pesanan sudah selesai",
		"thumbnail"   => "https://zerodha.com/static/images/img3.png",
		"description" => "Deskripsi",
		"support"     => "https://wa.me/624115151"
	);

	$response['btn_text']          = "Konfirmasi Pembayaran";
	$response['btn_url']           = "https://google.com";
	$response['order_status']      = "processing";
	$response['order_status_text'] = "Completed";
	$response['order_id']          = 45;

	return $response;
}

add_action( "lokuswp/after-checkout/after", "lwc_after_checkout_after", 10, 2 );
function lwc_after_checkout_after( $trx_uuid ) {

	if ( ! $trx_uuid ) {
		return;
	}

	$cart = lwp_get_cart_by( 'transaction_uuid', $trx_uuid, 'on-transaction');

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


function lwc_rest_cart_item_output( $item_data ) {

	$item_id      = $item_data['post_id'];
//	$variation_id = $item_data['variation_id'];

	if ( get_post_type( $item_id ) == 'product' ) {
		$item_data['unit_price']  = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_unit_price', true ) ?? null;
		$item_data['price_promo'] = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_price_promo', true ) ?? null;
		$item_data['weight']      = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_weight', true ) ?? 0;
		$item_data['stock']       = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_stock', true ) ?? 0;
		$item_data['stock_unit']  = get_post_meta( $item_id, '_stock_unit', true ) ?? '';
	}

	$item_data['amount'] = abs( lwc_get_price( $item_id ) ) * abs( $item_data['quantity'] );

	return $item_data;
}

add_filter( 'lokuswp/cart/rest/item', 'lwc_rest_cart_item_output', 10, 1 );

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