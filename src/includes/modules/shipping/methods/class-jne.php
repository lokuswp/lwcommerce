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
class JNE extends Shipping\Gateway {

	/**
	 * Shipping ID
	 *
	 * @var string
	 */
	public $id = 'jne';

	/**
	 * Shipping Name
	 *
	 * @var string
	 */
	public $name = "Jalur Nugraha Ekakurir (JNE)";


	/**
	 * Shipping Description
	 *
	 * @var string
	 */
	protected $description = "Mengantar barang sampai tujuan";

	/**
	 * Shipping Logo
	 *
	 * @var url
	 */
	public $logo = LWPC_URL . 'src/admin/assets/images/jne.png';

	/**
	 * Set Payment Service
	 */
	public $service = 'REG';

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
		'REG' => 'on',
		'OKE' => 'on',
		'YES' => 'on'
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
}

Shipping\Manager::register( new JNE() );
