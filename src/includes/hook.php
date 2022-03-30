<?php
/**
 * Adding Tab Shipping
 */





/**
 * Processing Cart Data from Cart Cookie
 * Scan Product Price
 */
function lwpc_cart_processing( $cart_item, $post_id ) {
	if ( get_post_type( $post_id ) == 'product' ) {
		$cart_item['price']        = abs( lwc_get_price( $post_id ) );
		$cart_item['unit_price'] = abs( lwc_get_unit_price( $post_id ) );
		$cart_item['price_promo']  = abs( lwc_get_price_promo( $post_id ) );
		$cart_item['min']          = 1;
		$cart_item['max']          = - 1;

		lwp_set_meta_counter( "_product_on_cart", $post_id );
	}

	return $cart_item;
}

add_filter( "lokuswp/cart/cookie/item", "lwpc_cart_processing", 10, 2 );


/**
 * Adding Tab to Customer Area
 */
add_action( "lwcommerce/customer/tab/header", function () {
	?>
    <div class="swiper-slide">
		<?php _e( 'Dashboard', 'lwcommerce' ); ?>
    </div>
	<?php
} );

add_action( "lwcommerce/customer/tab/header", function () {
	?>
    <div class="swiper-slide">
		<?php _e( 'Purchase', 'lwcommerce' ); ?>
    </div>
	<?php
} );
add_action( "lwcommerce/customer/tab/header", function () {
	?>
    <div class="swiper-slide">
		<?php _e( 'Account', 'lwcommerce' ); ?>
    </div>
	<?php
}, 9999 );

add_action( "lwcommerce/customer/tab/content", function () {
	require_once LWC_PATH . 'src/templates/presenter/customer/dashboard.php';
} );

add_action( "lwcommerce/customer/tab/content", function () {
	require_once LWC_PATH . 'src/templates/presenter/customer/purchase.php';
} );

add_action( "lwcommerce/customer/tab/content", function () {
	require_once LWC_PATH . 'src/templates/presenter/customer/account.php';
}, 9999 );

// Pre Transaction
// Generate Unique Code
add_action( "lokuswp/transaction/pre", function () {
	// $unique_code = rand(1, 6);
	// var_dump($unique_code);
} );


add_action( "lokuswp/transaction/after/save", "lwc_shipping_action", 10, 2 );
function lwc_shipping_action( $row_id, $transaction ) {

	// If Product Digital and Transaction Paid
	if ( $transaction->status == "paid" ) {
		as_schedule_single_action( strtotime( '+3 seconds' ), 'lwcommerce_shipping', array( $row_id . '-' . $transaction->status ), "lokuswp" );
	}
}