<?php

namespace LokusWP\Commerce;

// defined( 'ABSPATH' ) or die( 'ABSPATH Not Defined' );

class Frontend
{
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $slug   The string used to uniquely identify this plugin.
     */
    protected $slug;

    /**
     * The Name of Plugin
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $name   The string used to uniquely identify this plugin.
     */
    protected $name;

    /**
     * The current version of the plugin
     *
     * @since   1.0.0
     * @access  protected
     * @var     string  $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Register the admin page class with all the appropriate WordPress hooks.
     *
     * @param array $plugin
     */
    public static function register(array $plugin)
    {
        $public = new self($plugin['slug'], $plugin['name'], $plugin['version']);

        add_action('wp_enqueue_scripts', [$public, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$public, 'enqueue_scripts']);
        // add_action('wp_head', [$public, 'header']);
    }

    /**
     * Constructor function.
     *
     * @param object $parent Parent object.
     */
    public function __construct($slug, $name, $version)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        // // Load Theme CSS
        // wp_register_style('lsdd-theme', plugins_url('/public/assets/css/tm.css', LSDD_BASE), array(), $this->version, 'all');
        // wp_register_style('lsdd-listing', plugins_url('/public/assets/css/listing.css', LSDD_BASE), array(), $this->version, 'all');
        // wp_register_style('lsdd-payment', plugins_url('/public/assets/css/payment.css', LSDD_BASE), array(), $this->version, 'all');
        // wp_register_style('lsdd-thankyou', plugins_url('/public/assets/css/thankyou.css', LSDD_BASE), array(), $this->version, 'all');

        // if (lsdd_get_switch_option('popup_notification')) {
        //     wp_enqueue_style('lsdd-popup', plugins_url('/public/assets/css/popup.css', LSDD_BASE), array(), $this->version, 'all');
        // }

        // // Global Lib
        // wp_register_style('swiper', plugins_url('/includes/core/libraries/js/swiper/swiper.css', LSDD_BASE), array(), '5.3.6', 'all');
        // wp_register_style('lsdd-tab-swiper', plugins_url('/public/assets/css/tab-swiper.css', LSDD_BASE), array(), $this->version, 'all');

