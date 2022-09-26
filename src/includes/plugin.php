<?php /** @noinspection PhpIncludeInspection */

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Plugin {

	public function __construct() {

		// Activation and Deactivation
		register_activation_hook( LWC_BASE, [ $this, 'activation' ] );
		register_deactivation_hook( LWC_BASE, [ $this, 'uninstall' ] );

		// Plugins Loaded
		add_action( 'plugins_loaded', [ $this, 'global_loaded' ] );

		// Administration / BackOffice
		$lwcommerce = array(
			'slug'    => 'lwcommerce',
			'name'    => 'LWCommerce',
			'version' => LWC_VERSION
		);

		if ( is_admin() ) {
			require_once LWC_PATH . 'src/admin/class-admin.php';
			Admin::register( $lwcommerce );

			// Products
			new Modules\Product\Post_Type_Product;
			new Modules\Product\Metabox_Product;
			new Modules\Product\Import_Product;

			// System
			new Modules\Plugin\Updater;

			// Order
			require_once LWC_PATH . 'src/includes/modules/order/class-datatable-order.php';
		} else {
			require_once LWC_PATH . 'src/public/class-public.php';
			Frontend::register( $lwcommerce );

			// Shortcodes
			new Shortcodes\Product_Listing();
			new Shortcodes\Cart_Icon();
		}

		require_once LWC_PATH . 'src/public/class-ajax.php';

		// Register custom meta table
		$this->register_ordermeta();
	}

	/**
	 * Load Globally
	 * When you depend on third party function plugin
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function global_loaded() {

		if ( is_admin() ) {
			require_once LWC_PATH . 'src/includes/modules/order/class-followup-whatsapp.php';
		}
		require_once LWC_PATH . 'src/includes/hook/checkout/func-post-checkout.php';
		// require_once LWC_PATH . 'src/includes/modules/order/methods/class-order-processing.php';
	}

	/**
	 * Register Order Meta Data to WordPress
	 *
	 * @return void
	 * @since 0.1.0
	 */
	private function register_ordermeta() {
		global $wpdb;
		$wpdb->lwcommerce_ordermeta = $wpdb->prefix . 'lwcommerce_ordermeta';
	}

	/**
	 * Load Class Activation on Plugin Active
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function activation() {
		require_once LWC_PATH . 'src/includes/common/class-activation.php';
		Activation::activate();
	}

	/**
	 * Load Class Deactivation on Plugin Deactivate
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function uninstall() {
		require_once LWC_PATH . 'src/includes/common/class-deactivation.php';
		Deactivation::deactivate();
	}

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * @since 0.1.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'lokuswp' ), LOKUSWP_VERSION );
	}

	/**
	 * Wakeup.
	 *
	 * Disable serializing of the class.
	 *
	 * @since 0.1.0
	 */
	public function __wakeup() {
		// Disable serializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'lokuswp' ), LOKUSWP_VERSION );
	}
}