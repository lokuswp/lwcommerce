<?php

namespace LokusWP\Commerce\Shortcodes;

class Product_Listing {

	public function __construct() {
		add_shortcode( 'lwcommerce_product_list', [ $this, 'render' ] );

		add_shortcode( 'lwcommerce_products', [ $this, 'render' ] );
	}

	public function render( $atts ) {
		extract( shortcode_atts( array(
			'product_ids' => false,
			'mobile'      => false,
			'filter'      => '',
			'column'      => 4,
			'mode'        => "",
			'limit'       => -1
		), $atts ) );

		wp_enqueue_style( "lokuswp-grid" );

		wp_enqueue_style( "swiper" );
		wp_enqueue_script( "swiper" );

		ob_start();

		include LWC_PATH . "/src/templates/presenter/product/listing.php";

		return ob_get_clean();
	}
}