<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Activation {
	public static function activate() {

		// Create Folder Storage
		if ( ! is_dir( LWC_STORAGE ) ) {
			mkdir( LWC_STORAGE );
		}
	}
}