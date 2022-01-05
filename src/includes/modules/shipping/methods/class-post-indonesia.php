<?php

namespace LokusWP\Commerce;

use LokusWP\Utils\Log;

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
class POST_Indonesia extends Shipping\Gateway {

	/**
	 * Shipping ID
	 *
	 * @var string
	 */
	public $id = 'post_indonesia_kilat_khusus';

	/**
	 * Shipping Name
	 *
	 * @var string
	 */
	protected $name = "POS Indonesia (POS)";

	/**
	 * Shipping Description
	 *
	 * @var string
	 */
	protected $description = "Paket Kilat Khusus";

	/**
	 * Shipping Logo
	 *
	 * @var url
	 */
	protected $logo = LWPC_URL . 'src/admin/assets/images/post-indonesia.png';

	/**
	 * Set Payment Service
	 */
	public $service = 'p';

	/**
	 * Destination shipping destination base on Raja Ongkir City ID
	 */
	public $destination = "501";

	/**
	 * Weight in gram
	 */
	public $weight = 500;

	public $zone = [ 'national', 'lokal' ];

	public $package = [
		'Paket Kilat Khusus'      => 'on',
		'Express Next Day Barang' => 'on',
	];

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

Shipping\Manager::register( new POST_Indonesia() );
