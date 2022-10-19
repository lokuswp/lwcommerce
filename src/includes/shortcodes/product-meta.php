<?php

namespace LokusWP\Commerce\Shortcodes;

class Product_Meta {

	public function __construct() {
		add_shortcode( 'lwcommerce_count', [ $this, 'render' ] );
	}

	public function render( $atts ) {
		extract( shortcode_atts( array(
			'pid' => get_the_ID(),
		), $atts ) );

//		return lwp_set_meta_counter()

		return ob_get_clean();
	}
}