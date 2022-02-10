<?php

/**
 * @wordpress-plugin
 * 
 * Plugin Name:       LWPCommerce - BETA
 * Plugin URI:        https://lokuswp.id/plugin/lwpcommerce
 * Description:       Jual Beli Online jadi Menyenangkan
 * Version:           0.5.0
 * Author:            LokusWP
 * Author URI:        https://lokuswp.id/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       lwpcommerce
 * Domain Path:       /languages
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
defined('LOKUSWP_VERSION') or define('LOKUSWP_VERSION', '0.5.0');
defined('LWPC_VERSION') or define('LWPC_VERSION', '0.5.0');
defined('LWPC_BASE') or define('LWPC_BASE', plugin_basename(__FILE__));
defined('LWPC_PATH') or define('LWPC_PATH', plugin_dir_path(__FILE__));
defined('LWPC_URL') or define('LWPC_URL', plugin_dir_url(__FILE__));
defined('LWPC_STORAGE') or define('LWPC_STORAGE', wp_get_upload_dir()['basedir'] . '/lwpcommerce');
defined('LWPC_STRING_TEXT') or define('LWPC_STRING_TEXT', '0.0.1'); // String Version

// Autoload
require_once LWPC_PATH . 'src/autoload.php';