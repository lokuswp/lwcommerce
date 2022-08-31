<?php

namespace LokusWP\Commerce\Shipping\Rajaongkir;

use LokusWP\Commerce\Shipping\Rajaongkir;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Rajaongkir_Processing {
	public function __construct() {
		add_action( 'lokuswp/transaction/shipping/rajaongkir-jne', [ $this, 'processing' ], 10, 1 );
	}

	public function processing( $shipping ) {
		$origin = lwc_get_settings( 'store', 'city', 'intval' );

		$rajaongkir = Rajaongkir::get_instance();
		$rajaongkir->set_origin( $origin );
		$rajaongkir->set_destination( $shipping['destination'] );
		$rajaongkir->set_weight( $shipping['weight'] );
		$rajaongkir->set_courier( $shipping['courier'] );
		$rajaongkir->set_service( $shipping['service'] );

		$shipping_cost = $rajaongkir->get();

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

new Rajaongkir_Processing;