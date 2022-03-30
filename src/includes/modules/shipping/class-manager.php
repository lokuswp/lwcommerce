<?php

namespace LokusWP\Commerce\Shipping;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Manager
{
	public static $carriers = [];

	public static function register(Gateway $item)
	{
		self::$carriers[] = $item;

		return self::$carriers;
	}

	public static function registered(): array
	{

		return self::$carriers;
	}
}
