<?php
/*****************************************
 * Add Shipping Tab to Checkout Page
 *
 * @since 0.1.0
 *****************************************
 */
add_action( "lokuswp/transaction/tab/header", function () {
	?>
    <div class="swiper-slide">
		<?php _e( 'Shipping', 'lwcommerce' ); ?>
    </div>
	<?php
}, 2 );

add_action( "lokuswp/transaction/tab/content", function () {
	require_once LWC_PATH . 'src/templates/presenter/checkout/shipping.php';
}, 2 );
