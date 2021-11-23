<?php
/**
 * Registers the autoloader for classes
 *
 * @author Michiel Tramper - https://www.makeitworkpress.com
 */
// spl_autoload_register(function ($classname) {
// );

//     $class      = str_replace('\\', DIRECTORY_SEPARATOR, strtolower($classname));
//     $classpath  = LWPC_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $class . '.php';

//     // WordPress
//     // $parts      = explode('\\', $classname);
//     // $class      = 'class-' . strtolower(array_pop($parts));
//     // $folders    = strtolower(implode(DIRECTORY_SEPARATOR, $parts));
//     // $wppath     = LKC_PATH .  DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $folders . DIRECTORY_SEPARATOR . $class . '.php';

//     if (file_exists($classpath)) {
//         include_once $classpath;
//     } 
//     // elseif (file_exists($wppath)) {
//     //     include_once $wppath;
//     // }
// });

$classmap = array(
    'LokaWP\Commerce\Plugin' => 'includes/plugin.php',
    'LokaWP\Commerce\Post_Types' => 'includes/wordpress/posttypes.php',
    'LokaWP\Commerce\Metabox' => 'includes/wordpress/metabox.php'
);

require_once LWPC_PATH . 'src/includes/plugin.php';
include_once LWPC_PATH . 'src/includes/modules/product/class-posttype-product.php';
// include_once LWPC_PATH . 'src/includes/wordpress/metabox.php';