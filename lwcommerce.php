<?php

/**
 * @wordpress-plugin
 *
 * Plugin Name:       LWCommerce
 * Plugin URI:        https://lokuswp.id/plugins/lwcommerce
 * Description:       Local First eCommerce WordPress
 * Version:           0.1.9
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
defined( 'LWC_VERSION' ) or define( 'LWC_VERSION', '0.1.9' );
defined( 'LWC_TEXT_VERSION' ) or define( 'LWC_TEXT_VERSION', '0.0.1' ); // Translation File Version

defined( 'LWC_BASE' ) or define( 'LWC_BASE', plugin_basename( __FILE__ ) );
defined( 'LWC_PATH' ) or define( 'LWC_PATH', plugin_dir_path( __FILE__ ) );
defined( 'LWC_URL' ) or define( 'LWC_URL', plugin_dir_url( __FILE__ ) );
defined( 'LWC_STORAGE' ) or define( 'LWC_STORAGE', wp_get_upload_dir()['basedir'] . '/lwcommerce' );


/**
 *-----------------------*
 * Minimum Requirement System
 * PHP : 7.4
 * WordPress : 5.9
 * LokusWP : 0.1.6
 *
 * @since 0.1.0
 *-----------------------*
 **/
if ( ! version_compare( PHP_VERSION, '7.4', '>=' ) ) {
	add_action( 'admin_notices', 'lwc_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
	add_action( 'admin_notices', 'lwc_fail_wp_version' );
} elseif ( ! version_compare( LOKUSWP_VERSION, '0.1.6', '>=' ) ) {
	add_action( 'admin_notices', 'lwc_fail_lokuswp_version' );
} else {
	// Come On, Let's Goo !!! 🦾
	require_once dirname( __DIR__ ) . '/lwcommerce/src/autoload.php';
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
	$message      = sprintf( esc_html__( 'This plugin run but not working. LWCommerce required version of PHP %s', 'lwcommerce' ), '7.4' );
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
	$message      = sprintf( esc_html__( 'This plugin run but not working. LWCommerce required version of WordPress %s+', 'lwcommerce' ), '5.8' );
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
	$message      = sprintf( esc_html__( 'This plugin run but not working. LWCommerce required version of LokusWP %s+', 'lwcommerce' ), '0.1.6' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}