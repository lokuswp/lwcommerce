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

        <a href="<?php echo get_permalink( lwp_get_settings( 'settings', 'cart_page' ) ); ?>" class="cart-icon">
            <div class="cart-icon-wrapper">
                <div class="cart-icon svg-wrapper">
                    <small class="cart-qty">1</small>
                    <img src="<?= LOKUSWP_URL . 'src/assets/svg/cart.svg'; ?>" alt="cart-icon">
                </div>
            </div>
        </a>

		<?php

		return ob_get_clean();
	}
}
