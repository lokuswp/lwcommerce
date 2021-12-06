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

// function that runs when shortcode is called
// function lokuswp_transaction()
// {
// $request = wp_remote_get('http://localhost:10024/wp-json/lokuswp/v1/payment/list');

// if (is_wp_error($request)) {
// 	return false; // Bail early
// }

// $body = wp_remote_retrieve_body($request);
// $parse = json_decode($body);
?>

