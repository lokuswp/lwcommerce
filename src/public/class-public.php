<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Frontend {
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
	 * @since   1.0.0
	 * @access  protected
	 * @var     string $version The current version of the plugin.
	 */
	protected string $version;

	/**
	 * Register the admin page class with all the appropriate WordPress hooks.
	 *
	 * @param array $plugin
	 */
	public static function register( array $plugin ) {
		$public = new self( $plugin['slug'], $plugin['name'], $plugin['version'] );

		add_action( 'wp_enqueue_scripts', [ $public, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $public, 'enqueue_scripts' ] );
		add_filter( 'script_loader_tag', [ $public, 'defer_scripts' ], 10, 3 );

		// add_action('wp_head', [$public, 'header']);
	}

	/**
	 * Constructor function.
	 *
	 */
	public function __construct( $slug, $name, $version ) {
		$this->slug    = $slug;
		$this->name    = $name;
		$this->version = $version;

		// Load Required File
		require_once LWC_PATH . 'src/public/class-ajax.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Load Theme CSS
		wp_enqueue_style( 'lwc-skin', plugins_url( '/src/public/assets/css/skin.css', LWC_BASE ), array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the size LSDDonation\Licenses;
	 * e.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Load Theme JS
		wp_register_script( 'isotope', plugins_url( '/src/includes/libraries/js/isotope/isotope.js', LWC_BASE ), array( 'jquery' ), $this->version, false );


        wp_enqueue_script( $this->slug, plugins_url( '/src/public/assets/js/public.js', LWC_BASE ), array( 'jquery' ), $this->version, false );

		$checkout_page     = lwp_get_settings( 'lokuswp', 'settings', 'checkout_page' );
		if( $checkout_page == get_the_ID() ){
			wp_enqueue_script( $this->slug . '-shipping', plugins_url( '/src/public/assets/js/shipping.js', LWC_BASE ), array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Deferring Script for better Performance
	 *
	 * @link https://wpshout.com/defer-parsing-javascript-wordpress/
	 *
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return mixed|string
	 */
	function defer_scripts( $tag, $handle, $src ) {

		// The handles of the enqueued scripts we want to defer
		$defer_scripts = array(
			'lwcommerce',
		);

		if ( in_array( $handle, $defer_scripts ) ) {
			return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
		}

		return $tag;
	}

}