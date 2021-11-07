<?php

/**
 * @wordpress-plugin
 * 
 * Plugin Name:       LokaWP Commerce
 * Plugin URI:        https://lokawp.id/plugin/lokacommerce/
 * Description:       Plugin Toko Online WordPress
 * Version:           0.1.0
 * Author:            LokaWP
 * Author URI:        https://lokawp.id/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       lokacommerce
 * Domain Path:       /languages
 * 
 */

// Exit if accessed directly
// if (!defined('ABSPATH')) exit;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 * Define Constant
 */
defined('LWPC_VERSION') or define('LWPC_VERSION', '0.1.0');
defined('LWPC_BASE') or define('LWPC_BASE', plugin_basename(__FILE__));
defined('LWPC_PATH') or define('LWPC_PATH', plugin_dir_path(__FILE__));
defined('LWPC_URL') or define('LWPC_URL', plugin_dir_url(__FILE__));
defined('LWPC_STORAGE') or define('LWPC_STORAGE', wp_get_upload_dir()['basedir'] . '/lokacommerce');
defined('LWPC_TRANSLATION') or define('LWPC_TRANSLATION', '0.0.0.1'); // 1.0.0 - 0.0.0.1


// Requirement Minimum System
require_once LWPC_PATH . 'src/autoload.php';