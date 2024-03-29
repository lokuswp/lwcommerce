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
		Helper::generate_post( "page", __( "Product List", "lwcommerce" ), "products", '[lwcommerce_product_list]' );


		$this->create_category();
		$this->create_product();
		$this->set_appearance();

		//Helper::set_translation("lwcommerce", LWC_STRING_TEXT, 'id_ID');

		// Flush Permalink
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$wp_rewrite->flush_rules();
		flush_rewrite_rules( true );
	}

	public function create_category() {
		register_taxonomy(
			'product_category',
			'product',
			array(
				'hierarchical' => true,
				'label'        => __( 'Category', "lwcommerce" ),
				'query_var'    => true,
				'public'       => true,
				'rewrite'      => array(
					'slug'         => 'product-category',
					'with_front'   => true,
					'hierarchical' => true,
				),
				'has_archive'  => false,
			)
		);

		wp_insert_term(
			'Plugin',
			'product_category',
			array(
				'description' => 'WordPress Plugin',
				'slug'        => 'plugin'
			)
		);
		wp_insert_term(
			'Apparel',
			'product_category',
			array(
				'description' => 'Apparel',
				'slug'        => 'apparel'
			)
		);

		wp_insert_term(
			__( 'Food', 'lwcommerce' ),
			'product_category',
			array(
				'description' => 'Food',
				'slug'        => 'food'
			)
		);
	}

	private function create_product() {

		// Digital - Free Product
		$free = Helper::generate_post( "product", __( 'Plugin LWCommerce', 'lwcommerce' ), "lokuswp-lwcommerce", "WordPress Ecommerce Plugin" );
		update_post_meta( $free, "_product_type", "digital" );
		update_post_meta( $free, "_unit_price", 0 );
		update_post_meta( $free, "_stock", 9999 );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/free-plugin.png';
		if ( $free ) {
			Helper::set_featured_image( $thumbnail, $free );
		}
		wp_set_object_terms( $free, 'plugin', 'product_category' );

		// Digital - Paid Product
		$digital = Helper::generate_post( "product", __( 'Plugin LWDonation', 'lwcommerce' ), "lokuswp-lwdonation", "WordPress Donation Plugin" );
		update_post_meta( $digital, "_product_type", "digital" );
		update_post_meta( $digital, "_unit_price", 380000 );
		update_post_meta( $digital, "_stock", 1000 );
		update_post_meta( $digital, "_stock_unit", __( "License", "lwcommerce" ) );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/premium-plugin.png';
		if ( $digital ) {
			Helper::set_featured_image( $thumbnail, $digital );
		}
		wp_set_object_terms( $digital, 'plugin', 'product_category' );

		// Physical - Tshirt
		$tshirt = Helper::generate_post( "product", __( 'Tshirt LokusWP', 'lwcommerce' ), "lokuswp-tshirt", "Official Tshirt LokusWP" );
		update_post_meta( $tshirt, "_product_type", "physical" );
		update_post_meta( $tshirt, "_unit_price", 120000 );
		update_post_meta( $tshirt, "_promo_price", 100000 );
		update_post_meta( $tshirt, "_stock", 99 );
		update_post_meta( $tshirt, "_stock_unit", __( "Pcs", "lwcommerce" ) );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/lokuswp-tshirt.jpg';
		if ( $tshirt ) {
			Helper::set_featured_image( $thumbnail, $tshirt );
		}
		wp_set_object_terms( $tshirt, 'apparel', 'product_category' );

		// Affiliate
		$affiliate = Helper::generate_post( "product", __( 'Affiliate Tshirt', 'lwcommerce' ), "lokuswp-tshirt-affiliate", "Tshirt Afilliate to Shopee" );
		update_post_meta( $affiliate, "_product_type", "physical" );
		update_post_meta( $affiliate, "_unit_price", 125000 );
		update_post_meta( $affiliate, "_stock", 10 );
		update_post_meta( $affiliate, "_stock_unit", __( "Pcs", "lwcommerce" ) );
		update_post_meta( $affiliate, "_btn_cart_link", 'https://tokoalus.com' );
		update_post_meta( $affiliate, "_btn_cart_text", __( "Beli di Marketplace", "lwcommerce" ) );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/tshirt-affiliate.jpg';
		if ( $affiliate ) {
			Helper::set_featured_image( $thumbnail, $affiliate );
		}
		wp_set_object_terms( $affiliate, 'apparel', 'product_category' );

		// Physical - Food
		$food_product = Helper::generate_post( "product", __( 'Seblak Bandung', 'lwcommerce' ), "lokuswp-seblak", "Seblak Khas Bandung" );
		update_post_meta( $food_product, "_product_type", "physical" );
		update_post_meta( $food_product, "_unit_price", 12000 );
		update_post_meta( $food_product, "_stock", 10 );
		update_post_meta( $food_product, "_stock_unit", __( "Bowl", "lwcommerce" ) );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/seblak.jpg';
		if ( $food_product ) {
			Helper::set_featured_image( $thumbnail, $food_product );
		}
		wp_set_object_terms( $food_product, 'food', 'product_category' );

		$nasigoreng = Helper::generate_post( "product", __( 'Nasi Goreng Teri', 'lwcommerce' ), "lokuswp-nasi-goreng", "Nasi Goreng Teri <br> Image by ResepKoki.id" );
		update_post_meta( $nasigoreng, "_product_type", "physical" );
		update_post_meta( $nasigoreng, "_unit_price", 12000 );
		update_post_meta( $nasigoreng, "_promo_price", 10000 );
		update_post_meta( $nasigoreng, "_stock", 10 );
		update_post_meta( $nasigoreng, "_stock_unit", __( "Plate", "lwcommerce" ) );
		$thumbnail = LWC_URL . 'src/admin/assets/images/product/nasigoreng.jpg';
		if ( $nasigoreng ) {
			Helper::set_featured_image( $thumbnail, $nasigoreng );
		}
		wp_set_object_terms( $nasigoreng, 'food', 'product_category' );

	}

	private function set_appearance() {
		lwp_set_settings( 'lwcommerce', 'appearance', 'checkout_whatsapp', 'on' );
		lwp_set_settings( 'lokuswp', 'appearance', 'floating_cart', 'on' );

		// Set Default :: Shipping
		$shipping_carriers["pickup"]         = "on";
		$shipping_carriers["rajaongkir-jne"] = "on";
		lwp_update_option( 'shipping_manager', $shipping_carriers );

		// Set Default Origin : Tangerang
		lwp_set_settings( 'lwcommerce', 'store', 'state', '3' );
		lwp_set_settings( 'lwcommerce', 'store', 'city', '456' );
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
		require_once LWC_PATH . "src/includes/helper/Utils.php";
		\Utils::download_backbone();

		wp_send_json_success();
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