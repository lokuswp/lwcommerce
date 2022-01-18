<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Orders_Processing {

	public function __construct() {
		add_filter( 'lwpcommerce/orders', [ $this, 'orders_processing' ], 10, 2 );
	}

	public function orders_processing( $request, $transaction_id ): bool {
		$note     = lwp_recursive_sanitize_text_field( $request['product_note'] );
		$shipping = lwp_recursive_sanitize_text_field( $request['shipping'] );

		$update_shipping = lwpc_update_order_meta( $transaction_id, 'shipping', $shipping );
		foreach ( $note as $kye => $value ) {
			$update_note = lwpc_update_order_meta( $transaction_id, $kye, $value );
		}

		return $update_shipping && $update_note;
	}
}

new Orders_Processing;