<?php
/**
 * Transaction Status
 */
add_action( "lokuswp/post-checkout/status", "lwp_after_checkout_status", 10, 1 );
function lwp_after_checkout_status( $trx_uuid ) {
//    $trx_id = lwp_get_transaction_by_uuid( $trx_uuid )['transaction_id'];

	?>
    <div class="row mb-2" style="margin-top:12px;">
        <div class="col-xs-6"><?php _e( "Order Number", "lwcommerce" ); ?></div>
        <div id="lwc-order-id" class="col-xs-6 txt-right"><strong>#{{order_id}}</strong></div>
    </div>

    <div class="row mb-2">
        <div class="col-xs-6"><?php _e( "Order Status", "lwcommerce" ); ?></div>
        <div id="lwc-order-status" class="col-xs-6 txt-right">{{order_status_text}}</div>
    </div>
	<?php
}

/**
 * Download Section
 *
 * @since 0.1.0
 */
add_action( "lokuswp/after-checkout/after", "lwc_set_downloads_in_checkout", 10, 2 );
function lwc_set_downloads_in_checkout( $trx_uuid ) {

	if ( ! $trx_uuid ) {
		return;
	}

	$cart = lwp_get_cart_by( 'transaction_uuid', $trx_uuid, 'on-transaction' );

	if ( isset( $cart ) ) :?>
        <div id="lwcommerce-downloads">
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
        </div>
        <style>
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
	<?php endif;
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
		case 'shipping':
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