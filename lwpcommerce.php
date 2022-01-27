<?php

/**
 * @wordpress-plugin
 * 
 * Plugin Name:       LWPCommerce
 * Plugin URI:        https://lokuswp.id/plugin/lwpcommerce
 * Description:       Jual Beli secara Online di Blogmu
 * Version:           0.1.0
 * Author:            LokusWP
 * Author URI:        https://lokuswp.id/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       lwpcommerce
 * Domain Path:       /languages
 * 
 */

// Checking Test Env and Direct Access File
if (!defined('WPTEST')) {
  defined('ABSPATH') or die("Direct access to files is prohibited");
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 * Define Constant
 */
defined('LOKUSWP_VERSION') or define('LOKUSWP_VERSION', '0.1.0');
defined('LWPC_VERSION') or define('LWPC_VERSION', '0.1.0');
defined('LWPC_BASE') or define('LWPC_BASE', plugin_basename(__FILE__));
defined('LWPC_PATH') or define('LWPC_PATH', plugin_dir_path(__FILE__));
defined('LWPC_URL') or define('LWPC_URL', plugin_dir_url(__FILE__));
defined('LWPC_STORAGE') or define('LWPC_STORAGE', wp_get_upload_dir()['basedir'] . '/lwpcommerce');
defined('LWPC_STRING_TEXT') or define('LWPC_STRING_TEXT', '0.0.1'); // String Version

/**
 * Dependency Backbone Checking
 * @return void
 */
function lwpcommerce_dependency()
{
  $backbone_active = true;
  $backbone_version = true;

  // Checking Backbone Active
  if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('lokuswp/lokuswp.php')) {
    add_action('admin_notices', function () {
      echo '<div class="error"><p>' . __('LokusWP required. please activate the backbone plugin first.', 'lwpcommerce') . '</p></div>';
    });
    $backbone_active = false;
  }


  $backbone = get_plugin_data(dirname(dirname(__FILE__)) . '/lokuswp/lokuswp.php');
  if (!version_compare($backbone['Version'], LOKUSWP_VERSION, '>=')) {
    // add_action('admin_notices', 'lsdd_midtrans_fail_version');
    $backbone_version = false;
  }


  // Deactive Extension
  if (!$backbone_version || !$backbone_active) {
    deactivate_plugins(plugin_basename(__FILE__));

    if (isset($_GET['activate'])) {
      unset($_GET['activate']);
    }
  }
}
add_action('admin_init', 'lwpcommerce_dependency');

// Backbone Active -> Run LWPCommerce
$backbone = (array) apply_filters('active_plugins', get_option('active_plugins'));
if (in_array('lokuswp/lokuswp.php', $backbone)) {

  // Load Plugin
  require_once LWPC_PATH . 'src/autoload.php';
  $plugin = new LokusWP\Commerce\Plugin();
}


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
