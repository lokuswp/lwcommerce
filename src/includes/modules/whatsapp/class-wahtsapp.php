<?php

namespace LokusWP\Commerce\Modules;

class WhatsApp {

	public function __construct() {
		add_filter( 'lokuswp/whatsapp/template/processing', [ $this, 'templating' ], 10, 1 );
	}

	/**
	 * Prepared Email Data
	 *
	 * @param $order_data
	 *
	 * @return object
	 */
	public function prepare_data( $order_data ): object {
		$payment_registered = lwp_get_option( "payment_registered" );

		$payment_data = [];
		foreach ( $payment_registered as $payment_id ) {
			$payment = (object) lwp_get_option( $payment_id );

			// Skipping On Empty
			if ( ! isset( $payment->id ) ) {
				continue;
			}

			if ( $payment->id === $order_data['payment_id'] ) {
				$payment_data = $payment->data;
				break;
			}
		}

		$store_name['store_name'] = lwc_get_settings( 'store', 'name' );

		return (object) array_merge( $payment_data, $order_data, $store_name );
	}

	/**
	 * Prepared Template Email
	 *
	 * @param $locale
	 * @param $status
	 * @param $path
	 *
	 * @return string|void
	 */
	public function prepare_template( $locale, $path ) {

		// Email Template based on Status and Locale
		if ( file_exists( $path . '/' . $locale . '/' . 'follow-up.html' ) ) {
			$template = file_get_contents( $path . '/' . $locale . '/' . 'follow-up.html' );
		}

		if ( empty( $template ) ) {
			return;
		}

		return $template;
	}

	/**
	 * Templating Email
	 */
	public function templating( $order_data ) {

		// Change order data to object
		$data = $this->prepare_data( $order_data );

		$locale        = lwp_get_locale_by_country( $data->country ); // id_ID
		$template_path = LWC_PATH . 'src/templates/whatsapp/';

		// Getting Template based on Local, Status and Path
		$template = $this->prepare_template( $locale, $template_path );

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
		$payment_detail = $data->account_number . " *" . $data->bank_swift . "* (" . $data->bank_code . ") \n";
		$payment_detail .= "A.n " . "*" . $data->account_name . "*";

		return $payment_detail;
	}
}

new Whatsapp();