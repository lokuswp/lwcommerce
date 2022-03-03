<?php

namespace LokusWP\Commerce\Shortcodes;

class Customer_Area
{
    /**
     * Register Transaction Shortcode
     */
    public function __construct()
    {
        add_shortcode('lwcommerce_customer_area', [$this, 'render']);
    }

    public function render($atts)
    {
        // extract(shortcode_atts(array(
        //     'product_ids' => false,
        // ), $atts));
        wp_enqueue_style("lokuswp-swiper");

        wp_enqueue_script("lokuswp-swiper");
        wp_enqueue_style("lokuswp-grid");

        ob_start();

        require_once LWC_PATH . 'src/templates/presentation/customer/area.php';

        $render = ob_get_clean();

        return $render;
    }
}
