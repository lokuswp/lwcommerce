<?php
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
function lwc_get_settings( string $option = 'general_settings', string $item, string $validator = 'esc_attr', string $fallback = null ) {

	$settings = lwp_get_option( 'lwcommerce_' . $option );

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
 * Get Product Type based on Cart UUID
 * Product Types : ['digital', 'physical']
 *
 * @param $cart_uuid
 *
 * @return array
 * @since 0.1.0
 */
//function lwc_get_product_types( $cart_uuid ) {
//	global $wpdb;
//	$table_cart = $wpdb->prefix . 'lokuswp_carts';
//
//	$cart_uuid = sanitize_key( $cart_uuid );
//	$cart      = (array) $wpdb->get_results( "SELECT * FROM $table_cart WHERE cart_uuid='$cart_uuid'" );
//
//	$product_types = [];
//
//	if ( empty( $cart ) ) {
//		return [];
//	}
//
//	foreach ( $cart as $item ) {
//		$product_types[] = get_post_meta( $item->post_id, '_product_type', true );
//	}
//
//	return array_unique( $product_types );
//}

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