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
class JNE_YES extends Shipping\Gateway {

	/**
	 * Shipping ID
	 *
	 * @var string
	 */
	public $id = 'jne_yes';

	/**
	 * Shipping Name
	 *
	 * @var string
	 */
	protected $name = "Jalur Nugraha Ekakurir (JNE) YES";

	/**
	 * Shipping Description
	 *
	 * @var string
	 */
	protected $description = "Yakin Esok Sampai";

	/**
	 * Shipping Logo
	 *
	 * @var url
	 */
	protected $logo = LWPC_URL . 'src/admin/assets/images/jne.png';

	/**
	 * Set Payment Service
	 */
	protected $service = 'YES';

	/**
	 * Store location base on Raja Ongkir City ID
	 */
	protected $origin = "455";

	/**
	 * Destination shipping destination base on Raja Ongkir City ID
	 */
	protected $destination = "501";

	/**
	 * Weight in gram
	 */
	protected $weight = 500;

	public $zone = [ 'national', 'lokal' ];
	public $package = [ 'express', 'regular' ];
	public $type = "Kirim Ke Lokasi";
	public $group = "physical_shipping";
	public $docs_url = [ 'ID' => '', 'EN' => '' ];
	public $country = "ID";

	public function __construct() {
		$this->save_as_data();

		add_filter( 'lwpbackbone/transaction/extras', [ $this, 'lwp_shipping_cost' ] );
	}

	/**
	 * Inject Shipping Cost to Transaction
	 *
	 * @param $transaction
	 *
	 * @return array
	 */
	public function lwp_shipping_cost( $transaction ): array {
		$total                = $transaction['total'] + $this->cost;
		$transaction['total'] = $total;

		return $transaction;
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

Shipping\Manager::register( new JNE_YES() );
