<?php 

/**
 * Getting Price Normal of Product based on ID
 */
function lwpc_get_price_normal( $post_id ){
    $_price_normal = get_post_meta($post_id, '_price_normal', true );
    return isset( $_price_normal ) ? abs( $_price_normal ) : 0;
}
function lwpc_get_price_discount( $post_id ){
    $_price_discount = get_post_meta($post_id, '_price_discount', true );
    return isset( $_price_discount ) ? abs( $_price_discount ) : 0;
}

function lwpc_get_stock(){}