<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Orders_Processing {

	public function __construct() {
		add_action( 'lokuswp/transaction/precommit', [ $this, 'orders_processing' ], 10, 2);
	}

	public function orders_processing( $request, $transaction_id ): bool {
		$shipping = isset($request['shipping']) ? lwp_recursive_sanitize_text_field($request['shipping']) : [];

		lwpc_update_order_meta( $transaction_id, 'shipping', $shipping );
		lwpc_update_order_meta( $transaction_id, 'status', 'hold' );

		return true;
	}
}

new Orders_Processing;