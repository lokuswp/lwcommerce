<?php
/**
 * Get Dynamic Settings based on param
 *
 * @param string $option
 * @param string $item
 * @param string $validator
 * @param string|null $fallback
 *
 * @return mixed
 * @since 4.0.0
 */
function lwc_get_settings( string $option = 'general_settings', string $item, string $validator = 'esc_attr', string $fallback = null ) {

	$settings = get_option( 'lwcommerce_' . $option );

	// Vuln :: Function Injection -> calL_user_funct
	$whitelist = [ 'esc_attr', 'esc_url', 'esc_html', 'abs', 'intval', 'floatval', 'absint', 'array' ];
	if ( ! in_array( $validator, $whitelist ) ) {
		return null;
	}
	if ( ! isset( $settings[ $item ] ) ) {
		return $fallback ?? null;
	}
	if ( $validator == 'abs' || $validator == 'intval' || $validator == 'absint' ) {
		$fallback = 0;
	}

	if ( $validator == 'array' ) {
		$fallback = $fallback == null ? array() : $fallback;

		return empty( $settings[ $item ] ) ? $fallback : (array) $settings[ $item ];
	}

	return empty( $settings[ $item ] ) ? $fallback : call_user_func( $validator, $settings[ $item ] );
}

/**
 * Get Shipping Cost RajaOngkir
 *
 * @param string $shipping_id
 * @param string $service
 * @param string $destination
 *
 * @return false|mixed
 * @since 0.1.0
 */
function lwc_get_cost_rajaongkir( string $shipping_id, string $service, string $destination, string $weight ) {
	$origin           = lwc_get_settings( 'store', 'district', 'intval' );
	$destination_cost = get_transient( $shipping_id . '_cost' );

	$cost = $destination_cost["{$origin}_to_{$destination}_with_{$service}"] ?? false;

	if ( $cost ) {
		return $cost;
	}

	$apikey = lwc_get_settings( 'shipping', 'apikey' ) ?? '';

	$header = [
		'content-type' => 'application/json',
		'key'          => $apikey,
	];

	$body = [
		'origin'      => $origin,
		'destination' => $destination,
		'weight'      => $weight,
		'courier'     => $shipping_id,
	];

	$options = [
		'body'    => wp_json_encode( $body ),
		'headers' => $header,
	];

	$request  = wp_remote_post( 'https://api.rajaongkir.com/starter/cost', $options );
	$response = json_decode( wp_remote_retrieve_body( $request ) );
	$costs    = $response->rajaongkir->results[0]->costs;
	$index    = array_search( $service, array_column( $costs, 'service' ) );

	$cost            = $costs[ $index ]->cost[0]->value;
	$estimation_date = $costs[ $index ]->cost[0]->etd;

	// Push new destination to cache
	$destination_cost["{$origin}_to_{$destination}_with_{$service}"] = [
		'cost' => $cost,
		'etd'  => $estimation_date,
	];

	set_transient( $shipping_id . '_cost', $destination_cost, WEEK_IN_SECONDS );

	if ( $cost ) {
		return [
			'cost' => $cost,
			'etd'  => $estimation_date,
		];
	}

	return false;
}

/**
 * Get Product Type based on Cart UUID
 * Product Types : ['digital', 'physical']
 *
 * @param $cart_uuid
 *
 * @return array
 * @since 0.1.0
 */
function lwc_get_product_types( $cart_uuid ) {
	global $wpdb;
	$table_cart = $wpdb->prefix . 'lokuswp_carts';

	$cart_uuid = sanitize_key( $cart_uuid );
	$cart      = (array) $wpdb->get_results( "SELECT * FROM $table_cart WHERE cart_uuid='$cart_uuid'" );

	$product_types = [];

	if ( empty( $cart ) ) {
		return [];
	}

	foreach ( $cart as $item ) {
		$product_type    = get_post_meta( $item->post_id, '_product_type', true );
		$product_types[] = $product_type;
	}

	return array_unique( $product_types );
}

/**
 * Get Subtotal of Product in Cart
 *
 * @param $cart_uuid
 *
 * @return float|int
 * @since 0.1.0
 */
function lwc_get_subtotal( $cart_uuid ) {
	$cart     = lwp_get_cart_by( "cart_uuid", $cart_uuid );
	$subtotal = 0;

	if ( empty( $cart ) ) {
		return 0;
	}

	foreach ( $cart as $item ) {
		$subtotal += lwc_get_price( $item->post_id );
	}

	return $subtotal;
}