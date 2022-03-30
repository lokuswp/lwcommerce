<?php

namespace LokusWP\Commerce\Shortcodes;

class Product_Listing
{
    /**
     * Register Transaction Shortcode
     */
    public function __construct()
    {
        add_shortcode('lwcommerce_product_listing', [$this, 'render']);
    }

    public function render($atts)
    {
        extract(shortcode_atts(array(
            'product_ids' => false,
        ), $atts));

        wp_enqueue_style("lokuswp-grid");

        ob_start();

        include_once LWC_PATH . "/src/templates/presenter/product/listing.php";

        return ob_get_clean();
    }
}