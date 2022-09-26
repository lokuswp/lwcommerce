<?php

namespace LokusWP\Commerce\Modules;

use Mustache_Engine;

class FollowUp_Whatsapp {

	public function __construct() {
		add_filter( 'lwcommerce/order/followup/template', [ $this, 'templating' ], 10, 1 );
	}

	/**
	 * Prepared Email Data
	 *
	 * @param $order_data
	 *
	 * @return object
	 */
	public function prepare_data( $order_data ): object {
		$order_data = (object) $order_data;

		// Getting Payment is Active ??
		$payment_id     = 'payment-' . $order_data->payment_id;
		$payment_active = lwp_get_option( "payment_manager" );
		$data           = [];

		if ( in_array( $payment_id, $payment_active ) ) { // Check Payment is Active
			// Payment Data Exist
			$payment_data = lwp_get_option( $payment_id );
			if ( ! empty( $payment_data ) ) {
				// Getting Template From Payment
				$instance                               = new $payment_data['payment_class'];
				$data['notification_block_payment_text'] = $instance->notification_text();
			}
			$data['payment_data'] = $payment_data;
		}

		$data['brand_name'] = lwp_get_settings( 'lwcommerce', 'store', 'name' );
		$data['order_id']   = lwc_get_order_meta( $order_data->transaction_id, '_order_id', true );

		return (object) array_merge( $data, (array) $order_data );
	}

	public function prepare_template() {

        $default_followup_template = 'Hi *{{name}}*

Kami ingin mengingatkan terkait pesanan Anda
Yang masih belum diselesaikan
ID Pesanan : *#{{order_id}}*

*Detail Pesanan* :
{{summary}}

*Pembayaran* :
{{payment}}

_Jika ada yang ingin ditanyakan,_
_silahkan balas pesan ini_

Terimakasih
*{{brand_name}}*
';
        $whatsapp_followup_template  = lwp_get_settings( 'lwcommerce', 'general', 'followup_template' );
        $whatsapp_followup_template = empty( $checkout_template ) ? $default_followup_template : $whatsapp_followup_template;

		$template = $whatsapp_followup_template;

		return $template;
	}

	/**
	 * Templating Email
	 */
	public function templating( $order_data ) {

		$locale = lwp_get_locale_by_country( $order_data->country ); // id_ID

		// Change order data to object
		$data     = $this->prepare_data( $order_data );
		$template = $this->prepare_template();

		// Dynamic Replacing Tag based on Data, {{tag}} = value
		foreach ( $data as $tag => $value ) {
			$template = str_replace( "{{{$tag}}}", $value, $template );
		}

		$template = str_replace( "{{payment}}", lwp_get_notification_block_payment_text( $locale, $data ), $template );
		$template = str_replace( "{{summary}}", $this->order_detail( $data ), $template );

//		$template = str_replace( "{{brand_name}}", $data->store_name, $template );


		return $template;
	}

	private function order_detail( $data ): string {
		$order_detail = "";
		foreach ( $data->product as $item ) {
			$order_detail .= $item["post_title"] . "\n";
			$order_detail .= "➤ " . $item["quantity"] . " x " . $item["price"] . "\n";
		}
		$order_detail .= "Total : " . "*" . $data->total . "*";

		return $order_detail;
	}
}

new FollowUp_Whatsapp();