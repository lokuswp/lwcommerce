<?php

namespace LokusWP\Commerce;

use LSD\Migration\DB_LWCommerce_Order_Meta;
use LokusWP\WordPress\Helper;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Onboarding {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $slug The string used to uniquely identify this plugin.
	 */
	protected string $slug;

	/**
	 * The Name of Plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $name The string used to uniquely identify this plugin.
	 */
	protected string $name;

	/**
	 * The current version of the plugin
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $version the current version of the plugin.
	 */
	protected string $version;

	/**
	 * Register the admin page class with all the appropriate WordPress hooks.
	 *
	 * @param array $plugin
	 */
	public static function register( array $plugin ) {
		$admin = new self( $plugin['slug'], $plugin['name'], $plugin['version'] );

		add_action( 'admin_init', [ $admin, 'admin_init' ], 0 );
		add_action( 'admin_menu', [ $admin, 'admin_menu' ] );

		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_scripts' ] );

		add_action( 'wp_ajax_lwcommerce_download_backbone', [ $admin, 'download_backbone' ] );
		add_action( 'wp_ajax_lwcommerce_onboarding_store_screen', [ $admin, 'get_store_screen' ] );
		add_action( 'wp_ajax_lwcommerce_auto_setup', [ $admin, 'auto_setup' ] );
	}

	/**
	 * Onboarding constructor.
	 *
	 * @param string $slug
	 * @param string $name
	 * @param string $version
	 */
	public function __construct( string $slug, string $name, string $version ) {
		$this->slug    = $slug;
		$this->name    = $name;
		$this->version = $version;

		// Load Required File
		require_once LWC_PATH . 'src/admin/class-ajax.php';
	}


	/**
	 * Auto Setup LWCommerce
	 * - Create : Example Page : Product Listing
	 * - Create : Example Product
	 */
	public function auto_setup() {
		require LWC_PATH . 'src/includes/modules/database/class-db-orders.php';
		require_once LOKUSWP_PATH . 'src/includes/helper/class-wp-helper.php';

		// Create Table :: Orders
		$db_orders_meta = new DB_LWCommerce_Order_Meta();
		$db_orders_meta->create_table();

		// Create Page
		Helper::generate_post( "page", __( "Product Listing", "lwcommerce" ), "products", "[lwcommerce_product_listing]" );

		// Create Product
		$this->create_product();
        $this->set_appearance();

		//Helper::set_translation("lwcommerce", LWC_STRING_TEXT, 'id_ID');

        // Flush Permalink
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
        $wp_rewrite->flush_rules();
        flush_rewrite_rules( true );
	}

	private function create_product() {

		// Digital - Free Product
		$digital_free = Helper::generate_post( "product", __( 'Plugin LWCommerce', 'lwcommerce' ), "lokuswp-lwcommerce", "WordPress Ecommerce Plugin" );
		update_post_meta( $digital_free, "_product_type", "digital" );
		update_post_meta( $digital_free, "_unit_price", 0 );
		update_post_meta( $digital_free, "_stock", 9999 );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/free-plugin.jpg';
		if ( $digital_free ) {
			Helper::set_featured_image( $thumbnail, $digital_free );
		}

		// Digital - Paid Product
		$digital_premium = Helper::generate_post( "product", __( 'Plugin LWDonation', 'lwcommerce' ), "lokuswp-lwdonation", "WordPress Donation Plugin" );
		update_post_meta( $digital_premium, "_product_type", "digital" );
		update_post_meta( $digital_premium, "_unit_price", 580000 );
		update_post_meta( $digital_premium, "_stock", 1000 );
		update_post_meta( $digital_premium, "_stock_unit", __( "License", "lwcommerce" ) );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/premium-plugin.jpg';
		if ( $digital_premium ) {
			Helper::set_featured_image( $thumbnail, $digital_premium );
		}

        // Physical - Tshirt
        $tshirt_product = Helper::generate_post( "product", __( 'Tshirt LokusWP', 'lwcommerce' ), "lokuswp-tshirt", "Official Tshirt LokusWP" );
        update_post_meta( $tshirt_product, "_product_type", "physical" );
        update_post_meta( $tshirt_product, "_unit_price", 120000 );
        update_post_meta( $tshirt_product, "_stock", 100 );
        update_post_meta( $tshirt_product, "_stock_unit", __( "Pcs", "lwcommerce" ) );
        $thumbnail = LWC_URL . 'src/admin/assets/images/product/tshirt.jpg';
        if ( $tshirt_product ) {
            Helper::set_featured_image( $thumbnail, $tshirt_product );
        }

        // Affiliate
        $tshirt_white_product = Helper::generate_post( "product", __( 'Affiliate Tshirt', 'lwcommerce' ), "lokuswp-tshirt-affiliate", "Tshirt Afilliate to Shopee" );
        update_post_meta( $tshirt_white_product, "_product_type", "physical" );
        update_post_meta( $tshirt_white_product, "_unit_price", 125000 );
        update_post_meta( $tshirt_white_product, "_stock", 10 );
        update_post_meta( $tshirt_white_product, "_stock_unit", __( "Pcs", "lwcommerce" ) );
        update_post_meta( $tshirt_white_product, "_btn_cart_link", 'https://www.tokopedia.com/' );
        update_post_meta( $tshirt_white_product, "_btn_cart_text", __( "Buy at Tokopedia", "lwcommerce" ) );
        $thumbnail = LWC_URL . 'src/admin/assets/images/product/tshirt-white.jpg';
        if ( $tshirt_white_product ) {
            Helper::set_featured_image( $thumbnail, $tshirt_white_product );
        }

        // Physical - Food
        $food_product = Helper::generate_post( "product", __( 'Seblak Bandung', 'lwcommerce' ), "lokuswp-seblak", "Seblak Khas Bandung" );
        update_post_meta( $food_product, "_product_type", "physical" );
        update_post_meta( $food_product, "_unit_price", 15000 );
        update_post_meta( $food_product, "_stock", 10 );
        update_post_meta( $food_product, "_stock_unit", __( "Bowl", "lwcommerce" ) );
        $thumbnail = LWC_URL . 'src/admin/assets/images/product/seblak.jpg';
        if ( $food_product ) {
            Helper::set_featured_image( $thumbnail, $food_product );
        }

    }

	private function set_appearance() {
        lwp_get_settings( 'lwcommerce', 'appearance', 'checkout_whatsapp', 'on' );
	}


	/*****************************************
	 * Downloading LokusWP Backbone
	 * The Latest Version from Repository
	 *
	 * @return string
	 * @since 0.1.0
	 ***************************************
	 */
	public function download_backbone() {

		$server = "https://digitalcraft.id/api/v1/product/plugin/update/lokuswp";
		$remote = wp_remote_get( $server,
			array(
				'timeout' => 30,
				'headers' => array(
					'Accept' => 'application/json',
				)
			)
		);

		// Checking Error
		if ( is_wp_error( $remote ) ) {
			return $remote->get_error_message();
		}

		$remote = json_decode( $remote['body'] );
		$result = $remote->data;

		// Only Download when Remote have Download URL and Plugin not Exist in folder
		if ( ! file_exists( WP_PLUGIN_DIR . "/lokuswp/lokuswp.php" ) && isset( $result->download_url ) ) {

			// Downloading Plugin
			$download_plugin = $this->download_plugin( $result->download_url, "lokuswp" );
			if ( is_wp_error( $download_plugin ) ) {
				echo $download_plugin->get_error_code();
			} else {
				// Run Setup Wizard
				$this->activate_plugin( "lokuswp" );
				echo "success_download_dependency";
			}
		} else {
			// Run Setup Wizard
			$this->activate_plugin( "lokuswp" );
			echo "success_download_dependency";
		}

		wp_die();
	}

	public function activate_plugin( $plugin_slug ) {

		if ( ! is_plugin_active( "$plugin_slug/$plugin_slug.php" ) ) {
			$activated = activate_plugin( WP_PLUGIN_DIR . "/$plugin_slug/$plugin_slug.php", '', false, true );
			if ( is_wp_error( $activated ) ) {
				return new \WP_Error( "failed_activate_plugin", "Plugin activation failed! Please activate manual the plugin." );
			} else {
				return true; // Plugin was activated
			}
		} else {
			return true; // Plugin was activated
		}
	}

	/*****************************************
	 * Download File via URL
	 * Using WordPress Function to Download and Unzipping File
	 *
	 * @param string $download_url
	 * @param string $plugin_slug
	 *
	 * @return Exception
	 * @since 0.1.0
	 ****************************************
	 */
	public function download_plugin( string $download_url, string $plugin_slug ) {

		// Download URL
		if ( ! file_exists( WP_PLUGIN_DIR . "/$plugin_slug/$plugin_slug.php" ) ) {

			// Defined WP File System
			WP_Filesystem();

			// Try Downloading File form url, Network Failed Test : Passed
			try {
				$tmp_file = download_url( $download_url, 300 );
				if ( is_wp_error( $tmp_file ) ) {
					throw new Exception( 'Could download file file' );
				}

				//ray( $plugin_slug );

				if ( ! copy( $tmp_file, WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' ) ) {
					throw new Exception( 'Could not copy file' );
				};
				unlink( $tmp_file ); // Delete Temp File

				// Unzip File in wp-content/plugins/plugin-name.zip to folder plugin-name/
				$unzip = unzip_file( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip', WP_PLUGIN_DIR );
				if ( is_wp_error( $unzip ) ) {
					throw new Exception( "Failed to Unzip File" );
				}

				// Delete downloaded file
				unlink( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' ); // Delete zip file
				if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' ) ) {
					return new Exception( "Can't delete the file, because the file doesn't exist or can't be found." );
				}

				// Rename Folder based on slug
				$directories = scandir( WP_PLUGIN_DIR );
				foreach ( $directories as $directory ) {
					if ( strpos( $directory, $plugin_slug ) !== false ) {
						if ( ! rename( WP_PLUGIN_DIR . "/" . $directory, WP_PLUGIN_DIR . "/" . $plugin_slug ) ) {
							return new Exception( "Can't rename file" );
						}
					}
				}

			} catch ( \Exception $e ) {
				die ( 'File did downloade: ' . $e->getMessage() );
			}

		} else { // Plugin Exist

			// Check Plugin Active Status
			$this->activate_plugin( $plugin_slug );

		}
	}

	public function get_store_screen() {

		ob_start();
		require LWC_PATH . 'src/admin/settings/tabs/general/store.php';
		$html = ob_get_clean();

		$this->auto_setup();

		echo json_encode( array(
			"code"     => "success_get_store_screen",
			"template" => $html
		) );

		wp_die();
	}


	/*****************************************
	 * First Admin Loaded
	 * Auto Redirect to Onboarding LWCommerce
	 *
	 * @return void
	 * @since 0.1.0
	 ***************************************
	 */
	public function admin_init() {

		// Reminder User to Complete Onboarding Step
		if ( ! get_option( "lwcommerce_was_installed" ) && ! get_transient( "lwcommerce_fresh_install" ) ) {
			set_transient( "lwcommerce_fresh_install", true, 60 * 60 * 6 );
			header( 'Refresh:0; url=' . get_admin_url() . 'admin.php?page=lwcommerce' );
			exit;
		}

	}

	public function admin_menu(): void {
		add_menu_page(
			$this->name,
			$this->name,
			'manage_options',
			$this->slug,
			[ $this, 'onboarding_page' ],
			LWC_URL . 'src/admin/assets/svg/onboard.svg',
			2
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.5.0
	 */
	public function enqueue_styles() {
		// $dev_css = WP_DEBUG == true ? '.css' : '-min.css';
		$dev_css = '.css';

		// Onboarding
		if ( isset( $_GET["page"] ) && $_GET["page"] == "lwcommerce" ) {
			wp_enqueue_style( 'lwc-onboarding', LWC_URL . 'src/admin/assets/css/onboarding.css', array(), $this->version, 'all' );

			// Spectre CSS Framework
			wp_enqueue_style( 'spectre-exp', LWC_URL . 'src/includes/libraries/css/spectre/spectre-exp.min.css', array(), '0.5.9', 'all' );
			wp_enqueue_style( 'spectre-icons', LWC_URL . 'src/includes/libraries/css/spectre/spectre-icons.min.css', array(), '0.5.9', 'all' );
			wp_enqueue_style( 'spectre', LWC_URL . 'src/includes/libraries/css/spectre/spectre.min.css', array(), '0.5.9', 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// $dev_js = WP_DEBUG == true ? '.js' : '-min.js';
		$dev_js = '.js';

		// Load Admin Setting Js
		if ( isset( $_GET["page"] ) && $_GET["page"] == "lwcommerce" ) {
			wp_enqueue_script( 'admin-onboarding', LWC_URL . 'src/admin/assets/js/onboarding' . $dev_js, array(
				'jquery',
				'wp-color-picker'
			), $this->version, false );

			wp_localize_script( 'admin-onboarding', 'lwc_admin', array(
				'admin_url'    => get_admin_url(),
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'   => wp_create_nonce( 'lwc_admin_nonce' ),
				'plugin_url'   => LWC_URL,
				'plugin_exist' => file_exists( WP_PLUGIN_DIR . "/lokuswp/lokuswp.php" ),
			) );
		}

		// Enqueue Media For Administrator Only
		if ( current_user_can( 'manage_options' ) ) {
			wp_enqueue_media();
		}

	}


	/**
	 * Onboarding Page
	 *
	 * @since    1.0.0
	 */
	public function onboarding_page() {
		require_once LWC_PATH . 'src/admin/onboarding/onboarding.php';
	}


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of is forbidden' ) ), LWC_VERSION );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of is forbidden' ) ), LWC_VERSION );
	}
}