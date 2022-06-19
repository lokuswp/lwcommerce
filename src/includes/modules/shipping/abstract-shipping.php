<?php

namespace LokusWP\Commerce\Shipping;

// Checking Test Env and Direct Access File
if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

abstract class Gateway {

	/**
	 * Shipping ID
	 *
	 * @var string
	 */
	public string $id;

	/**
	 * Shipping Name
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * Shipping Description
	 *
	 * @var string
	 */
	public string $description;

	/**
	 * Shipping Logo Url
	 *
	 * @var string
	 */
	public string $logo_url;

	/**
	 * Shipping Documentation Url
	 *
	 * @var array
	 */
	public array $docs_url = [];

	/**
	 * Shipping Country
	 *
	 * @var array
	 */
	public array $country = [];

	/**
	 * Shipping Zone
	 *
	 * @var array
	 */
	public array $zones = [];

	/**
	 * Shipping Service
	 * Regular, Express, etc
	 *
	 * @var array
	 */
	public array $services = [];

	/**
	 * Shipping Category
	 *
	 * @var string
	 */
	public string $category;

	/**
	 * @param $config
	 *
	 * @return void
	 */
	public function init_data( $config ): void {
		$data                = array();
		$data['id']          = $this->id;
		$data['name']        = $this->name;
		$data['logo_url']    = $this->logo_url;
		$data['description'] = $this->description;
		$data['category']    = $this->category;
		$data['zones']       = $this->zones;
//		$data['services']       = $this->services;
		$data['data']           = "";
		$data['shipping_class'] = get_class( $this );

		$data = array_merge( $data, $config );

		// Saving Channel Data to DB
		$shipping_id = 'shipping-' . $this->id;
		if ( empty( lwp_get_option( $shipping_id ) ) ) {
			lwp_update_option( $shipping_id, $data );

			// Saving to Payment Status
			// Notification Status
			$shipping_carriers = lwp_get_option( 'shipping_manager' );

			if ( ! isset( $shipping_carriers[ $this->id ] ) ) {
				$shipping_carriers[ $this->id ] = 'on';
			}
			lwp_update_option( 'shipping_manager', $shipping_carriers );
			// Trigger Change Payment
			// Logger::info( "shipping gateway " . $this->id . " created and activated" );

		}
	}

	/**
	 * Reset Shipping Channel in Database
	 *
	 */
	public function reset_data(): bool {
		return lwp_delete_option( $this->id );
	}

	/**
	 * Get Shipping ID
	 *
	 * @return string
	 */
	public function get_ID(): string {
		return $this->id;
	}

	/**
	 * Get Description Settings
	 *
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}


	/**
	 * Get Shipping Status
	 *
	 * @return string
	 */
	public function get_status(): string {
		$shipping_carriers = lwp_get_option( 'shipping_manager' );

		if ( ! isset( $shipping_carriers[ $this->id ] ) ) {
			$shipping_carriers[ $this->id ] = 'off';
		}

		return $shipping_carriers[ $this->id ] == 'on';
	}

	/**
	 * Manage Shipping Settings
	 * used for settings shipping methods
	 *
	 * @return void
	 */
	abstract public function admin_manage( string $shipping_id );

	abstract public function notification_text( object $transaction );

	abstract public function notification_html( object $transaction );

	abstract public function get_service( $services, $shipping_data, $service_allowed );
}