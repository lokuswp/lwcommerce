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
    $html = '<div class="product-action">
                <button class="add-troli" product-id="">Tambah</button><br>
                <div class="lwpc-stepper">
                    <button type="button" class="minus" data-qty-action="minus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <input min="1" max="2" type="number" value="1" class="val-qty-{{id}}">
                    <button type="button" class="plus" data-qty-action="plus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>';
    echo $html;
}
