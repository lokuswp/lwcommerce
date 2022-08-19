<?php

use LokusWP\Commerce\Shipping;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

/*****************************************
 * JNE Shipping
 * Shipping Method from JNE using RajaOngkir API
 *
 * @since 0.1.0
 *****************************************
 */
class RajaOngkir_JNE extends Shipping\Gateway {
	public string $id = 'rajaongkir-jne';

	public string $name = "JNE";
	public string $description = "Kirim Barang dengan JNE";
	public string $logo_url = LWC_URL . 'src/admin/assets/images/jne.jpg';

	public array $docs_url = [ 'id' => '', 'en' => '' ];

	// Controlling Property
	public array $country = [ 'ID' ];
	public array $zones = [ 'national' ];
	public string $category = "send-to-buyer";

	public function __construct() {
		$config['services'] = [
			'reg'    => 'on',
			'oke'    => 'on',
			'yes'    => 'on',
			'ctc'    => 'on',
			'ctcyes' => 'on'
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

		if ( $this->get_status() == "on" || $this->get_status() == "1" ) {
			$weight      = $shipping_data->weight;
			$destination = $shipping_data->destination;

			foreach ( $service_allowed as $service ) {
				$service_data = lwc_get_cost_rajaongkir( strtolower( $this->name ), $destination, $weight, $service );
				if ( $service_data ) {
					$services[] = [
						'id'          => "jne-" . strtolower( $service ),
						'logoURL'     => $shipping_data->logo_url,
						'name'        => $shipping_data->name,
						'service'     => $service,
						'cost'        => $service_data['cost'],
						'description' => $service_data['etd'] . ' ' . __( "Hari" ),
					];
				}
			}
		}

		return $services;
	}

}

Shipping\Manager::register( new RajaOngkir_JNE() );
