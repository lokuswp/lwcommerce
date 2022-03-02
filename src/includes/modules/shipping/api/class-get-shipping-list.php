<?php

namespace LokusWP\Commerce\Shipping;

class Shipping_List {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register' ] );
	}

	public function register() {
		register_rest_route( 'lwcommerce/v1', '/shipping/active', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'lists' ],
			'permission_callback' => '__return_true',
		] );
	}

	public function lists( $request ) {
		$destination = sanitize_text_field( $request->get_params()['destination'] );
		$weight      = isset( $request->get_params()['weight'] ) ? $request->get_params()['weight'] : 1;

		if ( ! $destination ) {
			return new \WP_Error( 'no_destination', 'Destination is required', [ 'status' => 400 ] );
		}

		$shipping_active = lwp_get_option( "shipping_active" );

		if ( ! $shipping_active ) {
			return new \WP_Error( 'shipping_active', 'Shipping is not active', [ 'status' => 404 ] );
		}

		$obj = [];

		foreach ( $shipping_active as $shipping_id ) {
			$shipping_data  = (object) lwp_get_option( $shipping_id );
			$shipping_class = esc_attr( $shipping_data->payment_class );

			if ( ! class_exists( $shipping_class ) ) {
				return new \WP_Error( 'internal_error', 'Internal Server Error', [ 'status' => 500 ] );
			}

			$shipping_obj          = new $shipping_class();
			$shipping_obj->package = $shipping_data->package;

			if ( $shipping_obj->group === 'physical_shipping' ) {
				$data = [];
				foreach ( $shipping_obj->package as $key => $value ) {
					if ( $value === 'off' ) {
						unset ( $shipping_obj->package[ $key ] );
					}
				}

				foreach ( $shipping_obj->package as $key => $value ) {
					$shipping_obj->service     = $key;
					$shipping_obj->destination = $destination;
					$shipping_obj->weight      = $weight;

					preg_match( "/\(([^\)]*)\)/", $shipping_obj->name, $short_name );
					$name = preg_replace( '/\((.*?)\)/', '', $shipping_obj->name );

					// Get string between two strings.

					$data[] = [
						'id'         => $shipping_obj->id,
						'name'       => $name,
						'short_name' => $short_name[1],
						'service'    => $shipping_obj->service,
						'logo'       => $shipping_obj->logo,
						'cost'       => $shipping_obj->get_cost()['cost'] == null ? 0 : $shipping_obj->get_cost()['cost'],
						'etd'        => $shipping_obj->get_cost()['etd'] == null ? '0 Hari' : $shipping_obj->get_cost()['etd'],
						'currency'   => 'IDR',
					];
				}

				$obj[] = $data;
			}

		}

		$obj = array_merge( ...$obj );

		return new \WP_REST_Response( $obj, 200 );
	}
}

new Shipping_List();