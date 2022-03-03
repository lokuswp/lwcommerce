<?php
/**
 * Adding Tab Shipping 
 */
add_action("lokuswp/transaction/tab/header", function () {
?>
    <div class="swiper-slide">
		<?php _e( 'Shipping', 'lwcommerce' ); ?>
    </div>
	<?php
} );

add_action( "lokuswp/transaction/tab/content", function () {
	require_once LWC_PATH . 'src/templates/presentation/transaction/shipping.php';
} );

function lwpc_cart_data_processing( $data ) {

	$item_id              = $data['post_id'];
	$variation_id         = $data['variation_id'];
	$data['price_normal'] = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_price_normal', true ) ?? '';
	$data['price_promo']  = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_price_promo', true ) ?? '';
	$data['weight']       = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_weight', true ) ?? 0;
	$data['stock']        = get_post_meta( ! empty( $variation_id ) ? $variation_id : $item_id, '_stock', true ) ?? '';
	$data['stock_unit']   = get_post_meta( $item_id, '_stock_unit', true ) ?? '';

	// Support variation
	$data['calc_price'] = abs(lwpc_get_price($item_id)) * abs($data['quantity']);

	return $data;
}

add_filter( 'lokuswp/cart/data', 'lwpc_cart_data_processing', 10, 1 );


/**
 * Processing Cart Data from Cart Cookie
 * Scan Product Price
 */
function lwpc_cart_processing( $cart_item, $post_id ) {
	if ( get_post_type( $post_id ) == 'product' ) {
		$cart_item['price'] = abs( lwpc_get_price( $post_id ) );
		$cart_item['price_normal'] = abs( lwpc_get_normal_price( $post_id ) );
		$cart_item['price_promo'] = abs( lwpc_get_promo_price( $post_id ) );
		$cart_item['min']   = 1;
		$cart_item['max']   = - 1;

		lokuswp_set_meta_counter( "_product_on_cart", $post_id );
	}

	return $cart_item;
}
add_filter("lokuswp/cart/cookie/item", "lwpc_cart_processing", 10, 2);


/**
 * Adding Tab to Customer Area
 */
add_action("lwcommerce/customer/tab/header", function () {
?>
    <div class="swiper-slide">
        <?php _e('Dashboard', 'lwcommerce'); ?>
    </div>
<?php
});

add_action("lwcommerce/customer/tab/header", function () {
?>
    <div class="swiper-slide">
		<?php _e( 'Purchase', 'lwcommerce' ); ?>
    </div>
<?php
});
add_action("lwcommerce/customer/tab/header", function () {
?>
    <div class="swiper-slide">
		<?php _e( 'Account', 'lwcommerce' ); ?>
    </div>
<?php
}, 9999);

add_action("lwcommerce/customer/tab/content", function () {
    require_once LWC_PATH . 'src/templates/presentation/customer/dashboard.php';
});

add_action( "lwcommerce/customer/tab/content", function () {
	require_once LWC_PATH . 'src/templates/presentation/customer/purchase.php';
} );

add_action("lwcommerce/customer/tab/content", function () {
    require_once LWC_PATH . 'src/templates/presentation/customer/account.php';
}, 9999);

// Pre Transaction
// Generate Unique Code
add_action("lokuswp/transaction/pre", function () {
    // $unique_code = rand(1, 6);
    // var_dump($unique_code);
});
