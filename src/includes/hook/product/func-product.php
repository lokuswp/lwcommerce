<?php
add_action( "lwcommerce/product/listing/after", "lwc_add_to_cart_html", 10, 2 );
function lwc_add_to_cart_html( $product_id, $options ) {
	require LWC_PATH . 'src/templates/component/add-to-cart.php';
}