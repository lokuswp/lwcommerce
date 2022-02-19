<?php

namespace LokusWP\Commerce\Shortcodes;

class Products
{
    /**
     * Register Transaction Shortcode
     */
    public function __construct()
    {
        add_shortcode('lwpcommerce_products', [$this, 'render']);
    }

    public function render($atts)
    {
        extract(shortcode_atts(array(
            'product_ids' => false,
        ), $atts));

        wp_enqueue_style("lokuswp-grid");

        ob_start();

        require_once LWPC_PATH . 'src/templates/presentation/product/product_list.php';

        $render = ob_get_clean();

        return $render;
    }
}