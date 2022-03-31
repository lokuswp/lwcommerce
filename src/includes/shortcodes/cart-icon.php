<?php

namespace LokusWP\Commerce\Shortcodes;

class Cart_Icon {
	/**
	 * Register Transaction Shortcode
	 */
	public function __construct() {
		add_shortcode( 'lwcommerce_cart_icon', [ $this, 'render' ] );
	}

	public function render( $atts ) {
		// extract(shortcode_atts(array(
		//     'product_ids' => false,
		// ), $atts));

		wp_enqueue_style( "lokuswp-grid" );

		ob_start();

		?>
        <a href="<?php echo get_permalink( lwp_get_settings( 'settings', 'cart_page' ) ); ?>">
            <div class="cart-icon-wrapper"></div>
        </a>

        <script>jQuery('.cart-icon-wrapper').html('<div class="cart-icon svg-wrapper"><small class="cart-qty">' + lokusCart.countQty() + '</small><img src="' + lokuswp.plugin_url + 'src/assets/svg/cart.svg' + '" alt="cart-icon"></div>');</script>
		<?php

		$render = ob_get_clean();

		return $render;
	}
}
