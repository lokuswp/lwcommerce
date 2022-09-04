<?php

namespace LokusWP\Commerce\Shipping;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Shipping_Controller {

	public function __construct() {
		add_action( 'lokuswp/transaction/extras/data/shipping', [ $this, 'index' ], 10, 1 );
	}

	public function index( $shipping ) {
		do_action( "lokuswp/transaction/shipping/{$shipping['provider']}", $shipping );
	}
}

new Shipping_Controller;
