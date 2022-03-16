<?php

namespace LokusWP\Commerce;

use LokusWP\Admin\Tabs;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Onboarding {
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

	/**
	 * Register the admin page class with all the appropriate WordPress hooks.
	 *
	 * @param array $plugin
	 */
	public static function register( array $plugin ) {
		$admin = new self( $plugin['slug'], $plugin['name'], $plugin['version'] );

		add_action( 'admin_init', [ $admin, 'admin_init' ], 1 );
		add_action( 'admin_menu', [ $admin, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_lwcommerce_download_plugin', [ $admin, 'download_plugin' ] );
	}

	/**
	 * Onboarding constructor.
	 *
	 * @param string $slug
	 * @param string $name
	 * @param string $version
	 */
	public function __construct( $slug, $name, $version ) {
		$this->slug    = $slug;
		$this->name    = $name;
		$this->version = $version;

		// Load Required File
		require_once LWC_PATH . 'src/admin/class-ajax.php';
	}

	public function download_plugin() {
//		$json = '{
//		"success": true,
//                "data": [
//                    {
//	                    "lwcommerce": {
//	                    "name": "LWCommerce",
//                            "slug": "lwcommerce",
//                            "description": "One Integration",
//                            "category": "app",
//                            "download_url": "https://lokuswp.id/download/lwcommerce.zip",
//                            "price": 0,
//                            "currency": "IDR",
//                            "docs": [
//                                {
//	                                "ID": "https://lokuswp.id"
//                                },
//                                {
//	                                "US": "https://lokuswp.com"
//                                }
//                            ]
//                        }
//                    }
//                ]
//            }';
//
//		$result = json_decode( $json );
//
//		if ( $result ) {
//			$result = $result->data;
//		}


		if ( ! file_exists( WP_PLUGIN_DIR . "/lokuswp/lokuswp.php" ) ) {
			if ( ! empty( get_option( "lwcommerce_was_installed" ) ) ) {
				echo "ajax_lwcommerce";
			}

			if ( $this->lwc_download_plugin( "https://www.dropbox.com/s/4mldrxl6kdi6nuh/lokuswp.zip?dl=1", "lokuswp" ) ) {
				echo "ajax_success";
			} else {
				echo "ajax_failed";
			}
		}
		echo "ajax_success";

		wp_die();
	}


	/**
	 * Download File using wp function download_url
	 * Copy temp to Plugin Dir -> Unzip -> Clean File
	 *
	 * @param string $download_url
	 * @param string $plugin_slug
	 *
	 * @return string
	 */
	function lwc_download_plugin( string $download_url, string $plugin_slug ): string {

		if ( ! file_exists( WP_PLUGIN_DIR . "/$plugin_slug/$plugin_slug.php" ) ) {

			// For unzipping file
			WP_Filesystem();

			// Download the file
			$tmp_file = download_url( $download_url );


			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp_file ) ) {
				throw new PharException( "Download failed!" );
			}

			// Copy From Temp to Plugin Dir
			copy( $tmp_file, WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' );

			// Remove Temp File
			unlink( $tmp_file );

			// Unzip
			$unzip = unzip_file( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip', WP_PLUGIN_DIR );

			// If error storing temporarily, unlink
			if ( is_wp_error( $unzip ) ) {
				throw new PharException( "Unzip failed!" );
			}

			// Check if plugin active or not
			if ( is_plugin_active( "$plugin_slug/$plugin_slug.php" ) ) {
				throw new PharException( "Plugin already activated!" );
			}

			// Remove File
			unlink( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' );

			// Delete downloaded file
			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' ) ) {
				throw new PharException( "Can't delete the file, because the file doesn't exist or can't be found." );
			}
		}

		// Activate plugin
		$result = activate_plugin( WP_PLUGIN_DIR . "/$plugin_slug/$plugin_slug.php", '', false, true );

		if ( is_wp_error( $result ) ) {
			throw new PharException( $result->errors['no_plugin_header'][0] ?? 'Plugin activation failed! Please activate manual the plugin.' );
		}

		return true;
	}

	/**
	 * Register Admin Functionality
	 *
	 * @since    0.5.0
	 */
	public function admin_init() {

		// Handle Fresh Install when flag false and reminder set false;
		if ( ! get_option( "lwcommerce_was_installed" ) && ! get_transient( "lwcommerce_fresh_install" ) ) {
			set_transient( "lwcommerce_fresh_install", true, 60 * 60 * 6 );
			header( 'Refresh:0; url=' . get_admin_url() . 'admin.php?page=lwcommerce-onboarding' );
			exit;
		}

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
		if ( isset( $_GET["page"] ) && $_GET["page"] == "lwcommerce-onboarding" ) {
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
		if ( isset( $_GET["page"] ) && $_GET["page"] == "lwcommerce-onboarding" ) {
			wp_enqueue_script( 'admin-onboarding', LWC_URL . 'src/admin/assets/js/admin-setting' . $dev_js, array(
				'jquery',
				'wp-color-picker'
			), $this->version, false );

			wp_localize_script( 'admin-onboarding', 'lwc_admin', array(
				'admin_url'   => get_admin_url(),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'  => wp_create_nonce( 'lwc_admin_nonce' ),
				'plugin_url'  => LWC_URL,
				'translation' => $this->js_translation(),
			) );
		}

		// Enqueue Media For Administrator Only
		if ( current_user_can( 'manage_options' ) ) {
			wp_enqueue_media();
		}

	}

	/**
	 * Javascript Translation Stack
	 *
	 * @return array
	 */
	public function js_translation() {
		return array(
			'delete_report' => __( 'Are you sure you want to delete this item ?', 'lwcommerce' ),
		);
	}


	/**
	 * Register the admin page class with all the appropriate WordPress hooks.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {

		// Menu lwcommerce in WP-ADMIN
		add_menu_page(
			$this->name,
			$this->name,
			'manage_options',
			$this->slug . '-onboarding',
			[ $this, 'onboarding_page' ],
			LWC_URL . 'src/admin/assets/svg/onboard.svg',
			2
		);
	}

	/**
	 * Onboarding Page
	 *
	 * @since    1.0.0
	 */
	public function onboarding_page() {
		require_once LWC_PATH . 'src/admin/settings/onboarding.php';
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
