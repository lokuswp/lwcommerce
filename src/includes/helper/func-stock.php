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

	$stock = lwc_get_stock( $product_id );

	if ( $stock ) {
		return 'Stock <span class="lwc-stock">' . $stock . '</span>';
	}

	return '';
}