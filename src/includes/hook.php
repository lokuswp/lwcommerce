<?php 


// $classmap = array(
//     'LokaWP\Commerce\Plugin' => 'includes/plugin.php',
//     'LokaWP\Commerce\Post_Types' => 'includes/wordpress/posttypes.php',
//     'LokaWP\Commerce\Metabox' => 'includes/wordpress/metabox.php'
// );

add_action("lokuswp/transaction/tab/header", function () {
    ?>
        <!-- <div class="swiper-slide">
            <?php _e('Shipping', 'lokuswp'); ?>
        </div> -->
    <?php
    });
    add_action("lokuswp/transaction/tab/content", function () {
        // require_once LWPC_PATH . 'src/templates/transaction/shipping.php';
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
        }
    
        return $cart_item;
    }
    add_filter("lokuswp/cart/cookie/item", "lwpc_cart_processing", 10, 2);
    

