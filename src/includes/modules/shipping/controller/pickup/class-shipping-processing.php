<?php

namespace LokusWP\Commerce\Shipping\Takeaway;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Takeaway_Processing {
	public function __construct() {
		add_action( 'lokuswp/transaction/shipping/pickup', [ $this, 'processing' ], 10, 1 );
	}

	public function processing( $shipping ) {
		lwp_add_transaction_extras(
			"shipping",
			'pickup',
			__( "Shipping costs", "lwcommerce" ),
			0,
			"+",
			"fixed",
			"subtotal"
		);
	}
}

new Takeaway_Processing;