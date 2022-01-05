<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Shipping_Processing {

	public function __construct() {
		add_filter( 'lwpcommerce/shipping/gateway/jne', [ $this, 'shipping_processing' ], 10, 2 );
		add_filter( 'lwpcommerce/shipping/gateway/pos', [ $this, 'shipping_processing' ], 10, 2 );
	}

	public function shipping_processing( $shipping_id, $transaction ) {
		$service     = $transaction['shipment']['service'];
		$destination = $transaction['shipment']['destination'];

		$cost = lwpc_get_cost_rajaongkir( $shipping_id, $service, $destination );

		if ( ! $cost ) {
			return 'shipping not found';
		}

		$total                = $transaction['total'] + $cost['cost'];
		$transaction['total'] = $total;

		return $transaction;
	}
}
