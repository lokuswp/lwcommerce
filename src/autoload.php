<?php
if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

/**
 * Onboarding
 * 
 * Checking LokusWP Backbone
 */
/**
 * Dependency Backbone Checking
 * @return void
 */
// function lwpcommerce_dependency()
// {
//   $backbone_active = true;
//   $backbone_version = true;

//   // Checking Backbone Active
//   if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('lokuswp/lokuswp.php')) {
//     add_action('admin_notices', function () {
//       echo '<div class="error"><p>' . __('LokusWP required. please activate the backbone plugin first.', 'lwpcommerce') . '</p></div>';
//     });
//     $backbone_active = false;
//   }


//   $backbone = get_plugin_data(dirname(dirname(__FILE__)) . '/lokuswp/lokuswp.php');
//   if (!version_compare($backbone['Version'], LOKUSWP_VERSION, '>=')) {
//     // add_action('admin_notices', 'lsdd_midtrans_fail_version');
//     $backbone_version = false;
//   }


//   // Deactive Extension
//   if (!$backbone_version || !$backbone_active) {
//     deactivate_plugins(plugin_basename(__FILE__));

//     if (isset($_GET['activate'])) {
//       unset($_GET['activate']);
//     }
//   }
// }
// add_action('admin_init', 'lwpcommerce_dependency');

// Backbone Active -> Run LWPCommerce
// $backbone = (array) apply_filters('active_plugins', get_option('active_plugins'));
// if (in_array('lokuswp/lokuswp.php', $backbone)) {
// }


/**
 * Registers the autoloader for classes
 *
 * @author Michiel Tramper - https://www.makeitworkpress.com
 */
spl_autoload_register(function ($classname) {

	$class     = str_replace('\\', DIRECTORY_SEPARATOR, strtolower($classname));
	$classpath = LWPC_PATH . 'src/includes' . DIRECTORY_SEPARATOR . $class . '.php';
	$classpath = str_replace("lokuswp/commerce/", "", $classpath);
	$classpath = str_replace("lokuswp\\commerce\\", "", $classpath); // fix path for windows
	$classpath = str_replace("_", "-", $classpath); // fix path for windows

	// WordPress
	if (file_exists($classpath)) {
		include_once $classpath;
	}
});

// $classmap = array(
//     'LokaWP\Commerce\Plugin' => 'includes/plugin.php',
//     'LokaWP\Commerce\Post_Types' => 'includes/wordpress/posttypes.php',
//     'LokaWP\Commerce\Metabox' => 'includes/wordpress/metabox.php'
// );

add_action("lokuswp/transaction/tab/header", function () {
?>
	<!-- <div class="swiper-slide">
		<?php _e('Shipping', 'lokuswp'); ?>
	</div> -->
<?php
});
add_action("lokuswp/transaction/tab/content", function () {
	// require_once LWPC_PATH . 'src/templates/transaction/shipping.php';
});



/**
 * Processing Cart Data from Cart Cookie
 * Rendered based on Ecommerce Plugin for Respect Another Plugin
 */
function lwpc_cart_processing($cart_item, $post_id)
{

  if (get_post_type($post_id) == 'product') {
    $cart_item['price']     = abs(lwpc_get_price($post_id));
    $cart_item['min']       = 1;
    $cart_item['max']       = -1;
  }

  return $cart_item;
}
add_filter("lokuswp/cart/cookie/item", "lwpc_cart_processing", 10, 2);
