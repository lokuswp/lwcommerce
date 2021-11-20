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
defined('LWPC_STORAGE') or define('LWPC_STORAGE', wp_get_upload_dir()['basedir'] . '/lwpcommerce');
defined('LWPC_STRING_TEXT') or define('LWPC_STRING_TEXT', '0.0.1'); // String Version


// Requirement Minimum System
require_once LWPC_PATH . 'src/autoload.php';

require __DIR__ . '/vendor/autoload.php';

$options = array(
  'cluster' => 'ap1',
  'useTLS' => true
);
$pusher = new Pusher\Pusher(
  'c20f19f900376b9e80cb',
  '607509d0157ffde00922',
  '1300845',
  $options
);

$data['message'] = 'LOKI/IDR 1/600.000';
$pusher->trigger('my-channel', 'my-event', $data);
