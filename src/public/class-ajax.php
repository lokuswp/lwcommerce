<?php

namespace LokusWP\Commerce\Front;

class AJAX {
	public function __construct() {
		add_action( 'wp_ajax_nopriv_lwcommerce_get_shipping_services', [ $this, 'get_shipping_services' ] );
		add_action( 'wp_ajax_lwcommerce_get_shipping_services', [ $this, 'get_shipping_services' ] );
	}

	public function get_shipping_services() {
		$destination = abs( $_POST['destination'] );
		$cart_uuid   = sanitize_key( $_POST['cart_uuid'] );
		$coordinate  = $_POST['coordinate'];

		do_action( "lwcommerce/checkout/shipping" );

		$shipping_active_list = lwp_get_option( "shipping_manager" );

		if ( ! $destination ) {
			unset( $shipping_active_list['rajaongkir-jne'] );
		}

		if ( empty( $shipping_active_list ) ) {
			return new \WP_Error( 'shipping_active', 'Shipping is not active', [ 'status' => 404 ] );
		}

		$origin = lwc_get_settings( 'store', 'city', 'intval' );
		if ( empty( $origin ) ) {
			return new \WP_Error( 'shipping_origin_empty', 'Origin is required', [ 'status' => 404 ] );
		}

		$services = [];
		// Loop : Shipping Method
		foreach ( $shipping_active_list as $shipping_id => $shipping_status ) {

			if ( $shipping_status == "on" ) { // When Shipping Method is Active

				// Getting Shipping Method Data and Instance
				$shipping_data = (object) lwp_get_option( "shipping-" . $shipping_id );

				// White List Shipping Service
				$service_allowed = [];
				if ( isset( $shipping_data->services ) && ! empty( $shipping_data->services ) ) {
					foreach ( $shipping_data->services as $key => $value ) {
						if ( $value == "on" ) {
							$service_allowed[] = strtoupper( $key );
						}
					}
				}

				// Calculate Weight Total;
				$weight = 0;
				if ( $cart_uuid ) {
					$cart_data = lwp_get_cart_by( "cart_uuid", $cart_uuid );
					if ( is_wp_error( $cart_data ) ) {
						return lwp_set_response( "error", 500, "Failed Processing Cart Data" );
					}

					foreach ( $cart_data as $cart_item ) {
						$weight += abs( get_post_meta( $cart_item->post_id, '_weight', true ) );
					};
				}

				$shipping_data->destination = $destination;
				$shipping_data->weight      = $weight;
				$shipping_data->coordinate  = $coordinate;

				$services[] = apply_filters( "lwcommerce/shipping/services", [], $shipping_data, $service_allowed );
			}
		}

		// Remove Empty Array && merge to 1 dimensional array
		$services = array_map( 'array_filter', $services );
		$services = array_filter( $services );
		$services = call_user_func_array( 'array_merge', $services );

		wp_send_json_success( $services );
	}
}

new AJAX;