<?php

namespace LokusWP\Commerce;

if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Plugin
{
	public function __construct()
	{
		$shortcode = new Shortcodes\Storefront;
		$shortcode = new Shortcodes\Customer_Area;

		$posttype  = new Modules\Product\Post_Type_Product;
		$posttype  = new Modules\Product\Metabox_Product;


		// // Activation and Deactivation
		register_activation_hook(LWPC_BASE, [$this, 'activation']);
		register_deactivation_hook(LWPC_BASE, [$this, 'uninstall']);

		require_once LWPC_PATH . 'src/includes/helper/mock/func-mock.php';
		require_once LWPC_PATH . 'src/includes/helper/func-helper.php';

		if (!defined('WPTEST')) {
			require_once LWPC_PATH . 'src/includes/modules/shipping/abstract-shipping.php';
			require_once LWPC_PATH . 'src/includes/modules/shipping/class-manager.php';
			require_once LWPC_PATH . 'src/includes/modules/shipping/methods/class-email.php';
			// require_once LWPC_PATH . 'src/includes/modules/shipping/methods/class-dine-in.php';
			require_once LWPC_PATH . 'src/includes/modules/shipping/methods/class-jne.php';
			require_once LWPC_PATH . 'src/includes/modules/shipping/methods/class-post-indonesia.php';

			//	require_once LWPC_PATH . 'src/includes/modules/shipping/methods/class-shipping-processing.php';
			require_once LWPC_PATH . 'src/includes/modules/shipping/api/class-rajaongkir-api.php';
			require_once LWPC_PATH . 'src/includes/modules/shipping/api/class-get-shipping-list.php';
		}

		add_action('plugins_loaded', [$this, 'load_modules']);

		// // Administration / BackOffice
		$plugin = array('slug' => 'lwpcommerce', 'name' => 'LWPCommerce', 'version' => LWPC_VERSION);
		if (is_admin()) {
			require_once LWPC_PATH . 'src/admin/class-admin.php';
			Admin::register($plugin);
		} else {
			require_once LWPC_PATH . 'src/public/class-public.php';
			Frontend::register($plugin);
		}

		// Register custom meta table
		$this->register_custom_meta_table();
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
		_doing_it_wrong(__FUNCTION__, esc_html__('Something went wrong.', 'lokuswp'), LOKUSWP_VERSION);
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
		_doing_it_wrong(__FUNCTION__, esc_html__('Something went wrong.', 'lokuswp'), LOKUSWP_VERSION);
	}

	public function load_modules()
	{
		include_once LWPC_PATH . 'src/includes/hook.php';
		// require_once LWPC_PATH . 'src/includes/modules/shipping/methods/class-shipping-processing.php';
		// require_once LWPC_PATH . 'src/includes/modules/orders/methods/class-order-processing.php';
	}

	private function register_custom_meta_table()
	{
		// Registering meta table
		global $wpdb;
		$wpdb->lwpcommerce_ordermeta = $wpdb->prefix . 'lwpcommerce_ordermeta';
	}
}
