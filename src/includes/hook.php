<?php
// Transaction UI Inject
add_action("lokuswp/transaction/tab/header", function () {
?>
    <div class="swiper-slide">
        <?php _e('Shipping', 'lwpcommerce'); ?>
    </div>
<?php
});

add_action("lokuswp/transaction/tab/content", function () {
    require_once LWPC_PATH . 'src/templates/presentation/transaction/shipping.php';
});



/**
 * Processing Cart Data from Cart Cookie
 * Rendered based on Ecommerce Plugin for Respect Another Plugin
 */
function lwpc_cart_processing($cart_item, $post_id)
{
    if (get_post_type($post_id) == 'product') {
        $cart_item['price']     = abs(lwpc_get_price($post_id));
        $cart_item['min']       = 1;
        $cart_item['max']       = -1;

        lokuswp_set_meta_counter("_product_on_cart", $post_id);
    }

    return $cart_item;
}
add_filter("lokuswp/cart/cookie/item", "lwpc_cart_processing", 10, 2);

add_action("lwpcommerce/customer/tab/header", function () {
    ?>
        <div class="swiper-slide">
            <?php _e('Dashboard', 'lwpcommerce'); ?>
        </div>
    <?php
    });
    

add_action("lwpcommerce/customer/tab/header", function () {
?>
    <div class="swiper-slide">
        <?php _e('Transactions', 'lwpcommerce'); ?>
    </div>
<?php
});


add_action("lwpcommerce/customer/tab/header", function () {
?>
    <div class="swiper-slide">
        <?php _e('Account', 'lwpcommerce'); ?>
    </div>
<?php
}, 9999);
add_action("lwpcommerce/customer/tab/content", function () {
    require_once LWPC_PATH . 'src/templates/presentation/customer/dashboard.php';
});

add_action("lwpcommerce/customer/tab/content", function () {
    require_once LWPC_PATH . 'src/templates/presentation/customer/history.php';
});


add_action("lwpcommerce/customer/tab/content", function () {
    require_once LWPC_PATH . 'src/templates/presentation/customer/account.php';
}, 9999);



add_action("lokuswp/transaction/pre", function () {
    // $unique_code = rand(1, 6);
    // var_dump($unique_code);
});
