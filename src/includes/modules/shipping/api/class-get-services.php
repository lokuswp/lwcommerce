<?php

namespace LokusWP\Commerce\Shipping;

/*****************************************
 * Getting Shipping Services List
 * Get Name, Logo, Cost, ETA
 *
 * @since 0.1.0
 *****************************************
 */
class GET_Services {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register' ] );
	}

	public function register() {
		register_rest_route( 'lwcommerce/v1', '/shipping/services', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'lists' ],
			'permission_callback' => '__return_true',
		] );
	}

	public function lists( $request ) {
		$destination = abs( $request->get_params()['destination'] );
		$cart_uuid   = sanitize_key( $request->get_params()['cart_uuid'] );

		if ( ! $destination ) {
			return new \WP_Error( 'shipping_destination_empty', 'Destination is required', [ 'status' => 400 ] );
		}

		$shipping_active_list = lwp_get_option( "shipping_manager" );

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

				$services[] = apply_filters( "lwcommerce/shipping/services", [], $shipping_data, $service_allowed );
			}
		}

		// Remove Parent Array
		$services = array_values( $services[0] );

		return lwp_set_response( "rest", "get_shipping_services", "Successfully getting shipping services", true, $services );
	}
}

new GET_Services();