<?php

namespace LokusWP\Commerce;

use LokusWP\Admin\Tabs;


if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Onboarding
{
	/**
	 * The current version of the plugin
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $version the current version of the plugin.
	 */
	protected $version;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $slug The string used to uniquely identify this plugin.
	 */
	protected $slug;

	/**
	 * The Name of Plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $name The string used to uniquely identify this plugin.
	 */
	protected $name;

	/**
	 * Register the admin page class with all the appropriate WordPress hooks.
	 *
	 * @param  Options  $options
	 */
	public static function register(array $plugin)
	{
		$admin = new self($plugin['slug'], $plugin['name'], $plugin['version']);

		add_action('admin_init', [$admin, 'admin_init'], 1);
		add_action('admin_menu', [$admin, 'register_admin_menu']);
		add_action('admin_enqueue_scripts', [$admin, 'enqueue_styles']);
		add_action('admin_enqueue_scripts', [$admin, 'enqueue_scripts']);
	}

	/**
	 * Constructor function.
	 *
	 * @param  object  $parent  Parent object.
	 */
	public function __construct($slug, $name, $version)
	{
		$this->slug    = $slug;
		$this->name    = $name;
		$this->version = $version;

		// Load Required File
		require_once LWPC_PATH . 'src/admin/class-ajax.php';
	}

	/**
	 * Initiatie Admin
	 *
	 * @return void
	 */
	public function admin_init()
	{
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		// $dev_css = WP_DEBUG == true ? '.css' : '-min.css';
		$dev_css = '.css';

		// Onboarding
		if (isset($_GET["page"]) && $_GET["page"] == "lwcommerce-onboarding") {
			wp_enqueue_style('lwpc-onboarding', LWPC_URL . 'src/admin/assets/css/onboarding.css', array(), $this->version, 'all');

			// Spectre CSS Framework
			wp_enqueue_style('spectre-exp', LWPC_URL . 'src/includes/libraries/css/spectre/spectre-exp.min.css', array(), '0.5.9', 'all');
			wp_enqueue_style('spectre-icons', LWPC_URL . 'src/includes/libraries/css/spectre/spectre-icons.min.css', array(), '0.5.9', 'all');
			wp_enqueue_style('spectre', LWPC_URL . 'src/includes/libraries/css/spectre/spectre.min.css', array(), '0.5.9', 'all');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		// $dev_js = WP_DEBUG == true ? '.js' : '-min.js';
		$dev_js = '.js';

		// Datatable
		// wp_register_script('datatables', LWPC_URL . 'src/includes/libraries/js/datatables/datatables.min.js', array('jquery'), $this->version, false);
		// wp_register_script('datatables-buttons', LWPC_URL . 'src/includes/libraries/js/datatables/datatables.buttons.min.js', array('jquery'), $this->version, false);
		// wp_register_script('datatables-select', LWPC_URL . 'src/includes/libraries/js/datatables/datatables.select.min.js', array('jquery'), $this->version, false);
		// wp_register_script('datatables-buttons-excel', LWPC_URL . 'src/includes/libraries/js/datatables/jszip.min.js', array('jquery'), $this->version, false);
		// wp_register_script('datatables-buttons-html5', LWPC_URL . 'src/includes/libraries/js/datatables/buttons.html5.min.js', array('jquery'), $this->version, false);

		// // Load Lib Admin Restrict only lwcommerce Page
		// if (
		// 	isset($_GET['page']) && $_GET['page'] == 'lwcommerce' || strpos(get_post_type(get_the_ID()), 'lwpc-') !== false
		// 	|| isset($_GET['page']) && strpos($_GET['page'], 'lwcommerce-') !== false
		// ) {

		// 	// Load Admin Setting Js
		// 	if ($_GET['page'] === 'lwcommerce') {
		// 		wp_enqueue_script('admin-setting', LWPC_URL . 'src/admin/assets/js/admin-setting' . $dev_js, array('jquery', 'wp-color-picker'), $this->version, false);
		// 		wp_localize_script('admin-setting', 'lwpc_admin', array(
		// 			'ajax_url'    => admin_url('admin-ajax.php'),
		// 			'ajax_nonce'  => wp_create_nonce('lwpc_admin_nonce'),
		// 			'plugin_url'  => LWPC_URL,
		// 			//				'currency'    => lwpc_get_currency(),
		// 			'translation' => $this->js_translation(),
		// 		));
		// 	}

		// 	// Order JS
		// 	if ($_GET['page'] === 'lwcommerce-order' || $_GET['page'] === 'admin.php?page=lwcommerce-statistics') {
		// 		wp_enqueue_script(
		// 			'orders-js',
		// 			LWPC_URL . 'src/admin/assets/js/orders' . $dev_js,
		// 			array('jquery', 'datatables', 'datatables-buttons', 'datatables-select', 'datatables-buttons-excel', 'datatables-buttons-html5'),
		// 			$this->version,
		// 			false
		// 		);

		// 		wp_localize_script('orders-js', 'lwpc_orders', array(
		// 			'ajax_url'    => admin_url('admin-ajax.php'),
		// 			'ajax_nonce'  => wp_create_nonce('lwpc_admin_nonce'),
		// 			'plugin_url'  => LWPC_URL,
		// 			'is_pro'      => in_array('lwcommerce-pro/lwcommerce-pro.php', apply_filters('active_plugins', get_option('active_plugins'))),
		// 			'translation' => $this->js_translation(),
		// 		));
		// 	}

		// 	// Enquene Media For Administrator Only
		// 	if (current_user_can('manage_options')) {
		// 		wp_enqueue_media();
		// 	}
		// }
	}

	/**
	 * Javascript Translation Stack
	 *
	 * @return array
	 */
	public function js_translation()
	{
		return array(
			'delete_report' => __('Are you sure you want to delete this item ?', 'lwcommerce'),
		);
	}

	/**
	 * Register Menu in Admin Area
	 *
	 * lwcommerce Settings
	 * Products
	 * Orders
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_admin_menu()
	{

		// Menu lwcommerce in WP-ADMIN
		add_menu_page(
			$this->name,
			$this->name,
			'manage_options',
			$this->slug . '-onboarding',
			[$this, 'admin_menu_callback'],
			'',
			2
		);
	}

	/**
	 * Including Reports File
	 * When Clicking Menu Order.
	 *
	 * @return void
	 */
	public function admin_menu_order()
	{
		include_once LWPC_PATH . 'src/admin/orders/order.php';
	}

	/**
	 * Including settings lwcommerce page
	 * when clikcing menu LSDDOnation
	 *
	 * @return void
	 */
	public function admin_menu_callback()
	{
		include_once LWPC_PATH . 'src/admin/settings/onboarding.php';
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone()
	{
		_doing_it_wrong(__FUNCTION__, esc_html(__('Cloning of is forbidden')), LWPC_VERSION);
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup()
	{
		_doing_it_wrong(__FUNCTION__, esc_html(__('Unserializing instances of is forbidden')), LWPC_VERSION);
	}
}
