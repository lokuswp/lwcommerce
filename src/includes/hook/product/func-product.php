<?php
add_action( "lwcommerce/product/listing/after", "lwc_add_to_cart_html");
function lwc_add_to_cart_html() {
	require LWC_PATH . 'src/templates/component/add-to-cart.php';
}