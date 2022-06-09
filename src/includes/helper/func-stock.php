<?php
/**
 * Get Stock based on ID
 *
 * @param $product_id
 *
 * @return int
 * @since 0.1.0
 */
function lwc_get_stock( $product_id ): int {
	$stock = get_post_meta( $product_id, '_stock', true );
	if ( $stock ) {
		return $stock;
	}

	return 0;
}


function lwc_get_stock_html( $product_id = null ) {
	if ( ! $product_id ) {
		$product_id = get_the_ID();
	}

	$stock      = lwc_get_stock( $product_id );
	$stock_unit = esc_attr( get_post_meta( $product_id, '_stock_unit', true ) );

	if ( $stock ) {
		return 'Stock : <span class="lwc-stock">' . $stock . ' ' . $stock_unit . '</span>';
	}

	return '';
}