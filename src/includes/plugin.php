<?php
namespace LokaWP\Commerce;

// defined( 'ABSPATH' ) or die( 'Direct Access Detected' );

class Plugin
{
    public function __construct()
    {
        // require_once LWPC_PATH . 'includes/libraries/wp-register/register.php';

        // $registrations = [
        //     'image_sizes' => [
        //         [
        //             'name'   => 'fhd',
        //             'height' => 1080,
        //             'width'  => 1920,
        //             'crop'   => true
        //         ]
        //     ],   
        //     'post_types' => [
        //         [
        //             'name'          => 'beer', 
        //             'plural'        => __('Beers', 'textdomain'), 
        //             'singular'      => __('Beer', 'textdomain'), 
        //             'args'          => ['public' => true],       // Contains the arguments as they are supported by register_post_type. (optional)
        //             'taxonomies'    => ['category'],             // Connects existing taxonomies to this post type. Should be an array. (optional)
        //             'slug'          => 'slug',                   // Sets a custom slug, fastforward for the rewrite slug setting in arguments
        //             'icon'          => 'dashicon-beer'          // Sets a custom wp-admin menu icon, fastforward for the menu_icon setting in arguments
        //         ]
        //     ]
        // ];
        // $register = new \MakeitWorkPress\WP_Register\Register( $registrations, 'textdomain' );

        // Administration / BackOffice
        if (is_admin()) {
            require_once LWPC_PATH . 'src/admin/class-admin.php';
            require_once LWPC_PATH . 'src/includes/helper/func-helper.php';
            $plugin = array('slug' => 'lwpcommerce', 'name' => 'LWPCommerce', 'version' => LWPC_VERSION);
            Admin::register($plugin);
        }
    }
}
new Plugin;