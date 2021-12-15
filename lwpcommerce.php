<?php

/**
 * @wordpress-plugin
 * 
 * Plugin Name:       LWPCommerce
 * Plugin URI:        https://lokuswp.id/plugin/lwpcommerce
 * Description:       Plugin Untuk Berniaga Digital secara Online
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
defined('LWPC_BACKBONE_REQUIRE') or define('LWPC_BACKBONE_REQUIRE', '0.1.0');
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
  if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('lokuswp-backbone/lokuswp-backbone.php')) {
    add_action('admin_notices', function () {
      echo '<div class="error"><p>' . __('LokusWP BackBone required. please activate the backbone plugin first.', 'lwpcommerce') . '</p></div>';
    });
    $backbone_active = false;
  }


  $backbone = get_plugin_data(dirname(dirname(__FILE__)) . '/lokuswp-backbone/lokuswp-backbone.php');
  if (!version_compare($backbone['Version'], LWPC_BACKBONE_REQUIRE, '>=')) {
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
if (in_array('lokuswp-backbone/lokuswp-backbone.php', $backbone)) {

  // Load Plugin
  require_once LWPC_PATH . 'src/autoload.php';
  $plugin = new LokusWP\Commerce\Plugin();
}