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
		$destination = sanitize_text_field( $request->get_params()['destination'] );

		if ( ! $destination ) {
			return new \WP_Error( 'shipping_destination_empty', 'Destination is required', [ 'status' => 400 ] );
		}

		$shipping_active_list = lwp_get_option( "shipping_manager" );

		if ( empty( $shipping_active_list ) ) {
			return new \WP_Error( 'shipping_active', 'Shipping is not active', [ 'status' => 404 ] );
		}

		$services = [];

		foreach ( $shipping_active_list as $shipping_id => $shipping_status ) {

//			if ( $shipping_status == "on" ) {

			$shipping_data  = (object) lwp_get_option( "shipping-" . $shipping_id );
			$shipping_class = esc_attr( $shipping_data->shipping_class );

			// Error Handling
			if ( ! class_exists( $shipping_class ) ) {
				return new \WP_Error( 'shipping_class_not_exist', 'Shipping class on but not exist', [ 'status' => 500 ] );
			}

			// Get Shipping Package
			$shipping_obj = new $shipping_class();


			if ( $shipping_obj->category === 'send-to-buyer' ) {
				// Request Cost
				$services[] = $shipping_obj->get_cost( $services, $shipping_obj, $destination );

				//$services[] = apply_filters( "lwcommerce/shipping/services", [], $shipping_obj, $destination );
			}
		}

		return lwp_set_response( "rest", "shipping_services", "Successfully getting shipping services", true, $services );
	}
}

new GET_Services();