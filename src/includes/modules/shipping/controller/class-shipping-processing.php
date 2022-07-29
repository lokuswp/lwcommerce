<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Shipping_Processing {

	public function __construct() {
		add_filter( 'lokuswp/transaction/extras/data/shipping', [ $this, 'processing' ], 10, 1 );
	}

	public function processing( $shipping ) {
		$shipping_cost = lwc_get_cost_rajaongkir( $shipping['courier'], $shipping['destination'], $shipping['weight'], $shipping['service'] );

		lwp_add_transaction_extras(
			"shipping",
			$shipping['courier'] . ' ' . $shipping['service'],
			__( "Shipping costs", "lwcommerce-pro" ),
			$shipping_cost['cost'],
			"+",
			"fixed",
			"subtotal"
		);
	}
}

new Shipping_Processing;
