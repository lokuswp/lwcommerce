<?php

namespace LokusWP\Commerce;

use LokusWP\Commerce\Shipping\Rajaongkir;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Shipping_Processing {

	public function __construct() {
		add_filter( 'lokuswp/transaction/extras/data/shipping', [ $this, 'processing' ], 10, 1 );
	}

	public function processing( $shipping ) {

		if ( $shipping['service'] === 'take-away' ) {
			lwp_add_transaction_extras(
				"shipping",
				'take-away',
				__( "Shipping costs", "lwcommerce" ),
				0,
				"+",
				"fixed",
				"subtotal"
			);

			return;
		}

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

new Shipping_Processing;
