<section id="lwcommerce-order-history">
    <h5>Order History</h5>
    <div class="lwp-list-card">

		<?php
		$buckets = isset( $_COOKIE['_lokuswp_beta_bucket'] ) ? $_COOKIE['_lokuswp_beta_bucket'] : array();
        if( $buckets ){
	        $buckets = stripslashes( $buckets );
	        $buckets = json_decode( $buckets );
        }

		?>

		<?php foreach ( $buckets as $id ) : ?>
			<?php
//			$trx    = (object) lwp_get_transaction_by_uuid( $id );
//			$trx_id = $trx->transaction_id;
			?>

            <div class="lwc-card">
                <div class="row lwc-card-content">
                    <div class="col-6">
                        No Pesanan #51242
                        <small>1 x LWCommerce</small>
                        <br>
                        <span>3 Jam yang lalu</span>
                    </div>
                    <div class="col-6">
                        <p class="item-order"> 1 Barang</p>
                        <br>
                        <br>
                        <span class="status-order">
                        Selesai SVG
                    </span>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>

    </div>
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