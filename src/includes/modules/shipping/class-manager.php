<?php

namespace LokusWP\Commerce\Shipping;

// Checking Test Env and Direct Access File
if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Manager {
	public static $_shipping = array();

	public static function register( Gateway $payment_instance ): bool {
		self::$_shipping[ $payment_instance->get_ID() ] = $payment_instance;

		return true;
	}
}