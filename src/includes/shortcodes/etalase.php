<?php 
namespace LokusWP\Commerce\Shortcodes;

Class Etalase
{
    /**
     * Register Transaction Shortcode
     */
    public function __construct()
    {
        add_shortcode('lwpcommerce_etalase', [$this, 'render']);
    }

    public function render($atts)
    {
        extract(shortcode_atts(array(
            'product_ids' => false,
        ), $atts));

        ob_start();

        echo "Shortcode Etalase";

        $render = ob_get_clean();

        return $render;
    }
}