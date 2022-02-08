<?php
if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

class BIOS
{
	public function boot()
	{
		if ($this->is_fresh_install()) {
			$this->onboarding();
		} else {
			$this->run();
		}
	}

	public function is_fresh_install()
	{
	}

	public function it_has_backbone()
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

	public function onboarding()
	{
		
	}

	public function run()
	{
		$this->it_has_backbone();
		$backbone = (array) apply_filters('active_plugins', get_option('active_plugins'));
		if (in_array('lokuswp/lokuswp.php', $backbone)) {
		}
	}
}
// add_action('admin_init', 'lwpcommerce_dependency');


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
