<?php
//use LokusWP\Commerce\Shipping;
//
//if ( ! defined( 'WPTEST' ) ) {
//	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
//}
//
//class Email_SMTP extends Shipping\Gateway {
//	public string $id = 'email-smtp';
//	public string $name = "Email";
//	public string $description = "Sending Digital Product via Email";
//	public string $logo_url = LWC_URL . 'src/admin/assets/images/email.png';
//	public array $docs_url = [ 'id' => '', 'en' => '' ];
//
//	// Controlling Property
//	public array $country = [ 'ID' ];
//	public array $zones = [ 'digital' ];
//	public array $services = [ 'regular' => 'on' ];
//	public string $category = "store-delivery";
//
//	public function __construct() {
//		$this->init_data();
//	}
//
//	public function admin_manage( $shipping_id ) {
//	}
//
//	public function notification_text( object $transaction ) {
//	}
//
//	public function notification_html( object $transaction ) {
//		// Get Template HTML
//	}
//
//	public function calc_cost( $package){
//		return 0;
//	}
//}
//
//
//Shipping\Manager::register( new Email_SMTP() );
