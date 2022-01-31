<?php

namespace LokusWP\Commerce\Shortcodes;

class Storefront
{
    /**
     * Register Transaction Shortcode
     */
    public function __construct()
    {
        add_shortcode('lwpcommerce_storefront', [$this, 'render']);
    }

    public function render($atts)
    {
        extract(shortcode_atts(array(
            'product_ids' => false,
        ), $atts));

        wp_enqueue_style("lokuswp-grid");

        ob_start();

        require_once LWPC_PATH . 'src/templates/presentation/product/storefront.php';

        $render = ob_get_clean();

        return $render;
    }
}
