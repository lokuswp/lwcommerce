<?php

use LokusWP\Commerce\Onboarding;

if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

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

	include_once LWPC_PATH . 'src/includes/hook.php';
});

class Boot
{
	public function __construct()
	{
		if (empty(get_option("lwpcommerce_was_installed"))) { // if the plugin was not installed before
			$this->onboarding();
		} else {
			$this->run();
		}
	}

	public function onboarding()
	{
		include_once LWPC_PATH . 'src/admin/class-onboarding.php';
		$plugin = array('slug' => 'lwpcommerce', 'name' => 'LWPCommerce', 'version' => LWPC_VERSION);
		Onboarding::register($plugin);

		// update_option("lwpcommerce_was_installed", "setup");
	}

	public function it_has_backbone()
	{
		// $backbone_active = true;
		// $backbone_version = true;

		// // Checking Backbone Active
		// if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('lokuswp/lokuswp.php')) {
		// 	add_action('admin_notices', function () {
		// 		echo '<div class="error"><p>' . __('LokusWP required. please activate the backbone plugin first.', 'lwpcommerce') . '</p></div>';
		// 	});
		// 	$backbone_active = false;
		// }


		// $backbone = get_plugin_data(dirname(dirname(__FILE__)) . '/lokuswp/lokuswp.php');
		// if (!version_compare($backbone['Version'], LOKUSWP_VERSION, '>=')) {
		// 	$backbone_version = false;
		// }


		// // Deactive Extension
		// if (!$backbone_version || !$backbone_active) {
		// 	deactivate_plugins(plugin_basename(__FILE__));

		// 	if (isset($_GET['activate'])) {
		// 		unset($_GET['activate']);
		// 	}
		// }
	}

	public function run()
	{
		$this->it_has_backbone();

		$backbone = (array) apply_filters('active_plugins', get_option('active_plugins'));
		if (in_array('lokuswp/lokuswp.php', $backbone)) {
			new LokusWP\Commerce\Plugin();
		}
	}
}
new BOOT;
