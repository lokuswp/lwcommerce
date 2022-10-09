<?php

use LokusWP\Commerce\Shipping;
use LokusWP\Commerce\Shipping\Rajaongkir;

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

		if ( $this->get_status() == "on" || $this->get_status() == "1" && $shipping_data->id === "rajaongkir-jne" ) {
			$origin      = lwc_get_settings( 'store', 'city', 'intval' );
			$destination = $shipping_data->destination;
			$weight      = $shipping_data->weight;

			$rajaongkir = Rajaongkir::get_instance();
			$rajaongkir->set_origin( $origin );
			$rajaongkir->set_destination( $destination );
			$rajaongkir->set_weight( $weight );
			$rajaongkir->set_courier( strtolower( $this->name ) );
			$rajaongkir->set_service_allowed( $service_allowed );

			$service_data = $rajaongkir->get();

			foreach ( $service_data as $data ) {
				$services[] = [
					'id'          => $this->id . '-' . strtolower( $data['service'] ),
					'logoURL'     => $shipping_data->logo_url,
					'name'        => $shipping_data->name,
					'service'     => $data['service'],
					'cost'        => $data['cost'],
					'description' => $data['etd'] . ' ' . __( "Hari" ),
				];
			}
		}

		return $services;
	}

}

Shipping\Manager::register( new RajaOngkir_JNE() );