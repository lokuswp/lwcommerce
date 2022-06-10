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
			'REG' => 'on',
		];
		$this->init_data( $config );

		add_filter( "lwcommerce/shipping/services/{$this->id}", [ $this, "get_cost" ], 10, 3 );
	}

	public function admin_manage( $shipping_id ) {
	}

	public function notification_text( object $transaction ) {
	}


	public function notification_html( object $transaction ) {
	}

	public function get_cost( $services, $shipping_obj, $destination ) {

		$services[ $shipping_obj->id ] = [
			'id'         => $shipping_obj->id,
			'name'       => $shipping_obj->name,
			'short_name' => $shipping_obj->name,
			'service'    => "REG",
			'service_id' => strtolower( $shipping_obj->id . '-' . "REG" ),
			'logo_url'   => $shipping_obj->logo_url,
			'currency'   => 'IDR',
			'cost'       => 0,
			'eta'        => "1-2 days",
		];

		return $services;
	}

}

Shipping\Manager::register( new Free_Shipping() );