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
class Free_Shipping extends Shipping\Gateway {
	public string $id = 'free-shipping';

	public string $name = "Free Shipping";
	public string $description = "Diantar Gratis ke Tempat Anda";
	public string $logo_url = LWC_URL . 'src/admin/assets/images/takeaway.png';

	public array $docs_url = [ 'id' => '', 'en' => '' ];

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

//		if ( $shipping_data->id == $this->id ) {
			$services[] = [
				'id'          => "free-shipping",
				'logoURL'     => $this->logo_url,
				'name'        => $this->name,
				'service'     => "Regular",
				'cost'        => 0,
				'description' => "Gratis Diantar Kerumah",
			];
//		}

		return $services;
	}

}

Shipping\Manager::register( new Free_Shipping() );