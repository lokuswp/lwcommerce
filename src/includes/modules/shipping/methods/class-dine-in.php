<?php

namespace LokusWP\Commerce;

use LokusWP\BackBone\Utils\Log;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

/**
 * Payment Gateway Class extending from Payment\Gateway Abstraction
 *
 * @link country code https://en.wikipedia.org/wiki/List_of_ISO_3166_country_codes
 * @property string $id
 * @property string $country | WW for WorldWide ( Global ) | ISO 3166-1 alpha-2
 * DB : _lokuswp_options
 *
 * @since 1.0.0
 */
class Dine_In extends Shipping\Gateway {
	public $id = 'dine_in';

	protected $name = "Dine In";
	protected $description = "Pembeli mengambil produk di tempat";
	protected $logo = LWPC_URL . 'src/admin/assets/images/location.png';
	protected $fee = 0;

	public $zone = [ 'lokal' ];
	public $package = [ 'regular' ];
	public $type = "Kirim Ke Lokasi";
	public $group = "physical_shipping";
	public $docs_url = [ 'ID' => '', 'EN' => '' ];
	public $country = "ID";

	public function __construct() {
		$this->save_as_data();
	}

	// payment management for admin
	public function admin_manage( $shipping_id ) {
		//
	}

	// instruction with output html at receipt page
	public function instruction_html( object $transaction_obj ) {
	}

	// template text for notification channel sms or whatsapp
	public function notification_text( object $transaction_obj, string $event, string $shipping_id ) {
	}

	// template html for notification using smtp email
	public function notification_html( object $transaction_obj, string $event, string $shipping_id ) {
	}

	// template json for notification using webhook services, integromat, zapier or apps. etc
	public function notification_json( object $transaction_obj, string $event ) {
		return json_encode( [ "array" => "value" ] );
	}
}

Shipping\Manager::register( new Dine_In() );
