<?php
function lwc_get_unit_price($post_id)
{
	$unit_price = get_post_meta($post_id, '_unit_price', true);
	return isset($unit_price) ? abs($unit_price) : 0;
}

function lwc_get_price_promo($post_id)
{
	$price_promo = get_post_meta($post_id, '_price_promo', true);
	return isset($price_promo) ? abs($price_promo) : 0;
}

function lwc_get_price($post_id)
{
	$price_promo = lwc_get_price_promo($post_id);
	$unit_price = lwc_get_unit_price($post_id);

	if( $price_promo == null ) {
		return $unit_price;
	}

	if ($price_promo < $unit_price) {
		return $price_promo;
	} else {
		return $unit_price;
	}
}

function lwc_get_price_html()
{	
	$post_id = get_the_ID();
	
	$unit_price = lwc_get_unit_price($post_id);
	$price_promo = lwc_get_price_promo($post_id);

	if ($unit_price == 0) {
		$html = '<span>' . __("Free", "lwcommerce") . '</span>';
	} else if ($unit_price > 0 && $price_promo == 0 ) {
		$html = '<span>' . lwp_currency_format(true, $unit_price) . '</span>';
	} else {
		$html = '<span>' . lwp_currency_format(true, $price_promo) . '</span>';
		$html .= '<small><strike>' . lwp_currency_format(true, $unit_price) . '</strike></small>';

	}
	echo ($html);
}


function lwc_get_stock()
{
}


function lwc_get_stock_html()
{
	// Get Template ELement
}

function lwc_add_to_cart_html()
{
	require LWC_PATH . 'src/templates/component/add-to-cart.php';
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
function lwc_set_settings(string $option, string $item, $value, string $sanitize = 'sanitize_text_field')
{
	$settings = get_option('lwcommerce_' . $option);

	$whitelist = ['sanitize_text_field', 'sanitize_option', 'sanitize_key', 'abs', 'esc_url_raw', 'intval', 'floatval', 'absint', 'sanitize_email'];
	if (!in_array($sanitize, $whitelist)) {
		return false;
	}

	$row          = empty($settings) ? array() : $settings;
	$row[$item] = call_user_func($sanitize, $value);
	update_option('lwcommerce_' . $option, $row);
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
function lwc_get_settings(string $option = 'general_settings', string $item, string $validator = 'esc_attr', string $fallback = null)
{

	$settings = get_option('lwcommerce_' . $option);

	// Vuln :: Function Injection -> calL_user_funct
	$whitelist = ['esc_attr', 'esc_url', 'esc_html', 'abs', 'intval', 'floatval', 'absint', 'array'];
	if (!in_array($validator, $whitelist)) {
		return null;
	}
	if (!isset($settings[$item])) {
		return $fallback ?? null;
	}
	if ($validator == 'abs' || $validator == 'intval' || $validator == 'absint') {
		$fallback = 0;
	}

	if ($validator == 'array') {
		$fallback = $fallback == null ? array() : $fallback;

		return empty($settings[$item]) ? $fallback : (array) $settings[$item];
	}

	return empty($settings[$item]) ? $fallback : call_user_func($validator, $settings[$item]);
}

/**
 * @param  string  $shipping_id
 * @param  string  $service
 * @param  string  $destination
 *
 * @return false|mixed
 */
function lwc_get_cost_rajaongkir(string $shipping_id, string $service, string $destination, string $weight)
{
	$origin           = lwc_get_settings('store', 'district', 'intval');
	$destination_cost = get_transient($shipping_id . '_cost');

	$cost = $destination_cost["{$origin}_to_{$destination}_with_{$service}"] ?? false;

	if ($cost) {
		return $cost;
	}

	$apikey = lwc_get_settings('shipping', 'apikey') ?? '';

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
		'body'    => wp_json_encode($body),
		'headers' => $header,
	];

	$request  = wp_remote_post('https://api.rajaongkir.com/starter/cost', $options);
	$response = json_decode(wp_remote_retrieve_body($request));
	$costs    = $response->rajaongkir->results[0]->costs;
	$index    = array_search($service, array_column($costs, 'service'));

	$cost            = $costs[$index]->cost[0]->value;
	$estimation_date = $costs[$index]->cost[0]->etd;

	// Push new destination to cache
	$destination_cost["{$origin}_to_{$destination}_with_{$service}"] = [
		'cost' => $cost,
		'etd'  => $estimation_date,
	];

	set_transient($shipping_id . '_cost', $destination_cost, WEEK_IN_SECONDS);

	if ($cost) {
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
function lwc_get_order_meta($id, $meta_key, $single = true)
{
	return get_metadata('lwcommerce_order', $id, $meta_key, $single);
}

/**
 * @param $id
 * @param $meta_key
 * @param $value
 *
 * @return bool|int
 */
function lwc_update_order_meta($id, $meta_key, $value = '')
{
	return update_metadata('lwcommerce_order', $id, $meta_key, $value);
}

/**
 * @param $id
 * @param $meta_key
 * @param $value
 *
 * @return false|int
 */
function lwc_add_order_meta($id, $meta_key, $value = '')
{
	return add_metadata('lwcommerce_order', $id, $meta_key, $value);
}

/**
 * @param $id
 * @param $meta_key
 *
 * @return bool
 */
function lwc_delete_order_meta($id, $meta_key = '')
{
	return delete_metadata('lwcommerce_order', $id, $meta_key);
}


function lwc_get_product_types( $cart_uuid ) {
	$cart          = lwp_get_cart_by( "cart_uuid", $cart_uuid );
	$product_types = [];

	foreach ( $cart as $item ) {
		$product_id      = $item->post_id;
		$product_type    = get_post_meta( $product_id, '_product_type', true );
		$product_types[] = $product_type;
	}

	return array_unique( $product_types );
}

function lwc_get_subtotal( $cart_uuid ) {
	$cart     = lwp_get_cart_by( "cart_uuid", $cart_uuid );
	$subtotal = 0;

	foreach ( $cart as $item ) {
		$product_id = $item->post_id;
		$subtotal   += lwc_get_price( $product_id );
	}

	return $subtotal;
}