<?php

/**
 * @wordpress-plugin
 *
 * Plugin Name:       LWCommerce
 * Plugin URI:        https://lokuswp.id/plugins/lwcommerce
 * Description:       Local First eCommerce WordPress
 * Version:           0.2.5
 * Author:            LokusWP
 * Author URI:        https://lokuswp.id/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       lwcommerce
 * Domain Path:       /languages
 * Languages:         en_US
 */


// Checking Test Env and Direct Access File
if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 * Define Constant
 */
defined( 'LWC_VERSION' ) or define( 'LWC_VERSION', '0.2.5' );
defined( 'LWC_TEXT_VERSION' ) or define( 'LWC_TEXT_VERSION', '0.0.4' ); // Translation File Version
defined( 'LWC_BACKBONE_REQUIRED_VERSION' ) or define( 'LWC_BACKBONE_REQUIRED_VERSION', '0.2.0' );

defined( 'LWC_BASE' ) or define( 'LWC_BASE', plugin_basename( __FILE__ ) );
defined( 'LWC_PATH' ) or define( 'LWC_PATH', plugin_dir_path( __FILE__ ) );
defined( 'LWC_URL' ) or define( 'LWC_URL', plugin_dir_url( __FILE__ ) );
defined( 'LWC_STORAGE' ) or define( 'LWC_STORAGE', wp_get_upload_dir()['basedir'] . '/lwcommerce' );

$lwcommerce_was_installed = get_option( "lwcommerce_was_installed" );
$is_lokuswp_exist         = file_exists( WP_PLUGIN_DIR . '/lokuswp/lokuswp.php' );
$lokuswp_version          = $is_lokuswp_exist ? get_file_data( WP_PLUGIN_DIR . '/lokuswp/lokuswp.php', array( 'Version' ), false )[0] : false;
$lokuswp_active           = in_array( 'lokuswp/lokuswp.php', (array) apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );

// Check : PHP Version
if ( ! version_compare( PHP_VERSION, '7.4', '>=' ) ) {
	add_action( 'admin_notices', 'lwc_fail_php_version' );
}

// Disable Support PHP 8.0 - 8.1
if ( version_compare( PHP_VERSION, '7.5', '>' ) ) {
	add_action( 'admin_notices', 'lwp_fail_php_version' );

	return;
}

// Check : WordPress Version
if ( ! version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
	add_action( 'admin_notices', 'lwc_fail_wp_version' );
}

// Fresh Installed
if ( ! $is_lokuswp_exist && ! $lwcommerce_was_installed ) {
	// Fresh Installation : Onboard
	require_once dirname( __DIR__ ) . '/lwcommerce/src/autoload.php';
}

// Check :: LokusWP Version
if ( $is_lokuswp_exist && version_compare( $lokuswp_version, LWC_BACKBONE_REQUIRED_VERSION, '<' ) || ! in_array( 'lokuswp/lokuswp.php', get_option( 'active_plugins' ) ) && $is_lokuswp_exist && $lwcommerce_was_installed ) {
	add_action( 'admin_notices', 'lwc_fail_lokuswp_version' );
}

// Come On, Let's Goo !!! 🦾
if ( $is_lokuswp_exist && $lokuswp_active ) {
	require_once dirname( __DIR__ ) . '/lwcommerce/src/autoload.php';
}

// Notice :: Backbone Downloader After Installed
if ( ! $is_lokuswp_exist ) {
	require_once LWC_PATH . "src/includes/helper/class-admin-notice-backbone.php";
}


/**
 * Admin notice for minimum PHP version.
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @return void
 * @since 0.1.0
 */
function lwc_fail_php_version() {
	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'LWCommerce active but not working. required version of PHP %s', 'lwcommerce' ), '7.4' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Admin notice for minimum WordPress version.
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @return void
 * @since 0.1.0
 */
function lwc_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'LWCommerce active but not working. required version of WordPress %s+', 'lwcommerce' ), '5.8' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Admin notice for minimum LokusWP version.
 * Warning when the site doesn't have the minimum required LokusWP version.
 *
 * @return void
 * @since 0.1.0
 */
function lwc_fail_lokuswp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'LWCommerce active but not working. required LokusWP %s+ to be active', 'lwcommerce' ), LWC_BACKBONE_REQUIRED_VERSION );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	deactivate_plugins( WP_PLUGIN_DIR . '/lokuswp/lokuswp.php' );
	echo wp_kses_post( $html_message );
}