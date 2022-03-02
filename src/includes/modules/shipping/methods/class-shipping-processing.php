<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Shipping_Processing {

	public function __construct() {
		add_filter( 'lwcommerce/shipping/gateway/jne', [ $this, 'shipping_processing' ], 10, 2 );
		add_filter( 'lwcommerce/shipping/gateway/pos', [ $this, 'shipping_processing' ], 10, 2 );
	}

	public function shipping_processing( $shipping, $transaction ) {
		$shipping_id = $shipping['courier'];
		$service     = $shipping['service'];
		$destination = $shipping['destination'];
		$weight      = $shipping['weight'] ?? '1';

		$cost = lwpc_get_cost_rajaongkir( $shipping_id, $service, $destination, $weight );

		if ( ! $cost ) {
			return 'shipping not found';
		}

		$total                        = $transaction['total'] + $cost['cost'];
		$transaction['total']         = $total;
		$transaction['shipping_cost'] = $cost['cost'];

		return $transaction;
	}
}

new Shipping_Processing;
