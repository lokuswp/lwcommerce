<?php

namespace LokusWP\Commerce\Modules;

class WhatsApp {

	protected string $template_followup = 'Halo *{{buyer_name}}*
Kami ingin mengingatkan terkait pesanan Anda
Yang masih belum diselesaikan

Detail Pesanan *#{{order_id}}* :
{{order_detail}}

*Pembayaran* :
{{payment_detail}}

Terimakasih
{{store_name}}
';

	public function __construct() {
		$this->setup();

		add_filter( 'lokuswp/order/followup/template', [ $this, 'templating' ], 10, 1 );
	}

	public function setup() {
		/* Empty Settings -> Set Default Data */
		$settings = lwp_get_option( 'followup_order_whatsapp' );

		if ( empty( $settings ) ) {
			$options = [
				'followup' => $this->template_followup
			];
			lwp_update_option( 'followup_order_whatsapp', $options );
		}
	}

	/**
	 * Prepared Email Data
	 *
	 * @param $order_data
	 *
	 * @return object
	 */
	public function prepare_data( $order_data ): object {
		// Getting Payment is Active ??
		$payment_id              = 'payment-custom-bank-transfer';
		$payment_active          = lwp_get_option( "payment_manager" );
		$payment_data            = [];
		$payment_detail_template = '';

		if ( in_array( $payment_id, $payment_active ) ) { // Check Payment is Active
			// Payment Data Exist
			$payment_data = lwp_get_option( $payment_id );
			if ( ! empty( $payment_data ) ) {
				// Getting Template From Payment
				$instance                = new $payment_data['payment_class'];
				$payment_detail_template = $instance->notification_text();

			}
		}

		$store_name['store_name'] = lwc_get_settings( 'store', 'name' );

		return (object) array_merge( [ 'payment_data' => $payment_data ], $order_data, $store_name, [ 'payment_detail_template' => $payment_detail_template ] );
	}

	public function prepare_template() {

		$settings = lwp_get_option( 'followup_order_whatsapp' );

		return $settings['followup'];
	}

	/**
	 * Templating Email
	 */
	public function templating( $order_data ) {

		// Change order data to object
		$data = $this->prepare_data( $order_data );

		$template = $this->prepare_template();

		$template = str_replace( "{{buyer_name}}", $data->name, $template );
		$template = str_replace( "{{order_id}}", $data->transaction_id, $template );
		$template = str_replace( "{{order_detail}}", $this->order_detail( $data ), $template );
		$template = str_replace( "{{payment_detail}}", $this->payment_detail( $data ), $template );
		$template = str_replace( "{{store_name}}", $data->store_name, $template );

		return $template;
	}

	private function order_detail( $data ): string {
		$order_detail = "";
		foreach ( $data->product as $item ) {
			$order_detail .= "*" . $item["post_title"] . "* \n";
			$order_detail .= $item["quantity"] . " x " . "*" . $item["price"] . "* \n";
		}

		return $order_detail;
	}

	private function payment_detail( $data ): string {
		$payment_data            = (object) $data->payment_data;
		$payment_detail_template = $data->payment_detail_template;
		$data_payment            = $payment_data->data;

		$payment_detail_template = str_replace( "{{bank_name}}", $payment_data->name, $payment_detail_template );
		$payment_detail_template = str_replace( "{{#code}}", $data_payment['bank_swift_code'], $payment_detail_template );
		$payment_detail_template = str_replace( "{{code}}", $data_payment['bank_code'], $payment_detail_template );
		$payment_detail_template = str_replace( "{{/code}}", '', $payment_detail_template );
		$payment_detail_template = str_replace( "{{account_number}}", $data_payment['bank_account_number'], $payment_detail_template );
		$payment_detail_template = str_replace( "{{account_owner}}", $data_payment['bank_account_owner'], $payment_detail_template );
		$payment_detail_template = str_replace( "{{instruction}}", $payment_data->instruction, $payment_detail_template );

		return $payment_detail_template;
	}
}

new Whatsapp();