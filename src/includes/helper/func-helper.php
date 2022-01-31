<?php

/**
 * Getting Price Normal of Product based on ID
 */
function lwpc_get_price( $post_id ) {
	$_price_discount = lwpc_get_price_discount( $post_id );
	if ( $_price_discount == 0 ) {
		return $_price_discount;
	}

	$_price_normal = get_post_meta( $post_id, '_price_normal', true );

	return isset( $_price_normal ) ? abs( $_price_normal ) : 0;
}

function lwpc_get_price_discount( $post_id ) {
	$_price_discount = get_post_meta( $post_id, '_price_discount', true );

	return isset( $_price_discount ) ? abs( $_price_discount ) : 0;
}

function lwpc_get_price_html() {
	$normal_price = lwpc_get_price( get_the_ID() );

	if ( $normal_price == 0 ) {
		$html = '<span style="display:block">' . __( "Free", "lwpcommerce" ) . '</span>';
	} else {
		$html = '<small style="display:block"><strike>' . lwp_currency_format( true, $normal_price ) . '</strike></small>';
		$html .= '<span style="display:block">' . lwp_currency_format( true, lwpc_get_price_discount( get_the_ID() ) ) . '</span>';
	}
	echo( $html );
}


function lwpc_get_stock() {
}


function lwpc_get_stock_html() {
	// Get Template ELement
}

function lwpc_add_to_cart_html() {
	$product_id = get_the_ID();
	require LWPC_PATH . 'src/templates/component/add-to-cart.php';
}

/**
 * Set dynamic settings with sanitize
 *
 * @param  string  $option
 * @param  string  $item
 * @param [type] $value
 * @param  string  $sanitize
 *
 * @return false|void
 */
function lwpc_set_settings( string $option, string $item, $value, string $sanitize = 'sanitize_text_field' ) {
	$settings = get_option( 'lwpcommerce_' . $option );

	$whitelist = [ 'sanitize_text_field', 'sanitize_option', 'sanitize_key', 'abs', 'esc_url_raw', 'intval', 'floatval', 'absint', 'sanitize_email' ];
	if ( ! in_array( $sanitize, $whitelist ) ) {
		return false;
	}

	$row          = empty( $settings ) ? array() : $settings;
	$row[ $item ] = call_user_func( $sanitize, $value );
	update_option( 'lwpcommerce_' . $option, $row );
}

/**
 * Get Dynamic Settings based on param
 *
 * @param  string  $option
 * @param  string  $item
 * @param  string  $validator
 * @param  string|null  $fallback
 *
 * @return mixed
 * @since 4.0.0
 */
function lwpc_get_settings( string $option = 'general_settings', string $item, string $validator = 'esc_attr', string $fallback = null ) {

	$settings = get_option( 'lwpcommerce_' . $option );

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
 * @param  string  $shipping_id
 * @param  string  $service
 * @param  string  $destination
 *
 * @return false|mixed
 */
function lwpc_get_cost_rajaongkir( string $shipping_id, string $service, string $destination, string $weight ) {
	$origin           = lwpc_get_settings( 'store', 'district', 'intval' );
	$destination_cost = get_transient( $shipping_id . '_cost' );

	$cost = $destination_cost["{$origin}_to_{$destination}_with_{$service}"] ?? false;

	if ( $cost ) {
		return $cost;
	}

	$apikey = lwpc_get_settings( 'shipping', 'apikey' ) ?? '';

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
 * @param $id
 * @param $meta_key
 * @param $single
 *
 * @return array|false|mixed
 */
function lwpc_get_order_meta( $id, $meta_key, $single = true ) {
	return get_metadata( 'lwpcommerce_order', $id, $meta_key, $single );
}

/**
 * @param $id
 * @param $meta_key
 * @param $value
 *
 * @return bool|int
 */
function lwpc_update_order_meta( $id, $meta_key, $value = '' ) {
	return update_metadata( 'lwpcommerce_order', $id, $meta_key, $value );
}

/**
 * @param $id
 * @param $meta_key
 * @param $value
 *
 * @return false|int
 */
function lwpc_add_order_meta( $id, $meta_key, $value = '' ) {
	return add_metadata( 'lwpcommerce_order', $id, $meta_key, $value );
}

/**
 * @param $id
 * @param $meta_key
 *
 * @return bool
 */
function lwpc_delete_order_meta( $id, $meta_key = '' ) {
	return delete_metadata( 'lwpcommerce_order', $id, $meta_key );
}