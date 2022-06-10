<?php
/**
 * Display Order Number and Status
 * in Post Checkout
 *
 * @since 0.1.0
 */
add_action( "lokuswp/post-checkout/status", "lwc_post_transaction_field", 10, 1 );
function lwc_post_transaction_field( $trx_uuid ) {
	?>
    {{#order_id}}
    <div class="row mb-2" style="margin-top:12px;">
        <div class="col-xs-6"><?php _e( "Order Number", "lwcommerce" ); ?></div>
        <div id="lwc-order-id" class="col-xs-6 txt-right"><strong>#{{order_id}}</strong></div>
    </div>
    {{/order_id}}

    {{#order_status_text}}
    <div class="row mb-2">
        <div class="col-xs-6"><?php _e( "Order Status", "lwcommerce" ); ?></div>
        <div id="lwc-order-status" class="col-xs-6 txt-right">{{order_status_text}}</div>
    </div>
    {{/order_status_text}}
	<?php
}

/**
 * Display Download Section
 * in Post Checkout
 *
 * @since 0.1.0
 */
add_action( "lokuswp/post-checkout/after", "lwc_set_downloads_in_checkout", 10, 1 );
function lwc_set_downloads_in_checkout( $trx_uuid ) {
	?>
    {{#download_granted}}
    <div id="lwcommerce-downloads">
        <h6 style="font-weight: 600;"><?php _e( "Downloads", "lwcommerce" ); ?> </h6>

        <table class="table table-borderless spacing no-border">
            <tbody>
            {{#downloads}}
            <tr>
                <td class="product-thumbnail">
                    <a href="{{product_link}}">
                        <img src="{{product_thumbnail}}" alt="{{product_title}}">
                    </a>
                </td>

                <td class="product-item">
                    <h6>{{product_title}}</h6>
                    <strong>{{product_attachment_version}}</strong>
                </td>
                <td class="txt-right" style="width: 50px">
                    <a href="{{product_attachment_url}}"
                       class="lokus-btn btn-primary btn-block">
						<?php _e( "Download", "lwcommerce" ); ?>
                    </a>
                </td>
            </tr>
            {{/downloads}}
            </tbody>
        </table>
    </div>
    {{/download_granted}}

    <style>
        .no-border {
            border: none !important;
        }

        td {
            padding: 0 !important;
            vertical-align: middle !important;
        }

        #lwcommerce-downloads {
            padding: 12px;
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
	<?php
}

/**
 * Transaction Response
 *
 * @since 0.1.0
 */
add_filter( "lokuswp/rest/transaction/response", "lwc_transaction_response", 10, 2 );
function lwc_transaction_response( $response, $trx_id ) {

	$order_status = lwc_get_order_meta( $trx_id, "_order_status", true );
	$title        = __( "Pending", "lwcommerce" );
	$description  = __( "Deskripsi Pesanan", "lwcommerce" );

	switch ( $order_status ) {
		case 'pending':
			$title       = __( "Pending", "lwcommerce" );
			$description = __( "Deskripsi Pesanan", "lwcommerce" );
			break;
		case 'completed':
			$title       = __( "Pesanan telah Selesai", "lwcommerce" );
			$description = __( "Pesanan anda telah sampai, jika terdapat kendala anda bisa hubungi kami.", "lwcommerce" );
			break;
		case 'cancelled':
			$title       = __( "Order Cancelled", "lwcommerce" );
			$description = __( "Please be patient", "lwcommerce" );
			break;
		case 'shipped':
			$title       = __( "Order Shipped", "lwcommerce" );
			$description = __( "Please be patient", "lwcommerce" );
			break;
		case 'processing':
			$title       = __( "Order Processing", "lwcommerce" );
			$description = __( "Please be patient", "lwcommerce" );
			break;
	}

	$support = "https://wa.me" . lwp_get_option( 'support_phone' );

	// Screen Based on Status
	$response['screen'] = array(
		"title"       => $title,
		"thumbnail"   => LWC_URL . "src/public/assets/images/illustration-" . $order_status . ".jpg",
		"description" => $description,
		"support"     => $support
	);

	// Navigation
	$response['nav_title']       = $order_status == "pending" ? __( "Instruksi Pembayaran", "lwcommerce" ) : __( "Terimakasih", "lwcommerce" );
	$response['nav_history_url'] = get_permalink( lwp_get_ID_by_shortcode( "lwcommerce_order_history" ) );

	$response['btn_text'] = __( "Manual Confirmation", "lwcommerce" );
	$response['btn_url']  = lwp_get_settings( 'settings', 'confirmation_link', 'esc_url' );

	$response['order_status']      = $order_status;
	$response['order_status_text'] = lwp_get_transaction_status_text( $order_status );
	$response['order_id']          = lwc_get_order_meta( $trx_id, "_order_id", true );

	return $response;
}

add_filter( "lokuswp/rest/transaction/response", "lwc_download_response", 10, 2 );
function lwc_download_response( $response, $trx_id ) {
    
	$trx        = lwp_get_transaction( $trx_id );
	$trx_status = $trx['status'];
	$cart_uuid  = $trx['cart_uuid'];

	$cart = lwp_get_cart_by( "cart_uuid", $cart_uuid, "on-transaction" );

	if ( $cart && $trx_status == "paid" ) {
		$response['download_granted'] = true;
		foreach ( $cart as $item ) {
			$response['downloads'][] = [
				"product_title"              => html_entity_decode( get_the_title( $item->post_id ) ),
				"product_thumbnail"          => get_the_post_thumbnail_url( $item->post_id ),
				"product_attachment_version" => get_post_meta( $item->post_id, "_attachment_version", true ),
				"product_attachment_url"     => get_post_meta( $item->post_id, "_attachment_link", true ),
				"product_link"               => get_permalink( $item->post_id )
			];
		}
	}

	return $response;
}

//add_action( "lokuswp/transaction/save", "lwc_create_transaction", 10, 2 );
//function lwc_create_transaction( $trx_id, $trx_data ) {
//	//lwp_set_error( "lwc_create_transaction", __( "Transaction Failed", "lokuswp-xendit" ) );
//}