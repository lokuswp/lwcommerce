<?php

namespace LokusWP\Commerce\Shortcodes;

class Etalase
{
    /**
     * Register Transaction Shortcode
     */
    public function __construct()
    {
        add_shortcode('lwpcommerce_customer_area', [$this, 'render']);
    }

    public function render($atts)
    {
        // extract(shortcode_atts(array(
        //     'product_ids' => false,
        // ), $atts));

        wp_enqueue_style("lokuswp-grid");

        ob_start();

        require_once LWPC_PATH . 'src/templates/product/etalase.php';

        $render = ob_get_clean();

        return $render;
    }
}