        // // Selected Google Font
        // $apperance = lsdd_get_settings('appearance', 'settings');
        // $selected_font = empty($apperance) ? 'Poppins' : esc_attr($apperance);
        // wp_enqueue_style('lsdd-google-fonts', 'https://fonts.googleapis.com/css?family=' . $selected_font . '&display=swap', false);
    }

    /**
     * Register the JavaScript for the public-facing side of the siuse LSDDonation\Licenses;
e.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        // $url_parts = parse_url(get_site_url());
        // if ($url_parts && isset($url_parts['host'])) {
        //     $domain =  $url_parts['host'];
        // }

        wp_enqueue_script('lkc-cookie', plugins_url('/public/assets/js/cookie.js', LKC_BASE), array('jquery'), $this->version, false);
        wp_enqueue_script('lkc-cart', plugins_url('/public/assets/js/cart.js', LKC_BASE), array('jquery'), $this->version, false);

        // wp_register_script('lsdd-payment', plugins_url('/public/assets/js/payment.js', LSDD_BASE), array('jquery'), $this->version, false);
        // wp_register_script('lsdd-navigo', plugins_url('/includes/core/libraries/js/navigo/navigo.min.js', LSDD_BASE), array(), '8.11.0', false);

        wp_enqueue_script($this->slug, plugins_url('/src/public/assets/js/public.js', LWPC_BASE), array('jquery'), $this->version, false);
        // wp_localize_script($this->slug, 'lsdd_public', array(
        //     'ajax_wp' => admin_url('admin-ajax.php'),
        //     'ajax_url' => LSDD_URL . 'core/utils/lsdd-ajax.php',
        //     'ajax_nonce' => wp_create_nonce('lsdd-ajax-nonce'),
        //     'rest_url' => get_rest_url(),
        //     'plugin_url' => LSDD_URL,
        //     'domain_url' => isset($domain) ? $domain : get_site_url(),
        //     'payment_url' => get_permalink(lsdd_get_settings('general_settings', 'payment_page')),
        //     'payment_default' => lsdd_payment_default(),
        //     'options' => array(
        //         'popup' => lsdd_get_switch_option('popup_notification')
        //     ),
        //     'translation' => array(
        //         'cart_empty' => __('Cart Empty', 'lsddonation'),
        //         'select_method' => __('Please select a payment method', 'lsddonation'),
        //         'agree_terms' => __('You must agree Terms and Conditions', 'lsddonation'),
        //         'form_error' => __('Please fill a form correctly', 'lsddonation'),
        //         'minimum_error' => __('Minimum Donation', 'lsddonation'),
        //         'pay_error' => __("Failed to Processing Payment", 'lsddonation'),
        //         'popup_was_donate' => __("has contributed an amount", 'lsddonation'),
        //         'on' => __("on", 'lsddonation'),
        //         'program' => __("Program", 'lsddonation'),
        //     ),
        //     'currency' => array(
        //         'symbol' => lsdd_currency_display(),
        //         'format' => lsdd_currency_display('format'),
        //         'currency' => lsdd_get_currency(),
        //     ),
        // ));

        // // Popup Notification
        // if (lsdd_get_switch_option('popup_notification')) {
        //     wp_enqueue_script('lsdd-popup', plugins_url('/public/assets/js/popup.js', LSDD_BASE), array('jquery'), $this->version, false);
        // }

        // // Global Library
        // wp_register_script('swiper', plugins_url('/includes/core/libraries/js/swiper/swiper.js', LSDD_BASE), array('jquery'), '5.3.6', false);
        // wp_register_script('lsdd-swiper', plugins_url('/public/assets/js/main.js', LSDD_BASE), array('jquery'), $this->version, false);
    }

    /**
     * Setting Unique Code
     *
     * @param array $extras
     * @return void
     */
    function add_unique_code($extras)
    {
        // //Getting ID from Cart
        // if (isset($_COOKIE['_lsdd_cart'])) {
        //     $carts = (array) json_decode(stripslashes($_COOKIE['_lsdd_cart']));
        //     if ($carts) {
        //         $program_id = array_keys($carts)[0];
        //     }
        // }

        // $program_id = isset($program_id) ? $program_id : null;

        // $settings = get_option('lsdd_appearance_settings');
        // $option = isset($settings['lsdd_unique_code']) ? esc_attr($settings['lsdd_unique_code']) : 'off';
        // $minus = isset($settings['lsdd_unique_code_minus']) ? esc_attr($settings['lsdd_unique_code_minus']) : 'off';

        // // Zakat Exception
        // if (get_post_type($program_id) != 'lsdd-zakat') {
        //     if ($option != 'off') {
        //         $unique = array(
        //             array(
        //                 'title' => __('Unique Code', 'lsddonation'),
        //                 'price' => lsdd_generate_uniquecode(),
        //                 'operation' => $minus == 'on' ? '-' : '+',
        //             ),
        //         );

        //         $extras = is_array($extras) ? $extras : [];
        //         $extras = array_merge($unique, $extras);
        //     }
        // }

        // return $extras;
    }



    /**
     * Load ROot CSS for Theming
     *
     * @return void
     */
    public function header()
    {
        // $appearance = get_option('lsdd_appearance_settings', true);

        // $font = !isset($appearance['lsdd_theme_font']) || $appearance['lsdd_theme_font'] == null ? 'Poppins' : $appearance['lsdd_theme_font'];
        // $background = !isset($appearance['lsdd_theme_bg']) || $appearance['lsdd_theme_bg'] == null ? 'transparent' : $appearance['lsdd_theme_bg'];
        // $theme = !isset($appearance['lsdd_theme_color']) || $appearance['lsdd_theme_color'] == null ? '#fe5301' : $appearance['lsdd_theme_color'];

        // $lighter = lsdd_adjust_brightness($theme, 50);
        // $darker = lsdd_adjust_brightness($theme, -40);

        // // if (is_singular('lsdd_campaign')) {
        // echo '<meta name = "viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale = 1.0, user-scalable = no">';
        // // }

        // echo lsdd_minify_css('<style id="lsddonation-pre-css" type="text/css">
        //         :root {
        //             --lsdd-color: ' . $theme . ';
        //             --lsdd-lighter-color: ' . $lighter . ';
        //             --lsdd-darker-color: ' . $darker . ';
        //             --lsdd-bg-color: ' . $background . ';
        //         }

        //         #lsddonation,
        //         #lsdd-payment{
        //             background: ' . $background . ' !important;
        //         }

        //         .lsdd-content,
        //         .lsdd-content h1,
        //         .lsdd-content h2,
        //         .lsdd-content h4,
        //         .lsdd-content h5,
        //         .lsdd-content h6,
        //         .lsdd-container h3,
        //         .lsdd-container h4,
        //         .lsdd-container h5,
        //         .lsdd-container h6,
        //         .lsdd-font,
        //         .lsdd-btn{
        //             font-family: -apple-system, BlinkMacSystemFont, "' . $font . '", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        //         }

        //         .lsdd-theme-color{
        //             color: ' . $theme . ' !important;
        //         }

        //         .lsdd-outline{
        //             border: 1px solid ' . $theme . ' !important;
        //             background: transparent  !important;
        //             color: ' . $theme . ' !important;
        //         }

        //         .lsdd-primary{
        //             background: ' . $theme . ' !important;
        //         }

        //         .lsdd-bg-color{
        //             background: ' . $background . ' !important;
        //         }
        //     </style>');
    }

    /**
     * Registering Frontend AJAX
     *
     * @return void
     */
    public function register_ajax()
    {
        // require_once 'class-ajax.php';
    }
}

Frontend::register(array("lokacommerce", "LokaCommerce", "1.0.0"));