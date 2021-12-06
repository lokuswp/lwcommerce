<?php
namespace LokusWP\Commerce;

if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Plugin
{
	public function __construct()
	{
		$shortcode = new Shortcodes\Etalase;
		$posttype = new Modules\Product\Posttype;

		// Activation and Deactivation
		register_activation_hook(LWPC_BASE, [$this, 'activation']);
		register_deactivation_hook(LWPC_BASE, [$this, 'uninstall']);

		// Administration / BackOffice
		$plugin = array('slug' => 'lwpcommerce', 'name' => 'LWPCommerce', 'version' => LWPC_VERSION);
		if (is_admin()) {
			require_once LWPC_PATH . 'src/admin/class-admin.php';
			require_once LWPC_PATH . 'src/includes/helper/func-helper.php';

			Admin::register($plugin);
		}else{
			require_once LWPC_PATH . 'src/public/class-public.php';
			Frontend::register($plugin);
		}
	}

	/**
	 * Load Class Activator on Plugin Active
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function activation()
	{
		require_once LWPC_PATH . 'src/includes/common/class-activator.php';
		Activator::activate();
	}

	/**
	 * Load Class Deactivator on Plugin Deactivate
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function uninstall()
	{
		require_once LWPC_PATH . 'src/includes/common/class-deactivator.php';
		Deactivator::deactivate();
	}

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone()
	{
		// Cloning instances of the class is forbidden.
		_doing_it_wrong(__FUNCTION__, esc_html__('Something went wrong.', 'lwpbackbone'), LKBB_VERSION);
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup()
	{
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong(__FUNCTION__, esc_html__('Something went wrong.', 'lwpbackbone'), LKBB_VERSION);
	}
}
