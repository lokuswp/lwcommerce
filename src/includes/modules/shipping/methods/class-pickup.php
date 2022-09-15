<?php

use LokusWP\Commerce\Shipping;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

/*****************************************
 * Free Shipping
 * Shipping Method for Free Shipping without condition
 *
 * @since 0.1.0
 *****************************************
 */
class Pickup extends Shipping\Gateway {
	public string $id = 'pickup';

	public string $name = "Pickup";
	public string $description = "Pesanan Diambil sendiri";
	public string $logo_url = LWC_URL . 'src/admin/assets/images/pickup.png';

	public array $docs_url = [ 'id_ID' => '', 'en_US' => '' ];

	// Controlling Property
	public array $country = [ 'ID' ];
	public array $zones = [ 'local' ];
	public string $category = "send-to-buyer";

	public function __construct() {
		$config['services'] = [
			'regular' => 'on',
		];
		$this->init_data( $config );

		add_filter( "lwcommerce/shipping/services", [ $this, "get_service" ], 10, 3 );
	}

	public function admin_manage( $shipping_id ) {
	}

	public function notification_text( object $transaction ) {
	}


	public function notification_html( object $transaction ) {
	}

	public function get_service( $services, $shipping_data, $service_allowed ) {
		return $services;
	}

}

Shipping\Manager::register( new Pickup() );