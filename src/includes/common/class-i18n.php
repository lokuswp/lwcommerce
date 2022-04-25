<?php

namespace LokusWP\Commerce;

// Exit if accessed directly
if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class i18n {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'lwcommerce', false, LWC_PATH . '/languages/' );
	}
}

new i18n();
