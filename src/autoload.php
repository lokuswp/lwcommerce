<?php

use LokusWP\Commerce\Onboarding;
use LSD\Migration\DB_LWCommerce_Order_Meta;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

/**
 * First Boot the plugin
 *
 * @since 0.1.0
 */
class LWCommerce_Boot {

	public function __construct() {

		// Checking The Flag
		$lokuswp_was_installed    = get_option( "lokuswp_was_installed" );
		$lwcommerce_was_installed = get_option( "lwcommerce_was_installed" );
		$is_backbone_active       = in_array( 'lokuswp/lokuswp.php', get_option( 'active_plugins' ) );
		$is_backbone_exist        = file_exists( WP_PLUGIN_DIR . '/lokuswp/lokuswp.php' );

		// LokusWP Not Found -> Onboard
		if ( ! $is_backbone_exist && ! $lwcommerce_was_installed ) {
			$this->on_board_screen();
		}

		// LokusWP Exist and `was installed` but `not activated` -> Activate
		if ( $is_backbone_exist && ! $is_backbone_active && $lokuswp_was_installed ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			// activate_plugins( 'lokuswp/lokuswp.php' );
		}

		// LokusWP Exist and Active
		if ( $is_backbone_exist && $is_backbone_active && $lwcommerce_was_installed && $lokuswp_was_installed ) {
			$this->run();
		} elseif ( $is_backbone_exist && ! $lwcommerce_was_installed ) {
			// Reactive LokusWP on Disable
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			activate_plugins( 'lokuswp/lokuswp.php' );
			$this->on_board_screen();
		}

		// Image Size Mobile
		add_image_size( 'lwcommerce-thumbnail-listing--mobile', 269, 269, true );

		// Image Size Desktop
		add_image_size( 'lwcommerce-thumbnail-listing--desktop', 380, 9999, false );
		add_image_size( 'lwcommerce-thumbnail-single--desktop', 800, 9999, false );
	}

	/**
	 * Start Onboard Screen
	 * Only on Once
	 *
	 * @return void
	 * @version 0.1.0
	 */
	public function on_board_screen() {

		// Only Run On-boarding Screen, Not Entire System
		include_once LWC_PATH . 'src/admin/class-onboarding.php';
		Onboarding::register( array( 'slug' => 'lwcommerce', 'name' => 'LWCommerce', 'version' => LWC_VERSION ) );
	}

	public function rest_api() {

		require_once LWC_PATH . 'src/includes/modules/order/class-lwc-order.php';
	}

	public function shipping_module() {
		// Shipping
		require_once LWC_PATH . 'src/includes/modules/shipping/methods/class-rajaongkir-jne.php';
		require_once LWC_PATH . 'src/includes/modules/shipping/methods/class-rajaongkir.php';
		require_once LWC_PATH . 'src/includes/modules/shipping/methods/class-pickup.php';
	}

	public function load_plugins(){

		// Order
		require_once LWC_PATH . 'src/includes/modules/order/class-order.php';
	}

	/**
	 * Run Plugin After Everything Setup and OK
	 *
	 * @return void
	 * @version 0.1.0
	 */
	public function run() {

		/**
		 * Registers the autoloader for classes
		 * Thanks to Michiel Tramper 🙏
		 *
		 * @author Michiel Tramper
		 * @link https://www.makeitworkpress.com
		 */
		spl_autoload_register( function ( $classname ) {

			// Getting Path based on Class Name
			$class     = str_replace( '\\', DIRECTORY_SEPARATOR, strtolower( $classname ) );
			$classpath = LWC_PATH . 'src/includes' . DIRECTORY_SEPARATOR . $class . '.php'; // only load inside folder includes
			$classpath = str_replace( "lokuswp/commerce/", "", $classpath );
			$classpath = str_replace( "lokuswp\\commerce\\", "", $classpath ); // fix path for windows

			// Windows Environment
			if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
				$classpath = explode( "plugins\lwcommerce/", $classpath )[1];
			} else {
				$classpath = explode( "plugins/lwcommerce/", $classpath )[1];
			}

			$classpath = str_replace( "_", "-", $classpath ); // prevent replacing public_html
			$classpath = LWC_PATH . $classpath; // Add Root Path

			// Load File Based on Namespace
			if ( file_exists( $classpath ) ) {
				include_once $classpath;
			}
		} );

		// Helper

		require_once LWC_PATH . 'src/includes/helper/func-price.php';
		require_once LWC_PATH . 'src/includes/helper/func-stock.php';
		require_once LWC_PATH . 'src/includes/helper/func-setter.php';
		require_once LWC_PATH . 'src/includes/helper/func-getter.php';
		require_once LWC_PATH . 'src/includes/helper/func-helper.php';
		require_once LWC_PATH . 'src/includes/helper/func-order-meta.php';

		// Hook
		require_once LWC_PATH . 'src/includes/hook/cart/func-cart-processing.php';
		require_once LWC_PATH . 'src/includes/hook/checkout/func-checkout-tab.php';
		require_once LWC_PATH . 'src/includes/hook/checkout/func-checkout-logic.php';
		require_once LWC_PATH . 'src/includes/hook/checkout/func-checkout-whatsapp.php';
		require_once LWC_PATH . 'src/includes/hook/order/func-order-create.php';
		require_once LWC_PATH . 'src/includes/hook/product/func-product.php';
		require_once LWC_PATH . 'src/includes/hook/notification/func-notification-scheduler.php';


		if ( is_admin() ) {
			require_once LWC_PATH . 'src/includes/common/class-i18n.php';
			require_once LWC_PATH . 'src/includes/modules/shipping/controller/class-shipping-controller.php';
			require_once LWC_PATH . 'src/includes/modules/shipping/controller/rajaongkir/class-shipping-processing.php';
			require_once LWC_PATH . 'src/includes/modules/shipping/controller/pickup/class-shipping-processing.php';


			// Shipping Module
			require_once LWC_PATH . 'src/includes/modules/shipping/abstract-shipping.php';
			require_once LWC_PATH . 'src/includes/modules/shipping/class-manager.php';
		}

		// API
		if ( strpos( $_SERVER['REQUEST_URI'], '/wp-json/' ) !== false && ! is_admin() ) {
			// Shipping Module
			require_once LWC_PATH . 'src/includes/modules/shipping/abstract-shipping.php';
			require_once LWC_PATH . 'src/includes/modules/shipping/class-manager.php';

			require_once LWC_PATH . 'src/includes/modules/shipping/api/class-rajaongkir-api.php';
			require_once LWC_PATH . 'src/includes/modules/shipping/api/class-get-services.php';
		}

		add_action( "init", [ $this, "load_plugins" ] );
		add_action( "rest_api_init", [ $this, "rest_api" ] );
		add_action( "admin_init", [ $this, "rest_api" ] );
		add_action( "lwcommerce/wp-admin/settings", [ $this, "shipping_module" ] );
		add_action( "lwcommerce/checkout/shipping", [ $this, "shipping_module" ] );

		// Check if LokusWP is installed and Active
		if ( in_array( 'lokuswp/lokuswp.php', get_option( 'active_plugins' ) ) ) {
			new LokusWP\Commerce\Plugin(); // Run LWCommerce, Run !!! 🏃🏃🏃
		}
	}
}

// Booting ...
if ( defined( 'WPTEST' ) ) { // Skip on-boarding when run in Testing Mode
	new LokusWP\Commerce\Plugin();
} else {
	new LWCommerce_Boot();
}