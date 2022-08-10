<?php
/**
 * Get Unit Price based on ID
 *
 * @param $product_id
 *
 * @return float|int
 * @since 0.1.0
 */
function lwc_get_unit_price( $product_id = null ) {
	if ( ! $product_id ) {
		$product_id = get_the_ID();
	}

	$unit_price = get_post_meta( $product_id, '_unit_price', true );

	return isset( $unit_price ) ? abs( $unit_price ) : 0;
}

/**
 * Get Price Promo based on ID
 *
 * @param int $product_id
 *
 * @return float|int
 * @since 0.1.0
 */
function lwc_get_price_promo( int $product_id ) {
	if ( ! $product_id ) {
		$product_id = get_the_ID();
	}

	$price_promo = get_post_meta( $product_id, '_price_promo', true );

	return isset( $price_promo ) ? abs( $price_promo ) : 0;
}

/**
 * Get Right Price between Unit Price and Price Promo
 * based on ID
 *
 * @param int $product_id
 * @param string $currency
 *
 * @return float|int
 * @since 0.1.0
 */
function lwc_get_price( int $product_id, string $currency = "IDR" ) {
	if ( ! $product_id ) {
		$product_id = get_the_ID();
	}

	$price_promo = lwc_get_price_promo( $product_id );
	$unit_price  = lwc_get_unit_price( $product_id );

	$price = 0;

	if ( empty( $price_promo ) ) {
		$price = $unit_price;
	}

	if ( $price_promo < $unit_price && ! empty( $price_promo ) ) {
		$price = $price_promo;
	} else {
		$price = $unit_price;
	}

	return $price;
}

/**
 * Get Price and Currency Formatting
 * with HTML Format based on ID
 *
 * @param $product_id
 *
 * @return string
 * @since 0.1.0
 */
function lwc_get_price_html( $product_id = null ) {
	if ( ! $product_id ) {
		$product_id = get_the_ID();
	}

	$unit_price  = lwc_get_unit_price( $product_id );
	$price_promo = lwc_get_price_promo( $product_id );

	if ( $unit_price == 0 ) {
		// Free Format
		$html = '<span>' . __( "Free", "lwcommerce" ) . '</span>';
	} else if ( $unit_price > 0 && $price_promo == 0 ) {
		// Normal Format
		$html = '<span>' . lwp_currency_format( true, $unit_price ) . '</span>';
	} else {
		// Sale Format
		$html = '<span>' . lwp_currency_format( true, $price_promo ) . '</span>';
		$html .= '<small><strike>' . lwp_currency_format( true, $unit_price ) . '</strike></small>';
	}

	return $html;
}

