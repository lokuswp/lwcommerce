<?php

namespace LokusWP\Commerce\Shortcodes;

class Cart_Icon {

	public function __construct() {
		add_shortcode( 'lwcommerce_cart_icon', [ $this, 'render' ] );
	}

	public function render( $atts ) {
		// extract(shortcode_atts(array(
		//     'product_ids' => false,
		// ), $atts));

		wp_enqueue_style( "lokuswp-grid" );

		ob_start();
		// TODO :: Change to Skeleton
		?>



		<?php

		return ob_get_clean();
	}
}
