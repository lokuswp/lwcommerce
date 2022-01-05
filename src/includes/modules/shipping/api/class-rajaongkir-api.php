<?php

namespace LokusWP\Commerce\Shipping;

class Rajaongkir_API {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register' ] );
	}

	public function register() {
		register_rest_route( 'lokuswp/v1', '/rajaongkir/province', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_rajaongkir_province' ],
		] );
		register_rest_route( 'lokuswp/v1', '/rajaongkir/city', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_rajaongkir_city' ],
		] );
	}

	public function get_rajaongkir_province( $data ) {
		$id   = $data->get_param( 'id' ) ? '?id=' . $data->get_param( 'id' ) : '';
		$curl = curl_init();

		curl_setopt_array( $curl, [
			CURLOPT_URL            => 'https://api.rajaongkir.com/starter/province' . $id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'GET',
			CURLOPT_HTTPHEADER     => [
				'key: ' . '80aa49704fc30a939124a831882dea72',
			],
		] );

		$response = curl_exec( $curl );
		$err      = curl_error( $curl );

		curl_close( $curl );

		if ( $err ) {
			return [
				'status'  => 'error',
				'message' => $err,
			];
		} else {
			$response = json_decode( $response, true );

			return [
				'status'  => 'success',
				'data'    => $response['rajaongkir']['results'],
				'message' => 'Province data fetched',
			];
		}
	}

	public function get_rajaongkir_city( $data ) {
		$id = $data->get_param( 'id' ) ? '?id=' . $data->get_param( 'id' ) : '';
		if ( $id !== '' ) {
			$province_id = $data->get_param( 'province' ) ? '&province=' . $data->get_param( 'province' ) : '';
		} else {
			$province_id = $data->get_param( 'province' ) ? '?province=' . $data->get_param( 'province' ) : '';
		}
		$curl = curl_init();

		curl_setopt_array( $curl, [
			CURLOPT_URL            => 'https://api.rajaongkir.com/starter/city' . $id . $province_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'GET',
			CURLOPT_HTTPHEADER     => [
				'key: ' . '80aa49704fc30a939124a831882dea72',
			],
		] );

		$response = curl_exec( $curl );
		$err      = curl_error( $curl );

		curl_close( $curl );

		if ( $err ) {
			return [
				'status'  => 'error',
				'message' => $err,
			];
		} else {
			$response = json_decode( $response, true );

			return [
				'status'  => 'success',
				'data'    => $response['rajaongkir']['results'],
				'message' => 'City data fetched',
			];
		}
	}
}

new Rajaongkir_API();