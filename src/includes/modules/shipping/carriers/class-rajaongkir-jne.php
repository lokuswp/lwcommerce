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
	public string $logo_url = LWC_URL . 'src/admin/assets/images/jne.png';

	public array $docs_url = [ 'id' => '', 'en' => '' ];

	// Controlling Property
	public array $country = [ 'ID' ];
	public array $zones = [ 'national' ];
	public string $category = "send-to-buyer";

	public function __construct() {
		$config['services'] = [
			'REG' => 'on',
			'OKE' => 'on',
			'YES' => 'on'
		];
		$this->init_data( $config );
	}

	public function admin_manage( $shipping_id ) {
	}

	public function notification_text( object $transaction ) {
	}

	public function notification_html( object $transaction ) {
	}

	public function get_cost( $services, $shipping_obj, $destination ) {

		// Populate Shipping Package
//		foreach ( $shipping_data['services'] as $service_id => $value ) {
//////					if ( $value === 'off' ) {
//////						unset ( $shipping_obj->services[ $service ] );
//////					}
//////
//////					$shipping_obj->service     = $service;
//////					$shipping_obj->destination = $destination;
//////					$shipping_obj->weight      = $weight;
//////
////////					preg_match( "/\(([^\)]*)\)/", $shipping_obj->name, $short_name );
//////					$name = preg_replace( '/\((.*?)\)/', '', $shipping_obj->name );
//////
//////					// Get string between two strings.
//
//
//		}

		$services[ $shipping_obj->id ] = [
			'id'         => $shipping_obj->id,
			'name'       => $shipping_obj->name,
			'short_name' => $shipping_obj->name,
			'service'    => "REG",
			'service_id' => strtolower( $shipping_obj->id . '-' . "REG" ),
			'logo_url'   => $shipping_obj->logo_url,
			'currency'   => 'IDR',
			'cost'       => 500,
			'eta'        => "1-2 days",
		];

		return $services;
//
//		$destination = $service[0];
//		$weight      = $service[1];
//
//		$cost = lwcommerce_rajaongkir_cost_calculation( 501, $destination, 200, 'jne' );
//		ray( $cost );
//
//		return $cost['costs'];
	}

}

Shipping\Manager::register( new RajaOngkir_JNE() );

function lwcommerce_rajaongkir_cost_calculation( $origin, $destination, $weight, $courier ) {
	$header = [
		'content-type' => 'application/json',
		'key'          => '80aa49704fc30a939124a831882dea72',
	];

	$body = [
		'origin'      => abs( $origin ),
		'destination' => abs( $destination ),
		'weight'      => abs( $weight ),
		'courier'     => sanitize_key( $courier ),
	];

	$options = [
		'body'    => wp_json_encode( $body ),
		'headers' => $header,
	];

	$request  = wp_remote_post( 'https://api.rajaongkir.com/starter/cost', $options );
	$response = json_decode( wp_remote_retrieve_body( $request ) );

	$result['costs'] = $response->rajaongkir->results[0]->costs;

	return $result;
}
