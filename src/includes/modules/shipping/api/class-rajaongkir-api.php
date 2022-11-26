<?php

namespace LokusWP\Commerce\Shipping;

class RajaOngkir_API {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register' ] );
	}

	public function register() {

		register_rest_route( 'lwcommerce/v1', '/rajaongkir/province', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_rajaongkir_province' ],
			'permission_callback' => false,

		] );

		register_rest_route( 'lwcommerce/v1', '/rajaongkir/city', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_rajaongkir_city' ],
			'permission_callback' => false,
		] );

	}

	public function get_rajaongkir_province( $data ) {
		$id = $data->get_param( 'id' ) ? '?id=' . sanitize_key( $data->get_param( 'id' ) ) : '';

		if ( ! get_transient( 'lwcommerce_rajaongkir_province' ) ) {
			$apikey = '80aa49704fc30a939124a831882dea72';
			$server = 'https://api.rajaongkir.com/starter/province' . $id;
			$remote = wp_remote_get(
				$server,
				array(
					'timeout' => 30,
					'headers' => array(
						'Accept' => 'application/json',
						'key'    => $apikey,
					)
				)
			);

			if ( is_wp_error( $remote ) ) {
				return [
					'status'  => 'error',
					'message' => $err,
				];
			}

			$response = json_decode( $remote['body'], true );
			set_transient( 'lwcommerce_rajaongkir_province', $response, 60 * 60 * 60 ); // 2,5 Days
		} else {
			$response = get_transient( 'lwcommerce_rajaongkir_province' );
		}

		return [
			'status'  => 'success',
			'data'    => $response['rajaongkir']['results'],
			'message' => 'Province data fetched',
		];
	}

	public function get_rajaongkir_city( $data ) {
		$city_id     = $data->get_param( 'id' ) ? 'id=' . intval( $data->get_param( 'id' ) ) : '';
		$province_id = $data->get_param( 'province' ) ? 'province=' . intval( $data->get_param( 'province' ) ) : '';
		$apikey      = '80aa49704fc30a939124a831882dea72';

		// Only Getting City List based on Province ID
		if ( $province_id && ! $city_id ) {
			if ( ! get_transient( 'lwcommerce_rajaongkir_city_' . $province_id ) ) {
				$server = 'https://api.rajaongkir.com/starter/city?' . $province_id;
				$remote = wp_remote_get(
					$server,
					array(
						'timeout' => 30,
						'headers' => array(
							'Accept' => 'application/json',
							'key'    => $apikey,
						)
					)
				);

				if ( is_wp_error( $remote ) ) {
					return [
						'status'  => 'error',
						'message' => $err,
					];
				}

				$response = json_decode( $remote['body'], true );
				set_transient( 'lwcommerce_rajaongkir_city_' . $province_id, $response, 60 * 60 * 60 ); // 2,5 Days
			} else {
				$response = get_transient( 'lwcommerce_rajaongkir_city_' . $province_id );
			}
		}

		return [
			'status'  => 'success',
			'data'    => $response['rajaongkir']['results'],
			'message' => 'City data fetched',
		];

	}

	// Shipping Cost Cache Based on Weight
	// Soon :: jne_1kg_100_to_200 ( Weight 1kg, From Jakarta, to Jogja, Courier : JNE )
	public function get_rajaongkir_calc( $data ) {
	}
}

new RajaOngkir_API();