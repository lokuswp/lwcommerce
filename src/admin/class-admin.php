<?php

namespace LokusWP\Commerce;

use LokusWP\Admin\Tabs;
use LokusWP\Admin\Shortcode_Lists;
use LokusWP\Admin\Switch_Options;
use LokusWP\Logger;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Admin {
	/**
	 * The current version of the plugin
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $version the current version of the plugin.
	 */
	protected string $version;

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


	public static function register( array $plugin ) {
		$admin = new self( $plugin['slug'], $plugin['name'], $plugin['version'] );

		add_action( 'admin_init', [ $admin, 'admin_init' ], 1 );
		add_action( 'admin_menu', [ $admin, 'register_admin_menu' ], 1  );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_scripts' ] );
	}

	public function __construct( $slug, $name, $version ) {
		$this->slug    = $slug;
		$this->name    = $name;
		$this->version = $version;

		// Load Required File
		require_once LWC_PATH . 'src/admin/class-ajax.php';
	}

	/**
	 * Initiate Admin
	 *
	 * @return void
	 */
	public function admin_init() {

        // Storage Permission Notice
        if ( file_exists( LOKUSWP_STORAGE ) ) {
            $this->translation();
        }

		Tabs::add( 'lwcommerce', 'settings', __( 'Settings', 'lwcommerce' ), function () {
			require_once 'settings/tabs/settings.php';
		} );

		Tabs::add( 'lwcommerce', 'notification', __( 'Notification', 'lwcommerce' ), function () {
			require_once 'settings/tabs/notification.php';
		} );

		Tabs::add( 'lwcommerce', 'shipping', __( 'Shipping', 'lwcommerce' ), function () {
			require_once 'settings/tabs/shipping.php';
		} );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// $dev_css = WP_DEBUG == true ? '.css' : '-min.css';
		$dev_css = '.css';

		// Admin
		if ( isset( $_GET['page'] ) ) {
			if ( $_GET['page'] == 'lwcommerce' || strpos( $_GET['page'], 'lwcommerce-' ) !== false ) {

				// Spectre CSS Framework
				wp_enqueue_style( 'spectre-exp', LWC_URL . 'src/includes/libraries/css/spectre/spectre-exp.min.css', array(), '0.5.9', 'all' );
				wp_enqueue_style( 'spectre-icons', LWC_URL . 'src/includes/libraries/css/spectre/spectre-icons.min.css', array(), '0.5.9', 'all' );
				wp_enqueue_style( 'spectre', LWC_URL . 'src/includes/libraries/css/spectre/spectre.min.css', array(), '0.5.9', 'all' );

				wp_enqueue_style( 'wp-color-picker' );

				// Order CSS
				if ( $_GET['page'] === 'lwcommerce-order' ) {
					wp_enqueue_style( 'datatables-style', LWC_URL . 'src/includes/libraries/js/datatables/datatables.min.css', array(), $this->version, 'all' );
					wp_enqueue_style( 'datatables-style-buttons', LWC_URL . 'src/includes/libraries/js/datatables/buttons.dataTables.min.css', array(), $this->version, 'all' );
					wp_enqueue_style( 'datatables-style-select', LWC_URL . 'src/includes/libraries/js/datatables/select.dataTables.min.css', array(), $this->version, 'all' );

					wp_enqueue_style( 'orders-css', LWC_URL . 'src/admin/assets/css/orders' . $dev_css, array(), $this->version, 'all' );
				}
			}
		}

		// Global Admin Styles
		wp_enqueue_style( $this->slug . '-global', LWC_URL . 'src/admin/assets/css/admin-global' . $dev_css, array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// $dev_js = WP_DEBUG == true ? '.js' : '-min.js';
		$dev_js = '.js';

		// Datatable
		wp_register_script( 'datatables', LWC_URL . 'src/includes/libraries/js/datatables/datatables.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables-buttons', LWC_URL . 'src/includes/libraries/js/datatables/datatables.buttons.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables-buttons-excel', LWC_URL . 'src/includes/libraries/js/datatables/jszip.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables-buttons-html5', LWC_URL . 'src/includes/libraries/js/datatables/buttons.html5.min.js', array( 'jquery' ), $this->version, false );

		// Load Lib Admin Restrict only lwcommerce Page
		if (
			isset( $_GET['page'] ) && $_GET['page'] == 'lwcommerce' || strpos( get_post_type( get_the_ID() ), 'lwc-' ) !== false
			|| isset( $_GET['page'] ) && strpos( $_GET['page'], 'lwcommerce-' ) !== false
		) {

			// Load Admin Setting Js
			if ( $_GET['page'] === 'lwcommerce' ) {
				wp_enqueue_script( 'admin-setting', LWC_URL . 'src/admin/assets/js/admin-setting' . $dev_js, array(
					'jquery',
					'wp-color-picker'
				), $this->version, false );
				wp_localize_script( 'admin-setting', 'lwc_admin', array(
					'rest_url'    => get_rest_url(),
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'  => wp_create_nonce( 'lwc_admin_nonce' ),
					'plugin_url'  => LWC_URL,
					//				'currency'    => lwc_get_currency(),
					'translation' => $this->js_translation(),
				) );
			}

			// Order JS
			if ( $_GET['page'] === 'lwcommerce-order' || $_GET['page'] === 'admin.php?page=lwcommerce-statistics' ) {

				wp_enqueue_script(
					'orders-js',
					LWC_URL . 'src/admin/assets/js/orders' . $dev_js,
					array(
						'jquery',
						'datatables',
						'datatables-buttons',
						'datatables-buttons-excel',
						'datatables-buttons-html5'
					),
					$this->version,
					false
				);

				wp_localize_script( 'orders-js', 'lwc_orders', array(
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'  => wp_create_nonce( 'lwc_admin_nonce' ),
					'plugin_url'  => LWC_URL,
					'is_pro'      => apply_filters( 'lwcommerce/license/correct', false ),
					'translation' => $this->js_translation(),
				) );

			}

			// Enqueue Media For Administrator Only
			if ( current_user_can( 'manage_options' ) ) {
				wp_enqueue_media();
			}
		}
	}

	/**
	 * Javascript Translation Stack
	 *
	 * @return array
	 */
	public function js_translation(): array {
		return array(
			'delete_report' => __( 'Are you sure you want to delete this item ?', 'lwcommerce' ),
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
	public function register_admin_menu() {

		// Menu lwcommerce in WP-ADMIN
		add_menu_page(
			$this->name,
			$this->name,
			'manage_options',
			$this->slug,
			[ $this, 'admin_menu_callback' ],
			LWC_URL . 'src/admin/assets/images/lwcommerce.png',
			3
		);

		// Remove Duplicate Menu Page -> Sub Menu
		add_submenu_page( $this->slug, '', '', 'manage_options', $this->slug, '__return_null' );
		remove_submenu_page( $this->slug, $this->slug );

		add_submenu_page(
			$this->slug,
			__( 'Settings', 'lwcommerce' ),
			__( 'Settings', 'lwcommerce' ),
			'manage_options',
			'admin.php?page=lwcommerce&tab=settings',
			'',
			0
		);

		$backbone = (array) apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		if ( ! in_array( 'lwcommerce/lwcommerce.php', $backbone ) ) {

			add_submenu_page(
				$this->slug,
				__( 'Be a Pro', 'lwcommerce' ),
				__( 'Be a Pro', 'lwcommerce' ),
				'manage_options',
				'admin.php?page=lokuswp&tab=marketplace',
				'',
				9999
			);
		} else {
			add_submenu_page(
				$this->slug,
				__( 'Get More Addon', 'lwcommerce' ),
				__( 'Get More Addon', 'lwcommerce' ),
				'manage_options',
				'admin.php?page=lokuswp&tab=marketplace',
				'',
				9999
			);
		}

		// Menu Products
		add_menu_page(
			__( 'Products', 'lwcommerce' ),
			__( 'Products', 'lwcommerce' ),
			'manage_options',
			'edit.php?post_type=product',
			'',
			LWC_URL . 'src/admin/assets/svg/product.svg',
			3
		);

		// Submenu Product -> Categories
		add_submenu_page(
			'edit.php?post_type=product',
			__( 'Categories', 'lwcommerce' ),
			__( 'Categories', 'lwcommerce' ),
			'manage_options',
			'edit-tags.php?taxonomy=product_category&post_type=product',
			''
		);

		// Menu Orders
		$awaiting = get_option( 'lwcommerce_order_awaiting' ) > 0 ? abs( get_option( 'lwcommerce_order_awaiting' ) ) : 0;
		add_menu_page(
			__( 'Orders', 'lwcommerce' ),
			$awaiting ? sprintf( ( __( 'Orders', 'lwcommerce' ) . ' <span class="awaiting-mod">%d</span>' ), $awaiting ) : __( 'Orders', 'lwcommerce' ),
			'manage_options',
			'lwcommerce-order',
			[ $this, 'admin_menu_order' ],
			LWC_URL . 'src/admin/assets/svg/order.svg',
			3
		);

		// Add Shortcode List to wp-admin > lwcommerce > settings > apperance
		Shortcode_Lists::add_shortcode_list( "lwcommerce", $this->slug, $this->name, array(
			[
				'shortcode'   => '[lwcommerce_product_listing]',
				'description' => __( "Product List View", 'lwcommerce' )
			],
            [
                'shortcode'   => '[lwcommerce_product_listing mobile="true"]',
                'description' => __( "Product List Display with Mobile First version", 'lwcommerce' )
            ],
            [
                'shortcode'   => '[lwcommerce_product_listing filter="category"]',
                'description' => __( "Product List View with Category Filter", 'lwcommerce' )
            ]
		) );

		// Add Switch Options to wp-admin > lwcommerce > Appearance
		Switch_Options::add_options( "lwcommerce", $this->slug, $this->name, array(
			'checkout_whatsapp' => [
				'name'     => __( 'Checkout via Whatsapp', 'lwcommerce' ),
				'desc'     => __( 'Disable/Enable Opening Whatsapp when Checkout', 'lwcommerce' ),
				'override' => false
			]
		) );
	}


	/**
	 * Including Reports File
	 * When Clicking Menu Order.
	 *
	 * @return void
	 */
	public function admin_menu_order() {
		include_once LWC_PATH . 'src/admin/orders/order.php';
	}

	/**
	 * Including settings lwcommerce page
	 *
	 * @return void
	 */
	public function admin_menu_callback() {
		include_once LWC_PATH . 'src/admin/settings/index.php';
	}

    /**
     * Automatic Translation Apply
     * Copy Translation Into System
     * Set Translation Version
     *
     * @return void
     */
    public function translation()
    {

        // Copy Translation into System
        if ( is_admin() && !file_exists(WP_CONTENT_DIR . '/languages/plugins/lwcommerce-id_ID.mo') && file_exists(LWC_PATH . '/languages/lwcommerce-id_ID.mo')) {
            copy(LWC_PATH . '/languages/lwcommerce-id_ID.mo', WP_CONTENT_DIR . '/languages/plugins/lwcommerce-id_ID.mo');
        }

        if ( is_admin() && !file_exists(WP_CONTENT_DIR . '/languages/plugins/lwcommerce-id_ID.po') && file_exists(LWC_PATH . '/languages/lwcommerce-id_ID.po')) {
            copy(LWC_PATH . '/languages/lwcommerce-id_ID.po', WP_CONTENT_DIR . '/languages/plugins/lwcommerce-id_ID.po');

            Logger::Info("[System] Move Translation File into System");
            update_option('lwcommerce_text_version', LWC_TEXT_VERSION);
        }

        /** --- Deprecated Translation File --- */

        // Translation Deprecated Notice
        if(get_option( 'lwcommerce_text_version' )){
            if ( version_compare( LWC_TEXT_VERSION, get_option( 'lwcommerce_text_version' ),
                    '>' ) && is_plugin_active( 'loco-translate/loco.php' ) ) {
                add_action( 'admin_notices', function () {
                    $message      = esc_html__( 'LWCommerce Translation is Deprecated, Please Sync with Loco Translate',
                        'lwcommerce' );
                    $html_message = sprintf( '<div class="notice notice-info">%s <a href="?lwc-translation-sync" target="_blank">' . __( 'Sync Translation',
                            'lwcommerce' ) . '</a></div>', wpautop( $message ) );
                    echo wp_kses_post( $html_message );
                } );
            }
        }


        // Translation Sync Clicked -> Update Version
        if ( isset( $_GET['lwc-translation-sync'] ) ) {
            update_option( 'lwcommerce_text_version', LWC_TEXT_VERSION );
            header( 'Refresh:0; url=' . get_admin_url() . 'admin.php?path=languages/plugins/lwcommerce-' . get_locale() . '.po&bundle=lwcommerce/lwcommerce.php&domain=lwcommerce&page=loco-plugin&action=file-edit' );
            // Logger::Info( '[Update] Translation Version to v' . LWC_TEXT_VERSION );
        }

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
