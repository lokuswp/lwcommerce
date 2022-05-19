<?php
/**
 * Template : Order History
 *
 * @since 0.1.0
 */
?>

<section id="lwcommerce-order-history">
    <h5><?php _e( "Order History", "lwcommerce" ); ?></h5>

    <div class="lwp-list-card">
		<?php
		// Getting Data based on Cookie
		$buckets = isset( $_COOKIE['_lokuswp_bucket'] ) ? $_COOKIE['_lokuswp_bucket'] : "{}";

		// Empty Bucket
		if ( ! $buckets || $buckets == "{}" ) {
			$buckets = null;
		}

		// Processing Order History
		if ( $buckets ) :
			$buckets = json_decode( stripslashes( $buckets ) );

			foreach ( $buckets as $id ) :

				if ( ! $id ) {
					continue;
				}

				$trx       = (object) lwp_get_transaction_by_uuid( $id );
				$trx_id    = $trx->transaction_id;
				$cart_uuid = $trx->cart_uuid;
				$cart      = lwp_get_cart_by( "cart_uuid", $cart_uuid, 'on-transaction' );

				$order_id      = lwc_get_order_meta( $trx_id, '_order_id' );
				$order_date    = human_time_diff( strtotime( $trx->created_at ), strtotime( (string) lwp_current_date() ) ) . ' ' . __( 'ago', 'lwcommerce' );
				$first_product = isset( $cart[0] ) ? abs( $cart[0]->quantity ) . ' x ' . html_entity_decode( get_the_title( $cart[0]->post_id ) ) : '';
				$count_product = count( $cart );
				$order_status  = lwc_get_order_meta( $order_id, '_order_status' );
				$order_link    = get_permalink( lwp_get_settings( 'settings', 'checkout_page' ) ) . '/' . $trx->transaction_uuid;
				?>

                <div class="lwc-card">
                    <a href="<?= $order_link; ?>" target="_blank">
                        <div class="row lwc-card-content">
                            <div class="col-6">
								<?php _e( "Order ID", "lwcommerce" ); ?> #<?= $order_id; ?>
                                <small><?= $first_product; ?></small>
                                <br>
                                <span><?= $order_date; ?></span>
                            </div>
                            <div class="col-6">
                                <p class="item-order"><?= $count_product; ?> <?php _e( "Item", "lwcommerce" ); ?> </p>
                                <br>
                                <br>
                                <span class="status-order">
                                <?= ucfirst( $order_status ); ?>
                            </span>
                            </div>
                        </div>
                    </a>
                </div>

			<?php endforeach;
		else:
			_e( "No orders have been made yet", "lwcommerce" );
		endif; ?>
    </div>

    <p style="text-align:center; margin:30px auto;font-weight:600;">Powered by LWCommerce</p>
</section>

<style>
    #lwcommerce-order-history {
        max-width: 480px;
        margin: 0 auto;
    }

    #lwcommerce-order-history h5 {
        font-size: 18px;
        margin: 14px 0;
    }

    small {
        display: block;
    }

    .lwc-card {
        border: 1px solid #ddd;
        padding: 12px;
        border-radius: 8px;
        position: relative;
        margin: 12px 0;
    }

    .lwc-card a {
        text-decoration: none;
        color: #2a2a2a;
    }

    .status-order {
        position: absolute;
        bottom: 0;
        right: 0;
        padding: 12px;
    }

    .item-order {
        position: absolute;
        top: 0;
        right: 0;
        padding: 12px;
    }
</style>