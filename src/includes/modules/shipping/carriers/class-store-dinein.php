<?php
namespace LokusWP\Commerce\Shipping\Carriers;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Store_DineIn extends Gateway {
	protected string $id = 'store-dinein';
	protected string $name = "Makan Di Tempat";
	protected string $description = "Pesanan akan dintarkan ke tempat makan";
	protected string $logo_url = LWC_URL . 'src/admin/assets/images/email.png';
	protected array $docs_url = [ 'id' => '', 'en' => '' ];

	// Controlling Property
	protected array $country = [ 'ID' ];
	protected array $zone = [ 'local' ];
	protected array $services = [ 'regular' => 'on' ];
	protected string $category = "store-delivery";

	public function __construct() {
		$this->init_data();
	}

	public function admin_manage( $shipping_id ) {
	}

	public function notification_text( object $transaction ) {
	}

	public function notification_html( object $transaction ) {
		// Get Template HTML
	}

	public function calc_cost( $package){
		return 0;
	}
}


Shipping\Manager::register( new Shipping_SMTP() );
