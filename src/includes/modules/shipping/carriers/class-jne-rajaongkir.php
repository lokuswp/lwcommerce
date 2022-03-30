<?php
use LokusWP\Commerce\Shipping;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class JNE_RajaOngkir extends Shipping\Gateway {
	public string $id = 'jne-rajaongkir';
	public string $name = "JNE";
	public string $description = "Kirim Produk dengan JNE";
	public string $logo_url = LWC_URL . 'src/admin/assets/images/jne.png';
	public array $docs_url = [ 'id' => '', 'en' => '' ];

	// Controlling Property
	public array $country = [ 'ID' ];
	public array $zones = [ 'national' ];
	public array $services = [
		'REG' => 'on',
		'OKE' => 'on',
		'YES' => 'on'
	];
	public string $category = "store-delivery";

	public function __construct() {
		$this->init_data();
	}

	public function admin_manage( $shipping_id ) {
	}

	public function notification_text( object $transaction ) {
	}


	public function notification_html( object $transaction ) {
	}

	/**
	 * Separate to Shipping Physical
	 * Set Shipping Cost
	 *
	 * @return void
	 */
	public function calc_cost( $service ) {

	}

}
Shipping\Manager::register( new JNE_RajaOngkir() );

function lwcommerce_rajaongkir_cost_calculation( $origin, $destination, $weight, $courier ) {
	$header = [
		'content-type' => 'application/json',
		'key'          => '80aa49704fc30a939124a831882dea72',
	];

	$body = [
		'origin'      => abs($origin),
		'destination' => abs($destination),
		'weight'      => abs($weight),
		'courier'     => sanitize_key($courier),
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
//$result = lwcommerce_rajaongkir_cost_calculation( 501, 114, 200, 'jne' );
//var_dump($result);
