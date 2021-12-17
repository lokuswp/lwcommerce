<?php

/**
 * Getting Price Normal of Product based on ID
 */
function lwpc_get_price($post_id)
{
    $_price_normal = get_post_meta($post_id, '_price', true);
    return isset($_price_normal) ? abs($_price_normal) : 0;
}
function lwpc_get_price_discount($post_id)
{
    $_price_discount = get_post_meta($post_id, '_price_discount', true);
    return isset($_price_discount) ? abs($_price_discount) : 0;
}

function lwpc_get_price_html()
{
    $price = lwpc_get_price(get_the_ID());

    if ($price == 0) {
        $html = '<span style="display:block">' . __("Free", "lwpcommerce") . '</span>';
    } else {
        $html = '<small>' . lwpc_get_price(get_the_ID()) . '</small>';
        $html .= '<span style="display:block">' . lwpc_get_price_discount(get_the_ID()) . '</span>';
    }
    echo ($html);
}


function lwpc_get_stock()
{
}


function lwpc_get_stock_html()
{
    // Get Template ELement
}

function lwpc_add_to_cart_html()
{
    $product_id = get_the_ID();
    require LWPC_PATH . 'src/templates/atomic/molecule/add-to-cart.php';
}
